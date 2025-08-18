<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ __('auth.reset_password_subject') }}</title>
</head>

<body>
    <div class="container">
        <h2>{{ __('auth.reset_password_subject') }}</h2>

        <p>{{ __('auth.reset_password_line_1') }}</p>

        <p>
            <a href="{{ $url }}" class="button">
                {{ __('auth.reset_password_button') }}
            </a>
        </p>

        <p>{{ __('auth.reset_password_line_2') }}</p>

        <p>{{ __('auth.thanks') }},<br>{{ config('app.name') }}</p>
    </div>
</body>

</html>
