$(document).ready(function () {
	// Make all external links open in a new window
	$('a').each(function() {
		var thisHost = new RegExp('/' + window.location.host + '/');
		var thisJs = new RegExp('^javascript');
		if( ! thisHost.test(this.href) && ! thisJs.test(this.href) ) {
			$(this).click(function(event) {
				event.preventDefault();
				event.stopPropagation();
				window.open(this.href, '_blank');
			});
		}
	});
});