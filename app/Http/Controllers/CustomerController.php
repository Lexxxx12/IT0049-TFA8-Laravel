<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveCustomerRequest;
use App\Models\Customer;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,disabled,deleted'],
        ]);
        $status = $filters['status'] ?? 'active';
        $query = Customer::query()->with('account');

        if ($status === 'deleted') {
            $query->onlyTrashed();
        } elseif ($status === 'disabled') {
            $query->whereHas('account', fn ($account) => $account->where('is_active', false));
        }

        if (filled($filters['q'] ?? null)) {
            $query->where(function ($builder) use ($filters) {
                foreach (['full_name', 'email', 'phone'] as $column) {
                    $builder->orWhere($column, 'like', '%'.$filters['q'].'%');
                }
            });
        }

        return view('records', [
            'kind' => 'customers',
            'fields' => ['full_name', 'email', 'phone', 'password'],
            'image' => null,
            'filters' => $filters,
            'status' => $status,
            'records' => $query->latest('id')->paginate(12)->withQueryString(),
            'total' => Customer::count(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Customer);
    }

    public function store(SaveCustomerRequest $request): RedirectResponse
    {
        $customer = new Customer;
        $this->saveCustomer($customer, $request->validated());

        return redirect()->route('customers.index')->with('status', 'Customer saved.');
    }

    public function show(Customer $customer): RedirectResponse
    {
        return redirect()->route('customers.edit', $customer);
    }

    public function edit(Customer $customer): View
    {
        return $this->form($customer);
    }

    public function update(SaveCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $this->saveCustomer($customer, $request->validated());

        return redirect()->route('customers.index')->with('status', 'Customer saved.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        DB::transaction(function () use ($customer) {
            $lockedCustomer = Customer::query()->lockForUpdate()->findOrFail($customer->id);
            $account = $lockedCustomer->account()->lockForUpdate()->first();

            if ($account) {
                $account->update([
                    'is_active' => false,
                    'session_version' => $account->session_version + 1,
                ]);
            }

            $lockedCustomer->delete();
        });

        return back()->with('status', 'Customer deleted.');
    }

    private function form(Customer $customer): View
    {
        return view('form', [
            'kind' => 'customers',
            'fields' => ['full_name', 'email', 'phone', 'password'],
            'image' => null,
            'record' => $customer,
        ]);
    }

    private function saveCustomer(Customer $customer, array $data): void
    {
        try {
            DB::transaction(function () use ($customer, $data) {
                $attributes = $data;
                unset($attributes['password']);
                $customer->fill($attributes)->save();

                $account = $customer->account;
                if ($account) {
                    $account->email = $data['email'];
                    if (! empty($data['password'])) {
                        $account->password = $data['password'];
                        $account->session_version++;
                    }
                    $account->save();
                } elseif (! empty($data['password'])) {
                    $customer->account()->create([
                        'email' => $data['email'],
                        'password' => $data['password'],
                    ]);
                }
            });
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'email' => 'That login identifier is already in use.',
            ]);
        }
    }
}
