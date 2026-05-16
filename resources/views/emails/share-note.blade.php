<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note Shared</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }

        .container {
            max-width: 640px;
            margin: 40px auto;
            background: white;
            border: 1px solid #e5e7eb;
        }

        .header {
            background: #2563eb;
            color: white;
            padding: 32px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 32px;
        }

        .note-box {
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            padding: 20px;
            margin-top: 24px;
        }

        .label {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .value {
            font-size: 15px;
            margin-bottom: 20px;
        }

        .preview {
            background: white;
            border-left: 4px solid #2563eb;
            padding: 16px;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .button-wrapper {
            margin-top: 32px;
            text-align: center;
        }

        .button {
            display: inline-block;
            background: #2563eb;
            color: white !important;
            text-decoration: none;
            padding: 14px 28px;
            font-weight: bold;
        }

        .footer {
            padding: 24px;
            text-align: center;
            background: #f9fafb;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Note Shared With You</h1>
    </div>

    <div class="content">

        <p>Hello,</p>

        <p>
            <strong>{{ $senderName }}</strong>
            has shared a note with you.
        </p>

        <div class="note-box">

            <div class="label">Note Title</div>
            <div class="value">
                {{ $note->title }}
            </div>

            <div class="label">Preview</div>

            <div class="preview">
                {!! \Illuminate\Support\Str::limit(strip_tags($note->content), 500) !!}
            </div>

        </div>

        <div class="button-wrapper">
            <a href="{{ $noteUrl }}" class="button">
                OPEN NOTE
            </a>
        </div>

    </div>

    <div class="footer">
        {{ config('app.name') }} <br>
        This is an automated email.
    </div>

</div>

</body>
</html>