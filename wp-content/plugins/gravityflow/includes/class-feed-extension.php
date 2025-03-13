<?php
/**
 * Gravity Flow Extension Base
 *
 * @package     GravityFlow
 * @subpackage  Classes/ExtensionBase
 * @copyright   Copyright (c) 2015-2018, Steven Henty S.L.
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! class_exists( 'GFForms' ) ) {
	die();
}
GFForms::include_feed_addon_framework();

use Gravity_Flow\Gravity_Flow\Translations;

/**
 * Class Gravity_Flow_Feed_Extension
 *
 * @since 1.0
 */
abstract class Gravity_Flow_Feed_Extension extends GFFeedAddOn {

	/**
	 * The item name used by Easy Digital Downloads.
	 *
	 * @deprecated 2.2.4 Use $edd_item_id instead
	 * @var string
	 */
	public $edd_item_name = '';

	/**
	 * The item name used by Easy Digital Downloads.
	 *
	 * @since 2.2.4
	 *
	 * @var string
	 */
	public $edd_item_id = '';

	/**
	 * Holds the license key for the current installation.
	 *
	 * Set with a constant e.g. GRAVITY_FLOW_EXTENSION_LICENSE_KEY
	 *
	 * @since 2.2.5
	 *
	 * @var string
	 */
	public $license_key = '';

	/**
	 * Class constructor.
	 */		
	public function __construct() {
		parent::__construct();
		Gravity_Flow_Common::$_extensions[ $this->get_slug() ] = $this;
	}	

	/**
	 * If the extensions minimum requirements are met add the general hooks.
	 */
	public function init() {
		parent::init();

		$meets_requirements = $this->meets_minimum_requirements();
		if ( ! $meets_requirements['meets_requirements'] ) {
			return;
		}

		if ( ! $this->is_gravityforms_supported( '2.5.6' ) ) {
			$this->init_translations();
		}

		add_filter( 'gravityflow_menu_items', array( $this, 'menu_items' ) );
		add_filter( 'gravityflow_toolbar_menu_items', array( $this, 'toolbar_menu_items' ) );
	}

	/**
	 * If the extensions minimum requirements are met add the admin hooks.
	 */
	public function init_admin() {
		parent::init_admin();

		$meets_requirements = $this->meets_minimum_requirements();
		if ( ! $meets_requirements['meets_requirements'] ) {
			return;
		}

		add_filter( 'gravityflow_settings_menu_tabs', array( $this, 'app_settings_tabs' ) );
		add_filter( 'plugin_action_links', array( $this, 'plugin_settings_link' ), 10, 2 );

		// Members 2.0+ Integration.
		if ( function_exists( 'members_register_cap_group' ) ) {
			remove_filter( 'members_get_capabilities', array( $this, 'members_get_capabilities' ) );
			add_filter( 'gravityflow_members_capabilities', array( $this, 'get_members_capabilities' ) );
		}

		add_action( 'admin_notices', array( $this, 'action_admin_notices' ) );
	}

	/**
	 * Returns the feed extension short title.
	 *
	 * @since unknown
	 * @since 2.7.3 Updated to return Gravity Flow appended with the short title for Uninstall Page
	 *  
	 * @return string
	 */	
	public function get_short_title() {
		$is_gravityforms_uninstall = rgget( 'page' ) == 'gf_settings' && rgget( 'subview' ) == 'uninstall';
		return $is_gravityforms_uninstall ? 'Gravity Flow ' . $this->_short_title : $this->_short_title;
	}

	/**
	 * Add the extension capabilities to the Gravity Flow group in Members.
	 *
	 * Override to provide human readable labels.
	 *
	 * @since 1.8.1-dev
	 *
	 * @param array $caps The capabilities and their human readable labels.
	 *
	 * @return array
	 */
	public function get_members_capabilities( $caps ) {
		foreach ( $this->_capabilities as $capability ) {
			$caps[ $capability ] = $capability;
		}

		return $caps;
	}

