<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Procesos</title>
    <link type="text/css" href="{{ mix('css/app.css').config('app.version') }}" rel="stylesheet">
</head>
<body>
<div id="q-app" v-cloak></div>
<script type="text/javascript" src="{{ mix('js/app.js').config('app.version') }}"></script>
</body>
</html>
