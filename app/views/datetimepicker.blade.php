{{ Form::text($name, NULL, ['placeholder' => 'YYYY-MM-DD HH:MM']) }}
<script type="text/javascript">
	$(function () {
		$("#{{ $name }}").datetimepicker({
			sideBySide: true,
			format: "YYYY-MM-DD HH:mm",
		});
	});
</script>