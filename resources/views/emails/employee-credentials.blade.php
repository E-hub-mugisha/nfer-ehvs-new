{{-- resources/views/emails/employee-credentials.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .card { background: #fff; max-width: 560px; margin: auto; border-radius: 8px;
                padding: 36px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .label { color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        .value { font-size: 16px; font-weight: bold; color: #111827; margin-bottom: 16px; }
        .badge { background: #1e3a8a; color: #fff; padding: 10px 20px;
                 border-radius: 6px; font-size: 20px; letter-spacing: 2px; display: inline-block; }
        .footer { text-align: center; color: #9ca3af; font-size: 12px; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="color:#1e3a8a;margin-top:0;">Welcome to NFER-EHVS</h2>
        <p>Hello <strong>{{ $user->name }}</strong>,</p>
        <p>Your employee account has been created. Use the credentials below to log in to the portal.</p>
        <hr style="border:none;border-top:1px solid #e5e7eb;margin:24px 0;">
        <p class="label">Email Address</p>
        <p class="value">{{ $user->email }}</p>
        <p class="label">Temporary Password</p>
        <div class="badge">{{ $plainPassword }}</div>
        <p style="margin-top:16px;color:#6b7280;font-size:13px;">
            Please log in and change your password immediately.
        </p>
        <a href="{{ url('/login') }}"
           style="display:inline-block;margin-top:16px;background:#1e3a8a;color:#fff;
                  padding:10px 24px;border-radius:6px;text-decoration:none;">
            Go to Login
        </a>
        <div class="footer">
            &copy; {{ date('Y') }} NFER-EHVS &mdash; National Formal Employment Registration System
        </div>
    </div>
</body>
</html>