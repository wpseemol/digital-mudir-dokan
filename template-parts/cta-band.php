<?php
/**
 * "Order by phone / WhatsApp" band.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_contact = dmd_contact();

if ( ! $dmd_contact['hotline'] && ! $dmd_contact['whatsapp'] ) {
	return;
}
?>
<section class="dmd-cta py-4 sm:py-6" aria-labelledby="dmd-cta-heading">
	<div class="dmd-container">
		<div class="relative overflow-hidden rounded-2xl bg-green px-6 py-8 text-white sm:px-10 sm:py-10">
			<div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-green-dark/40 blur-2xl" aria-hidden="true"></div>
			<div class="pointer-events-none absolute -bottom-24 left-1/4 h-56 w-56 rounded-full bg-white/5" aria-hidden="true"></div>

			<div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
				<div class="max-w-xl">
					<h2 id="dmd-cta-heading" class="m-0 font-display text-xl font-bold leading-tight text-white sm:text-2xl">
						<?php echo esc_html( get_theme_mod( 'dmd_cta_title', __( 'ফোনে বা WhatsApp-এ সহজেই অর্ডার করুন', 'digital-mudir-dokan' ) ) ); ?>
					</h2>
					<p class="m-0 mt-2 text-sm text-white/80 sm:text-base">
						<?php echo esc_html( get_theme_mod( 'dmd_cta_text', __( 'আমাদের প্রতিনিধি আপনার অর্ডার নিশ্চিত করে দ্রুত ডেলিভারির ব্যবস্থা করবেন।', 'digital-mudir-dokan' ) ) ); ?>
					</p>
				</div>

				<div class="flex flex-wrap gap-3">
					<?php if ( $dmd_contact['hotline'] ) : ?>
						<a class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-green transition hover:bg-green-soft hover:text-green" href="tel:<?php echo esc_attr( dmd_tel( $dmd_contact['hotline'] ) ); ?>">
							<?php dmd_the_icon( 'phone', 18 ); ?>
							<?php echo esc_html( $dmd_contact['hotline'] ); ?>
						</a>
					<?php endif; ?>
					<?php if ( $dmd_contact['whatsapp'] ) : ?>
						<a class="inline-flex items-center gap-2 rounded-lg border border-white/30 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/20 hover:text-white" href="https://wa.me/<?php echo esc_attr( ltrim( dmd_tel( $dmd_contact['whatsapp'] ), '+' ) ); ?>" target="_blank" rel="noopener">
							<?php dmd_the_icon( 'whatsapp', 18 ); ?>
							<?php esc_html_e( 'WhatsApp', 'digital-mudir-dokan' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
