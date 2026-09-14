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
	 * Mobile Navigation Drawer
	 * ------------------------------------------------------------------ */
	function initMobileMenu() {
		var toggles = document.querySelectorAll('.dmd-menu-toggle');
		var nav = document.getElementById('dmd-mobile-nav');
		var body = document.body;

		if (!toggles.length || !nav) return;

		// Create backdrop
		var backdrop = document.createElement('div');
		backdrop.className = 'fixed inset-0 z-40 bg-black/50 transition-opacity duration-300 opacity-0 hidden';
		body.appendChild(backdrop);

		function toggleMenu(open) {
			toggles.forEach(toggle => toggle.setAttribute('aria-expanded', open ? 'true' : 'false'));
			
			if (open) {
				nav.classList.remove('translate-x-full');
				nav.classList.add('translate-x-0');
			} else {
				nav.classList.remove('translate-x-0');
				nav.classList.add('translate-x-full');
			}
			
			backdrop.classList.toggle('hidden', !open);
			setTimeout(() => {
				backdrop.classList.toggle('opacity-0', !open);
			}, 10);
			
			body.classList.toggle('overflow-hidden', open);
		}

		toggles.forEach(toggle => {
			toggle.addEventListener('click', function () {
				var isOpen = toggle.getAttribute('aria-expanded') === 'true';
				toggleMenu(!isOpen);
			});
		});

		backdrop.addEventListener('click', function () {
			toggleMenu(false);
		});

		nav.addEventListener('click', function (e) {
			if (e.target.tagName === 'A') {
				toggleMenu(false);
			}
		});

		// Escape key
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && nav.classList.contains('translate-x-0')) {
				toggleMenu(false);
			}
		});
	}

	/* ------------------------------------------------------------------
	 * Disclosure toggles (search panel)
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

		// Detect existing column class (e.g., columns-4)
		var columnClass = Array.from(container.classList).find(c => c.startsWith('columns-'));

		function setView(view) {
			localStorage.setItem('dmd_shop_view', view);
			
			// Reset classes
			container.classList.remove('view-grid', 'view-list', 'grid-cols-1');
			if (columnClass) {
				container.classList.remove(columnClass);
			}

			if (view === 'list') {
				container.classList.add('view-list', 'grid-cols-1');
			} else {
				container.classList.add('view-grid');
				if (columnClass) {
					container.classList.add(columnClass);
				}
			}

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
	 * Initialize Swiper.js
	 * ------------------------------------------------------------------ */
	function initHeroSlider() {
		var slider = document.querySelector('.hero-swiper');
		if (!slider) {
			return;
		}

		var slides = slider.querySelectorAll('.swiper-slide');
		if (slides.length <= 1) {
			slider.querySelector('.swiper-button-prev-custom').style.display = 'none';
			slider.querySelector('.swiper-button-next-custom').style.display = 'none';
			return;
		}

		const isAutoplay = slider.dataset.autoplay !== 'false';
		const delayTime = parseInt(slider.dataset.delay, 10) || 4000;
		const speedTime = parseInt(slider.dataset.speed, 10) || 800;

		new Swiper(slider, {
			loop: true,
			speed: speedTime,
			autoplay: isAutoplay ? {
				delay: delayTime,
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
	 * Homepage Product Tab Filter
	 * ------------------------------------------------------------------ */
	function initProductTabs() {
		var section = document.querySelector('.dmd-product-section[aria-labelledby="dmd-section-all-products"]');
		if (!section) return;

		var container = section.querySelector('.product-grid-container');
		var tabs = section.querySelectorAll('.product-tabs button');
		
		if (!container || !tabs.length) return;

		tabs.forEach(tab => {
			tab.addEventListener('click', function() {
				// Update active tab styles
				tabs.forEach(t => t.classList.remove('bg-[#1B6A3B]', 'text-white', 'border-[#1B6A3B]'));
				tab.classList.add('bg-[#1B6A3B]', 'text-white', 'border-[#1B6A3B]');

				var category = tab.dataset.category;
				var products = container.querySelectorAll('.product-item');

				products.forEach(product => {
					if (category === 'all' || product.dataset.categories.split(' ').includes(category)) {
						product.classList.remove('hidden');
					} else {
						product.classList.add('hidden');
					}
				});
			});
		});
	}

	/* ------------------------------------------------------------------
	 * FAQ Accordion
	 * ------------------------------------------------------------------ */
	function initFAQ() {
		document.querySelectorAll('.faq-item').forEach(item => {
			item.addEventListener('click', () => {
				const answer = item.querySelector('.faq-answer');
				const icon = item.querySelector('.faq-icon');
				
				// Close all other FAQs
				document.querySelectorAll('.faq-answer').forEach(el => {
					if (el !== answer) el.classList.add('hidden');
				});
				document.querySelectorAll('.faq-icon').forEach(el => {
					if (el !== icon) el.textContent = '+';
				});

				answer.classList.toggle('hidden');
				icon.textContent = answer.classList.contains('hidden') ? '+' : '-';
			});
		});
	}

	/* ------------------------------------------------------------------
	 * Boot
	 * ------------------------------------------------------------------ */
	function boot() {
		initHeroSlider();
		initProductTabs();
		initFAQ();
		// ...

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
