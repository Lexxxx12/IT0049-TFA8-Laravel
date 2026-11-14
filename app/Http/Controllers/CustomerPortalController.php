<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCustomerPasswordRequest;
use App\Http\Requests\UpdateCustomerProfileRequest;
use App\Models\Customer;
use App\Models\CustomerAccount;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerPortalController extends Controller
{
    public function update(UpdateCustomerProfileRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            Customer::whereKey($request->user('customer')->customer_id)->lockForUpdate()->firstOrFail()->update($request->validated());
        });

        return redirect()->route('customer.account')->with('status', 'Your profile has been updated.');
    }

    public function password(UpdateCustomerPasswordRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $version = DB::transaction(function () use ($request, $data) {
            $account = CustomerAccount::lockForUpdate()->findOrFail($request->user('customer')->id);
            if (! Hash::check($data['current_password'], $account->password)) {
                throw ValidationException::withMessages(['current_password' => 'Your password changed. Please enter your current password again.']);
            }
            $account->password = $data['password'];
            $account->session_version++;
            $account->save();

            return $account->session_version;
        });
        $request->session()->regenerate();
        $request->session()->put('customer_auth_version', $version);

        return redirect()->route('customer.account')->with('status', 'Your password has been changed. Other sessions have been signed out.');
    }

    public function purchases(Request $request): View
    {
        $filters = $request->validate(['q' => 'nullable|string|max:100', 'from' => 'nullable|date_format:Y-m-d', 'to' => 'nullable|date_format:Y-m-d'.($request->filled('from') ? '|after_or_equal:from' : '')]);
        $query = Sale::where('customer_id', $request->user('customer')->customer_id)->with(['product', 'staff']);
        if (filled($filters['q'] ?? null)) {
            $q = $filters['q'];
            $receiptId = preg_match('/^(?:LP-)?0*(\d+)$/i', $q, $matches) ? (int) $matches[1] : 0;
            $query->where(fn ($builder) => $builder->where('product_name', 'like', '%'.$q.'%')->orWhereHas('product', fn ($product) => $product->where('name', 'like', '%'.$q.'%'))->orWhere('id', $receiptId));
        }
        if (! empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }if (! empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        return view('customer-purchases', ['sales' => $query->latest('id')->paginate(12)->withQueryString(), 'filters' => $filters]);
    }
}
