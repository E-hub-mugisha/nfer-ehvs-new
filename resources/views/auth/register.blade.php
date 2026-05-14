<x-guest-layout>
    <x-slot name="pageTitle">Create Account</x-slot>
    <x-slot name="pageSubtitle">Join thousands of employers and employees on Rwanda's national employment verification platform.</x-slot>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Full Name --}}
        <div class="auth-field">
            <x-input-label for="name" :value="__('Full Name')" />
            <div class="input-icon-wrap">
                <i class="bi bi-person input-icon"></i>
                <x-text-input
                    id="name"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="First and last name"
                />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

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
                    autocomplete="username"
                    placeholder="you@example.com"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div class="auth-field">
            <x-input-label for="password" :value="__('Password')" />
            <div class="input-icon-wrap">
                <i class="bi bi-lock input-icon"></i>
                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Min. 8 characters"
                    style="padding-right:44px!important;"
                />
                <button type="button" class="toggle-pass" tabindex="-1" aria-label="Toggle password visibility">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            {{-- Strength indicator --}}
            <div style="display:flex;gap:4px;margin-top:8px;" id="strength-bars">
                <div style="height:3px;flex:1;background:#dce5f0;border-radius:2px;" id="sb1"></div>
                <div style="height:3px;flex:1;background:#dce5f0;border-radius:2px;" id="sb2"></div>
                <div style="height:3px;flex:1;background:#dce5f0;border-radius:2px;" id="sb3"></div>
                <div style="height:3px;flex:1;background:#dce5f0;border-radius:2px;" id="sb4"></div>
            </div>
            <p style="font-size:11px;color:#b4c3d6;margin-top:5px;" id="strength-label">Use letters, numbers & symbols</p>
        </div>

        {{-- Confirm Password --}}
        <div class="auth-field">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="input-icon-wrap">
                <i class="bi bi-shield-lock input-icon"></i>
                <x-text-input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Re-enter your password"
                    style="padding-right:44px!important;"
                />
                <button type="button" class="toggle-pass" tabindex="-1" aria-label="Toggle confirm password visibility">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Terms note --}}
        <p style="font-size:12px;color:#b4c3d6;margin-bottom:24px;line-height:1.6;">
            By creating an account you agree to the
            <a href="#" class="auth-link">Terms of Use</a> and
            <a href="#" class="auth-link">Privacy Policy</a>
            of the NFER-EHVS platform.
        </p>

        {{-- Submit --}}
        <button type="submit" style="width:100%;">
            Create Account <i class="bi bi-arrow-right"></i>
        </button>

        {{-- Login link --}}
        <div class="auth-bottom-strip">
            Already have an account?
            <a href="{{ route('login') }}" class="auth-link">Sign in</a>
        </div>
    </form>

    <script>
        const pwInput = document.getElementById('password');
        const bars = ['sb1','sb2','sb3','sb4'].map(id => document.getElementById(id));
        const label = document.getElementById('strength-label');
        const levels = [
            { color: '#ef4444', text: 'Too weak' },
            { color: '#f97316', text: 'Weak' },
            { color: '#eab308', text: 'Fair' },
            { color: '#22c55e', text: 'Strong' },
        ];

        pwInput?.addEventListener('input', () => {
            const v = pwInput.value;
            let score = 0;
            if (v.length >= 8) score++;
            if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
            if (/\d/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;

            bars.forEach((b, i) => {
                b.style.background = i < score ? levels[Math.min(score - 1, 3)].color : '#dce5f0';
            });

            label.textContent = v.length === 0 ? 'Use letters, numbers & symbols' : levels[Math.min(score - 1, 3)]?.text || 'Too weak';
            label.style.color = v.length === 0 ? '#b4c3d6' : levels[Math.min(score - 1, 3)].color;
        });
    </script>
</x-guest-layout>