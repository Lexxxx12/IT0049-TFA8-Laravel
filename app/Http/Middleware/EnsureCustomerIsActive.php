<?php

namespace App\Http\Middleware;

use App\Support\CustomerSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerIsActive
{
    public function __construct(private CustomerSession $customerSession) {}

    public function handle(Request $request, Closure $next): Response
    {
        $account = $request->user('customer')?->fresh();
        $version = $request->session()->get('customer_auth_version', 1);
        if (! $account || ! $account->is_active || ! $account->customer()->exists() || (int) $version !== $account->session_version) {
            $this->customerSession->invalidate($request);

            return redirect()->route('customer.login')->withErrors(['email' => 'Your customer account is unavailable or your session expired. Please sign in again or contact staff.']);
        }
        $request->session()->put('customer_auth_version', $account->session_version);

        return $next($request);
    }
}
