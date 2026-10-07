<?php
/**
 * Login and registration forms, presented as a tabbed card.
 *
 * The active tab is decided server-side so a failed submission comes back on the
 * form that produced the error, and `assets/js/theme.js` only handles switching.
 *
 * @package Digital_Mudir_Dokan
 * @version 9.9.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * Both forms read $_POST to refill themselves after a failed submission. The
 * login and register nonces are verified by WooCommerce's form handlers.
 */
// phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended

do_action( 'woocommerce_before_customer_login_form' );

$dmd_register_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );

// Only used to decide which tab a failed submission comes back on.
$dmd_active_tab = ( isset( $_POST['register'] ) || ( isset( $_GET['action'] ) && 'register' === $_GET['action'] ) ) ? 'register' : 'login';

if ( ! $dmd_register_enabled ) {
	$dmd_active_tab = 'login';
}

$dmd_is_register = 'register' === $dmd_active_tab;
$dmd_label_class = 'mb-1.5 block text-sm font-semibold text-gray-700';
$dmd_submit_class = 'w-full py-3 rounded-lg bg-green hover:bg-green-dark text-white font-semibold transition text-center shadow-sm mt-3';
?>
<div class="mx-auto my-12 w-full max-w-md rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
	<div id="customer_login" class="dmd-account-tabs">

		<?php if ( $dmd_register_enabled ) : ?>
			<div class="dmd-tablist mb-6 grid grid-cols-2 gap-1 rounded-xl bg-gray-100 p-1"
				role="tablist"
				aria-label="<?php esc_attr_e( 'Login or register', 'digital-mudir-dokan' ); ?>">

				<button type="button"
					id="dmd-tab-login"
					class="dmd-tab<?php echo $dmd_is_register ? '' : ' is-active'; ?>"
					role="tab"
					aria-selected="<?php echo $dmd_is_register ? 'false' : 'true'; ?>"
					aria-controls="dmd-panel-login"
					data-dmd-tab="login">
					<?php esc_html_e( 'লগইন (Login)', 'digital-mudir-dokan' ); ?>
				</button>

				<button type="button"
					id="dmd-tab-register"
					class="dmd-tab<?php echo $dmd_is_register ? ' is-active' : ''; ?>"
					role="tab"
					aria-selected="<?php echo $dmd_is_register ? 'true' : 'false'; ?>"
					aria-controls="dmd-panel-register"
					data-dmd-tab="register">
					<?php esc_html_e( 'নতুন একাউন্ট খুলুন (Register)', 'digital-mudir-dokan' ); ?>
				</button>
			</div>
		<?php endif; ?>

		<!-- Login -->
		<div id="dmd-panel-login"
			class="dmd-tab-panel<?php echo $dmd_is_register ? ' hidden' : ''; ?>"
			role="tabpanel"
			aria-labelledby="dmd-tab-login">

			<form class="woocommerce-form woocommerce-form-login login" method="post">

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
					<label for="username" class="<?php echo esc_attr( $dmd_label_class ); ?>">
						<?php esc_html_e( 'Username or email address', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span>
					</label>
					<input type="text"
						class="<?php echo esc_attr( dmd_auth_input_class() ); ?>"
						name="username"
						id="username"
						autocomplete="username"
						required
						value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
				</p>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
					<label for="password" class="<?php echo esc_attr( $dmd_label_class ); ?>">
						<?php esc_html_e( 'Password', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span>
					</label>
					<input type="password"
						class="<?php echo esc_attr( dmd_auth_password_class() ); ?>"
						name="password"
						id="password"
						autocomplete="current-password"
						required />
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<p class="form-row mb-4 flex items-center justify-between">
					<label class="flex items-center gap-2 text-sm text-gray-600">
						<input class="h-4 w-4 rounded border-gray-300 text-green focus:ring-green/30" name="rememberme" type="checkbox" id="rememberme" value="forever" />
						<span><?php esc_html_e( 'Remember me', 'digital-mudir-dokan' ); ?></span>
					</label>
					<a class="text-sm font-medium text-green underline underline-offset-2 transition hover:text-green-dark" href="<?php echo esc_url( wp_lostpassword_url() ); ?>">
						<?php esc_html_e( 'Lost your password?', 'digital-mudir-dokan' ); ?>
					</a>
				</p>

				<p class="form-row mb-0">
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<button type="submit" class="<?php echo esc_attr( $dmd_submit_class ); ?>" name="login" value="<?php esc_attr_e( 'Login', 'digital-mudir-dokan' ); ?>">
						<?php esc_html_e( 'Login', 'digital-mudir-dokan' ); ?>
					</button>
				</p>

				<?php do_action( 'woocommerce_login_form_end' ); ?>
			</form>

			<?php if ( $dmd_register_enabled ) : ?>
				<p class="mb-0 mt-5 text-center text-sm text-gray-600">
					<?php esc_html_e( 'একাউন্ট নেই?', 'digital-mudir-dokan' ); ?>
					<button type="button" class="font-semibold text-green underline underline-offset-2 transition hover:text-green-dark" data-dmd-tab="register">
						<?php esc_html_e( 'রেজিস্ট্রেশন করুন', 'digital-mudir-dokan' ); ?>
					</button>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( $dmd_register_enabled ) : ?>
			<!-- Register -->
			<div id="dmd-panel-register"
				class="dmd-tab-panel<?php echo $dmd_is_register ? '' : ' hidden'; ?>"
				role="tabpanel"
				aria-labelledby="dmd-tab-register">

				<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
							<label for="reg_username" class="<?php echo esc_attr( $dmd_label_class ); ?>">
								<?php esc_html_e( 'Username', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span>
							</label>
							<input type="text"
								class="<?php echo esc_attr( dmd_auth_input_class() ); ?>"
								name="username"
								id="reg_username"
								autocomplete="username"
								required
								value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
						</p>
					<?php endif; ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
						<label for="reg_email" class="<?php echo esc_attr( $dmd_label_class ); ?>">
							<?php esc_html_e( 'Email address', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span>
						</label>
						<input type="email"
							class="<?php echo esc_attr( dmd_auth_input_class() ); ?>"
							name="email"
							id="reg_email"
							autocomplete="email"
							required
							value="<?php echo ( ! empty( $_POST['email'] ) && is_string( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" />
					</p>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
							<label for="reg_password" class="<?php echo esc_attr( $dmd_label_class ); ?>">
								<?php esc_html_e( 'Password', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span>
							</label>
							<input type="password"
								class="<?php echo esc_attr( dmd_auth_password_class() ); ?>"
								name="password"
								id="reg_password"
								autocomplete="new-password"
								required />
						</p>
					<?php else : ?>
						<p class="mb-4 text-sm text-gray-600"><?php esc_html_e( 'A link to set your password will be sent to your email address.', 'digital-mudir-dokan' ); ?></p>
					<?php endif; ?>

					<?php do_action( 'woocommerce_register_form' ); ?>

					<p class="woocommerce-form-row form-row mb-0">
						<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
						<button type="submit" class="<?php echo esc_attr( $dmd_submit_class ); ?>" name="register" value="<?php esc_attr_e( 'Register', 'digital-mudir-dokan' ); ?>">
							<?php esc_html_e( 'Register', 'digital-mudir-dokan' ); ?>
						</button>
					</p>

					<?php do_action( 'woocommerce_register_form_end' ); ?>
				</form>

				<p class="mb-0 mt-5 text-center text-sm text-gray-600">
					<?php esc_html_e( 'একাউন্ট আছে?', 'digital-mudir-dokan' ); ?>
					<button type="button" class="font-semibold text-green underline underline-offset-2 transition hover:text-green-dark" data-dmd-tab="login">
						<?php esc_html_e( 'লগইন করুন', 'digital-mudir-dokan' ); ?>
					</button>
				</p>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
do_action( 'woocommerce_after_customer_login_form' );
// phpcs:enable
