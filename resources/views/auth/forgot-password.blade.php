<x-guest-layout>
    <x-slot name="pageTitle">Forgot Password?</x-slot>
    <x-slot name="pageSubtitle">No worries. Enter your registered email and we'll send you a secure reset link within minutes.</x-slot>

    {{-- Info tip box --}}
    <div style="background:rgba(212,148,58,0.08);border:1px solid rgba(212,148,58,0.22);border-radius:12px;padding:14px 16px;margin-bottom:28px;display:flex;align-items:flex-start;gap:10px;">
        <i class="bi bi-info-circle" style="color:var(--gold);font-size:16px;flex-shrink:0;margin-top:1px;"></i>
        <p style="font-size:13px;color:#7a5c1e;margin:0;line-height:1.6;">
            The reset link will expire in <strong>60 minutes</strong>. Check your spam folder if you don't see it in your inbox.
        </p>
    </div>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="auth-status-box">
            <i class="bi bi-check-circle-fill" style="flex-shrink:0;margin-top:1px;"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
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
                    placeholder="you@example.com"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Submit --}}
        <button type="submit" style="width:100%;margin-top:8px;">
            <i class="bi bi-send"></i> Send Reset Link
        </button>

        {{-- Back to login --}}
        <div class="auth-bottom-strip">
            <a href="{{ route('login') }}" class="auth-muted-link" style="display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-arrow-left" style="font-size:13px;"></i>
                Back to Sign In
            </a>
        </div>
    </form>
</x-guest-layout>