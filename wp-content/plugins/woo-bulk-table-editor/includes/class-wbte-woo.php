<?php
/**
 * Bulk Table Editor
 *
 * @package BulkTableEditor/includes
 */

defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Admin\Features\Navigation\Menu;
use Automattic\WooCommerce\Admin\Features\Navigation\Screen;

require_once __DIR__ . '/class-wbte-functions.php';

if ( ! class_exists( 'Wbte_Woo' ) ) {

	/**
	 * Class for admin tasks
	 */
	class Wbte_Woo {

		/**
		 * Action & Hooks
		 */
		public static function wbte_admin_hooks() {
			
			add_action( 'admin_menu', array( __CLASS__, 'wbte_woocommerce_submenu_page' ) );
			add_action( 'admin_notices', array( __CLASS__, 'wbte_admin_activation_notice_success' ) );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'wbte_header_scripts' ) );
			add_filter( 'admin_footer_text', array( __CLASS__, 'wbte_set_footer_text' ) );
			add_action( 'woocommerce_admin_field_button', array( __CLASS__, 'wbte_add_admin_field_select' ) );
			add_action( 'admin_head', array( __CLASS__, 'wbte_add_button_wbte' ) );
			add_action( 'admin_post_return_products_csv_file', array( __CLASS__, 'wbte_create_csv_file' ) );
			
			//Wbte
			add_action( 'wbte_action_cyp_create_link_to_cyp', array( __CLASS__, 'wbte_create_link_to_cyp' ) );
			add_action( 'wbte_update_table_rows_data_async', array( __CLASS__, 'wbte_update_table_rows_data' ) );
			add_action( 'wbte_update_table_rows_ext_data_async', array( __CLASS__, 'wbte_update_table_rows_ext_data' ) );
			add_action( 'wbte_delete_table_rows_data_async', array( __CLASS__, 'wbte_delete_table_rows_data' ) );
			add_filter( 'wp_dropdown_cats', array( __CLASS__, 'wbte_add_multiple_select' ), 10, 2 );
			
			//Ajax
			add_action( 'wp_ajax_wbte_update_table_rows_data', array( __CLASS__, 'wbte_update_table_rows_ajax_handler' ) );
			add_action( 'wp_ajax_nopriv_wbte_update_table_rows_data', array( __CLASS__, 'wbte_update_table_rows_ajax_handler' ) );
			add_action( 'wp_ajax_wbte_update_table_rows_ext_data', array( __CLASS__, 'wbte_update_table_rows_ext_ajax_handler' ) );
			add_action( 'wp_ajax_nopriv_wbte_update_table_rows_ext_data', array( __CLASS__, 'wbte_update_table_rows_ext_ajax_handler' ) );
			add_action( 'wp_ajax_wbte_delete_table_rows_data', array( __CLASS__, 'wbte_delete_table_rows_ajax_handler' ) );
			add_action( 'wp_ajax_nopriv_wbte_delete_table_rows_data', array( __CLASS__, 'wbte_delete_table_rows_ajax_handler' ) );
			
			//Add settings
			add_filter( 'woocommerce_get_settings_pages', array( __CLASS__, 'wbte_add_settings' ) );

			self::wbte_load_translations();
			
		}
		
		/**
		 * Create submenu in products
		 */
		public static function wbte_woocommerce_submenu_page() {

			add_submenu_page(
				'edit.php?post_type=product',
				__( 'Bulk Table Editor', 'woo-bulk-table-editor' ),
				__( 'Bulk Table Editor', 'woo-bulk-table-editor' ),
				'manage_woocommerce',
				'wbte-products',
				array( __CLASS__, 'wbte_products_page' ),
				1
			);

			if (
				! class_exists( '\Automattic\WooCommerce\Admin\Features\Navigation\Menu' ) ||
				! class_exists( '\Automattic\WooCommerce\Admin\Features\Navigation\Screen' )
			) {
				return;
			}

			Menu::add_plugin_item(
				array(
					'id'         => 'woo-bulk-table-editor',
					'title'      => __( 'Bulk Table Editor', 'woo-bulk-table-editor' ),
					'capability' => 'manage_woocommerce',
					'url'        => 'wbte-products',
				)
			);
			
		}
		/**
		 * Get Bulk Table Editor main page
		 */
		public static function wbte_products_page() {

			if ( class_exists( 'WooCommerce' ) ) {
				require_once __DIR__ . '/class-wbte-templates.php';
				$editor = new WbteTemplates();
				$editor->wbte_load(); 
			}

		}


		/**
		 * Add settings and language support
		 */
		public static function wbte_add_settings( $settings ) {

			$settings[] = include_once dirname( __FILE__ ) . '../../includes/class-wbte-settings.php';
			return $settings;

		}

		/**
		 * Add translations
		 */
		public static function wbte_load_translations() {

			load_plugin_textdomain( 'woo-bulk-table-editor', false, basename( dirname( __FILE__ ) ) . '../languages' );

		}

		/**
		 * On activation notice
		 */
		public static function wbte_admin_activation_notice_success() {

			$allowed_tags = array(
				'a' => array(
					'class'  => array(),
					'href'   => array(),
					'target' => array(),
					'title'  => array(),
				),
			);
			/* translators: %s: url for documentation */
			$read_doc = __( 'Read the <a href="%1$s" target="_blank"> extension documentation </a> for more information.', 'woo-bulk-table-editor' );
			$out_str  = sprintf( $read_doc, esc_url( 'https://docs.woocommerce.com/document/woocommerce-bulk-table-editor' ) );

			if ( get_transient( 'wbte-admin-notice-activated' ) ) {
				?>
				<div class="updated woocommerce-message">
					<a class="woocommerce-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wc-hide-notice', 'wbte_admin_activation_notice_success' ), 'woocommerce_hide_notices_nonce', '_wc_notice_nonce' ) ); ?>"><?php esc_html_e( 'Dismiss', 'woo-bulk-table-editor' ); ?></a>
					<p>
						<?php esc_html_e( 'Thank you for installing Bulk Table Editor for WooCommerce. You can now start to bulk update products. ', 'woo-bulk-table-editor' ); ?>
						<?php echo wp_kses( $out_str, $allowed_tags ); ?>
					</p>
					<p class="submit">
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=product&page=wbte-products' ) ); ?>" class="button-primary"><?php esc_html_e( 'Start bulk editing products', 'woo-bulk-table-editor' ); ?></a>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=wbte' ) ); ?>" class="button-secondary"><?php esc_html_e( 'Settings', 'woo-bulk-table-editor' ); ?></a>
					</p>
				</div>

				<?php
				delete_transient( 'wbte-admin-notice-activated' );
			}
		}
		
		/**
		 * Add scripts and style
		 *
		 * @param var $hook object.
		 */
		public static function wbte_header_scripts( $hook ) {

			if ( 'product_page_wbte-products' !== $hook ) {
				return;
			}

			global $wbte_version;

			wp_register_script( 'wbte_jquery-ui', 'https://code.jquery.com/ui/1.13.1/jquery-ui.js', array( 'jquery' ), '1.13.1', true );
			wp_enqueue_script( 'wbte_jquery-ui' );

			wp_register_script( 'wbte_tablecalc', plugins_url( '../assets/js/jquery.tablecalc.min.js', __FILE__ ), array( 'jquery' ), $wbte_version, false );
			wp_enqueue_script( 'wbte_tablecalc' );

			wp_register_script( 'wbte_select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), $wbte_version, false );
			wp_enqueue_script( 'wbte_select2' );

			wp_register_script( 'wbte_scripts', plugins_url( '../assets/js/wbte-scripts.js', __FILE__ ), array( 'jquery' ), $wbte_version, true );
				
			wp_register_script( 'lozad', 'https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js', array(), '1', false );
			wp_enqueue_script( 'lozad' );

			wp_register_style( 'woocommerce_admin', plugins_url( '../plugins/woocommerce/assets/css/admin.css' ), array(), '1.12.1' );
			wp_enqueue_style( 'woocommerce_admin' );

			wp_register_style( 'fonta', 'https://use.fontawesome.com/releases/v5.2.0/css/all.css', array(), $wbte_version );
			wp_enqueue_style( 'fonta' );

			wp_register_style( 'jquery-select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '1.13.1', 'all' );
			wp_enqueue_style( 'jquery-select2' );

			wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css', array(), '1.13.1', 'all' );
			wp_enqueue_style( 'jquery-ui' );

			wp_register_style( 'wbte_css', plugins_url( '../assets/css/wbte.css', __FILE__ ), array(), $wbte_version );
			wp_enqueue_style( 'wbte_css' );

			wp_enqueue_media( 'wbte_scripts' );
			wp_enqueue_editor( 'wbte_scripts' );

			$options = get_option( 'wbte_options' );
			// Localize the script with new data
			$translation = array(
				'media_lib_title'     => __( 'Product image', 'woo-bulk-table-editor' ),
				'media_lib_button'    => __( 'Select image', 'woo-bulk-table-editor' ),
				'sku_count'           => ( $options['wbte_sku_count'] ) ? $options['wbte_sku_count'] : 3,
				'sku_delimiter'       => ( $options['wbte_sku_delimiter'] ) ? $options['wbte_sku_delimiter'] : '-',
				'products'		      => __( 'Products', 'woo-bulk-table-editor' ),
				'variations'	      => __( 'Variations', 'woo-bulk-table-editor' ),
				'disable_description' => ( $options['wbte_disable_description'] ) ? $options['wbte_disable_description'] : '',
			);
			wp_localize_script( 'wbte_scripts', 'wbte_object', $translation );
			wp_enqueue_script( 'wbte_scripts' );

		}

		/**
		 * Set footer text.
		 */
		public static function wbte_set_footer_text( $text ) {

			$page = filter_input( 1, 'page', FILTER_DEFAULT );

			if ( 'wbte-products' === $page ) {
				?>
				<span> </span>
				<?php

			} else {

				return $text;

			}

		}
		
		/**
		 * Add admin select category
		 *
		 * @param var $value object.
		 */
		public static function wbte_add_admin_field_select( $value ) {

			$wbte_options   = get_option( 'wbte_options' );
			$wbte_functions = new WbteFunctions();

			?>
				<tr valign="top">
					<th scope="row">
						<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_attr( $value['title'] ); ?></label>
						<span class="woocommerce-help-tip" data-tip="<?php echo esc_attr( $value['desc_tip'] ); ?>"></span>
					</th>
					<td>
						<?php $wbte_functions->wbte_settings_get_categories_select( $wbte_options ); ?>
					</td>
				</tr>
			<?php
		}

		/**
		 * Add button to edit product
		 */
		public static function wbte_add_button_wbte() {
			
			global $current_screen;

			if ( 'product' !== $current_screen->id ) {
				return;
			}

			?>
			<script>
			jQuery( function() {
				jQuery(' <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=product&page=wbte-products' ) ); ?>" class="page-title-action"><?php esc_attr_e( 'Bulk Table Editor', 'woo-bulk-table-editor' ); ?></a>').insertBefore('.wp-header-end');
			});
			</script>
			<?php
			
		}

		/** 
		 * If Calculate your price / Cost & Reports / Bulk Category Editor is present create link
		 */
		public static function wbte_create_link_to_cyp() {

			if ( is_plugin_active( 'woo-calculate-your-price/woo-calculate-your-price.php' ) ) {
				
				global $cyp_version;
				$p_cat = filter_input( 1, 'product_cat', FILTER_DEFAULT );

				if ( version_compare( $cyp_version, '3.0.1', '>=' ) >= 0 ) {
					?>
					<a type="button" href="<?php echo esc_url( admin_url( 'admin.php?page=crw&product_cat=' . $p_cat ) ); ?>" class="button" id="wbte" style="margin-left:4px;">
						<i class="fas fa-chart-line"></i> <?php esc_attr_e( 'Cost & Reports', 'woo-bulk-table-editor' ); ?></a>
					<?php
				} else {
					?>
					<a type="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=product&page=cyp&product_cat=' . $p_cat ) ); ?>" class="button" id="wbte" style="margin-left:4px;">
						<i class="fas fa-chart-line"></i> <?php esc_attr_e( 'Calculate Your Price', 'woo-bulk-table-editor' ); ?></a>
					<?php
				}

			}

			if ( is_plugin_active( 'woo-bulk-category-editor/woo-bulk-category-editor.php' ) ) {
				?>
					<a type="button" href="<?php echo esc_url( admin_url( 'admin.php?page=wbc' ) ); ?>" class="button" id="wbc" style="margin-left:4px;">
					<i class="fas fa-th-list"></i> <?php esc_attr_e( 'Bulk Category Editor', 'woo-bulk-table-editor' ); ?></a>
				<?php
			}

		}

		/**
		 * Update product
		 */
		public static function wbte_update_table_rows_ajax_handler() {
		
			check_ajax_referer( 'footer_id', 'nonce' );
			$data = wp_unslash( $_POST );

			if ( apply_filters( 'wbte_update_table_rows_data_async', true ) ) {
				WC()->queue()->add( 'wbte_update_table_rows_data_async', $data, 'wbte_work_queue' );
			} else {
				self::wbte_update_table_rows_data( $data );
			}

			die();

		}

		/**
		 * Update product ext table
		 */
		public static function wbte_update_table_rows_ext_ajax_handler() {
			
			check_ajax_referer( 'footer_id_ext', 'nonce' );
			$data = wp_unslash( $_POST );

			if ( apply_filters( 'wbte_update_table_rows_ext_data_async', true ) ) {
				WC()->queue()->add( 'wbte_update_table_rows_ext_data_async', $data, 'wbte_work_queue' );
			} else {
				self::wbte_update_table_rows_ext_data( $data );
			}

			die();
		}

		/**
		 * Update products
		 * 
		 * @param var $data data.
		 */
		public static function wbte_update_table_rows_data( $data ) {

			$wbte = new WbteFunctions();
			$rows = ( isset( $data['rows'] ) ) ? (array) $data['rows'] : array();
			
			foreach ( $rows as $row ) {
				$wbte->wbte_update_product( $row );
			}
			
		}

		/**
		 * Update products ext table
		 * 
		 * @param var $data data.
		 */
		public static function wbte_update_table_rows_ext_data( $data ) {

			$wbte = new WbteFunctions();
			$rows = ( isset( $data['rows'] ) ) ? (array) $data['rows'] : array();

			foreach ( $rows as $row ) {
				$wbte->wbte_update_product_ext( $row );
			}	
			
		}

		/**
		 * Delete product (ajax)
		 */
		public static function wbte_delete_table_rows_ajax_handler() {
			
			check_ajax_referer( 'footer_id', 'nonce' );
			$data = wp_unslash( $_POST );

			if ( apply_filters( 'wbte_delete_table_rows_data_async', true ) ) {
				WC()->queue()->add( 'wbte_delete_table_rows_data_async', $data, 'wbte_work_queue' );
			} else {
				self::wbte_delete_table_rows_data( $data );
			}

			die();
		}

		/**
		 * Delete products
		 * 
		 * @param var $data data.
		 */
		public static function wbte_delete_table_rows_data( $data ) {

			$wbte = new WbteFunctions();
			$rows = ( isset( $data['rows'] ) ) ? (array) $data['rows'] : array();

			foreach ( $rows as $row ) {
				$wbte->wbte_move_to_trash( $row );
			}

		}

		/**
		 * Create csv file
		 */
		public static function wbte_create_csv_file() {

			$functions = new WbteFunctions();
			$functions->wbte_create_csv_export_file();

		}

		/**
		 * Add multiple select
		 */
		public static function wbte_add_multiple_select( $output, $args ) {
			
			if ( isset( $args['wbte_multi_select'] ) && $args['wbte_multi_select'] ) {

				$output   = preg_replace( '/^<select/i', '<select multiple ', $output );
				$output   = str_replace( "name='{$args['name']}'", "name='{$args['name']}[]'", $output );
				$selected = is_array($args['selected']) ? $args['selected'] : explode( ',', $args['selected'] );
				
				foreach ( array_map( 'trim', $selected ) as $value ) {
					$output = str_replace( "value=\"{$value}\"", "value=\"{$value}\" selected", $output );
				}
			}
		
			return $output;
		}

		

	}
	
	Wbte_Woo::wbte_admin_hooks();

}
