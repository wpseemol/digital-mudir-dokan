/**
 * Digital Mudir Dokan — front-end behaviour.
 *
 * Everything here is progressive enhancement: each block checks that its markup
 * is on the page before it does anything, and the page stays usable without it.
 */
(function () {
	'use strict';

	var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* ------------------------------------------------------------------
	 * Disclosure toggles (mobile menu, search panel)
	 * ------------------------------------------------------------------ */
	function initToggle(button, panel) {
		if (!button || !panel) {
			return;
		}

		button.addEventListener('click', function () {
			var open = button.getAttribute('aria-expanded') === 'true';

			button.setAttribute('aria-expanded', open ? 'false' : 'true');
			panel.hidden = open;
			panel.classList.toggle('hidden', open);

			if (!open) {
				var field = panel.querySelector('input, a, button');
				if (field) {
					field.focus();
				}
			}
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') {
				button.setAttribute('aria-expanded', 'false');
				panel.hidden = true;
				panel.classList.add('hidden');
				button.focus();
			}
		});
	}

	/* ------------------------------------------------------------------
	 * Quantity steppers
	 * ------------------------------------------------------------------ */
	function initQuantity(scope) {
		scope.querySelectorAll('[data-dmd-quantity]').forEach(function (wrap) {
			if (wrap.dataset.dmdReady === '1') {
				return;
			}
			wrap.dataset.dmdReady = '1';

			var input = wrap.querySelector('input.qty');
			if (!input) {
				return;
			}

			function step(direction) {
				var current = parseFloat(input.value) || 0;
				var stepBy = parseFloat(input.getAttribute('step')) || 1;
				var min = input.getAttribute('min') ? parseFloat(input.getAttribute('min')) : 0;
				var max = input.getAttribute('max') ? parseFloat(input.getAttribute('max')) : Infinity;
				var value = current + direction * stepBy;

				if (value < min) {
					value = min;
				}
				if (value > max) {
					value = max;
				}

				input.value = value;
				input.dispatchEvent(new Event('change', { bubbles: true }));
			}

			var down = wrap.querySelector('[data-dmd-qty-down]');
			var up = wrap.querySelector('[data-dmd-qty-up]');

			if (down) {
				down.addEventListener('click', function () {
					step(-1);
				});
			}
			if (up) {
				up.addEventListener('click', function () {
					step(1);
				});
			}
		});
	}

	/* ------------------------------------------------------------------
	 * Testimonial videos — load the iframe only on demand
	 * ------------------------------------------------------------------ */
	function initVideos() {
		document.querySelectorAll('[data-dmd-video]').forEach(function (holder) {
			var button = holder.querySelector('.dmd-video__play');
			if (!button) {
				return;
			}

			button.addEventListener('click', function () {
				var id = holder.dataset.dmdVideo;
				var frame = document.createElement('iframe');

				frame.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0';
				frame.title = button.textContent.trim();
				frame.allow = 'accelerometer; autoplay; encrypted-media; picture-in-picture';
				frame.allowFullscreen = true;
				frame.className = 'absolute inset-0 h-full w-full border-0';
				frame.loading = 'lazy';

				holder.innerHTML = '';
				holder.appendChild(frame);
			});
		});
	}

	/* ------------------------------------------------------------------
	 * Express order buttons carry the chosen quantity to checkout
	 * ------------------------------------------------------------------ */
	function initOrderNow() {
		document.querySelectorAll('[data-dmd-order-now]').forEach(function (link) {
			link.addEventListener('click', function () {
				var form = link.closest('form.cart') || document.querySelector('form.cart');
				if (!form) {
					return;
				}

				var qty = form.querySelector('input.qty');
				if (qty && parseInt(qty.value, 10) > 1) {
					var url = new URL(link.href, window.location.origin);
					url.searchParams.set('quantity', qty.value);
					link.href = url.toString();
				}
			});
		});
	}

	/* ------------------------------------------------------------------
	 * WooCommerce category toggle
	 * ------------------------------------------------------------------ */
	function initCategoryToggle() {
		var widget = document.querySelector('.widget_product_categories');
		if (!widget) {
			return;
		}

		var list = widget.querySelector('ul');
		if (!list) {
			return;
		}

		var items = Array.prototype.slice.call(list.children);
		var limit = 8;

		if (items.length <= limit) {
			return;
		}

		// Hide excess items
		items.forEach(function (item, index) {
			if (index >= limit) {
				item.classList.add('hidden');
			}
		});

		// Create toggle button
		var button = document.createElement('button');
		button.className = 'dmd-btn dmd-btn--ghost dmd-btn--block mt-3 text-xs';
		button.innerHTML = 'আরও দেখুন +';
		button.type = 'button';

		button.addEventListener('click', function () {
			var isExpanded = button.getAttribute('aria-expanded') === 'true';
			items.forEach(function (item, index) {
				if (index >= limit) {
					item.classList.toggle('hidden', isExpanded);
				}
			});

			button.setAttribute('aria-expanded', !isExpanded);
			button.innerHTML = !isExpanded ? 'কম দেখুন -' : 'আরও দেখুন +';
		});

		widget.appendChild(button);
	}

	/* ------------------------------------------------------------------
	 * View Switcher (Grid/List)
	 * ------------------------------------------------------------------ */
	function initViewSwitcher() {
		var container = document.querySelector('ul.products');
		var toggles = document.querySelectorAll('[data-view-toggle]');

		if (!container || toggles.length === 0) {
			return;
		}

		function setView(view) {
			localStorage.setItem('dmd_shop_view', view);
			container.classList.remove('view-grid', 'view-list');
			container.classList.add('view-' + view);

			toggles.forEach(function (btn) {
				btn.setAttribute('aria-pressed', btn.dataset.viewToggle === view);
			});
		}

		var savedView = localStorage.getItem('dmd_shop_view') || 'grid';
		setView(savedView);

		toggles.forEach(function (btn) {
			btn.addEventListener('click', function () {
				setView(btn.dataset.viewToggle);
			});
		});
	}

	/* ------------------------------------------------------------------
	 * Product description toggle
	 * ------------------------------------------------------------------ */
	function initDescriptionToggle() {
		var wrapper = document.querySelector('[data-dmd-description-wrapper]');
		if (!wrapper) {
			return;
		}

		var content = wrapper.querySelector('[data-dmd-description-content]');
		var fade = wrapper.querySelector('[data-dmd-description-fade]');
		var toggle = wrapper.querySelector('[data-dmd-description-toggle]');
		var text = toggle.querySelector('[data-dmd-toggle-text]');

		toggle.addEventListener('click', function () {
			var isExpanded = content.classList.contains('max-h-none');

			content.classList.toggle('max-h-64', isExpanded);
			content.classList.toggle('max-h-none', !isExpanded);
			fade.classList.toggle('hidden', !isExpanded);
			text.textContent = isExpanded ? 'বিস্তারিত দেখুন +' : 'কম দেখুন -';
		});
	}

	/* ------------------------------------------------------------------
	 * Boot
	 * ------------------------------------------------------------------ */
	function boot() {
		// Initialize Swiper.js
	function initHeroSlider() {
		var slider = document.querySelector('.hero-swiper');
		if (!slider) {
			return;
		}

		var autoplay = slider.dataset.autoplay === 'true';
		var speed = parseInt(slider.dataset.speed, 10) || 800;
		var delay = parseInt(slider.dataset.delay, 10) || 4000;

		new Swiper('.hero-swiper', {
			loop: true,
			speed: speed,
			autoplay: autoplay ? {
				delay: delay,
				disableOnInteraction: false,
				pauseOnMouseEnter: true,
			} : false,
			navigation: {
				prevEl: '.swiper-button-prev-custom',
				nextEl: '.swiper-button-next-custom',
			},
			pagination: {
				el: '.swiper-pagination-custom',
				clickable: true,
				bulletClass: 'w-3 h-3 rounded-full bg-white/60 inline-block cursor-pointer transition-all',
				bulletActiveClass: '!w-8 !bg-emerald-600',
			},
		});
	}

	/* ------------------------------------------------------------------
	 * Boot
	 * ------------------------------------------------------------------ */
	function boot() {
		initHeroSlider();

		initToggle(
			document.querySelector('.dmd-menu-toggle'),
			document.getElementById('dmd-mobile-nav')
		);

		initToggle(
			document.querySelector('.dmd-search-toggle'),
			document.getElementById('dmd-search-panel')
		);

		initQuantity(document);
		initVideos();
		initOrderNow();
		initCategoryToggle();
		initViewSwitcher();
		initDescriptionToggle();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}

	// WooCommerce replaces cart markup over AJAX — rebind what it swaps out.
	document.body.addEventListener('updated_wc_div', function () {
		initQuantity(document);
	});
	document.body.addEventListener('updated_cart_totals', function () {
		initQuantity(document);
	});
})();
