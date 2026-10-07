# Repository Guidelines

A WooCommerce-first WordPress theme for a Bangladeshi grocery shop, built with Tailwind CSS 3.4 and semantic HTML5. Text domain: `digital-mudir-dokan`. Deeper conventions live in `GEMINI.md`.

## Project Structure & Module Organization

```
*.php                  Root templates (front-page, header, footer, single, archive, 404…)
inc/                   Modular PHP: template-functions, template-tags, customizer,
                       seo-schema, woocommerce, class-dmd-nav-walker
template-parts/        Homepage blocks (hero-slider, product-section, trust-row, video-wall…)
woocommerce/           Plugin template overrides (loops, single product, cart, checkout, myaccount)
assets/css/            tailwind.src.css (edit), theme.css (compiled), woocommerce-overrides.css
assets/js/             theme.js, live-search.js, customizer.js
tools/                 audit-safelist.js
languages/             digital-mudir-dokan.pot
```

There is no separate test directory.

## Build, Test, and Development Commands

Run from the theme root:

```bash
npm install            # install Tailwind CLI
npm run build:css      # compile minified assets/css/theme.css
npm run watch:css      # rebuild on save
npm run audit:safelist # flag selectors that would be tree-shaken away
```

`assets/css/theme.css` is committed for distribution but must never be hand-edited.

## Coding Style & Naming Conventions

- **PHP:** WordPress Coding Standards, tab indentation, Yoda-free conditions, `defined( 'ABSPATH' ) || exit;` in every `inc/` file. Prefix functions `dmd_`, constants `DMD_`, and docblocks with `@package Digital_Mudir_Dokan`.
- **JS:** tab indentation, vanilla ES5-style IIFEs with `'use strict';`, progressive enhancement only.
- **CSS:** extend `tailwind.src.css`; use theme tokens (`green`, `green-dark`, `ink`, `muted`, `line`, `surface`, `font-display`, `font-bangla`).
- Escape all output: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`.
- Classes generated at runtime by WooCommerce/select2 belong in `safelist` in `tailwind.config.js`.

## Testing Guidelines

No automated test suite exists. Before committing:

1. Run `npm run audit:safelist` — it must exit clean.
2. Rebuild the CSS and smoke-test the front page, shop archive, single product, cart, checkout and account pages in a local WordPress (Local WP) with WooCommerce active.
3. Verify one `<h1>` per page, keyboard focus, and `prefers-reduced-motion` behaviour.
4. Test customizer options under **Appearance → Customize → Digital Mudir Dokan**.

## Commit & Pull Request Guidelines

History uses imperative, sentence-case subjects without type prefixes or trailing periods, e.g. `Enhance shop and category grid column settings`. Multi-file work includes a short bullet body explaining each change.

Pull requests should state what changed and why, list affected templates/customizer options, include screenshots for UI changes, and confirm rebuilt CSS plus a passing safelist audit.

## Security & Configuration Tips

Never commit credentials or local environment paths. Remote assets (Google Fonts, Swiper CDN) are enqueued in `functions.php`. Keep templates firing WooCommerce action hooks (`woocommerce_single_product_summary`, `woocommerce_before_shop_loop`) so third-party extensions remain compatible.
