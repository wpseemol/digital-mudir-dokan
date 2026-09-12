#!/usr/bin/env node
/**
 * Safelist audit.
 *
 * WooCommerce, WordPress core and select2 print class names at runtime that
 * Tailwind's content scanner never sees, so their rules get tree-shaken out of
 * the build. This script compares the selectors declared in the source
 * stylesheet against the theme source and the current safelist, and reports
 * anything that would silently disappear.
 *
 * Usage: npm run audit:safelist
 */

const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const cssPath = path.join(root, 'assets/css/tailwind.src.css');
const css = fs.readFileSync(cssPath, 'utf8');
const config = require(path.join(root, 'tailwind.config.js'));

// Every class name we declare a rule for.
const selectors = new Set(
	Array.from(css.matchAll(/\.([A-Za-z_][\w-]*)/g), (m) => m[1])
);

// Tailwind utilities referenced inside @apply are not selectors we define.
const applied = new Set();
for (const match of css.matchAll(/@apply([^;]+);/g)) {
	match[1]
		.trim()
		.split(/\s+/)
		.forEach((token) => applied.add(token.replace(/^\./, '')));
}

// Everything the content scanner can actually read.
function collect(dir, acc = []) {
	for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
		if (entry.name === 'node_modules' || entry.name.startsWith('.')) continue;
		const full = path.join(dir, entry.name);
		if (entry.isDirectory()) {
			collect(full, acc);
		} else if (/\.(php|js)$/.test(entry.name)) {
			acc.push(full);
		}
	}
	return acc;
}

const source = collect(root)
	.filter((file) => !file.includes('tools' + path.sep))
	.map((file) => fs.readFileSync(file, 'utf8'))
	.join('\n');

const safelisted = new Set(
	(config.safelist || []).filter((entry) => typeof entry === 'string')
);

const missing = [...selectors]
	.filter(
		(name) =>
			!applied.has(name) &&
			!safelisted.has(name) &&
			!name.startsWith('dmd-') &&
			!source.includes(name)
	)
	.sort();

if (!missing.length) {
	console.log('Safelist is complete — every declared selector is reachable.');
	process.exit(0);
}

console.log('These selectors would be dropped from the build:\n');
missing.forEach((name) => console.log("    '" + name + "',"));
console.log('\nAdd them to `safelist` in tailwind.config.js, then rebuild.');
process.exit(1);
