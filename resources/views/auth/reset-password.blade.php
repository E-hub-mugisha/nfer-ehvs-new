<x-guest-layout>
    <x-slot name="pageTitle">Reset Password</x-slot>
    <x-slot name="pageSubtitle">Create a strong new password for your NFER-EHVS account. You'll be signed in automatically after resetting.</x-slot>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        {{-- Hidden Token --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div class="auth-field">
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="input-icon-wrap">
                <i class="bi bi-envelope input-icon"></i>
                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- New Password --}}
        <div class="auth-field">
            <x-input-label for="password" :value="__('New Password')" />
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

            {{-- Strength bars --}}
            <div style="display:flex;gap:4px;margin-top:8px;">
                <div style="height:3px;flex:1;background:#dce5f0;border-radius:2px;" id="rsb1"></div>
                <div style="height:3px;flex:1;background:#dce5f0;border-radius:2px;" id="rsb2"></div>
                <div style="height:3px;flex:1;background:#dce5f0;border-radius:2px;" id="rsb3"></div>
                <div style="height:3px;flex:1;background:#dce5f0;border-radius:2px;" id="rsb4"></div>
            </div>
            <p style="font-size:11px;color:#b4c3d6;margin-top:5px;" id="r-strength-label">Use letters, numbers & symbols</p>
        </div>

        {{-- Confirm Password --}}
        <div class="auth-field">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
            <div class="input-icon-wrap">
                <i class="bi bi-shield-lock input-icon"></i>
                <x-text-input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Re-enter your new password"
                    style="padding-right:44px!important;"
                />
                <button type="button" class="toggle-pass" tabindex="-1" aria-label="Toggle confirm password visibility">
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            {{-- Match indicator --}}
            <div id="match-indicator" style="display:none;margin-top:6px;font-size:12px;align-items:center;gap:5px;"></div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Submit --}}
        <button type="submit" style="width:100%;margin-top:8px;">
            <i class="bi bi-shield-check"></i> Reset Password
        </button>

        {{-- Back link --}}
        <div class="auth-bottom-strip">
            <a href="{{ route('login') }}" class="auth-muted-link" style="display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-arrow-left" style="font-size:13px;"></i>
                Back to Sign In
            </a>
        </div>
    </form>

    <script>
        /* ── Password strength ── */
        const pwInput   = document.getElementById('password');
        const bars      = ['rsb1','rsb2','rsb3','rsb4'].map(id => document.getElementById(id));
        const label     = document.getElementById('r-strength-label');
        const levels    = [
            { color: '#ef4444', text: 'Too weak' },
            { color: '#f97316', text: 'Weak' },
            { color: '#eab308', text: 'Fair' },
            { color: '#22c55e', text: 'Strong' },
        ];

        const getScore = v => {
            let s = 0;
            if (v.length >= 8) s++;
            if (/[A-Z]/.test(v) && /[a-z]/.test(v)) s++;
            if (/\d/.test(v)) s++;
            if (/[^A-Za-z0-9]/.test(v)) s++;
            return s;
        };

        pwInput?.addEventListener('input', () => {
            const v = pwInput.value;
            const score = getScore(v);
            bars.forEach((b, i) => {
                b.style.background = i < score ? levels[Math.min(score - 1, 3)].color : '#dce5f0';
            });
            label.textContent = v.length === 0 ? 'Use letters, numbers & symbols' : levels[Math.min(score - 1, 3)]?.text || 'Too weak';
            label.style.color = v.length === 0 ? '#b4c3d6' : levels[Math.min(score - 1, 3)].color;
            checkMatch();
        });

        /* ── Password match indicator ── */
        const confirmInput  = document.getElementById('password_confirmation');
        const matchIndicator = document.getElementById('match-indicator');

        const checkMatch = () => {
            const pw  = pwInput.value;
            const pwc = confirmInput.value;
            if (!pwc.length) { matchIndicator.style.display = 'none'; return; }
            matchIndicator.style.display = 'flex';
            if (pw === pwc) {
                matchIndicator.innerHTML = '<i class="bi bi-check-circle-fill" style="color:#22c55e;"></i> <span style="color:#22c55e;">Passwords match</span>';
            } else {
                matchIndicator.innerHTML = '<i class="bi bi-x-circle-fill" style="color:#ef4444;"></i> <span style="color:#ef4444;">Passwords do not match</span>';
            }
        };

        confirmInput?.addEventListener('input', checkMatch);
    </script>
</x-guest-layout>