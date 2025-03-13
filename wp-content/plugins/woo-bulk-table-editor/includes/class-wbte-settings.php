<?php
/**
 * Bulk Table Editor Settings
 *
 * @package BulkTableEditor/Settings
 */

defined( 'ABSPATH' ) || exit;

/**
 * Settings for API.
 */
if ( class_exists( 'WC_Admin_Settings_WBTE', false ) ) {
	return new WC_Admin_Settings_WBTE();
}

/**
 * Wbte_Setting class
 */
class WC_Admin_Settings_WBTE extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'wbte';
		$this->label = __( 'Bulk Table Editor', 'woo-bulk-table-editor' );

		parent::__construct();
	
		register_setting( 'wbte_options_group', 'wbte_options_settings' );

	}

	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_sections() {
	
		$sections = array(
			''                  => __( 'General', 'woo-bulk-table-editor' ),
			'wbte_integrations' => __( 'Integration', 'woo-bulk-table-editor' ),
		);
		
		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );

	}

	/**
	 * Settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		global $current_section;
		$section  = $current_section;
		$settings = array();

		if ( '' === $section ) {

			$settings = array(

				array(
					'name' => __( 'Bulk Table Editor', 'woo-bulk-table-editor' ),
					'type' => 'title',
					'desc' => __( 'The following options are used to configure Bulk Table Editor', 'woo-bulk-table-editor' ),
					'id'   => 'wbte_general_options',
				),
				
				array(
					'name'     => __( 'Products per page', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'Will create a paging function', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_posts_per_page]',
					'type'     => 'number',
					'css'      => 'min-width:200px;',
				),

				array(
					'name'     => __( 'Default product category', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'Select product category or all products', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_product_cat]',
					'type'     => 'button',
					'css'      => 'min-width:200px;',
				),

				array(
					'name'     => __( 'Query products by statuses', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'Set the statuses you want to see, default all statuses is shown in the extension', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_product_status]',
					'type'     => 'multiselect',
					'css'      => 'min-width:200px;',
					'options'  => array(
						'publish' => 'Publish',
						'private' => 'Private',
						'draft'   => 'Draft',
						'pending' => 'Pending',
					)
				),

				array(
					'name'     => __( 'Automatic stock management', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'Turns off stock management when stock has null/zero values, defaults sets status to: in stock', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_no_stock_management]',
					'type'     => 'checkbox',
					'css'      => 'min-width:200px;',
				),

				array(
					'name'     => __( 'Disable autofocus', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'Disables autofocus on input fields and controls in table', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_no_autofocus]',
					'type'     => 'checkbox',
					'css'      => 'min-width:200px;',
				),

				array(
					'name'     => __( 'Disable description', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'Disables description field, increase of performance if many products/variations', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_disable_description]',
					'type'     => 'checkbox',
					'css'      => 'min-width:200px;',
				),

				array(
					'name'     => __( 'Autofocus on date selectors', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'If checked the table date selectors in focus when mouse over', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_date_format_autofocus]',
					'type'     => 'checkbox',
					'css'      => 'min-width:200px;',
				),

				array(
					'name'     => __( 'Filter ON SALE by Query (default JS)', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'If many products this function should not be used (no paging so all products is loaded)', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_table_sale_filter]',
					'type'     => 'checkbox',
					'css'      => 'min-width:200px;',
				),
				
				array(
					'name'     => __( 'SKU generator length', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'Default 3 letters. I.e a product variation: Shirt, Blue, large generates to: shi-blu-lar', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_sku_count]',
					'type'     => 'number',
					'css'      => 'min-width:200px;',
				),

				array(
					'name'     => __( 'SKU delimiter', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'Default SKU uses - and creates SHI-BLU-LAR when generating', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_sku_delimiter]',
					'type'     => 'select',
					'css'      => 'min-width:200px;',
					'options'  => array(
						'-'      => __( 'Use default ( - )', 'woo-bulk-table-editor' ),
						'.'      => __( 'Use dot ( . )', 'woo-bulk-table-editor' ),
					),
				),

				array(
					'type' => 'sectionend',
					'id'   => 'wbte_general_options',
				),
			);

		} elseif ( 'wbte_integrations' === $section ) {

			$settings = array(
				
				array(
					'name' => __( 'Bulk Table Editor', 'woo-bulk-table-editor' ),
					'type' => 'title',
					'desc' => __( 'The following options are used to configure Bulk Table Editor', 'woo-bulk-table-editor' ),
					'id'   => 'wbte_general_integrations',
				),
				
				array(
					'name'     => __( 'Custom price (slug)', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'Insert custom slug i.e _wholesaler_price. Recommended to use only a type of price and not i.e a percent value.', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_custom_price_1]',
					'type'     => 'text',
					'css'      => 'min-width:200px;',
					'class'    => 'wbte_custom_price',
				),

				array(
					'name'     => __( 'Custom price name', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'Your custom column name in the table, keep it short', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_custom_price_1_header]',
					'type'     => 'text',
					'css'      => 'min-width:200px;',
				),

				array(
					'name'     => __( 'Integrations (get slug)', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'Select integration, will only work if plugin is installed', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_integration]',
					'type'     => 'select',
					'css'      => 'min-width:200px;',
					'class'    => 'wbte_integration',
					'options'  => array(
						''                      => __( 'Select integration', 'woo-bulk-table-editor' ),
						'_suggested_price'      => __( 'Name your price (suggested price field)', 'woo-bulk-table-editor' ),
						'_cost_of_goods'        => __( 'Cost & Reports (cost of goods field)', 'woo-bulk-table-editor' ),
						'_wc_cog_cost'          => __( 'Cost of goods (cost of goods field)', 'woo-bulk-table-editor' ),
						'_wwp_wholesale_amount' => __( 'Wholesale for WooCommerce (wholesale field)', 'woo-bulk-table-editor' ),
					),
				),

				array(
					'name'     => __( 'Custom price - calculate like sales price', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'If checked it uses price for calculations i.e: normal price - % = custom price. Uncheked it is independent i.e: custom price - % = new custom price', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_custom_price_1_normal_calc]',
					'type'     => 'checkbox',
					'css'      => 'min-width:200px;',
				),

				array(
					'name'     => __( 'Show extra column', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'Must be checked to show the custom price or SKU on main page', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_custom_price_1_visible]',
					'type'     => 'checkbox',
					'css'      => 'min-width:200px;',
				),

				array(
					'name'     => __( 'Show SKU in main page', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'If checked SKU will show as custom column (replaces the custom integration price)', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_use_sku_main_page]',
					'type'     => 'checkbox',
					'css'      => 'min-width:200px;',
				), 

				array(
					'name'     => __( 'Enable Vendors', 'woo-bulk-table-editor' ),
					'desc_tip' => __( 'If checked a column for vendor and bulk functions is enabled for setting vendors on products', 'woo-bulk-table-editor' ),
					'id'       => 'wbte_options[wbte_vendor_integration]',
					'type'     => 'checkbox',
					'css'      => 'min-width:200px;',
				),
				
				array(
					'type' => 'sectionend',
					'id'   => 'wbte_general_integrations',
				),

			);
		}

		return apply_filters( 'wc_' . $this->id . '_settings', $settings );

	}
	
	/**
	 * Output the settings
	 *
	 * @since 1.0
	 */
	public function output() {
	
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::output_fields( $settings );
		echo esc_attr( $this->js_scripts() );
	}
			
	/**
	 * Output JS scripts
	 */
	public function js_scripts() {
		?>
		<script>
		var $ = jQuery;
		jQuery(document).ready(function ( $ ) {
			$('.wbte_integration').val('');
			$('.wbte_integration').on('change', setIntegration);
		});

		function setIntegration() {
			var selected = $('.wbte_integration option:selected').val();
			$('.wbte_custom_price').val( selected );
		}
		</script>
		<?php
	}

}

return new WC_Admin_Settings_WBTE();


