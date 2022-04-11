@extends('dev.utils.layout')

@section('content')

    <div class="logsFileView">
        <div class="codeView">
            <pre>
                {{print($file)}}
            </pre>
        </div>
    </div>

@endsection
