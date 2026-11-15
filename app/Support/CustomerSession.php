<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class CustomerSession
{
    public function invalidate(Request $request): void
    {
        $staff = Auth::guard('web')->user();

        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($staff) {
            Auth::guard('web')->login($staff);
        }
    }
}
