@if($messages)
    @php
        $messages = (is_array($messages) ? $messages : (array) $messages)
    @endphp
    @foreach($messages as $message)
        @include('components.alerts.alert-single', ['type' => $type, 'message' => $message])
    @endforeach
@endif