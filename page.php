<?php
/**
 * Static page.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
<div class="bg-surface py-6">
    <div class="dmd-container flex flex-wrap items-center justify-between gap-3">
        <h1 class="m-0 text-xl font-bold sm:text-2xl"><?php the_title(); ?></h1>
        <?php dmd_breadcrumbs(); ?>
    </div>
</div>

<div class="dmd-container py-10">
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'dmd-page' ); ?>>

        <?php if ( has_post_thumbnail() ) : ?>
        <figure class="m-0 mb-8 overflow-hidden rounded-lg">
            <?php the_post_thumbnail( 'large', array( 'class' => 'w-full object-cover' ) ); ?>
        </figure>
        <?php endif; ?>

        <div class="dmd-prose ">
            <?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<nav class="mt-6 flex items-center gap-2 text-sm" aria-label="' . esc_attr__( 'Page', 'digital-mudir-dokan' ) . '"><span>' . esc_html__( 'Pages:', 'digital-mudir-dokan' ) . '</span>',
						'after'  => '</nav>',
					)
				);
				?>
        </div>

        <?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
    </article>
</div>
<?php
endwhile;

get_footer();