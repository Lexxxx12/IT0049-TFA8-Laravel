<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomerStatusController extends Controller
{
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $data = $request->validate(['is_active' => 'required|boolean']);
        DB::transaction(function () use ($customer, $data) {
            $customer = Customer::lockForUpdate()->findOrFail($customer->id);
            $account = $customer->account()->lockForUpdate()->first();
            if (! $account) {
                throw ValidationException::withMessages(['account' => 'Set a password in the customer edit form to enable an online account first.']);
            }$account->update(['is_active' => (bool) $data['is_active'], 'session_version' => $account->session_version + 1]);
        });

        return back()->with('status', $data['is_active'] ? 'Customer access restored. They can sign in again.' : 'Customer login deactivated. Purchase history has been preserved.');
    }

    public function restore(int $id): RedirectResponse
    {
        DB::transaction(function () use ($id) {
            $customer = Customer::onlyTrashed()->lockForUpdate()->findOrFail($id);
            $customer->restore();
            if ($account = $customer->account) {
                $account->update(['is_active' => true, 'session_version' => $account->session_version + 1]);
            }
        });

        return redirect('/customers')->with('status', 'Customer restored.');
    }
}
