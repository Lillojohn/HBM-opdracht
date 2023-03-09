<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Verificatie Mail</title>
</head>
<body class="antialiased">
<div>
    <p>Verificatie link voor het account: {{ $verificationLink }}</p>
</div>
</body>
</html>
