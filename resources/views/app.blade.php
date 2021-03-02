<!doctype html>
<html lang=”fr”>
<head>
    <meta charset=”utf-8">
    <meta http-equiv=”X-UA-Compatible” content=”IE=edge”>
    <meta name=”viewport” content=”width=device-width, initial-scale=1">
    <meta name="viewport" content="maximum-scale=1">


    <!-- csrf token -->
    <meta name=”csrf-token” content=”{{ csrf_token() }}”>
    <title>BCFD - Intranet</title>

    <!-- styles -->

    <link href=”{{ asset('css/app.css') }}” rel=”stylesheet”>
</head>
<body data-root-url={{ asset('') }}>
<div id="app"></div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
