{{ HTML::script('vendor/jquery/jquery-2.1.1.min.js') }}
{{ HTML::script('vendor/twbs/bootstrap/bootstrap.min.js') }}

{{ HTML::script('vendor/moment/moment.min.js') }}
{{ HTML::script('vendor/eonasdan/bootstrap-datetimepicker/bootstrap-datetimepicker.en-gb.js') }}
{{ HTML::script('vendor/eonasdan/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}

{{ HTML::script('vendor/fullcalendar/fullcalendar.min.js') }}
{{ HTML::script('vendor/fullcalendar/timeline.js') }}
{{ HTML::script('vendor/google/swfobject.js') }}
{{ HTML::script('vendor/rails/jquery-ujs/rails.js') }}

<script type="text/javascript">
	var siteUrl = '{{ url('/') }}';
	$(document).ready(function () {
		$("[rel=tooltip]").tooltip();
	});
</script>
