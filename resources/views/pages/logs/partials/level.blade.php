@switch($level)
    @case('EMERGENCY')
    @case('ALERT')
    @case('CRITICAL')
    @case('ERROR')
        <span class="badge badge-danger">{{ ucwords(strtolower($level)) }}</span>
    @break

    @case('WARNING')
    @case('NOTICE')
        <span class="badge badge-warning">{{ ucwords(strtolower($level)) }}</span>
    @break

    @case('INFO')
    @case('DEBUG')
        <span class="badge badge-info">{{ ucwords(strtolower($level)) }}</span>
    @break

    @default
        <span class="badge badge-secondary">{{ ucwords(strtolower($level)) }}</span>
@endswitch