	/**
	 * Add a tab to the app settings page for this extension.
	 *
	 * @param array $settings_tabs The app settings tabs.
	 *
	 * @return array
	 */
	public function app_settings_tabs( $settings_tabs ) {

		if ( $this->license_key ) {
			$app_settings = $this->app_settings_fields();
			$fields = ! empty( $app_settings[0]['fields'] ) ? $app_settings[0]['fields'] : array();
			if ( is_array( $fields ) && count( $fields ) == 1 ) {
				// This extension only has a license key setting but the license key is already set to we don't need the settings tab;
				return $settings_tabs;
			}
		}

		$settings_tabs[] = array(
			'name'           => $this->_slug,
			'label'          => $this->get_short_title(),
			'callback'       => array( $this, 'app_settings_tab' ),
			'icon'           => $this->get_menu_icon(),
			'icon_namespace' => $this->get_icon_namespace(),
		);

		return $settings_tabs;
	}

	/**
	 * The callback for this extensions app settings tab.
	 */
	public function app_settings_tab() {

		require_once( GFCommon::get_base_path() . '/tooltips.php' );

		$icon = $this->app_settings_icon();
		if ( empty( $icon ) ) {
			$icon = '<i class="fa fa-cogs"></i>';
		}
		?>

		<h3><span><?php echo $icon ?> <?php echo $this->app_settings_title() ?></span></h3>

		<?php

		if ( $this->maybe_uninstall() ) {

			printf(
				'<div class="alert success">%s</div>',
				sprintf(
					esc_html__( '%s has been successfully uninstalled. It can be re-activated from the %splugins page%s.', 'gravityforms' ),
					$this->_title,
					'<a href="plugins.php">',
					'</a>'
				)
			);

		} else {

			// Get fields.
			$sections = $this->app_settings_fields();
			if ( ! empty( $sections ) ) {
				$sections = $this->prepare_settings_sections( $sections, 'app_settings' );

				// Initialize new settings renderer.
				$renderer = new Gravity_Forms\Gravity_Forms\Settings\Settings(
					array(
						'capability'     => $this->_capabilities_app_settings,
						'fields'         => $sections,
						'header'         => array(
							'icon'  => $this->app_settings_icon(),
							'title' => $this->app_settings_title(),
						),
						'initial_values' => $this->get_app_settings(),
						'save_callback'  => array( $this, 'update_app_settings' ),
					)
				);

				// Save renderer to instance.
				$this->set_settings_renderer( $renderer );

				$this->get_settings_renderer()->render();
			}

			$this->render_uninstall();

		}
	}

	/**
	 * Override this function to customize the markup for the uninstall section on the plugin settings page
	 */
	public function render_uninstall() {
		if ( GFForms::get_page() === 'settings' ) {
			parent::render_uninstall();
			return;
		}

		if ( rgget( 'page' ) === 'gravityflow_settings' && rgget( 'view' ) === 'uninstall' ) {
			$icon        = array(
				'icon'           => $this->get_menu_icon(),
				'icon_namespace' => $this->get_icon_namespace(),
			);
			$icon_markup = Gravity_Flow_Common::get_icon_markup( $icon, 'dashicons-admin-generic' );
			?>
			<form action="" method="post" class="gform-settings-panel gform-settings-panel__addon-uninstall">
				<?php wp_nonce_field( 'gflow_extension_uninstall', 'gflow_extension_uninstall' ); ?>
				<div class="gform-settings-panel__content">
					<div class="addon-logo dashicons"><?php echo $icon_markup; ?></div>
					<div class="addon-uninstall-text">
						<h4 class="gform-settings-panel__title"><?php printf( esc_html__( '%s', 'gravityflow' ), $this->get_short_title() ) ?></h4>
						<?php
						if ( version_compare( GFForms::$version, '2.5.10', '<' ) )  {
							?>
							<div><?php printf( esc_html__( 'This operation deletes ALL %s settings.', 'gravityflow' ), $this->get_short_title() ) ?></div>
							<?php
						} else {
							?>
							<div><?php echo esc_html( $this->uninstall_message() ); ?></div>
							<?php
						}
						?>
					</div>
					<div class="addon-uninstall-button">
						<input id="addon" name="addon" type="hidden" value="<?php echo $this->get_slug(); ?>">
						<button type="submit" aria-label="<?php printf( esc_html__( 'Uninstall %s', 'gravityflow'), $this->get_short_title() ); ?>" name="uninstall_addon" value="uninstall" class="button uninstall-addon red" onclick="return confirm('<?php echo esc_js( $this->uninstall_confirm_message() ); ?>');" onkeypress="return confirm('<?php echo esc_js( $this->uninstall_confirm_message() ); ?>');">
							<i class="dashicons dashicons-trash"></i>
							<?php esc_attr_e( 'Uninstall', 'gravityflow' ); ?>
						</button>
					</div>
				</div>
			</form>
			<?php
			return;
		}
	}

