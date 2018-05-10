@if($messages)
    @php
        $messages = (is_array($messages) ? $messages : (array) $messages)
    @endphp
    @foreach($messages as $message)
        <div class="alert alert-{{ $type }}">
            {{ $message }}
        </div>
    @endforeach
@endif