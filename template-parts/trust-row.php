<?php
/**
 * Three promises shown between the product grids and the testimonials.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_promises = array(
	array(
		'icon'  => 'truck',
		'title' => __( 'Home delivery across Bangladesh', 'digital-mudir-dokan' ),
		'text'  => get_theme_mod( 'dmd_delivery_inside', __( 'ঢাকার ভেতরে: ১-২ দিন', 'digital-mudir-dokan' ) ) . ' · ' . get_theme_mod( 'dmd_delivery_outside', __( 'ঢাকার বাইরে: ২-৩ দিন', 'digital-mudir-dokan' ) ),
	),
	array(
		'icon'  => 'shield',
		'title' => __( 'Pure and natural, every jar', 'digital-mudir-dokan' ),
		'text'  => __( 'Sourced directly from growers and packed without additives.', 'digital-mudir-dokan' ),
	),
	array(
		'icon'  => 'refresh',
		'title' => __( 'Easy returns', 'digital-mudir-dokan' ),
		'text'  => __( 'Tell us within seven days if something is not right.', 'digital-mudir-dokan' ),
	),
);
?>
<section class="dmd-promises border-y border-line bg-white py-8" aria-label="<?php esc_attr_e( 'Shopping with us', 'digital-mudir-dokan' ); ?>">
	<div class="dmd-container">
		<ul class="m-0 grid list-none gap-6 p-0 sm:grid-cols-3">
			<?php foreach ( $dmd_promises as $dmd_promise ) : ?>
				<li class="flex list-none items-start gap-3">
					<span class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-soft text-green">
						<?php dmd_the_icon( $dmd_promise['icon'], 20 ); ?>
					</span>
					<span>
						<strong class="block text-[15px] font-semibold"><?php echo esc_html( $dmd_promise['title'] ); ?></strong>
						<span class="text-sm text-muted"><?php echo esc_html( $dmd_promise['text'] ); ?></span>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