	/**
	 * Return the plugin's icon for the form/settings/uninstall page.
	 *
	 * @since 2.7.5
	 *
	 * @return string
	 */	
	public function get_menu_icon() {
		return 'dashicons-gravityflow-icon';
	}

	/**
	 * Return the plugin's icon namespace.
	 *
	 * @since 2.8
	 *
	 * @return string
	 */
	public function get_icon_namespace() {
		return 'gflow';
	}

	/**
	 * Render the uninstall button on Gravity Forms uninstall page to correctly point for Gravity Flow Feed Extensions
	 *
	 * @since 2.7.3
	 */
	public function render_settings_button() {

		if ( ! $this->current_user_can_uninstall() ) {
			return;
		}

		if ( rgget( 'page' ) === 'gf_settings' ) {
			GFAddOn::render_uninstall();
			return;
		}

		$icon        = array(
			'icon'           => $this->get_menu_icon(),
			'icon_namespace' => $this->get_icon_namespace(),
		);
		$icon_markup = Gravity_Flow_Common::get_icon_markup( $icon, 'dashicons-admin-generic' );
		$url         = add_query_arg( array( 'view' => $this->get_slug() ), admin_url( 'admin.php?page=gravityflow_settings' ) );
		?>
		<form action="" method="post" class="gform-settings-panel gform-settings-panel__addon-uninstall">
			<?php wp_nonce_field( 'uninstall', 'gflow_extension_uninstall' ); ?>
			<div class="gform-settings-panel__content">
				<div class="addon-logo dashicons"><?php echo $icon_markup; ?></div>
				<div class="addon-uninstall-text">
					<h4 class="gform-settings-panel__title"><?php printf( esc_html__( '%s', 'gravityforms' ), $this->get_short_title() ) ?></h4>
					<div><?php esc_attr_e( 'To continue uninstalling this add-on click the settings button.', 'gravityforms' ) ?></div>
				</div>
				<div class="addon-uninstall-button">
					<a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo 'Visit ' . $this->get_short_title() . ' Settings page'; ?>" class="button addon-settings">
						<i class="dashicons dashicons-admin-generic"></i>
						<?php esc_attr_e( 'Settings', 'gravityforms' ); ?>
					</a>
				</div>
			</div>
		</form>
		<?php
	}

	/**
	 * Get the settings for the app settings tab.
	 *
	 * @return array
	 */
	public function app_settings_fields() {
		if ( $this->license_key ) {
			return array();
		}

		return array(
			array(
				'title'  => $this->get_short_title(),
				'fields' => array(
					array(
						'name'                => 'license_key',
						'label'               => esc_html__( 'License Key', 'gravityflow' ),
						'type'                => 'text',
						'validation_callback' => array( $this, 'license_validation' ),
						'feedback_callback'   => array( $this, 'license_feedback' ),
						'error_message'       => __( 'Invalid license', 'gravityflow' ),
						'class'               => 'large',
						'default_value'       => '',
					),
				),
			),
		);
	}

