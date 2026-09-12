=== Digital Mudir Dokan ===

Contributors: organicusers
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: e-commerce, grocery, woocommerce, custom-menu, custom-logo, featured-images, threaded-comments, translation-ready, rtl-language-support

A WooCommerce-first theme for grocery and organic food shops, built with Tailwind CSS and semantic HTML5.

== Description ==

Digital Mudir Dokan is built for a Bangladeshi online grocery: a hero slider, a
top-selling row, a full product grid, customer video testimonials and a blog
strip on the front page, with tailored templates for the shop, single product,
cart, checkout and account pages.

The design pairs Poppins for Latin headings with Hind Siliguri for Bangla body
copy, so mixed-script product names and prices set correctly without extra work.

Features:

* Hero slider with up to four slides, managed in the Customizer. Autoplay pauses
  on hover, on keyboard focus and when the tab is hidden, and is skipped entirely
  for visitors who prefer reduced motion. Without JavaScript the slider degrades
  to a horizontal snap scroller, so every slide stays reachable.
* An express order button that adds a product to the cart and goes straight to
  checkout, on both the product card and the single product page.
* Product cards with discount percentage, "hot" and "sold out" badges.
* Shop archive with a category sidebar, a products-per-page control and sorting.
* Single product page with gallery, delivery times, stock state, share links and
  a recently viewed row.
* Rebuilt cart, checkout, login/register and account templates.
* JSON-LD structured data for Organization, WebSite, Product, BreadcrumbList and
  BlogPosting, plus Open Graph and Twitter card tags. All of it stands down
  automatically when Yoast, Rank Math, SEOPress or AIOSEO is active.
* Semantic HTML5 landmarks throughout, a skip link, visible keyboard focus and
  labelled navigation regions.

== Installation ==

1. In WordPress go to Appearance > Themes > Add New > Upload Theme.
2. Choose digital-mudir-dokan.zip and click Install Now, then Activate.
3. Install and activate WooCommerce if it is not already running, and complete
   its setup wizard so the shop, cart, checkout and account pages exist.
4. Go to Appearance > Customize > Digital Mudir Dokan and fill in Shop details,
   Hero slider, Homepage sections and Social profiles.
5. Under Appearance > Menus, assign a menu to the Primary location.
6. Under Settings > Reading, set your front page. The theme's homepage layout is
   used either way, and any content on an assigned static front page is printed
   between the product grids and the video wall.

== Frequently Asked Questions ==

= The homepage product rows are empty. =

They read from WooCommerce. Publish some products, and they appear. The "top
selling" row falls back to your newest products until the shop has sales data.

= How do I change the theme colours? =

The palette lives in two places that must stay in step: the CSS custom
properties at the top of assets/css/tailwind.src.css and the `colors` block in
tailwind.config.js. Edit both, then rebuild the stylesheet (see below).

= How do I rebuild the CSS after editing templates? =

The theme ships with a compiled stylesheet, so you only need this if you change
markup or styles:

    npm install
    npm run build:css

Tailwind removes any class it cannot find in the source files. WooCommerce
prints many class names at runtime, so those are listed in `safelist` in
tailwind.config.js. After adding rules for plugin markup, run
`npm run audit:safelist` to check nothing will be stripped from the build.

= Why does the theme replace WooCommerce's stylesheets? =

The three plugin stylesheets are dequeued and their styling is provided by the
theme build instead, which avoids two sets of rules fighting each other. The
handful of things those files also carried — the star rating font, the gallery
slider layout and the AJAX loading spinner — are redrawn in the theme CSS.

== Copyright ==

Digital Mudir Dokan WordPress Theme, (C) 2026
Digital Mudir Dokan is distributed under the terms of the GNU GPL v2 or later.

This theme bundles no third-party image, font or icon files. Poppins and Hind
Siliguri are loaded from Google Fonts at runtime, both under the SIL Open Font
License v1.1. The interface icons are original SVG paths defined in
inc/template-functions.php.

Tailwind CSS is a build-time dependency only and is not redistributed in the
theme. Tailwind CSS is licensed MIT, Copyright Tailwind Labs Inc.

== Changelog ==

= 1.0.0 =
* Initial release.
