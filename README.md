# Digital Mudir Dokan

A WooCommerce theme for a Bangladeshi online grocery, built with Tailwind CSS
and semantic HTML5.

## Install

Upload `digital-mudir-dokan.zip` via **Appearance → Themes → Add New → Upload
Theme**, activate it, then make sure WooCommerce is installed and its setup
wizard has run so the shop, cart, checkout and account pages exist.

Then work through **Appearance → Customize → Digital Mudir Dokan**:

| Section | What to set |
| --- | --- |
| Announcement bar | The green strip above the header, and whether it shows |
| Hero slider | Up to four slides: image, headline, supporting line, button, plus autoplay and timing |
| Homepage sections | Headings and product counts for the two grids, and the YouTube links for the video wall |
| Shop details | Phones, emails, address, trade licence, WhatsApp, Messenger, footer disclaimer, payment strip image |
| Delivery | The delivery times shown on each product page, and the express order button label |
| Social profiles | Facebook, TikTok, Instagram, X, YouTube |

Assign a menu to the **Primary** location under Appearance → Menus. Until you
do, the header lists your product categories automatically.

## Front page order

    hero slider → top selling → all products → promises row
    → static page content (if any) → customer videos → blog

## File map

    style.css                  Theme header only; the design is in assets/css/theme.css
    functions.php              Supports, image sizes, menus, widget areas, assets
    front-page.php             Homepage assembly
    header.php / footer.php    Page shell
    index / archive / search / single / page / 404 / sidebar / searchform / comments

    inc/
      template-functions.php   Icons, social helpers, body classes, kses allowlists
      template-tags.php        Breadcrumbs, pagination, hero slides, contact details
      class-dmd-nav-walker.php Accessible dropdown menu walker
      customizer.php           Every option listed above
      seo-schema.php           JSON-LD, Open Graph, Twitter, meta description
      woocommerce.php          Hook rewiring, express order, cart fragments

    template-parts/            hero-slider, product-section, trust-row, video-wall,
                               blog-strip, content, content-search, content-none

    woocommerce/               content-product, archive-product, single-product,
                               content-single-product, cart/, checkout/, myaccount/,
                               global/quantity-input

    assets/css/tailwind.src.css  Source stylesheet — edit this
    assets/css/theme.css         Compiled output — do not edit by hand
    assets/js/theme.js           Slider, menu, quantity steppers, lazy video
    tools/audit-safelist.js      Checks nothing is tree-shaken out of the build

## Rebuilding the CSS

Only needed if you change markup or styles; a compiled stylesheet ships with the
theme.

    npm install
    npm run build:css      # one-off, minified
    npm run watch:css      # rebuild on save
    npm run audit:safelist # verify no plugin styles get stripped

Tailwind only keeps classes it can find in the source. WooCommerce prints many
class names at runtime, so those are pinned in `safelist` in
`tailwind.config.js`. If you add CSS for plugin markup, run the audit — it
compares the selectors you declared against the theme source and the safelist,
and tells you exactly what to add.

## Notes on the WooCommerce integration

The three plugin stylesheets are dequeued and replaced by the theme build, so
there is only one set of rules to reason about. The functional pieces those
files also carried are redrawn in `tailwind.src.css`: the star rating (which
normally relies on a bundled icon font), the FlexSlider gallery layout, and the
AJAX loading spinner.

Templates lay out the markup themselves but still fire the plugin's action
hooks — `woocommerce_single_product_summary`, `woocommerce_before_shop_loop`,
`woocommerce_archive_description` and the rest — so extensions that attach to
them keep working. Only the callbacks a template draws by hand are unhooked.

## Accessibility and SEO

One `h1` per page; `header`/`nav`/`main`/`article`/`section`/`aside`/`footer`
landmarks; a skip link; labelled navigation regions; visible keyboard focus;
`prefers-reduced-motion` respected by the slider.

Structured data covers Organization, WebSite, Product (with offers and ratings),
BreadcrumbList and BlogPosting, alongside Open Graph and Twitter card tags. If
Yoast, Rank Math, SEOPress or AIOSEO is active the theme prints none of it, so
pages never carry two competing descriptions or two Product entities.

## Child themes

Everything is hookable and the templates are standard, so a child theme with a
`style.css` header and `@import` of the parent stylesheet is enough. Override
any file by copying it into the child at the same path.
