@php
    $brand = config('app.name', 'Nexora ERP');
    $supportEmail = config('mail.from.address', 'bildirim@nexoraerp.com.tr');
@endphp

<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? $brand }}</title>
</head>
<body style="margin:0;background:#f3f6fb;color:#172033;font-family:Arial,Helvetica,sans-serif;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">
        {{ $preheader ?? 'Nexora ERP bildirimi' }}
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6fb;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;background:#ffffff;border:1px solid #e4e9f2;border-radius:18px;overflow:hidden;box-shadow:0 24px 70px rgba(15,23,42,.08);">
                    <tr>
                        <td style="background:#101827;padding:30px 34px;">
                            <div style="font-size:28px;letter-spacing:9px;font-weight:800;color:#ffffff;line-height:1;">
                                NE<span style="color:#2563eb;">X</span>ORA
                            </div>
                            <div style="margin-top:9px;font-size:11px;letter-spacing:6px;color:#9aa7bd;">
                                ERP & MUHASEBE
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:34px;">
                            @isset($eyebrow)
                                <div style="display:inline-block;margin-bottom:16px;padding:8px 12px;border-radius:999px;background:#eff6ff;color:#1d4ed8;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;">
                                    {{ $eyebrow }}
                                </div>
                            @endisset

                            <h1 style="margin:0;color:#101827;font-size:30px;line-height:1.18;font-weight:800;">
                                {{ $title ?? 'Nexora ERP bildirimi' }}
                            </h1>

                            @isset($intro)
                                <p style="margin:18px 0 0;color:#526077;font-size:16px;line-height:1.75;">
                                    {{ $intro }}
                                </p>
                            @endisset

                            @if(! empty($buttonText) && ! empty($buttonUrl))
                                <table role="presentation" cellpadding="0" cellspacing="0" style="margin-top:28px;">
                                    <tr>
                                        <td style="border-radius:12px;background:#2563eb;">
                                            <a href="{{ $buttonUrl }}" style="display:inline-block;padding:15px 22px;color:#ffffff;font-size:15px;font-weight:800;text-decoration:none;">
                                                {{ $buttonText }}
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            @if(! empty($sections))
                                <div style="margin-top:30px;">
                                    @foreach($sections as $section)
                                        <div style="margin-top:14px;padding:18px;border:1px solid #e8edf5;border-radius:14px;background:#f8fafc;">
                                            <div style="color:#101827;font-size:15px;font-weight:800;">
                                                {{ $section['title'] ?? '' }}
                                            </div>
                                            <div style="margin-top:8px;color:#5b677d;font-size:14px;line-height:1.7;">
                                                {{ $section['body'] ?? '' }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @isset($note)
                                <div style="margin-top:28px;padding:16px;border-left:4px solid #2563eb;background:#eff6ff;border-radius:12px;color:#385074;font-size:14px;line-height:1.7;">
                                    {{ $note }}
                                </div>
                            @endisset

                            @if(! empty($buttonUrl))
                                <p style="margin:24px 0 0;color:#7a8598;font-size:12px;line-height:1.7;">
                                    Buton çalışmazsa bağlantıyı tarayıcınıza yapıştırabilirsiniz:<br>
                                    <a href="{{ $buttonUrl }}" style="color:#2563eb;word-break:break-all;">{{ $buttonUrl }}</a>
                                </p>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td style="border-top:1px solid #e8edf5;background:#f8fafc;padding:22px 34px;">
                            <p style="margin:0;color:#526077;font-size:13px;line-height:1.7;">
                                Bu e-posta Nexora ERP sistem bildirimi olarak gönderilmiştir.
                                Destek için <a href="mailto:{{ $supportEmail }}" style="color:#2563eb;text-decoration:none;">{{ $supportEmail }}</a> adresinden bize ulaşabilirsiniz.
                            </p>
                            <p style="margin:12px 0 0;color:#9aa7bd;font-size:12px;">
                                © {{ now()->year }} Nexora ERP. Tüm hakları saklıdır.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
