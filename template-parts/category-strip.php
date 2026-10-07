<?php
/**
 * "Shop by category" strip on the front page.
 *
 * Scrolls sideways on phones and becomes a grid from md up. Categories without
 * a thumbnail fall back to the image of one of their products.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_count      = max( 4, min( 24, (int) get_theme_mod( 'dmd_home_categories_count', 12 ) ) );
$dmd_categories = dmd_get_top_categories( $dmd_count );

if ( ! $dmd_categories ) {
	return;
}
?>
<section class="dmd-section !pb-6 sm:!pb-8" aria-labelledby="dmd-home-categories-heading">
	<div class="dmd-container">
		<?php
		dmd_section_heading(
			array(
				'title'     => get_theme_mod( 'dmd_home_categories_title', __( 'Shop by category', 'digital-mudir-dokan' ) ),
				'id'        => 'dmd-home-categories-heading',
				'link_url'  => dmd_get_all_categories_url(),
				'link_text' => __( 'All categories', 'digital-mudir-dokan' ),
			)
		);
		?>

		<ul class="dmd-scroll-x -mx-4 my-0 list-none gap-3 px-4 pb-1 sm:gap-4 md:mx-0 md:grid md:grid-cols-4 md:overflow-visible md:px-0 lg:grid-cols-6">
			<?php foreach ( $dmd_categories as $dmd_cat ) : ?>
				<?php $dmd_img = dmd_get_term_image_url( $dmd_cat ); ?>
				<li class="w-[112px] shrink-0 list-none sm:w-[132px] md:w-auto">
					<a class="group flex h-full flex-col items-center gap-3 rounded-xl border border-line bg-white p-3 text-center transition hover:-translate-y-0.5 hover:border-green/30 hover:shadow-lift sm:p-4" href="<?php echo esc_url( get_term_link( $dmd_cat ) ); ?>">
						<span class="flex aspect-square w-full items-center justify-center overflow-hidden rounded-full bg-green-soft">
							<?php if ( $dmd_img ) : ?>
								<img src="<?php echo esc_url( $dmd_img ); ?>" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover mix-blend-multiply transition-transform duration-300 group-hover:scale-110" />
							<?php else : ?>
								<span class="text-green"><?php dmd_the_icon( 'grocery', 34 ); ?></span>
							<?php endif; ?>
						</span>
						<span class="min-w-0">
							<span class="dmd-line-clamp-2 block text-[13px] font-semibold leading-snug text-ink group-hover:text-green sm:text-sm"><?php echo esc_html( $dmd_cat->name ); ?></span>
							<span class="mt-0.5 block text-[11px] text-muted">
								<?php
								/* translators: %s: number of products. */
								echo esc_html( sprintf( _n( '%s item', '%s items', $dmd_cat->count, 'digital-mudir-dokan' ), number_format_i18n( $dmd_cat->count ) ) );
								?>
							</span>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
