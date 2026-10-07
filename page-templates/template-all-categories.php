<?php
/**
 * Template Name: All Categories
 *
 * Every top-level product category with its image, product count and
 * sub-categories. The header's "All categories" links point here once a
 * page uses this template.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$dmd_categories = function_exists( 'WC' ) ? dmd_get_top_categories() : array();
?>
<section class="dmd-archive-header border-b border-line bg-gradient-to-b from-green-soft/70 to-white">
	<div class="dmd-container py-6 sm:py-8">
		<?php dmd_breadcrumbs(); ?>
		<h1 class="dmd-heading mt-4 !text-2xl sm:!text-3xl"><?php the_title(); ?></h1>
		<p class="m-0 mt-1 text-sm text-muted">
			<?php
			/* translators: %s: number of categories. */
			echo esc_html( sprintf( _n( '%s category', '%s categories', count( $dmd_categories ), 'digital-mudir-dokan' ), number_format_i18n( count( $dmd_categories ) ) ) );
			?>
		</p>

		<?php
		while ( have_posts() ) :
			the_post();
			if ( trim( get_the_content() ) ) :
				?>
				<div class="dmd-prose mt-3 max-w-3xl !text-sm text-muted"><?php the_content(); ?></div>
				<?php
			endif;
		endwhile;
		?>
	</div>
</section>

<div class="dmd-container py-8 sm:py-12">
	<?php if ( $dmd_categories ) : ?>
		<ul class="m-0 grid list-none grid-cols-1 gap-4 p-0 sm:grid-cols-2 lg:grid-cols-3 lg:gap-5 xl:grid-cols-4">
			<?php foreach ( $dmd_categories as $dmd_cat ) : ?>
				<?php
				$dmd_link     = get_term_link( $dmd_cat );
				$dmd_img      = dmd_get_term_image_url( $dmd_cat, 'woocommerce_thumbnail' );
				$dmd_children = get_terms(
					array(
						'taxonomy'   => 'product_cat',
						'parent'     => $dmd_cat->term_id,
						'hide_empty' => false,
						'number'     => 6,
					)
				);
				$dmd_children = is_wp_error( $dmd_children ) ? array() : $dmd_children;
				?>
				<li class="list-none">
					<article class="group flex h-full flex-col overflow-hidden rounded-xl border border-line bg-white transition duration-200 hover:-translate-y-0.5 hover:border-green/25 hover:shadow-lift">
						<a class="relative flex aspect-[4/3] items-center justify-center overflow-hidden bg-green-soft" href="<?php echo esc_url( $dmd_link ); ?>" tabindex="-1" aria-hidden="true">
							<?php if ( $dmd_img ) : ?>
								<img src="<?php echo esc_url( $dmd_img ); ?>" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover mix-blend-multiply transition-transform duration-500 group-hover:scale-105" />
							<?php else : ?>
								<span class="text-green"><?php dmd_the_icon( 'grocery', 48 ); ?></span>
							<?php endif; ?>
							<span class="absolute right-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-green shadow-soft">
								<?php
								/* translators: %s: number of products. */
								echo esc_html( sprintf( _n( '%s item', '%s items', $dmd_cat->count, 'digital-mudir-dokan' ), number_format_i18n( $dmd_cat->count ) ) );
								?>
							</span>
						</a>

						<div class="flex flex-1 flex-col p-4">
							<h2 class="m-0 text-base font-semibold leading-snug">
								<a class="text-ink hover:text-green" href="<?php echo esc_url( $dmd_link ); ?>"><?php echo esc_html( $dmd_cat->name ); ?></a>
							</h2>

							<?php if ( $dmd_cat->description ) : ?>
								<p class="dmd-line-clamp-2 m-0 mt-1 text-[13px] text-muted"><?php echo esc_html( wp_strip_all_tags( $dmd_cat->description ) ); ?></p>
							<?php endif; ?>

							<?php if ( $dmd_children ) : ?>
								<ul class="m-0 mt-3 flex list-none flex-wrap gap-1.5 p-0">
									<?php foreach ( $dmd_children as $dmd_child ) : ?>
										<li class="list-none">
											<a class="inline-flex rounded-full bg-surface px-2.5 py-1 text-xs text-ink hover:bg-green-soft hover:text-green" href="<?php echo esc_url( get_term_link( $dmd_child ) ); ?>"><?php echo esc_html( $dmd_child->name ); ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<a class="dmd-link-arrow mt-auto pt-4" href="<?php echo esc_url( $dmd_link ); ?>">
								<?php esc_html_e( 'Shop now', 'digital-mudir-dokan' ); ?>
								<?php dmd_the_icon( 'chevron-r', 14 ); ?>
								<span class="screen-reader-text"><?php echo esc_html( $dmd_cat->name ); ?></span>
							</a>
						</div>
					</article>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<p class="text-center text-muted"><?php esc_html_e( 'No product categories yet.', 'digital-mudir-dokan' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
