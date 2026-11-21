@extends('layout',['title'=>'Staff registration','authPage'=>true])
@section('content')
<div class="auth-heading"><span class="auth-symbol"><x-icon name="staff" :size="25"/></span><h1>Create a staff account</h1><p>Register a secure account for the POS workspace.</p></div>
<form method="post" action="{{ route('register.store') }}" class="auth-form registration-form" data-submit>@csrf
<label for="full_name">Full name</label><input id="full_name" name="full_name" value="{{ \App\Support\OldInput::value('full_name') }}" autocomplete="name" placeholder="Your full name" required @error('full_name') aria-invalid="true" aria-describedby="full_name-error" @enderror><x-input-error field="full_name"/>
<label for="username">Username</label><input id="username" name="username" value="{{ \App\Support\OldInput::value('username') }}" autocomplete="username" placeholder="Choose a username" required @error('username') aria-invalid="true" aria-describedby="username-error" @enderror><x-input-error field="username"/>
<label for="password">Password</label><div class="password-field"><input id="password" type="password" name="password" autocomplete="new-password" placeholder="At least 8 characters" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror><button type="button" data-password-toggle="password" aria-label="Show password"><x-icon name="eye" :size="19"/></button></div><x-input-error field="password"/>
<label for="password_confirmation">Confirm password</label><input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Repeat your password" required>
<button type="submit" class="button primary full-width">Create staff account</button>
</form><div class="auth-switch"><span>Already registered?</span><a href="{{ route('login') }}">Sign in</a></div><p class="auth-small-print"><x-icon name="lock" :size="14"/> Passwords are securely hashed by Laravel.</p>
@endsection
