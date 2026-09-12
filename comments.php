<?php
/**
 * Comments.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="dmd-comments mt-10 border-t border-line pt-8" aria-labelledby="dmd-comments-title">

	<?php if ( have_comments() ) : ?>
		<h2 id="dmd-comments-title" class="m-0 text-lg font-semibold">
			<?php
			$dmd_count = get_comments_number();
			printf(
				/* translators: %s: comment count. */
				esc_html( _n( '%s comment', '%s comments', $dmd_count, 'digital-mudir-dokan' ) ),
				esc_html( number_format_i18n( $dmd_count ) )
			);
			?>
		</h2>

		<ol class="dmd-comment-list mt-6 list-none space-y-6 p-0">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'avatar_size' => 44,
					'short_ping'  => true,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'class' => 'dmd-comment-nav mt-6 flex justify-between text-sm',
			)
		);
		?>

		<?php if ( ! comments_open() ) : ?>
			<p class="mt-6 text-sm text-muted"><?php esc_html_e( 'Comments are closed.', 'digital-mudir-dokan' ); ?></p>
		<?php endif; ?>
	<?php else : ?>
		<h2 id="dmd-comments-title" class="screen-reader-text"><?php esc_html_e( 'Comments', 'digital-mudir-dokan' ); ?></h2>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_form'         => 'dmd-comment-form mt-8 grid gap-4',
			'class_submit'       => 'dmd-btn dmd-btn--primary',
			'title_reply_before' => '<h3 id="reply-title" class="m-0 text-lg font-semibold">',
			'title_reply_after'  => '</h3>',
			'comment_field'      => sprintf(
				'<p class="comment-form-comment m-0"><label class="dmd-label" for="comment">%1$s</label><textarea id="comment" name="comment" class="dmd-field h-32" required></textarea></p>',
				esc_html__( 'Your comment', 'digital-mudir-dokan' )
			),
		)
	);
	?>
</section>
