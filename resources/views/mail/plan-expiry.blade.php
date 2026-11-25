<!DOCTYPE html>
<html>
<body style="margin:0;background:#f8fafc;font-family:Arial,Helvetica,sans-serif;color:#1F2937;">
    <div style="max-width:560px;margin:0 auto;padding:32px 20px;font-family:Arial,Helvetica,sans-serif;">
        <div style="background:#ffffff;border-radius:16px;padding:28px;">
            <p style="margin:0 0 8px;font-size:14px;color:#1E7FA8;font-weight:600;">Doc2Due</p>
            <h1 style="margin:0 0 12px;font-size:22px;font-family:Arial,Helvetica,sans-serif;">{{ $company->plan->name }} expires in {{ $daysBefore }} days</h1>
            <p style="margin:0;font-size:15px;line-height:1.6;color:#334155;">
                {{ $company->name }} is on the {{ $company->plan->name }} plan until {{ $company->plan_expires_at->format('M j, Y') }}.
                Renew from the billing page to keep your document limit. Nothing is deleted if the plan lapses.
            </p>
            <p style="margin:24px 0 0;">
                <a href="{{ route('billing.index') }}" style="display:inline-block;background:#363bb8;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:8px;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Review billing</a>
            </p>
        </div>
    </div>
</body>
</html>
