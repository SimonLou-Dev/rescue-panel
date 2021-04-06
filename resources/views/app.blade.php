<!doctype html>
<html lang=”fr”>
<head>
    <meta charset=”utf-8">
    <meta http-equiv=”X-UA-Compatible” content=”IE=edge”>
    <meta name=”viewport” content=”width=device-width, initial-scale=1">
    <meta name="viewport" content="maximum-scale=1">
        <!-- csrf token -->
    <script>
        const csrf = '{{csrf_token()}}'
    </script>
    <title>BCFD - Intranet</title>
    <!-- styles -->
    <link href=”{{ asset('css/app.css') }}” rel=”stylesheet”>
    <!-- pusher -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
</head>
<body data-root-url={{ asset('') }}>
    <div id="app"></div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
