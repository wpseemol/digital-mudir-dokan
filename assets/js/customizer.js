/**
 * Live preview for Customizer settings that use postMessage.
 */
(function ($) {
	'use strict';

	if (typeof wp === 'undefined' || !wp.customize) {
		return;
	}

	wp.customize('blogname', function (value) {
		value.bind(function (to) {
			$('.dmd-branding a, .dmd-branding p').text(to);
		});
	});

	wp.customize('blogdescription', function (value) {
		value.bind(function (to) {
			$('.dmd-site-description').text(to);
		});
	});

	wp.customize('dmd_topbar_text', function (value) {
		value.bind(function (to) {
			$('.dmd-topbar span').first().text(to);
		});
	});
})(jQuery);
