<!DOCTYPE html>
<html>
<body style="margin:0;background:#f8fafc;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    <div style="max-width:560px;margin:0 auto;padding:32px 20px;font-family:Arial,Helvetica,sans-serif;">
        <div style="background:#ffffff;border-radius:16px;padding:28px;">
            <p style="margin:0 0 8px;font-size:14px;color:#2563EB;font-weight:600;">Doc2Due</p>
            <h1 style="margin:0 0 12px;font-size:22px;font-family:Arial,Helvetica,sans-serif;">{{ $document->title }}</h1>
            <p style="margin:0 0 20px;font-size:15px;line-height:1.5;color:#334155;">
                @if ($daysBefore === 0)
                    This document expires today.
                @else
                    This document expires in {{ $daysBefore }} days.
                @endif
            </p>
            <p style="margin:0;font-size:14px;line-height:1.7;color:#475569;">
                Category: {{ $document->category->name }}<br>
                Contractor: {{ $document->contractor->name ?? 'None' }}<br>
                Expiry date: {{ $document->expiry_date->format('M j, Y') }}
            </p>
            <p style="margin:24px 0 0;">
                <a href="{{ route('documents.index') }}" style="display:inline-block;background:#363bb8;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:8px;font-family:Arial,Helvetica,sans-serif;font-size:14px;">View documents</a>
            </p>
        </div>
    </div>
</body>
</html>
