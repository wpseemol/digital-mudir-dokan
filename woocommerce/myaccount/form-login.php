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
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 max-w-7xl">
	<div id="customer_login" class="mx-auto w-full max-w-md space-y-8">

		<section class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm" aria-labelledby="dmd-login-title">
			<h2 id="dmd-login-title" class="mb-2 mt-0 text-2xl font-bold text-ink"><?php esc_html_e( 'Login', 'digital-mudir-dokan' ); ?></h2>
			<p class="mb-6 mt-0 text-sm text-gray-600"><?php esc_html_e( 'Sign in to track your orders and check out faster.', 'digital-mudir-dokan' ); ?></p>

			<form class="woocommerce-form woocommerce-form-login login" method="post">

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
					<label for="username" class="mb-1.5 block text-sm font-semibold text-gray-700"><?php esc_html_e( 'Username or email address', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
					<input type="text"
						class="w-full rounded-lg border border-gray-300 px-4 py-3 text-base text-ink outline-none transition focus:border-green focus:ring-2 focus:ring-green"
						name="username"
						id="username"
						autocomplete="username"
						required
						value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
				</p>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
					<label for="password" class="mb-1.5 block text-sm font-semibold text-gray-700"><?php esc_html_e( 'Password', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
					<input class="w-full rounded-lg border border-gray-300 px-4 py-3 text-base text-ink outline-none transition focus:border-green focus:ring-2 focus:ring-green"
						type="password"
						name="password"
						id="password"
						autocomplete="current-password"
						required />
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<p class="form-row mb-4 flex items-center justify-between">
					<label class="flex items-center gap-2 text-sm text-gray-600">
						<input class="h-4 w-4 rounded border-gray-300 text-green focus:ring-green" name="rememberme" type="checkbox" id="rememberme" value="forever" />
						<span><?php esc_html_e( 'Remember me', 'digital-mudir-dokan' ); ?></span>
					</label>
					<a class="text-sm font-medium text-green hover:text-green-dark transition" href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'digital-mudir-dokan' ); ?></a>
				</p>

				<p class="form-row mb-4">
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<button type="submit" class="mt-4 flex w-full items-center justify-center rounded-lg bg-green px-6 py-3.5 font-semibold text-white shadow-sm transition hover:bg-green-dark" name="login" value="<?php esc_attr_e( 'Login', 'digital-mudir-dokan' ); ?>">
						<?php esc_html_e( 'Login', 'digital-mudir-dokan' ); ?>
					</button>
				</p>

				<?php do_action( 'woocommerce_login_form_end' ); ?>
			</form>
		</section>

		<?php if ( $dmd_register_enabled ) : ?>
			<section id="register" class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm" aria-labelledby="dmd-register-title">
				<h2 id="dmd-register-title" class="mb-2 mt-0 text-2xl font-bold text-ink"><?php esc_html_e( 'Register', 'digital-mudir-dokan' ); ?></h2>
				<p class="mb-6 mt-0 text-sm text-gray-600"><?php esc_html_e( 'Create an account in under a minute.', 'digital-mudir-dokan' ); ?></p>

				<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
							<label for="reg_username" class="mb-1.5 block text-sm font-semibold text-gray-700"><?php esc_html_e( 'Username', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
							<input type="text"
								class="w-full rounded-lg border border-gray-300 px-4 py-3 text-base text-ink outline-none transition focus:border-green focus:ring-2 focus:ring-green"
								name="username"
								id="reg_username"
								autocomplete="username"
								required
								value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
						</p>
					<?php endif; ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
						<label for="reg_email" class="mb-1.5 block text-sm font-semibold text-gray-700"><?php esc_html_e( 'Email address', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
						<input type="email"
							class="w-full rounded-lg border border-gray-300 px-4 py-3 text-base text-ink outline-none transition focus:border-green focus:ring-2 focus:ring-green"
							name="email"
							id="reg_email"
							autocomplete="email"
							required
							value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" />
					</p>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
							<label for="reg_password" class="mb-1.5 block text-sm font-semibold text-gray-700"><?php esc_html_e( 'Password', 'digital-mudir-dokan' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
							<input type="password"
								class="w-full rounded-lg border border-gray-300 px-4 py-3 text-base text-ink outline-none transition focus:border-green focus:ring-2 focus:ring-green"
								name="password"
								id="reg_password"
								autocomplete="new-password"
								required />
						</p>
					<?php else : ?>
						<p class="mb-4 text-sm text-gray-600"><?php esc_html_e( 'A link to set your password will be sent to your email address.', 'digital-mudir-dokan' ); ?></p>
					<?php endif; ?>

					<?php do_action( 'woocommerce_register_form' ); ?>

					<p class="woocommerce-form-row form-row mb-4">
						<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
						<button type="submit" class="mt-4 flex w-full items-center justify-center rounded-lg bg-green-dark px-6 py-3.5 font-semibold text-white shadow-sm transition hover:bg-green-dark" name="register" value="<?php esc_attr_e( 'Register', 'digital-mudir-dokan' ); ?>">
							<?php esc_html_e( 'Register', 'digital-mudir-dokan' ); ?>
						</button>
					</p>

					<?php do_action( 'woocommerce_register_form_end' ); ?>
				</form>
			</section>
		<?php endif; ?>
	</div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
