<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ __('auth.reset_password_subject') }}</title>
</head>

<body>
    <h2>{{ __('auth.reset_password_subject') }}</h2>

    <p>{{ __('auth.reset_password_line_1') }}</p>

    <p>
        <a href="{{ $url }}"
            style="display:inline-block;padding:10px 20px;background:#4CAF50;color:white;text-decoration:none;border-radius:5px;">
            {{ __('auth.reset_password_button') }}
        </a>
    </p>

    <p>{{ __('auth.reset_password_line_2') }}</p>

    <p>{{ __('auth.thanks') }},<br>{{ config('app.name') }}</p>
</body>

</html>
