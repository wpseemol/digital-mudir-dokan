/**
 * Header live search: product and category suggestions in a full-width panel.
 */
(function ($) {
	'use strict';

	$(function () {
		var input = $('.dmd-header-search .dmd-search-form input[name="s"]');
		var panel = $('#dmd-live-search-results');
		var timer;
		var request;

		if (!input.length || !panel.length || typeof dmd_ajax_object === 'undefined') {
			return;
		}

		function close() {
			panel.addClass('hidden').empty();
		}

		function show(html) {
			panel.html(html).removeClass('hidden');
		}

		input.on('input', function () {
			var query = $.trim($(this).val());

			window.clearTimeout(timer);
			if (request) {
				request.abort();
			}

			if (query.length < 2) {
				close();
				return;
			}

			timer = window.setTimeout(function () {
				show('<p class="m-0 p-4 text-sm text-muted">' + (dmd_ajax_object.searching || '…') + '</p>');

				request = $.post(dmd_ajax_object.ajax_url, {
					action: 'dmd_live_search',
					query: query,
					nonce: dmd_ajax_object.nonce
				}).done(function (response) {
					if (response && response.success) {
						show(response.data);
					} else {
						show($('<p class="m-0 p-4 text-sm text-muted"></p>').text(response && response.data ? response.data : ''));
					}
				}).fail(function (xhr, status) {
					if ('abort' !== status) {
						close();
					}
				});
			}, 250);
		});

		input.on('focus', function () {
			if ($.trim(input.val()).length >= 2 && panel.children().length) {
				panel.removeClass('hidden');
			}
		});

		$(document).on('click', function (e) {
			if (!$(e.target).closest('#dmd-live-search-results, .dmd-header-search').length) {
				panel.addClass('hidden');
			}
		});

		$(document).on('keydown', function (e) {
			if ('Escape' === e.key && !panel.hasClass('hidden')) {
				panel.addClass('hidden');
				input.trigger('focus');
			}
		});
	});
})(jQuery);
