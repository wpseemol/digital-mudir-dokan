<?php
/**
 * Quantity input with stepper buttons.
 *
 * @package Digital_Mudir_Dokan
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

// Older WooCommerce releases do not pass every one of these through, so the
// template fills in the same defaults core would use.
$type     = isset( $type ) ? $type : 'number';
$classes  = isset( $classes ) ? $classes : array( 'input-text', 'qty', 'text' );
$input_id = isset( $input_id ) ? $input_id : uniqid( 'quantity_' );

/* translators: %s: Quantity. */
$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'digital-mudir-dokan' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'digital-mudir-dokan' );

if ( $max_value && $min_value === $max_value ) :
	?>
	<div class="quantity hidden">
		<input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />
	</div>
	<?php
else :
	?>
	<div class="quantity" data-dmd-quantity>
		<button type="button" class="dmd-qty-btn" data-dmd-qty-down aria-label="<?php esc_attr_e( 'Decrease quantity', 'digital-mudir-dokan' ); ?>">
			<span aria-hidden="true">&minus;</span>
		</button>

		<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $label ); ?></label>

		<input
			type="<?php echo esc_attr( $type ); ?>"
			id="<?php echo esc_attr( $input_id ); ?>"
			class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
			name="<?php echo esc_attr( $input_name ); ?>"
			value="<?php echo esc_attr( $input_value ); ?>"
			aria-label="<?php esc_attr_e( 'Product quantity', 'digital-mudir-dokan' ); ?>"
			<?php if ( $min_value ) : ?>min="<?php echo esc_attr( $min_value ); ?>"<?php endif; ?>
			<?php if ( $max_value > 0 ) : ?>max="<?php echo esc_attr( $max_value ); ?>"<?php endif; ?>
			<?php if ( ! empty( $step ) ) : ?>step="<?php echo esc_attr( $step ); ?>"<?php endif; ?>
			<?php if ( ! empty( $placeholder ) ) : ?>placeholder="<?php echo esc_attr( $placeholder ); ?>"<?php endif; ?>
			<?php if ( ! empty( $inputmode ) ) : ?>inputmode="<?php echo esc_attr( $inputmode ); ?>"<?php endif; ?>
			<?php if ( ! empty( $autocomplete ) ) : ?>autocomplete="<?php echo esc_attr( $autocomplete ); ?>"<?php endif; ?>
		/>

		<button type="button" class="dmd-qty-btn" data-dmd-qty-up aria-label="<?php esc_attr_e( 'Increase quantity', 'digital-mudir-dokan' ); ?>">
			<span aria-hidden="true">+</span>
		</button>
	</div>
	<?php
endif;
