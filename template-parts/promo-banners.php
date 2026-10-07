<?php
/**
 * Two side-by-side promo banners (Customizer → Promo banners).
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_banners = array();
for ( $dmd_i = 1; $dmd_i <= 2; $dmd_i++ ) {
	$dmd_image = dmd_resolve_image( get_theme_mod( "dmd_promo_{$dmd_i}_image" ) );
	if ( $dmd_image ) {
		$dmd_banners[] = array(
			'image' => $dmd_image,
			'url'   => get_theme_mod( "dmd_promo_{$dmd_i}_url" ),
		);
	}
}

if ( ! $dmd_banners ) {
	return;
}
?>
<section class="dmd-promos pb-2" aria-label="<?php esc_attr_e( 'Offers', 'digital-mudir-dokan' ); ?>">
	<div class="dmd-container grid gap-3 sm:gap-5 <?php echo esc_attr( count( $dmd_banners ) > 1 ? 'md:grid-cols-2' : '' ); ?>">
		<?php foreach ( $dmd_banners as $dmd_banner ) : ?>
			<?php $dmd_tag = $dmd_banner['url'] ? 'a' : 'div'; ?>
			<<?php echo esc_html( $dmd_tag ); ?> class="group block aspect-[2/1] overflow-hidden rounded-2xl bg-surface" <?php echo $dmd_banner['url'] ? 'href="' . esc_url( $dmd_banner['url'] ) . '"' : ''; ?>>
				<img src="<?php echo esc_url( $dmd_banner['image']['url'] ); ?>"
					<?php if ( $dmd_banner['image']['srcset'] ) : ?>
						srcset="<?php echo esc_attr( $dmd_banner['image']['srcset'] ); ?>"
						sizes="(min-width: 768px) 620px, 100vw"
					<?php endif; ?>
					alt="<?php esc_attr_e( 'Special offer', 'digital-mudir-dokan' ); ?>"
					loading="lazy"
					decoding="async"
					class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]" />
			</<?php echo esc_html( $dmd_tag ); ?>>
		<?php endforeach; ?>
	</div>
</section>
