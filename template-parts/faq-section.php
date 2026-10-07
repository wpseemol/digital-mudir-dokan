<?php
/**
 * FAQ section — native disclosure widgets, so it works without JavaScript.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_default_faqs = array(
	array(
		'q' => __( 'ডেলিভারি চার্জ কত?', 'digital-mudir-dokan' ),
		'a' => __( 'আমাদের ডেলিভারি চার্জ ঢাকার ভেতরে ৫০ টাকা এবং ঢাকার বাইরে ১০০ টাকা।', 'digital-mudir-dokan' ),
	),
	array(
		'q' => __( 'পণ্য রিটার্ন করা যাবে?', 'digital-mudir-dokan' ),
		'a' => __( 'হ্যাঁ, পণ্য পাওয়ার ২৪ ঘণ্টার মধ্যে সমস্যা জানালে আমরা রিটার্ন বা পরিবর্তন করে থাকি।', 'digital-mudir-dokan' ),
	),
	array(
		'q' => __( 'আপনারা কি ক্যাশ অন ডেলিভারি দেন?', 'digital-mudir-dokan' ),
		'a' => __( 'হ্যাঁ, আমরা সারা বাংলাদেশে ক্যাশ অন ডেলিভারি দিয়ে থাকি।', 'digital-mudir-dokan' ),
	),
);

$dmd_faqs = array();
for ( $dmd_i = 1; $dmd_i <= 5; $dmd_i++ ) {
	$dmd_q = get_theme_mod( "dmd_faq_{$dmd_i}_q" );
	$dmd_a = get_theme_mod( "dmd_faq_{$dmd_i}_a" );

	if ( $dmd_q && $dmd_a ) {
		$dmd_faqs[] = array(
			'q' => $dmd_q,
			'a' => $dmd_a,
		);
	} elseif ( isset( $dmd_default_faqs[ $dmd_i - 1 ] ) ) {
		$dmd_faqs[] = $dmd_default_faqs[ $dmd_i - 1 ];
	}
}

if ( ! $dmd_faqs ) {
	return;
}

$dmd_faq_eyebrow = get_theme_mod( 'dmd_faq_eyebrow', __( 'সচরাচর জিজ্ঞাসিত প্রশ্নাবলী', 'digital-mudir-dokan' ) );
$dmd_faq_title   = get_theme_mod( 'dmd_faq_title', __( 'সাধারণ প্রশ্ন ও উত্তর', 'digital-mudir-dokan' ) );
$dmd_faq_text    = get_theme_mod( 'dmd_faq_text', __( 'আপনার প্রশ্নের উত্তর এখানে না পেলে আমাদের সাথে সরাসরি যোগাযোগ করুন।', 'digital-mudir-dokan' ) );
?>
<section class="dmd-section" aria-labelledby="dmd-faq-heading">
	<div class="dmd-container grid gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.6fr)] lg:gap-14">
		<div>
			<?php if ( $dmd_faq_eyebrow ) : ?>
				<p class="dmd-eyebrow"><?php echo esc_html( $dmd_faq_eyebrow ); ?></p>
			<?php endif; ?>
			<h2 id="dmd-faq-heading" class="dmd-heading"><?php echo esc_html( $dmd_faq_title ); ?></h2>
			<?php if ( $dmd_faq_text ) : ?>
				<p class="mt-3 text-sm text-muted sm:text-base"><?php echo esc_html( $dmd_faq_text ); ?></p>
			<?php endif; ?>
		</div>

		<div class="space-y-3">
			<?php foreach ( $dmd_faqs as $dmd_index => $dmd_faq ) : ?>
				<details class="group rounded-xl border border-line bg-white transition-colors open:border-green/40 open:shadow-soft" <?php echo 0 === $dmd_index ? 'open' : ''; ?>>
					<summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4 text-[15px] font-semibold text-ink [&::-webkit-details-marker]:hidden">
						<span><?php echo esc_html( $dmd_faq['q'] ); ?></span>
						<span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-soft text-green transition-transform duration-200 group-open:rotate-45">
							<?php dmd_the_icon( 'plus', 16 ); ?>
						</span>
					</summary>
					<div class="border-t border-line px-5 py-4 text-sm leading-relaxed text-muted">
						<?php echo esc_html( $dmd_faq['a'] ); ?>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
