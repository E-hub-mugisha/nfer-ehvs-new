<x-guest-layout>
    <x-slot name="pageTitle">Welcome Back</x-slot>
    <x-slot name="pageSubtitle">Sign in to your NFER-EHVS account to access the employment verification portal.</x-slot>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="auth-status-box">
            <i class="bi bi-check-circle-fill" style="flex-shrink:0;margin-top:1px;"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="auth-field">
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="input-icon-wrap">
                <i class="bi bi-envelope input-icon"></i>
                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div class="auth-field">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link" style="font-size:12px;text-transform:none;letter-spacing:0;">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="input-icon-wrap">
                <i class="bi bi-lock input-icon"></i>
                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                    style="padding-right:44px!important;"
                />
                <button type="button" class="toggle-pass" tabindex="-1" aria-label="Toggle password visibility">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Remember Me --}}
        <div class="auth-field" style="margin-bottom:28px;">
            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                >
                <span class="check-label">Keep me signed in for 30 days</span>
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit" style="width:100%;">
            Sign In <i class="bi bi-arrow-right"></i>
        </button>

        {{-- Register link --}}
        <div class="auth-bottom-strip">
            Don't have an account?
            <a href="{{ route('register') }}" class="auth-link">Create one free</a>
        </div>
    </form>
</x-guest-layout>