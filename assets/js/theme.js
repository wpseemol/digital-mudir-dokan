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
	 * Hero slider
	 * ------------------------------------------------------------------ */
	function initSlider(root) {
		var track = root.querySelector('[data-dmd-track]');
		if (!track) {
			return;
		}

		var slides = Array.prototype.slice.call(track.children);
		if (slides.length < 2) {
			return;
		}

		var dots = Array.prototype.slice.call(root.querySelectorAll('[data-dmd-dot]'));
		var prev = root.querySelector('[data-dmd-prev]');
		var next = root.querySelector('[data-dmd-next]');
		var index = 0;
		var timer = null;
		var interval = parseInt(root.dataset.interval, 10) || 6000;
		var autoplay = root.dataset.autoplay === 'true' && !prefersReducedMotion;

		function render() {
			track.style.transform = 'translateX(' + (index * -100) + '%)';

			slides.forEach(function (slide, i) {
				// Hide off-screen slides from assistive tech and tab order.
				slide.setAttribute('aria-hidden', i === index ? 'false' : 'true');
				slide.querySelectorAll('a, button').forEach(function (el) {
					el.tabIndex = i === index ? 0 : -1;
				});
			});

			dots.forEach(function (dot, i) {
				dot.setAttribute('aria-current', i === index ? 'true' : 'false');
			});
		}

		function goTo(target) {
			index = (target + slides.length) % slides.length;
			render();
		}

		function start() {
			if (!autoplay) {
				return;
			}
			stop();
			timer = window.setInterval(function () {
				goTo(index + 1);
			}, interval);
		}

		function stop() {
			if (timer) {
				window.clearInterval(timer);
				timer = null;
			}
		}

		if (prev) {
			prev.addEventListener('click', function () {
				goTo(index - 1);
				start();
			});
		}

		if (next) {
			next.addEventListener('click', function () {
				goTo(index + 1);
				start();
			});
		}

		dots.forEach(function (dot) {
			dot.addEventListener('click', function () {
				goTo(parseInt(dot.dataset.dmdDot, 10));
				start();
			});
		});

		// Arrow keys when the carousel has focus.
		root.addEventListener('keydown', function (event) {
			if (event.key === 'ArrowLeft') {
				goTo(index - 1);
			} else if (event.key === 'ArrowRight') {
				goTo(index + 1);
			}
		});

		// Pause while the visitor is reading or interacting.
		root.addEventListener('mouseenter', stop);
		root.addEventListener('mouseleave', start);
		root.addEventListener('focusin', stop);
		root.addEventListener('focusout', start);

		document.addEventListener('visibilitychange', function () {
			if (document.hidden) {
				stop();
			} else {
				start();
			}
		});

		// Touch swipe.
		var startX = null;
		track.addEventListener('touchstart', function (event) {
			startX = event.touches[0].clientX;
			stop();
		}, { passive: true });

		track.addEventListener('touchend', function (event) {
			if (startX === null) {
				return;
			}
			var delta = event.changedTouches[0].clientX - startX;
			if (Math.abs(delta) > 40) {
				goTo(delta < 0 ? index + 1 : index - 1);
			}
			startX = null;
			start();
		});

		render();
		start();
	}

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
	 * Boot
	 * ------------------------------------------------------------------ */
	function boot() {
		document.querySelectorAll('[data-dmd-slider]').forEach(initSlider);

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
