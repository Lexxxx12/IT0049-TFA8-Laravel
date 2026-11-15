<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterCustomerRequest;
use App\Models\Customer;
use App\Rules\PasswordBytes;
use App\Support\CustomerSession;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    public function registration(): View
    {
        return view('customer-register');
    }

    public function register(RegisterCustomerRequest $request): RedirectResponse
    {
        $data = $request->validated();
        try {
            $account = DB::transaction(function () use ($data) {
                $customer = Customer::create(collect($data)->only(['full_name', 'email', 'phone'])->all());

                return $customer->account()->create(['email' => $data['email'], 'password' => $data['password']]);
            });
        } catch (UniqueConstraintViolationException $exception) {
            throw ValidationException::withMessages(['email' => 'This email already has an account. Please sign in or contact staff.']);
        }
        Auth::guard('customer')->login($account);
        $request->session()->regenerate();
        $request->session()->put('customer_auth_version', Auth::guard('customer')->user()->session_version);

        return redirect()->route('customer.account')->with('status', 'Your customer account is ready.');
    }

    public function loginForm(): View
    {
        return view('customer-login');
    }

    public function login(Request $request): RedirectResponse
    {
        if (is_string($request->input('email'))) {
            $request->merge(['email' => mb_strtolower(trim($request->input('email')))]);
        }
        $data = $request->validate(['email' => 'required|email|max:100', 'password' => ['required', 'string', new PasswordBytes]]);
        $key = 'customer-login|'.$data['email'].'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages(['email' => 'Too many attempts. Try again in '.RateLimiter::availableIn($key).' seconds.']);
        }
        if (! Auth::guard('customer')->attempt($data + ['is_active' => true, 'customer' => fn ($query) => $query->whereHas('customer')])) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages(['email' => 'The email or password is incorrect, or the account is unavailable.']);
        }
        RateLimiter::clear($key);
        $request->session()->regenerate();
        $request->session()->put('customer_auth_version', Auth::guard('customer')->user()->session_version);

        return redirect()->route('customer.account');
    }

    public function account(Request $request): View
    {
        return view('customer-account', ['customer' => $request->user('customer')->customer()->firstOrFail()]);
    }

    public function logout(Request $request, CustomerSession $customerSession): RedirectResponse
    {
        $customerSession->invalidate($request);

        return redirect()->route('customer.login');
    }
}
