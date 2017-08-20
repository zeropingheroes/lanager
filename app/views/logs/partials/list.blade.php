@if (count($logs))
    <table class="table logs-index">
        <thead>
        <tr>
            <th>ID</th>
            <th class="time">Time</th>
            <th class="sapi">SAPI</th>
            <th>Level</th>
            <th colspan="2">Message</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $logs as $log )
            <tr>
                <td>
                    {{ link_to_route('logs.show', $log->id, $log->id) }}
                </td>
                <td title="{{{ $log->created_at }}}">
                    {{{ $log->created_at->diffForHumans() }}}
                </td>
                <td>
                    {{{ strtoupper($log->php_sapi_name) }}}
                </td>
                <td>
                    @include('logs.partials.level', ['level' => $log->level])
                </td>
                <td>
                    {{{ $log->message }}}
                </td>
                <td class="text-right">
                    @include('buttons.url',
                        [
                            'url' => route('logs.show', $log->id),
                            'icon' => 'optionHorizontal',
                            'size' => 'extraSmall'
                        ]
                    )
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>No log entries found!</p>
@endif