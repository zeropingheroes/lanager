$(document).ready(function () {
	// Make all external links open in a new window
	$('a').each(function() {
		var a = new RegExp('/' + window.location.host + '/');
		if(!a.test(this.href)) {
			$(this).click(function(event) {
				event.preventDefault();
				event.stopPropagation();
				window.open(this.href, '_blank');
			});
		}
	});
});