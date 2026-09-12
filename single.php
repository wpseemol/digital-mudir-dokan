<?php
/**
 * Single post.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
<div class="bg-surface py-4">
    <div class="dmd-container"><?php dmd_breadcrumbs(); ?></div>
</div>

<div class="dmd-container py-10">
    <div class="grid gap-8 <?php echo is_active_sidebar( 'sidebar-1' ) ? 'lg:grid-cols-[minmax(0,1fr)_300px]' : ''; ?>">

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'dmd-single-post' ); ?>>

            <header class="mb-6">
                <h1 class="m-0 text-2xl font-bold leading-tight sm:text-3xl"><?php the_title(); ?></h1>

                <p class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-muted m-0">
                    <?php dmd_posted_on(); ?>
                    <span class="inline-flex items-center gap-1.5">
                        <?php dmd_the_icon( 'user', 15 ); ?>
                        <?php the_author(); ?>
                    </span>
                    <?php if ( has_category() ) : ?>
                    <span><?php the_category( ', ' ); ?></span>
                    <?php endif; ?>
                </p>
            </header>

            <?php if ( has_post_thumbnail() ) : ?>
            <figure class="m-0 mb-6 overflow-hidden rounded-lg">
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

            <?php if ( has_tag() ) : ?>
            <footer class="mt-8 border-t border-line pt-5 text-sm">
                <?php the_tags( '<span class="dmd-tags flex flex-wrap gap-2">', '', '</span>' ); ?>
            </footer>
            <?php endif; ?>

            <?php
				the_post_navigation(
					array(
						'class'              => 'dmd-post-nav mt-8 grid gap-4 border-t border-line pt-6 sm:grid-cols-2 text-sm',
						'prev_text'          => '<span class="block text-muted">' . esc_html__( 'Previous', 'digital-mudir-dokan' ) . '</span><span class="font-medium">%title</span>',
						'next_text'          => '<span class="block text-muted">' . esc_html__( 'Next', 'digital-mudir-dokan' ) . '</span><span class="font-medium">%title</span>',
						'screen_reader_text' => __( 'Continue reading', 'digital-mudir-dokan' ),
					)
				);

				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
        </article>

        <?php get_sidebar(); ?>
    </div>
</div>
<?php
endwhile;

get_footer();