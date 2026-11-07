/**
 * Theme dashboard: media picker + live theme preview.
 */
(function ($) {
	'use strict';

	$(function () {
		/* Image fields */
		if (typeof wp !== 'undefined' && wp.media) {
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
		}

		/* Cart / checkout theme picker — update live preview on the right */
		$('[data-dmd-theme-picker]').each(function () {
			var picker = $(this);
			var live = picker.find('[data-dmd-theme-live]');

			function applyTheme(slug) {
				live.removeClass('is-classic is-compact is-market').addClass('is-' + slug);
				picker.find('.dmd-theme-card').removeClass('is-selected');
				picker.find('.dmd-theme-card').has('input[value="' + slug + '"]').addClass('is-selected');
			}

			picker.on('change', '[data-dmd-theme-option]', function () {
				applyTheme($(this).val());
			});

			picker.on('click', '.dmd-theme-card', function () {
				var input = $(this).find('[data-dmd-theme-option]');
				if (input.length && !input.prop('checked')) {
					input.prop('checked', true).trigger('change');
				}
			});
		});
	});
})(jQuery);
