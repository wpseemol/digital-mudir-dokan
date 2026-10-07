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
			$('.dmd-branding > a:not(.custom-logo-link)').text(to);
		});
	});

	wp.customize('blogdescription', function (value) {
		value.bind(function (to) {
			$('.dmd-site-description').text(to);
		});
	});

	wp.customize('dmd_topbar_text', function (value) {
		value.bind(function (to) {
			$('.dmd-topbar-text').text(to);
		});
	});
	wp.customize('dmd_logo_width', function (value) {
		value.bind(function (to) {
			$('.dmd-branding img.custom-logo').css({ width: to + 'px', maxWidth: 'min(' + to + 'px, 46vw)' });
		});
	});
})(jQuery);
