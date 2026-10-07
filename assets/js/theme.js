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
	 * Toast — a short-lived status message
	 * ------------------------------------------------------------------ */
	var DMD_TOAST_BASE = 'dmd-toast fixed bottom-6 right-6 z-50 max-w-xs bg-gray-900 text-white px-5 py-3 rounded-xl shadow-xl text-sm transition-all duration-300 transform translate-y-2 opacity-0';
	var DMD_TOAST_SHOWN = 'translate-y-0 opacity-100';

	function showToast(message) {
		// Never stack two toasts in the same corner.
		document.querySelectorAll('.dmd-toast').forEach(function (existing) {
			existing.remove();
		});

		var toast = document.createElement('div');
		toast.className = DMD_TOAST_BASE;
		toast.setAttribute('role', 'status');
		toast.textContent = message;
		document.body.appendChild(toast);

		window.requestAnimationFrame(function () {
			toast.className = DMD_TOAST_BASE + ' ' + DMD_TOAST_SHOWN;
		});

		window.setTimeout(function () {
			toast.className = DMD_TOAST_BASE;
			window.setTimeout(function () {
				toast.remove();
			}, 300);
		}, 3000);
	}

	/* ------------------------------------------------------------------
	 * Express order buttons carry the chosen quantity and variation to checkout
	 * ------------------------------------------------------------------ */
	function variationMessage() {
		return (window.dmd_theme_object && window.dmd_theme_object.selectVariation) || 'Please choose a variation.';
	}

	function chooseVariationMessage() {
		return (window.dmd_theme_object && window.dmd_theme_object.chooseVariation)
			|| 'দয়া করে প্রথমে একটি ভেরিয়েন্ট বা সাইজ নির্বাচন করুন, তারপর অর্ডার সম্পন্ন করুন।';
	}

	/**
	 * Permalink of the product shown by a loop card.
	 *
	 * The theme's cards do not use WooCommerce's `.woocommerce-loop-product__link`
	 * wrapper, so fall back to the title link and then to the first anchor.
	 */
	function cardPermalink(card) {
		var anchor = card.querySelector('.woocommerce-loop-product__link[href], h2 a[href], h3 a[href]')
			|| card.querySelector('a[href]');

		return anchor ? anchor.getAttribute('href') : '';
	}

	function initOrderNow() {
		document.querySelectorAll('[data-dmd-order-now], .dmd-direct-checkout-btn').forEach(function (link) {
			link.addEventListener('click', function (event) {
				// Loop cards (home, shop, category): there is no variation
				// form on the page, so a variable product cannot be added
				// straight from the card.
				var card = link.closest('li.product');

				if (card) {
					if (!card.classList.contains('product-type-variable')) {
						return; // Simple products keep their direct checkout link.
					}

					event.preventDefault();
					showToast(chooseVariationMessage());

					var permalink = cardPermalink(card);
					if (permalink) {
						window.setTimeout(function () {
							window.location.href = permalink;
						}, 1200);
					}
					return;
				}

				var form = link.closest('form.cart') || document.querySelector('form.cart');
				if (!form) {
					return;
				}

				var isVariations = form.classList.contains('variations_form');
				var variation = form.querySelector('input.variation_id');
				var chosenVariation = variation ? parseInt(variation.value, 10) : 0;

				// Instant checkout needs a variation before it can build a cart line.
				if (isVariations && chosenVariation < 1) {
					event.preventDefault();
					showToast(variationMessage());

					var firstChip = document.querySelector('.dmd-variation-chip:not(.is-unavailable)');
					if (firstChip) {
						firstChip.focus();
					}
					return;
				}

				var url = new URL(link.href, window.location.origin);
				var changed = false;

				var qty = form.querySelector('input.qty') || form.querySelector('input[name="quantity"]');
				if (qty && parseInt(qty.value, 10) > 1) {
					url.searchParams.set('quantity', qty.value);
					changed = true;
				}

				// Variable products: pass the chosen variation and attribute
				// values through so the express handler can add them to the cart.
				if (chosenVariation > 0) {
					url.searchParams.set('variation_id', variation.value);
					changed = true;
				}

				form.querySelectorAll('.variations select').forEach(function (select) {
					if (select.name && select.value) {
						url.searchParams.set(select.name, select.value);
						changed = true;
					}
				});

				if (changed) {
					link.href = url.toString();
				}
			});
		});
	}

	/* ------------------------------------------------------------------
	 * WooCommerce category toggle
	 * ------------------------------------------------------------------ */
	function initCategoryToggle() {
		var list = document.querySelector('.dmd-product-categories, .widget_product_categories > ul');
		if (!list) {
			return;
		}

		var widget = list.closest('.dmd-filter-card, .widget_product_categories, .dmd-widget') || list.parentElement;
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
		button.className = 'mt-3 w-full rounded-lg border border-line bg-white px-3 py-2 text-xs font-medium text-emerald-800 transition hover:bg-emerald-50';
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
		var container = document.getElementById('dmd-archive-products');
		var toggles = document.querySelectorAll('[data-view-toggle]');

		if (!container || toggles.length === 0) {
			return;
		}

		function setView(view) {
			localStorage.setItem('dmd_archive_view', view);
			
			if (view === 'list') {
				container.classList.remove('grid-view');
				container.classList.add('list-view');
			} else {
				container.classList.remove('list-view');
				container.classList.add('grid-view');
			}

			toggles.forEach(function (btn) {
				btn.setAttribute('aria-pressed', btn.dataset.viewToggle === view);
			});
		}

		var savedView = localStorage.getItem('dmd_archive_view') || 'grid';
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
		jQuery(document).ready(function($) {
			// Scope to the All Products section container specifically
			var $allProductsSection = $('#all-products-section');
			if ($allProductsSection.length === 0) return;

			$allProductsSection.find('.product-tab-btn').on('click', function(e) {
				e.preventDefault();
				$allProductsSection.find('.product-tab-btn').removeClass('bg-[#1B6A3B] text-white border-[#1B6A3B]').addClass('bg-white text-gray-700 border-gray-300');
				$(this).addClass('bg-[#1B6A3B] text-white border-[#1B6A3B]').removeClass('bg-white text-gray-700 border-gray-300');

				const selectedCat = $(this).data('category');
				if (selectedCat === 'all') {
					$allProductsSection.find('.product-card-item').fadeIn(200);
				} else {
					$allProductsSection.find('.product-card-item').hide();
					$allProductsSection.find(`.product-card-item[data-category*="${selectedCat}"]`).fadeIn(200);
				}
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
	 * Variable products — pill chips mirroring the attribute selects
	 *
	 * WooCommerce drives the variation through `.variations select`, so the
	 * chips stay a view of that element: clicking one sets the value and fires
	 * `change`, and the chips are rebuilt whenever WooCommerce rewrites the
	 * options. The select is only hidden once the chips exist, which keeps the
	 * form usable without JavaScript.
	 * ------------------------------------------------------------------ */
	function buildVariationChips($form) {
		$form.find('.variations select').each(function () {
			var $select = $(this);
			var $field = $select.closest('.dmd-variation-field');
			var $chips = $field.find('[data-dmd-chips]');

			if (!$chips.length) {
				return;
			}

			var current = $select.val();
			$chips.empty();

			$select.find('option').each(function () {
				var $option = $(this);
				var value = $option.val();

				if (!value) {
					return;
				}

				var $chip = $('<button type="button" class="dmd-variation-chip"></button>');
				var isActive = current === value;

				$chip.text($option.text());
				$chip.attr('data-dmd-chip-value', value);
				$chip.attr('aria-pressed', isActive ? 'true' : 'false');

				if (isActive) {
					$chip.addClass('is-active');
				}

				if (this.disabled) {
					$chip.addClass('is-unavailable').prop('disabled', true);
				}

				$chips.append($chip);
			});

			if (!$chips.children().length) {
				return;
			}

			var label = $field.find('label').first().text().replace(/\*+\s*$/, '').trim();

			$chips.attr({ role: 'group', 'aria-label': label || 'Variation' });
			$chips.prop('hidden', false);
			$select.addClass('dmd-variation-native');
		});
	}

	function initVariationChips() {
		if (typeof jQuery === 'undefined') {
			return;
		}

		var $ = jQuery;
		var $forms = $('.variations_form');

		if (!$forms.length) {
			return;
		}

		$forms.each(function () {
			var $form = $(this);

			if ($form.data('dmdChips')) {
				return;
			}
			$form.data('dmdChips', true);

			buildVariationChips($form);

			$form.on('click', '.dmd-variation-chip', function () {
				var $chip = $(this);

				if ($chip.prop('disabled')) {
					return;
				}

				var value = $chip.attr('data-dmd-chip-value');
				var $field = $chip.closest('.dmd-variation-field');

				$field.find('select').first().val(value).trigger('change');

				// The rebuild replaced the node that was clicked, so move focus
				// onto its replacement.
				$field
					.find('.dmd-variation-chip')
					.filter(function () {
						return $(this).attr('data-dmd-chip-value') === value;
					})
					.first()
					.trigger('focus');
			});

			$form.on('change', '.variations select', function () {
				buildVariationChips($form);
			});

			$form.on('woocommerce_update_variation_values reset_data', function () {
				buildVariationChips($form);
			});

			// WooCommerce tries to focus the select here; the chips are visible.
			$form.on('reset_focus', function () {
				$form.find('.dmd-variation-chip:not(.is-unavailable)').first().trigger('focus');
			});
		});
	}

	/* ------------------------------------------------------------------
	 * Variable products — preselect the first purchasable variation
	 * ------------------------------------------------------------------ */
	function pickFirstVariation($form) {
		var raw = $form.attr('data-product_variations');
		var variations = null;

		if (raw) {
			try {
				variations = JSON.parse(raw);
			} catch (e) {
				variations = null;
			}
		}

		if (!Array.isArray(variations) || !variations.length) {
			return null;
		}

		var match = null;

		variations.forEach(function (variation) {
			if (!match && variation.is_in_stock && variation.is_purchasable) {
				match = variation;
			}
		});

		if (!match) {
			variations.forEach(function (variation) {
				if (!match && variation.is_in_stock) {
					match = variation;
				}
			});
		}

		return match || variations[0];
	}

	function autoSelectVariation($form) {
		if ($form.data('dmdAutoSelected')) {
			return;
		}

		var $selects = $form.find('.variations select');

		if (!$selects.length) {
			return;
		}

		var alreadyChosen = false;
		$selects.each(function () {
			if ($(this).val()) {
				alreadyChosen = true;
			}
		});

		if (!alreadyChosen) {
			var variation = pickFirstVariation($form);

			// Prefer a combination WooCommerce already knows is purchasable; it
			// cannot be invalid, unlike picking the first option of each select.
			if (variation && variation.attributes) {
				$selects.each(function () {
					var $select = $(this);
					var name = $select.data('attribute_name') || $select.attr('name');
					var value = variation.attributes[name];

					if (value) {
						$select.val(value);
					}
				});
			}

			// Anything still empty takes its first selectable option. This is
			// the only path when variations are fetched over AJAX.
			$selects.each(function () {
				var $select = $(this);

				if ($select.val()) {
					return;
				}

				var first = $select
					.find('option')
					.filter(function () {
						return this.value !== '' && !this.disabled;
					})
					.first()
					.val();

				if (first) {
					$select.val(first);
				}
			});

			$selects.first().trigger('change');
		}

		$form.data('dmdAutoSelected', true);
	}

	function initVariationAutoSelect() {
		if (typeof jQuery === 'undefined') {
			return;
		}

		var $ = jQuery;

		// WooCommerce fires this once the form is wired up.
		$(document).on('wc_variation_form', '.variations_form', function () {
			autoSelectVariation($(this));
		});

		// Cover a form WooCommerce has already initialised.
		$('.variations_form').each(function () {
			var $form = $(this);

			if ($form.data('product_variations')) {
				autoSelectVariation($form);
			}
		});
	}

	/* ------------------------------------------------------------------
	 * My account — login / register tabs
	 * ------------------------------------------------------------------ */
	function initAccountTabs() {
		var triggers = document.querySelectorAll('[data-dmd-tab]');

		if (!triggers.length) {
			return;
		}

		var panels = {
			login: document.getElementById('dmd-panel-login'),
			register: document.getElementById('dmd-panel-register')
		};

		var tabs = Array.prototype.slice.call(document.querySelectorAll('[role="tab"][data-dmd-tab]'));

		function activate(name, moveFocus) {
			if (!panels[name]) {
				return;
			}

			Object.keys(panels).forEach(function (key) {
				if (panels[key]) {
					panels[key].classList.toggle('hidden', key !== name);
				}
			});

			tabs.forEach(function (tab) {
				var isActive = tab.dataset.dmdTab === name;
				tab.classList.toggle('is-active', isActive);
				tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
			});

			if (moveFocus) {
				var field = panels[name].querySelector('input');
				if (field) {
					field.focus();
				}
			}
		}

		triggers.forEach(function (trigger) {
			trigger.addEventListener('click', function (event) {
				event.preventDefault();
				activate(trigger.dataset.dmdTab, trigger.getAttribute('role') !== 'tab');
			});
		});

		// Arrow keys walk the tablist.
		tabs.forEach(function (tab, index) {
			tab.addEventListener('keydown', function (event) {
				var offset = event.key === 'ArrowRight' ? 1 : (event.key === 'ArrowLeft' ? -1 : 0);

				if (!offset) {
					return;
				}

				event.preventDefault();

				var next = tabs[(index + offset + tabs.length) % tabs.length];
				next.focus();
				activate(next.dataset.dmdTab, false);
			});
		});

		if (window.location.hash === '#register' && panels.register) {
			activate('register', false);
		}
	}

	/* ------------------------------------------------------------------
	 * Boot
	 * ------------------------------------------------------------------ */
	function boot() {
		initMobileMenu();
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
		initVariationChips();
		initVariationAutoSelect();
		initAccountTabs();
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
