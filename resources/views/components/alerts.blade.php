@if( session('alerts') )
    @foreach(session('alerts') as $alert)
        <div class="alert alert-{{ $alert['type'] }}">
            {{ $alert['message'] }}
        </div>
    @endforeach
@endif
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endforeach
@endif