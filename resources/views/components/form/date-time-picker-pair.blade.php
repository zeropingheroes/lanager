<div class="form-row">
    <script type="text/javascript">
        $(function () {
            var format = 'YYYY-MM-DD HH:mm:ss';

            var start = $('#{{ $startFieldName }}');
            var end = $('#{{ $endFieldName }}');
            var startDate = moment(start.val(), format).toDate();
            var endDate = moment(end.val(), format).toDate();

            start.datetimepicker({
                format: format,
                date: moment(),
                sideBySide: true
            });

            start.datetimepicker('date', startDate);

            end.datetimepicker({
                format: format,
                date: moment(),
                sideBySide: true,
                useCurrent: false
            });

            end.datetimepicker('date', endDate);

            $("#{{ $startFieldName }}").on("change.datetimepicker", function (e) {
                $('#{{ $endFieldName }}').datetimepicker('minDate', e.date);
            });
            $("#{{ $endFieldName }}").on("change.datetimepicker", function (e) {
                $('#{{ $startFieldName }}').datetimepicker('maxDate', e.date);
            });
        });
    </script>
    <div class="form-group col-md-6">
        <label for="{{ $startFieldName }}">@lang('title.'.$startFieldName)</label>
        <input type="text" class="form-control datetimepicker-input" id="{{ $startFieldName }}" name="{{ $startFieldName }}"
               placeholder="YYYY-MM-DD HH:MM:SS" value="{{ old($startFieldName, $item->$startFieldName) }}"
               data-toggle="datetimepicker" data-target="#{{ $startFieldName }}">
    </div>

    <div class="form-group col-md-6">
        <label for="{{ $endFieldName }}">@lang('title.'.$endFieldName)</label>
        <input type="text" class="form-control datetimepicker-input" id="{{ $endFieldName }}" name="{{ $endFieldName }}"
               placeholder="YYYY-MM-DD HH:MM:SS" value="{{ old($endFieldName, $item->$endFieldName) }}"
               data-toggle="datetimepicker" data-target="#{{ $endFieldName }}">
    </div>
</div>