	/**
	 * Return the saved settings.
	 *
	 * @return mixed
	 */
	public function get_app_settings() {
		return parent::get_app_settings();
	}

	/**
	 * Validate the license key setting.
	 *
	 * @param string $value The field value; the license key.
	 * @param array  $field The field properties.
	 *
	 * @return bool|null
	 */
	public function license_feedback( $value, $field ) {

		if ( empty( $value ) ) {
			return null;
		}

		$license_data = $this->check_license( $value );

		$valid = null;
		if ( empty( $license_data ) || $license_data->license == 'invalid' ) {
			$valid = false;
		} elseif ( $license_data->license == 'valid' ) {
			$valid = true;
		}

		return $valid;

	}

	/**
	 * Retrieve the license data.
	 *
	 * @param string $value The license key for this extension.
	 *
	 * @return array|mixed|object
	 */
	public function check_license( $value = '' ) {
		if ( empty( $value ) ) {
			$value = $this->license_key ? $this->license_key : $this->get_app_setting( 'license_key' );
		}

		if ( empty( $value ) ) {
			return false;
		}

		$item_name_or_id = empty( $this->edd_item_id ) ? $this->edd_item_name : $this->edd_item_id;
		$response        = gravity_flow()->perform_edd_license_request( 'check_license', $value, $item_name_or_id );

		return json_decode( wp_remote_retrieve_body( $response ) );
	}

	/**
	 * Deactivate the old license key and active the new license key.
	 *
	 * @param array  $field         The field properties.
	 * @param string $field_setting The field value; the license key.
	 */
	public function license_validation( $field, $field_setting ) {
		$old_license = $this->get_app_setting( 'license_key' );

		if ( $old_license && $field_setting != $old_license ) {
			$item_name_or_id = empty( $this->edd_item_id ) ? $this->edd_item_name : $this->edd_item_id;
			$response        = gravity_flow()->perform_edd_license_request( 'deactivate_license', $old_license, $item_name_or_id );
			$this->log_debug( __METHOD__ . '(): response: ' . print_r( $response, 1 ) );
		}

		if ( empty( $field_setting ) ) {
			return;
		}

		$this->activate_license( $field_setting );
	}

	/**
	 * Activate the license key.
	 *
	 * @param string $license_key The license key for this extension.
	 *
	 * @return array|mixed|object
	 */
	public function activate_license( $license_key ) {
		$item_name_or_id = empty( $this->edd_item_id ) ? $this->edd_item_name : $this->edd_item_id;
		$response        = gravity_flow()->perform_edd_license_request( 'activate_license', $license_key, $item_name_or_id );

		// Force plugins page to refresh the update info.
		set_site_transient( 'update_plugins', null );
		$cache_key = md5( 'edd_plugin_' . sanitize_key( $this->_path ) . '_version_info' );
		delete_transient( $cache_key );

		return json_decode( wp_remote_retrieve_body( $response ) );
	}

	/**
	 * Override to add menu items to the Gravity Flow app menu.
	 *
	 * @param array $menu_items The app menu items.
	 *
	 * @return array
	 */
	public function menu_items( $menu_items ) {
		return $menu_items;
	}

	/**
	 * Override to add menu items to the Gravity Flow toolbar.
	 *
	 * @param array $menu_items The toolbar menu items.
	 *
	 * @return array
	 */
	public function toolbar_menu_items( $menu_items ) {
		return $menu_items;
	}

	/**
	 * Add the failed requirements error message.
	 *
	 * @since 1.7.1-dev
	 */
	public function failed_requirements_init() {
		$failed_requirements = $this->meets_minimum_requirements();

		// Prepare errors list.
		$errors = '';
		foreach ( $failed_requirements['errors'] as $error ) {
			$errors .= sprintf( '<li>%s</li>', esc_html( $error ) );
		}

		// Prepare error message.
		$error_message = sprintf(
			'%s<br />%s<ol>%s</ol>',
			sprintf( esc_html__( '%s is not able to run because your WordPress environment has not met the minimum requirements.', 'gravityflow' ), $this->_title ),
			sprintf( esc_html__( 'Please resolve the following issues to use %s:', 'gravityflow' ), $this->get_short_title() ),
			$errors
		);

		// Add error message.
		GFCommon::add_error_message( $error_message );
	}

