<?php

namespace App\Http\Controllers;

use App\Actions\RecordSale;
use App\Http\Requests\RecordSaleRequest;
use App\Http\Requests\SaveRecordRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use League\Flysystem\FilesystemException;

class PosController extends Controller
{
    private function entity(string $kind): array
    {
        return match ($kind) {
            'products' => [Product::class, ['name', 'price', 'stock_quantity'], 'image'],
            default => abort(404),
        };
    }

    public function index(Request $request, string $kind)
    {
        [$model, $fields, $image] = $this->entity($kind);
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,archived'],
        ]);
        $status = $filters['status'] ?? 'active';
        $query = $model::query();

        if ($status === 'archived') {
            $query->onlyTrashed();
        }

        if (filled($filters['q'] ?? null)) {
            $q = $filters['q'];
            $query->where('name', 'like', '%'.$q.'%');
        }

        return view('records', compact('kind', 'fields', 'image', 'filters', 'status') + ['records' => $query->latest('id')->paginate(12)->withQueryString(), 'total' => $model::count()]);
    }

    public function form(string $kind, ?int $id = null)
    {
        [$model, $fields, $image] = $this->entity($kind);
        $record = $id ? $model::findOrFail($id) : new $model;

        return view('form', compact('kind', 'fields', 'image', 'record'));
    }

    public function save(SaveRecordRequest $r, string $kind, ?int $id = null)
    {
        [$model, $fields, $image] = $this->entity($kind);
        $record = $id ? $model::findOrFail($id) : new $model;
        $data = $r->validated();
        unset($data['version']);
        $newPath = null;
        $oldPath = $record->$image;

        try {
            if ($r->hasFile($image)) {
                $newPath = $r->image($image)->cover(800, 800)->toWebp()->storePublicly($kind, 'public');
                $data[$image] = $newPath;
            }

            DB::transaction(function () use ($record, $data, $id, $r) {
                if ($id) {
                    $locked = Product::lockForUpdate()->findOrFail($id);
                    if ($locked->version() !== $r->input('version')) {
                        throw ValidationException::withMessages(['stock_quantity' => 'This product changed. Reload this page before saving.']);
                    }
                    $locked->fill($data)->save();
                } else {
                    $record->fill($data)->save();
                }
            });
        } catch (\Throwable $e) {
            if ($newPath) {
                rescue(fn () => Storage::disk('public')->delete($newPath));
            }
            if ($e instanceof ImageException || $e instanceof FilesystemException) {
                report($e);
                throw ValidationException::withMessages([$image => 'The image could not be saved. Please try a smaller image or contact staff.']);
            }
            throw $e;
        }
        if ($newPath && $oldPath) {
            rescue(fn () => Storage::disk('public')->delete($oldPath));
        }

        return redirect()->route('records.index', ['kind' => $kind])->with('status', 'Record saved.');
    }

    public function destroy(string $kind, int $id)
    {
        [$model] = $this->entity($kind);
        DB::transaction(function () use ($model, $id) {
            $model::lockForUpdate()->findOrFail($id)->delete();
        });

        return back()->with('status', 'Product archived.');
    }

    public function saleForm(Request $r)
    {
        $token = (string) Str::uuid();
        $tokens = $r->session()->get('sale_tokens', []);
        $tokens[$token] = true;
        $r->session()->put('sale_tokens', array_slice($tokens, -30, null, true));

        return view('sale', ['products' => Product::orderBy('name')->get(), 'customers' => Customer::orderBy('full_name')->get(), 'token' => $token, 'recentSales' => Sale::with(['product', 'customer'])->latest('id')->limit(4)->get()]);
    }

    public function recordSale(RecordSaleRequest $r, RecordSale $recordSale)
    {
        $data = $r->validated();
        $existing = Sale::where('submission_token', $data['submission_token'])->where('sold_by', $r->user()->id)->first();
        if ($existing) {
            return redirect()->route('sales.index')->with('status', 'This sale was already recorded.');
        }
        if (! $r->session()->get('sale_tokens.'.$data['submission_token'])) {
            throw ValidationException::withMessages(['quantity' => 'This sale form expired. Open Record Sale again.']);
        }
        $recordSale->execute($data, $r->user()->id);

        return redirect()->route('sales.index')->with('status', 'Sale recorded. Stock updated.')->with('receipt_id', Sale::where('submission_token', $data['submission_token'])->value('id'));
    }

    public function history(Request $request)
    {
        $filters = $request->validate(['q' => 'nullable|string|max:100', 'from' => 'nullable|date_format:Y-m-d', 'to' => 'nullable|date_format:Y-m-d'.($request->filled('from') ? '|after_or_equal:from' : ''), 'product_id' => 'nullable|integer|exists:products,id', 'customer_id' => 'nullable|integer|exists:customers,id']);
        $query = Sale::with(['product', 'customer', 'staff']);
        if (filled($filters['q'] ?? null)) {
            $q = $filters['q'];
            $receiptId = preg_match('/^(?:LP-)?0*(\d+)$/i', $q, $matches) ? (int) $matches[1] : 0;
            $query->where(fn ($builder) => $builder->where('product_name', 'like', '%'.$q.'%')->orWhere('customer_name', 'like', '%'.$q.'%')->orWhere('staff_name', 'like', '%'.$q.'%')->orWhere('id', $receiptId)->orWhereHas('product', fn ($p) => $p->where('name', 'like', '%'.$q.'%'))->orWhereHas('customer', fn ($c) => $c->where('full_name', 'like', '%'.$q.'%')));
        }
        foreach (['product_id', 'customer_id'] as $key) {
            if (! empty($filters[$key])) {
                $query->where($key, $filters[$key]);
            }
        }
        if (! empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }if (! empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }
        $summary = ['count' => (clone $query)->count(), 'units' => (clone $query)->sum('quantity'), 'total' => (clone $query)->sum('total_price')];

        return view('history', ['sales' => $query->latest('id')->paginate(15)->withQueryString(), 'filters' => $filters, 'summary' => $summary, 'products' => Product::withTrashed()->orderBy('name')->get(), 'customers' => Customer::withTrashed()->orderBy('full_name')->get()]);
    }
}
