<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $account = $request->user('customer')?->fresh();
        $version = $request->session()->get('customer_auth_version', 1);
        if (! $account || ! $account->is_active || ! $account->customer()->exists() || (int) $version !== $account->session_version) {
            Auth::guard('customer')->logout();
            $request->session()->forget('customer_auth_version');
            $request->session()->regenerate();

            return redirect()->route('customer.login')->withErrors(['email' => 'Your customer account is unavailable or your session expired. Please sign in again or contact staff.']);
        }
        $request->session()->put('customer_auth_version', $account->session_version);

        return $next($request);
    }
}
