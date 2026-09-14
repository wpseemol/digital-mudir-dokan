# GEMINI.md

## Project Overview

**Digital Mudir Dokan** is a fully customized WordPress + WooCommerce theme tailored for a grocery e-commerce platform in Bangladesh (with specific support for Bangla typography and localized features). It features a modern, high-performance design using **Tailwind CSS**, optimized for mobile responsiveness, accessibility, and lightweight page speed.

### Tech Stack
- **CMS Platform:** WordPress
- **E-Commerce engine:** WooCommerce
- **CSS Framework:** Tailwind CSS 3.4+ (compiled from `assets/css/tailwind.src.css` to `assets/css/theme.css`)
- **Fonts:** Poppins (display & English typography) and Hind Siliguri (Bangla body typography)
- **Asset Loader & Configuration:** Node.js-based build processes for styling.

### Architecture & File Structure
```
├── style.css                  # Theme metadata header (design is in assets/css/theme.css)
├── functions.php              # Core bootstrap: setups theme supports, enqueues styles/scripts, and imports inc/ modular helpers
├── front-page.php             # Main landing page template assembling custom modules
├── header.php / footer.php    # Shared document frame/page layout
├── inc/                       # Modular PHP functional files
│   ├── class-dmd-nav-walker.php # Accessible multi-level dropdown main menu walker
│   ├── customizer.php         # Localized shop configurations (topbar, hero slider, home settings, delivery/express, shop details, social media)
│   ├── seo-schema.php         # Native SEO meta tags and JSON-LD structural markup (Organization, Product, WebSite, etc.)
│   ├── template-functions.php # Helper utilities (SVG icons system, clean class bindings, WP KSES safe HTML overrides)
│   ├── template-tags.php      # Display components (SEO-friendly breadcrumbs, slide displays, pagination)
│   └── woocommerce.php        # Core WooCommerce customization hooks, AJAX-driven updates, and specialized express checkout logic
├── template-parts/            # Modular content blocks (hero slider, product sections, trust badge rows, video reviews wall, and blog)
├── woocommerce/               # High-fidelity custom template overrides (loops, single product summary, cart, checkout, and accounts pages)
├── assets/
│   ├── css/
│   │   ├── tailwind.src.css   # Source CSS containing Tailwind directives and manual style classes
│   │   └── theme.css          # Compiled and minified production stylesheet (DO NOT EDIT)
│   └── js/
│       ├── customizer.js      # PostMessage handler for instant Customizer preview updates
│       └── theme.js           # Lightweight vanilla JS for components (slider, mobile menus, lazy loading, and quantity adjustments)
└── tools/
    └── audit-safelist.js      # Custom Node.js developer script to check for missing CSS safelist selectors
```

---

## Building and Running

### Prerequisites
- A running WordPress local server (e.g., Local WP, DevKinsta, Laragon, or custom Docker setup) with WooCommerce active.
- Node.js installed locally.

### Development Commands
Run these commands from the theme directory:

1. **Install Dependencies:**
   ```bash
   npm install
   ```

2. **Compile CSS once (Minified):**
   ```bash
   npm run build:css
   ```

3. **Development Mode (Automatic Compilation on Save):**
   ```bash
   npm run watch:css
   ```

4. **Verify Tailwind CSS Safelist:**
   Because WordPress plugins like WooCommerce and Select2 injection components render HTML class names dynamically at runtime, they are not scanned by Tailwind. These selectors must be listed in `tailwind.config.js` under `safelist`.
   Run the audit script to check if any required selectors from your stylesheet were dropped:
   ```bash
   npm run audit:safelist
   ```

---

## Development Conventions

### Styling & Tailwind CSS
- **DO NOT** edit `assets/css/theme.css` directly. Make style changes in `assets/css/tailwind.src.css` or write standard utility classes in markup files (`.php` files).
- Always use the preset theme design tokens for consistency:
  - **Green (Brand):** `bg-green` (Primary #113D21), `bg-green-dark` (Hover/Dark #058a36), `bg-green-soft` (Fills #e8f8ee)
  - **Typography & Neutrals:** `text-ink` (#1b1b1b), `text-muted` (#6b7280), `border-line` (#e6e9ec), `bg-surface` (#f3f5f7)
  - **Fonts:** Display fonts inherit `font-display` (Poppins + Hind Siliguri); body text uses `font-bangla` (Hind Siliguri).
- Ensure any added styles/custom selectors not explicitly visible in PHP files are registered in the `safelist` section of `tailwind.config.js`. Run `npm run audit:safelist` to identify missing selectors before staging.

### WordPress PHP Development
- **Strict Escaping & Security:** Every variable printed must be fully sanitized or escaped.
  - Text escaping: `esc_html()`, `esc_html_e()`, `esc_html__()`
  - Attributes escaping: `esc_attr()`, `esc_attr_e()`, `esc_attr__()`
  - URL escaping: `esc_url()`
  - Rich HTML elements escaping: Use `wp_kses_post()` or standard WordPress KSES helpers.
- **Text Domain:** Use `'digital-mudir-dokan'` for all translatable strings.
- **Hook Rewiring:** Ensure templates trigger default WooCommerce hook tags (`woocommerce_single_product_summary`, `woocommerce_before_shop_loop`, etc.) to stay compatible with third-party extensions. For bespoke layouts, unhook WooCommerce callbacks in `inc/woocommerce.php` and render the replacement markup natively.

### Accessibility (a11y) & SEO
- **Semantic HTML:** Always use semantic elements like `<header>`, `<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`, and `<footer>` where appropriate.
- **Landmarks and Headings:** Ensure exactly one `<h1>` per page. Avoid generic headings without structured context.
- **SEO & Custom Schemas:** The theme includes JSON-LD structured data. These features automatically disable themselves if popular SEO plugins (Yoast, Rank Math, SEOPress, AIOSEO) are detected, preventing duplicate metadata markup. Maintain this verification pattern in `inc/seo-schema.php`.
- **Motion Reduction:** The custom slider and page interactions should always honor the `prefers-reduced-motion` media query (this is automated in base styling but must be respected in JS/CSS additions).

### Customizer Configurations
All major customization parameters live inside **Appearance → Customize → Digital Mudir Dokan** panel. New homepage features or settings must follow the existing pattern in `inc/customizer.php` using WordPress Customizer controls and enqueued with postMessage transport for instant live-preview support in `assets/js/customizer.js`.

### New Features & Customizer Updates
- **Logo Width:** Adjustable via *Appearance → Customize → Digital Mudir Dokan → Header*.
- **Hero Slider:** Supports both 'Boxed' and 'Full width' layout modes via *Appearance → Customize → Digital Mudir Dokan → Hero slider / Banner*.
