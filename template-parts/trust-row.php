<?php
/**
 * Service highlights shown under the category strip.
 *
 * @package Digital_Mudir_Dokan
 */

defined( 'ABSPATH' ) || exit;

$dmd_contact  = dmd_contact();
$dmd_promises = array(
	array(
		'icon'  => 'truck',
		'title' => __( 'সারা দেশে হোম ডেলিভারি', 'digital-mudir-dokan' ),
		'text'  => get_theme_mod( 'dmd_delivery_inside', __( 'ঢাকার ভেতরে: ১-২ দিন', 'digital-mudir-dokan' ) ),
	),
	array(
		'icon'  => 'leaf',
		'title' => __( '১০০% খাঁটি ও প্রাকৃতিক', 'digital-mudir-dokan' ),
		'text'  => __( 'সরাসরি উৎপাদকের কাছ থেকে সংগ্রহ', 'digital-mudir-dokan' ),
	),
	array(
		'icon'  => 'wallet',
		'title' => __( 'ক্যাশ অন ডেলিভারি', 'digital-mudir-dokan' ),
		'text'  => __( 'পণ্য হাতে পেয়ে মূল্য পরিশোধ', 'digital-mudir-dokan' ),
	),
	array(
		'icon'  => 'headset',
		'title' => __( 'গ্রাহক সহায়তা', 'digital-mudir-dokan' ),
		'text'  => $dmd_contact['hotline'] ? sprintf( /* translators: %s: hotline number. */ __( 'হটলাইন: %s', 'digital-mudir-dokan' ), $dmd_contact['hotline'] ) : __( 'সকাল ৯টা – রাত ১০টা', 'digital-mudir-dokan' ),
	),
);
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
