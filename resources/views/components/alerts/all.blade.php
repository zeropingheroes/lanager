@include('components.alerts.alert-group', ['messages' => session('error'), 'type' => 'danger'])
@include('components.alerts.alert-group', ['messages' => session('warning'), 'type' => 'warning'])
@include('components.alerts.alert-group', ['messages' => session('success'), 'type' => 'success'])
@include('components.alerts.alert-group', ['messages' => session('info'), 'type' => 'info'])
