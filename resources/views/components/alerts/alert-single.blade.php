@if($type && $message)
    <div class="alert alert-{{ $type }}">
        {{ $message }}
    </div>
@endif