	/**
	 * Determine if the add-ons minimum requirements have been met with Gravity Forms 2.2+.
	 *
	 * @since 1.8.1-dev
	 *
	 * @return array
	 */
	public function meets_minimum_requirements() {
		if ( $this->is_gravityforms_supported( '2.2' ) ) {
			return parent::meets_minimum_requirements();
		}

		return array( 'meets_requirements' => true, 'errors' => array() );
	}

	/**
	 * Add the settings link for the extension to the installed plugins page.
	 *
	 * @param array  $links An array of plugin action links.
	 * @param string $file  Path to the plugin file relative to the plugins directory.
	 *
	 * @since 1.7.1-dev
	 *
	 * @return array
	 */
	public function plugin_settings_link( $links, $file ) {
		if ( $file != $this->_path ) {
			return $links;
		}

		array_unshift( $links, '<a href="' . admin_url( 'admin.php' ) . '?page=gravityflow_settings&view=' . $this->_slug . '">' . esc_html__( 'Settings', 'gravityflow' ) . '</a>' );

		return $links;
	}

	/**
	 * Adds the invalid license admin notice.
	 *
	 * @since 2.2.4
	 */
	public function action_admin_notices() {

		if ( ! ( $this->edd_item_name || $this->edd_item_id ) ) {
			// Only display the admin notice for official extensions.
			return;
		}

		if ( is_multisite() && ! is_main_site() ) {
			return;
		}

		$pending_installation = ! is_multisite() && ( get_option( 'gravityflow_pending_installation' ) || isset( $_GET['gravityflow_installation_wizard'] ) );

		if ( $pending_installation ) {
			return;
		}

		$is_saving_license_key = isset( $_POST['_gaddon_setting_license_key'] ) && isset( $_POST[ '_' . $this->get_slug() . '_save_settings_nonce' ] );

		$transient_key = $this->get_slug() . '_license_details';

		$license_details = false;
		if ( $is_saving_license_key ) {
			$posted_license_key = sanitize_text_field( rgpost( '_gaddon_setting_license_key' ) );
			if ( wp_verify_nonce( $_POST[ '_' . $this->get_slug() . '_save_settings_nonce' ], $this->get_slug() . '_save_settings' ) ) {
				$license_details = $posted_license_key ? $this->activate_license( $posted_license_key ) : false;
			}
			if ( $license_details ) {
				$expiration = DAY_IN_SECONDS + rand( 0, DAY_IN_SECONDS );
				set_transient( $transient_key, $license_details, $expiration );
			}
		} else {
			$license_details = get_transient( $transient_key );
			if ( ! $license_details ) {
				$last_check = get_option( 'gravityflow_last_license_check' );
				if ( $last_check > time() - 5 * MINUTE_IN_SECONDS ) {
					return;
				}
				$license_details = $this->check_license();
				if ( $license_details ) {
					if ( $this->license_key && in_array( $license_details->license, array( 'site_inactive', 'inactive' ) ) ) {
						$license_details = $this->activate_license( $this->license_key );
					}
					$expiration = DAY_IN_SECONDS + rand( 0, DAY_IN_SECONDS );
					set_transient( $transient_key, $license_details, $expiration );
					update_option( 'gravityflow_last_license_check', time() );
				}
			}
		}

		$license_status = $license_details ? $license_details->license : '';

		if ( $license_status != 'valid' ) {

			$add_buttons = ! is_multisite() || ( is_multisite() && is_main_site() );

			$primary_button_link = admin_url( 'admin.php?page=gravityflow_settings&view=' . $this->get_slug() );

			$message = '';

			switch ( $license_status ) {
				case 'expired':
					/* translators: %s is the title of the Gravity Flow Extension */
					$message .= sprintf( esc_html__( 'Your license for %s has expired.', 'gravityflow' ), $this->_title );

					$add_buttons = false;
					break;
				case 'invalid':
					/* translators: %s is the title of the Gravity Flow Extension */
					$message .= sprintf( esc_html__( 'Your %s license is invalid.', 'gravityflow' ), $this->_title );
					break;
				case 'deactivated':
					/* translators: %s is the title of the Gravity Flow Extension */
					$message .= sprintf( esc_html__( 'Your %s license is inactive.', 'gravityflow' ), $this->_title );
					break;
				/** @noinspection PhpMissingBreakStatementInspection */
				case '':
					$license_status = 'site_inactive';
				// break intentionally left blank
				case 'inactive':
				case 'site_inactive':
				default:
					/* translators: %s is the title of the Gravity Flow Extension */
					$message .= sprintf( esc_html__( 'Your %s license has not been activated.', 'gravityflow' ), $this->_title );
					break;
			}

			$message .= ' ' . esc_html__( "This means you're missing out on security fixes, updates and support.", 'gravityflow' );

			if ( ! empty( $this->edd_item_id ) ) {
				$url = 'https://gravityflow.io/?p=' . $this->edd_item_id . '&utm_source=admin_notice&utm_medium=admin&utm_content=' . $license_status . '&utm_campaign=Admin%20Notice';
			} else {
				$url = 'https://gravityflow.io/extensions/?utm_source=admin_notice&utm_medium=admin&utm_content=' . $license_status . '&utm_campaign=Admin%20Notice';
			}

			// Show a different notice on settings page for inactive licenses (hide the buttons)
			if ( ! $this->license_key && $add_buttons && ! $this->is_extension_settings() ) {
				$message .= '<br /><br />' . esc_html__( '%sActivate your license%s or %sget a license here%s', 'gravityflow' );
				$message = sprintf( $message, '<a href="' . esc_url( $primary_button_link ) . '" class="button button-primary">', '</a>', '<a href="' . esc_url( $url ) . '" class="button button-secondary">', '</a>' );
			}

			$key = $this->get_slug() . '_license_notice_' . date( 'Y' ) . date( 'z' );

			$notice = array(
				'key'          => $key,
				'capabilities' => $this->_capabilities_app_settings,
				'type'         => 'error',
				'text'         => $message,
			);

			$notices = array( $notice );

			GFCommon::display_dismissible_message( $notices );
		}
	}
	/**
	 * Returns TRUE if the current page is the extension settings main page.
	 *
	 * @since 2.2.4
	 *
	 * @return bool
	 */
	public function is_extension_settings() {

		$is_extension_settings = rgget( 'page' ) == 'gravityflow_settings' && rgget( 'view' ) == $this->get_slug();

		return $is_extension_settings;
	}

	/**
	 * Inits the TranslationsPress integration.
	 *
	 * @since 2.7.5
	 */
	public function init_translations() {
		Translations\Manager::get_instance( $this->get_slug() );
	}

	/**
	 * Uses TranslationsPress to install translations for the specified locale.
	 *
	 * @since 2.7.5
	 *
	 * @param string $locale The locale the translations are to be installed for.
	 */
	public function install_translations( $locale = '' ) {
		Translations\Manager::get_instance( $this->get_slug() )->install( $locale );
	}

	/**
	 * Returns an array of locales from the mo files found in the WP_LANG_DIR/plugins directory.
	 *
	 * Used to display the installed locales on the system report.
	 *
	 * @since 2.7.5
	 *
	 * @return array
	 */
	public function get_installed_locales() {
		return Translations\Manager::get_instance( $this->get_slug() )->get_installed_translations();
	}

	/**
	 * Installs or upgrades the plugin.
	 *
	 * @since 2.7.5
	 */
	public function setup() {
		Translations\Manager::get_instance( $this->get_slug() )->legacy_install_on_setup( $this );
		parent::setup();
	}

}
