@extends('layout',['title'=>'Staff login','authPage'=>true])
@section('content')
<div class="auth-heading"><span class="auth-symbol"><x-icon name="staff" :size="25"/></span><h1>Staff sign in</h1><p>Use your staff username and password.</p></div>
<form method="post" action="{{ route('login.store') }}" class="auth-form" data-submit>@csrf
<label for="username">Username</label><input id="username" name="username" value="{{ \App\Support\OldInput::value('username') }}" autocomplete="username" placeholder="Your staff username" required @error('username') aria-invalid="true" aria-describedby="username-error" @enderror><x-input-error field="username"/>
<label for="password">Password</label><div class="password-field"><input id="password" type="password" name="password" autocomplete="current-password" placeholder="Enter your password" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror><button type="button" data-password-toggle="password" aria-label="Show password"><x-icon name="eye" :size="19"/></button></div><x-input-error field="password"/>
<button type="submit" class="button primary full-width">Sign in to workspace</button>
</form><div class="auth-switch"><span>New staff member?</span><a href="{{ route('register') }}">Create an account</a></div><p class="auth-small-print"><x-icon name="lock" :size="14"/> Authentication is protected by Laravel Breeze and session security.</p>
@endsection
