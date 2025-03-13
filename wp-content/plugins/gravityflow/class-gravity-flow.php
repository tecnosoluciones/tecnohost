<?php
/**
 * Gravity Flow
 *
 * @package     GravityFlow
 * @subpackage  Classes/Gravity_Flow
 * @copyright   Copyright (c) 2015-2018, Steven Henty S.L.
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

use Gravity_Flow\Gravity_Flow\Inbox\Models\Task;
use Gravity_Flow\Gravity_Flow\Inbox\Inbox_Service_Provider;
use Gravity_Flow\Gravity_Flow\Config\Services;

use Gravity_Flow\Gravity_Flow\Translations;
use Gravity_Flow\Gravity_Flow\Locking;

// Make sure Gravity Forms is active and already loaded.
if ( class_exists( 'GFForms' ) ) {

	// The Add-On Framework is not loaded by default.
	// Use the following function to load the appropriate files.
	GFForms::include_feed_addon_framework();

	/**
	 * Class Gravity_Flow
	 */
	class Gravity_Flow extends GFFeedAddOn {

		const VENDOR_JS_THEME = 'gravityflow_vendor_js_theme';
		const VENDOR_JS_ADMIN = 'gravityflow_vendor_js_admin';

		const THEME_JS  = 'gravityflow_theme_js';
		const THEME_CSS = 'gravityflow_theme_css';

		const ADMIN_COMPONENTS_CSS = 'gravityflow_admin_components_css';

		const ADMIN_JS  = 'gravityflow_admin_js';
		const ADMIN_CSS = 'gravityflow_admin_css';

		/**
		 * The instance of this class.
		 *
		 * @var null|Gravity_Flow
		 */
		private static $_instance = null;

		/**
		 * Tokenize the shortcode check for multiple calls.
		 *
		 * @var null
		 */
		public static $has_shortcode = null;

		/**
		 * Tokenize the steps check for multiple calls.
		 *
		 * @var array
		 */
		public static $found_feeds = array();

		/**
		 * Our Service Container object.
		 *
		 * @var \Gravity_Forms\Gravity_Forms\GF_Service_Container $container
		 */
		private $container;

		/**
		 * Defines the add-on version.
		 *
		 * @var string
		 */
		public $_version = GRAVITY_FLOW_VERSION;

		/**
		 * The minimum Gravity Forms version required.
		 *
		 * The Framework will display an appropriate message on the plugins page if necessary
		 *
		 * @var string
		 */
		protected $_min_gravityforms_version = '2.3';

		/**
		 * The add-on slug.
		 *
		 * @var string
		 */
		protected $_slug = 'gravityflow';

		/**
		 * The path to the main plugin file, relative to the WordPress plugins folder.
		 *
		 * @var string
		 */
		protected $_path = 'gravityflow/gravityflow.php';

		/**
		 * The full path to this file.
		 *
		 * @var string
		 */
		protected $_full_path = __FILE__;

		/**
		 * Title of the plugin to be used on the settings page, form settings and plugins page.
		 *
		 * @var string
		 */
		protected $_title = 'Gravity Flow';

		/**
		 * Short version of the plugin title to be used on menus and other places where a less verbose string is useful.
		 *
		 * @var string
		 */
		protected $_short_title = 'Workflow';

		/**
		 * The capabilities to be listed by the Members plugin.
		 *
		 * @var array
		 */
		protected $_capabilities = array(
			'gravityflow_uninstall',
			'gravityflow_settings',
			'gravityflow_create_steps',
			'gravityflow_submit',
			'gravityflow_inbox',
			'gravityflow_status',
			'gravityflow_status_view_all',
			'gravityflow_reports',
			'gravityflow_activity',
			'gravityflow_workflow_detail_admin_actions',
			'gravityflow_admin_actions',
		);

		/**
		 * The capability required to access the app settings.
		 *
		 * @var string
		 */
		protected $_capabilities_app_settings = 'gravityflow_settings';

		/**
		 * The capability required to create steps.
		 *
		 * @var string
		 */
		protected $_capabilities_form_settings = 'gravityflow_create_steps';

		/**
		 * The app menu capabilities.
		 *
		 * @var array
		 */
		protected $_capabilities_app_menu = array(
			'gravityflow_uninstall',
			'gravityflow_settings',
			'gravityflow_create_steps',
			'gravityflow_submit',
			'gravityflow_inbox',
			'gravityflow_status',
			'gravityflow_activity',
			'gravityflow_reports',
		);

		/**
		 * The capability required to uninstall the plugin.
		 *
		 * @var string
		 */
		protected $_capabilities_uninstall = 'gravityflow_uninstall';

		/**
		 * Returns an instance of this class, and stores it in the $_instance property.
		 *
		 * @return null|Gravity_Flow
		 */
		public static function get_instance() {
			if ( self::$_instance == null ) {
				$container           = self::gflow_initialize_services();
				$instance            = new Gravity_Flow();
				$instance->container = $container;

				self::$_instance = $instance;
			}

			return self::$_instance;
		}

		public function container() {
			return $this->container;
		}

		/**
		 * Set up Services and Containers
		 *
		 * @since 2.7.1
		 *
		 * @return Container
		 */
		public static function gflow_initialize_services() {
			if ( ! class_exists( '\Gravity_Forms\Gravity_Forms\GF_Service_Container' ) ) {
				require_once( dirname( __FILE__ ) . '/includes/lib/class-gf-service-container.php' );
				require_once( dirname( __FILE__ ) . '/includes/lib/class-gf-service-provider.php' );
				$container = new \Gravity_Forms\Gravity_Forms\GF_Service_Container();
			} else {
				$container = \GFForms::get_service_container();
			}

			$services  = new Services();
			foreach ( $services->get() as $class ) {
				$obj = new $class();
				$container->add_provider( $obj );
			}

			return $container;
		}

		/**
		 * The assignee status feedback.
		 *
		 * @var null|string
		 */
		private $_custom_page_content = null;

		/**
		 * Disallow cloning of the class.
		 */
		private function __clone() {
		}

		/**
		 * Adds hooks which need to be included before the init hook is triggered.
		 */
		public function pre_init() {
			require_once( dirname( __FILE__ ) . '/includes/pages/class-inbox.php' );
			require_once( dirname( __FILE__ ) . '/includes/pages/class-reports.php' );

			add_filter( 'gform_export_form', array( $this, 'filter_gform_export_form' ) );
			add_action( 'gform_forms_post_import', array( $this, 'action_gform_forms_post_import' ) );
			parent::pre_init();
			add_action( 'gform_post_add_entry', array( $this, 'action_gform_post_add_entry' ), 10, 2 );
			add_filter( 'cron_schedules', array( $this, 'filter_cron_schedule' ) );
			if ( ! wp_next_scheduled( 'gravityflow_cron' ) ) {
				wp_schedule_event( time(), 'fifteen_minutes', 'gravityflow_cron' );
			}

			add_action( 'gravityflow_cron', array( $this, 'cron' ) );
			add_action( 'wp', array( $this, 'filter_wp' ) );
			add_action( 'update_site_option_auto_update_plugins', array( $this, 'action_update_site_option_auto_update_plugins' ), 10, 3 );
		}

		/**
		 * Adds hooks required in both the front-end and the admin.
		 */
		public function init() {
			parent::init();

			if ( ! $this->is_gravityforms_supported( '2.5.6' ) ) {
				$this->init_translations();
			}

			if ( ! is_user_logged_in() ) {
				add_filter( 'nonce_user_logged_out', array( $this, 'filter_nonce_user_logged_out' ) );
			}

			add_shortcode( 'gravityflow', array( $this, 'shortcode' ) );

			// Prevent default feed processing behaviour; step processing starts after submission.
			remove_filter( 'gform_entry_post_save', array( $this, 'maybe_process_feed' ), 10 );
			add_filter( 'auto_update_plugin', array( $this, 'maybe_auto_update' ), 10, 2 );
			add_filter( 'gform_enqueue_scripts', array( $this, 'filter_gform_enqueue_scripts' ), 10, 2 );
			add_filter( 'gform_pre_replace_merge_tags', array( $this, 'replace_variables' ), 10, 7 );

			add_filter( 'gform_is_value_match', array( $this, 'filter_gform_is_value_match' ), 10, 6 );

			add_action( 'gform_entry_created', array( $this, 'action_entry_created' ), 8, 2 );
			add_action( 'gform_register_init_scripts', array( $this, 'filter_gform_register_init_scripts' ), 10, 3 );
			add_action( 'wp_login', array( $this, 'filter_wp_login' ), 10, 2 );

			if ( $this->is_gravityforms_supported( '2.3.4.2' ) ) {
				add_filter( 'gform_entry_pre_handle_confirmation', array( $this, 'after_submission' ), 9, 2 );
			} elseif ( $this->is_gravityforms_supported( '2.3.3.10' ) ) {
				add_action( 'gform_pre_handle_confirmation', array( $this, 'after_submission' ), 9, 2 );
			} else {
				add_action( 'gform_after_submission', array( $this, 'after_submission' ), 9, 2 );
			}

			add_action( 'gform_after_update_entry', array( $this, 'filter_after_update_entry' ), 10, 2 );

			add_filter( 'gform_form_settings_menu', array( $this, 'filter_form_settings_menu' ), 10, 1 );
            add_filter( 'gform_form_settings_menu', array( $this, 'filter_extension_form_settings_menu' ), 20, 1 );

			$this->add_delayed_payment_support(
				array(
					'option_label' => esc_html__( 'Start the Workflow once payment has been received.', 'gravityflow' ),
				)
			);

			add_filter( 'add_menu_classes', array( $this, 'show_inbox_count' ), 10 );

			// Integrations - GravityView.
			require_once dirname( __FILE__ ) . '/includes/integrations/gravityview-hooks.php';
		}

		/**
		 * Adds the admin side hooks.
		 */
		public function init_admin() {
			parent::init_admin();

			add_action( 'gform_entry_detail_sidebar_middle', array( $this, 'entry_detail_status_box' ), 10, 2 );
			add_filter( 'gform_notification_events', array( $this, 'add_notification_event' ), 10, 2 );

			add_filter( 'set-screen-option', array( $this, 'set_option' ), 10, 3 );
			add_action( 'load-workflow_page_gravityflow-status', array( $this, 'load_screen_options' ) );
			add_filter( 'gform_entries_field_value', array( $this, 'filter_gform_entries_field_value' ), 10, 4 );

			add_action( 'admin_enqueue_scripts', array( $this, 'action_admin_enqueue_scripts' ) );

			add_filter( $this->_slug . '_feed_actions', array( $this, 'filter_feed_actions' ), 10, 3 );

			if ( ! has_action( 'gform_post_form_duplicated', array( $this, 'post_form_duplicated' ) ) ) {
				add_action( 'gform_post_form_duplicated', array( $this, 'post_form_duplicated' ), 10, 2 );
			}

			// Members 2.0+ Integration.
			if ( function_exists( 'members_register_cap_group' ) ) {
				remove_filter( 'members_get_capabilities', array( $this, 'members_get_capabilities' ) );
				add_action( 'members_register_cap_groups', array( $this, 'members_register_cap_group' ) );
				add_action( 'members_register_caps', array( $this, 'members_register_caps' ) );
			}

			if ( $this->is_app_settings() ) {
				require_once( GFCommon::get_base_path() . '/tooltips.php' );
			}

			add_action( 'admin_notices', array( $this, 'action_admin_notices' ) );

			wp_register_style( 'gravityflow_dashicons', plugins_url( 'gravityflow/css/gravityflow-icon.css' ) );
			wp_enqueue_style( 'gravityflow_dashicons' );

			$locking = new Locking\Locking();
			$locking->enqueue_scripts();
		}

		/**
		 * Adds the Ajax hooks.
		 */
		public function init_ajax() {
			parent::init_ajax();
			add_action( 'wp_ajax_gravityflow_save_feed_order', array( $this, 'ajax_save_feed_order' ) );
			add_action( 'wp_ajax_gravityflow_feed_message', array( $this, 'ajax_feed_message' ) );

			add_action( 'wp_ajax_gravityflow_print_entries', array( $this, 'ajax_print_entries' ) );
			add_action( 'wp_ajax_nopriv_gravityflow_print_entries', array( $this, 'ajax_print_entries' ) );

			add_action( 'wp_ajax_gravityflow_export_status', array( $this, 'ajax_export_status' ) );
			add_action( 'wp_ajax_nopriv_gravityflow_export_status', array( $this, 'ajax_export_status' ) );
			add_action( 'wp_ajax_gravityflow_download_export', array( $this, 'ajax_download_export' ) );

			add_action( 'wp_ajax_nopriv_gravityflow_render_reports', array( $this, 'ajax_render_workflow_reports' ) );
			add_action( 'wp_ajax_gravityflow_render_reports', array( $this, 'ajax_render_workflow_reports' ) );
		}

		/**
		 * Adds the front-end hooks.
		 */
		public function init_frontend() {
			parent::init_frontend();
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ), 10 );
			add_action( 'template_redirect', array( $this, 'action_template_redirect' ), 2 );
			if ( class_exists( 'GFSignature' ) && ! class_exists( 'GF_Field_Signature' ) ) {
				add_filter( 'gform_admin_pre_render', array( $this, 'delete_signature_script' ) );
				$this->maybe_save_signature();
			}

			add_filter( 'query_vars', array( $this, 'filter_query_vars' ), 99 );
			add_filter( 'gform_require_login', array( $this, 'filter_gform_require_login' ), 99, 2 );

		}

		/**
		 * Skips the login required check for AJAX multi-file uploads.
		 *
		 * @since 2.8.6
		 *
		 * @param bool  $require_login Whether the form requires login.
		 * @param array $form          The form object.
		 *
		 * @return bool
		 */
		public function filter_gform_require_login( $require_login, $form ) {

			if ( ! class_exists( 'GFAsyncUpload' ) || ! isset( $_POST['gravityflow_step_upload_nonce'] ) ) {
				return $require_login;
			}

			$entry_id          = absint( rgpost( 'entry_id' ) );
			$nonce_action_args = array(
				'gravityflow_step_upload',
				absint( rgar( $form, 'id' ) ),
				absint( rgpost( 'field_id' ) ),
				$entry_id,
				absint( gform_get_meta( $entry_id, 'workflow_step' ) ),
			);

			if ( ! wp_verify_nonce( $_POST['gravityflow_step_upload_nonce'], implode( '|', $nonce_action_args ) ) ) {
				GFAsyncUpload::die_error( 403, __( 'Failed to upload file.', 'gravityflow' ) );
			}

			// The nonce is valid.
			// GFAsyncUpload validates a nonce for forms requiring login; it doesn't support email based assignees.
			// Returning false to prevent it occurring.

			return false;
		}

		/**
		 * Returns the plugin short title.
		 *
		 * @return string
		 */
		public function get_short_title() {
			$is_gravityforms_uninstall = rgget( 'page' ) == 'gf_settings' && rgget( 'subview' ) == 'uninstall';
			return $is_gravityforms_uninstall ? $this->_title : $this->translate_navigation_label( 'workflow' );
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
		 * Installs or upgrades the plugin.
		 */
		public function setup() {
			Translations\Manager::get_instance( $this->get_slug() )->legacy_install_on_setup( $this );
			parent::setup();
		}

		/**
		 * Performs installation or upgrade tasks.
		 *
		 * @param string $previous_version The previously installed version number.
		 */
		public function upgrade( $previous_version ) {

			wp_cache_flush();

			if ( empty( $previous_version ) ) {
				// New installation.
				$settings = $this->get_app_settings();
				if ( defined( 'GRAVITY_FLOW_LICENSE_KEY' ) ) {
					$settings['license_key'] = GRAVITY_FLOW_LICENSE_KEY;
				} else {
					update_option( 'gravityflow_pending_installation', true );
				}
				$settings['background_updates'] = true;
				$this->update_app_settings( $settings );
				$this->update_wp_auto_updates( true );

			} else {
				// Upgrade.
				if ( version_compare( $previous_version,'1.5.1', '<' ) ) {
					$this->fix_workflow_field_choices();
				}

				if ( version_compare( $previous_version,'1.7.1-dev', '<' ) ) {
					$this->upgrade_171();
				}

				if ( version_compare( $previous_version, '2.0.2-dev', '<' ) ) {
					$this->upgrade_202();
				}

				if ( version_compare( $previous_version, '2.4.0-dev', '<' ) ) {
					$this->upgrade_240();
				}

				if ( version_compare( $previous_version, '2.5', '<' ) ) {
					$this->upgrade_250();
				}

				if ( version_compare( $previous_version, '2.5.12', '<' ) ) {
					$this->upgrade_2512();
				}

			}

			wp_cache_flush();

			$this->setup_db();
		}

		/**
		 * Creates the activity log table.
		 */
		private function setup_db() {
			global $wpdb;

			// Default collation.
			$charset_collate = 'utf8_unicode_ci';

			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			if ( ! empty( $wpdb->charset ) ) {
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty( $wpdb->collate ) ) {
				$charset_collate .= " COLLATE $wpdb->collate";
			}

			$sql = "
CREATE TABLE {$wpdb->prefix}gravityflow_activity_log (
id bigint(20) unsigned not null auto_increment,
log_object varchar(50),
log_event varchar(50),
log_value varchar(255),
date_created datetime not null,
form_id mediumint(8) unsigned not null,
lead_id int(10) unsigned not null,
assignee_id varchar(255),
assignee_type varchar(50),
display_name varchar(250),
feed_id mediumint(8) unsigned not null,
duration int(10) unsigned not null,
PRIMARY KEY  (id)
) $charset_collate;";

			// Fixes an issue with dbDelta lower-casing table names, which cause problems on case sensitive DB servers.
			if ( class_exists( 'GF_Upgrade' ) ) {
				add_filter( 'dbdelta_create_queries', array( gf_upgrade(), 'dbdelta_fix_case' ) );
			} else {
				// Deprecated since Gravity Forms 2.2.
				add_filter( 'dbdelta_create_queries', array( 'RGForms', 'dbdelta_fix_case' ) );
			}

			dbDelta( $sql );

			if ( class_exists( 'GF_Upgrade' ) ) {
				remove_filter( 'dbdelta_create_queries', array( gf_upgrade(), 'dbdelta_fix_case' ) );
			} else {
				// Deprecated since Gravity Forms 2.2.
				remove_filter( 'dbdelta_create_queries', array( 'RGForms', 'dbdelta_fix_case' ) );
			}
		}

		/**
		 * Fixes and issue with the Assignee, User and Role fields where the choices are saved in the form meta causing
		 * conditional logic and field filters to display a dropdown with out of date choices.
		 *
		 * @since 1.5.1
		 */
		private function fix_workflow_field_choices() {
			$forms = GFAPI::get_forms();
			foreach ( $forms as $form ) {
				$form_dirty = false;
				if ( isset( $form['fields'] ) && is_array( $form['fields'] ) ) {
					foreach ( $form['fields'] as $field ) {
						/* @var GF_Field $field */
						if ( in_array( $field->type, array( 'workflow_assignee_select', 'workflow_user', 'workflow_role' ) ) ) {
							if ( is_array( $field->choices ) ) {
								$field->choices = '';
								$form_dirty = true;
							}
						}
					}
				}
				if ( $form_dirty ) {
					GFAPI::update_form( $form );
				}
			}
		}

		/**
		 * Updates the steps in the database for compatibility with versions 1.7.1 and greater.
		 */
		public function upgrade_171() {
			$steps = $this->get_steps();

			foreach( $steps as $step ) {
				$step_dirty = false;
				$step_type = $step->get_type();

				if ( $step_type == 'approval' && $step->type == 'select' && ! $step->assignee_policy ) {
					// Convert unanimous_approval setting to assignee_policy if not already.
					$unanimous_approval = $step->unanimous_approval;
					if ( ! $unanimous_approval ) {
						$step->assignee_policy = 'any';
					} else {
						$step->assignee_policy = 'all';
					}
					$step_dirty = true;
				}

				if ( in_array( $step_type, array( 'approval', 'user_input' ), true )
				     && $step->type == 'routing'
				     && ! $step->assignee_policy_171_migration_complete
				) {
					$step->assignee_policy = 'all';
					$step->assignee_policy_171_migration_complete = true;
					$step_dirty = true;
				}

				if ( $step_dirty ) {
					$this->save_feed_settings( $step->get_id(), $step->get_form_id(), $step->get_feed_meta() );
				}
			}
		}

		/**
		 * Migrate the custom settings added by Gravity_Flow_Step_Feed_Sliced_Invoices to their equivalent settings in the Sliced Invoices add-on.
		 */
		public function upgrade_202() {
			$feeds = $this->get_feeds_by_slug( 'slicedinvoices' );

			foreach ( $feeds as $feed ) {
				$feed_dirty = false;
				$feed_meta  = $feed['meta'];

				$quote_status = rgar( $feed_meta, 'quote_status' );
				if ( $quote_status ) {
					$feed_meta['set_quote_status'] = $quote_status;
					unset( $feed_meta['quote_status'] );
					$feed_dirty = true;
				}

				$invoice_status = rgar( $feed_meta, 'invoice_status' );
				if ( $quote_status ) {
					$feed_meta['set_invoice_status'] = $invoice_status;
					unset( $feed_meta['invoice_status'] );
					$feed_dirty = true;
				}

				$line_items = rgar( $feed_meta, 'mappedFields_line_items' );
				if ( $line_items === 'entry_order_summary' ) {
					$feed_meta['use_product_fields']      = true;
					$feed_meta['mappedFields_line_items'] = '';
					$feed_dirty                           = true;
				}

				if ( $feed_dirty ) {
					$this->update_feed_meta( $feed['id'], $feed_meta );
				}
			}
		}

		/**
		 * Migrate the Gravity PDF Select field to a Checkbox field
		 *
		 * @since 2.4
		 */
		public function upgrade_240() {
			$steps = $this->get_steps();

			foreach ( $steps as $step ) {
				$step_dirty = false;
				$feed_meta  = $step->get_feed_meta();
				foreach ( $feed_meta as $key => $value ) {
					if ( strpos( $key, 'gpdfEnable' ) !== false && $value ) {
						$pdf_key = str_replace( 'gpdfEnable', 'gpdfValue', $key );
						if ( isset( $feed_meta[ $pdf_key ] ) ) {
							$pdf_id      = $feed_meta[ $pdf_key ];
							$new_pdf_key = str_replace( 'gpdfEnable', 'gravitypdf_' . $pdf_id, $key );

							unset( $feed_meta[ $key ] );
							unset( $feed_meta[ $pdf_key ] );
							$feed_meta[ $new_pdf_key ] = '1';

							$step_dirty = true;
						}
					}
				}

				if ( $step_dirty ) {
					$this->save_feed_settings( $step->get_id(), $step->get_form_id(), $feed_meta );
				}
			}
		}

		/**
		 * Turn on the security setting which allows shortcodes to override permissions.
		 *
		 * @since 2.5
		 */
		public function upgrade_250() {
			$settings = $this->get_app_settings();

			$settings['allow_display_all_attribute']     = true;
			$settings['allow_allow_anonymous_attribute'] = true;
			$settings['allow_field_ids']                 = true;
			$this->update_app_settings( $settings );
		}

		/**
		 * Populates the WordPress auto_update_plugins option, if background updates is enabled.
		 *
		 * @since 2.5.12
		 */
		public function upgrade_2512() {
			$settings = $this->get_app_settings();
			if ( $settings['background_updates'] ) {
				$this->update_wp_auto_updates( true );
			}
		}

		/**
		 * Enqueue the JavaScript and output the root url and the nonce.
		 *
		 * @return array
		 */
		public function scripts() {
			$form_id           = absint( rgget( 'id' ) );
			$form              = GFAPI::get_form( $form_id );
			$routing_fields    = ! empty( $form ) ? GFCommon::get_field_filter_settings( $form ) : array();
			$input_fields      = array();
			$has_start_step    = false;
			$has_complete_step = false;
			if ( is_array( rgar( $form, 'fields' ) ) ) {
				foreach ( $form['fields'] as $field ) {
					/* @var GF_Field $field */
					$input_fields[] = array(
						'key'  => absint( $field->id ),
						'text' => esc_html( $field->get_field_label( false, null ) ),
					);
				}

				if ( $this->is_form_settings( 'gravityflow' ) && $this->is_feed_list_page() ) {
					if ( $this->get_workflow_start_step( $form_id ) ) {
						$has_start_step = true;
					}
					if ( $this->get_workflow_complete_step( $form_id ) ) {
						$has_complete_step = true;
					}
				}
			}

			$users = $this->is_form_settings( 'gravityflow' ) ? $this->get_users_as_choices() : array();


			$legacy = version_compare( GFForms::$version, '2.5-dev-1', '<' ) ? '-legacy' : '';

			$nonce = wp_create_nonce( 'wp_rest' );

			$scripts = array(
				array(
					'handle'   => 'gravityflow_form_editor_js',
					'src'      => $this->get_base_url() . "/js/form-editor{$this->min()}.js",
					'version'  => $this->_version,
					'enqueue'  => array(
						array(
							'admin_page' => array('form_editor'),
						),
					),
					'strings' => array(
						'user' => array(
							'defaults' => array(
								'label' => esc_html__( 'User', 'gravityflow' ),
							),
						),
						'role' => array(
							'defaults' => array(
								'label' => esc_html__( 'Role', 'gravityflow' ),
							),
						),
						'discussion' => array(
							'defaults' => array(
								'label' => esc_html__( 'Discussion', 'gravityflow' ),
							),
						),
					),
				),
				array(
					'handle'   => 'gravityflow_settings_js',
					'src'      => $this->get_base_url() . "/js/settings.js",
					'version'  => $this->_version,
					'enqueue'  => array(
						array( 'query' => 'page=gravityflow_settings&view=connected_apps' ),
					),
					'strings' => array(
						'nonce' => wp_create_nonce( 'gflow_settings_js' ),
						'ajaxurl' => admin_url( 'admin-ajax.php' ),
						'required_fields' => esc_html__( 'Please fill in all required fields', 'gravityflow' ),
					)
				),
				array(
					'handle'  => 'gravityflow_multi_select',
					'src'     => $this->get_base_url() . "/js/multi-select{$this->min()}.js",
					'deps'    => array( 'jquery' ),
					'version' => $this->_version,
					'enqueue' => array(
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=_notempty_' ),
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=0' ),
					),
				),
				array(
					'handle'  => 'gravityflow_quicksearch',
					'src'     => $this->get_base_url() . "/js/quicksearch{$this->min()}.js",
					'deps'    => array( 'jquery' ),
					'version' => $this->_version,
					'enqueue' => array(
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=_notempty_' ),
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=0' ),
					),
				),
				array(
					'handle'  => 'gf_routing_setting',
					'src'     => $this->get_base_url() . "/js/routing-setting{$this->min()}.js",
					'deps'    => array( 'jquery' ),
					'version' => $this->_version,
					'enqueue' => array(
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=_notempty_' ),
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=0' ),
					),
					'strings' => array(
						'accounts'     => $users,
						'fields'       => $routing_fields,
						'input_fields' => $input_fields,
					),
				),
				array(
					'handle'  => 'gravityflow_form_settings_js',
					'src'     => $this->get_base_url() . "/js/form-settings{$legacy}{$this->min()}.js",
					'deps'    => array( 'jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'jquery-ui-datepicker', 'gform_datepicker_init', 'gf_routing_setting' ),
					'version' => $this->_version,
					'enqueue' => array(
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=_notempty_' ),
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=0' ),
					),
					'strings' => array(
						'feedId'         => absint( rgget( 'fid' ) ),
						'formId'         => absint( rgget( 'id' ) ),
						'mergeTagLabels' => $this->get_form_settings_js_merge_tag_labels(),
						'assigneeSearchPlaceholder' => esc_attr__( 'Type to search', 'gravityflow' ),
					),
				),
				array(
					'handle'  => 'gform_field_filter',
					'src'     => GFCommon::get_base_url() . "/js/routing-setting{$this->min()}.js",
					'deps'    => array( 'jquery', 'gform_datepicker_init' ),
					'version' => $this->_version,
					'enqueue' => array(
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=_notempty_' ),
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=0' ),
					),
				),
				array(
					'handle'  => 'gravityflow_feed_list',
					'src'     => $this->get_base_url() . "/js/feed-list{$this->min()}.js",
					'deps'    => array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker' ),
					'version' => $this->_version,
					'enqueue' => array(
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow' ),
					),
					'strings' => array(
						'hasStartStep'    => $has_start_step,
						'hasCompleteStep' => $has_complete_step,
						'formId'          => $form_id,
						'nonce'           => wp_create_nonce( 'gravityflow_feed_list' ),
					),
				),
				array(
					'handle'  => 'gravityflow_entry_detail',
					'src'     => $this->get_base_url() . "/js/entry-detail{$this->min()}.js",
					'version' => $this->_version,
					'deps'    => array( 'jquery', 'sack' ),
					'enqueue' => array(
						array( 'query' => 'page=gravityflow-inbox', ),
						array( 'query' => 'page=gf_entries', ),
					),
				),
				array(
					'handle'  => 'gravityflow_status_list',
					'src'     => $this->get_base_url() . "/js/status-list{$this->min()}.js",
					'deps'    => array( 'jquery', 'gform_field_filter' ),
					'version' => $this->_version,
					'enqueue' => array(
						array(
							'query' => 'page=gravityflow-status',
						),
					),
					'strings' => array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ),
				),
				array(
					'handle'  => 'google_charts',
					'src'     => 'https://www.google.com/jsapi',
					'version' => $this->_version,
					'enqueue' => array(
						array( 'query' => 'page=gravityflow-reports' ),
					),
				),
				array(
					'handle'  => 'gravityflow_reports',
					'src'     => $this->get_base_url() . "/js/reports{$this->min()}.js",
					'version' => $this->_version,
					'deps'    => array( 'jquery', 'google_charts' ),
					'enqueue' => array(
						array( 'query' => 'page=gravityflow-reports' ),
					),
				),
				array(
					'handle' => 'gravityflow_inbox',
					'src' => $this->get_base_url() . "/js/inbox{$this->min()}.js",
					'version' => $this->_version,
					'enqueue' => array(
						array(
							'query' => 'page=gravityflow-inbox',
						),
					),
					'strings' => array(
						'restUrl' => esc_url_raw( rest_url() ),
						'nonce'   => $nonce,
					),
				),
			);

			return array_merge( parent::scripts(), $scripts );
		}

		/**
		 * Get the correct script suffix depending on SCRIPT_DEBUg state.
		 *
		 * @return string
		 */
		private function min() {
			return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';
		}

		/**
		 * Target for the wp_enqueue_scripts hook.
		 *
		 * Enqueues the required front-end scripts when the shortcode is found in the post content.
		 */
		public function enqueue_frontend_scripts() {
			global $wp_query;

			if ( isset( $wp_query->posts ) && is_array( $wp_query->posts ) ) {
				$shortcode_found = $this->look_for_shortcode();

				if ( $shortcode_found ) {
					$this->enqueue_form_scripts();
					$nonce = wp_create_nonce( 'wp_rest' );

					// Enqueue new theme CSS and JS bundles
					wp_enqueue_style( self::ADMIN_COMPONENTS_CSS,  $this->get_base_url() . "/assets/css/dist/admin-components{$this->min()}.css", null, $this->_version );
					wp_enqueue_style( self::THEME_CSS,  $this->get_base_url() . "/assets/css/dist/theme{$this->min()}.css", null, $this->_version );
					wp_enqueue_script( self::VENDOR_JS_THEME, $this->get_base_url() . "/assets/js/dist/vendor-theme{$this->min()}.js", array(), $this->_version, true );
					wp_enqueue_script( self::THEME_JS, $this->get_base_url() . "/assets/js/dist/scripts-theme{$this->min()}.js", array( self::VENDOR_JS_THEME ), $this->_version, true );

					wp_enqueue_script( 'sack', "/wp-includes/js/tw-sack{$this->min()}.js", array(), '1.6.1' );
					wp_enqueue_script( 'gravityflow_entry_detail', $this->get_base_url() . "/js/entry-detail{$this->min()}.js", array( 'jquery', 'sack' ), $this->_version );
					wp_enqueue_script( 'gravityflow_status_list', $this->get_base_url() . "/js/status-list{$this->min()}.js",  array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'gform_datepicker_init' ), $this->_version );
					wp_enqueue_script( 'gform_field_filter', GFCommon::get_base_url() . "/js/gf_field_filter{$this->min()}.js",  array( 'jquery', 'gform_datepicker_init' ), $this->_version );
					wp_enqueue_script( 'gravityflow_frontend', $this->get_base_url() . "/js/frontend{$this->min()}.js",  array(), $this->_version );
					wp_enqueue_script( 'gravityflow_inbox', $this->get_base_url() . "/js/inbox{$this->min()}.js",  array(), $this->_version );

					wp_enqueue_style( 'gform_admin_icons', GFCommon::get_base_url() . "/assets/css/dist/admin-icons{$this->min()}.css", array(), $this->_version );
					wp_enqueue_style( 'gform_admin', GFCommon::get_base_url() . "/css/admin{$this->min()}.css", null, $this->_version );
					wp_enqueue_style( 'gform_font_awesome', GFCommon::get_base_url() . "/css/font-awesome{$this->min()}.css", null, $this->_version );
					wp_enqueue_style( 'gravityflow_entry_detail', $this->get_base_url() . "/css/entry-detail{$this->min()}.css", null, $this->_version );
					wp_enqueue_style( 'gravityflow_frontend_css', $this->get_base_url() . "/css/frontend{$this->min()}.css", null, $this->_version );
					wp_enqueue_style( 'gravityflow_status', $this->get_base_url() . "/css/status{$this->min()}.css", null, $this->_version );
					wp_localize_script( 'gravityflow_status_list', 'gravityflow_status_list_strings', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
					wp_localize_script( 'gravityflow_inbox', 'gravityflow_inbox_strings', array( 'restUrl' => esc_url_raw( rest_url() ), 'nonce' => $nonce ) );

					/**
					 * Allows additional scripts to be enqueued when the gravityflow shortcode is present on the page.
					 */
					do_action( 'gravityflow_enqueue_frontend_scripts' );
					GFCommon::maybe_output_gf_vars();
				}
			}
		}

		/**
		 * Determines if at least one of the posts for the current WP query contains the shortcode or block.
		 *
		 * @return bool
		 */
		public function look_for_shortcode() {
			if ( ! is_null( self::$has_shortcode ) ) {
				return self::$has_shortcode;
			}

			global $wp_query;

			if ( isset( $wp_query->posts ) ) {
				foreach ( $wp_query->posts as $post ) {
					if ( $post instanceof WP_Post && $this->has_shortcode_or_block( $post->post_content ) ) {
						self::$has_shortcode = true;
						return true;
					}
				}
			}

			self::$has_shortcode = false;
			return false;
		}

		/**
		 * Determines if the supplied post content contains the shortcode or block (also checks post content for reusable blocks).
		 *
		 * @since 2.5.10
		 *
		 * @param string $post_content The post content to be checked.
		 *
		 * @return bool
		 */
		public function has_shortcode_or_block( $post_content ) {
			if ( empty( $post_content ) ) {
				return false;
			}

			if ( stripos( $post_content, '[gravityflow' ) !== false || stripos( $post_content, '<!-- wp:gravityflow/' ) !== false ) {
				return true;
			}

			if ( ! function_exists( 'has_block' ) || ! has_block( 'block', $post_content ) ) {
				return false;
			}

			$blocks = parse_blocks( $post_content );

			foreach ( $blocks as $block ) {
				if ( rgar( $block, 'blockName' ) !== 'core/block' || empty( $block['attrs']['ref'] ) ) {
					continue;
				}

				$reusable_block = get_post( $block['attrs']['ref'] );
				if ( empty( $reusable_block ) || $reusable_block->post_type !== 'wp_block' ) {
					continue;
				}

				if ( $this->has_shortcode_or_block( $reusable_block->post_content ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Target of the nonce_user_logged_out hook.
		 *
		 * Sets the uid used in the logged out user nonce to the assignee key.
		 *
		 * @param int $uid ID of the nonce-owning user.
		 *
		 * @return int|string Zero or the assignee key.
		 */
		public function filter_nonce_user_logged_out( $uid ) {
			if ( empty( $uid ) ) {
				$assignee_key = $this->get_current_user_assignee_key();
				if ( ! empty( $assignee_key ) ) {
					$uid = $assignee_key;
				}
			}
			return $uid;
		}

		/**
		 * Target of the gform_enqueue_scripts hook.
		 *
		 * Enqueues the chosen script if a workflow field has the enhanced ui enabled.
		 *
		 * @param array $form    The current form.
		 * @param bool  $is_ajax Indicates if Ajax is enabled for this form.
		 */
		public function filter_gform_enqueue_scripts( $form, $is_ajax ) {

			if ( $this->has_enhanced_dropdown( $form ) ) {
				wp_enqueue_script( 'gform_gravityforms' );
				if ( wp_script_is( 'chosen', 'registered' ) ) {
					wp_enqueue_script( 'chosen' );
				} else {
					wp_enqueue_script( 'gform_chosen' );
				}
			}
		}

		/**
		 * Adds the enhanced ui init scripts for the workflow fields.
		 *
		 * @param array $form         The current form.
		 * @param array $field_values The dynamic population field values.
		 * @param bool  $is_ajax      Indicates if Ajax is enabled for this form.
		 */
		public function filter_gform_register_init_scripts( $form, $field_values, $is_ajax ) {

			if ( $this->has_enhanced_dropdown( $form ) ) {
				$chosen_script = $this->get_chosen_init_script( $form );
				GFFormDisplay::add_init_script( $form['id'], 'workflow_assignee_chosen', GFFormDisplay::ON_PAGE_RENDER, $chosen_script );
				GFFormDisplay::add_init_script( $form['id'], 'workflow_assignee_chosen', GFFormDisplay::ON_CONDITIONAL_LOGIC, $chosen_script );
			}
		}

		/**
		 * Returns the enhanced ui init script for the workflow field.
		 *
		 * @param array $form The current form.
		 *
		 * @return string
		 */
		public static function get_chosen_init_script( $form ) {
			$chosen_fields = array();
			foreach ( $form['fields'] as $field ) {
				$input_type = GFFormsModel::get_input_type( $field );
				if ( $field->enableEnhancedUI && in_array( $input_type, array( 'workflow_assignee_select', 'workflow_user', 'workflow_role', 'workflow_multi_user' ) ) ) {
					$chosen_fields[] = "#input_{$form['id']}_{$field->id}";
				}
			}

			return "gformInitChosenFields('" . implode( ',', $chosen_fields ) . "','" . esc_attr( apply_filters( "gform_dropdown_no_results_text_{$form['id']}", apply_filters( 'gform_dropdown_no_results_text', __( 'No results matched', 'gravityflow' ), $form['id'] ), $form['id'] ) ) . "');";
		}

		/**
		 * Determines if the enhanced UI is enabled on at least one of the workflow fields.
		 *
		 * @param array $form The current form.
		 *
		 * @return bool
		 */
		public function has_enhanced_dropdown( $form ) {

			if ( ! is_array( $form['fields'] ) ) {
				return false;
			}

			foreach ( $form['fields'] as $field ) {
				if ( in_array( RGFormsModel::get_input_type( $field ), array( 'workflow_assignee_select', 'workflow_user', 'workflow_role', 'workflow_multi_user' ) ) && $field->enableEnhancedUI ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * The feeds list page title.
		 *
		 * @return string
		 */
		public function feed_list_title() {
			$url            = add_query_arg( array( 'fid' => '0' ) );
			$url            = esc_url( $url );
			$legacy         = version_compare( GFForms::$version, '2.5-dev-1', '<' ) ? '-legacy' : '';
			$add_new_button = $legacy ? " <a class='add-new-h2' href='{$url}'>" . __( 'Add New', 'gravityflow' ) . '</a>' : '';

			return esc_html__( 'Workflow Steps', 'gravityflow' ) . $add_new_button;
		}

		/**
		 * The stylesheets to be enqueued.
		 *
		 * @return array
		 */
		public function styles() {

			$legacy = version_compare( GFForms::$version, '2.5-dev-1', '<' ) ? '-legacy' : '';

			$styles = array(
				array(
					'handle'  => 'gform_admin',
					'src'     => GFCommon::get_base_url() . "/assets/css/dist/admin{$this->min()}.css",
					'version' => GFForms::$version,
					'enqueue' => array(
						array(
							'query'      => 'page=gravityflow-inbox',
						),
						array(
							'query'      => 'page=gravityflow-submit',
						),
						array(
							'query'      => 'page=gravityflow-status',
						),
						array(
							'query'      => 'page=gravityflow-reports',
						),
						array(
							'query'      => 'page=gravityflow-activity',
						),
					),
				),
				array(
					'handle'  => 'gravityflow_inbox',
					'src'     => $this->get_base_url() . "/css/inbox{$this->min()}.css",
					'version' => $this->_version,
					'enqueue' => array(
						array(
							'query' => 'page=gravityflow-inbox',
						),
					),
				),
				array(
					'handle'  => 'gravityflow_entry_detail',
					'src'     => $this->get_base_url() . "/css/entry-detail{$this->min()}.css",
					'version' => $this->_version,
					'deps' => array( 'gform_admin' ),
					'enqueue' => array(
						array(
							'query'      => 'page=gravityflow-inbox&view=entry',
						),
					),
				),
				array(
					'handle'  => 'gravityflow_submit',
					'src'     => $this->get_base_url() . "/css/submit{$this->min()}.css",
					'version' => $this->_version,
					'enqueue' => array(
						array(
							'query'      => 'page=gravityflow-submit',
						),
					),
				),
				array(
					'handle'  => 'gravityflow_status',
					'src'     => $this->get_base_url() . "/css/status{$this->min()}.css",
					'version' => $this->_version,
					'enqueue' => array(
						array(
							'query'      => 'page=gravityflow-status',
						),
					)
				),
				array(
					'handle'  => 'gravityflow_reports',
					'src'     => $this->get_base_url() . "/css/reports{$this->min()}.css",
					'version' => $this->_version,
					'enqueue' => array(
						array(
							'query'      => 'page=gravityflow-reports',
						),
					)
				),
				array(
					'handle'  => 'gravityflow_activity',
					'src'     => $this->get_base_url() . "/css/activity{$this->min()}.css",
					'version' => $this->_version,
					'enqueue' => array(
						array(
							'query'      => 'page=gravityflow-activity',
						),
					),
				),
				array(
					'handle'  => 'gravityflow_feed_list',
					'src'     => $this->get_base_url() . "/css/feed-list{$this->min()}.css",
					'version' => $this->_version,
					'deps' => array( 'wp-color-picker' ),
					'enqueue' => array(
						array(
							'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow',
						),
					),
				),
				array(
					'handle'  => 'gravityflow_multi_select_css',
					'src'     => $this->get_base_url() . "/css/multi-select{$this->min()}.css",
					'version' => $this->_version,
					'enqueue' => array(
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=_notempty_' ),
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=0' ),
					),
				),
				array(
					'handle'  => 'gravityflow_form_settings',
					'src'     => $this->get_base_url() . "/css/form-settings{$legacy}{$this->min()}.css",
					'version' => $this->_version,
					'enqueue' => array(
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=_notempty_' ),
						array( 'query' => 'page=gf_edit_forms&view=settings&subview=gravityflow&fid=0' ),
					),
				),
				array(
					'handle'  => 'gravityflow_settings',
					'src'     => $this->get_base_url() . "/css/settings{$this->min()}.css",
					'version' => $this->_version,
					'enqueue' => array(
						array( 'query' => 'page=gravityflow_settings' ),
					),
				),
				array(
					'handle'  => 'gravityflow_discussion_field',
					'src'     => $this->get_base_url() . "/css/discussion-field{$this->min()}.css",
					'version' => $this->_version,
					'enqueue' => array(
						array( 'field_types' => array( 'workflow_discussion' ) ),
					),
				),
			);

			return array_merge( parent::styles(), $styles );
		}

		/**
		 * The feed settings page title.
		 *
		 * @return string
		 */
		public function feed_settings_title() {
			return esc_html__( 'Workflow Step Settings', 'gravityflow' );
		}

		/**
		 * Target for the set-screen-option hook.
		 *
		 * Sets the value of the entries_per_page option.
		 *
		 * @param bool|int $status False or the screen option value.
		 * @param string   $option The option name.
		 * @param int      $value  Screen option value.
		 *
		 * @return mixed
		 */
		public function set_option( $status, $option, $value ) {
			if ( 'entries_per_page' == $option ) {
				return $value;
			}

			return $status;
		}

		/**
		 * Returns a choices array containing users, roles, and applicable form fields.
		 *
		 * @return array
		 */
		public function get_users_as_choices() {
			static $choices;

			$args            = Gravity_Flow_Common::get_users_args();
			$total_accounts  = Gravity_Flow_Common::get_total_accounts();
			$account_choices = array();

			if ( $total_accounts > $args['number'] ) {
				$settings            = $this->get_feed( rgget( 'fid' ) );
				$feed_meta           = rgar( $settings, 'meta' );
				$notification_types  = array(
					'workflow',
					'assignee',
					'rejection',
					'approval',
					'in_progress',
					'complete',
					'revert'
				);
				$current_users       = array();
				$exclude_account_ids = array();
				// Get all types of notification users and assignees.
				foreach ( $notification_types as $type ) {
					$_type = ( $type === 'assignee' ) ? 'type' : $type . '_notification_type';

					if ( rgar( $feed_meta, $_type ) === 'select' ) {
						$key   = ( $type === 'assignee' ) ? 'assignees' : $type . '_notification_users';
						$value = rgar( $feed_meta, $key );
						if ( ! empty( $value ) ) {
							$current_users = array_merge( $current_users, $value );
						}
					} else {
						$key                   = ( $type === 'assignee' ) ? 'routing' : $type . '_notification_routing';
						$current_users_routing = rgar( $feed_meta, $key );
						if ( ! empty( $current_users_routing ) ) {
							$_current_users = array();
							foreach ( $current_users_routing as $_routing ) {
								$_current_users[] = $_routing['assignee'];
							}

							$current_users = array_merge( $current_users, $_current_users );
						}
					}
				}

				if ( ! empty( $current_users ) ) {
					foreach ( $current_users as $current_user ) {
						list( $string, $user_id ) = explode( '|', $current_user );
						$account = get_user_by( 'id', $user_id );
						if ( $account ) {
							$name                  = $account->display_name ? $account->display_name : $account->user_login;
							$account_choices[]     = array( 'value' => 'user_id|' . $account->ID, 'label' => $name );
							$exclude_account_ids[] = $user_id;
						}
					}

					if ( ! empty( $exclude_account_ids ) ) {
						// Exclude current assignees when get_users().
						$args['exclude'] = $exclude_account_ids;
					}
				}
			}

			$key  = md5( get_current_blog_id() . '_' . serialize( $args ) );

			if ( ! isset( $choices[ $key ] ) ) {
				$role_choices = Gravity_Flow_Common::get_roles_as_choices( true, true );

				// Get a user list.
				$accounts = get_users( $args );
				foreach ( $accounts as $account ) {
					$name              = $account->display_name ? $account->display_name : $account->user_login;
					$account_choices[] = array( 'value' => 'user_id|' . $account->ID, 'label' => $name );
				}

				if ( isset( $args['exclude'] ) ) {
					usort( $account_choices, array( $this, 'sort_account_choices' ) );
				}

				$choices[ $key ] = array(
					array(
						'label'   => __( 'Users', 'gravityflow' ),
						'choices' => $account_choices,
					),
					array(
						'label'   => __( 'Roles', 'gravityflow' ),
						'choices' => $role_choices,
					),
				);

				$form_id = absint( rgget( 'id' ) );

				$form = GFAPI::get_form( $form_id );

				$field_choices = array();

				$assignee_fields_as_choices = $this->get_assignee_fields_as_choices( $form );

				if ( ! empty( $assignee_fields_as_choices ) ) {
					$field_choices = $assignee_fields_as_choices;
				}

				$email_fields_as_choices = $this->get_email_fields_as_choices( $form );

				if ( ! empty( $email_fields_as_choices ) ) {
					$field_choices = array_merge( $field_choices, $email_fields_as_choices );
				}


				if ( rgar( $form, 'requireLogin' ) ) {
					$field_choices[] = array(
						'label' => __( 'User (Created by)', 'gravityflow' ),
						'value' => 'entry|created_by',
					);
				}

				if ( ! empty( $field_choices ) ) {
					$choices[ $key ][] = array(
						'label'   => __( 'Fields', 'gravityflow' ),
						'choices' => $field_choices,
					);
				}

				/**
				 * Allows the assignee choices to be modified.
				 *
				 * @since 2.1
				 *
				 * @param array $choices The assignee choices
				 * @param array $form    The Form
				 */
				$choices[ $key ] = apply_filters( 'gravityflow_assignee_choices', $choices[ $key ], $form );
			}

			return $choices[ $key ];
		}

		/**
		 * The usort() callback for sorting account choices.
		 *
		 * @since 2.5.3
		 *
		 * @param array $a The first account choice to compare.
		 * @param array $b The second first account choice to compare.
		 *
		 * @return int
		 */
		public function sort_account_choices( $a, $b ) {
			return $a['label'] > $b['label'];
		}

		/**
		 * Returns a choices array containing the forms assignee fields.
		 *
		 * @param null|array $form Null or the form to retrieve the assignee fields from.
		 *
		 * @return array
		 */
		public function get_assignee_fields_as_choices( $form = null ) {
			if ( empty( $form ) ) {
				$form_id = absint( rgget( 'id' ) );
				$form = GFAPI::get_form( $form_id );
			}

			$assignee_fields = array();
			if ( isset( $form['fields'] ) && is_array( $form['fields'] ) ) {
				foreach ( $form['fields'] as $field ) {
					/* @var GF_Field $field */
					$type = GFFormsModel::get_input_type( $field );
					if ( $type == 'workflow_assignee_select' ) {
						$assignee_fields[] = array( 'label' => GFFormsModel::get_label( $field ), 'value' => 'assignee_field|' . $field->id );
					} elseif ( $type == 'workflow_user' ) {
						$assignee_fields[] = array( 'label' => GFFormsModel::get_label( $field ), 'value' => 'assignee_user_field|' . $field->id );
					} elseif ( $type == 'workflow_multi_user' ) {
						$assignee_fields[] = array( 'label' => GFFormsModel::get_label( $field ), 'value' => 'assignee_multi_user_field|' . $field->id );
					} elseif ( $type == 'workflow_role' ) {
						$assignee_fields[] = array( 'label' => GFFormsModel::get_label( $field ), 'value' => 'assignee_role_field|' . $field->id );
					}
				}
			}
			return $assignee_fields;
		}

		/**
		 * Returns a choices array containing the forms email fields.
		 *
		 * @param null|array $form Null or the form to retrieve the email fields from.
		 *
		 * @return array
		 */
		public function get_email_fields_as_choices( $form = null ) {
			if ( empty( $form ) ) {
				$form_id = absint( rgget( 'id' ) );
				$form = GFAPI::get_form( $form_id );
			}

			$email_fields = array();
			if ( isset( $form['fields'] ) && is_array( $form['fields'] ) ) {
				foreach ( $form['fields'] as $field ) {
					/* @var GF_Field $field */
					if ( $field->get_input_type() == 'email' ) {
						$email_fields[] = array( 'label' => GFFormsModel::get_label( $field ), 'value' => 'email_field|' . $field->id );
					}
				}
			}
			return $email_fields;
		}

		/**
		 * The settings to appear on the edit feed page.
		 *
		 * @return array
		 */
		public function feed_settings_fields() {
			$current_step_id = $this->get_current_feed_id();

			$steps = array();

			$is_start_step     = false;
			$is_complete_step  = false;
			$has_start_step    = false;
			$has_complete_step = false;

			if ( $current_step_id ) {
				$step = $this->get_step( $current_step_id );
				if ( ! $step ) {
					wp_die(  __( 'Step settings unavailable. The selected step type is not active.', 'gravityflow' ) );
				}
				$step_type = $step->get_type();
			} else {
				$step_type = $this->get_setting( 'step_type' );
			}

			if ( $step_type === 'workflow_start' ) {
				$is_start_step = true;
			} elseif ( $step_type === 'workflow_complete' ) {
				$is_complete_step = true;
			}

			if ( $is_start_step ) {
				$has_start_step = true;
			} elseif ( $is_complete_step ) {
				$has_complete_step = true;
			}

			$form_id = absint( rgget( 'id' ) );

			if ( ! ( $has_start_step && $has_complete_step ) ) {
				// Workflows created before 2.5 don't have start or complete steps - they can be added manually.
				$steps   = $this->get_steps( $form_id );
				$has_start_step = $this->get_workflow_start_step( $form_id ) ? true : false;
				$has_complete_step = $this->get_workflow_complete_step( $form_id ) ? true : false;
			}

			$step_type_choices = array();

			$step_classes = Gravity_Flow_Steps::get_all();

			$start_step_choice = false;

			$complete_step_choice = false;

			foreach ( $step_classes as $key => $step_class ) {
				$step_type_choice = array( 'label' => $step_class->get_label(), 'value' => $step_class->get_type() );
				$step_type_choice['icon_url'] = $step_class->get_icon_url();
				if ( $current_step_id > 0 ) {
					$step_type_choice['disabled'] = 'disabled';
					$step_type_choice['div_class'] = 'gravityflow-disabled';
				}
				if ( $step_class->is_supported() ) {
					if ( $step_class->get_type() == 'workflow_start' ) {
						if ( $is_start_step || ( $steps && ! $has_start_step ) ) {
							$start_step_choice = $step_type_choice;
						}
					} elseif ( $step_class->get_type() == 'workflow_complete' ) {
						if ( $is_complete_step || ( $steps && ! $has_complete_step ) ) {
							$complete_step_choice = $step_type_choice;
						}
					} else {
						$step_type_choices[] = $step_type_choice;
					}
				} else {
					unset( $step_classes[ $key ] );
				}
			}

			if ( $start_step_choice ) {
				array_unshift( $step_type_choices, $start_step_choice );
			}

			if ( $complete_step_choice ) {
				array_push( $step_type_choices, $complete_step_choice );
			}

			$settings = array();

			$step_type_setting = array(
				'name'                => 'step_type',
				'label'               => esc_html__( 'Step Type', 'gravityflow' ),
				'type'                => 'radio_image',
				'horizontal'          => true,
				'required'            => true,
				'onchange'            => 'jQuery(this).parents("form").submit();',
				'choices'             => $step_type_choices,
				'validation_callback' => array( $this, 'step_type_validation_callback' ),
			);

			$step_id = absint( rgget( 'fid' ) );

			$step_title = $step_id === 0 ? $step_title = esc_html__( 'Step', 'gravityflow' ) : esc_html__( 'Step ID #', 'gravityflow' ) . $step_id;

			$settings[] = array(
				'title'  => $step_title,
				'fields' => array(
					array(
						'name'     => 'step_name',
						'label'    => __( 'Name', 'gravityflow' ),
						'type'     => 'text',
						'class'    => 'medium',
						'required' => true,
						'tooltip'  => '<h6>' . __( 'Name', 'gravityflow' ) . '</h6>' . __( 'Enter a name to uniquely identify this step.', 'gravityflow' ),
					),
					array(
						'name'  => 'description',
						'label' => esc_html__( 'Description', 'gravityflow' ),
						'class' => 'fieldwidth-3 fieldheight-2',
						'type'  => 'textarea',
					),
					$step_type_setting,
				),
			);

			if ( $is_start_step ) {
				if (  Gravity_Flow_Partial_Entries::get_instance()->is_workflow_enabled( $form_id ) ) {
					/* translators: 1: number textbox 2: units of time dropdown */
					$delay_label = esc_html__( 'Start this workflow %1$s %2$s after the form submission or after the partial entry has been created or updated.', 'gravityflow' );
				} else {
					/* translators: 1: number textbox 2: units of time dropdown */
					$delay_label = esc_html__( 'Start this workflow %1$s %2$s after the form submission.', 'gravityflow' );
				}
				$standard_fields = array(
					array(
						'name'           => 'condition',
						'tooltip'        => esc_html__( "Build the conditional logic that should be applied to this workflow before it's allowed to be processed. If an entry does not meet the conditions then the workflow will not be processed.", 'gravityflow' ),
						'label'          => esc_html__( 'Workflow Condition', 'gravityflow' ),
						'type'           => 'feed_condition',
						'callback'       => array( $this, 'settings_feed_condition' ),
						'checkbox_label' => esc_html__( 'Enable Condition for this workflow', 'gravityflow' ),
						'instructions'   => esc_html__( 'Process this workflow if', 'gravityflow' ),
					),
					array(
						'name'             => 'scheduled',
						'label'            => esc_html__( 'Workflow Schedule', 'gravityflow' ),
						'type'             => 'schedule',
						'checkbox_label'   => esc_html__( 'Schedule this workflow', 'gravityflow' ),
						'date_label'       => esc_html__( 'Start this workflow on %s', 'gravityflow' ),
						'date_field_label' => esc_html__( 'Start this workflow %1$s %2$s %3$s %4$s', 'gravityflow' ),
						'delay_label'      => $delay_label,
						'tooltip'          => esc_html__( 'Scheduling the workflow will queue entries and prevent them from starting the workflow until the specified date or until the delay period has elapsed.', 'gravityflow' )
						                      . ' ' . esc_html__( 'Note: the schedule setting requires the WordPress Cron which is included and enabled by default unless your host has deactivated it.', 'gravityflow' ),

					),
				);
				$settings[0]['fields'] = array_merge( $settings[0]['fields'], $standard_fields );
			} elseif ( $is_complete_step ) {
				$standard_fields = array(
					array(
						'name'     => 'step_highlight',
						'default_value' => '0',
						'type'     => 'hidden',
						'required' => false,
						'tooltip'  => esc_html__( 'Highlighted steps will stand out in both the workflow inbox and the step list. Use highlighting to bring attention to important tasks and to help organise complex workflows.', 'gravityflow' ),
					),
					array(
						'name'  => 'feed_condition_conditional_logic',
						'default_value' => '0',
						'type'  => 'hidden',
					),
					array(
						'name'             => 'scheduled',
						'label'            => esc_html__( 'Workflow Schedule', 'gravityflow' ),
						'type'             => 'hidden',
					),
				);
				$settings[0]['fields'] = array_merge( $settings[0]['fields'], $standard_fields );
			} elseif ( ! $is_complete_step ) {
				$standard_fields = array(
					array(
						'name'     => 'highlight',
						'label'    => esc_html__( 'Highlight', 'gravityflow' ),
						'type'     => 'highlight',
						'required' => false,
						'tooltip'  => esc_html__( 'Highlighted steps will stand out in both the workflow inbox and the step list. Use highlighting to bring attention to important tasks and to help organise complex workflows.', 'gravityflow' ),
					),
					array(
						'name'           => 'condition',
						'tooltip'        => esc_html__( "Build the conditional logic that should be applied to this step before it's allowed to be processed. If an entry does not meet the conditions of this step it will fall on to the next step in the list.", 'gravityflow' ),
						'label'          => esc_html__( 'Condition', 'gravityflow' ),
						'type'           => 'feed_condition',
						'callback'       => array( $this, 'settings_feed_condition' ),
						'checkbox_label' => esc_html__( 'Enable Condition for this step', 'gravityflow' ),
						'instructions'   => esc_html__( 'Perform this step if', 'gravityflow' ),
					),
					array(
						'name'    => 'scheduled',
						'label'   => esc_html__( 'Schedule', 'gravityflow' ),
						'type'    => 'schedule',
						'tooltip' => esc_html__( 'Scheduling a step will queue entries and prevent them from starting this step until the specified date or until the delay period has elapsed.', 'gravityflow' )
						             . ' ' . esc_html__( 'Note: the schedule setting requires the WordPress Cron which is included and enabled by default unless your host has deactivated it.', 'gravityflow' ),

					),
				);
				$settings[0]['fields'] = array_merge( $settings[0]['fields'], $standard_fields );
			}

			foreach ( $step_classes as $step_class ) {
				$type = $step_class->get_type();
				if ( $step_type !== $type ) {
					continue;
				}
				$step_settings = $step_class->get_step_settings();
				$step_settings['id'] = 'gravityflow-step-settings-' . $type;
				$step_settings['class'] = 'gravityflow-step-settings';

				if ( ! isset( $step_settings['fields'] ) ) {
					$step_settings['fields'] = array();
				}
				$status_options = $step_class->get_status_config();

				if ( $step_class->supports_due_date() ) {
					$final_status_choices = array();

					foreach ( $status_options as $status_option ) {
						$final_status_choices[] = array( 'label' => $status_option['status_label'], 'value' => $status_option['status'] );
					}

					$final_status_choices[] = array( 'label' => esc_html__( 'Due date', 'gravityflow' ), 'value' => 'due_date' );

					$step_settings['fields'][] = array(
						'name' => 'due_date',
						'label' => esc_html__( 'Due date', 'gravityflow' ),
						'tooltip' => esc_html__( 'Enable the due date setting to allow entries to be highlighted when they have passed their due dates. An optional column can be set on the inbox / status pages too.', 'gravityflow' ),
						'type'       => 'due_date',
						'status_choices' => $final_status_choices,
					);
				}

				if ( $step_class->supports_expiration() ) {
					$final_status_choices = array();

					foreach ( $status_options as $status_option ) {
						$final_status_choices[] = array( 'label' => $status_option['status_label'], 'value' => $status_option['status'] );
					}

					$final_status_choices[] = array( 'label' => esc_html__( 'Expired', 'gravityflow' ), 'value' => 'expired' );

					$step_settings['fields'][] = array(
						'name'           => 'expiration',
						'label'          => esc_html__( 'Expiration', 'gravityflow' ),
						'tooltip'        => esc_html__( 'Enable the expiration setting to allow this step to expire. Once expired, the entry will automatically proceed to the step configured in the Next Step setting(s) below.', 'gravityflow' ),
						'type'           => 'expiration',
						'status_choices' => $final_status_choices,
					);
				}

				foreach ( $status_options as $status_option ) {
					$setting_label = isset( $status_option['destination_setting_label'] ) ?  $status_option['destination_setting_label'] : esc_html__( 'Next step if', 'gravityflow' ) . ' ' . $status_option['status_label'];
					$default_destination = isset( $status_option['default_destination'] ) ? $status_option['default_destination'] : 'next';
					$step_settings['fields'][] = array(
						'name'          => 'destination_' . $status_option['status'],
						'label'         => $setting_label,
						'type'          => 'step_selector',
						'default_value' => $default_destination,
					);
				}
				$step_settings['dependency'] = array( 'field' => 'step_type', 'values' => array( $type ) );
				$settings[] = $step_settings;

			}

			$list_url         = remove_query_arg( 'fid' );
			$new_url          = add_query_arg( array( 'fid' => 0 ) );
			$success_feedback = sprintf( __( 'Step settings updated. %sBack to the list%s or %sAdd another step%s.', 'gravityflow' ), '<a href="' . esc_url( $list_url ) . '">', '</a>', '<a href="' . esc_url( $new_url ) . '">', '</a>' );

			$settings[] = array(
				'id'     => 'save_button',
				'fields' => array(
					array(
						'id'       => 'save_button',
						'type'     => 'save',
						'name' => 'save_button',
						'value'    => __( 'Update Step Settings', 'gravityflow' ),
						'messages' => array(
							'success' => $success_feedback,
							'error'   => __( 'There was an error while saving the step settings', 'gravityflow' ),
						),
					),
				),
			);

			/**
			 * Allows the step settings to be modified.
			 *
			 * @since 2.8.6
			 *
			 * @param array $settings The settings for a step.
			 * @param int   $current_step_id The current step ID or 0 for new step.
			 */
			$settings = apply_filters( 'gravityflow_step_settings_fields', $settings, $current_step_id );

			return $settings;
		}

		/**
		 * Display or return the markup for the feed_condition field type.
		 *
		 * @since 1.7.1-dev Added support for logic based on the entry meta.
		 *
		 * @param array $field The field properties.
		 * @param bool  $echo  Should the setting markup be echoed.
		 *
		 * @return string
		 */
		public function settings_feed_condition( $field, $echo = true ) {
			$form_id     = absint( rgget( 'id' ) );
			$step_id     = $this->get_current_feed_id();
			$entry_meta  = array_merge( $this->get_feed_condition_entry_meta( $form_id, $step_id ), $this->get_feed_condition_entry_properties() );
			$find        = 'var feedCondition';
			$replacement = sprintf( 'var entry_meta = %s; %s', json_encode( $entry_meta ), $find );

			if ( $this->is_gravityforms_supported( '2.5-beta-1' ) ) {
				$renderer  = $this->get_settings_renderer();
				$field     = new \Gravity_Forms\Gravity_Forms\Settings\Fields\Conditional_Logic( $field, $renderer );
				$base_html = $field->markup();
			} else {
				$base_html = parent::settings_feed_condition( $field, false );
			}

			$html = str_replace( $find, $replacement, $base_html );

			if ( $echo ) {
				echo $html;
			}

			return $html;
		}

		/**
		 * Ajax handler the for the feed message request.
		 */
		public function ajax_feed_message() {
			$html            = '';
			$warning         = false;
			$entry_count     = 0;
			$current_step_id = absint( rgget( 'fid' ) );

			if ( $current_step_id ) {
				$current_step = $this->get_step( $current_step_id );
				if ( ! empty( $current_step ) ) {
					$entry_count = $current_step->entry_count();
				}
			}

			if ( $entry_count > 0 ) {
				$warning = sprintf( _n( 'There is %s entry currently on this step. This entry may be affected if the settings are changed.', 'There are %s entries currently on this step. These entries may be affected if the settings are changed.', $entry_count, 'gravityflow' ), $entry_count );
			}

			if ( $warning ) {
				$html = '<div class="delete-alert alert_red"><i class="fa fa-exclamation-triangle gf_invalid"></i> ' . $warning . '</div>';
			}

			echo $html;
			die();
		}

		/**
		 * Sets the _assignee_settings_md5 class property on feed validation, if there are entries on this step.
		 *
		 * @since 2.7
		 *
		 * @param $field        \Gravity_Forms\Gravity_Forms\Settings\Fields\Base|array Gravity Forms 2.4: array, 2.5: Settings API field
		 * @param $field_setting
		 */
		public function step_type_validation_callback( $field, $field_setting ) {
			$current_step_id = $this->get_current_feed_id();
			$entry_count = 0;
			$current_step = false;
			if ( $current_step_id ) {
				$current_step = $this->get_step( $current_step_id );
				$entry_count = $current_step->entry_count();
			}

			if ( $current_step ) {
				$required_capabilities = $current_step->get_required_capabilities();
				// Checking ALL required capabilities, one by one.
				// In this way, we can also match the "gform_full_access" cap with other Gravity Form or Gravity Flow caps.
				foreach ( $required_capabilities as $cap ) {
					if ( ! $this->current_user_can_any( $cap ) ) {
						$error_message = esc_html__( "You don't have sufficient permissions to update the step settings.", 'gravityflow' );
						GFCommon::add_error_message( $error_message );
						if ( $this->is_gravityforms_supported( '2.5-beta-1' ) ) {
							$field->set_error( $error_message );
						} else {
							$this->set_field_error( $field, $error_message );
						}
						return;
					}
				}
			}

			$assignee_settings = array();

			if ( $entry_count > 0 && $current_step ) {
				$this->_assignee_settings_md5 = $current_step->assignees_hash();
			}
		}

		/**
		 * Sets the _assignee_settings_md5 class property on feed validation, if there are entries on this step.
		 * Also checks permissions.
		 *
		 *
		 * @deprecated 2.7
		 *
		 * @since 1.0
		 * @since 2.5   Add new checks for step required capabilities.
		 * @since 2.7   Unused
		 *
		 * @param array  $field         The field properties.
		 * @param string $field_setting The field value.
		 *
		 * @return bool
		 */
		public function save_feed_validation_callback( $field, $field_setting ) {

			_deprecated_function( 'save_feed_validation_callback', '2.7', 'step_type_validation_callback' );

			$current_step_id = $this->get_current_feed_id();
			$entry_count = 0;
			$current_step = false;
			if ( $current_step_id ) {
				$current_step = $this->get_step( $current_step_id );
				$entry_count = $current_step->entry_count();
			}

			if ( $current_step ) {
				$required_capabilities = $current_step->get_required_capabilities();
				// Checking ALL required capabilities, one by one.
                // In this way, we can also match the "gform_full_access" cap with other Gravity Form or Gravity Flow caps.
				foreach ( $required_capabilities as $cap ) {
					if ( ! $this->current_user_can_any( $cap ) ) {
						GFCommon::add_error_message( esc_html__( "You don't have sufficient permissions to update the step settings.", 'gravityflow' ) );

						return false;
					}
				}
			}

			if ( $entry_count > 0 && $current_step ) {
				$this->_assignee_settings_md5 = $current_step->assignees_hash();
			}

			return true;
		}

		/**
		 * Saves the feed settings. Adds the feeds for the start and complete settings if they don't already exist when the first step is added.
		 *
		 * @since 2.5
		 *
		 * @param $feed_id
		 * @param $form_id
		 * @param $settings
		 *
		 * @return int
		 */
		public function save_feed_settings( $feed_id, $form_id, $settings ) {

			// Get all feeds from the parent method. This includes the start and complete steps which are stripped from $this->get_feeds() if there are no other steps.
			$feeds = parent::get_feeds( $form_id );

			if ( empty( $feeds ) ) {
				$start_step_meta = array (
					'step_name' => __( 'Start', 'gravityflow' ),
					'description' => '',
					'step_type' => 'workflow_start',
					'feed_condition_conditional_logic' => '0',
					'feed_condition_conditional_logic_object' =>
						array (
						),
					'scheduled' => '0',
					'schedule_type' => 'delay',
					'schedule_date' => '',
					'schedule_delay_offset' => '',
					'schedule_delay_unit' => 'hours',
					'schedule_date_field_before_after' => 'after',
					'schedule_date_field_offset' => '0',
					'schedule_date_field_offset_unit' => 'hours',
					'instructionsEnable' => '0',
					'instructionsValue' => '',
					'display_fields_mode' => 'all_fields',
					'destination_complete' => 'next',
				);
				$start_step_id = $this->insert_feed( $form_id, true, $start_step_meta );
				do_action( 'gform_post_save_feed_settings', $start_step_id, $form_id, $start_step_meta, $this );

				$complete_step_meta = array (
					'step_name' => __( 'Complete', 'gravityflow' ),
					'description' => '',
					'step_type' => 'workflow_complete',
					'feed_condition_conditional_logic' => '0',
					'scheduled' => '0',
				);
				$complete_step_id = $this->insert_feed( $form_id, true, $complete_step_meta );
				do_action( 'gform_post_save_feed_settings', $complete_step_id, $form_id, $complete_step_meta, $this );

			}
			return parent::save_feed_settings( $feed_id, $form_id, $settings );
		}

		/**
		 * Updates the feed properties and triggers the assignee refresh.
		 *
		 * @param int   $id   The feed ID.
		 * @param array $meta The feed properties.
		 */
		public function update_feed_meta( $id, $meta ) {
			parent::update_feed_meta( $id, $meta );
			$results = $this->maybe_refresh_assignees();

			if ( ! empty( $results['removed'] ) || ! empty( $results['added'] ) ) {
				GFCommon::add_message( 'Assignees updated' );
			}
		}

		/**
		 * Triggers the assignees refresh of the current forms active entries, if applicable.
		 *
		 * @return array
		 */
		public function maybe_refresh_assignees() {
			$results = array(
				'removed' => array(),
				'added' => array(),
			);

			if ( ! ( rgget( 'page' ) == 'gf_edit_forms' && rgget( 'view' ) == 'settings' && rgget( 'subview' ) == 'gravityflow' ) ) {
				return $results;
			}

			$current_step_id = $this->get_current_feed_id();
			$current_step = $this->get_step( $current_step_id, null, false );
			if ( empty( $current_step ) ) {
				return $results;
			}

			$assignee_settings_md5 = $current_step->assignees_hash();
			if ( isset( $this->_assignee_settings_md5 ) && $this->_assignee_settings_md5 !== $assignee_settings_md5 ) {
				$results = $this->refresh_assignees();
			}
			return $results;
		}

		/**
		 * Refreshes the assignees for active entries for the current form.
		 *
		 * @return array
		 */
		public function refresh_assignees() {
			$results = array(
				'removed' => array(),
				'added' => array(),
			);
			$current_step_id = $this->get_current_feed_id();

			$current_step = $this->get_step( $current_step_id, null, false );

			$entry_count = $current_step->entry_count();

			if ( $entry_count == 0 ) {
				// Nothing to do.
				return $results;
			}

			$form = $this->get_current_form();


			// Avoid paging through entries from GFAPI::get_entries() by using custom query.
			$assignee_status_by_entry = $this->get_asssignee_status_by_entry( $form['id'] );

			foreach ( $assignee_status_by_entry as $entry_id => $assignee_status ) {
				$entry = GFAPI::get_entry( $entry_id );
				$step_for_entry = $this->get_step( $current_step_id, $entry );
				if ( $entry['workflow_step'] != $step_for_entry->get_id() ) {
					continue;
				}
				$updated = false;
				$current_assignees = $step_for_entry->get_assignees();
				foreach ( $current_assignees as $assignee ) {
					/* @var Gravity_Flow_Assignee $assignee */
					$assignee_key = $assignee->get_key();

					if ( ! isset( $assignee_status[ $assignee_key ] ) ) {
						// New assignee.
						$step = $this->get_step( $current_step_id, $entry );
						$assignee->update_status( 'pending' );
						$step->end_if_complete();
						$results['added'][] = $assignee;
					}
				}

				foreach ( $assignee_status as $old_assignee_key => $old_status ) {
					foreach ( $current_assignees as $assignee ) {
						$assignee_key = $assignee->get_key();
						if ( $assignee_key == $old_assignee_key ) {
							continue 2;
						}
					}
					// No longer an assignee - remove.
					$old_assignee = Gravity_Flow_Assignees::create( $old_assignee_key, $step_for_entry );
					$old_assignee->remove();
					$old_assignee->log_event( 'removed' );
					$results['removed'][] = $old_assignee;
				}

				$this->process_workflow( $form, $entry_id );
			}

			return $results;
		}

		/**
		 * Queries the database for assigned active entries for the specified form.
		 *
		 * @param int $form_id The form ID.
		 *
		 * @return array
		 */
		public function get_asssignee_status_by_entry( $form_id ) {
			global $wpdb;
			$assignee_status_by_entry = array();
			$table = Gravity_Flow_Common::get_entry_meta_table_name();
			$entry_table = Gravity_Flow_Common::get_entry_table_name();
			$entry_id_column = Gravity_Flow_Common::get_entry_id_column_name();
			$sql = $wpdb->prepare( "
			SELECT m.form_id, m.{$entry_id_column} as entry_id, m.meta_key, m.meta_value
			FROM $table m
			INNER JOIN $entry_table l
			ON l.id = m.{$entry_id_column}
			WHERE m.meta_key LIKE %s
			AND m.meta_key NOT LIKE '%%_timestamp'
			AND m.form_id=%d
			AND l.status='active'", 'workflow_user_id_%', $form_id );
			$rows = $wpdb->get_results( $sql );

			if ( ! is_wp_error( $rows ) && count( $rows ) > 0 ) {
				foreach ( $rows as $row ) {
					$user_id = str_replace( 'workflow_user_id_', '', $row->meta_key );
					if ( ! isset( $assignee_status_by_entry[ $row->entry_id ] ) ) {
						$assignee_status_by_entry[ $row->entry_id ] = array();
					}
					$assignee_status_by_entry[ $row->entry_id ][ 'user_id|' . $user_id ] = $row->meta_value;
				}
			}

			$sql = $wpdb->prepare( "
			SELECT m.form_id, m.{$entry_id_column} as entry_id, m.meta_key, m.meta_value
			FROM $table m
			INNER JOIN $entry_table l
			ON l.id = m.{$entry_id_column}
			WHERE m.meta_key LIKE %s
			AND m.meta_key NOT LIKE '%%_timestamp'
			AND m.form_id=%d
			AND l.status='active'", 'workflow_email_%', $form_id );
			$rows = $wpdb->get_results( $sql );

			if ( ! is_wp_error( $rows ) && count( $rows ) > 0 ) {
				foreach ( $rows as $row ) {
					$user_id = str_replace( 'workflow_email_', '', $row->meta_key );
					if ( ! isset( $assignee_status_by_entry[ $row->entry_id ] ) ) {
						$assignee_status_by_entry[ $row->entry_id ] = array();
					}
					$assignee_status_by_entry[ $row->entry_id ][ 'email|' . $user_id ] = $row->meta_value;
				}
			}

			$sql = $wpdb->prepare( "
			SELECT m.form_id, m.{$entry_id_column} as entry_id, m.meta_key, m.meta_value
			FROM $table m
			INNER JOIN $entry_table l
			ON l.id = m.{$entry_id_column}
			WHERE m.meta_key LIKE %s
			AND m.meta_key NOT LIKE '%%_timestamp'
			AND m.form_id=%d
			AND l.status='active'", 'workflow_role_%', $form_id );
			$rows = $wpdb->get_results( $sql );

			if ( ! is_wp_error( $rows ) && count( $rows ) > 0 ) {
				foreach ( $rows as $row ) {
					$user_id = str_replace( 'workflow_role_', '', $row->meta_key );
					if ( ! isset( $assignee_status_by_entry[ $row->entry_id ] ) ) {
						$assignee_status_by_entry[ $row->entry_id ] = array();
					}
					$assignee_status_by_entry[ $row->entry_id ][ 'role|' . $user_id ] = 'role|' . $user_id;
				}
			}

			return $assignee_status_by_entry;
		}

		/**
		 * Target for the gform_entries_field_value hook.
		 *
		 * Sets the value for the workflow_step column.
		 *
		 * @param string $value    The entry value to be filtered.
		 * @param int    $form_id  The current form ID.
		 * @param int    $field_id The current field ID.
		 * @param array  $entry    The current entry.
		 *
		 * @return string
		 */
		public function filter_gform_entries_field_value( $value, $form_id, $field_id, $entry ) {
			if ( $field_id == 'workflow_step' ) {
				if ( empty( $value ) ) {
					$value = '';
				} else {
					$step = $this->get_step( $value );
					if ( $step ) {
						$value = $step->get_name();
					}
				}
			}
			return $value;
		}

		/**
		 * Ajax handler for the request to save the custom feed order.
		 */
		public function ajax_save_feed_order() {
			if ( rgpost( 'action' ) !== 'gravityflow_save_feed_order' ) {
				return;
			}

			check_ajax_referer( 'gravityflow_feed_list', 'nonce' );

			$feed_ids = rgpost( 'feed_ids' );
			$form_id  = absint( rgpost( 'form_id' ) );
			foreach ( $feed_ids as &$feed_id ) {
				$feed_id = absint( $feed_id );
			}
			update_option( 'gravityflow_feed_order_' . $form_id, $feed_ids );

			echo json_encode( array( array( 'ok' ), 200 ) );
			die();
		}

		/**
		 * Ajax handler for the print entries request, triggers output of the selected entries.
		 */
		public function ajax_print_entries() {
			require_once( $this->get_base_path() . '/includes/pages/class-print-entries.php' );
			Gravity_Flow_Print_Entries::render();
			exit();
		}

		/**
		 * Get the feeds for the specified form and sort them if applicable.
		 *
		 * This method strips out the start and complete feeds if there are no other feeds.
		 *
		 * @param null|int $form_id Null or the form ID.
		 *
		 * @return array
		 */
		public function get_feeds( $form_id = null ) {

			$feeds = parent::get_feeds( $form_id );

			$ordered_ids = get_option( 'gravityflow_feed_order_' . $form_id );

			if ( $ordered_ids ) {
				$feeds = array_reverse( $feeds );
			}

			if ( ! empty( $ordered_ids ) ) {
				$this->step_order = $ordered_ids;

				usort( $feeds, array( $this, 'sort_feeds' ) );
			}

			$start_feed = false;

			$complete_feed = false;

			foreach ( $feeds as $key => $feed ) {
				if ( $feed['meta']['step_type'] == 'workflow_start' ) {
					$start_feed = $feed;
					unset( $feeds[ $key ] );
				} elseif ( $feed['meta']['step_type'] == 'workflow_complete' ) {
					$complete_feed = $feed;
					unset( $feeds[ $key ] );
				}
			}

			if ( ! empty( $feeds ) ) {
				if ( $start_feed ) {
					array_unshift( $feeds, $start_feed );
				}

				if ( $complete_feed ) {
					array_push( $feeds, $complete_feed );
				}
			}

			return $feeds;
		}

		/**
		 * Get the workflow steps.
		 *
		 * The virtual Workflow Complete step is not returned.
		 *
		 * @param null|int   $form_id Null or the form ID.
		 * @param null|array $entry   Null or the entry to initialize the steps for.
		 *
		 * @return Gravity_Flow_Step[]
		 */
		public function get_steps( $form_id = null, $entry = null ) {
			$feeds = $this->get_feeds( $form_id );

			$steps = array();

			foreach ( $feeds as $feed ) {
				$step = Gravity_Flow_Steps::create( $feed, $entry );
				if ( $step && $step->get_type() != 'workflow_complete' ) {
					$steps[] = $step;
				}
			}

			return $steps;
		}

		/**
		 * The usort() callback for sorting the feeds.
		 *
		 * @param array $a The first feed to compare.
		 * @param array $b The second feed to compare.
		 *
		 * @return bool|int
		 */
		public function sort_feeds( $a, $b ) {
			$order = $this->step_order;
			$a     = array_search( $a['id'], $order );
			$b     = array_search( $b['id'], $order );

			if ( $a === false && $b === false ) {
				return 0;
			} else if ( $a === false ) {
				return 1;
			} else if ( $b === false ) {
				return - 1;
			} else {
				return $a - $b;
			}
		}

		/**
		 * Returns the virtual workflow complete step or false.
		 *
		 * @since 2.5
		 *
		 * @param null|int   $form_id Null or the form ID.
		 * @param null|array $entry   Null or the entry to initialize the step for.
		 *
		 * @return false|Gravity_Flow_Step_Workflow_Complete
		 */
		public function get_workflow_complete_step( $form_id = null, $entry = null ) {
			$feeds = $this->get_feeds( $form_id );

			$workflow_complete_step = false;

			foreach ( $feeds as $feed ) {
				$step = Gravity_Flow_Steps::create( $feed, $entry );
				if ( $step && $step->get_type() == 'workflow_complete' ) {
					$workflow_complete_step = $step;
					break;
				}
			}

			return $workflow_complete_step;
		}

		/**
		 * Returns the start step or false.
		 *
		 * @since 2.5
		 *
		 * @param null|int   $form_id Null or the form ID.
		 * @param null|array $entry   Null or the entry to initialize the step for.
		 *
		 * @return false|Gravity_Flow_Step_Workflow_Start
		 */
		public function get_workflow_start_step( $form_id = null, $entry = null ) {
			$steps = $this->get_steps( $form_id, $entry );

			$start_step = false;

			if ( ! empty( $steps ) && $steps[0]->get_type() == 'workflow_start' ) {
				// The start step is always first in the list.
				$start_step = $steps[0];
			}

			return $start_step;
		}

		/**
		 * Renders and initializes a radio field or a collection of radio fields based on the $field array.
		 * Images/icons are used in place of the HTML radio buttons.
		 *
		 * @since 1.0
		 * @since 2.5.12   Change from protected to public for Gravity Forms 2.5.
		 *
		 * @param array $field Field array containing the configuration options of this field.
		 * @param bool  $echo  True to echo the output to the screen, false to simply return the contents as a string.
		 *
		 * @return string Returns the markup for the radio buttons.
		 */
		public function settings_radio_image( $field, $echo = true ) {

			$field['type'] = 'radio'; // Making sure type is set to radio.

			$settings_prefix = version_compare( GFForms::$version, '2.5-dev-1', '<' ) ? 'gaddon' : 'gform';

			$selected_value   = $this->get_setting( $field['name'], rgar( $field, 'default_value' ) );
			$field_attributes = $this->get_field_attributes( $field );
			$horizontal       = rgar( $field, 'horizontal' ) ? " {$settings_prefix}-setting-inline" : '';
			$html             = '';
			if ( is_array( $field['choices'] ) ) {
				foreach ( $field['choices'] as $i => $choice ) {
					$choice['id']      = $field['name'] . $i;
					$choice_attributes = $this->get_choice_attributes( $choice, $field_attributes );

					$tooltip = isset( $choice['tooltip'] ) ? gform_tooltip( $choice['tooltip'], rgar( $choice, 'tooltip_class' ), true ) : '';

					$radio_value = isset( $choice['value'] ) ? $choice['value'] : $choice['label'];
					$checked     = checked( $selected_value, $radio_value, false );

					$div_class = rgar( $choice, 'div_class' );
					if ( ! empty( $div_class ) ) {
						$div_class = ' ' . sanitize_html_class( $div_class );
					}

					$icon_url = rgar( $choice, 'icon_url' );

					if ( strpos( $icon_url, 'http' ) === 0 ) {
						$icon = '<img src="' . $icon_url . '"/>';
					} else {
						$icon = $icon_url;
					}

					$input_name = "_{$settings_prefix}_setting_" . esc_attr( $field['name'] );

					$html .= '
	                        <div id="' . $settings_prefix . '-setting-radio-choice-' . $choice['id'] . '" class="' . $settings_prefix . '-setting-radio' . $div_class . $horizontal . '">
	                        <input
	                                id = "' . esc_attr( $choice['id'] ) . '"
	                                type = "radio" ' .
					         'name="' . $input_name . '" ' .
					         'value="' . $radio_value . '" ' .
					         implode( ' ', $choice_attributes ) . ' ' .
					         $checked .
					         ' />
	                        <label for="' . esc_attr( $choice['id'] ) . '">
	                            <span>' . $icon . '<br />' . esc_html( $choice['label'] ) . ' ' . $tooltip . '</span>
							</label>
	                        </div>
	                    ';
				}
			}

			if ( $this->field_failed_validation( $field ) ) {
				$html .= $this->get_error_icon( $field );
			}

			if ( $echo ) {
				echo $html;
			}

			return $html;
		}

		/**
		 * Renders or returns markup for a checkbox plus container composite setting.
		 *
		 * The container will be displayed or hidden depending on the value of the checkbox field.
		 *
		 * @since 1.5.1
		 *
		 * @param array $field The field properties.
		 * @param bool  $echo  Indicates if the HTML should be echoed.
		 *
		 * @return string
		 */
		public function settings_checkbox_and_container( $field, $echo = true ) {
			$checkbox_field = rgar( $field, 'checkbox' );

			if ( empty( $checkbox_field ) ) {
				return '';
			}

			$checkbox_defaults = array(
				'type'       => 'checkbox',
				'name'       => $field['name'] . 'Enable',
				'label'      => esc_html__( 'Enable', 'gravityflow' ),
				'horizontal' => true,
				'value'      => '1',
				'choices'    => false,
				'tooltip'    => false,
			);

			$checkbox_field = wp_parse_args( $checkbox_field, $checkbox_defaults );

			if ( empty( $checkbox_field['choices'] ) ) {
				$checkbox_field['choices'] = array(
					array(
						'name'          => $checkbox_field['name'],
						'label'         => $checkbox_field['label'],
						'onchange'      => sprintf( "( function( $, elem ) {
								$( elem ).parents( 'td' ).css( 'position', 'relative' );
								if( $( elem ).prop( 'checked' ) ) {
									$( '%1\$s' ).fadeIn();
								} else {
									$( '%1\$s' ).fadeOut();
								}
							} )( jQuery, this );",
							"#{$field['name']}Container" ),
					),
				);
			}

			$field['checkbox'] = $checkbox_field;

			$checkbox_field = rgar( $field, 'checkbox' );

			$is_enabled = $this->get_setting( $checkbox_field['name'] );

			$container_settings_markup = '';

			if ( isset( $field['settings'] ) && is_array( $field['settings'] ) ) {
				foreach (  $field['settings'] as $setting ) {
					if ( ! isset( $setting['type'] ) ) {
						continue;
					}
					$method = 'settings_' . $setting['type'];
					if ( isset( $setting['before'] ) ) {
						$container_settings_markup .= rgar( $setting, 'before' );
						unset( $setting['before'] );
					}
					if ( isset( $setting['after'] ) ) {
						$after = rgar( $setting, 'after' );
						unset( $setting['after'] );
					} else {
						$after = '';
					}
					if ( method_exists( $this, $method ) ) {
						$container_settings_markup .= $this->{$method}( $setting, false );
					}
					$container_settings_markup .= isset( $setting['tooltip'] ) ? gform_tooltip( $setting['tooltip'], rgar( $setting, 'tooltip_class' ) . ' tooltip ' . $setting['name'], true ) : '';
					$container_settings_markup .= $after;
				}
			}

			$html = sprintf(
				'%s <div id="%s" class="%s">%s</div>',
				$this->settings_checkbox( $checkbox_field, false ),
				$field['name'] . 'Container',
				$is_enabled ? '' : 'hidden',
				$container_settings_markup
			);

			if ( $echo ) {
				echo $html;
			}

			return $html;
		}

		/**
		 * Renders or returns a composite setting with a checkbox and text field.
		 *
		 * The text field will be hidden or displayed depending on the value of the checkbox.
		 *
		 * @since 1.5.1 Updated to use Gravity_Flow::settings_checkbox_and_container()
		 * @since unknown
		 *
		 * @param array $field The field properties.
		 * @param bool  $echo  Indicates if the HTML should be echoed.
		 *
		 * @return string
		 */
		public function settings_checkbox_and_text( $field, $echo = true ) {
			$text_input = rgars( $field, 'text' );

			$text_field = array(
				'name'    => $field['name'] . 'Value',
				'type'    => 'text',
				'class'   => '',
				'tooltip' => false,
			);

			$text_field['class'] .= ' ' . $text_field['name'];

			$text_field = wp_parse_args( $text_input, $text_field );

			unset( $field['textarea'] );

			$field['settings'] = array( $text_field );

			return $this->settings_checkbox_and_container( $field, $echo );
		}

		/**
		 * Renders or returns a composite setting with a checkbox and text field.
		 *
		 * The text field will be hidden or displayed depending on the value of the checkbox.
		 *
		 * @since unknown
		 * @since 1.5.1 Updated to use Gravity_Flow::settings_checkbox_and_container()
		 * @since 2.6   Renamed method with legacy prefix to support Gravity Forms 2.5 Settings API.
		 *
		 * @param array $field The field properties.
		 * @param bool  $echo  Indicates if the HTML should be echoed.
		 *
		 * @return string
		 */
		public function legacy_settings_checkbox_and_textarea( $field, $echo = true ) {
			$field = $this->prepare_settings_checkbox_and_textarea( $field );

			return $this->settings_checkbox_and_container( $field, $echo );
		}

		/**
		 * Adds the textarea settings to the field properties array.
		 *
		 * @param array $field The field properties.
		 *
		 * @return array
		 */
		public function prepare_settings_checkbox_and_textarea( $field ) {
			$textarea_input = rgars( $field, 'textarea' );

			$textarea_field = array(
				'name'    => $field['name'] . 'Value',
				'type'    => 'textarea',
				'class'   => '',
				'tooltip' => false,
			);

			$textarea_field['class'] .= ' ' . $textarea_field['name'];

			$textarea_field = wp_parse_args( $textarea_input, $textarea_field );

			unset( $field['textarea'] );

			$field['settings'] = array( 'textarea' => $textarea_field );

			return $field;
		}

		/**
		 * Validate the combined checkbox and textarea setting.
		 *
		 * @since unknown
		 * @since 2.6   Renamed method with legacy prefix to support Gravity Forms 2.5 Settings API.
		 *
		 * @param array $field  The field properties.
		 * @param array $value  The setting value to be potentially saved.
		 */
		public function legacy_validate_checkbox_and_textarea_settings( $field, $value ) {

			$field = $this->prepare_settings_checkbox_and_textarea( $field );

			$checkbox_field = $field['checkbox'];
			$textarea_field = $field['settings']['textarea'];
			$settings       = $this->get_posted_settings();
			$this->validate_checkbox_settings( $checkbox_field, $settings );
			$this->validate_textarea_settings( $textarea_field, $settings );
		}

		/**
		 * Validate step_highlight composite setting.
		 *
		 * Validate the sub-settings are of appropriate type and required status.
		 *
		 * @since 1.9.2
		 * @since 2.6   Renamed from validate_step_highlight_settings to support Gravity Forms 2.5
		 *
		 * @param array $field    The field properties.
		 * @param array $settings The settings to be potentially saved.
		 */
		public function validate_highlight_settings( $field, $settings ) {

			if ( ! $this->is_gravityforms_supported( '2.5-beta-1' ) ) {
				$checkbox_field = $field->settings_fields['step_highlight'];
				$this->validate_checkbox_settings( $checkbox_field, $settings );
				$color_field = $field->settings_fields['step_highlight_color'];
				$this->validate_text_settings( $color_field, $settings );
				$this->validate_step_highlight_color_settings( $color_field, $settings );
				return;
			}

			$checkbox_field = $field->settings_fields['step_highlight'];
			$renderer  = $this->get_settings_renderer();
			$cb_field     = new \Gravity_Forms\Gravity_Forms\Settings\Fields\Checkbox( $checkbox_field, $renderer );
			$cb_field->do_validation( $settings[ 'step_highlight'] );

			$color_field = $field->settings_fields['step_highlight_color'];
			$text_field     = new \Gravity_Forms\Gravity_Forms\Settings\Fields\Text( $color_field, $renderer );
			$text_field->do_validation( $settings[ 'step_highlight_color'] );
			$this->validate_step_highlight_color_settings( $color_field, $settings );
		}

		/**
		 * Validate step_highlight_color is a hexadecimal code.
		 *
		 * @since 1.9.2
		 *
		 * @param array $field    The field properties.
		 * @param array $settings The settings to be potentially saved.
		 */
		public function validate_step_highlight_color_settings( $field, $settings ) {

			if( $settings['step_highlight'] && ! preg_match( '/^#[a-f0-9]{6}$/i', $settings['step_highlight_color'] ) ) {
				$this->set_field_error( $field, __( 'You must provide a color value for the active highlight to apply.', 'gravityflow' ) );
			}

		}

		/**
		 * Renders the HTML for the step selector setting.
		 *
		 * @since 1.0
		 * @since 2.5.12 Added the $echo param.
		 *
		 * @param array $field The field properties.
		 * @param bool  $echo  Whether to output the setting.
		 *
		 * @return string
		 */
		public function settings_step_selector( $field, $echo = true ) {
			$form    = $this->get_current_form();
			$feed_id = $this->get_current_feed_id();
			$form_id = absint( $form['id'] );
			$steps   = $this->get_steps( $form_id );

			$step_choices   = array();
			$step_choices[] = array( 'label' => esc_html__( 'Workflow Complete', 'gravityflow' ), 'value' => 'complete' );
			$step_choices[] = array( 'label' => esc_html__( 'Next step in list', 'gravityflow' ), 'value' => 'next' );
			foreach ( $steps as $i => $step ) {
				$step_id = $step->get_id();
				if ( $feed_id != $step_id ) {
					$step_choices[] = array( 'label' => $step->get_name(), 'value' => $step_id );
				}
			}

			$step_selector_field = array(
				'name'          => $field['name'],
				'label'         => $field['label'],
				'type'          => 'select',
				'default_value' => isset( $field['default_value'] ) ? $field['default_value'] : 'next',
				'horizontal'    => true,
				'choices'       => $step_choices,
			);

			$html = $this->settings_select( $step_selector_field, false );

			if ( $echo ) {
				echo $html;
			}
			return $html;
		}

		/**
		 * Adds columns to the list of feeds.
		 *
		 * Setting name => label.
		 *
		 * @return array
		 */
		public function feed_list_columns() {
			$columns = array(
				'step_name'      => __( 'Step name', 'gravityflow' ),
				'step_highlight' => '',
				'step_type'      => esc_html__( 'Step Type', 'gravityflow' ),
			);

			$count_entries = apply_filters( 'gravityflow_entry_count_step_list', true );
			if ( $count_entries ) {
				$columns['entry_count'] = esc_html__( 'Entries', 'gravityflow' );
			}
			return $columns;
		}

		/**
		 * Returns the value to be displayed in the step type column of the feeds list.
		 *
		 * @param array $item The current feed.
		 *
		 * @return string
		 */
		public function get_column_value_step_type( $item ) {
			$step       = $this->get_step( $item['id'] );
			$step_label = empty( $step ) ? $item['meta']['step_type'] : $step->get_label();

			if ( empty( $step ) || ! $step->is_supported() ) {

				return '<span><i class="fa fa-exclamation-triangle gf_invalid"></i> ' . $step_label . '  ' . esc_html__( '(missing)', 'gravityflow' ) . '</span>';
			}

			$icon_url  = $step->get_icon_url();
			$icon_html = ( strpos( $icon_url, 'http' ) === 0 ) ? sprintf( '<img src="%s" style="width:20px;height:20px;margin-right:5px;vertical-align:middle;"/>', $icon_url ) : sprintf( '<span style="width:20px;height:20px;margin-right:5px;vertical-align:middle;">%s</span>', $icon_url );

			return $icon_html . $step_label;
		}

		/**
		 * Returns the value to be displayed in the entry count column of the feeds list.
		 *
		 * @param array $item The current feed.
		 *
		 * @return string
		 */
		public function get_column_value_entry_count( $item ) {
			$count_entries = apply_filters( 'gravityflow_entry_count_step_list', true );
			if ( ! $count_entries ) {
				return '';
			}
			$form_id = rgget( 'id' );
			$form_id = absint( $form_id );
			$step = $this->get_step( $item['id'] );

			if ( ! $step ) {
				return '';
			}

			if ( $step->get_type() == "workflow_start" ) {
				// Display the count only if there are scheduled entries.
				$count = $step->entry_count();
				return $count = $count ? $count : '';
			}

			if ( $step->get_type() == "workflow_complete" ) {
				// There's currently no way to filter accurately on Gravity Forms entry list.
				return '';
			}

			$step_id = $step ? $step->get_id() : 0;
			$count = $step ? $step->entry_count() : 0;
			$url = admin_url( 'admin.php?page=gf_entries&view=entries&id='. $form_id . '&field_id=workflow_step&operator=is&s=' . $step_id );
			$link = sprintf( '<a href="%s">%d</a>', $url, $count );
			return $link;
		}

		/**
		 * Return value of step_highlight composite setting for display on feed list.
		 *
		 * @since 1.9.2
		 *
		 * @param array $item Current workflow step.
		 *
		 * @return string
		 */
		public function get_column_value_step_highlight( $item ) {
			$step_highlight = '';

			if ( ! empty( $item['meta']['step_highlight'] ) ) {
				switch ( $item['meta']['step_highlight_type'] ) :

					case 'color':
						if ( preg_match( '/^#[a-f0-9]{6}$/i', $item['meta']['step_highlight_color'] ) ) {
							$step_highlight = '<div class="step_highlight step_highlight_color" style="background-color: ' . $item['meta']['step_highlight_color'] . ';">&nbsp;</div>';
						}
						break;

					case 'text':
						$step_highlight = '<div class="step_highlight step_highlight_text">' . $item['meta']['step_highlight_text'] . '</div>';
						break;

					case 'icon':
						$step_highlight = $item['meta']['step_highlight_icon'];
						break;

				endswitch;
			}

			return $step_highlight;
		}

		/**
		 * Returns the array of links to be displayed when mouseover a step.
		 *
		 * @return array
		 */
		public function get_action_links() {
			$feed_id       = '_id_';
			$edit_url      = add_query_arg( array( 'fid' => $feed_id ) );
			$links         = array(
				'edit'      => '<a href="' . esc_url( $edit_url ) . '">' . esc_html__( 'Edit', 'gravityforms' ) . '</a>',
				'duplicate' => '<a href="#" onclick="gaddon.duplicateFeed(\'' . esc_js( $feed_id ) . '\');" onkeypress="gaddon.duplicateFeed(\'' . esc_js( $feed_id ) . '\');">' . esc_html__( 'Duplicate', 'gravityforms' ) . '</a>',
				'delete'    => '<a class="submitdelete" onclick="javascript: if(confirm(\'' . esc_js( __( 'WARNING: You are about to delete this item.', 'gravityforms' ) ) . esc_js( __( "'Cancel' to stop, 'OK' to delete.", 'gravityforms' ) ) . '\')){ gaddon.deleteFeed(\'' . esc_js( $feed_id ) . '\'); }" onkeypress="javascript: if(confirm(\'' . esc_js( __( 'WARNING: You are about to delete this item.', 'gravityforms' ) ) . esc_js( __( "'Cancel' to stop, 'OK' to delete.", 'gravityforms' ) ) . '\')){ gaddon.deleteFeed(\'' . esc_js( $feed_id ) . '\'); }" style="cursor:pointer;">' . esc_html__( 'Delete', 'gravityforms' ) . '</a>',
				'step_id'   => 'ID: ' . $feed_id,
			);

			return $links;
		}


		/**
		 * Returns the message to be displayed in the feeds list when no steps have been configured for the form.
		 *
		 * @return string
		 */
		public function feed_list_no_item_message() {
			$url = add_query_arg( array( 'fid' => 0 ) );
			return sprintf( __( "You don't have any steps configured. Let's go %screate one%s!", 'gravityflow' ), "<a href='" . esc_url( $url ) . "'>", '</a>' );
		}

		/**
		 * Entry meta data is custom data that's stored and retrieved along with the entry object.
		 * For example, entry meta data may contain the results of a calculation made at the time of the entry submission.
		 *
		 * To add entry meta override the get_entry_meta() function and return an associative array with the following keys:
		 *
		 * label
		 * - (string) The label for the entry meta
		 * is_numeric
		 * - (boolean) Used for sorting
		 * is_default_column
		 * - (boolean) Default columns appear in the entry list by default. Otherwise the user has to edit the columns and select the entry meta from the list.
		 * update_entry_meta_callback
		 * - (string | array) The function that should be called when updating this entry meta value
		 * filter
		 * - (array) An array containing the configuration for the filter used on the results pages, the entry list search and export entries page.
		 *           The array should contain one element: operators. e.g. 'operators' => array('is', 'isnot', '>', '<')
		 *
		 * @param array $entry_meta An array of entry meta already registered with the gform_entry_meta filter.
		 * @param int   $form_id    The Form ID.
		 *
		 * @return array The filtered entry meta array.
		 */
		public function get_entry_meta( $entry_meta, $form_id ) {
			$steps        = $this->get_steps( $form_id );
			$step_choices = $workflow_final_status_options = array();

			foreach ( $steps as $step ) {
				if (  empty( $step ) || ! $step->is_active() ) {
					continue;
				}

				$status_choices = array();
				$step_id        = $step->get_id();
				$step_name      = $step->get_name();
				$step_choices[] = array( 'value' => $step_id, 'text' => $step_name );

				$step_status_options = $step->get_status_config();
				foreach ( $step_status_options as $status_option ) {
					$status_choices[] = array(
						'value' => $status_option['status'],
						'text'  => $this->translate_status_label( $status_option['status'] ),
					);
				}

				$entry_meta = array_merge( $entry_meta, $step->get_entry_meta( $entry_meta, $form_id ) );

				$entry_meta[ 'workflow_step_status_' . $step_id ] = array(
					'label'             => __( 'Status:', 'gravityflow' ) . ' ' . $step_name,
					'is_numeric'        => false,
					'is_default_column' => false, // This column will not be displayed by default on the entry list.
					'filter'            => array(
						'operators' => array( 'is', 'isnot' ),
						'choices'   => $status_choices,
					),
				);

				$workflow_final_status_options = array_merge( $workflow_final_status_options, $status_choices );

			}

			if ( ! empty( $steps ) ) {
				$workflow_final_status_options[] = array(
					'value' => 'pending',
					'text'  => $this->translate_status_label( 'pending' ),
				);

				$workflow_final_status_options[] = array(
					'value' => 'complete',
					'text'  => $this->translate_status_label( 'complete' ),
				);

				$workflow_final_status_options[] = array(
					'value' => 'cancelled',
					'text' => $this->translate_status_label( 'cancelled' ),
				);

				// Remove duplicates.
				$workflow_final_status_options = array_map( 'unserialize', array_unique( array_map( 'serialize', $workflow_final_status_options ) ) );

				$workflow_final_status_options = array_values( $workflow_final_status_options );

				$entry_meta['workflow_final_status'] = array(
					'label'                      => 'Final Status',
					'is_numeric'                 => false,
					'update_entry_meta_callback' => array( $this, 'callback_update_entry_meta_workflow_final_status' ),
					'is_default_column'          => true, // This column will be displayed by default on the entry list.
					'filter'                     => array(
						'operators' => array( 'is', 'isnot' ),
						'choices'   => $workflow_final_status_options,
					),
				);

				$entry_meta['workflow_step'] = array(
					'label'                      => 'Workflow Step',
					'is_numeric'                 => false,
					'update_entry_meta_callback' => array( $this, 'callback_update_entry_meta_workflow_step' ),
					'is_default_column'          => true, // This column will be displayed by default on the entry list.
					'filter'                     => array(
						'operators' => array( 'is', 'isnot' ),
						'choices'   => $step_choices,
					),
				);

				$entry_meta['workflow_timestamp'] = array(
					'label'                      => 'Timestamp',
					'is_numeric'                 => true,
					'update_entry_meta_callback' => array( $this, 'callback_update_entry_meta_timestamp' ),
					'is_default_column'          => false, // This column will not be displayed by default on the entry list.
				);
			}

			return $entry_meta;
		}

		/**
		 * The target of callback_update_entry_meta_workflow_step.
		 *
		 * @param string $key   The entry meta key.
		 * @param array  $entry The Entry Object.
		 * @param array  $form  The Form Object.
		 *
		 * @return string|void
		 */
		public function callback_update_entry_meta_workflow_step( $key, $entry, $form ) {

			if ( ! isset( $entry['id'] ) ) {
				return;
			}

			if ( isset( $entry['workflow_final_status'] ) && $entry['workflow_final_status'] != 'pending' && isset( $entry['workflow_step'] ) ) {
				return $entry['workflow_step'];
			}

			if ( isset( $entry['workflow_step'] )  && $entry[ $key ] !== false ) {
				return $entry['workflow_step'];
			} else {
				return 0;
			}
		}

		/**
		 * The target of callback_update_entry_meta_workflow_current_status.
		 *
		 * @param string $key   The entry meta key.
		 * @param array  $entry The Entry Object.
		 * @param array  $form  The Form Object.
		 *
		 * @return string|void
		 */
		public function callback_update_entry_meta_workflow_current_status( $key, $entry, $form ) {

			if ( ! isset( $entry['id'] ) ) {
				return;
			}

			if ( isset( $entry['workflow_current_status'] ) && $entry['workflow_current_status'] != 'pending' && $entry[ $key ] !== false ) {
				return $entry['workflow_current_status'];
			} else {
				return 'pending';
			}
		}

		/**
		 * The target of callback_update_entry_meta_workflow_final_status.
		 *
		 * @param string $key   The entry meta key.
		 * @param array  $entry The Entry Object.
		 * @param array  $form  The Form Object.
		 *
		 * @return string|void
		 */
		public function callback_update_entry_meta_workflow_final_status( $key, $entry, $form ) {

			if ( ! isset( $entry['id'] ) ) {
				return;
			}

			if ( isset( $entry['workflow_final_status'] ) && $entry['workflow_final_status'] != 'pending' && $entry[ $key ] !== false ) {
				return $entry['workflow_final_status'];
			} else {
				return 'pending';
			}
		}

		/**
		 * The target of update_entry_meta_callback.
		 *
		 * @param string $key   The entry meta key.
		 * @param array  $entry The Entry Object.
		 * @param array  $form  The Form Object.
		 *
		 * @return string|void
		 */
		public function callback_update_entry_meta_timestamp( $key, $entry, $form ) {
			if ( ! isset( $entry['id'] ) ) {
				return;
			}
			return ! isset( $entry['workflow_timestamp'] ) ? strtotime( $entry['date_created'] ) : time();
		}

		/**
		 * Displays the workflow info on the entry detail page, if enabled.
		 *
		 * @param array                  $form         The current form.
		 * @param array                  $entry        The current step.
		 * @param null|Gravity_Flow_Step $current_step Null or the current step.
		 * @param array                  $args         The page arguments.
		 */
		public function workflow_entry_detail_status_box( $form, $entry, $current_step = null, $args = array() ) {

			if ( is_null( $current_step ) ) {
				$current_step = $this->get_current_step( $form, $entry );
			}

			$display_workflow_info = (bool) $args['workflow_info'];

			$step_status = (bool) $args['step_status'];

			$current_user_is_assignee = false;

			if ( $current_step && ! $display_workflow_info && ! $step_status ) {
				$current_user_assignee_key = $current_step->get_current_assignee_key();
				if ( $current_user_assignee_key ) {
					$assignee                 = $current_step->get_assignee( $current_user_assignee_key );
					$current_user_is_assignee = $assignee->is_current_user();
				}
			}

			if ( $current_user_is_assignee || $display_workflow_info || ( $current_step && $step_status ) ) {
				?>
				<div id="gravityflow-status-box-container" class="postbox">

					<h3 class="hndle" style="cursor:default;">
						<span><?php
							if ( $display_workflow_info ) {
								echo esc_html( $this->translate_navigation_label( 'workflow' ) );
							}
							?></span>
					</h3>

					<div id="submitcomment" class="submitbox">
						<div id="minor-publishing" class="gravityflow-status-box">
							<?php

							$this->maybe_display_entry_detail_workflow_info( $current_step, $form, $entry, $args );
							$this->maybe_display_entry_detail_step_status( $current_step, $form, $entry, $args );

							?>
						</div>

					</div>

				</div>
				<?php
			}

			do_action( 'gravityflow_workflow_detail_sidebar', $form, $entry, $current_step, $args );

			$this->maybe_display_entry_detail_admin_actions( $current_step, $form, $entry );
		}

		/**
		 * Displays the workflow info on the entry detail page, if enabled.
		 *
		 * @param Gravity_Flow_Step $current_step The current step for this entry.
		 * @param array             $form         The form which created this entry.
		 * @param array             $entry        The entry currently being displayed.
		 * @param array             $args         The properties for the page currently being displayed.
		 */
		public function maybe_display_entry_detail_workflow_info( $current_step, $form, $entry, $args ) {
			$display_workflow_info = (bool) $args['workflow_info'];

			if ( ! $display_workflow_info ) {
				return;
			}

			$entry_id      = absint( $entry['id'] );
			$entry_id_link = $entry_id;

			if ( GFAPI::current_user_can_any( 'gravityforms_view_entries' ) ) {
				$entry_id_link = '<a href="' . admin_url( 'admin.php?page=gf_entries&view=entry&id=' . absint( $form['id'] ) . '&lid=' . absint( $entry['id'] ) ) . '">' . $entry_id . '</a>';
			}

			printf( '<div class="gravityflow-status-box-field gravityflow-status-box-field-entry-id"><span class="gravityflow-status-box-field-label">%s: </span><span class="gravityflow-status-box-field-value">%s</span></div>', esc_html__( 'Entry ID', 'gravityflow' ), $entry_id_link );

			/**
			 * Allows the format for dates within the entry detail workflow info box to be modified.
			 *
			 * @param string $date_format A date format string - defaults to the date format setting in the WordPress general settings.
			 */
			$date_format = apply_filters( 'gravityflow_date_format_entry_detail', '' );
			$date_created = Gravity_Flow_Common::format_date( $entry['date_created'], $date_format, false, true );
			printf( '<div class="gravityflow-status-box-field gravityflow-status-box-field-submitted-time"><span class="gravityflow-status-box-field-label">%s: </span><span class="gravityflow-status-box-field-value">%s</span></div>', esc_html__( 'Submitted', 'gravityflow' ), esc_html( $date_created ) );

			if ( ! empty( $entry['workflow_timestamp'] ) ) {
				$last_updated = Gravity_Flow_Common::format_date( $entry['workflow_timestamp'], $date_format, false, true );
				if ( $date_created != $last_updated ) {
					printf( '<div class="gravityflow-status-box-field gravityflow-status-box-field-last-updated"><span class="gravityflow-status-box-field-label">%s: </span><span class="gravityflow-status-box-field-value">%s</span></div>', esc_html__( 'Last updated', 'gravityflow' ), esc_html( $last_updated ) );
				}
			}

			if ( ! empty( $entry['created_by'] ) && $usermeta = get_userdata( $entry['created_by'] ) ) {
				printf( '<div class="gravityflow-status-box-field gravityflow-status-box-field-submitted"><span class="gravityflow-status-box-field-label">%s: </span><span class="gravityflow-status-box-field-value">%s</span></div>', esc_html__( 'Submitted by', 'gravityflow' ), esc_html( $usermeta->display_name ) );
			}

			$workflow_status = gform_get_meta( $entry['id'], 'workflow_final_status' );

			if ( ! empty( $workflow_status ) ) {
				$workflow_status_label = $this->translate_status_label( $workflow_status );
				printf( '<div class="gravityflow-status-box-field gravityflow-status-box-field-status"><span class="gravityflow-status-box-field-label">%s: </span><span class="gravityflow-status-box-field-value">%s</span></div>', esc_html__( 'Status', 'gravityflow' ), $workflow_status_label );
			}

			if ( false !== $current_step && $current_step instanceof Gravity_Flow_Step
			     && $current_step->supports_due_date() && $current_step->due_date
			) {
				$gflow_due_date_date = Gravity_Flow_Common::format_date( $current_step->get_due_date_timestamp(), $date_format, false, false );
				printf( '<div class="gravityflow-status-box-field gravityflow-status-box-field-due-date"><span class="gravityflow-status-box-field-label">%s: </span><span class="gravityflow-status-box-field-value">%s</span></div>', esc_html__( 'Due Date', 'gravityflow' ), $gflow_due_date_date );
			}

			if ( false !== $current_step && $current_step instanceof Gravity_Flow_Step
			     && $current_step->supports_expiration() && $current_step->expiration
			) {
				$glfow_date = Gravity_Flow_Common::format_date( $current_step->get_expiration_timestamp(), $date_format, false, true );
				printf( '<div class="gravityflow-status-box-field gravityflow-status-box-field-expires"><span class="gravityflow-status-box-field-label">%s: </span><span class="gravityflow-status-box-field-value">%s</span></div>', esc_html__( 'Expires', 'gravityflow' ), $glfow_date );
			}

			/**
			 * Allows content to be added in the workflow box below the workflow status info.
			 *
			 * @param array             $form         The form which created this entry.
			 * @param array             $entry        The entry currently being displayed.
			 * @param Gravity_Flow_Step $current_step The current step for this entry.
			 */
			do_action( 'gravityflow_below_workflow_info_entry_detail', $form, $entry, $current_step );
		}

		/**
		 * Displays the step status on the entry detail page.
		 *
		 * @param Gravity_Flow_Step $current_step The current step for this entry.
		 * @param array             $form         The form which created this entry.
		 * @param array             $entry        The entry currently being displayed.
		 * @param array             $args         The properties for the page currently being displayed.
		 */
		public function maybe_display_entry_detail_step_status( $current_step, $form, $entry, $args ) {
			if ( false !== $current_step && $current_step instanceof Gravity_Flow_Step ) {
				$display_workflow_info = (bool) $args['workflow_info'];

				if ( $display_workflow_info ) {
					echo '<hr style="margin-top:10px;"/>';
				}

				if ( $current_step->is_queued() ) {
					$this->display_queued_step_details( $current_step );
				} elseif ( $current_step->is_expired() ) {
					$entry_id = absint( $entry['id'] );
					$this->display_expired_step_details( $current_step, $form, $entry_id );
				} else {
					$current_step->workflow_detail_box( $form, $args );
				}
			}
		}

		/**
		 * Display the details for the queued step.
		 *
		 * @param Gravity_Flow_Step $current_step The current step for this entry.
		 */
		public function display_queued_step_details( $current_step ) {
			printf( '<div class="gravityflow-status-box-field gravityflow-status-box-field-step-name"><h4><span class="gravityflow-status-box-field-label">%s </span><span class="gravityflow-status-box-field-value">(%s)</span></h4></div>', $current_step->get_name(), esc_html__( 'Queued', 'gravityflow' ) );

			$scheduled_timestamp = $current_step->get_schedule_timestamp();

			switch ( $current_step->schedule_type ) {
				case 'date':
					$scheduled_date = $current_step->schedule_date;
					break;
				case 'date_field':
					$scheduled_date_str = date( 'Y-m-d H:i:s', $scheduled_timestamp );
					$scheduled_date     = get_date_from_gmt( $scheduled_date_str );
					break;
				case 'delay':
				default:
					$scheduled_date_str = date( 'Y-m-d H:i:s', $scheduled_timestamp );
					$scheduled_date     = get_date_from_gmt( $scheduled_date_str );
			}

			printf( '<div class="gravityflow-status-box-field gravityflow-status-box-field-scheduled-date"><h4><span class="gravityflow-status-box-field-label">%s: </span><span class="gravityflow-status-box-field-value">%s<span></h4></div>', esc_html__( 'Scheduled', 'gravityflow' ), $scheduled_date );
		}

		/**
		 * Display the details for the expired step.
		 *
		 * @param Gravity_Flow_Step $current_step The current step for this entry.
		 * @param array             $form         The form which created this entry.
		 * @param integer           $entry_id     The ID of the current entry.
		 */
		public function display_expired_step_details( $current_step, $form, $entry_id ) {
			$current_step->log_event( esc_html__( 'Step expired', 'gravityflow' ) );
			$note = esc_html__( 'Step expired', 'gravityflow' ) . ': ' . $current_step->get_name();
			$current_step->add_note( $note );
			$this->process_workflow( $form, $entry_id );
			$current_step = null;
			printf( '<div class="gravityflow-status-box-field gravityflow-status-box-field-expired-step"><h4>%s</h4></div>', esc_html__( 'Expired: refresh the page', 'gravityflow' ) );
		}

		/**
		 * Displays the admin actions drop down on the entry detail page, if applicable.
		 *
		 * @param Gravity_Flow_Step $current_step The current step for this entry.
		 * @param array             $form         The form which created this entry.
		 * @param array             $entry        The entry currently being displayed.
		 */
		public function maybe_display_entry_detail_admin_actions( $current_step, $form, $entry ) {
			$steps = $this->get_steps( $form['id'] );

			if ( GFAPI::current_user_can_any( 'gravityflow_workflow_detail_admin_actions' ) && ! empty( $steps ) ) {
				?>
				<div class="postbox">
					<h3 class="hndle" style="cursor:default;">
						<span><?php esc_html_e( 'Admin', 'gravityflow' ); ?></span>
					</h3>

					<div id="submitcomment" class="submitbox">
						<div id="minor-publishing" style="padding:10px;">
							<?php wp_nonce_field( 'gravityflow_admin_action', '_gravityflow_admin_action_nonce' ); ?>
							<select id="gravityflow-admin-action" name="gravityflow_admin_action">
								<option value=""><?php esc_html_e( 'Select an action', 'gravityflow' ); ?></option>
								<?php echo $this->get_admin_action_select_options( $current_step, $steps, $form, $entry ); ?>
							</select>
							<input type="submit" class="button " name="_gravityflow_admin_action" value="<?php esc_html_e( 'Apply', 'gravityflow' ); ?>"/>

						</div>
					</div>
				</div>
				<?php
			}
		}

		/**
		 * Prepares a string containing the HTML options and optgroups for the admin actions drop down.
		 *
		 * @param bool|Gravity_Flow_Step $current_step The current step.
		 * @param Gravity_Flow_Step[]    $steps        The steps for this form.
		 * @param array                  $form         The current form.
		 * @param array                  $entry        The current entry.
		 *
		 * @return string
		 */
		public function get_admin_action_select_options( $current_step, $steps, $form, $entry ) {

			if ( $current_step ) {
				$admin_actions = array(
					array(
						'label' => esc_html__( 'Cancel Workflow', 'gravityflow' ),
						'value' => 'cancel_workflow',
					),
					array(
						'label' => esc_html__( 'Restart this step', 'gravityflow' ),
						'value' => 'restart_step',
					),
				);
			} else {
				$admin_actions = array();
			}

			$admin_actions[] = array(
				'label' => esc_html__( 'Restart Workflow', 'gravityflow' ),
				'value' => 'restart_workflow',
			);

			if ( count( $steps ) > 1 ) {
				$choices = array();
				foreach ( $steps as $step ) {

					if ( ! $step->is_active() ) {
						continue;
					}

					$step_id = $step->get_id();
					if ( ! $current_step || ( $current_step && $current_step->get_id() != $step_id ) ) {
						$choices[] = array(
							'label' => $step->get_name(),
							'value' => 'send_to_step|' . $step->get_id(),
						);
					}
				}

				if ( ! empty( $choices ) ) {
					$admin_actions[] = array(
						'label'   => esc_html__( 'Send to step:', 'gravityflow' ),
						'choices' => $choices,
					);
				}
			}

			/**
			 * Filter the choices which appear in the admin actions drop down.
			 *
			 * @param array                  $admin_actions Contains the properties for the options and optgroups.
			 * @param bool|Gravity_Flow_Step $current_step  The current step.
			 * @param Gravity_Flow_Step[]    $steps         The steps for this form.
			 * @param array                  $form          The current form.
			 * @param array                  $entry         The current entry,
			 */
			$admin_actions = apply_filters( 'gravityflow_admin_actions_workflow_detail', $admin_actions, $current_step, $steps, $form, $entry );

			return $this->get_select_options( $admin_actions, '' );
		}

		/**
		 * Displays the entry detail status box, if appropriate.
		 *
		 * @param array $form  The current form.
		 * @param array $entry The current entry.
		 */
		public function entry_detail_status_box( $form, $entry ) {

			if ( ! isset( $entry['workflow_final_status'] ) ) {
				return;
			}

			$current_step = $this->get_current_step( $form, $entry );

			?>
			<div class="postbox">
				<h3><?php echo esc_html( $this->translate_navigation_label( 'workflow' ) ); ?></h3>
				<?php
				if ( $current_step == false ) {
					?>
					<h4 style="padding:10px;"><?php esc_html_e( 'Workflow complete', 'gravityflow' ); ?></h4>

					<?php

				} else {

					$current_step->entry_detail_status_box( $form );
				}
				?>
				<div style="padding:10px;">
					<a href="<?php echo admin_url( 'admin.php?page=gravityflow-inbox&view=entry&id=' . absint( $form['id'] ) . '&lid=' . absint( $entry['id'] ) ); ?>" ><?php esc_html_e( 'View' ); ?></a>
				</div>

			</div>
			<?php
		}

		/**
		 * Returns the current step object for the supplied form and entry.
		 *
		 * @param array $form  The current form.
		 * @param array $entry The current entry.
		 *
		 * @return bool|Gravity_Flow_Step
		 */
		public function get_current_step( $form, $entry ) {

			if ( ! isset( $entry['workflow_step'] ) ) {
				return false;
			}

			if ( $entry['workflow_step'] === 0 ) {
				$step = $this->get_first_step( $form['id'], $entry );
			} else {
				$step = $this->get_step( $entry['workflow_step'], $entry );
			}

			return $step;
		}

		/**
		 * Returns the next step for the supplied entry.
		 *
		 * @param Gravity_Flow_Step $step The current step.
		 * @param array             $entry The current entry.
		 * @param array             $form  The current form.
		 *
		 * @return bool|Gravity_Flow_Step
		 */
		public function get_next_step( $step, $entry, $form ) {
			$current_step = $step;
			$keep_looking = true;

			$form_id = absint( $form['id'] );
			$steps   = $this->get_steps( $form_id, $entry );

			while ( $keep_looking && $step ) {

				if ( ! $step instanceof Gravity_Flow_Step ) {
					$next_step_id = $step = false;
				} else {
					$next_step_id = $step->get_next_step_id();
				}

				if ( $next_step_id == 'complete' ) {
					$step = false;
					$keep_looking = false;
				} elseif ( $next_step_id == 'next' ) {
					$step = $this->get_next_step_in_list( $form, $step, $entry, $steps );
					$keep_looking = false;
				} else {
					$step = $this->get_step( $next_step_id, $entry );

					if ( empty( $step ) ) {
						$keep_looking = false;
					} elseif ( $step->get_type() == 'workflow_start' ) {
						if ( $step->is_condition_met( $form ) ) {
							$keep_looking = false;
						} else {
							$step = false;
						}
					} elseif ( ! $step->is_active() || ! $step->is_condition_met( $form ) ) {
						$step = $this->get_next_step_in_list( $form, $step, $entry, $steps );
						if ( ! empty( $step ) ) {
							$keep_looking = false;
						}
					} else {
						$keep_looking = false;
					}
				}
			}

			/**
			 * Allows the next step in workflow to be customized.
			 *
			 * Return the next step (or false)
			 *
			 * @since 2.4.3
			 *
			 * @param Gravity_Flow_Step|bool $step         The next step.
			 * @param Gravity_Flow_Step      $current_step The current step.
			 * @param array                  $entry        The current entry array.
			 * @param array                  $form         The current form array.
			 * @param array                  $steps        The steps for current form.
			 */
			$step = apply_filters( 'gravityflow_next_step', $step, $current_step, $entry, $steps );

			return $step;
		}

		/**
		 * Initializes and returns the step object for the supplied step id and optional entry.
		 *
		 * @param int        $step_id The step ID.
		 * @param null|array $entry   Null or the current entry.
		 * @param bool       $cache   Check whether to use feeds cache.
		 *
		 * @return bool|Gravity_Flow_Step
		 */
		public function get_step( $step_id, $entry = null, $cache = true ) {
			$feed = isset( self::$found_feeds[ $step_id ] ) && $cache ? self::$found_feeds[ $step_id ] : $this->get_feed( $step_id );

			if ( ! $feed ) {
				return false;
			}

			$step = Gravity_Flow_Steps::create( $feed, $entry );

			self::$found_feeds[ $step_id ] = $feed;

			return $step;
		}

		/**
		 * Returns the next step in the list. FALSE if there isn't a next step.
		 *
		 * @param array               $form         The current form.
		 * @param Gravity_Flow_Step   $current_step The current step.
		 * @param array               $entry        The current entry.
		 * @param Gravity_Flow_Step[] $steps        The steps for the current form. Optional.
		 *
		 * @return bool|Gravity_Flow_Step
		 */
		public function get_next_step_in_list( $form, $current_step, $entry, $steps = array() ) {
			$form_id = absint( $form['id'] );
			if ( empty( $steps ) ) {
				$steps = $this->get_steps( $form_id, $entry );
			}
			$current_step_id = $current_step->get_id();
			$next_step = false;
			foreach ( $steps as $step ) {
				if ( $next_step ) {

					if ( $step->is_active() && $step->is_condition_met( $form ) ) {
						return $step;
					}
				}
				if ( $next_step == false && $current_step_id == $step->get_id() ) {
					$next_step = true;
				}
			}
			return false;
		}

		/**
		 * Returns an array of pages to appear in the app menu.
		 *
		 * @return array
		 */
		public function get_app_menu_items() {
			$menu_items = array();

			$inbox_item = array(
				'name' => 'gravityflow-inbox',
				'label' => esc_html( $this->translate_navigation_label( 'inbox' ) ),
				'permission' => 'gravityflow_inbox',
				'callback' => array( $this, 'inbox' ),
			);
			$menu_items[] = $inbox_item;

			$form_ids = $this->get_published_form_ids();

			if ( ! empty( $form_ids ) ) {
				$menu_item = array(
					'name' => 'gravityflow-submit',
					'label' => esc_html( $this->translate_navigation_label( 'submit' ) ),
					'permission' => 'gravityflow_submit',
					'callback' => array( $this, 'submit' ),
				);
				$menu_items[] = $menu_item;
			}

			$status_item = array(
				'name' => 'gravityflow-status',
				'label' => esc_html( $this->translate_navigation_label( 'status' ) ),
				'permission' => 'gravityflow_status',
				'callback' => array( $this, 'status' ),
			);
			$menu_items[] = $status_item;

			$support_item = array(
				'name' => 'gravityflow-support',
				'label' => esc_html( $this->translate_navigation_label( 'support' ) ),
				'permission' => 'gform_full_access',
				'callback' => array( $this, 'support' ),
			);
			$menu_items[] = $support_item;

			$reports_item = array(
				'name' => 'gravityflow-reports',
				'label' => esc_html( $this->translate_navigation_label( 'reports' ) ),
				'permission' => 'gravityflow_reports',
				'callback' => array( $this, 'reports' )
			);
			$menu_items[] = $reports_item;

			$activity_item = array(
				'name' => 'gravityflow-activity',
				'label' => esc_html( $this->translate_navigation_label( 'activity' ) ),
				'permission' => 'gravityflow_activity',
				'callback' => array( $this, 'activity' ),
			);
			$menu_items[] = $activity_item;

			$menu_items = apply_filters( 'gravityflow_menu_items', $menu_items );

			return $menu_items;
		}

		/**
		 * Build left side options, always have app Settings first and Uninstall last, put extensions in the middle.
		 *
		 * @return array
		 */
		public function get_app_settings_tabs() {

			$setting_tabs = array(
				array(
					'name' => 'settings',
					'label' => esc_html__( 'General', 'gravityflow' ),
					'title' => esc_html__( 'Gravity Flow Settings', 'gravityflow' ),
					'callback' => array( $this, 'app_settings_tab' ),
				),
				array(
					'name'           => 'labels',
					'label'          => __( 'Labels', 'gravityflow' ),
					'callback'       => array( $this, 'app_settings_label_tab' ),
					'icon'           => 'gflow-icon--tag',
					'icon_namespace' => $this->get_icon_namespace(),
				),
				array(
					'name'           => 'connected_apps',
					'label'          => __( 'Connected Apps', 'gravityflow' ),
					'callback'       => array( $this, 'app_settings_connected_apps_tab' ),
					'icon'           => 'gflow-icon--share-social',
					'icon_namespace' => $this->get_icon_namespace(),
				),
				/*
				array(
					'name' => 'tools',
					'label' => __( 'Tools', 'gravityflow' ),
					'callback' => array( $this, 'app_tools_tab' )
				),
				*/
			);

			$setting_tabs = apply_filters( 'gravityflow_settings_menu_tabs', $setting_tabs );

			if ( $this->current_user_can_any( $this->_capabilities_uninstall ) ) {
				$setting_tabs[] = array(
                    'name'           => 'uninstall',
                    'label'          => __( 'Uninstall', 'gravityflow' ),
                    'callback'       => array( $this, 'app_settings_uninstall_tab' ),
                    'icon'           => 'gflow-icon--trash',
                    'icon_namespace' => $this->get_icon_namespace(),
                );
			}

			ksort( $setting_tabs, SORT_NUMERIC );

			return $setting_tabs;
		}

		/**
		 * Returns the base64 encoded svg+xml icon to appear in the app menu.
		 *
		 * @return string
		 */
		public function get_app_menu_icon() {
			$admin_icon = $this->get_admin_icon_b64();
			return $admin_icon;
		}


		/**
		 * Stores an array containing the status and navigation labels in the gravityflow_app_settings_labels option when the settings are saved.
		 */
		public function maybe_update_app_settings_labels() {
			if ( isset( $_POST['gravityflow-labels-update'] ) ) {
				check_admin_referer( 'gravityflow_app_settings_labels' );
				$labels = array(
					'status'     => rgpost( 'status_labels' ),
					'navigation' => rgpost( 'navigation_labels' ),
				);
				update_option( 'gravityflow_app_settings_labels', $labels );
			}
		}

		/**
		 * Prepares a string containing the markup for the navigation label fields.
		 *
		 * @param array $labels The navigation and status labels.
		 *
		 * @return string
		 */
		public function get_navigation_labels_fields( $labels ) {
			$default_navigation_labels = $this->get_default_navigation_labels();
			$custom_navigation_labels  = isset( $labels['navigation'] ) ? $labels['navigation'] : array();
			$navigation_labels         = array_merge( $default_navigation_labels, $custom_navigation_labels );
			$fields                    = array();

			foreach ( $navigation_labels as $navigation_label_key => $navigation_label ) {
				if ( isset( $default_navigation_labels[ $navigation_label_key ] ) ) {
					$default_navigation_label = $default_navigation_labels[ $navigation_label_key ];
					$fields[]                 = sprintf( '<tr><th><label for="navigation_label_%s">%s</label></th><td><input id="navigation_label_%s" type="text" name="navigation_labels[%s]" value="%s" /></td></tr>', $navigation_label_key, $default_navigation_label, $navigation_label_key, $navigation_label_key, rgar( $custom_navigation_labels, $navigation_label_key ) );
				}
			}

			return join( "\n", $fields );
		}

		/**
		 * Prepares a string containing the markup for the status label fields.
		 *
		 * @param array $labels The navigation and status labels.
		 *
		 * @return string
		 */
		public function get_status_labels_fields( $labels ) {
			$default_status_labels = array(
				'pending'   => esc_html__( 'Pending', 'gravityflow' ),
				'cancelled' => esc_html__( 'Cancelled', 'gravityflow' )
			);
			$custom_status_labels  = isset( $labels['status'] ) ? $labels['status'] : array();
			$steps                 = Gravity_Flow_Steps::get_all();

			foreach ( $steps as $step ) {
				$status_configs = $step->get_status_config();
				foreach ( $status_configs as $status_config ) {
					$default_status_labels[ $status_config['status'] ] = $status_config['status_label'];
				}
			}

			$status_labels = array_merge( $default_status_labels, $custom_status_labels );
			$fields        = array();

			foreach ( $status_labels as $status_label_key => $status_label ) {
				$default_status_label = $default_status_labels[ $status_label_key ];
				$fields[]             = sprintf( '<tr><th><label for="status_label_%s">%s</label></th><td><input id="status_label_%s" type="text" name="status_labels[%s]" value="%s" /></td></tr>', $status_label_key, $default_status_label, $status_label_key, $status_label_key, rgar( $custom_status_labels, $status_label_key ) );
			}

			return join( "\n", $fields );
		}

		/**
		 * Render the content for the app Settings > Labels tab.
		 */
		public function app_settings_label_tab() {
			$this->maybe_update_app_settings_labels();

			$labels = get_option( 'gravityflow_app_settings_labels', array() );

			?>

			<h3><span><i class="fa fa-cogs"></i> <?php esc_html_e( 'Labels', 'gravityflow' ); ?></span></h3>

			<form  id="gform-settings" method="POST" action="">
				<?php wp_nonce_field( 'gravityflow_app_settings_labels' ); ?>
				<div class="gaddon-section gaddon-first-section">
					<h4 class="gaddon-section-title gf_settings_subgroup_title"> <?php echo esc_html__( 'Navigation', 'gravityflow' ); ?> </h4>
					<?php

					printf( '<table id="gravityflow-settings-labels-navigation" class="gravityflow-settings-labels">%s</table>', $this->get_navigation_labels_fields( $labels ) );

					?>
				</div>
				<div class="gaddon-section">
					<h4 class="gaddon-section-title gf_settings_subgroup_title"> <?php echo esc_html__( 'Status Labels', 'gravityflow' ); ?> </h4>
					<?php

					printf( '<table id="gravityflow-settings-labels-status" class="gravityflow-settings-labels">%s</table>', $this->get_status_labels_fields( $labels ) );

					?>
				</div>
				<?php echo get_submit_button( esc_html__( 'Update', 'gravityflow' ), 'primary large', 'gravityflow-labels-update', false ); ?>
			</form>

			<?php
		}

		/**
		 * Render the content for the app Settings > Connected Apps tab.
		 */
		public function app_settings_connected_apps_tab() {
			gravityflow_connected_apps()->settings_tab();
		}

		/**
		 * Render the content for the tools page.
		 */
		public function app_tools_tab() {
			$message = '';
			$success = null;

			if ( isset( $_POST['_revoke_token'] ) && check_admin_referer( 'gflow_revoke_token' ) ) {
				$token_str = sanitize_text_field( $_POST['gflow_token'] );
				$token = $this->decode_access_token( $token_str, false );
				if ( empty( $token ) ) {
					$message = __( 'Invalid token', 'gravityflow' );
					$success = false;
				}
				if ( ! empty( $token ) && $token['exp'] < time() ) {
					$message = __( 'Token already expired', 'gravityflow' );
					$success = false;
				}
				if ( is_null( $success ) ) {
					$revoked_tokens = get_option( 'gravityflow_revoked_tokens', array() );
					$revoked_tokens[ $token['jti'] ] = $token['exp'];
					update_option( 'gravityflow_revoked_tokens', $revoked_tokens );
					$success = true;
					$message = __( 'Token revoked', 'gravityflow' );
				}
			}
			?>
			<h3><span><i class="fa fa-cogs"></i> <?php esc_html_e( 'Tools', 'gravityflow' ) ?></span></h3>
			<?php

			if ( ! is_null( $success ) ) {
				$class = $success ? 'gold' : 'red';
				?>

				<div class="push-alert-<?php echo $class; ?>"
				     style="border-left: 1px solid #E6DB55; border-right: 1px solid #E6DB55;">
					<?php echo esc_html( $message ); ?>
				</div>
			<?php } ?>
			<div>
				<form method="POST" action="<?php echo admin_url( 'admin.php?page=gravityflow_settings&view=tools' ); ?>">
					<?php wp_nonce_field( 'gflow_revoke_token' ); ?>
					<div>
						<label for="gflow_token"><?php esc_html_e( 'Revoke a token', 'gravityflow' );?></label>
					</div>
					<div>
						<textarea id="gflow_token" name="gflow_token"></textarea>
					</div>

					<input type="submit" name="_revoke_token" value="<?php esc_html_e( 'Revoke', 'gravityflow' );?>" />
				</form>
			</div>
			<?php
		}

		/**
		 * Get an array of form IDs selected for display on the submit page.
		 *
		 * @return array
		 */
		public function get_published_form_ids() {
			$settings = $this->get_app_settings();

			if ( $settings === false ) {
				return array();
			}

			$selected_form_ids = array();

			foreach ( $settings as $key => $setting ) {
				if ( strstr( $key, 'publish_form_' ) && $setting == 1 ) {
					$form_id = str_replace( 'publish_form_', '', $key );
					$selected_form_ids[] = absint( $form_id );
				}
			}

			$workflow_forms = GFFormsModel::get_forms( true );

			$published_form_ids = array();

			foreach ( $workflow_forms as $workflow_form ) {
				if ( in_array( $workflow_form->id, $selected_form_ids ) ) {
					$published_form_ids[] = $workflow_form->id;
				}
			}

			return $published_form_ids;
		}

		/**
		 * Target for the load-workflow_page_gravityflow-status hook.
		 *
		 * Adds the screen options to the status page.
		 */
		public function load_screen_options() {

			$screen = get_current_screen();

			if ( ! is_object( $screen ) || $screen->id != 'workflow_page_gravityflow-status' ) {
				return;
			}

			if ( $this->is_status_page() ) {
				$args = array(
					'label'   => esc_html__( 'Entries per page', 'gravityflow' ),
					'default' => 20,
					'option'  => 'entries_per_page',
				);
				add_screen_option( 'per_page', $args );
			}

		}

		/**
		 * Determines if the current location is the status page.
		 *
		 * @return bool
		 */
		public function is_status_page() {
			return rgget( 'page' ) == 'gravityflow-status';
		}

		/**
		 * Returns the settings to be displayed on the app settings page.
		 *
		 * @return array
		 */
		public function app_settings_fields() {
			$settings = array();

			if ( ! defined( 'GRAVITY_FLOW_LICENSE_KEY' ) ) {
				$settings[] = array(
					'title'  => esc_html__( 'Settings', 'gravityflow' ),
					'fields' => array(
						array(
							'name'          => 'license_key',
							'label'         => esc_html__( 'License Key', 'gravityflow' ),
							'type'          => 'text',
							'validation_callback' => array( $this, 'license_validation' ),
							'feedback_callback'    => array( $this, 'license_feedback' ),
							'error_message' => __( 'Invalid license', 'gravityflow' ),
							'class' => 'large',
							'default_value' => '',
						),
						array(
							'name'          => 'background_updates',
							'label'         => esc_html__( 'Automatic Updates', 'gravityflow' ),
							'tooltip'       => esc_html__( 'Set this to ON to allow WordPress to download and install Gravity Flow bug fixes and security updates automatically in the background. Requires a valid license key.' , 'gravityflow' ),
							'type'          => 'toggle',
						),
						array(
							'name'          => 'enable_pre_release',
							'label'         => esc_html__( 'Pre-Release Versions', 'gravityflow' ),
							'type'          => 'toggle',
							'toggle_label'  => esc_html__( 'Get updates to pre-release versions of Gravity Flow', 'gravityflow' ),
							'tooltip'       => esc_html__( 'Set this option to update this site to pre-release versions of Gravity Flow including betas and release candidates' , 'gravityflow' ),
						),
					),
				);
			}

			$settings[] = $this->get_app_settings_fields_emails();
			$settings[] = $this->get_app_settings_fields_pages();
			$settings[] = $this->get_app_settings_fields_advanced();
			$settings[] = $this->get_app_settings_fields_published_forms();

			$settings[] = array(
				'id'     => 'save_button',
				'fields' => array(
					array(
						'id'       => 'save_button',
						'name'     => 'save_button',
						'type'     => 'save',
						'value'    => __( 'Update Settings', 'gravityflow' ),
						'messages' => array(
							'success' => __( 'Settings updated successfully', 'gravityflow' ),
							'error'   => __( 'There was an error while saving the settings', 'gravityflow' ),
						),
					),
				)
			);

			return $settings;

		}

		/**
		 * Returns an array of feature settings to be displayed on the app settings page.
		 *
		 * @since 2.7
		 *
		 * @return array
		 */
		public function get_app_settings_fields_advanced() {

			$settings = array(
				'title'  => esc_html__( 'Advanced', 'gravityflow' ),
				'fields' => array(
					array(
						'name'          => 'workflow_inbox_count',
						'label'         => esc_html__( 'Workflow Inbox Count', 'gravityflow' ),
						'tooltip' 		=> __( 'Enable this setting to display the inbox count next to the admin Workflow menu item in the WordPress menu. Warning: For users with a lot of entries, enabling this may affect the performance of the admin dashboard.' , 'gravityflow' ),
						'type'          => 'checkbox',
						'horizontal' 	=> true,
						'default_value' => false,
						'choices' => array(
							array(
								'label' => __( 'Display the number of pending inbox entries in the WordPress menu.', 'gravityflow' ),
								'name'  => 'workflow_inbox_count',
								'value' => true,
							),
						),
					),
					array(
						'name'  => 'shortcodes',
						'label' => esc_html__( 'Shortcode Security', 'gravityflow' ),
						'type'        => 'checkbox',
						'description' => esc_html__( 'Important: Do not enable any of these settings unless all page editors are authorized.', 'gravityflow' ),
						'choices'     => array(
							array(
								'label'   => esc_html__( 'Allow the Status shortcode to display all entries to all registered users.', 'gravityflow' ),
								'name'    => 'allow_display_all_attribute',
								'tooltip' => esc_html__( 'This setting allows the display_all attribute to be used in the shortcode.', 'gravityflow' ),
							),
							array(
								'label'   => esc_html__( 'Allow the Status shortcode to display all entries to all anonymous users.', 'gravityflow' ),
								'name'    => 'allow_allow_anonymous_attribute',
								'tooltip' => esc_html__( 'This setting allows the allow_anonymous attribute to be used in the shortcode.', 'gravityflow' ),
							),
							array(
								'label'   => esc_html__( 'Allow the Inbox and Status shortcodes to display field values.', 'gravityflow' ),
								'name'    => 'allow_field_ids',
								'tooltip' => esc_html__( 'This setting allows the fields attribute to be used in the shortcode.', 'gravityflow' ),
							),
							array(
								'label'   => esc_html__( 'Allow the Reports shortcode to display workflow reports to all registered and anonymous users.', 'gravityflow' ),
								'name'    => 'allow_display_reports',
								'tooltip' => esc_html__( 'This setting allows the Reports shortcode to display workflow reports to all registered and anonymous users.', 'gravityflow' ),
							),
						),
					),
				),
			);

			if ( class_exists( 'GravityView_Field' ) ) {
				$settings['fields'][] = array(
					'name'  => 'gravityview',
					'label' => esc_html__( 'GravityView Integration', 'gravityflow' ),
					'type'        => 'checkbox',
					'choices'     => array(
						array(
							'label'   => esc_html__( 'Editing an entry in GravityView will complete a User Input Step when the user is an assignee.', 'gravityflow' ),
							'name'    => 'gravityview_allow_edit_user_input',
							'tooltip' => esc_html__( 'This setting will not prevent editing in GravityView if the workflow is not on a User Input step.', 'gravityflow' ),
						),
					),
				);
			}

			return $settings;
		}

		/**
		 * Returns an array of email related settings to be displayed on the app settings page.
		 *
		 * @since 2.3.4
		 *
		 * @return array
		 */
		public function get_app_settings_fields_emails() {

			$settings = array(
				'title'  => esc_html__( 'Workflow Emails', 'gravityflow' ),
				'fields' => array(),
			);

			require_once GFCommon::get_base_path() . '/notification.php';
			$notification_services = GFNotification::get_notification_services();

			if ( count( $notification_services ) > 1 ) {
				$service_choices = array();

				foreach ( $notification_services as $key => $service ) {
					$service_choices[] = array(
						'label' => rgar( $service, 'label' ),
						'value' => $key,
						'icon'  => rgar( $service, 'image' ),
					);
				}

				$settings['fields'][] = array(
					'name'          => 'email_service',
					'label'         => esc_html__( 'Email Service', 'gravityflow' ),
					'tooltip'       => __( 'Select which service should be used to send the workflow emails. WordPress uses the server hosting your site or an active SMTP plugin.', 'gravityflow' ),
					'type'          => 'radio',
					'horizontal'    => true,
					'default_value' => 'wordpress',
					'choices'       => $service_choices,
					'onchange'      => 'jQuery(this).parents("form").submit();',
				);
			}

			$settings['fields'][] = array(
				'name'          => 'from_name',
				'label'         => esc_html__( 'From Name', 'gravityflow' ),
				'tooltip'       => __( 'The default From Name to be used when the From Name setting is not configured on the individual steps.', 'gravityflow' ),
				'type'          => 'text',
				'default_value' => get_bloginfo( 'name' ),
				'class'         => 'medium',
			);

			$settings['fields'][] = $this->get_app_settings_field_from_email();

			return $settings;
		}

		/**
		 * Returns an array of properties for the From Email setting to be displayed on the app settings page.
		 *
		 * @since 2.3.4
		 *
		 * @return array
		 */
		public function get_app_settings_field_from_email() {
			$setting = array(
				'name'                => 'from_email',
				'label'               => esc_html__( 'From Email', 'gravityflow' ),
				'tooltip'             => __( 'The default From Email to be used when the From Email setting is not configured on the individual steps.', 'gravityflow' ),
				'type'                => 'text',
				'default_value'       => get_bloginfo( 'admin_email' ),
				'class'               => 'medium',
				'validation_callback' => array( $this, 'validate_from_email' ),
			);

			$service = $this->get_setting( 'email_service' );

			if ( $service == 'postmark' && function_exists( 'gf_postmark' ) ) {
				$choices = array();

				try {
					$postmark = new GF_Postmark_API();
					$postmark->set_account_token( gf_postmark()->get_plugin_setting( 'accountToken' ) );
					$sender_signatures = $postmark->get_sender_signatures();

					foreach ( $sender_signatures as $sender_signature ) {
						$choices[] = array(
							'label' => $sender_signature['EmailAddress'],
							'value' => $sender_signature['EmailAddress'],
						);
					}

					unset( $setting['default_value'], $setting['class'], $setting['validation_callback'] );
					$setting['type']    = 'select';
					$setting['choices'] = $choices;
				} catch ( Exception $e ) {
					// Do nothing. The text based setting will be used instead.
				}
			}

			return $setting;
		}

		/**
		 * Validates the From Name app setting.
		 *
		 * @since 2.3.4
		 *
		 * @param array  $field The setting properties.
		 * @param string $value The setting value.
		 */
		public function validate_from_email( $field, $value ) {
			if ( empty( $value ) || GFCommon::has_merge_tag( $value ) ) {
				return;
			}

			if ( ! GFCommon::is_valid_email( $value ) ) {
				$this->set_field_error( $field, esc_html__( 'Please enter a valid email address.', 'gravityflow' ) );
				return;
			}

			$service = $this->get_setting( 'email_service', 'wordpress' );
			if ( $service == 'wordpress' ) {
				return;
			}

			$error_message = '';

			if ( $service == 'mailgun' && function_exists( 'gf_mailgun' ) ) {
				$from_domain = explode( '@', $value );
				$from_domain = end( $from_domain );
				if ( ! gf_mailgun()->is_valid_domain( $from_domain ) ) {
					$error_message = sprintf(
						esc_html__( 'From Email domain must be an %1$sactive domain%3$s. You can learn more about verifying your domain in the %2$sMailgun documentation%3$s.', 'gravityflow' ),
						"<a href='https://mailgun.com/app/domains'>",
						"<a href='https://documentation.mailgun.com/user_manual.html#verifying-your-domain'>",
						'</a>'
					);
				}
			}

			if ( $error_message ) {
				$this->set_field_error( $field, $error_message );
			}

		}

		/**
		 * Returns the Published Forms section of fields to be displayed on the app settings page.
		 *
		 * @since 2.3.4
		 *
		 * @return array
		 */
		public function get_app_settings_fields_published_forms() {
			$forms   = GFAPI::get_forms();
			$choices = array();
			foreach ( $forms as $form ) {
				$form_id = absint( $form['id'] );
				$feeds   = $this->get_feeds( $form_id );
				if ( ! empty( $feeds ) ) {
					$choices[] = array(
						'label' => esc_html( $form['title'] ),
						'name'  => 'publish_form_' . absint( $form['id'] ),
					);
				}
			}

			if ( ! empty( $choices ) ) {
				$published_forms_fields = array(
					array(
						'name'    => 'form_ids',
						'label'   => esc_html__( 'Published', 'gravityflow' ),
						'type'    => 'checkbox',
						'choices' => $choices,
					),
				);
			} else {
				$published_forms_fields = array(
					array(
						'name'  => 'no_workflows',
						'label' => '',
						'type'  => 'html',
						'html'  => esc_html__( 'No workflow steps have been added to any forms yet.', 'gravityflow' ),
					),
				);
			}

			return array(
				'title'       => esc_html__( 'Published Workflow Forms', 'gravityflow' ),
				'description' => esc_html__( 'Select the forms you wish to publish on the Submit page.', 'gravityflow' ),
				'fields'      => $published_forms_fields,
			);
		}

		/**
		 * Returns the Default Pages section of settings to be displayed on the app settings page.
		 *
		 * @since 2.3.4
		 *
		 * @return array
		 */
		public function get_app_settings_fields_pages() {
			return array(
				'title'       => esc_html__( 'Default Pages', 'gravityflow' ),
				'description' => esc_html__( 'Select the pages which contain the following gravityflow shortcodes. For example, the inbox page selected below will be used when preparing merge tags such as {workflow_inbox_link} when the page_id attribute is not specified.', 'gravityflow' ),
				'fields'      => array(
					array(
						'name'  => 'inbox_page',
						'label' => esc_html__( 'Inbox', 'gravityflow' ),
						'type'  => 'wp_dropdown_pages',
					),
					array(
						'name'  => 'status_page',
						'label' => esc_html__( 'Status', 'gravityflow' ),
						'type'  => 'wp_dropdown_pages',
					),
					array(
						'name'  => 'submit_page',
						'label' => esc_html__( 'Submit', 'gravityflow' ),
						'type'  => 'wp_dropdown_pages',
					),
				),
			);
		}

		/**
		 * Determines if the license is valid so the correct feedback icon can be displayed next to the setting.
		 *
		 * @param string $value The license key.
		 * @param array  $field The field properties.
		 *
		 * @return bool|null
		 */
		public function license_feedback( $value, $field ) {

			if ( empty( $value ) ) {
				return null;
			}

			$license_data = $this->check_license( $value );

			$valid = $license_data && $license_data->license == 'valid' ? true : false;

			return $valid;
		}

		/**
		 * Performs the remote request to check if the license key is activated, valid, and not expired.
		 *
		 * @param string $value The license key.
		 *
		 * @return array|object|false
		 */
		public function check_license( $value = '' ) {
			if ( empty( $value ) ) {
				$value = $this->get_app_setting( 'license_key' );
			}

			if ( empty( $value ) ) {
				return false;
			}

			// Static cache to prevent multiple requests for the same license key.
			static $response = array();

			if ( ! isset( $response[ $value ] ) ) {
				$response[ $value ] = $this->perform_edd_license_request( 'check_license', $value );
			}

			return json_decode( wp_remote_retrieve_body( $response[ $value ] ) );
		}

		/**
		 * Deactivates the old license key and triggers activation of the new license key.
		 *
		 * @param array  $field         The license field properties.
		 * @param string $field_setting The license key to be validated.
		 */
		public function license_validation( $field, $field_setting ) {
			$old_license = $this->get_app_setting( 'license_key' );

			if ( $old_license && $field_setting != $old_license ) {
				// Deactivate the old site.
				$response = $this->perform_edd_license_request( 'deactivate_license', $old_license );
				$this->log_debug( __METHOD__ . '() - response: ' . print_r( $response, 1 ) );
			}

			set_transient( 'gravityflow_license_details', false );

			if ( empty( $field_setting ) ) {
				return;
			}

			$this->activate_license( $field_setting );
		}

		/**
		 * Activates the license key for this site and clears the cached version info,
		 *
		 * @param string $license_key The license key to be activated.
		 *
		 * @return array|object
		 */
		public function activate_license( $license_key ) {
			$response = $this->perform_edd_license_request( 'activate_license', $license_key );

			set_site_transient( 'update_plugins', null );
			$cache_key = md5( 'edd_plugin_' . sanitize_key( $this->_path ) . '_version_info' );
			delete_transient( $cache_key );

			return json_decode( wp_remote_retrieve_body( $response ) );
		}

		/**
		 * Send a request to the EDD store url.
		 *
		 * @since 2.2.4 Added support for item ID as well as name.
		 * @since unkonwn
		 *
		 * @param string     $edd_action      The action to perform (check_license, activate_license or deactivate_license).
		 * @param string     $license         The license key.
		 * @param string|int $item_name_or_id The EDD item name. Defaults to the value of the GRAVITY_FLOW_EDD_ITEM_NAME constant.
		 *
		 * @return array|WP_Error The response.
		 */
		public function perform_edd_license_request( $edd_action, $license, $item_name_or_id = GRAVITY_FLOW_EDD_ITEM_ID ) {
			// Prepare the request arguments.
			$args = array(
				'timeout'   => 10,
				'sslverify' => true,
				'body'      => array(
					'edd_action' => $edd_action,
					'license'    => trim( $license ),
					'url'        => network_home_url(),
				),
			);

			if ( is_numeric( $item_name_or_id ) ) {
				$args['body']['item_id'] = $item_name_or_id;
			} else {
				$args['body']['item_name'] = urlencode( $item_name_or_id );
			}

			// Send the remote request.
			$response = wp_remote_post( GRAVITY_FLOW_EDD_STORE_URL, $args );

			return $response;
		}

		/**
		 * Triggers display of the submit page, if installation has been completed.
		 */
		public function submit() {

			if ( $this->maybe_display_installation_wizard() ) {
				return;
			}

			$this->submit_page( true );
		}

		/**
		 * Renders the submit page.
		 *
		 * @since  unknown
		 * @since  2.6   Added the $form_ids parameter.
		 *
		 * @param bool       $admin_ui Whether to display the admin UI.
		 * @param null|array $form_ids An array of form IDs.
		 */
		public function submit_page( $admin_ui, $form_ids = null ) {

			?>
			<div class="gravityflow_wrap gf_entry_wrap gravityflow_workflow_wrap gravityflow_workflow_submit">
				<?php if ( $admin_ui ) :	?>
					<h2 class="gf_admin_page_title">
						<img width="45" height="22" src="<?php echo esc_url( gravity_flow()->get_base_url() ); ?>/images/gravity-flow-icon-black.svg" style="margin-right:5px;"/>

						<span><?php esc_html_e( 'Submit a Workflow Form', 'gravityflow' ); ?></span>

					</h2>
					<?php
					$this->toolbar();
				endif;

				require_once( $this->get_base_path() . '/includes/pages/class-submit.php' );

				if ( is_array( $form_ids ) && ! empty ( $form_ids ) ) {
					$published_form_ids = $form_ids;
				} else {
					$published_form_ids = gravity_flow()->get_published_form_ids();
				}

				if ( isset( $_GET['id'] ) ) {

					$form_id = absint( $_GET['id'] );

					$can_render_form = in_array( $form_id, $published_form_ids );

					/**
					 * Controls whether a form can be rendered.
					 *
					 * @since 2.5.4
					 *
					 * @param bool|WP_Error $can_render_form Return a boolean or a WP_Error object with a message to display to the user.
					 * @param int           $form_id         The Form ID
					 */
					$can_render_form = apply_filters( 'gravityflow_can_render_form', $can_render_form, $form_id );

					if ( is_wp_error( $can_render_form ) ) {
						/** @var WP_Error $can_render_form */
						echo $can_render_form->get_error_message();
					} elseif ( $can_render_form ) {
						Gravity_Flow_Submit::form( $form_id );
					}
				} else {
					Gravity_Flow_Submit::list_page( $published_form_ids, $admin_ui );
				}

				?>
			</div>
			<?php
		}

		/**
		 * Determines if the installation wizard should be displayed.
		 *
		 * @return bool
		 */
		public function maybe_display_installation_wizard() {

			if ( is_multisite() || ! current_user_can( 'gform_full_access' ) ) {
				return false;
			}

			$pending_installation = get_option( 'gravityflow_pending_installation' ) || isset( $_GET['gravityflow_installation_wizard'] );

			if ( $pending_installation ) {
				require_once( $this->get_base_path() . '/includes/wizard/class-installation-wizard.php' );
				$wizard = new Gravity_Flow_Installation_Wizard;
				$result = $wizard->display();
				return $result;
			}

			if ( GFAPI::current_user_can_any( 'gform_full_access' ) && $this->is_dev_version() && ! SCRIPT_DEBUG ) {
				$message = esc_html__( 'Important: Gravity Flow (Development Version) is missing some important files that were not included in the installation package. Consult the readme.md file for further details.', 'gravityflow' );
				GFCommon::add_message( $message, true );
			};

			return false;
		}

		/**
		 * Checks whether the current version is a development version. The development version does not include
		 * minified CSS and JavaScript files.
		 *
		 * Interim build packages of the development version generated during continuous integration do contain
		 * the minified files and are therefore not considered development versions despite the version number.
		 * These builds contain the commit hash in the plugin version.
		 *
		 * @since 1.7.1
		 *
		 * @return bool
		 */
		public function is_dev_version() {
			$is_dev_version = false;
			$version = $this->get_version();
			if ( strpos( $version, '-dev' ) > 0  ) {
				$plugin_data    = get_plugin_data( $this->get_base_path() . '/gravityflow.php' );
				$plugin_version = $plugin_data['Version'];
				$hash = str_replace( $version, '', $plugin_version );
				if ( empty( $hash ) ) {
					$is_dev_version = true;
				}
			}
			return $is_dev_version;
		}


		/**
		 * Displays the Inbox UI
		 */
		public function inbox() {

			if ( $this->maybe_display_installation_wizard() ) {
				return;
			}

			$this->inbox_page();

		}

		/**
		 * Renders the inbox page.
		 *
		 * @param array $args The inbox page arguments.
		 */
		public function inbox_page( $args = array() ) {

			$defaults = array(
				'display_empty_fields' => true,
				'check_permissions'    => true,
				'show_header'          => true,
				'timeline'             => true,
				'step_highlight'       => true,
				'due_date'             => false,
				'context_key'          => 'wp-admin',
				'back_link'            => false,
				'back_link_text'       => __( 'Return to list', 'gravityflow' ),
				'back_link_url'        => null,
			);

			$args = array_merge( $defaults, $args );

			if ( rgget( 'view' ) == 'entry' || ! empty( $args['entry_id'] ) ) {

				$entry_id = absint( rgget( 'lid' ) );

				if ( empty( $entry_id ) ) {

					$entry_id = absint( $args['entry_id'] );

				}

				$entry = GFAPI::get_entry( $entry_id );

				if ( is_wp_error( $entry ) ) {
					esc_html_e( 'Oops! We could not locate your entry.', 'gravityflow' );
					return;
				}

				$form_id   = $entry['form_id'];
				$passed_id = rgget( 'id' );

				// ID in URL param does not match the entry's Form ID; bail.
				if ( ! empty( $passed_id ) && $form_id != $passed_id ) {
					esc_html_e( 'Oops! We could not locate your entry.', 'gravityflow' );
					return;
				}

				$form = GFAPI::get_form( $form_id );

				$process_entry_detail = apply_filters( 'gravityflow_inbox_entry_detail_pre_process', true, $form, $entry );

				if ( ! $process_entry_detail || is_wp_error( $process_entry_detail ) ) {
					return;
				}

				require_once( $this->get_base_path() . '/includes/pages/class-entry-detail.php' );

				$step = $this->get_current_step( $form, $entry );

				if ( $step ) {
					$token = $this->decode_access_token();

					if ( isset( $token['scopes']['action'] ) ) {
						if ( $token['scopes']['action'] === 'cancel_workflow' ) {
							$entry_id = rgars( $token, 'scopes/entry_id' );
							if ( empty( $entry_id ) || $entry_id != $entry['id'] ) {
								esc_html_e( 'Error: incorrect entry.', 'gravityflow' );
								return;
							}
							$api = new Gravity_Flow_API( $form_id );
							$result = $api->cancel_workflow( $entry );
							if ( $result ) {
								$complete_step = $this->get_workflow_complete_step( $form_id );
								if ( $complete_step->cancellationEnable && strlen( $complete_step->cancellationValue ) ) {
									$feedback = $complete_step->cancellationValue;
								} else {
									$feedback = esc_html__( 'Workflow Cancelled', 'gravityflow' );
								}

								/**
								 * Allows the user feedback to be modified after cancelling the workflow with the cancel link.
								 *
								 * Return a sanitized string.
								 *
								 * @since 2.0.2
								 *
								 * @param string                $feedback   The sanitized feedback to send to the browser.
								 * @param array                 $entry      The current entry array.
								 * @param Gravity_Flow_Assignee $assignee   The assignee object.
								 * @param string                $new_status The new status
								 * @param array                 $form       The current form array.
								 * @param Gravity_Flow_Step     $step       The current step
								 */
								$feedback = apply_filters( 'gravityflow_feedback_cancel_workflow', $feedback, $entry, $form, $step );
								echo $feedback;
							}
							return;
						}

						$feedback = $step->maybe_process_token_action( $token['scopes']['action'], $token, $form, $entry );

						if ( empty( $feedback ) ) {
							esc_html_e( 'Error: This URL is no longer valid.', 'gravityflow' );
							return;
						}
						if ( is_wp_error( $feedback ) ) {
							/* @var WP_Error $feedback */
							echo $feedback->get_error_message();
							return;
						}
						$this->process_workflow( $form, $entry_id );
						echo $feedback;
						return;
					}
				}

				$feedback = $this->maybe_process_admin_action( $form, $entry );

				if ( empty( $feedback ) && $step ) {

					$feedback = $step->process_status_update( $form, $entry );

					if ( $feedback && ! is_wp_error( $feedback ) ) {
						$this->process_workflow( $form, $entry_id );
					}
				}

				if ( is_wp_error( $feedback ) ) {
					$error_data = $feedback->get_error_data();
					if ( ! empty( $error_data['form'] ) ) {
						$form = $error_data['form'];
					}
					?>
					<div class="notice error is-dismissible gravityflow_validation_error" style="padding:6px;">
						<p><?php echo esc_html( $feedback->get_error_message() ); ?></p>
					</div>
					<?php

				} elseif ( $feedback ) {
					GFCache::flush();

					$entry = GFAPI::get_entry( $entry_id ); // Refresh entry.

					$feedback = GFCommon::replace_variables( $feedback, $form, $entry, false, true, true, 'html' );

					if ( substr( $feedback, 0, 3 ) !== '<p>' ) {
						$feedback = sprintf( '<p>%s</p>', $feedback );
					}
					?>
					<div class="gravityflow_workflow_notice updated notice notice-success is-dismissible" style="padding:6px;">
						<?php echo $feedback; ?>
					</div>
					<?php

					$next_step = $this->get_current_step( $form, $entry );
					$current_user_assignee_key = $this->get_current_user_assignee_key();
					if ( $next_step && ( $next_step->is_assignee( $current_user_assignee_key ) || $args['check_permissions'] == false || $this->current_user_can_any( 'gravityflow_status_view_all' ) ) ) {
						$step = $next_step->get_current_assignee_status() == 'complete' ? false : $next_step;
					} else {
						$step = false;
						$args['display_instructions'] = false;
					}
					$args['check_permissions'] = false;
				}

				Gravity_Flow_Entry_Detail::entry_detail( $form, $entry, $step, $args );
				return;
			} else {

				?>
				<div class="gravityflow_wrap gf_entry_wrap gravityflow_workflow_wrap gravityflow_workflow_detail">
					<?php if ( $args['show_header'] ) :	?>
						<h2 class="gf_admin_page_title">
							<img width="45" height="22" src="<?php echo $this->get_base_url(); ?>/images/gravity-flow-icon-cropped.svg" style="margin-right:5px;"/>
							<span><?php esc_html_e( 'Workflow Inbox', 'gravityflow' ); ?></span>
						</h2>

						<?php GFCommon::display_admin_message(); ?>

						<?php
						$this->toolbar();
					endif;

					require_once( $this->get_base_path() . '/includes/pages/class-inbox.php' );
					Gravity_Flow_Inbox::display( $args );

					?>
				</div>
				<?php
			}
		}

		/**
		 * Triggers display of the status page, if installation has been completed.
		 */
		public function status() {

			if ( $this->maybe_display_installation_wizard() ) {
				return;
			}

			$this->status_page();
		}

		/**
		 * Renders the status page.
		 *
		 * @param array $args The status page arguments.
		 */
		public function status_page( $args = array() ) {
			$defaults = array(
				'display_header' => true,
				'context_key'    => 'wp-admin',
			);
			$args = array_merge( $defaults, $args );
			?>
			<div class="gravityflow_wrap gf_entry_wrap gravityflow_workflow_wrap gravityflow_workflow_status">

				<?php if ( $args['display_header'] ) : ?>
					<h2 class="gf_admin_page_title">
						<img width="44px" height="22px" src="<?php echo esc_url( gravity_flow()->get_base_url() ); ?>/images/gravity-flow-icon-cropped.svg" style="margin-right:5px;"/>
						<span><?php esc_html_e( 'Workflow Status', 'gravityflow' ); ?></span>
					</h2>

					<?php GFCommon::display_admin_message(); ?>

					<?php $this->toolbar(); ?>
					<?php
				endif;

				require_once( $this->get_base_path() . '/includes/pages/class-status.php' );
				Gravity_Flow_Status::render( $args );
				?>
			</div>
			<?php
		}

		/**
		 * Displays the Activity UI
		 */
		public function activity() {

			if ( $this->maybe_display_installation_wizard() ) {
				return;
			}

			$this->activity_page();
		}

		/**
		 * Renders the activity page.
		 *
		 * @param array $args The activity page arguments.
		 */
		public function activity_page( $args = array() ) {
			$defaults = array(
				'display_header' => true,
			);
			$args = array_merge( $defaults, $args );
			?>
			<div class="gravityflow_wrap gf_entry_wrap gravityflow_workflow_wrap gravityflow_workflow_activity">

				<?php if ( $args['display_header'] ) : ?>
					<h2 class="gf_admin_page_title">
						<img width="45" height="22" src="<?php echo esc_url( gravity_flow()->get_base_url() ); ?>/images/gravity-flow-icon-cropped.svg" style="margin-right:5px;"/>

						<span><?php esc_html_e( 'Workflow Activity', 'gravityflow' ); ?></span>

					</h2>

					<?php GFCommon::display_admin_message(); ?>

					<?php $this->toolbar(); ?>
					<?php
				endif;

				require_once( $this->get_base_path() . '/includes/pages/class-activity.php' );
				Gravity_Flow_Activity_List::display( $args );
				?>
			</div>
			<?php
		}

		/**
		 * Displays the Reports UI
		 */
		public function reports() {

			if ( $this->maybe_display_installation_wizard() ) {
				return;
			}

			$this->reports_page();
		}

		/**
		 * Renders the reports page.
		 *
		 * @param array $args The reports page arguments.
		 */
		public function reports_page( $args = array() ) {
			$defaults = array(
				'display_header' => true,
			);
			$args = array_merge( $defaults, $args );
			?>
			<div class="gravityflow_wrap gf_entry_wrap gravityflow_workflow_wrap gravityflow_workflow_reports">

				<?php if ( $args['display_header'] ) : ?>
					<h2 class="gf_admin_page_title">
						<img width="45" height="22" src="<?php echo esc_url( gravity_flow()->get_base_url() ); ?>/images/gravity-flow-icon-cropped.svg" style="margin-right:5px;"/>

						<span><?php esc_html_e( 'Workflow Reports', 'gravityflow' ); ?></span>

					</h2>

					<?php GFCommon::display_admin_message(); ?>

					<?php $this->toolbar(); ?>
					<?php
				endif;

				require_once( $this->get_base_path() . '/includes/pages/class-reports.php' );
				Gravity_Flow_Reports::display( $args );
				?>
			</div>
			<?php
		}

		/**
		 * Renders the admin side toolbar.
		 */
		public function toolbar() {

			$legacy = version_compare( GFForms::$version, '2.5-dev-1', '<' ) ? true : false;

			?>

			<div
				id="<?php echo $legacy ? 'gf_form_toolbar': 'gform-form-toolbar'; ?>"
				class="gform-form-toolbar"
			>
				<ul
					id="<?php echo $legacy ? 'gf_form_toolbar_links': 'gform-form-toolbar__menu'; ?>"
					class="gform-form-toolbar__menu"
				>

					<?php

					$menu_items = self::get_toolbar_menu_items();

					echo GFForms::format_toolbar_menu_items( $menu_items );

					?>
				</ul>
			</div>
			<?php
		}

		/**
		 * Prepares an array of properties to be used by Gravity Forms when rendering the toolbar.
		 *
		 * @return array
		 */
		public function get_toolbar_menu_items() {
			$menu_items = array();

			$active_class = 'gf_toolbar_active';
			$not_active_class = '';

			$menu_items['inbox'] = array(
				'label'        => esc_html( $this->translate_navigation_label( 'inbox' ) ),
				'icon'         => '<i class="fa fa-inbox fa-lg"></i>',
				'title'        => __( 'Your inbox of pending tasks', 'gravityflow' ),
				'url'          => '?page=gravityflow-inbox',
				'menu_class'   => 'gf_form_toolbar_editor',
				'link_class'   => ( rgget( 'page' ) == 'gravityflow-inbox' ) ? $active_class : $not_active_class,
				'capabilities' => 'gravityflow_inbox',
				'priority'     => 1000,
			);

			$form_ids = $this->get_published_form_ids();

			if ( ! empty( $form_ids ) ) {
				$menu_items['submit'] = array(
					'label'        => esc_html( $this->translate_navigation_label( 'submit' ) ),
					'icon'         => '<i class="fa fa-pencil-square-o fa-lg"></i>',
					'title'        => __( 'Submit a Workflow', 'gravityflow' ),
					'url'          => '?page=gravityflow-submit',
					'menu_class'   => 'gf_form_toolbar_editor',
					'link_class'   => ( rgget( 'page' ) == 'gravityflow-submit' ) ? $active_class : $not_active_class,
					'capabilities' => 'gravityflow_submit',
					'priority'     => 900,
				);
			}

			$menu_items['status'] = array(
				'label'          => esc_html( $this->translate_navigation_label( 'status' ) ),
				'icon'           => '<i class="fa fa-tachometer fa-lg"></i>',
				'title'          => __( 'Your workflows', 'gravityflow' ),
				'url'            => '?page=gravityflow-status',
				'menu_class'     => 'gf_form_toolbar_settings',
				'link_class'   => ( rgget( 'page' ) == 'gravityflow-status' ) ? $active_class : $not_active_class,
				'capabilities'   => 'gravityflow_status',
				'priority'       => 800,
			);

			$menu_items['reports'] = array(
				'label'          => esc_html( $this->translate_navigation_label( 'reports' ) ),
				'icon'           => '<i class="fa fa fa-bar-chart-o fa-lg"></i>',
				'title'          => __( 'Reports', 'gravityflow' ),
				'url'            => '?page=gravityflow-reports',
				'menu_class'     => 'gf_form_toolbar_settings',
				'link_class'   => ( rgget( 'page' ) == 'gravityflow-reports' ) ? $active_class : $not_active_class,
				'capabilities'   => 'gravityflow_reports',
				'priority'       => 700,
			);

			$menu_items['activity'] = array(
				'label'          => esc_html( $this->translate_navigation_label( 'activity' ) ),
				'icon'           => '<i class="fa fa fa-list fa-lg"></i>',
				'title'          => __( 'Activity', 'gravityflow' ),
				'url'            => '?page=gravityflow-activity',
				'menu_class'     => 'gf_form_toolbar_settings',
				'link_class'   => ( rgget( 'page' ) == 'gravityflow-activity' ) ? $active_class : $not_active_class,
				'capabilities'   => 'gravityflow_activity',
				'priority'       => 600,
			);

			$menu_items = apply_filters( 'gravityflow_toolbar_menu_items', $menu_items );

			return $menu_items;
		}

		/**
		 * Processes the admin action from the entry detail page.
		 *
		 * @param array $form The current form.
		 * @param array $entry The current entry.
		 *
		 * @return bool|string|WP_Error Return a success feedback message safe for page output or a WP_Error instance with an error.
		 */
		public function maybe_process_admin_action( $form, $entry ) {
			$feedback = false;
			if ( isset( $_POST['_gravityflow_admin_action'] ) && check_admin_referer( 'gravityflow_admin_action', '_gravityflow_admin_action_nonce' ) && GFAPI::current_user_can_any( 'gravityflow_workflow_detail_admin_actions' ) ) {
				$admin_action = rgpost( 'gravityflow_admin_action' );
				switch ( $admin_action ) {
					case 'cancel_workflow' :
						$api = new Gravity_Flow_API( $form['id'] );
						$success = $api->cancel_workflow( $entry );
						if ( $success ) {
							$this->log_debug( __METHOD__ . '() - workflow cancelled. entry id ' . $entry['id'] );
							$feedback = esc_html__( 'Workflow cancelled.',  'gravityflow' );

						} else {
							$this->log_debug( __METHOD__ . '() - workflow cancel failed. entry id ' . $entry['id'] );
							$feedback = esc_html__( 'The entry does not currently have an active step.', 'gravityflow' );
						}

						break;
					case 'restart_step':
						$api = new Gravity_Flow_API( $form['id'] );
						$success = $api->restart_step( $entry );
						if ( $success ) {
							$this->log_debug( __METHOD__ . '() - step restarted. entry id ' . $entry['id'] );
							$feedback = esc_html__( 'Workflow Step restarted.',  'gravityflow' );
						} else {
							$this->log_debug( __METHOD__ . '() - step restart failed. entry id ' . $entry['id'] );
							$feedback = esc_html__( 'The entry does not currently have an active step.', 'gravityflow' );
						}

						break;
					case 'restart_workflow':
						$api = new Gravity_Flow_API( $form['id'] );
						$api->restart_workflow( $entry );
						$this->log_debug( __METHOD__ . '() - workflow restarted. entry id ' . $entry['id'] );
						$feedback = esc_html__( 'Workflow restarted.',  'gravityflow' );
						break;
				}
				list( $base_admin_action, $action_id ) = rgexplode( '|', $admin_action, 2 );
				if ( $base_admin_action == 'send_to_step' ) {
					$step_id = $action_id;
					$api = new Gravity_Flow_API( $form['id'] );
					$api->send_to_step( $entry, $step_id );
					$entry = GFAPI::get_entry( $entry['id'] );
					$new_step = $api->get_current_step( $entry );
					$feedback = $new_step ? sprintf( esc_html__( 'Sent to step: %s',  'gravityflow' ), $new_step->get_name() ) : esc_html__( 'Workflow Complete',  'gravityflow' );
				}

				/**
				 * Allows the feedback for the admin action to be modified. Also allows custom admin actions to be processed.
				 *
				 * @param string $feedback     A string with the feedback to be displayed to the user or an instance of WP_Error.
				 * @param string $admin_action The admin action.
				 * @param array  $form         The form array.
				 * @param array  $entry        The entry array.
				 */
				$feedback = apply_filters( 'gravityflow_admin_action_feedback', $feedback, $admin_action, $form, $entry );
			}
			return $feedback;
		}

		/**
		 * Adds the workflow notification events, if the form has a workflow configured.
		 *
		 * @param array $events The notification events.
		 * @param array $form   The current form.
		 *
		 * @return array
		 */
		public function add_notification_event( $events, $form ) {
			if ( $this->has_feed( $form['id'] ) ) {
				$events['workflow_approval']   = __( 'Workflow: approved or rejected', 'gravityflow' );
				$events['workflow_user_input'] = __( 'Workflow: user input', 'gravityflow' );
				$events['workflow_complete']   = __( 'Workflow: complete', 'gravityflow' );
				$events['workflow_cancelled']  = __( 'Workflow: cancelled', 'gravityflow' );
			}

			return $events;
		}

		/**
		 * Checks the workflow steps to see if any feeds belonging to other add-ons need to be delayed.
		 *
		 * @param array $entry The entry created from the current form submission.
		 * @param array $form  The form object used to process the current submission.
		 *
		 * @return void
		 */
		public function action_entry_created( $entry, $form ) {
			$form_id = absint( $form['id'] );

			if ( empty( $form_id ) || ! isset( $entry['id'] ) || $entry['status'] === 'spam' ) {
				return;
			}

			$steps = $this->get_steps( $form_id );

			foreach ( $steps as $step ) {
				if ( ! $step->is_active() || ! is_callable( array( $step, 'intercept_submission' ) ) ) {
					continue;
				}

				$step->intercept_submission();
			}

			$this->maybe_delay_workflow( $entry, $form );
		}

		/**
		 * Determines if workflow processing should be delayed for the current submission.
		 *
		 * @since unknown
		 * @since 2.5.8 Updated to support the delayed payment enhancements in GF 2.4.13.
		 *
		 * @param array $entry The entry created from the current form submission.
		 * @param array $form  The form object used to process the current submission.
		 */
		public function maybe_delay_workflow( $entry, $form ) {
			if ( ! $this->get_first_step( $form['id'], $entry ) ) {
				return;
			}

			// From GF 2.4.13 GFPaymentAddOn uses the gform_is_delayed_pre_process_feed filter located in maybe_delay_feed() to delay processing.
			// With older GF versions maybe_delay_feed() contains the logic for PayPal Standard.
			$is_delayed = $this->maybe_delay_feed( $entry, $form );

			/**
			 * Allow processing of the workflow to be delayed.
			 *
			 * @since 2.0.2-dev
			 *
			 * @param bool  $is_delayed Indicates if processing of the workflow should be delayed.
			 * @param array $entry      The current entry.
			 * @param array $form       The current form.
			 */
			$is_delayed = apply_filters( 'gravityflow_is_delayed_pre_process_workflow', $is_delayed, $entry, $form );

			if ( $is_delayed ) {
				$this->log_debug( __METHOD__ . '() - processing delayed for entry id ' . $entry['id'] );
				if ( $this->is_gravityforms_supported( '2.3.4.2' ) ) {
					remove_filter( 'gform_entry_pre_handle_confirmation', array( $this, 'after_submission' ), 9 );
				} elseif ( $this->is_gravityforms_supported( '2.3.3.10' ) ) {
					remove_action( 'gform_pre_handle_confirmation', array( $this, 'after_submission' ), 9 );
				} else {
					remove_action( 'gform_after_submission', array( $this, 'after_submission' ), 9 );
				}
			} else {
				gform_update_meta( $entry['id'], "{$this->_slug}_is_fulfilled", true );
			}
		}

		/**
		 * Starts the workflow if it was delayed pending PayPal payment.
		 *
		 * @since unknown
		 * @since 2.5.8 Updated to use action_trigger_payment_delayed_feeds().
		 *
		 * @param array  $entry          The entry for which the PayPal payment has been completed.
		 * @param array  $paypal_config  The PayPal feed used to process the entry.
		 * @param string $transaction_id The PayPal transaction ID.
		 * @param float  $amount         The transaction amount.
		 *
		 * @return void
		 */
		public function paypal_fulfillment( $entry, $paypal_config, $transaction_id, $amount ) {
			$this->action_trigger_payment_delayed_feeds( $transaction_id, $paypal_config, $entry );
		}

		/**
		 * Starts the workflow if it was delayed pending payment by a GFPaymentAddOn.
		 *
		 * @since 2.5.8
		 *
		 * @param string     $transaction_id The transaction or subscription ID.
		 * @param array      $payment_feed   The payment feed which originated the transaction.
		 * @param array      $entry          The entry currently being processed.
		 * @param null|array $form           The form currently being processed or null for the legacy PayPal integration.
		 */
		public function action_trigger_payment_delayed_feeds( $transaction_id, $payment_feed, $entry, $form = null ) {
			$this->log_debug( __METHOD__ . '(): Checking fulfillment for transaction ' . $transaction_id . ' for ' . $payment_feed['addon_slug'] );

			if ( empty( $entry['workflow_step'] ) && $this->is_delayed( $payment_feed ) && ! $this->is_entry_view() ) {
				if ( is_null( $form ) ) {
					$form = GFFormsModel::get_form_meta( $entry['form_id'] );
				}
				$entry_id = absint( $entry['id'] );
				$this->process_workflow( $form, $entry_id );
			} else {
				$this->log_debug( __METHOD__ . '(): Entry ' . $entry['id'] . ' is already fulfilled or workflow is not delayed. No action necessary.' );
			}
		}

		/**
		 * Target for the gform_after_submission hook.
		 * Triggers workflow processing on completion of the form submission.
		 *
		 * @param array $entry The current entry.
		 * @param array $form  The current form.
		 *
		 * @return array|WP_Error
		 */
		public function after_submission( $entry, $form ) {
			if ( ! isset( $entry['id'] ) || $entry['status'] === 'spam' ) {
				return $entry;
			}

			if ( isset( $entry['workflow_step'] ) ) {
				$entry_id = absint( $entry['id'] );
				$this->process_workflow( $form, $entry_id );
				$entry = GFAPI::get_entry( $entry_id );
			}

			return $entry;
		}

		/**
		 * Target for the gform_after_update_entry hook.
		 * Triggers workflow processing on entry update.
		 *
		 * @param array $form     The current form.
		 * @param int   $entry_id The entry ID.
		 */
		public function filter_after_update_entry( $form, $entry_id ) {
			$entry = GFAPI::get_entry( $entry_id );
			if ( ! is_wp_error( $entry ) && isset( $entry['workflow_final_status'] ) && $entry['workflow_final_status'] == 'pending' ) {
				$this->process_workflow( $form, $entry_id );
			}
		}

		/**
		 * Target for the gform_form_settings_menu hook.
		 * Updated workflow icon.
		 *
		 * @since 2.5.13
		 *
		 * @param array $menu_items The form settings menu items.
		 *
		 * @return array
		 */
		function filter_form_settings_menu( $menu_items ) {
			foreach ( $menu_items as &$menu_item ) {
				if ( $menu_item['name'] === 'gravityflow' ) {
					$menu_item['icon'] = 'dashicons-gravityflow-icon';
				}
			}

			return $menu_items;
		}

        /**
		 * Target for the gform_form_settings_menu hook.
		 * Set default icon for extensions.
		 *
		 * @since 2.7.5
		 *
		 * @param array $menu_items The form settings menu items.
		 *
		 * @return array
		 */
		function filter_extension_form_settings_menu( $menu_items ) {
			foreach ( $menu_items as &$menu_item ) {
				if ( empty( $menu_item['icon'] ) && strpos( $menu_item['name'], 'gravityflow' ) === 0 ) {
					$menu_item['icon'] = 'dashicons-gravityflow-icon';
				}
			}
			return $menu_items;
		}

		/**
		 * Add inbox notification count to Workflow Menu.
		 *
		 * @since 2.5.12
		 *
		 * @param array $menu The current WP Dashboard Menu.
		 */
		public function show_inbox_count( $menu ) {

			$app_settings = $this->get_app_settings();

			if ( ! rgar( $app_settings, 'workflow_inbox_count' ) ) {
				return $menu;
			}

			$custom_labels = get_option( 'gravityflow_app_settings_labels', array() );
			$custom_navigation_labels = rgar( $custom_labels, 'navigation' );
			$custom_workflow_label = rgar( $custom_navigation_labels, 'workflow' );
			$workflow_label = $custom_workflow_label ? $custom_workflow_label : 'Workflow';

			$workflow_menu_pos = -1;
			foreach ( $menu as $menuitem ) {
				if ( $menuitem[0] == $workflow_label ) {
					$workflow_menu_pos = array_search( $menuitem, $menu, true );
				}
			}

			$pending_count = $this->get_inbox_count();
			$menu[ $workflow_menu_pos ][0] = sprintf( __( '%s %s' ), $workflow_label, "<span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . "</span></span>" );

			return $menu;
		}

		/**
		 * Starts or resumes workflow processing.
		 *
		 * @param array $form     The current form.
		 * @param int   $entry_id The entry ID.
		 */
		public function process_workflow( $form, $entry_id ) {

			$entry = GFAPI::get_entry( $entry_id );
			if ( ! is_wp_error( $entry ) && isset( $entry['workflow_step'] ) ) {

				$this->log_debug( __METHOD__ . '() - processing. entry id ' . $entry_id );

				$step_id          = $entry['workflow_step'];
				$starting_step_id = $step_id;

				$partial_entry_pending_start = false;

				if ( empty( $step_id ) && ( empty( $entry['workflow_final_status'] ) || $entry['workflow_final_status'] == 'pending') ) {
					$this->log_debug( __METHOD__ . '() - not yet started workflow. starting.' );
					// Starting workflow.
					$form_id = absint( $form['id'] );
					$step = $this->get_first_step( $form_id, $entry );
					$this->log_event( 'workflow', 'started', $form['id'], $entry_id );
					if ( $step ) {
						$step->start();
						$this->log_debug( __METHOD__ . '() - started.' );
					} elseif ( ! empty( $entry['partial_entry_id'] ) && $this->get_workflow_start_step( $form_id, $entry ) ) {
						$partial_entry_pending_start = true;
						$this->log_debug( __METHOD__ . '() - start condition not met.' );
					} else {
						$this->log_debug( __METHOD__ . '() - no first step.' );
					}
				} else {
					$this->log_debug( __METHOD__ . '() - resuming workflow.' );
					$step = $this->get_step( $step_id, $entry );
				}

				$step_complete = false;

				if ( $step ) {
					$step_id = $step->get_id();
					$step_complete = $step->end_if_complete();
					$this->log_debug( __METHOD__ . '() - step ' . $step_id . ' complete: ' . ( $step_complete ? 'yes' : 'no' ) );
				}

				while ( $step_complete && $step ) {

					$this->log_debug( __METHOD__ . '() - getting next step.' );

					// Refresh the entry before getting the next step.
					$entry         = GFAPI::get_entry( $entry_id );
					$step          = $this->get_next_step( $step, $entry, $form );
					$step_complete = false;

					if ( $step ) {
						$step_id       = $step->get_id();
						$step_complete = $step->start();
						if ( $step_complete ) {
							$step->end();
						}
					}
					$entry['workflow_step'] = $step_id;
				}

				if ( ! $partial_entry_pending_start ) {
					if ( $step == false ) {
						$this->log_debug( __METHOD__ . '() - ending workflow.' );
						gform_delete_meta( $entry_id, 'workflow_step' );

						$final_status = gform_get_meta( $entry_id, 'workflow_current_status' );
						if ( empty( $final_status ) || $final_status == 'pending' ) {
							$final_status = 'complete';
						}

						gform_delete_meta( $entry_id, 'workflow_current_status' );
						gform_update_meta( $entry_id, 'workflow_final_status', $final_status );

						$entry_created_timestamp = strtotime( $entry['date_created'] );
						$duration                = time() - $entry_created_timestamp;
						$this->log_event( 'workflow', 'ended', $form['id'], $entry_id, $final_status, 0, $duration );

						do_action( 'gravityflow_workflow_complete', $entry_id, $form, $final_status );

						// Refresh entry after action.
						$entry = GFAPI::get_entry( $entry_id );
						GFAPI::send_notifications( $form, $entry, 'workflow_complete' );
					} else {
						$this->log_debug( __METHOD__ . '() - not ending workflow.' );
						$step_id = $step->get_id();
						gform_update_meta( $entry_id, 'workflow_step', $step_id );
					}
                }

				do_action( 'gravityflow_post_process_workflow', $form, $entry_id, $step_id, $starting_step_id );
			}
		}

		/**
		 * Returns the first active step which meets its conditional logic (if configured).
		 *
		 * @param int   $form_id The current form ID.
		 * @param array $entry   The current entry.
		 *
		 * @return bool|Gravity_Flow_Step
		 */
		public function get_first_step( $form_id, $entry ) {
			$form  = GFAPI::get_form( $form_id );
			$steps = $this->get_steps( $form_id, $entry );
			foreach ( $steps as $step ) {
				if ( $step->get_type() == 'workflow_start' && $step->is_active() ) {
					if ( $step->is_condition_met( $form ) ) {
						return $step;
					} else {
						break;
					}
				}
				if ( $step->is_active() && $step->is_condition_met( $form ) ) {
					return $step;
				}
			}

			return false;
		}

		/**
		 * Adds the gravityflow shortcode.
		 *
		 * @param array       $atts    The shortcode attributes.
		 * @param null|string $content The shortcode content.
		 *
		 * @return string
		 */
		public function shortcode( $atts, $content = null ) {

			if ( get_post_type() != 'page' ) {
				return '';
			}

			$a = $this->get_shortcode_atts( $atts );

			if ( $a['display_all'] || $a['allow_anonymous'] || $a['fields'] ) {

				$app_settings = $this->get_app_settings();

				if ( $a['display_all'] && ! rgar( $app_settings, 'allow_display_all_attribute' ) && ! GFAPI::current_user_can_any( 'gravityflow_status_view_all' ) ) {

					$a['display_all'] = false;
				}

				if ( $a['allow_anonymous'] && ! rgar( $app_settings, 'allow_allow_anonymous_attribute' ) ) {

					$a['allow_anonymous'] = false;
				}

				if ( $a['fields'] && ! rgar( $app_settings, 'allow_field_ids' ) ) {

					$a['fields'] = array();
				}

			}

			if ( ! $a['allow_anonymous'] && ! is_user_logged_in() ) {
				$token = $this->decode_access_token();
				if ( ! $token ) {
					return;
				}
			}

			$entry_id = absint( rgget( 'lid' ) );

			if ( empty( $entry_id ) && ! empty( $a['entry_id'] ) ) {
				$entry_id = absint( $a['entry_id'] );
			}

			if ( ! empty( $a['form'] ) && ! empty( $entry_id ) ) {
				// Limited support for multiple shortcodes on the same page.
				$form_id = $a['form'] ? explode( ',', $a['form'] ) : '';
				if ( is_array( $form_id ) && count( $form_id ) === 1 ) {
					$form_id = $form_id[0];
				}
				$entry = GFAPI::get_entry( $entry_id );
				if ( is_wp_error( $entry ) || ( is_array( $form_id ) && ! in_array( $entry['form_id'], $form_id ) ) || ( ! is_array( $form_id ) && $entry['form_id'] !== $a['form'] ) ) {
					return;
				}
			}

			$html = '';

			if ( ! empty( $a['title'] ) ) {
				$html .= sprintf( '<h3>%s</h3>', $a['title'] );
			}

			switch ( $a['page'] ) {
				case 'inbox':
					$html .= $this->get_shortcode_inbox_page( $a );
					break;
				case 'submit':
					$form_ids = $a['forms'] ? explode( ',', $a['forms'] ) : '';
					ob_start();
					$this->submit_page( false, $form_ids );
					$html .= ob_get_clean();
					break;
				case 'status':
					wp_enqueue_script( 'gravityflow_entry_detail' );
					wp_enqueue_script( 'gravityflow_status_list' );

					if ( rgget( 'view' ) || ! empty( $entry_id ) ) {
						$html .= $this->get_shortcode_status_page_detail( $a );
					} elseif ( is_user_logged_in() || ( $a['display_all'] && $a['allow_anonymous'] ) ) {
						$html .= $this->get_shortcode_status_page( $a );
					}
					break;
				case 'reports':
					$html .= $this->get_shortcode_reports_page( $a );
					break;
			}

			/**
			 * Allows the gravityflow shortcode to be modified and supports custom pages.
			 *
			 * @param string $html    The HTML.
			 * @param array  $atts    The original shortcode attributes.
			 * @param string $content The content inside the shortcode block.
			 */
			$html = apply_filters( 'gravityflow_shortcode_' . $a['page'], $html, $atts, $content );

			return $html;

		}

		/**
		 * Get the shortcode attributes, after merging with the defaults.
		 *
		 * @param array $atts The attributes from the shortcode.
		 *
		 * @return array
		 */
		public function get_shortcode_atts( $atts ) {
			$a = shortcode_atts( $this->get_shortcode_defaults(), $atts );

			if ( $a['form_id'] > 0 ) {
				$a['form'] = $a['form_id'];
			}

			$a['title'] = sanitize_text_field( $a['title'] );
			$a          = $this->booleanize_shortcode_attributes( $a );

			if ( is_null( $a['display_all'] ) ) {
				$a['display_all'] = GFAPI::current_user_can_any( 'gravityflow_status_view_all' );
				$this->log_debug( __METHOD__ . '() - display_all set by capabilities: ' . $a['display_all'] );
			} else {
				$a['display_all'] = strtolower( $a['display_all'] ) == 'true' ? true : false;
				$this->log_debug( __METHOD__ . '() - display_all overridden: ' . $a['display_all'] );
			}

			return $a;
		}

		/**
		 * The default attributes for the gravityflow shortcode.
		 *
		 * @return array
		 */

		public function get_shortcode_defaults() {
			$defaults = array(
				'page'             => 'inbox',
				'form'             => null,
				'forms'            => null,
				'form_id'          => null,
				'entry_id'         => null,
				'fields'           => array(),
				'display_all'      => null,
				'actions_column'   => false,
				'allow_anonymous'  => false,
				'title'            => '',
				'id_column'        => true,
				'submitter_column' => true,
				'step_column'      => true,
				'status_column'    => true,
				'timeline'         => true,
				'last_updated'     => false,
				'step_status'      => true,
				'workflow_info'    => true,
				'sidebar'          => true,
				'step_highlight'   => true,
				'back_link'        => false,
				'back_link_text'   => __( 'Return to list', 'gravityflow' ),
				'back_link_url'    => null,
				'context_key'      => '',
				'due_date'         => false,
				'range'            => '',
				'category'         => '',
				'step_id'          => null,
				'assignee'         => '',
				'display_filter'   => true,
				'is_block'         => false,
				'is_shortcode'     => false,
				'legacy'           => false,
			);

			return $defaults;
		}

		/**
		 * Converts the string attribute values to booleans.
		 *
		 * @param array $a The shortcode attributes.
		 *
		 * @return array
		 */
		public function booleanize_shortcode_attributes( $a ) {
			$attributes = $this->get_shortcode_defaults();

			foreach ( $attributes as $attribute => $default ) {
				if ( ! isset( $a[ $attribute ] ) ) {
					$a[ $attribute ] = $default;
					continue;
				}

				if ( $default === true ) {
					$a[ $attribute ] = strtolower( $a[ $attribute ] ) == 'false' ? false : true;
				} elseif ( $default === false ) {
					$a[ $attribute ] = strtolower( $a[ $attribute ] ) == 'true' ? true : false;
				}
			}

			return $a;
		}

		/**
		 * Get the HTML for the inbox page shortcode.
		 *
		 * @param array $a The shortcode attributes.
		 *
		 * @return string
		 */
		public function get_shortcode_inbox_page( $a ) {
			wp_enqueue_script( 'gravityflow_entry_detail' );
			wp_enqueue_script( 'gravityflow_status_list' );
			$args = array(
				'form_id'          => $a['form'],
				'entry_id'         => $a['entry_id'],
				'id_column'        => $a['id_column'],
				'submitter_column' => $a['submitter_column'],
				'step_column'      => $a['step_column'],
				'actions_column'   => $a['actions_column'],
				'show_header'      => false,
				'field_ids'        => $a['fields'] ? explode( ',', $a['fields'] ) : '',
				'detail_base_url'  => add_query_arg( array( 'page' => 'gravityflow-inbox', 'view' => 'entry' ) ),
				'timeline'         => $a['timeline'],
				'last_updated'     => $a['last_updated'],
				'step_status'      => $a['step_status'],
				'workflow_info'    => $a['workflow_info'],
				'sidebar'          => $a['sidebar'],
				'step_highlight'   => $a['step_highlight'],
				'back_link'        => $a['back_link'],
				'back_link_text'   => $a['back_link_text'],
				'back_link_url'    => $a['back_link_url'],
				'context_key'      => $a['context_key'],
				'due_date'         => $a['due_date'],
				'is_shortcode'     => ! $a['is_block'],
				'is_block'         => $a['is_block'],
				'legacy'           => $a['legacy'],
			);

			/**
			 * @var Task $tasks
			 */
			$tasks = $this->container->get( Inbox_Service_Provider::TASK_MODEL );
			$tasks->add_args_for_shortcode( $args );

			ob_start();
			$this->inbox_page( $args );
			$html = ob_get_clean();

			return $html;
		}

		/**
		 * Get the HTML for the status page shortcode, detail view.
		 *
		 * @param array $a The shortcode attributes.
		 *
		 * @return string
		 */
		public function get_shortcode_status_page_detail( $a ) {
			ob_start();
			$check_permissions = true;

			if ( $a['allow_anonymous'] || $a['display_all'] ) {
				$check_permissions = false;
			}

			$args = array(
				'entry_id'          => $a['entry_id'],
				'show_header'       => false,
				'detail_base_url'   => add_query_arg( array( 'page' => 'gravityflow-inbox', 'view' => 'entry' ) ),
				'check_permissions' => $check_permissions,
				'timeline'          => $a['timeline'],
				'sidebar'           => $a['sidebar'],
				'workflow_info'     => $a['workflow_info'],
				'step_status'       => $a['step_status'],
				'context_key'       => $a['context_key'],
				'back_link'         => $a['back_link'],
				'back_link_text'    => $a['back_link_text'],
				'back_link_url'     => $a['back_link_url'],
			);

			if ( is_null( $args['back_link_url' ] ) ) {
				$args['back_link_url' ] = remove_query_arg( array( 'gworkflow_token', 'new_status', 'view', 'lid', 'id', 'page' ) );
			}

			$this->inbox_page( $args );
			$html = ob_get_clean();

			return $html;
		}

		/**
		 * Get the HTML for the status page shortcode, list view.
		 *
		 * @param array $a The shortcode attributes.
		 *
		 * @return string
		 */
		public function get_shortcode_status_page( $a ) {
			require_once( ABSPATH . 'wp-admin/includes/screen.php' );
			require_once( ABSPATH . 'wp-admin/includes/template.php' );
			ob_start();

			$args = array(
				'base_url'         => remove_query_arg( array(
					'entry-id',
					'form-id',
					'start-date',
					'end-date',
					'_wpnonce',
					'_wp_http_referer',
					'action',
					'action2',
					'o',
					'f',
					't',
					'v',
					'gravityflow-print-page-break',
					'gravityflow-print-timelines',
				) ),
				'detail_base_url'  => add_query_arg( array( 'page' => 'gravityflow-inbox', 'view' => 'entry' ) ),
				'display_header'   => false,
				'action_url'       => 'http' . ( isset( $_SERVER['HTTPS'] ) ? 's' : '' ) . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}?",
				'field_ids'        => $a['fields'] ? explode( ',', $a['fields'] ) : '',
				'display_all'      => $a['display_all'],
				'id_column'        => $a['id_column'],
				'submitter_column' => $a['submitter_column'],
				'step_column'      => $a['step_column'],
				'status_column'    => $a['status_column'],
				'last_updated'     => $a['last_updated'],
				'step_status'      => $a['step_status'],
				'workflow_info'    => $a['workflow_info'],
				'sidebar'          => $a['sidebar'],
				'context_key'      => $a['context_key'],
				'due_date'         => $a['due_date'],
				'legacy'           => $a['legacy'],
			);

			if ( isset( $a['form'] ) ) {
			    $form_id = $a['form'] ? explode( ',', $a['form'] ) : '';
			    if ( is_array( $form_id ) && count( $form_id ) === 1 ) {
				    $form_id = $form_id[0];
                }
				$args['constraint_filters'] = array(
					'form_id' => $form_id,
				);
			}

			if ( ! is_user_logged_in() && $a['allow_anonymous'] ) {
				$args['bulk_actions'] = array();
			}

			$this->status_page( $args );
			$html = ob_get_clean();

			return $html;
		}

		/**
		 * Get the HTML for the reports page shortcode.
		 *
		 * @since 2.5.9
		 *
		 * @param array $a The shortcode attributes.
		 *
		 * @return string
		 */
		public function get_shortcode_reports_page( $a ) {

			wp_enqueue_script( 'google_charts', 'https://www.gstatic.com/charts/loader.js',  array(), $this->_version );
			wp_enqueue_script( 'gravityflow_reports', $this->get_base_url() . "/js/reports{$this->min()}.js",  array( 'jquery', 'google_charts' ), $this->_version );

			$app_settings  = $this->get_app_settings();
			$allow_reports = rgar( $app_settings, 'allow_display_reports' );

			$args = array(
				'display_header'        => false,
				'base_url'              => remove_query_arg( array(
					'page',
					'range',
					'form-id',
					'category',
					'step-id',
					'assignee',
				) ),
				'form_id'               => $a['form'],
				'range'                 => $a['range'],
				'category'              => $a['category'],
				'step_id'               => $a['step_id'],
				'assignee'              => $a['assignee'],
				'display_filter'        => $a['display_filter'],
				'check_permissions'     => ! $allow_reports,
			);

			ob_start();
			$this->reports_page( $args );
			$html = ob_get_clean();

			return $html;
		}

		/**
		 * Checks if a particular user has a role.
		 * Returns true if a match was found.
		 *
		 * @param string $role    Role name.
		 * @param int    $user_id (Optional) The ID of a user. Defaults to the current user.
		 *
		 * @return bool
		 */
		public function check_user_role( $role, $user_id = null ) {

			return in_array( $role, $this->get_user_roles( $user_id ) );
		}

		/**
		 * Get the roles for the current or specified user.
		 *
		 * @param null|int $user_id (Optional) The ID of a user. Defaults to the current user.
		 *
		 * @return array
		 */
		public function get_user_roles( $user_id = null ) {

			if ( is_numeric( $user_id ) ) {
				$user = get_userdata( $user_id );
			} else {
				$user = wp_get_current_user();
			}

			if ( empty( $user ) ) {
				return array();
			}

			return (array) $user->roles;
		}

		/**
		 * Return the inbox entries count from transient.
		 *
		 * @since 2.5.12
		 *
		 * @return int
		 */
		public function get_inbox_count() {
			$count_value = get_transient( 'gflow_inbox_count_' . get_current_user_id()  );
			if ( $count_value === false ) {
				$count_value = Gravity_Flow_API::get_inbox_entries_count();
				set_transient( 'gflow_inbox_count_' . get_current_user_id() , $count_value, MINUTE_IN_SECONDS );
			}

			return $count_value;
		}

		/**
		 * Displays the support page.
		 */
		public function support() {
			if ( $this->maybe_display_installation_wizard() ) {
				return;
			}

			require_once( $this->get_base_path() . '/includes/pages/class-support.php' );
			Gravity_Flow_Support::display();
		}

		/**
		 * Renders the app settings page.
		 */
		public function app_tab_page() {
			if ( $this->maybe_display_installation_wizard() ) {
				return;
			}
			parent::app_tab_page();
		}

		/**
		 * Returns the specified app setting.
		 *
		 * @since 2.3.4
		 * @since 1.4.3-dev
		 *
		 * @param string $setting_name The app setting to be returned.
		 * @param null|string $default The default value to be returned when the setting does not have a value.
		 *
		 * @return mixed|string
		 */
		public function get_app_setting( $setting_name, $default = null ) {
			$setting = parent::get_app_setting( $setting_name );

			if ( ! empty( $setting ) ) {
				return $setting;
			}

			// If a default page hasn't been configured use the admin page.
			if ( in_array( $setting_name, array( 'inbox_page', 'status_page', 'submit_page' ) ) ) {
				return 'admin';
			}

			return $default;
		}

		/**
		 * Returns the currently saved plugin settings.
		 *
		 * @return array
		 */
		public function get_app_settings() {
			return parent::get_app_settings();
		}

		/**
		 * Updates the app settings with the provided settings.
		 *
		 * @param array $settings The settings to be saved.
		 */
		public function update_app_settings( $settings ) {
			if ( $this->is_save_postback() ) {
				$previous_settings = $this->get_previous_settings();
				$pages             = array( 'inbox_page', 'status_page', 'submit_page' );

				foreach ( $pages as $page ) {
					$this->maybe_update_page_content( $page, $settings, $previous_settings );
				}

				if ( $settings['background_updates'] != $previous_settings['background_updates'] ) {
					$this->update_wp_auto_updates( $settings['background_updates'] );
				}
			}

			parent::update_app_settings( $settings );
		}

		/**
		 * If a new page has been selected ensure it contains the gravityflow shortcode.
		 *
		 * @since 1.4.3-beta
		 *
		 * @param string $page              The setting currently being processed; inbox_page, status_page, or submit_page.
		 * @param array  $settings          The valid settings to be saved.
		 * @param array  $previous_settings The previous settings.
		 */
		public function maybe_update_page_content( $page, $settings, $previous_settings ) {
			$new_setting = rgar( $settings, $page );

			if ( ! $new_setting || $new_setting == rgar( $previous_settings, $page ) ) {
				return;
			}

			$post = get_post( $new_setting );

			if ( ! $post || stripos( $post->post_content, '[gravityflow' ) !== false ) {
				return;
			}

			if ( ! empty( $post->post_content ) ) {
				$post->post_content .= "\n";
			}

			$post->post_content .= sprintf( '[gravityflow page="%s"]', str_replace( '_page', '', $page ) );

			wp_update_post( $post );
		}

		/**
		 * Target for the auto_update_plugin hook.
		 *
		 * Enables the plugin to update automatically, if enabled.
		 *
		 * @param bool   $update Whether to update.
		 * @param object $item   The update offer.
		 *
		 * @return bool
		 */
		public function maybe_auto_update( $update, $item ) {
			if ( ! isset( $item->slug ) || $item->slug !== 'gravityflow-gravityflow' || is_null( $update ) ) {
				return $update;
			}

			if ( $this->is_auto_update_disabled( $update ) ) {
				$this->log_debug( __METHOD__ . '() - Aborting; auto updates disabled.' );

				return false;
			}


			if ( ! $this->should_update_to_version( $item->new_version ) ) {
				$this->log_debug( __METHOD__ . sprintf( '() - Aborting; auto update from %s to %s is not supported.', $this->_version, $item->new_version ) );

				return false;
			}

			$this->log_debug( __METHOD__ . sprintf( '() - OK to update from %s to %s.', $this->_version, $item->new_version ) );

			return true;
		}

		/**
		 * Determines if background automatic updates are disabled.
		 *
		 * @since 2.6 Added the enabled param.
		 *
		 * @param bool|null $enabled Indicates if auto updates are enabled.
		 *
		 * @return bool
		 */
		public function is_auto_update_disabled( $enabled = null ) {
			global $wp_version;

			if ( is_null( $enabled ) || version_compare( $wp_version, '5.5', '<' ) ) {
				$enabled = $this->get_app_setting( 'background_updates' );
			}

			$this->log_debug( __METHOD__ . ' - $enabled: ' . var_export( $enabled, true ) );

			$disabled = apply_filters( 'gravityflow_disable_auto_update', ! $enabled );
			$this->log_debug( __METHOD__ . '() - $disabled: ' . var_export( $disabled, true ) );

			if ( ! $disabled ) {
				$disabled = defined( 'GRAVITYFLOW_DISABLE_AUTO_UPDATE' ) && GRAVITYFLOW_DISABLE_AUTO_UPDATE;
				$this->log_debug( __METHOD__ . '() - GRAVITYFLOW_DISABLE_AUTO_UPDATE: ' . var_export( $disabled, true ) );
			}

			return $disabled;
		}

		/**
		 * Determines if the current version should update to the offered version.
		 *
		 * @since 2.6
		 *
		 * @param string $offered_ver The version number to be compared against the installed version number.
		 *
		 * @return bool
		 */
		public function should_update_to_version( $offered_ver ) {
			if ( version_compare( $this->_version, $offered_ver, '>=' ) ) {
				return false;
			}

			/**
			 * If major version updates are allowed we don't need to compare the branch version numbers.
			 *
			 * @since 2.6
			 *
			 * @param bool $allowed Indicates if Gravity Flow should update to major versions automatically. Default is true.
			 */
			if ( apply_filters( 'gravityflow_major_version_auto_updates_allowed', true ) ) {
				return true;
			}

			$current_branch = implode( '.', array_slice( preg_split( '/[.-]/', $this->_version ), 0, 2 ) );
			$new_branch     = implode( '.', array_slice( preg_split( '/[.-]/', $offered_ver ), 0, 2 ) );

			return $current_branch == $new_branch;
		}

		/**
		 * Removes the settings from the database and clears the cron job.
		 */
		public function uninstall() {

			require_once( $this->get_base_path() . '/includes/wizard/class-installation-wizard.php' );
			$wizard = new Gravity_Flow_Installation_Wizard;
			$wizard->flush_values();

			wp_clear_scheduled_hook( 'gravityflow_cron' );

			$this->uninstall_db();

			parent::uninstall();
		}

		/**
		 * Removes the activity table on uninstall.
		 */
		private function uninstall_db() {

			global $wpdb;
			$table = Gravity_Flow_Activity::get_activity_log_table_name();
			$wpdb->query( "DROP TABLE IF EXISTS $table" );

		}

		/**
		 * Add a step note to the specified entry.
		 *
		 * @param int         $entry_id  The ID of the entry the note is to be added to.
		 * @param string      $note      The note to be added.
		 * @param bool|int    $user_id   The user ID or false.
		 * @param bool|string $user_name The user name or step type.
		 */
		public function add_timeline_note( $entry_id, $note, $user_id = false, $user_name = 'gravityflow' ) {
			$assignee_key = $this->get_current_user_assignee_key();
			if ( $assignee_key ) {
				$assignee = Gravity_Flow_Assignees::create( $assignee_key );
				if ( $assignee->get_type() === 'user_id' ) {
					$user_id   = $assignee->get_id();
					$user_name = $assignee->get_display_name();
				}
			}

			/**
			* Allows the timeline note to be customized.
			*
			* @since 2.5.7
			*
			* @param string                 $note           The message to be added to the timeline.
			* @param int                    $entry_id       The entry of the current step.
			* @param bool|int               $user_id        The ID of user performing the action.
			* @param string                 $user_name      The username of user performing the action.
			* @param bool|Gravity_Flow_Step $step           If it is a step based action the current step.
			*
			* @return bool|string
			*/

			$note = apply_filters( 'gravityflow_timeline_note_add', $note, $entry_id, $user_id, $user_name, false );

			if ( $note ) {
				GFFormsModel::add_note( $entry_id, $user_id, $user_name, $note, 'gravityflow' );
			}
		}

		/**
		 * Target for the gform_export_form hook.
		 *
		 * Adds the form feeds to form object before export.
		 *
		 * @param array $form The form to be exported.
		 *
		 * @return array
		 */
		public function filter_gform_export_form( $form ) {

			$feeds = $this->get_feeds( $form['id'] );

			if ( ! isset( $form['feeds'] ) ) {
				$form['feeds'] = array();
			}

			$form['feeds']['gravityflow'] = $feeds;
			return $form;
		}

		/**
		 * Target for the gform_forms_post_import hook.
		 *
		 * Imports the feeds for the newly imported forms.
		 *
		 * @param array $forms The imported forms.
		 */
		public function action_gform_forms_post_import( $forms ) {
			$gravityflow_feeds_imported = false;
			foreach ( $forms as $import_form ) {

				// Ensure the imported form is the latest. Compensates for a bug in Gravity Forms < 2.1.1.13.
				$form = GFAPI::get_form( $import_form['id'] );

				if ( isset( $form['feeds']['gravityflow'] ) ) {
					$this->import_gravityflow_feeds( $form['feeds']['gravityflow'], $form['id'] );
					$gravityflow_feeds_imported = ! empty( $form['feeds']['gravityflow'] ) ? true : $gravityflow_feeds_imported;
					unset( $form['feeds']['gravityflow'] );
					if ( empty( $form['feeds'] ) ) {
						unset( $form['feeds'] );
					}
					GFAPI::update_form( $form );
				}
			}

			if ( $gravityflow_feeds_imported ) {
				GFCommon::add_message( esc_html__( 'Gravity Flow Steps imported. IMPORTANT: Check the assignees for each step. If the form was imported from a different installation with different user IDs then steps may need to be reassigned.', 'gravityflow' ) );
			}
		}

		/**
		 * Target of the admin_enqueue_scripts hook.
		 *
		 * Triggers enqueuing of the form scripts for the workflow detail page.
		 */
		public function action_admin_enqueue_scripts() {
			$this->maybe_enqueue_form_scripts();

			// Enqueue admin CSS and JS
			wp_enqueue_style( self::ADMIN_COMPONENTS_CSS,  $this->get_base_url() . "/assets/css/dist/admin-components{$this->min()}.css", null, $this->_version );
			wp_enqueue_style( self::ADMIN_CSS,  $this->get_base_url() . "/assets/css/dist/admin{$this->min()}.css", null, $this->_version );
			wp_enqueue_script( self::VENDOR_JS_ADMIN, $this->get_base_url() . "/assets/js/dist/vendor-admin{$this->min()}.js", array(), $this->_version, true );
			wp_enqueue_script( self::ADMIN_JS, $this->get_base_url() . "/assets/js/dist/scripts-admin{$this->min()}.js", array( self::VENDOR_JS_ADMIN ), $this->_version, true );

			/**
			 * Allows additional scripts to be enqueued when others are loaded in the admin.
			 *
			 * @since 2.8.1
			 */
			do_action( 'gravityflow_enqueue_admin_scripts' );
		}

		/**
		 * Triggers enqueuing of the form scripts for the workflow detail page.
		 */
		public function maybe_enqueue_form_scripts() {
			if ( $this->is_workflow_detail_page() ) {
				$this->enqueue_form_scripts();
			}
		}

		/**
		 * Enqueues the scripts for the current form.
		 */
		public function enqueue_form_scripts() {
			$form = $this->get_current_form();

			if ( empty( $form ) ) {
				return;
			}
			require_once( GFCommon::get_base_path() . '/form_display.php' );

			if ( $this->has_enhanced_dropdown( $form ) ) {
				if ( wp_script_is( 'chosen', 'registered' ) ) {
					wp_enqueue_script( 'chosen' );
				} else {
					wp_enqueue_script( 'gform_chosen' );
				}
			}

			GFFormDisplay::enqueue_form_scripts( $form );
		}

		/**
		 * Determines if the current location is the workflow detail page.
		 *
		 * @return bool
		 */
		public function is_workflow_detail_page() {
			$id  = rgget( 'id' );
			$lid = rgget( 'lid' );
			return rgget( 'page' ) == 'gravityflow-inbox' && rgget( 'view' ) == 'entry' && ! empty( $id ) && ! empty( $lid );
		}

		/**
		 * Returns an array of active form IDs which have workflows.
		 *
		 * @return array
		 */
		public function get_workflow_form_ids() {
			if ( isset( $this->form_ids ) ) {
				return $this->form_ids;
			}
			$forms = GFFormsModel::get_forms( true );
			$form_ids = array();
			foreach ( $forms as $form ) {
				$form_id = absint( $form->id );
				$feeds = gravity_flow()->get_feeds( $form_id );
				if ( ! empty( $feeds ) ) {
					$form_ids[] = $form_id;
				}
			}
			$this->form_ids = $form_ids;
			return $this->form_ids;
		}

		/**
		 * Target for the gravityflow_cron filter.
		 *
		 * The cron job which will trigger processing of scheduled and expired steps, and reminder emails.
		 */
		public function cron() {
			$this->log_debug( __METHOD__ . '() Starting cron.' );

			if ( method_exists( 'GF_Upgrade', 'get_submissions_block' ) && gf_upgrade()->get_submissions_block() ) {
				$this->log_debug( __METHOD__ . '(): submissions are blocked because an upgrade of Gravity Forms is in progress' );
				return;
			}

			$this->maybe_process_queued_entries();
			$this->maybe_process_expiration_and_reminders();

			$this->log_debug( __METHOD__ . '() Finished cron.' );
		}

		/**
		 * Triggers processing of scheduled steps.
		 */
		public function maybe_process_queued_entries() {

			$this->log_debug( __METHOD__ . '(): starting' );

			$form_ids = $this->get_workflow_form_ids();

			if ( empty( $form_ids ) ) {
				return;
			}

			global $wpdb;

			$entry_table = Gravity_Flow_Common::get_entry_table_name();
			$meta_table = Gravity_Flow_Common::get_entry_meta_table_name();
			$entry_id_column = Gravity_Flow_Common::get_entry_id_column_name();

			$sql = "
SELECT l.id, l.form_id
FROM $entry_table l
INNER JOIN $meta_table m
ON l.id = m.{$entry_id_column}
AND l.status='active'
AND m.meta_key LIKE 'workflow_step_status_%'
AND m.meta_value='queued'";

			$results = $wpdb->get_results( $sql );

			if ( empty( $results ) || is_wp_error( $results ) ) {
				return;
			}

			$this->log_debug( __METHOD__ . '() Queued entries: ' . print_r( $results, true ) );

			foreach ( $results as $result ) {
				$form = GFAPI::get_form( $result->form_id );

				if ( ! $form ) {
					continue;
				}

				if ( ! $form['is_active'] ) {
					continue;
				}

				$entry = GFAPI::get_entry( $result->id );
				$step = $this->get_current_step( $form, $entry );
				if ( $step && ! $step->is_active() ) {
					continue;
				}
				if ( $step && $step->is_queued() ) {
					$complete = $step->start();
					if ( $complete ) {
						$this->process_workflow( $form, $entry['id'] );
					} else {
						$this->log_debug( __METHOD__ . '() queued entry started step but step is not complete: ' . $entry['id'] );
					}
				} else {
					$this->log_debug( __METHOD__ . '() queued entry not on a queued step: ' . $entry['id'] );
				}
			}
		}

		/**
		 * Expire entries that need to be expired and send pending reminder emails.
		 *
		 * @since 1.5.1 Added support for repeat reminders.
		 * @since unknown
		 */
		public function maybe_process_expiration_and_reminders() {

			$this->log_debug( __METHOD__ . '(): starting' );

			$form_ids = $this->get_workflow_form_ids();

			$this->log_debug( __METHOD__ . '(): workflow form IDs: ' . print_r( $form_ids, true ) );

			foreach ( $form_ids as $form_id ) {
				$form = GFAPI::get_form( $form_id );

				if ( ! $form['is_active'] ) {
					continue;
				}

				$steps = $this->get_steps( $form_id );
				foreach ( $steps as $step ) {
					if ( ! $step || ! $step instanceof Gravity_Flow_Step ) {
						$this->log_debug( __METHOD__ . '(): step not a step!  ' . print_r( $step ) . ' - form ID: ' . $form_id );
						continue;
					}

					if ( ! $step->is_active() ) {
						continue;
					}

					if ( ! $step->expiration && ! ( $step->assignee_notification_enabled && $step->resend_assignee_emailEnable && $step->resend_assignee_emailValue > 0 ) ) {
						continue;
					}

					$this->log_debug( __METHOD__ . '(): checking assignees for all the entries on step ' . $step->get_id() );

					$criteria = array(
						'status' => 'active',
						'field_filters' => array(
							array(
								'key' => 'workflow_step',
								'value' => $step->get_id(),
							),
						),
					);

					$paging = array(
						'offset'    => 0,
						'page_size' => 150,
					);
					// Criteria: step active.
					$entries = GFAPI::get_entries( $form_id, $criteria, null, $paging );

					$this->log_debug( __METHOD__ . '(): count entries on step ' . $step->get_id() . ' = ' . count( $entries ) );

					foreach ( $entries as $entry ) {
						$current_step = $this->get_step( $entry['workflow_step'], $entry );

						if ( ! $current_step ) {
							$this->log_debug( __METHOD__ . '(): The step (id: ' . $entry['workflow_step'] . ') no longer exists. Skip entry id: ' . $entry['id'] );

							continue;
						}

						$this->log_debug( __METHOD__ . '(): processing entry: ' . $entry['id'] );

						if ( $current_step->is_expired() ) {

							$this->log_debug( __METHOD__ . '(): step has expired: ' . $current_step->get_id() . ' entry id: ' . $entry['id'] );

							$expiration_status = $current_step->status_expiration ? $current_step->status_expiration : 'complete';

							$this->log_debug( __METHOD__ . '(): expiration status: ' . $expiration_status );

							$current_step->log_event( esc_html__( 'Step expired', 'gravityflow' ) );

							$expiration_note = $current_step->get_name() . ': ' . esc_html__( 'Step expired', 'gravityflow' );

							$current_step->add_note( $expiration_note );

							gravity_flow()->process_workflow( $form, $entry['id'] );

							// Next entry.
							continue;
						}

						$assignees = $current_step->get_assignees();

						foreach ( $assignees as $assignee ) {
							$assignee_status = $assignee->get_status();
							if ( $assignee_status == 'pending' ) {
								$assignee_timestamp = $assignee->get_status_timestamp();
								$trigger_timestamp = $assignee_timestamp + ( (int) $current_step->resend_assignee_emailValue * DAY_IN_SECONDS );
								$reminder_timestamp = $assignee->get_reminder_timestamp();
								if ( time() > $trigger_timestamp && $reminder_timestamp == false ) {
									$this->log_debug( __METHOD__ . '(): assignee_timestamp: ' . $assignee_timestamp . ' - ' . get_date_from_gmt( date( 'Y-m-d H:i:s', $assignee_timestamp ), 'F j, Y H:i:s' ) );
									$this->log_debug( __METHOD__ . '(): trigger_timestamp: ' . $trigger_timestamp  . ' - ' . get_date_from_gmt( date( 'Y-m-d H:i:s', $trigger_timestamp ), 'F j, Y H:i:s' ) );
									$current_step->maybe_send_assignee_notification( $assignee, true );
									$assignee->set_reminder_timestamp();
									$this->log_debug( __METHOD__ . '(): sent first reminder about entry ' . $entry['id'] . ' to ' . $assignee->get_key() );
								}
								if ( time() > $trigger_timestamp && $reminder_timestamp !== false ) {
									$this->log_debug( __METHOD__ . '(): not sending first reminder to ' . $assignee->get_key() . ' for entry ' . $entry['id'] . ' because a reminder was already sent: ' . get_date_from_gmt( date( 'Y-m-d H:i:s', $reminder_timestamp ), 'F j, Y H:i:s' ) );
									if ( $current_step->resend_assignee_email_repeatEnable ) {
										$repeat_days = absint( $current_step->resend_assignee_email_repeatValue );
										//Depreciated Filter - See/Use gravityflow_assignee_email_reminder_repeat_days
										$repeat_days = apply_filters( 'gravityflow_assignee_eamil_reminder_repeat_days', $repeat_days, $form, $entry, $current_step, $assignee );
										/**
										 * Allows the number of days between each assignee email reminder to be modified.
										 *
										 * Return zero to deactivate the repeat reminder.
										 *
										 * @deprecated 2.5.3 - Fix typo of gravityflow_assignee_eamil_reminder_repeat_days (email)
										 *
										 * @param int                   $repeat_days The number of days between each reminder.
										 * @param array                 $form        The current form.
										 * @param array                 $entry       The current entry.
										 * @param Gravity_Flow_Step     $step        The current step.
										 * @param Gravity_Flow_Assignee $assignee    The current assignee.
										 */
										$repeat_days = apply_filters( 'gravityflow_assignee_email_reminder_repeat_days', $repeat_days, $form, $entry, $current_step, $assignee );
										if ( $repeat_days > 0 ) {
											$repeat_trigger_timestamp = $reminder_timestamp + ( (int) $repeat_days * DAY_IN_SECONDS );
											if ( time() > $repeat_trigger_timestamp ) {
												$current_step->maybe_send_assignee_notification( $assignee, true );
												$assignee->set_reminder_timestamp();
												$this->log_debug( __METHOD__ . '(): sent repeat reminder about entry ' . $entry['id'] . ' to ' . $assignee->get_key() );
											} else {
												$this->log_debug( __METHOD__ . '(): repeat reminder to ' . $assignee->get_key() .' for entry ' . $entry['id'] . ' is scheduled for ' . get_date_from_gmt( date( 'Y-m-d H:i:s', $repeat_trigger_timestamp ), 'F j, Y H:i:s' ) );
											}
										}
									}
								}
								if ( time() < $trigger_timestamp && $reminder_timestamp == false ) {
									$this->log_debug( __METHOD__ . '(): reminder to ' . $assignee->get_key() .' for entry ' . $entry['id'] . ' is scheduled for ' . get_date_from_gmt( date( 'Y-m-d H:i:s', $trigger_timestamp ), 'F j, Y H:i:s' ) );
								}
							}
						}
					}
				}
			}
		}

		/**
		 * The app settings page title.
		 *
		 * @return string
		 */
		public function app_settings_title() {
			return esc_html__( 'Gravity Flow Settings', 'gravityflow' );
		}

		/**
		 * The message to be displayed before the uninstall button.
		 *
		 * @return string
		 */
		public function uninstall_warning_message() {
			return sprintf( esc_html__( '%sThis operation deletes ALL Gravity Flow settings%s. If you continue, you will NOT be able to retrieve these settings.', 'gravityflow' ), '<strong>', '</strong>' );
		}

		/**
		 * The message to be displayed when the uninstall button is clicked.
		 *
		 * @return string
		 */
		public function uninstall_confirm_message() {
			return __( "Warning! ALL Gravity Flow settings will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'gravityflow' );
		}

		/**
		 * Target for the gravityflow_feed_actions filter.
		 *
		 * Removes the delete action when entries are on this step.
		 *
		 * @param array  $action_links The feed action links.
		 * @param array  $item         The feed.
		 * @param string $column       The column ID.
		 *
		 * @return array
		 */
		public function filter_feed_actions( $action_links, $item, $column ) {

			if ( empty( $action_links ) ) {
				return $action_links;
			}
			$feed_id = $item['id'];

			$current_step = $this->get_step( $feed_id );

			$count_entries = apply_filters( 'gravityflow_entry_count_step_list', true );

			$entry_count = $current_step && $count_entries ? absint( $current_step->entry_count() ) : false;

			if ( $entry_count && $entry_count > 0 ) {
				unset( $action_links['delete'] );
			}
			return $action_links;
		}

		/**
		 * Imports the feeds into the new form.
		 *
		 * @param array $original_feeds The original feeds.
		 * @param int   $new_form_id    The new form ID.
		 */
		public function import_gravityflow_feeds( $original_feeds, $new_form_id ) {
			$feed_id_mappings = array();

			foreach ( $original_feeds as $feed ) {
				$new_feed_id = GFAPI::add_feed( $new_form_id, $feed['meta'], 'gravityflow' );
				if ( ! $feed['is_active'] ) {
					$this->update_feed_active( $new_feed_id, false );
				}
				$feed_id_mappings[ $feed['id'] ] = $new_feed_id;
			}

			$new_steps = $this->get_steps( $new_form_id );

			foreach ( $new_steps as $new_step ) {
				$statuses_configs = $new_step->get_status_config();
				$new_step_meta = $new_step->get_feed_meta();
				$step_ids_updated = false;
				foreach ( $statuses_configs as $status_config ) {
					$destination_key = 'destination_' . $status_config['status'];
					if ( isset( $new_step_meta[ $destination_key ] ) ) {
						$old_destination_step_id = $new_step_meta[ $destination_key ];
						if ( ! in_array( $old_destination_step_id, array( 'next', 'complete' ) ) && isset( $feed_id_mappings[ $old_destination_step_id ] ) ) {
							$new_step_meta[ $destination_key ] = $feed_id_mappings[ $old_destination_step_id ];
							$step_ids_updated = true;
						}
					}
				}
				if ( $new_step->get_type() == 'approval' ) {
					if ( ! empty( $new_step->revertValue ) ) {
						$new_step_meta['revertValue'] = $feed_id_mappings[ $new_step->revertValue ];
						$step_ids_updated = true;
					}
				}
				// Change feed id in conditional logic.
				$is_condition_enabled = rgar( $new_step_meta, 'feed_condition_conditional_logic' ) == true;
				$logic                = rgars( $new_step_meta, 'feed_condition_conditional_logic_object/conditionalLogic' );
				if ( $is_condition_enabled && ! empty( $logic ) ) {
					foreach ( $new_step_meta['feed_condition_conditional_logic_object']['conditionalLogic']['rules'] as $key => $rule ) {
						if ( 0 === strpos( $rule['fieldId'], 'workflow_step_status_' ) ) {
							$old_feed_id = explode( '_', $rule['fieldId'] ); // fieldId is in the format of "workflow_step_status_30".
							$new_step_meta['feed_condition_conditional_logic_object']['conditionalLogic']['rules'][$key]['fieldId'] = 'workflow_step_status_' . $feed_id_mappings[$old_feed_id[3]];
							$step_ids_updated = true;
						}
					}
				}

				if ( $step_ids_updated ) {
					$this->update_feed_meta( $new_step->get_id(), $new_step_meta );
				}
			}
		}

		/**
		 * Target for the wp filter.
		 *
		 * Processes the access and approval step tokens.
		 *
		 * @return bool
		 */
		public function filter_wp() {

			if ( isset( $_GET['gflow_access_token'] ) ) {

				$token = $this->decode_access_token();

				if ( ! empty( $token ) && ! isset( $token['scopes']['action'] )&& ! is_user_logged_in() ) {
					// Remove the token from the URL to avoid accidental sharing.
					$secure = ( 'https' === parse_url( home_url(), PHP_URL_SCHEME ) );
					$sanitized_cookie = sanitize_text_field( $_GET['gflow_access_token'] );
					setcookie( 'gflow_access_token', $sanitized_cookie, null, $this->get_cookie_path(), null, $secure, true );

					$request_uri = remove_query_arg( 'gflow_access_token' );

					$redirect_url = home_url() . $request_uri;

					$this->log_debug( __METHOD__ . '(): redirect url: ' . $redirect_url );

					wp_safe_redirect( $redirect_url );

					exit();
				}
			}

			if ( isset( $_REQUEST['gflow_token'] ) && ! is_admin() ) {
				$token = $_REQUEST['gflow_token'];
				$token_json = base64_decode( $token );
				$token_array = json_decode( $token_json, true );

				if ( empty( $token_array ) ) {
					return false;
				}

				$entry_id = $token_array['entry_id'];
				if ( empty( $entry_id ) ) {
					return false;
				}

				$entry = GFAPI::get_entry( $entry_id );

				$step_id = $token_array['step_id'];
				if ( empty( $step_id ) ) {
					return false;
				}

				$step = $this->get_step( $step_id, $entry );
				if ( ! $step instanceof Gravity_Flow_Step_Approval ) {
					return false;
				}
				if ( ! $step->is_valid_token( $token ) ) {
					return false;
				}

				$form_id = $entry['form_id'];

				$form = GFAPI::get_form( $form_id );

				$user_id = $token_array['user_id'];
				$new_status = $token_array['new_status'];

				$feedback = $step->process_assignee_status( $user_id, 'user_id', $new_status, $form );

				if ( ! empty( $feedback ) ) {
					$this->process_workflow( $form, $entry_id );
					$this->_custom_page_content = $feedback;
					add_filter( 'the_content', array( $this, 'custom_page_content' ) );
				}
			}
		}

		/**
		 * Target for the the_content filter.
		 *
		 * Adds the assignee status feedback to the page content.
		 *
		 * @param string $content The page content.
		 *
		 * @return string
		 */
		public function custom_page_content( $content ) {
			$content .= $this->_custom_page_content;
			return $content;
		}

		/**
		 * Generates the access token for the specified assignee.
		 *
		 * Loosely based on the JWT spec.
		 *
		 * @param Gravity_Flow_Assignee $assignee             The current assignee.
		 * @param array                 $scopes               The access token scopes.
		 * @param bool|string           $expiration_timestamp The expiration timestamp.
		 *
		 * @return string
		 */
		public function generate_access_token( $assignee, $scopes = array(), $expiration_timestamp = false ) {

			if ( empty( $scopes ) ) {
				$scopes = array(
					'pages' => array( 'inbox', 'status' ),
				);
			}

			$user = $assignee->get_user();

			if ( ! empty( $user ) ) {
				$scopes['user_id'] = $user->ID;
			}

			if ( empty( $expiration_timestamp ) ) {
				$expiration_timestamp = strtotime( '+30 days' );
			}

			$jti = uniqid();

			$token_array = array(
				'iat'  => time(),
				'exp' => $expiration_timestamp,
				'sub'    => $assignee->get_key(),
				'scopes' => $scopes,
				'jti' => $jti,
			);

			$token = rawurlencode( base64_encode( json_encode( $token_array ) ) );

			$secret = get_option( 'gravityflow_token_secret' );
			if ( empty( $secret ) ) {
				$secret = wp_generate_password( 64 );
				update_option( 'gravityflow_token_secret', $secret );
			}

			$sig = hash_hmac( 'sha256', $token, $secret );

			$token .= '.' . $sig;

			$this->log_event( 'token', 'generated', 0, 0, json_encode( $token_array ), 0, 0, $assignee->get_id(), $assignee->get_type(), $assignee->get_display_name() );

			return $token;
		}

		/**
		 * Validates the access token.
		 *
		 * @param bool|string $token The access token or false.
		 *
		 * @return bool
		 */
		public function validate_access_token( $token = false ) {

			if ( empty( $token ) ) {
				$token = $this->get_access_token();
			}

			if ( empty( $token ) ) {
				$this->log_debug( __METHOD__ . '(): empty token; returning false.' );

				return false;
			}

			$parts = explode( '.', $token );
			if ( count( $parts ) < 2 ) {
				$this->log_debug( __METHOD__ . '(): token parts < 2; returning false.' );

				return false;
			}

			$body_64_probably_url_decoded = $parts[0];
			$sig                          = $parts[1];

			if ( empty( $sig ) ) {
				$this->log_debug( __METHOD__ . '(): empty sig; returning false.' );

				return false;
			}

			$secret = get_option( 'gravityflow_token_secret' );
			if ( empty( $secret ) ) {
				$this->log_debug( __METHOD__ . '(): empty secret; returning false.' );

				return false;
			}

			$verification_sig  = hash_hmac( 'sha256', $body_64_probably_url_decoded, $secret );
			$verification_sig2 = hash_hmac( 'sha256', rawurlencode( $body_64_probably_url_decoded ), $secret );

			if ( ! hash_equals( $sig, $verification_sig ) && ! hash_equals( $sig, $verification_sig2 ) ) {
				$this->log_debug( __METHOD__ . '(): failed hash validation; returning false.' );

				return false;
			}

			$body_json = base64_decode( $body_64_probably_url_decoded );
			if ( empty( $body_json ) || empty( json_decode( $body_json, true ) ) ) {
				$body_json = base64_decode( urldecode( $body_64_probably_url_decoded ) );
				if ( empty( $body_json ) ) {
					$this->log_debug( __METHOD__ . '(): empty body_json; returning false.' );

					return false;
				}
			}

			$token = json_decode( $body_json, true );

			if ( ! isset( $token['jti'] ) ) {
				$this->log_debug( __METHOD__ . '(): jti not set; returning false.' );

				return false;
			}

			if ( ! isset( $token['exp'] ) ) {
				$this->log_debug( __METHOD__ . '(): exp not set; returning false.' );

				return false;
			}

			if ( $token['exp'] < time() ) {
				$this->log_debug( __METHOD__ . '(): exp < time; returning false.' );

				return false;
			}

			$revoked_tokens = get_option( 'gravityflow_revoked_tokens', array() );
			if ( isset( $revoked_tokens[ $token['jti'] ] ) ) {
				$this->log_debug( __METHOD__ . '(): token revoked; returning false.' );

				return false;
			}

			$this->log_debug( __METHOD__ . '(): token valid.' );

			return true;
		}

		/**
		 * Retrieves the access token from the query string or cookie.
		 *
		 * @return bool|string
		 */
		public function get_access_token() {
			$token = false;
			if ( empty( $token ) ) {
				$token = rgget( 'gflow_access_token' );
			}

			if ( empty( $token ) ) {
				$token = rgpost( 'gflow_access_token' );
			}

			if ( empty( $token ) ) {
				$token = rgar( $_COOKIE, 'gflow_access_token' );
			}

			return $token;
		}

		/**
		 * Decodes the access token.
		 *
		 * @param bool|string $token    The access token or false.
		 * @param bool        $validate Indicates if the access token should be validated.
		 *
		 * @return array|bool
		 */
		public function decode_access_token( $token = false, $validate = true ) {
			if ( empty( $token ) ) {
				$token = $this->get_access_token();
			}

			if ( empty( $token ) ) {
				$this->log_debug( __METHOD__ . '(): empty token; returning false.' );

				return false;
			}

			if ( $validate && ! $this->validate_access_token( $token ) ) {
				$this->log_debug( __METHOD__ . '(): token failed validation; returning false.' );

				return false;
			}

			$parts = explode( '.', $token );
			if ( count( $parts ) < 2 ) {
				$this->log_debug( __METHOD__ . '(): token parts < 2; returning false.' );

				return false;
			}

			$body_64 = $parts[0];

			$body_json = base64_decode( $body_64 );
			if ( empty( $body_json ) ) {
				$this->log_debug( __METHOD__ . '(): base64_decode result empty; returning false.' );

				return false;
			}

			if ( empty( json_decode( $body_json, true ) ) ) {
				$body_json = base64_decode( urldecode( $body_64 ) );
			}

			return json_decode( $body_json, true );

		}

		/**
		 * Returns the assignee object for the current access token or false.
		 *
		 * @param string $token The assignee access token.
		 *
		 * @return bool|Gravity_Flow_Assignee
		 */
		public function parse_token_assignee( $token ) {
			if ( empty( $token ) ) {
				return false;
			}

			$assignee_key = sanitize_text_field( $token['sub'] );

			$assignee = Gravity_Flow_Assignees::create( $assignee_key );

			return $assignee;
		}

		/**
		 * Registers activity event in the activity log. The activity log is used to generate reports.
		 *
		 * @param string $log_type      The object of the event: 'workflow', 'step', 'assignee'.
		 * @param string $event         The event which occurred: 'started', 'ended', 'status'.
		 * @param int    $form_id       The form ID.
		 * @param int    $entry_id      The Entry ID.
		 * @param string $log_value     The value to log.
		 * @param int    $step_id       The Step ID.
		 * @param int    $duration      The duration in seconds - if applicable.
		 * @param int    $assignee_id   The assignee ID - if applicable.
		 * @param string $assignee_type The Assignee type - if applicable.
		 * @param string $display_name  The display name of the User.
		 */
		public function log_event( $log_type, $event, $form_id = 0, $entry_id = 0, $log_value = '', $step_id = 0, $duration = 0, $assignee_id = 0, $assignee_type = '', $display_name = '' ) {
			global $wpdb;
			$wpdb->insert(
				$wpdb->prefix . 'gravityflow_activity_log',
				array(
					'log_object' => $log_type, // workflow, step, assignee - what did the activity happen to?
					'log_event' => $event, // started, ended, status - what activity happened?
					'log_value' => $log_value, // approved, rejected, complete - what value, if any, was generated?
					'date_created' => current_time( 'mysql', true ),
					'form_id' => $form_id,
					'lead_id' => $entry_id,
					'assignee_id' => $assignee_id,
					'assignee_type' => $assignee_type,
					'display_name' => $display_name,
					'feed_id' => $step_id,
					'duration' => $duration, // Time interval in seconds, if any.
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
				)
			);
		}

		/**
		 * Target for the wp_login hook.
		 *
		 * Stores the assignee access token in a cookie.
		 */
		public function filter_wp_login() {
			unset( $_COOKIE['gflow_access_token'] );
			setcookie( 'gflow_access_token', null, - 1, $this->get_cookie_path() );
		}

		/**
		 * Format the duration for output.
		 *
		 * @param int $seconds The duration in seconds.
		 *
		 * @return string
		 */
		public function format_duration( $seconds ) {
			if ( method_exists( 'DateTime', 'diff' ) ) {
				$dtF           = new DateTime( '@0' );
				$dtT           = new DateTime( "@$seconds" );
				$date_interval = $dtF->diff( $dtT );
				$interval      = array();

				$interval = $this->maybe_add_date_intervals( $interval, $date_interval );

				if ( $date_interval->y == 0 && $date_interval->m == 0 ) {
					$interval = $this->maybe_add_time_intervals( $interval, $date_interval );
				}

				return join( ', ', $interval );
			} else {
				return esc_html( $seconds );
			}
		}

		/**
		 * Adds the year, month and day intervals, if appropriate.
		 *
		 * @param array        $interval      The intervals.
		 * @param DateInterval $date_interval The date interval object.
		 *
		 * @return array
		 */
		public function maybe_add_date_intervals( $interval, $date_interval ) {
			if ( $date_interval->y > 0 ) {
				$years_format = _n( '%d year', '%d years', $date_interval->y, 'gravityflow' );
				$interval[]   = esc_html( sprintf( $years_format, $date_interval->y ) );
			}
			if ( $date_interval->m > 0 ) {
				$months_format = _n( '%d month', '%d months', $date_interval->m, 'gravityflow' );
				$interval[]    = esc_html( sprintf( $months_format, $date_interval->m ) );
			}
			if ( $date_interval->d > 0 ) {
				$days_format = esc_html__( '%dd', 'gravityflow' );
				$interval[]  = sprintf( $days_format, $date_interval->d );
			}

			return $interval;
		}

		/**
		 * Adds the hours, minutes and seconds intervals, if appropriate.
		 *
		 * @param array        $interval      The intervals.
		 * @param DateInterval $date_interval The date interval object.
		 *
		 * @return array
		 */
		public function maybe_add_time_intervals( $interval, $date_interval ) {
			if ( $date_interval->h > 0 ) {
				$hours_format = esc_html__( '%dh', 'gravityflow' );
				$interval[]   = sprintf( $hours_format, $date_interval->h );
			}
			if ( $date_interval->d == 0 && $date_interval->h == 0 ) {
				if ( $date_interval->i > 0 ) {
					$minutes_format = esc_html__( '%dm', 'gravityflow' );
					$interval[]     = sprintf( $minutes_format, $date_interval->i );
				}
				if ( $date_interval->s > 0 ) {
					$seconds_format = esc_html__( '%ds', 'gravityflow' );
					$interval[]     = sprintf( $seconds_format, $date_interval->s );
				}
			}

			return $interval;
		}

		/**
		 * Returns the base64 encoded svg+xml icon.
		 *
		 * @param bool $color Indicates if the icon should be in color.
		 *
		 * @return string
		 */
		public function get_admin_icon_b64( $color = false ) {
			$icon = gravityflow_icon();
			return $icon;
		}

		/**
		 * Target for the template_redirect hook.
		 *
		 * Hack to fix paging on the status shortcode.
		 */
		public function action_template_redirect() {
			global $wp_query;
			if ( isset( $wp_query->query_vars['paged'] ) && $wp_query->query_vars['paged'] > 0 ) {
				if ( $this->look_for_shortcode() ) {
					remove_filter( 'template_redirect', 'redirect_canonical' );
				}
			}
		}

		/**
		 * Target for the cron_schedules filter. Add 15 minutes to the schedule.
		 *
		 * @param array $schedules An array of non-default cron schedules.
		 *
		 * @return array
		 */
		function filter_cron_schedule( $schedules ) {
			$schedules['fifteen_minutes'] = array(
				'interval' => 15 * MINUTE_IN_SECONDS,
				'display'  => esc_html__( 'Every Fifteen Minutes' ),
			);

			return $schedules;
		}

		/**
		 * Retrieves the setting for a specific field/input
		 *
		 * @param string     $setting_name  The field or input name.
		 * @param string     $default_value Optional. The default value.
		 * @param bool|array $settings      Optional. THe settings array.
		 *
		 * @return string|array
		 */
		public function get_setting( $setting_name, $default_value = '', $settings = false ) {
			return parent::get_setting( $setting_name, $default_value, $settings );
		}

		/**
		 * Processes the Ajax status export request.
		 */
		public function ajax_export_status() {
			if ( ! wp_verify_nonce( rgget( 'gravityflow_export_nonce' ), 'gravityflow_export_nonce' ) || ! GFAPI::current_user_can_any( 'gravityflow_status' ) ) {
				$response['status'] = 'error';
				$response['message'] = __( 'Not authorized', 'gravityflow' );
				$response_json = json_encode( $response );
				echo $response_json;
				die();
			}

			require_once( 'includes/pages/class-status.php' );

			$args['format'] = 'csv';
			$args['per_page'] = 50;
			$args['file_name'] = 'gravityflow-status-export';
			$result = Gravity_Flow_Status::render( $args );
			echo json_encode( $result );
			die();
		}

		/**
		 * Target of the wp_ajax_gravityflow_download_export hook.
		 *
		 * Processes the Ajax export download request.
		 */
		public function ajax_download_export() {

			if ( ! wp_verify_nonce( rgget( 'nonce' ), 'gravityflow_download_export' ) || ! GFAPI::current_user_can_any( 'gravityflow_status' ) ) {
				$response['status'] = 'error';
				$response['message'] = __( 'Not authorized', 'gravityflow' );
				$response_json = json_encode( $response );
				echo $response_json;
				die();
			}

			$file_name = $_REQUEST['file_name'];

			$upload_dir = wp_upload_dir();

			$file_path = trailingslashit( $upload_dir['basedir'] ) . $file_name . '.csv';

			$file = '';

			if ( @file_exists( $file_path ) ) {
				$file = @file_get_contents( $file_path );
				@unlink( $file_path );
			}

			nocache_headers();
			header( 'Content-Type: text/csv; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=' . $file_name . '-' . date( 'm-d-Y' ) . '.csv' );
			header( 'Expires: 0' );

			echo $file;
			die();
		}

		/**
		 * AJAX helper to render workflow reports.
		 *
		 * @since 2.5.10
		 */
		public function ajax_render_workflow_reports() {
			if ( ! wp_verify_nonce( rgpost( 'nonce' ), 'gravityflow_render_reports' ) ) {
				$response['status'] = 'error';
				$response['message'] = __( 'Not authorized', 'gravityflow' );
				$response_json = json_encode( $response );
				echo $response_json;
				die();
			}

			$args = json_decode( rgpost( 'args' ), true );

			// Get values from the filter, and replace the params if they use "-" instead of "_".
			$data = array();
			parse_str( rgpost( 'data' ), $data );
			foreach ( $data as $key => $arg ) {
			    unset( $data[ $key ] );
				$key = str_replace( '-', '_', $key );
				$data[ $key ] = $arg;
			}

			$args = array_merge( $args, $data );

			require_once( $this->get_base_path() . '/includes/pages/class-reports.php' );

			$assignee_key = sanitize_text_field( rgar( $args, 'assignee' ) );
			list( $args['assignee_type'], $args['assignee_id'] ) = rgexplode( '|', $assignee_key, 2 );

			Gravity_Flow_Reports::output_reports( $args );
			die();
		}

		/**
		 * Returns the display label for the specified navigation label key.
		 *
		 * @param string $label_key The navigation label key.
		 *
		 * @return string
		 */
		public function translate_navigation_label( $label_key ) {

			$custom_labels = get_option( 'gravityflow_app_settings_labels', array() );

			$custom_navigation_labels = rgar( $custom_labels, 'navigation' );


			$custom_label = rgar( $custom_navigation_labels, $label_key );

			if ( ! empty( $custom_label) ) {
				return $custom_label;
			}

			$default_labels = $this->get_default_navigation_labels();

			$label = rgar( $default_labels, $label_key );

			return empty( $label ) ?  $label_key :  $label;
		}

		/**
		 * Returns the display labels for the navigation keys.
		 *
		 * @return array
		 */
		public function get_default_navigation_labels() {
			return array(
				'workflow' => esc_html__( 'Workflow', 'gravityflow' ),
				'inbox'    => esc_html__( 'Inbox', 'gravityflow' ),
				'submit'   => esc_html__( 'Submit', 'gravityflow' ),
				'status'   => esc_html__( 'Status', 'gravityflow' ),
				'support'  => esc_html__( 'Support', 'gravityflow' ),
				'reports'  => esc_html__( 'Reports', 'gravityflow' ),
				'activity' => esc_html__( 'Activity', 'gravityflow' ),
			);
		}

		/**
		 * Returns the display label for the supplied status.
		 *
		 * @param string $status The status.
		 *
		 * @return string
		 */
		public function translate_status_label( $status ) {
			$original_status = $status;

			$status = strtolower( $status );

			$custom_labels = get_option( 'gravityflow_app_settings_labels', array() );

			$status_labels = rgar( $custom_labels, 'status' );

			$custom_label = rgar( $status_labels, $status );

			if ( ! empty( $custom_label ) ) {
				return $custom_label;
			}

			switch ( $status ) {
				case 'pending' :
					return esc_html__( 'Pending', 'gravityflow' );

				case 'complete' :
					return esc_html__( 'Complete', 'gravityflow' );

				case 'approved' :
					return esc_html__( 'Approved', 'gravityflow' );

				case 'rejected' :
					return esc_html__( 'Rejected', 'gravityflow' );

				case 'expired' :
					return esc_html__( 'Expired', 'gravityflow' );

				case 'cancelled' :
					return esc_html__( 'Cancelled', 'gravityflow' );

			}

			$steps = Gravity_Flow_Steps::get_all();

			foreach ( $steps as $step ) {
				$status_configs = $step->get_status_config();
				foreach ( $status_configs as $status_config ) {
					if ( $status == strtolower( $status_config['status'] ) ) {
						return $step->get_status_label( $original_status );
					}
				}
			}
			return $original_status;
		}


		/**
		 * Hack to fix signature add-on in the front-end until GF_Field is implemented.
		 *
		 * The input name is rendered with the form ID in the front-end but editing is expected to be done in admin.
		 */
		public function maybe_save_signature() {

			// See if this is an entry and it needs to be updated, abort if not.
			if ( ! ( RG_CURRENT_VIEW == 'entry' && rgpost( 'save' ) == 'Update' ) ) {
				return;
			}

			$lead_id = rgget( 'lid' );
			$form    = RGFormsModel::get_form_meta( rgget( 'id' ) );
			if ( empty( $lead_id ) ) {
				// The lid is not always in the querystring when paging through entries, use same logic from entry detail page.
				$filter         = rgget( 'filter' );
				$status         = in_array( $filter, array( 'trash', 'spam' ) ) ? $filter : 'active';
				$search         = rgget( 's' );
				$position       = rgget( 'pos' ) ? rgget( 'pos' ) : 0;
				$sort_direction = rgget( 'dir' ) ? rgget( 'dir' ) : 'DESC';

				$sort_field      = empty( $_GET['sort'] ) ? 0 : $_GET['sort'];
				$sort_field_meta = RGFormsModel::get_field( $form, $sort_field );
				$is_numeric      = $sort_field_meta['type'] == 'number';

				$star = $filter == 'star' ? 1 : null;
				$read = $filter == 'unread' ? 0 : null;

				$leads = RGFormsModel::get_leads( rgget( 'id' ), $sort_field, $sort_direction, $search, $position, 1, $star, $read, $is_numeric, null, null, $status );

				if ( ! $lead_id ) {
					$lead = ! empty( $leads ) ? $leads[0] : false;
				} else {
					$lead = RGFormsModel::get_lead( $lead_id );
				}

				if ( ! $lead ) {
					_e( "Oops! We couldn't find your lead. Please try again", 'gravityflow' );

					return;
				}
			}

			// Loop through form fields, get the field name of the signature field.
			foreach ( $form['fields'] as $field ) {
				if ( RGFormsModel::get_input_type( $field ) == 'signature' ) {
					// Get field name so the value can be pulled from the post data.
					$form_id = absint( $form['id'] );
					$input_name = 'input_' . $form_id . '_' . str_replace( '.', '_', $field['id'] );

					// When adding a new signature the data field will be populated.
					if ( ! rgempty( "{$input_name}_data" ) ) {
						// New image added, save.
						$filename = gf_signature()->save_signature( $input_name . '_data' );
					} else {
						// Existing image edited.
						$filename = rgpost( $input_name . '_signature_filename' );
					}
					$_POST[ "input_{$field['id']}" ] = $filename;

				}
			}

		}

		/**
		 * Hack until the Signature Add-On uses GF_Field
		 *
		 * @param array $form The current form.
		 *
		 * @return array
		 */
		public function delete_signature_script( $form ) {
			$form_id = absint( $form['id'] );
			?>

			<script type="text/javascript">
				function deleteSignature(leadId, fieldId) {

					if (!confirm(<?php echo json_encode( __( "Would you like to delete this file? 'Cancel' to stop. 'OK' to delete", 'gravityformssignature' ) ); ?>))
						return;

					jQuery.post(ajaxurl, {
						lead_id: leadId,
						field_id: fieldId,
						action: 'gf_delete_signature',
						gf_delete_signature: '<?php echo wp_create_nonce( 'gf_delete_signature' ) ?>'
					}, function (response) {
						if ( ! response ){
							jQuery('#input_' + fieldId + '_signature_filename').val('');
						}
						jQuery('#input_<?php echo $form_id; ?>_' + fieldId + '_signature_image').hide();
						jQuery('#input_<?php echo $form_id; ?>_' + fieldId + '_Container').show();
						jQuery('#input_<?php echo $form_id; ?>_' + fieldId + '_resetbutton').show();
					});
				}
			</script>

			<?php
			return $form;
		}

		/**
		 * Allow feeds to duplicated.
		 *
		 * @param int|array $id The ID of the feed to be duplicated or the feed object when duplicating a form.
		 *
		 * @return bool
		 */
		public function can_duplicate_feed( $id ) {
			return true;
		}

		/**
		 * Target of the gform_post_form_duplicated hook.
		 *
		 * Triggers copying of the feeds from the original form to the duplicate.
		 *
		 * @param int $form_id The original form ID.
		 * @param int $new_id  The duplicate form ID.
		 */
		public function post_form_duplicated( $form_id, $new_id ) {

			$original_feeds = $this->get_feeds( $form_id );

			$this->import_gravityflow_feeds( $original_feeds, $new_id );

		}

		/**
		 * Target of the gform_post_add_entry hook.
		 *
		 * Starts the workflow for entries added via GFAPI::add_entry().
		 *
		 * @param array $entry The newly added entry.
		 * @param array $form  The form for this entry.
		 */
		public function action_gform_post_add_entry( $entry, $form ) {
			if ( is_wp_error( $entry ) || ! empty( $entry['partial_entry_id'] ) || rgar( $entry, 'status' ) !== 'active' ) {
				return;
			}

			$this->log_debug( __METHOD__ . '(): starting' );

			$api   = new Gravity_Flow_API( $form['id'] );
			$steps = $api->get_steps();

			if ( ! empty( $steps ) ) {
				gform_add_meta( $entry['id'], 'workflow_final_status', 'pending', $form['id'] );
				$this->log_debug( __METHOD__ . '(): triggering workflow for entry ID: ' . $entry['id'] );
				gravity_flow()->maybe_process_feed( $entry, $form );
				$api->process_workflow( $entry['id'] );
			}
		}

		/**
		 * Get the assignee key for the current access token or user.
		 *
		 * @return string|bool
		 */
		public function get_current_user_assignee_key() {
			$assignee_key = false;
			if ( $token = gravity_flow()->decode_access_token() ) {
				$assignee_key = sanitize_text_field( $token['sub'] );
			} elseif ( is_user_logged_in() ) {
				$assignee_key = 'user_id|' . get_current_user_id();
			}

			return $assignee_key;
		}

		/**
		 * Get the entry meta for use with the feed_condition setting.
		 *
		 * @since 1.7.1-dev
		 * @since 2.6.1     Added parameters for form_id and step_id.
		 *
		 * @param int $form_id The form ID.
		 * @param int $step_id The step ID.
		 *
		 * @return array
		 */
		public function get_feed_condition_entry_meta( $form_id = 0, $step_id = 0 ) {
			$entry_meta = GFFormsModel::get_entry_meta( $form_id );

			unset( $entry_meta['workflow_final_status'], $entry_meta['workflow_step'], $entry_meta[ 'workflow_step_status_' . $step_id ] );

			return $entry_meta;
		}

		/**
		 * Get the entry properties for use with the feed_condition setting.
		 *
		 * @since 1.7.1-dev
		 *
		 * @return array
		 */
		public function get_feed_condition_entry_properties() {
			$user_choices = array();

			if ( $this->is_form_settings() ) {
				$args = apply_filters( 'gform_filters_get_users', array(
					'number' => 200,
					'fields' => array( 'ID', 'user_login' )
				) );

				$users = get_users( $args );
				foreach ( $users as $user ) {
					$user_choices[] = array( 'text' => $user->user_login, 'value' => $user->ID );
				}
			}

			$form_id = absint( rgget( 'id' ) );

			/**
			 * Allows feed condition entry properties to be modified for the form.
			 *
			 * @since 2.2.4-dev
			 *
			 * @param array $properties The feed condition entry properties.
			 * @param int   $form_id Form id.
			 */
			$properties = apply_filters( 'gravityflow_feed_condition_entry_properties',
				array(
					'ip'             => array(
						'label'  => esc_html__( 'User IP', 'gravityflow' ),
						'filter' => array(
							'operators' => array( 'is', 'isnot', '>', '<', 'contains' ),
						),
					),
					'source_url'     => array(
						'label'  => esc_html__( 'Source URL', 'gravityflow' ),
						'filter' => array(
							'operators' => array( 'is', 'isnot', '>', '<', 'contains' ),
						),
					),
					'payment_status' => array(
						'label'  => esc_html__( 'Payment Status', 'gravityflow' ),
						'filter' => array(
							'operators' => array( 'is', 'isnot' ),
							'choices'   => $this->get_entry_payment_statuses_as_choices(),
						),
					),
					'payment_amount' => array(
						'label'  => esc_html__( 'Payment Amount', 'gravityflow' ),
						'filter' => array(
							'operators' => array( 'is', 'isnot', '>', '<', 'contains' ),
						),
					),
					'transaction_id' => array(
						'label'  => esc_html__( 'Transaction ID', 'gravityflow' ),
						'filter' => array(
							'operators' => array( 'is', 'isnot', '>', '<', 'contains' ),
						),
					),
					'created_by' => array(
						'label'  => esc_html__( 'Created By', 'gravityflow' ),
						'filter' => array(
							'operators' => array( 'is', 'isnot' ),
							'choices'   => $user_choices,
						),
					),
				),
				$form_id
			);

			return $properties;
		}

		/**
		 * Returns an array of supported entry payment statuses formatted for use as drop down choices.
		 *
		 * @since 2.2.4-dev
		 *
		 * @return array
		 */
		public function get_entry_payment_statuses_as_choices() {
			if ( ! $this->is_gravityforms_supported( '2.4' ) ) {
				return array(
					array(
						'text'  => esc_html__( 'Authorized', 'gravityflow' ),
						'value' => 'Authorized',
					),
					array(
						'text'  => esc_html__( 'Paid', 'gravityflow' ),
						'value' => 'Paid',
					),
					array(
						'text'  => esc_html__( 'Processing', 'gravityflow' ),
						'value' => 'Processing',
					),
					array(
						'text'  => esc_html__( 'Failed', 'gravityflow' ),
						'value' => 'Failed',
					),
					array(
						'text'  => esc_html__( 'Active', 'gravityflow' ),
						'value' => 'Active',
					),
					array(
						'text'  => esc_html__( 'Cancelled', 'gravityflow' ),
						'value' => 'Cancelled',
					),
					array(
						'text'  => esc_html__( 'Pending', 'gravityflow' ),
						'value' => 'Pending',
					),
					array(
						'text'  => esc_html__( 'Refunded', 'gravityflow' ),
						'value' => 'Refunded',
					),
					array(
						'text'  => esc_html__( 'Voided', 'gravityflow' ),
						'value' => 'Voided',
					),
				);
			}

			return GFCommon::get_entry_payment_statuses_as_choices();
		}

		/**
		 * Fork of GFCommon::evaluate_conditional_logic which supports evaluating logic based on entry properties.
		 *
		 * @since 1.7.1-dev
		 *
		 * @param array $logic The conditional logic to be evaluated.
		 * @param array $form  The current form.
		 * @param array $entry The current entry.
		 *
		 * @return bool
		 */
		public function evaluate_conditional_logic( $logic, $form, $entry ) {
			if ( ! $logic || ! is_array( rgar( $logic, 'rules' ) ) ) {
				return true;
			}

			$form_id         = $form['id'];
			$entry_meta      = array_merge( $this->get_feed_condition_entry_meta( $form_id ), $this->get_feed_condition_entry_properties() );
			$entry_meta_keys = array_keys( $entry_meta );
			$match_count     = 0;

			if ( is_array( $logic['rules'] ) ) {
				foreach ( $logic['rules'] as $rule ) {

					$rule['value'] = GFCommon::replace_variables( $rule['value'], $form, $entry, false, false, false, 'text' );

					if ( in_array( $rule['fieldId'], $entry_meta_keys ) ) {
						$is_value_match = GFFormsModel::is_value_match( rgar( $entry, $rule['fieldId'] ), $rule['value'], $rule['operator'], null, $rule, $form );
					} else {
						$source_field   = GFFormsModel::get_field( $form, $rule['fieldId'] );
						$field_value    = empty( $entry ) ? GFFormsModel::get_field_value( $source_field, array() ) : GFFormsModel::get_lead_field_value( $entry, $source_field );
						$is_value_match = GFFormsModel::is_value_match( $field_value, $rule['value'], $rule['operator'], $source_field, $rule, $form );
					}

					if ( $is_value_match ) {
						$match_count ++;
					}
				}
			}

			$do_action = ( $logic['logicType'] == 'all' && $match_count == sizeof( $logic['rules'] ) ) || ( $logic['logicType'] == 'any' && $match_count > 0 );

			return $do_action;
		}

		/**
		 * Determines if a non-text field types are being used for conditional routing
		 *
		 * @since 2.4.1
		 *
		 * @param bool         $is_match     Does the target field’s value match with the rule value?
		 * @param string|array $field_value  The field value to use with the comparison.
		 * @param string       $target_value The value from the conditional routing rule to use with the comparison.
		 * @param string       $operation    The conditional routing rule operator.
		 * @param object       $source_field The field object for the source of the field value.
		 * @param array        $rule         The current rule object.
		 *
		 * @return bool
		 */
		public function filter_gform_is_value_match( $is_match, $field_value, $target_value, $operation, $source_field, $rule ) {

			if ( ! ( $source_field instanceof GF_Field ) || ! in_array( $source_field->type, array( 'workflow_multi_user', 'date' ) ) ) {
				return $is_match;
			}

			switch ( $source_field->type ) {
				case 'workflow_multi_user':
					if ( in_array( $target_value, $field_value, true ) ) {
						return true;
					}
					break;
				case 'date':
					if ( class_exists( 'GP_Conditional_Logic_Dates' ) ) {
						return $is_match;
					}

					$field_value = strtotime( $field_value );

					if ( is_numeric( $target_value ) ) {
						$target_value = (int)$target_value;
					} else {
						$target_value = strtotime( $target_value );
					}

					if ( $operation == '>' && $field_value > $target_value ) {
						return true;
					}
					if ( $operation == '<' && $field_value < $target_value ) {
						return true;
					}
					if ( $operation == 'is' && $field_value == $target_value ) {
						return true;
					}
					if ( $operation == 'isnot' && $field_value != $target_value ) {
						return true;
					}
					break;
			}
			return false;
		}

		/**
		 * Target for the gform_pre_replace_merge_tags filter. Replaces the workflow_timeline and created_by merge tags.
		 *
		 * @since 2.2.4 Added the assignee to the merge tag if the current user is an assignee.
		 * @since unknown
		 *
		 * @param string $text       The text which may contain merge tags to be processed.
		 * @param array  $form       The current form.
		 * @param array  $entry      The current entry.
		 * @param bool   $url_encode Indicates if the replacement value should be URL encoded.
		 * @param bool   $esc_html   Indicates if HTML found in the replacement value should be escaped.
		 * @param bool   $nl2br      Indicates if newlines should be converted to html <br> tags.
		 * @param string $format     Determines how the value should be formatted. HTML or text.
		 *
		 * @return string
		 */
		public function replace_variables( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {

			if ( strpos( $text, '{' ) === false || empty( $entry ) ) {
				return $text;
			}

			remove_filter( 'gform_pre_replace_merge_tags', array( $this, 'replace_variables' ) );
			$step = gravity_flow()->get_current_step( $form, $entry );
			add_filter( 'gform_pre_replace_merge_tags', array( $this, 'replace_variables' ), 10, 7 );

			$assignee = null;

			if ( $step ) {
				$current_assignees = $step->get_assignees();
				foreach ( $current_assignees as $current_assignee ) {
					if ( $current_assignee->is_current_user() ) {
						$assignee = $current_assignee;
						break;
					}
				}
			}

			$args = compact( 'form', 'entry', 'url_encode', 'esc_html', 'nl2br', 'format', 'step', 'assignee' );

			$merge_tags = Gravity_Flow_Merge_Tags::get_all( $args );

			foreach ( $merge_tags as $merge_tag ) {
				$text = $merge_tag->replace( $text );
			}

			return $text;
		}

		/**
		 * Determines if any of the form fields have conditional logic configured.
		 *
		 * @param array $form The current form.
		 *
		 * @return bool
		 */
		public function fields_have_conditional_logic( $form ) {
			$has_conditional_logic = false;
			if ( isset( $form['fields'] ) && is_array( $form['fields'] ) ) {
				foreach ( $form['fields'] as $field ) {
					if ( is_array( $field->conditionalLogic ) ) {
						$has_conditional_logic = true;
						break;
					}
				}
			}
			return $has_conditional_logic;
		}

		/**
		 * Determines if the form has any page fields with conditional logic.
		 *
		 * @param array $form The current form.
		 *
		 * @return bool
		 */
		public function pages_have_conditional_logic( $form ) {
			$has_conditional_logic = false;
			if ( isset( $form['fields'] ) && is_array( $form['fields'] ) ) {
				foreach ( $form['fields'] as $field ) {
					if ( $field->type == 'page' && is_array( $field->conditionalLogic ) ) {
						$has_conditional_logic = true;
						break;
					}
				}
			}
			return $has_conditional_logic;
		}

		/**
		 * Returns the current form object based on the id query var. Otherwise returns false.
		 */
		public function get_current_form() {

			return rgempty( 'id', $_GET ) ? false : GFFormsModel::get_form_meta( rgget( 'id' ) );
		}

		/**
		 * Returns the mergeTagLabels property of the strings for form-settings.js.
		 *
		 * @since 1.4.3-dev
		 *
		 * @used-by Gravity_Flow::scripts()
		 * @uses    esc_html__()
		 *
		 * @return array
		 */
		public function get_form_settings_js_merge_tag_labels() {
			return array(
				'group'                  => esc_html__( 'Workflow', 'gravityflow' ),
				'workflow_entry_link'    => esc_html__( 'Entry Link', 'gravityflow' ),
				'workflow_entry_url'     => esc_html__( 'Entry URL', 'gravityflow' ),
				'workflow_inbox_link'    => esc_html__( 'Inbox Link', 'gravityflow' ),
				'workflow_inbox_url'     => esc_html__( 'Inbox URL', 'gravityflow' ),
				'workflow_cancel_link'   => esc_html__( 'Cancel Link', 'gravityflow' ),
				'workflow_cancel_url'    => esc_html__( 'Cancel URL', 'gravityflow' ),
				'workflow_note'          => esc_html__( 'Note', 'gravityflow' ),
				'workflow_timeline'      => esc_html__( 'Timeline', 'gravityflow' ),
				'assignees'              => esc_html__( 'Assignees', 'gravityflow' ),
				'workflow_approve_link'  => esc_html__( 'Approve Link', 'gravityflow' ),
				'workflow_approve_url'   => esc_html__( 'Approve URL', 'gravityflow' ),
				'workflow_approve_token' => esc_html__( 'Approve Token', 'gravityflow' ),
				'workflow_revert_link'   => esc_html__( 'Revert Link', 'gravityflow' ),
				'workflow_revert_url'    => esc_html__( 'Revert URL', 'gravityflow' ),
				'workflow_revert_token'  => esc_html__( 'Revert Token', 'gravityflow' ),
				'workflow_reject_link'   => esc_html__( 'Reject Link', 'gravityflow' ),
				'workflow_reject_url'    => esc_html__( 'Reject URL', 'gravityflow' ),
				'workflow_reject_token'  => esc_html__( 'Reject Token', 'gravityflow' ),
				'current_step'           => esc_html__( 'Current Step', 'gravityflow' ),
			);
		}

		/**
		 * Register the Gravity Flow capabilities group with the Members plugin.
		 *
		 * @since 1.8.1-dev
		 */
		public function members_register_cap_group() {
			members_register_cap_group(
				'gravityflow',
				array(
					'label' => $this->get_short_title(),
					'icon'  => 'dashicons-gravityflow-icon',
					'caps'  => array(),
				)
			);
		}

		/**
		 * Register the capabilities and their human readable labels with the Members plugin.
		 *
		 * @since 1.8.1-dev
		 */
		public function members_register_caps() {
			$caps = $this->get_members_caps();

			foreach ( $caps as $cap => $label ) {
				members_register_cap(
					$cap,
					array(
						'label' => $label,
						'group' => 'gravityflow'
					)
				);
			}
		}

		/**
		 * Get the capabilities and their human readable labels to be registered with the Members plugin.
		 *
		 * @since 1.8.1-dev
		 */
		public function get_members_caps() {
			$status_label = $this->translate_navigation_label( 'status' );
			$caps         = array(
				'gravityflow_inbox'                         => $this->translate_navigation_label( 'inbox' ),
				'gravityflow_workflow_detail_admin_actions' => __( 'Entry Detail Admin Actions', 'gravityflow' ),
				'gravityflow_submit'                        => $this->translate_navigation_label( 'submit' ),
				'gravityflow_status'                        => $status_label,
				'gravityflow_status_view_all'               => $status_label . ' - ' . __( 'View All', 'gravityflow' ),
				'gravityflow_admin_actions'                 => $status_label . ' - ' . __( 'Admin Actions', 'gravityflow' ),
				'gravityflow_reports'                       => $this->translate_navigation_label( 'reports' ),
				'gravityflow_activity'                      => $this->translate_navigation_label( 'activity' ),
				'gravityflow_settings'                      => __( 'Manage Settings', 'gravityflow' ),
				'gravityflow_uninstall'                     => __( 'Uninstall', 'gravityflow' ),
				'gravityflow_create_steps'                  => __( 'Manage Form Steps', 'gravityflow' ),
			);

			return apply_filters( 'gravityflow_members_capabilities', $caps );
		}

		/**
		 * Renders the header for the tabs UI.
		 *
		 * Fixes an issue in the add-on framework where tab links don't clean existing params.
		 *
		 * @param array  $tabs        The app tabs.
		 * @param string $current_tab The current tab name.
		 * @param string $title       The page title.
		 * @param string $message     The message to be displayed above the page title.
		 */
		public function app_tab_page_header( $tabs, $current_tab, $title, $message = '' ) {
			$legacy = version_compare( GFForms::$version, '2.5-dev-1', '<' ) ? true : false;

			if ( $legacy ) {

				// Print admin styles.
				wp_print_styles( array( 'jquery-ui-styles', 'gform_admin' ) );

				?>

				<div class="gravityflow_wrap <?php echo GFCommon::get_browser_class() ?>">

				<?php if ( $message ) { ?>
					<div id="message" class="updated"><p><?php echo $message; ?></p></div>
				<?php } ?>

				<h2><?php echo esc_html( $title ) ?></h2>

				<div id="gform_tab_group" class="gform_tab_group vertical_tabs">
				<ul id="gform_tabs" class="gform_tabs">
					<?php
					foreach ( $tabs as $tab ) {
						if ( isset( $tab['permission'] ) && ! $this->current_user_can_any( $tab['permission'] ) ) {
							continue;
						}
						$label = isset( $tab['label'] ) ? $tab['label'] : $tab['name'];
						?>
						<li <?php echo urlencode( $current_tab ) == $tab['name'] ? "class='active'" : '' ?>>
							<a href="<?php echo esc_url( add_query_arg( array(
								'page' => 'gravityflow_settings',
								'view' => $tab['name'],
							), admin_url( 'admin.php' ) ) ); ?>"><?php echo esc_html( $label ) ?></a>
						</li>
						<?php
					}
					?>
				</ul>

				<div id="gform_tab_container" class="gform_tab_container">
				<div class="gform_tab_content" id="tab_<?php esc_attr_e( $current_tab ); ?>">

				<?php
				return;
			}

			wp_print_styles( array( 'jquery-ui-styles', 'gform_admin', 'gform_settings' ) );

			?>

		<div class="wrap <?php echo GFCommon::get_browser_class() ?>">

			<header class="<?php echo esc_attr( $this->get_slug() ); ?>-app-settings-header">
				<div class="<?php echo esc_attr( $this->get_slug() ); ?>-app-settings__wrapper">
					<img width="300"
					     src="<?php echo esc_url( gravity_flow()->get_base_url() ); ?>/images/gravity-flow-logo.svg"/>
					<div class="gform-settings-header_buttons">
						<?php echo apply_filters( 'gform_settings_header_buttons', '' ); ?>
					</div>
				</div>
			</header>

			<?php if ( $message ) { ?>
				<div id="message" class="updated"><p><?php echo $message; ?></p></div>
			<?php } ?>

			<div class="gform-settings__wrapper">

			<nav class="gform-settings__navigation">
				<?php
				foreach ( $tabs as $tab ) {

					// Check for capabilities.
					if ( isset( $tab['permission'] ) && ! $this->current_user_can_any( $tab['permission'] ) ) {
						continue;
					}

					// Prepare tab label, URL.
					$label = isset( $tab['label'] ) ? $tab['label'] : $tab['name'];
					$url   = add_query_arg( array(
						'page' => 'gravityflow_settings',
						'view' => $tab['name'],
					), admin_url( 'admin.php' ) );

                    // Get tab icon.
					$icon_markup = Gravity_Flow_Common::get_icon_markup( $tab );

					printf(
						'<a href="%s"%s><span class="icon">%s</span> <span class="label">%s</span></a>',
						esc_url( $url ),
						$current_tab === $tab['name'] ? ' class="active"' : '',
						is_null( $icon_markup ) ? '<i class="gflow-icon gflow-icon--tool"></i>' : $icon_markup,
						esc_html( $label )
					);

				}
				?>
			</nav>

		<div class="gform-settings__content" id="tab_<?php echo esc_attr( $current_tab ); ?>">

			<?php

		}

		/**
		 * Get the site cookie path.
		 *
		 * @return string
		 */
		public function get_cookie_path() {
			$site_cookie_path = SITECOOKIEPATH;

			/**
			 * Allow the site cookie path to be overridden.
			 *
			 * @since 1.9.2-dev
			 *
			 * @param string $site_cookie_path The site cookie path.
			 */
			return apply_filters( 'gravityflow_site_cookie_path', $site_cookie_path );
		}

		/**
		 * Adds the invalid license admin notice.
		 *
		 * @since 2.2.4
		 */
		public function action_admin_notices() {

			$suppress_on_multisite = defined( 'GRAVITY_FLOW_LICENSE_KEY' ) || ! is_main_site();

			if ( is_multisite() && $suppress_on_multisite ) {
				return;
			}

			$pending_installation = ! is_multisite() && ( get_option( 'gravityflow_pending_installation' ) || isset( $_GET['gravityflow_installation_wizard'] ) );

			if ( $pending_installation ) {
				return;
			}

			$settings_prefix = version_compare( GFForms::$version, '2.5-dev-1', '<' ) ? 'gaddon' : 'gform';

			$is_saving_license_key = isset( $_POST[ "_{$settings_prefix}_setting_license_key" ] ) && isset( $_POST['_gravityflow_save_settings_nonce'] );

			$license_details = false;

			if ( $is_saving_license_key ) {
				$posted_license_key = sanitize_text_field( rgpost( "_{$settings_prefix}_setting_license_key" ) );
				if ( wp_verify_nonce( $_POST['_gravityflow_save_settings_nonce'], 'gravityflow_save_settings' ) ) {
					$license_details = $posted_license_key ? $this->activate_license( $posted_license_key ) : false;
				}
				if ( $license_details ) {
					$expiration = DAY_IN_SECONDS + rand( 0, DAY_IN_SECONDS );
					set_transient( 'gravityflow_license_details', $license_details, $expiration );
				}
			} else {
				$license_details = get_transient( 'gravityflow_license_details' );
				if ( ! $license_details ) {
					$last_check = get_option( 'gravityflow_last_license_check' );
					if ( $last_check > time() - 5 * MINUTE_IN_SECONDS ) {
						return;
					}

					$license_key     = defined( 'GRAVITY_FLOW_LICENSE_KEY' ) ? GRAVITY_FLOW_LICENSE_KEY : '';
					$license_details = $this->check_license( $license_key );
					if ( $license_details ) {
						if ( defined( 'GRAVITY_FLOW_LICENSE_KEY' ) && in_array( $license_details->license, array( 'site_inactive', 'inactive' ) ) ) {
							$license_details = $this->activate_license( GRAVITY_FLOW_LICENSE_KEY );
						}
						$expiration = DAY_IN_SECONDS + rand( 0, DAY_IN_SECONDS );
						set_transient( 'gravityflow_license_details', $license_details, $expiration );
						update_option( 'gravityflow_last_license_check', time() );
					}
				}
			}

			$license_status = $license_details ? $license_details->license : '';

			if ( $license_status != 'valid' ) {

				$add_buttons = ! is_multisite();

				$primary_button_link = admin_url( 'admin.php?page=gravityflow_settings' );

				$message = '';

				switch ( $license_status ) {
					case 'expired':
						/* translators: %s is the title of the plugin */
						$message     .= sprintf( esc_html__( 'Your %s license has expired.', 'gravityflow' ), $this->_title );
						$add_buttons = false;
						break;
					case 'invalid':
						/* translators: %s is the title of the plugin */
						$message .= sprintf( esc_html__( 'Your %s license is invalid.', 'gravityflow' ), $this->_title );
						break;
					case 'deactivated':
						/* translators: %s is the title of the plugin */
						$message .= sprintf( esc_html__( 'Your %s license is inactive.', 'gravityflow' ), $this->_title );
						break;
					/** @noinspection PhpMissingBreakStatementInspection */
					case '':
						$license_status = 'site_inactive';
					// break intentionally left blank
					case 'inactive':
					case 'site_inactive':
					default:
						/* translators: %s is the title of the plugin */
						$message .= sprintf( esc_html__( 'Your %s license has not been activated.', 'gravityflow' ), $this->_title );
						break;
				}

				$message .= ' ' . esc_html__( "This means you're missing out on security fixes, updates and support.", 'gravityflow' );

				$url = 'https://gravityflow.io/?utm_source=admin_notice&utm_medium=admin&utm_content=' . $license_status . '&utm_campaign=Admin%20Notice#pricing';

				// Show a different notice on settings page for inactive licenses (hide the buttons)
				if ( ! defined( 'GRAVITY_FLOW_LICENSE_KEY' ) && $add_buttons && ! $this->is_app_settings() ) {
					$message .= '<br /><br />' . esc_html__( '%sActivate your license%s or %sget a license here%s', 'gravityflow' );
					$message = sprintf( $message, '<a href="' . esc_url( $primary_button_link ) . '" class="button button-primary">', '</a>', '<a href="' . esc_url( $url ) . '" class="button button-secondary">', '</a>' );
				}

				$key = 'gravityflow_license_notice_' . date( 'Y' ) . date( 'z' );

				$notice = array(
					'key'          => $key,
					'capabilities' => 'gravityflow_settings',
					'type'         => 'error',
					'text'         => $message,
				);

				$notices = array( $notice );

				GFCommon::display_dismissible_message( $notices );
			}

			// Deprecation warning for Gravity Forms < 2.5.
			// To have it only apply on Settings > Workflow - Add  && rgget( 'view' ) == 'settings' && rgget( 'subview' ) == 'gravityflow'
			if ( ! $this->is_gravityforms_supported( '2.5-beta' ) && rgget( 'page' ) == 'gf_edit_forms' ) {
				echo sprintf('<div class="gf-notice notice notice-error"><p>%s</p></div>', __( 'This site is running a version of Gravity Forms that is not supported by Gravity Flow. Please upgrade to Gravity Forms 2.5 or later.', 'gravityflow' ) );
			}

		}

		/**
		 * Removes "page" from the query vars array when accessing the inbox/detail pages to fix an issue introduced in WP 5.5 where it results in a 404.
		 *
		 * @since 2.5.12
		 *
		 * @param array $query_vars The array of allowed query variable names.
		 *
		 * @return array
		 */
		public function filter_query_vars( $query_vars ) {
			global $wp_version;

			if ( rgget( 'page' ) === 'gravityflow-inbox' && version_compare( $wp_version, '5.5', '>=' ) ) {
				$query_vars = array_diff( $query_vars, array( 'page' ) );
			}

			return $query_vars;
		}

		/**
		 * Updates the WordPress auto_update_plugins option to enable or disable automatic updates so the correct state is displayed on the plugins page.
		 *
		 * @since 2.5.12
		 *
		 * @param bool $is_enabled Indicates if background updates are enabled for Gravity Flow in the app settings.
		 */
		public function update_wp_auto_updates( $is_enabled ) {
			$option       = 'auto_update_plugins';
			$auto_updates = (array) get_site_option( $option, array() );

			if ( $is_enabled ) {
				$auto_updates[] = GRAVITY_FLOW_PLUGIN_BASENAME;
				$auto_updates   = array_unique( $auto_updates );
			} else {
				$auto_updates = array_diff( $auto_updates, array( GRAVITY_FLOW_PLUGIN_BASENAME ) );
			}

			$callback = array( $this, 'action_update_site_option_auto_update_plugins' );
			remove_action( 'update_site_option_auto_update_plugins', $callback );
			update_site_option( $option, $auto_updates );
			add_action( 'update_site_option_auto_update_plugins', $callback, 10, 3 );
		}

		/**
		 * Updates the background updates app setting when the WordPress auto_update_plugins option is changed.
		 *
		 * @since 2.5.12
		 *
		 * @param string $option    The name of the option.
		 * @param array  $value     The current value of the option.
		 * @param array  $old_value The previous value of the option.
		 */
		public function action_update_site_option_auto_update_plugins( $option, $value, $old_value ) {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX && ! empty( $_POST['asset'] ) && ! empty( $_POST['state'] ) ) {
				// Option is being updated by the ajax request performed when using the enable/disable auto-updates links on the plugins page.
				$asset = sanitize_text_field( urldecode( $_POST['asset'] ) );
				if ( $asset !== GRAVITY_FLOW_PLUGIN_BASENAME ) {
					return;
				}

				$is_enabled = $_POST['state'] === 'enable';
			} else {
				// Option is being updated by some other means.
				$is_enabled  = in_array( GRAVITY_FLOW_PLUGIN_BASENAME, $value );
				$was_enabled = in_array( GRAVITY_FLOW_PLUGIN_BASENAME, $old_value );

				if ( $is_enabled === $was_enabled ) {
					return;
				}
			}

			$settings = $this->get_app_settings();

			if ( $settings['background_updates'] != $is_enabled ) {
				$settings['background_updates'] = $is_enabled;
				$this->update_app_settings( $settings );
			}
		}

		/**
		 * Renders the app settings uninstall tab.
		 *
		 * @since 2.7.4
		 */
		public function app_settings_uninstall_tab() {

			if ( $this->maybe_uninstall() ) {
				GFAddOn::app_settings_uninstall_tab();
			} else {
				if ( $this->current_user_can_uninstall() ) {
					?>

					<div class="gform-settings-panel">
						<header class="gform-settings-panel__header">
							<h4 class="gform-settings-panel__title"><?php esc_html_e( 'Uninstall Gravity Flow', 'gravityflow' ); ?></h4>
						</header>
						<div class="gform-settings-panel__content">
							<p class="alert error">
							<?php echo $this->uninstall_warning_message() ?>
							</p>
							<form action="" method="post">
								<?php
									if ( GFCommon::current_user_can_uninstall() ) {

										wp_nonce_field( 'gform_uninstall', 'gform_uninstall_nonce' );

										$uninstall_button = '<input type="submit" name="uninstall" value="' . sprintf( esc_attr__( 'Uninstall %s', 'gravityflow' ), $this->get_short_title() ) . '" class="button" onclick="return confirm(\'' . esc_js( $this->uninstall_confirm_message() ) . '\');" onkeypress="return confirm(\'' . esc_js( $this->uninstall_confirm_message() ) . '\');"/>';
										echo $uninstall_button;

									}
								?>
							</form>
						</div>
					</div>
					<?php

					self::uninstall_extensions();
				}
			}
		}

		/**
		 * Renders the extension panels and processes the uninstallation request.
		 *
		 * @since 2.7.5
		 */
		private static function uninstall_extensions() {
			$installed_extensions = Gravity_Flow_Common::$_extensions;

			if ( isset( $_POST['gflow_extension_uninstall'] ) && wp_verify_nonce( $_POST['gflow_extension_uninstall'], 'gflow_extension_uninstall' ) && isset( $_POST['addon'] ) && isset( $installed_extensions[ $_POST['addon'] ] ) ) {
				$installed_extensions[ $_POST['addon'] ]->uninstall_addon();
				unset( $installed_extensions[ $_POST['addon'] ] );
				if ( wp_safe_redirect( $_SERVER['HTTP_REFERER'] ) ) {
					exit;
				}
			}

			?>
			<div class="gform-addons-uninstall-panel">
			<?php
			foreach ( $installed_extensions as $extension ) {
				$extension->render_uninstall();
			}
			?>
			</div>
			<?php
		}

		/**
		 * Inits the TranslationsPress integration.
		 *
		 * @since 2.5.6
		 */
		public function init_translations() {
			Translations\Manager::get_instance( $this->get_slug() );
		}

		/**
		 * Uses TranslationsPress to install translations for the specified locale.
		 *
		 * @since 2.5.6
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
		 * @since 2.5.6
		 *
		 * @return array
		 */
		public function get_installed_locales() {
			return Translations\Manager::get_instance( $this->get_slug() )->get_installed_translations();
		}

	}
}
