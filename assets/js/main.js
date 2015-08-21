jQuery(document).ready(function($){

	Site.init();

});

var Site = (function($) {

	// Aliased Variables
	var cfg, throttle, helper, loader;

	// DOM caching
	var	$win = $(window),
		$doc = $(document);

	// Globals
	var window_width 	= $win.width(),
		window_height 	= $win.height(),
		scroll_top 		= $doc.scrollTop();

	return {

		settings: {
			speedIn: 	600,
			speedOut: 	400,
			easing: 	[0.55, 0, 0.1, 1] // easeOutQuint
		},

		init: function () {
			cfg 		= this.settings;
			throttle 	= this.handlers.throttle;
			helper 		= this.helpers;
			loader 		= this.handlers.loader;

			// Load scripts to support missing functionality
			this.initFallbacks();

			// Add browser detection
			this.detectBrowser();
		},

		handlers: {
			// Throttle resize and scroll events
			throttle: function (handler, func) {
				var eventHappend = false,
					throttleOps = {};

				throttleOps[handler] = function () {
					eventHappend = true;
				};

				$win.on(throttleOps);

				setInterval(function () {
					if (eventHappend) {

						eventHappend = false;

						if (handler === 'resize') {

							window_width = $win.width();
							window_height = $win.height();
						}

						if (handler === 'scroll') {

							scroll_top = $doc.scrollTop();
						}

						func();
					}
				}, 250);
			},
			loader: function (func) {
				$win.on('load', func);
			}
		},

		helpers: {
			// Get a random Num between min and max
			getRandom: function (min, max) {
				return Math.floor(Math.random() * (max - min + 1)) + min;
			},

			// Check the window width
			checkWindowWidth: function( width ){
				// Check the media query size
				if (window.matchMedia) {
					var mql = window.matchMedia('screen and (min-width: '+width+'px)');
					// Return the result of the match
					return mql.matches;
				}

				// TODO Test this statement block
				// Check if Modernizr is installed & to see if mq mthod exists
				if (typeof Modernizr !== 'undefined' && typeof Modernizr.mq !== 'undefined') {
					// Return the result of the match
					return Modernizr.mq('(min-width: '+width+'px)');
				}

				// If we have no support get close with jQuery
				return( window_width > width );
			},

			// Set cookie
			setCookie: function(cname, cvalue, exdays) {
				if (exdays === undefined) {
					exdays = 360;
				}
				var d = new Date();
				d.setTime(d.getTime() + (exdays*86400000));
				var expires = "expires="+d.toUTCString();
				document.cookie = cname + "=" + cvalue + "; " + expires;
			},

			// Get cookie
			getCookie: function(cname) {
				var name = cname + "=";
				var ca = document.cookie.split(';');
				for(var i=0; i<ca.length; i++) {
					var c = ca[i];
					while (c.charAt(0)==' ') c = c.substring(1);
					if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
				}
				return "";
			},

			// Check if element exists and run function
			check: function (el, func) {
				if ($(el).length) {
					func();
				}
			}
		},

		// Load scripts to support missing functionality
		initFallbacks: function(){
			// Install placeholder js if Moderniser detects lack of placeholder support
			if (typeof Modernizr !== 'undefined') {
				Modernizr.load({
					test: Modernizr.input.placeholder,
					nope: [
						url_data.template_dir + '/inc/fallback/placeholders.jquery.min.js'
					]
				});
			}
		},

		// Browser detection
		detectBrowser: function(){
			document.documentElement.setAttribute('data-ua', navigator.userAgent);
			document.documentElement.setAttribute('data-nav', navigator.sayswho.toLowerCase());
		}

	};
})(jQuery);