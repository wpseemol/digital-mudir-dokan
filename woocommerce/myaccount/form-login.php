<?php
/**
 * Login and registration forms.
 *
 * @package Digital_Mudir_Dokan
 * @version 9.6.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' );

$dmd_register_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
?>
<div class="dmd-account-forms mx-auto grid max-w-4xl gap-6 <?php echo $dmd_register_enabled ? 'md:grid-cols-2' : 'max-w-md'; ?>">

	<section class="rounded-lg border border-line bg-white p-6" aria-labelledby="dmd-login-title">
		<h2 id="dmd-login-title" class="mb-1 mt-0 text-lg font-semibold"><?php esc_html_e( 'Login', 'digital-mudir-dokan' ); ?></h2>
		<p class="mb-5 mt-0 text-sm text-muted"><?php esc_html_e( 'Sign in to track your orders and check out faster.', 'digital-mudir-dokan' ); ?></p>

		<form class="woocommerce-form woocommerce-form-login login" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username"><?php esc_html_e( 'Username or email address', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
				<input type="text"
					class="woocommerce-Input woocommerce-Input--text input-text"
					name="username"
					id="username"
					autocomplete="username"
					required
					value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>" />
			</p>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password"><?php esc_html_e( 'Password', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text"
					type="password"
					name="password"
					id="password"
					autocomplete="current-password"
					required />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<p class="form-row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
					<span><?php esc_html_e( 'Remember me', 'digital-mudir-dokan' ); ?></span>
				</label>
			</p>

			<p class="form-row">
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="dmd-btn dmd-btn--primary dmd-btn--block woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Login', 'digital-mudir-dokan' ); ?>">
					<?php esc_html_e( 'Login', 'digital-mudir-dokan' ); ?>
				</button>
			</p>

			<p class="woocommerce-LostPassword lost_password m-0 text-sm">
				<a class="text-green" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'digital-mudir-dokan' ); ?></a>
			</p>

			<?php do_action( 'woocommerce_login_form_end' ); ?>
		</form>
	</section>

	<?php if ( $dmd_register_enabled ) : ?>
		<section class="rounded-lg border border-line bg-white p-6" aria-labelledby="dmd-register-title">
			<h2 id="dmd-register-title" class="mb-1 mt-0 text-lg font-semibold"><?php esc_html_e( 'Register', 'digital-mudir-dokan' ); ?></h2>
			<p class="mb-5 mt-0 text-sm text-muted"><?php esc_html_e( 'Create an account in under a minute.', 'digital-mudir-dokan' ); ?></p>

			<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_username"><?php esc_html_e( 'Username', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input type="text"
							class="woocommerce-Input woocommerce-Input--text input-text"
							name="username"
							id="reg_username"
							autocomplete="username"
							required
							value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>" />
					</p>
				<?php endif; ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_email"><?php esc_html_e( 'Email address', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
					<input type="email"
						class="woocommerce-Input woocommerce-Input--text input-text"
						name="email"
						id="reg_email"
						autocomplete="email"
						required
						value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>" />
				</p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_password"><?php esc_html_e( 'Password', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input type="password"
							class="woocommerce-Input woocommerce-Input--text input-text"
							name="password"
							id="reg_password"
							autocomplete="new-password"
							required />
					</p>
				<?php else : ?>
					<p class="text-sm text-muted"><?php esc_html_e( 'A link to set your password will be sent to your email address.', 'digital-mudir-dokan' ); ?></p>
				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<p class="woocommerce-form-row form-row">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="dmd-btn dmd-btn--primary dmd-btn--block woocommerce-Button woocommerce-button button" name="register" value="<?php esc_attr_e( 'Register', 'digital-mudir-dokan' ); ?>">
						<?php esc_html_e( 'Register', 'digital-mudir-dokan' ); ?>
					</button>
				</p>

				<?php do_action( 'woocommerce_register_form_end' ); ?>
			</form>
		</section>
	<?php endif; ?>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
