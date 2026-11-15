@extends('layout',['title'=>'Customer login','authPage'=>true])
@section('content')
<div class="auth-heading"><span class="auth-symbol"><x-icon name="bag" :size="25"/></span><h1>Customer sign in</h1><p>Shop, track orders, and view your purchases and receipts.</p></div>
<form method="post" action="{{ route('customer.login.store') }}" class="auth-form" data-submit>@csrf
<label for="email">Email</label><input id="email" type="email" name="email" value="{{ \App\Support\OldInput::value('email') }}" autocomplete="username" placeholder="you@example.com" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror><x-input-error field="email"/>
<label for="password">Password</label><div class="password-field"><input id="password" type="password" name="password" autocomplete="current-password" placeholder="Enter your password" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror><button type="button" data-password-toggle="password" aria-label="Show password"><x-icon name="eye" :size="19"/></button></div><x-input-error field="password"/>
<button type="submit" class="button primary full-width">Customer sign in</button>
</form><div class="auth-switch"><span>Need an account?</span><a href="{{ route('customer.register') }}">Create an account</a></div><p class="auth-small-print"><x-icon name="lock" :size="14"/> Need help signing in? Contact our staff.</p>
@endsection
