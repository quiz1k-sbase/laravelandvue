@extends('main')

@section('content')
<div id="app">
</div>

<script>
    window.appConfig = {
        csrf: "{{csrf_token()}}",
    }
</script>
@endsection
