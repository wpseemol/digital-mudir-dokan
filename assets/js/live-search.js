/**
 * Header live search: discover panel on focus, typed results while searching.
 */
(function ($) {
	'use strict';

	$(function () {
		var input = $('.dmd-header-search .dmd-search-form input[name="s"]');
		var panel = $('#dmd-live-search-results');
		var wrap = $('.dmd-header-search').first();
		var header = $('#dmd-header');
		var timer;
		var request;
		var activeIndex = -1;
		var discoverHtml = '';
		var lastQuery = null;

		if (!input.length || !panel.length || typeof dmd_ajax_object === 'undefined') {
			return;
		}

		function items() {
			return panel.find('[data-dmd-ls-item], .dmd-ls-popular__btn');
		}

		function setActive(index) {
			var list = items();
			list.removeClass('is-active');
			if (index < 0 || !list.length) {
				activeIndex = -1;
				return;
			}
			activeIndex = index % list.length;
			if (activeIndex < 0) {
				activeIndex = list.length - 1;
			}
			var el = list.eq(activeIndex);
			el.addClass('is-active');
			if (el[0] && el[0].scrollIntoView) {
				el[0].scrollIntoView({ block: 'nearest' });
			}
		}

		function openItem(el) {
			if (!el || !el.length) {
				return;
			}
			if (el.is('button[data-dmd-ls-suggest]')) {
				var term = el.data('dmd-ls-suggest');
				input.val(term).trigger('input').trigger('focus');
				return;
			}
			var href = el.attr('href');
			if (href) {
				window.location.href = href;
			}
		}

		function close() {
			panel.addClass('hidden').attr('aria-hidden', 'true');
			wrap.removeClass('is-search-open');
			if (header.length) {
				header.removeClass('is-search-open');
			}
			activeIndex = -1;
		}

		function show(html) {
			panel.html(html).removeClass('hidden').attr('aria-hidden', 'false');
			wrap.addClass('is-search-open');
			if (header.length) {
				header.addClass('is-search-open').removeClass('is-hidden');
			}
			activeIndex = -1;
			setActive(-1);
		}

		function fetchPanel(query, force) {
			var q = $.trim(query || '');

			if (!force && lastQuery === q && panel.children().length && !panel.hasClass('hidden')) {
				panel.removeClass('hidden');
				return;
			}

			window.clearTimeout(timer);
			if (request) {
				request.abort();
			}

			// Reuse cached discover HTML when the field is empty.
			if (!q && discoverHtml && !force) {
				lastQuery = '';
				show(discoverHtml);
				return;
			}

			var delay = q ? 220 : 0;

			timer = window.setTimeout(function () {
				show('<p class="m-0 p-5 text-sm text-muted">' + (dmd_ajax_object.searching || '…') + '</p>');

				request = $.post(dmd_ajax_object.ajax_url, {
					action: 'dmd_live_search',
					query: q,
					nonce: dmd_ajax_object.nonce
				}).done(function (response) {
					if (response && response.success) {
						lastQuery = q;
						if (!q) {
							discoverHtml = response.data;
						}
						show(response.data);
					} else {
						show($('<p class="m-0 p-5 text-sm text-muted"></p>').text(response && response.data ? response.data : ''));
					}
				}).fail(function (xhr, status) {
					if ('abort' !== status) {
						close();
					}
				});
			}, delay);
		}

		input.on('focus click', function () {
			fetchPanel(input.val(), false);
		});

		input.on('input', function () {
			var query = $.trim($(this).val());
			if (query.length === 1) {
				// Keep discover panel while the first character is typed.
				if (!panel.hasClass('hidden') && discoverHtml) {
					return;
				}
				fetchPanel('', false);
				return;
			}
			fetchPanel(query, true);
		});

		panel.on('click', '[data-dmd-ls-suggest]', function (e) {
			e.preventDefault();
			openItem($(this));
		});

		$(document).on('click', function (e) {
			if (!$(e.target).closest('#dmd-live-search-results, .dmd-header-search').length) {
				close();
			}
		});

		$(document).on('keydown', function (e) {
			if (panel.hasClass('hidden')) {
				return;
			}

			var list = items();

			if ('Escape' === e.key) {
				e.preventDefault();
				close();
				input.trigger('focus');
				return;
			}

			if ('ArrowDown' === e.key) {
				e.preventDefault();
				setActive(activeIndex + 1);
				return;
			}

			if ('ArrowUp' === e.key) {
				e.preventDefault();
				setActive(activeIndex <= 0 ? list.length - 1 : activeIndex - 1);
				return;
			}

			if ('Enter' === e.key && activeIndex >= 0) {
				e.preventDefault();
				openItem(list.eq(activeIndex));
			}
		});
	});
})(jQuery);
