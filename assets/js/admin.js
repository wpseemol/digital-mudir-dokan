/**
 * Theme dashboard: media-library image picker.
 */
(function ($) {
	'use strict';

	$(function () {
		if (typeof wp === 'undefined' || !wp.media) {
			return;
		}

		$('[data-dmd-image]').each(function () {
			var field = $(this);
			var input = field.find('input[type="hidden"]');
			var preview = field.find('.dmd-image-field__preview');
			var remove = field.find('[data-dmd-image-remove]');
			var frame;

			field.on('click', '[data-dmd-image-select]', function (e) {
				e.preventDefault();

				if (!frame) {
					frame = wp.media({
						title: dmdAdmin.chooseImage,
						button: { text: dmdAdmin.useImage },
						library: { type: 'image' },
						multiple: false
					});

					frame.on('select', function () {
						var image = frame.state().get('selection').first().toJSON();
						var size = image.sizes && image.sizes.medium ? image.sizes.medium : image;

						input.val(image.id);
						preview.empty().append($('<img alt="">').attr('src', size.url));
						remove.prop('hidden', false);
					});
				}

				frame.open();
			});

			remove.on('click', function (e) {
				e.preventDefault();
				input.val('');
				preview.empty();
				remove.prop('hidden', true);
			});
		});
	});
})(jQuery);
