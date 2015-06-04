/*
 * Protect window.console method calls, e.g. console is not defined on IE
 * unless dev tools are open, and IE doesn't define console.debug
 */
(function () {
	if (!window.console) {
		window.console = {};
	}
	// union of Chrome, FF, IE, and Safari console methods
	var m = [
		"log", "info", "warn", "error", "debug", "trace", "dir", "group",
		"groupCollapsed", "groupEnd", "time", "timeEnd", "profile", "profileEnd",
		"dirxml", "assert", "count", "markTimeline", "timeStamp", "clear"
	];
	// define undefined methods as noops to prevent errors
	for (var i = 0; i < m.length; i++) {
		if (!window.console[m[i]]) {
			window.console[m[i]] = function () {
			};
		}
	}
})();

(function($) {
	$(document).ready(function () {

		//Install placeholder js if Moderniser detects lack of placeholder support
		Modernizr.load({
			test: Modernizr.input.placeholder,
			nope: [
				url_data.template_dir + '/inc/fallback/placeholders.jquery.min.js'
			]
		});

		//Setup global screen size variables
		var window_width = $(window).width();
		var window_height = $(window).height();

		//Update global screen size variables
		$(window).resize(function () {

			window_width = $(window).width();
			window_height = $(window).height();

			//Run scripts that will use screen size variables after they are updates
			resize_window();

		});

		function resize_window() {
			console.log('Resize window');

			//Add any functions that need to update on resize here
		}

		//Add theme specific functions below

	});
})(jQuery);
