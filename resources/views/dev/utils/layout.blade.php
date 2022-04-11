<!doctype html>
<html lang="fr">
    <head>
        <title>Rescue panel - dev</title>
        <link rel="stylesheet" href="{{asset('css/dev.css')}}">
    </head>
    <body>
        <div class="header">
            <div class="menu-item">
                <a href="{{ route('dev.mdt') }}">MDT</a>
                <a href="{{ route('dev.dashboard') }}">dashboard</a>
                <a href="{{ route('dev.logs') }}">logs</a>
                <a href="{{ route('dev.console') }}">console</a>
                <a href="{{ route('dev.storage') }}">storage</a>
                <a href="{{ route('dev.user') }}">user</a>
            </div>
        </div>
        <div class="content">
            @yield('content')
        </div>

    </body>
</html>
