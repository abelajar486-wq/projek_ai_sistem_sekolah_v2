<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $document['title'] }}</title>
    <style>
        @page { margin: 32px 40px; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #111827; line-height: 1.55; }
        .doc-title { font-size: 20px; font-weight: bold; text-align: center; margin: 0 0 4px; text-transform: uppercase; }
        .doc-subtitle { font-size: 12px; text-align: center; color: #6b7280; margin: 0 0 16px; letter-spacing: 1px; text-transform: uppercase; }
        .meta { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .meta td { padding: 2px 0; font-size: 11.5px; vertical-align: top; }
        .meta td.label { width: 150px; color: #6b7280; }
        .meta td.value { font-weight: bold; }
        .divider { border-bottom: 1.5px solid #111827; margin: 10px 0 18px; }
        .section { margin-bottom: 18px; }
        .section h2 { font-size: 14px; margin: 0 0 8px; padding-bottom: 4px; border-bottom: 1px solid #d1d5db; }
        .content { font-size: 12px; }
        .content h1, .content h2, .content h3 { font-size: 13px; margin: 12px 0 6px; }
        .content p { margin: 0 0 8px; }
        .content ul, .content ol { margin: 0 0 8px 18px; padding: 0; }
        .content li { margin-bottom: 3px; }
        .content code { background: #f3f4f6; padding: 1px 3px; font-size: 11px; }
        .content pre { background: #f3f4f6; padding: 8px; border: 1px solid #e5e7eb; font-size: 11px; white-space: pre-wrap; }
        .content blockquote { border-left: 3px solid #9ca3af; margin: 0 0 8px; padding-left: 10px; color: #4b5563; }
        .content table { border-collapse: collapse; width: 100%; margin-bottom: 8px; }
        .content th, .content td { border: 1px solid #d1d5db; padding: 4px 6px; font-size: 11px; }
        .footer { position: fixed; bottom: -18px; left: 0; right: 0; text-align: center; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="footer">Dokumen dibuat otomatis oleh EduBot AI &bull; {{ $generatedAt->format('d/m/Y H:i') }}</div>

    <div class="doc-title">{{ $document['title'] }}</div>

    @if(! empty($document['subtitle']))
        <div class="doc-subtitle">{{ $document['subtitle'] }}</div>
    @endif

    @if(! empty($document['meta']))
        <table class="meta">
            @foreach($document['meta'] as $label => $value)
                <tr>
                    <td class="label">{{ $label }}</td>
                    <td class="value">: {{ $value }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <div class="divider"></div>

    @foreach($sections as $section)
        <div class="section">
            @if(! empty($section['heading']))
                <h2>{{ $section['heading'] }}</h2>
            @endif
            <div class="content">{!! $section['html'] !!}</div>
        </div>
    @endforeach
</body>
</html>
