<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('auth.verify_email_subject') }}</title>
</head>

<body>
    <h1>{{ __('auth.hello') }}, {{ $name }}</h1>
    <p>{{ __('auth.verify_email_body') }}</p>
    <a href="{{ $verifyUrl }}">{{ __('auth.verify_account') }}</a>
</body>

</html>
