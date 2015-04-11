{{
InputGroup::withContents(
	Form::text($name, NULL, ['placeholder' => 'YYYY-MM-DD HH:MM'])
)->append( Icon::time() )
}}
<script type="text/javascript">
	$(function () {
		$("#{{ $name }}").datetimepicker({
			sideBySide: true,
			format: "YYYY-MM-DD HH:mm",
		});
	});
</script>