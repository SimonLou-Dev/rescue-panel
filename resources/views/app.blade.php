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
    <script type="module">
        import RefreshRuntime from "http://localhost:3000/@react-refresh"
        RefreshRuntime.injectIntoGlobalHook(window)
        window.$RefreshReg$ = () => {}
        window.$RefreshSig$ = () => (type) => type
        window.__vite_plugin_react_preamble_installed__ = true
    </script>
    <script type="module" src="http://localhost:3000/@vite/client"></script>

    {!!ViteGetAssets::asset('main.jsx')!!}

    <!-- styles -->


    <link href=”{{ asset('css/app.css') }}” rel=”stylesheet”>

</head>
<body data-root-url={{ asset('') }}>
    <div id="app"></div>
</body>
</html>
