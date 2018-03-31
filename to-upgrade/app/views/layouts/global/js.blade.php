{{ HTML::script('vendor/jquery/jquery-2.1.1.min.js') }}
{{ HTML::script('vendor/twbs/bootstrap/bootstrap.min.js') }}

{{ HTML::script('vendor/moment/moment.min.js') }}
{{ HTML::script('vendor/eonasdan/bootstrap-datetimepicker/bootstrap-datetimepicker.js') }}

{{ HTML::script('vendor/fullcalendar/fullcalendar.min.js') }}
{{ HTML::script('vendor/fullcalendar/timeline.js') }}
{{ HTML::script('vendor/google/swfobject.js') }}
{{ HTML::script('vendor/rails/jquery-ujs/rails.js') }}

{{ HTML::script('js/lanager.js') }}

<script type="text/javascript">
    var siteUrl = '{{ url('/') }}';
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
