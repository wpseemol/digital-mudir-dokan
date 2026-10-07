<?php
/**
 * Service highlights shown under the category strip.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_contact  = dmd_contact();
$dmd_promises = array();

foreach ( dmd_trust_defaults() as $dmd_i => $dmd_card ) {
	$dmd_title = get_theme_mod( "dmd_trust_{$dmd_i}_title", $dmd_card['title'] );
	$dmd_text  = get_theme_mod( "dmd_trust_{$dmd_i}_text", $dmd_card['text'] );

	if ( 4 === $dmd_i && '' === trim( (string) $dmd_text ) && $dmd_contact['hotline'] ) {
		/* translators: %s: hotline number. */
		$dmd_text = sprintf( __( 'হটলাইন: %s', 'digital-mudir-dokan' ), $dmd_contact['hotline'] );
	}

	if ( '' === trim( (string) $dmd_title ) ) {
		continue;
	}

	$dmd_promises[] = array(
		'icon'  => $dmd_card['icon'],
		'title' => $dmd_title,
		'text'  => $dmd_text,
	);
}

if ( ! $dmd_promises ) {
	return;
}
?>
<section class="dmd-promises pt-2 sm:pt-4" aria-label="<?php esc_attr_e( 'Shopping with us', 'digital-mudir-dokan' ); ?>">
	<div class="dmd-container">
		<ul class="m-0 grid list-none grid-cols-2 gap-3 p-0 lg:grid-cols-4 lg:gap-5">
			<?php foreach ( $dmd_promises as $dmd_promise ) : ?>
				<li class="flex list-none items-center gap-3 rounded-xl border border-line bg-white p-3 sm:p-4">
					<span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-green-soft text-green sm:h-12 sm:w-12">
						<?php dmd_the_icon( $dmd_promise['icon'], 22 ); ?>
					</span>
					<span class="min-w-0">
						<strong class="block text-[13px] font-semibold leading-snug sm:text-[15px]"><?php echo esc_html( $dmd_promise['title'] ); ?></strong>
						<span class="block truncate text-xs text-muted sm:text-[13px]"><?php echo esc_html( $dmd_promise['text'] ); ?></span>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
