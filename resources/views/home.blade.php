<html lang="{{str_replace('_','-', app()->getLocale())}}">
<head>

    <?php
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
        header('Access-Control-Allow-Headers: *');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        }

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }

     }
    ?>
    <!-- GET CSRF TOKEN -->
    <script>
        const csrf = '{{csrf_token()}}'
    </script>

    <title>BCFD - Intranet</title>

    <!-- META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--End META -->

    <!--Vite Assets-->
    {!! ViteGetAssets::asset('/js/app.jsx', ["react"])  !!}
    <!-- End Vite Assets -->

    <!-- JS LIBS - pusher -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

</head>
<body data-root-url={{ asset('') }}>
<div id="app"></div>
</body>
</html>
<!--


http://localhost:3000/assets/js/test/mains.jsx
http://localhost:3000/assets/js/test/main/jsx
