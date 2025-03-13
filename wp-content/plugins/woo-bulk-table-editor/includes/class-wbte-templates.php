<?php
/**
 * Bulk Table Editor templates
 *
 * @package BulkTableEditor/includes
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/class-wbte-functions.php';
require_once __DIR__ . '/class-wbte-extended.php';

/**
 * Class for templates
 */
class WbteTemplates {

	/**
	 * WbteFunctions
	 *
	 * @var var $wbtefunctions.
	 */
	public $wbtefunctions;

	/**
	 * WbteExtended
	 *
	 * @var var $extended.
	 */
	public $extended;

	/**
	 * WbteOptions
	 *
	 * @var var $wbte_options.
	 */
	public $wbte_options;


	/**
	 * Constructor
	 */
	public function __construct() {
		$this->wbtefunctions = new WbteFunctions();
		$this->wbte_options  = get_option( 'wbte_options' );
		$this->extended      = new WbteExtended();
	}

	/**
	 * Load the page and contents
	 */
	public function wbte_load() {

		$view = filter_input( 1, 'view', FILTER_DEFAULT );

		if ( ! isset( $view ) ) {
			$view = 'prod';
		}
		?>
		<div class="wbte-main">
		<?php
		
		$this->wbte_get_bte_header( $view );
		$this->wbte_get_table_paging_top( $view );

		if ( 'prod' === $view ) {
			$this->wbte_get_table();
		} else {
			$this->extended->wbte_get_table();
		}

		$this->wbte_get_mobile_view();
		$this->wbte_get_main_scripts();
		$this->wbte_get_sticky_footer();
		?>
		</div>
		<?php
		
	}

	/**
	 * Scripts
	 */
	public function wbte_get_main_scripts() {

		$show_sku          = ( strlen( $this->wbte_options[ 'wbte_use_sku_main_page' ] ) > 0 ) ? $this->wbte_options[ 'wbte_use_sku_main_page' ] : 'no';
		$custom_price      = $this->wbtefunctions->wbte_get_custom_price_info();
		$view              = ( strlen ( filter_input( 1, 'view', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'view', FILTER_DEFAULT ) : 'prod';
		$show_vendors      = ( strlen( $this->wbte_options[ 'wbte_vendor_integration' ] ) > 0 ) ? $this->wbte_options[ 'wbte_vendor_integration' ] : 'no';
		$sum_col           = 8;
		$custom_col        = '';
		$custom_col_active = 'false';
		$_products		   = __( 'Products', 'woo-bulk-table-editor' );
		$_variations	   = __( 'Variations', 'woo-bulk-table-editor' );
		$_use_sale_filter  = ( strlen( $this->wbte_options[ 'wbte_table_sale_filter' ] ) > 0 ) ? $this->wbte_options[ 'wbte_table_sale_filter' ] : 'no';
		
		
		if ( 'yes' === $custom_price['active'] ) {
			$sum_col++;
			$custom_col        = ', 8 ';
			$custom_col_active = 'true';
		}

		if ( 'yes' === $show_vendors ) {
			$sum_col++;
		}

		?>
		<script>
			var tbl               = '#wbtetable';
			var tbl_ext           = '#wbtetable_ext';
			var $                 = jQuery;
			var sum_col           = <?php echo esc_js( $sum_col ); ?>;
			var custom_col_active = <?php echo esc_js( $custom_col_active ); ?>;
			var page_view         = '<?php echo esc_js( $view ); ?>';
			var show_sku_home     = '<?php echo esc_js( $show_sku ); ?>';
			var vendor_active     = '<?php echo esc_js( $show_vendors ); ?>';
			var cur_tbl           = ( 'ext' === page_view ) ? tbl_ext : tbl;
			var _products         = '<?php echo esc_attr( $_products ); ?>';
			var _variations       = '<?php echo esc_attr( $_variations ); ?>';
			var tbl_sale_filter   = '<?php echo esc_attr( $_use_sale_filter ); ?>';

			window.addEventListener('loading', wbteProgressBar('start'));

			jQuery(document).ready(function ( $ ) {

				if ( page_view === 'ext' ) {
					$( tbl_ext ).tableCalc({
						calcColumns: [ 6, 7, 8, 9 ], 
						calcColumn_sum: 10,
						rowId: 0, 
						textColumns: [ 1, 2, 3, 4, 5 ],
						calcType: 'c', 
						calcCustom: '',
						decimals: 2, 
						calcOnLoad: false, 
						onEvent: '', 
					});

					$( tbl_ext +' tr td input' ).on( 'change', extChanged );
					$( tbl_ext +' tr td select' ).on( 'change', extChanged );
					

				} else {
					$( tbl ).tableCalc({
						calcColumns: [ 2, 3, 4, 7<?php echo esc_js( $custom_col ); ?>], 
						calcColumn_sum: sum_col,
						rowId: 0, 
						textColumns: [ 5, 6 ], 
						calcType: 'c', 
						calcCustom: '(0:0 * 0:1)',
						decimals: 2, 
						calcOnLoad: false, 
						onEvent: 'change', 
					});

					$( tbl +' tr td input' ).on( 'change', calcChanged );
					$( tbl +' tr td select' ).on( 'change', calcChanged );

					calcChanged( true );
				}

				
				
				//Row search
				$( '#product_search' ).on( 'keypress', function(event) {
					if (event.key === 'Enter'){
						event.preventDefault();
						rowSearch();
					}
				} );
				$( '#product_search' ).on( 'input', function() {
					if ( this.value === ''){
						$( cur_tbl + ' tbody tr' ).show();
						wbtePrintCount(cur_tbl);
						$('#wbte-pages').find('a').each(function(){
							var url    = $(this).attr('href');
							var newUrl = url.replace( /\&row_search\=.*/i, '' );
							$(this).attr('href', newUrl );
						});
						wbteCalculateFooterTotals();
					}
				});
				$( "input[name='stype']" ).on( 'click', radioChange );
				
				//If search exist - apply to new page
				if ( $('#product_search').val().length > 0 ) {
					rowSearch();
				}

				//Sales filter
				var params = location.search;
				if ( 'yes' !== tbl_sale_filter && params.search('sales_filter=') > 0 ) {
					wbteFilterSales();
				}
				if ( params.search('tags-filter=') > 0 ) {
					wbteFilterTags();
				}

				var observer = lozad(); // lazy loads elements
				observer.observe();
				
				var wp_date_format = 'yy-mm-dd';

				$('#datep_from').datepicker( { 'dateFormat': wp_date_format } );
				$('#datep_to').datepicker( { 'dateFormat': wp_date_format } );
				$('input[name="salefrom"]').datepicker(  { 'dateFormat': wp_date_format } );
				$('input[name="saleto"]').datepicker( { 'dateFormat': wp_date_format } );
				
				$('#saveall').css( 'color', 'black' );

				// Add paging bottom
				$( '#wbte-paging-bottom' ).empty();
				$( '#wbte-paging-bottom' ).append( paging_buttons );

				wbteProgressBar('stop');
			});

			//Count products
			wbtePrintCount(cur_tbl);
			
			async function wbteCountProducts( wbte_table ) {

				var prod     = '0';
				var prod_var = '0';
				prod         = $( wbte_table + ' tbody tr[data-type="prod"]:visible').length;
				prod_var     = $( wbte_table + ' tbody tr[data-type="var"]:visible').length;

				return [ prod, prod_var ];

			}
			
			async function wbtePrintCount(cur_tbl){
			
				const result = await wbteCountProducts(cur_tbl);
				$('#wbte-prod-count').text( _products + ': ' + result[0] + ' | ' + _variations + ': ' + result[1] );

			}

			//Show progressbar when loading
			function wbteProgressBar( option ) {
	
				if (option === 'start') {
					$('#wbte-loading-page').removeClass('wbte-saving').addClass('wbte-saving-show');
					var v = 60;

					setInterval(
						function() {
							if ( v > 100) {
								v = 0;
							}
							$('#pbar-loading-page').val(v);
							v += 20;
						},
						10
					);
				} else {
					$('#wbte-loading-page').removeClass('wbte-saving-show').addClass('wbte-saving');
				}

			}

		</script>
		<?php
	}

	/**
	 * Sticky footer
	 */
	public function wbte_get_sticky_footer() {
		
		$view = filter_input( 1, 'view', FILTER_DEFAULT );
		?>
		<footer class="wbte-footer" style="display: flex;">
			<div style="flex: 0 0 37%;text-align:left;padding:4px;color:whitesmoke;">
			<?php 
			$img_file = plugins_url( '../consortia-100.png', __FILE__ );

			/* translators: %s: url to vendor and logo */ 
			printf( esc_html__( '- developed by %1$s%2$s%3$s', 'woo-bulk-table-editor' ), '<a href="https://www.consortia.no/en/" target="_blank">', '<img src="' . esc_attr( $img_file ) . '" class="cas-logo">', '</a>' );
			?>
			</div>
			<div style="flex: 1;text-align:left;">
			<button class="button" id="reset-values-footer" onclick="wbte_reset_table_values();" style="width:150px;"><i class="fas fa-undo"></i> <?php esc_attr_e( 'Undo changes', 'woo-bulk-table-editor' ); ?></button>
			<button type="button" id="saveall-foot" class="button action" onclick="<?php echo esc_js( ( 'ext' === $view ) ? 'saveAllExt()' : 'saveAll()' ); ?>;" style="width:150px;"><i class="fas fa-database"></i> <?php esc_html_e( 'Save', 'woo-bulk-table-editor' ); ?></button>
			</div>
		</footer>
		<?php
	}

	/**
	 * Check for autofocus
	 */
	public function wbte_get_autofocus_value( $type ) {
		
		$mouseover_value = 'this.focus();';

		if ( 'date' === $type ) {
			if ( 'yes' !== $this->wbte_options['wbte_date_format_autofocus'] ) {
				$mouseover_value = '';
			}
		} else {
			if ( 'yes' === $this->wbte_options['wbte_no_autofocus'] ) {
				$mouseover_value = '';
			}
		}

		return $mouseover_value;
	
	}

	/**
	 * Get header and search
	 *
	 * @param var $view page view.
	 */
	public function wbte_get_bte_header( $view ) {

		$get_prod_search = filter_input( 1, 'product_search', FILTER_DEFAULT );
		$get_row_search  = filter_input( 1, 'row_search', FILTER_DEFAULT );
		$stype           = filter_input( 1, 'stype', FILTER_DEFAULT );
		$prod_cat        = filter_input( 1, 'product_cat', FILTER_DEFAULT );
		$sales_filter    = filter_input( 1, 'sales_filter', FILTER_DEFAULT );
		$url             = admin_url( 'edit.php?post_type=product&page=wbte-products&sales_filter=' . $sales_filter . '&product_cat=' . $prod_cat . '&view=' );
		
		$auto_date  = 'date';
		$auto_focus = '';

		?>
		<div class="wrap">
			<div style="display: block;">
				<div style="width:50%;float:left;">
				<h1 class="wp-heading-inline"><?php esc_html_e( 'Bulk Table Editor', 'woo-bulk-table-editor' ); ?></h1>
				</div>
				<div style="width:50%;float:right;text-align:right;padding-top:12px;">
				<input type="hidden" id="wbte-prod-count-total" value="">
				<i class="fas fa-info-circle" style="padding-left: 15px;"></i><span id="wbte-prod-count" style="padding-left:5px;"></span>
				</div>
			</div>
		</div>

		<div class="row-container">
			<div style="display:inline-flex;padding-top:10px;">
			<?php
			if ( 'prod' === $view ) {
				?>
					<a type="button" href="#0" name="products" class="button-secondary disabled" style="margin-right:5px;">
					<i class="fas fa-home"></i> <?php esc_attr_e( 'Editor home', 'woo-bulk-table-editor' ); ?>
					</a>
					<a type="button" href="<?php echo esc_url( $url . 'ext' ); ?>" name="extend" class="button-primary" style="margin-right:5px;">
					<i class="fas fa-arrows-alt"></i> <?php esc_attr_e( 'Other values', 'woo-bulk-table-editor' ); ?>
					</a>
				<?php
			} else {
				?>
					<a type="button" href="<?php echo esc_url( $url . 'prod' ); ?>" name="products" class="button-primary" style="margin-right:5px;">
					<i class="fas fa-home"></i> <?php esc_attr_e( 'Editor home', 'woo-bulk-table-editor' ); ?>
					</a>
					<a type="button" href="#0" name="extended" class="button-secondary disabled" style="margin-right:5px;">
					<i class="fas fa-arrows-alt"></i> <?php esc_attr_e( 'Other values', 'woo-bulk-table-editor' ); ?>
					</a>
				<?php
			}
			
			?>
			<a type="button" href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=wbte' ) ); ?>" class="button" id="a-settings" style="margin-right:4px;"><i class="fas fa-cog"></i> </a> 
			<form method="post" id="download_products" action="<?php esc_attr_e( admin_url( 'admin-post.php?csv=true&product_cat=' . $prod_cat ) ); ?>">
				<input type="hidden" name="action" value="return_products_csv_file">
				<button type="submit" name="download_products" class="button">
					<i class="fas fa-cloud-download-alt"></i> 
				</button>
			</form>
			<?php do_action( 'wbte_action_cyp_create_link_to_cyp'); ?>
			</div>
			<div class="col-search display-desktop">
				<div>   
				<form name="gs" type="get" action="edit.php">  
					<input type="hidden" name="post_type" value="product">  
					<input type="hidden" name="page" value="wbte-products"> 
					<input type="hidden" name="view" id="form-gs-view" value="<?php echo esc_attr( $view ); ?>"> 
					<input type="search" value="<?php esc_attr_e( $get_row_search ); ?>" class="input-txt-search" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_focus ) ); ?>" placeholder="<?php esc_html_e( 'search', 'woo-bulk-table-editor' ); ?><?php echo ' ' . esc_attr( $get_prod_search ); ?>" id="product_search" name="product_search" >
					<button type="submit" id="btnsearch" class="button"><i class="fas fa-search"></i></button><br/>
					<div class="frm-radio">
					<?php esc_attr_e( 'Search in', 'woo-bulk-table-editor' ); ?>
					<input type="radio" name="stype" value="s" <?php echo ( 's' === $stype ) ? 'checked="checked"' : ''; ?>><?php esc_attr_e( 'Text', 'woo-bulk-table-editor' ); ?>
					<input type="radio" name="stype" value="sku" <?php echo ( 'sku' === $stype ) ? 'checked="checked"' : ''; ?>><?php esc_attr_e( 'SKU', 'woo-bulk-table-editor' ); ?>
					<input type="radio" name="stype" value="rows" <?php echo ( 'rows' === $stype || !isset( $stype ) ) ? 'checked="checked"' : ''; ?>><?php esc_attr_e( 'Rows', 'woo-bulk-table-editor' ); ?>
					</div>           
				</form>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get table head info
	 */
	public function wbte_get_table_head_info() {

		$custom_price    = $this->wbtefunctions->wbte_get_custom_price_info();
		$calculate_field = 4;
		$options         = get_option( 'wbte_options' );
		$show_sku        = ( strlen( $options[ 'wbte_use_sku_main_page' ] ) > 0 ) ? $options[ 'wbte_use_sku_main_page' ] : 'no';
		$show_vendors    = ( strlen( $options[ 'wbte_vendor_integration' ] ) > 0 ) ? $options[ 'wbte_vendor_integration' ] : 'no';

		if ( 'yes' === $custom_price['normal_calc'] ) {
			$calculate_field = 1;
		}

		$auto_date  = 'date';
		$auto_focus = '';

		?>
			<tr class="th-info">
				<th></th>
				<th style="vertical-align: middle;">
					<strong><?php esc_html_e( 'Bulk Editor', 'woo-bulk-table-editor' ); ?></strong><br />
					<span class="th-desc"><?php esc_html_e( 'Fill in values to apply to all visible products. Double click name for description, ID and SKU.', 'woo-bulk-table-editor' ); ?>
					</span>
				</th>
				<th style="vertical-align: top;"><input type="number" name="stock_select" id="stock_select" class="input-txt" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_focus ) ); ?>"><br/>
					<select name="stock_select_type" id="stock_select_type" class="input-select" onchange="calcSpecial(0,2,'stock_select');">
						<?php $this->wbtefunctions->wbte_get_bulk_options( 'stock' ); ?>
					</select>
				</th>
				<th style="vertical-align: top;"><input type="number" name="price_select" id="price_select" class="input-txt" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_focus ) ); ?>">
				<br/>
					<select name="price_select_type" id="price_select_type" class="input-select" onchange="calcSpecial(1,3,'price_select');">
						<?php $this->wbtefunctions->wbte_get_bulk_options( 'price' ); ?>
					</select>
				</th>
				<th style="vertical-align: top;"><input type="number" name="sale_price_select" id="sale_price_select" class="input-txt" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_focus ) ); ?>">
				<br/>
					<select name="sale_price_select_type" id="sale_price_select_type" class="input-select" onchange="calcSpecial(1,4,'sale_price_select');">
						<?php $this->wbtefunctions->wbte_get_bulk_options( 'sale_price' ); ?>
					</select>
				</th>
				<th style="vertical-align: top;">
					<input type="text" class="input-date" id="datep_from" onchange="setSalesDate(this);" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_date ) ); ?>;">
					<input type="time" style="width:100%;float:right;padding-left:5px;" oninput="setSalesTime(this);" id="datep_from_time" value="00:00" min="00:00" max="23:59" pattern="[0-9]{2}:[0-9]{2}">
				</th>
				<th style="vertical-align: top;">
					<input type="text" class="input-date" id="datep_to" onchange="setSalesDate(this);" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_date ) ); ?>;">
					<input type="time" style="width:100%;float:right;padding-left:5px;" oninput="setSalesTime(this);" id="datep_to_time" value="23:59" min="00:00" max="23:59" pattern="[0-9]{2}:[0-9]{2}">
				</th>
				<th><!-- Sale in percent --></th>
				<?php
				if ( 'yes' === $custom_price['active'] && 'no' === $show_sku ) {
					?>
					<th style="vertical-align: top;"><input type="number" name="custom_price_select" id="custom_price_select" class="input-txt" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_focus ) ); ?>">
					<br/>
						<select name="custom_price_select_type" id="custom_price_select_type" class="input-select" onchange="calcSpecial(<?php echo esc_js( $calculate_field ); ?>,8,'custom_price_select');">
							<?php $this->wbtefunctions->wbte_get_bulk_options( '' ); ?>
						</select>
					</th>
					<?php
				} elseif ( 'yes' === $show_sku ) {
					?>
					<th style="vertical-align: top;"><input type="text" name="sku_select" id="sku_select" class="input-txt" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_focus ) ); ?>">
					<br/>
						<select name="sku_select_type" id="sku_select_type" class="input-select" onchange="bulkSetValues('sku_select','');">
							<?php $this->wbtefunctions->wbte_get_bulk_options( 'sku' ); ?>
						</select>
					</th>
					<?php
				}
				if ( 'yes' === $show_vendors ) {
					?>
					<th style="vertical-align: bottom;">
					<select class="input-select" name="vendor_select_type" id="vendor_select_type" onchange="bulkSetValues('vendor_select','');">
						<?php $this->wbtefunctions->wbte_get_vendor_select(); ?>
					</select>
					</th>
					<?php
				}
				?>
				<th style="vertical-align: top;">
					<button type="button" id="saveall" class="button action" onclick='saveAll();' style="width:100%;"><i class="fas fa-database"></i> <?php esc_html_e( 'Save', 'woo-bulk-table-editor' ); ?></button>
					<div class="wbte-saving" id="wbte-saving">
						<progress id="pbar-saving" class="pbar-saving" value="0" max="100"></progress>
					</div>
					<div class="wbte-saving" id="wbte-loading-page">
						<progress id="pbar-loading-page" class="pbar-saving" value="0" max="100"></progress>
					</div>
				</th>
			</tr>

		<?php
	}


	/**
	 * Get table row
	 *
	 * @param var $product object.
	 * @param var $has_attributes bool.
	 * @param var $parent_id int.
	 */
	public function wbte_get_table_row( $product, $has_attributes, $parent_id ) {

		$product_name       = $product->get_title();
		$product_id         = $product->get_id();
		$custom_price       = $this->wbtefunctions->wbte_get_custom_price_info();
		$date_format        = get_option( 'date_format' );
		$options            = get_option( 'wbte_options' );
		$show_sku           = ( strlen( $options[ 'wbte_use_sku_main_page' ] ) > 0 ) ? $options[ 'wbte_use_sku_main_page' ] : 'no';
		$show_vendors       = ( strlen( $options[ 'wbte_vendor_integration' ] ) > 0 ) ? $options[ 'wbte_vendor_integration' ] : 'no';
		$custom_price_value = '';
		$prev_sale_price    = get_post_meta( $product->get_id(), '_wbte_prev_sale_price', true );
		$sale_time_from     = get_post_meta( $product->get_id(), '_wbte_sale_time_from', true );
		$sale_time_to       = get_post_meta( $product->get_id(), '_wbte_sale_time_to', true );
		$prev_price         = get_post_meta( $product->get_id(), '_wbte_prev_price', true );
		$disable_desc       = ( strlen( $options[ 'wbte_disable_description' ] ) > 0 ) ? $options[ 'wbte_disable_description' ] : 'no';
		$type               = ( $has_attributes ) ? 'var' : 'prod';

		if ( $has_attributes ) {

			$attributes = $product->get_attributes();

			if ( isset( $attributes ) && is_array( $attributes ) ) {
				foreach ( $attributes as $key => $val ) {
					if ( isset( $val ) && is_string( $val ) && strlen( $val ) > 0 ) {
						$product_name .= ', ' . ucfirst( $val );
					}
				}
			}

			$product_id = $product->get_parent_id();
			
			if ( 0 === $product_id ) {
				$product_id = $product->get_id();
			}
		}

		if ( 'yes' === $custom_price['active'] ) {
			$custom_price_value = get_post_meta( $product->get_id(), $custom_price['price'], true );
		}

		$auto_date  = 'date';
		$auto_focus = '';
		$tags       = $this->wbtefunctions->wbte_get_product_tags( $product->get_id() );

		?>
		<tr class="lozad" style="vertical-align: top;" data-type="<?php echo esc_attr( $type ); ?>">
			<td id="<?php echo esc_attr( $product->get_id() ); ?>" style="padding-top:10px;">
				<form id="frm_<?php echo esc_attr( $product->get_id() ); ?>_<?php echo esc_attr( wp_rand( 1, 50000 ) ); ?>" method="post">
				<input name="id" value="<?php echo esc_attr( $product->get_id() ); ?>" type="checkbox">
				<input type="hidden" name="tags" value="<?php echo esc_attr( $tags ); ?>">
				<input type="hidden" name="prev_sale_price" value="<?php echo esc_attr( $prev_sale_price ); ?>">
				<input type="hidden" name="prev_price" value="<?php echo esc_attr( $prev_price ); ?>">
			</td>
			<td style="display:flex;width:98%" ondblclick="wbte_show_desc('<?php echo esc_attr( $product->get_id() ); ?>');">
				
				<?php
				if ( ! $has_attributes ) {
					?>
					<div style="flex:1;width:100%;">
					<input type="text" class="input-txt-name" name="product_name" value="<?php echo esc_html( $product_name ); ?>" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_focus ) ); ?>;">
					<input type="hidden" name="name_update" value="true">
					<?php
				} else {
					?>
					<div style="flex:1;width:100%;padding-top:5px;">
					<input type="hidden" name="product_name" value="<?php echo esc_html( $product_name ); ?>">
					<input type="hidden" name="name_update" value="false">
					<?php echo esc_html( $product_name ); ?>
					<?php
				}
				?>
					<?php if ( 'no' === $disable_desc ) : ?>
					<div class="wbte-prod-desc-hide" id="wbte_desc_<?php echo esc_attr( $product->get_id() ); ?>" style="padding-top:4px;">
						<textarea id="desc_<?php echo esc_attr( $product->get_id() ); ?>" class="wbte-tiny-editor"><?php echo wp_kses( $product->get_description(), 'post' ); ?></textarea>
					</div>
					<?php endif; ?>
					<ul class="wbte-ul-info-hide" id="wbte_ul_desc_<?php echo esc_attr( $product->get_id() ); ?>">
						<li><?php esc_html_e( 'ID:', 'woo-bulk-table-editor' ); ?> <?php echo esc_attr( $product->get_id() ); ?></li>
						<li><?php esc_html_e( 'SKU:', 'woo-bulk-table-editor' ); ?> <?php echo esc_attr( $product->get_sku() ); ?></li>
					</ul>
				</div>
				<div style="flex: 0;text-align:center;">
					<a href="<?php echo esc_url( get_admin_url() . 'post.php?post=' . $product_id . '&action=edit' ); ?>">
						<i class="fas fa-edit"></i>
					</a>
					<button name="btn_desc" id="btn_desc_<?php echo esc_attr( $product->get_id() ); ?>" class="btn-desc-hide" onclick="wbte_hide_desc('<?php echo esc_attr( $product->get_id() ); ?>');" style="border:none;margin-top:15px;background:none;">
						<i class="fas fa-times-circle"></i>
					</button>
				</div>
			</td>
			<td>
				<input type="number" name="stock" class="input-txt" value="<?php echo esc_attr( $product->get_stock_quantity() ); ?>" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_focus ) ); ?>;"> 
			</td>
			<td>
				<input type="number" step="any" name="price" class="input-txt" value="<?php echo esc_attr( $product->get_regular_price() ); ?>" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_focus ) ); ?>"> 
			</td> 
			<td>
				<input type="number" step="any" name="saleprice" class="input-txt" value="<?php echo esc_attr( $product->get_sale_price() ); ?>" onchange="calcPercent(this);" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_focus ) ); ?>"> 
			</td>   
			<td>
			<?php
			$sale_from_value = '';
			if ( $product->get_date_on_sale_from() ) {
				$sale_from_value = date_format( $product->get_date_on_sale_from(), $date_format );
			};
			?>
				<input type="text" style="width:53%;float:left;margin:0;" name="salefrom" autocomplete="off" class="input-date" value="<?php echo esc_attr( $sale_from_value ); ?>" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_date ) ); ?>;"> 
				<input type="time" style="width:46%;float:right;margin:0;padding-left:2px;" name="salefrom_time" value="<?php echo ( strlen( $sale_time_from ) > 0 ) ? esc_attr( $sale_time_from ) : '00:00'; ?>" min="00:00" max="23:59" pattern="[0-9]{2}:[0-9]{2}">
			</td>
			<td>
			<?php
			$sale_to_value = '';
			if ( $product->get_date_on_sale_to() ) {
				$sale_to_value = date_format( $product->get_date_on_sale_to(), $date_format );
			}
			?>
				<input type="text" style="width:53%;float:left;margin:0;" name="saleto" autocomplete="off" class="input-date" value="<?php echo esc_attr( $sale_to_value ); ?>" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_date ) ); ?>;"> 
				<input type="time" style="width:46%;float:right;margin:0;padding-left:2px;" name="saleto_time" value="<?php echo ( strlen( $sale_time_to ) > 0 ) ? esc_attr( $sale_time_to ) : '23:59'; ?>" min="00:00" max="23:59" pattern="[0-9]{2}:[0-9]{2}">
			</td>
			<td><input type="text" name="salepercent" class="input-txt" value="" readonly="readonly" style="direction:rtl;"></td>
			<?php
			if ( 'yes' === $custom_price['active'] && 'no' === $show_sku ) {
				?>
				<td>
				<input type="number" step="any" name="customprice" class="input-txt" value="<?php echo esc_attr( $custom_price_value ); ?>" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_focus ) ); ?>"> 
				</td>
				<?php
			} elseif ( 'yes' === $show_sku ) {
				?>
				<td>
					<input type="text" class="input-txt" name="sku" value="<?php echo esc_attr( $this->wbtefunctions->wbte_get_product_sku( $product ) ); ?>" onmouseover="<?php echo esc_js( $this->wbte_get_autofocus_value( $auto_focus ) ); ?>">
				</td>
				<?php
			}
			if ( 'yes' === $show_vendors ) {
				?>
				<td>
					<?php $this->wbtefunctions->wbte_get_vendor_selected( $product_id ); ?>
				</td>
				<?php
			}
			$stock_value = '';
			if ( null !== $product->get_stock_quantity() && $product->get_stock_quantity() > 0 && 
				 null !== $product->get_regular_price() && $product->get_regular_price() > 0 ) {
				$stock_value = wc_format_decimal( $product->get_stock_quantity() * $product->get_regular_price() );
			}
			?>
			<td>
				<input type="number" name="totcol" class="input-txt" value="<?php echo esc_attr( $stock_value ); ?>" readonly="readonly" style="direction:rtl;"> 
				</form>
			</td>
		</tr>
		<?php
	}

	/**
	 * No row found
	 */
	public function wbte_get_no_row_found() {
		
		$custom_price = $this->wbtefunctions->wbte_get_custom_price_info();
		$colspan_val  = 8;
		
		if ( 'yes' === $custom_price['active'] ) {
			$colspan_val = 9;
		}
		?>
			<tr><td colspan="<?php echo esc_attr( $colspan_val ); ?>"><strong><?php esc_html_e( 'No products found', 'woo-bulk-table-editor' ); ?></strong></td></tr>
		<?php

	}

	/**
	 * Get table footer
	 */
	public function wbte_get_table_footer() {
		
		$custom_price = $this->wbtefunctions->wbte_get_custom_price_info();
		$options      = get_option( 'wbte_options' );
		$show_vendors = ( strlen( $options[ 'wbte_vendor_integration' ] ) > 0 ) ? $options[ 'wbte_vendor_integration' ] : 'no';
		$colspan_val  = 8;
		
		if ( 'yes' === $custom_price['active'] ) {
			$colspan_val++;
		}

		if ( 'yes' === $show_vendors ) {
			$colspan_val++;
		}
		
		?>
		<tfoot>
			<tr>
				<td><?php wp_nonce_field( 'footer_id' ); ?></td>
				<td><input type="hidden" id="msg-confirm-delete" value="<?php esc_attr_e( 'Move checked items to trash?', 'woo-bulk-table-editor' ); ?>"></td>
				<td><span id="tbl-total-s" class="span-txt-center"></span></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			<?php
			if ( 'yes' === $custom_price['active'] ) {
				?>
				<td></td>
				<?php
			}
			if ( 'yes' === $show_vendors ) {
				?>
				<td></td>
				<?php
			}
			?>
				<td></td>
				<td><span id="tbl-total-f" class="span-txt"></span></td>
			</tr>
			<tr class="th-info">
				<td colspan="<?php echo esc_attr( $colspan_val ); ?>">
				</td>
				<td>
				
				</td>
			</tr>
		</tfoot>
		<?php
	}

	/**
	 * Get paging above table
	 *
	 * @param var $view the view.
	 */
	public function wbte_get_table_paging_top( $view ) {

		$get_prod_cat = ( strlen( filter_input( 1, 'product_cat', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'product_cat', FILTER_DEFAULT ) : get_option( 'wbte_options' )['wbte_product_cat'];
		$prod_search  = filter_input( 1, 'product_search', FILTER_DEFAULT );

		if ( strlen( $prod_search ) > 0 ) {
			$get_prod_cat = '';
		}
		?>
		<div class="row-cat-paging display-desktop">
			<div class="col-category"> <!-- Select category -->
				<?php $this->wbtefunctions->wbte_get_categories_select( $get_prod_cat ); ?>
			<div>
				<?php $this->wbtefunctions->wbte_get_sales_products_select(); ?>
			</div>
			<div>
				<?php $this->wbtefunctions->wbte_get_tags_filter(); ?>
			</div>
			<div class="col-clear">
				<?php if ( 'prod' === $view ) : ?>
					<a type="button" href="#" class="button" id="a-delete-rows" onclick="deleteRows();"><i class="fas fa-trash-alt"></i> <?php esc_attr_e( 'Delete rows', 'woo-bulk-table-editor' ); ?></a>
					<a type="button" href="#" class="button" id="a-clear-sales" onclick="clearSales();"><i class="fas fa-eraser"></i> <?php esc_attr_e( 'Remove sales', 'woo-bulk-table-editor' ); ?></a>
				<?php endif; ?>
				<a type="button" href="#" class="button" id="reset-values" onclick="wbte_reset_table_values();"><i class="fas fa-undo"></i> <?php esc_attr_e( 'Undo changes', 'woo-bulk-table-editor' ); ?></a>
				</div>
			</div>
			<div id="wbte-paging-top" class="div-paging">
			
			</div>
		</div>
		<?php
	}


	/**
	 * Get table head
	 */
	public function wbte_get_table_head() {

		$custom_price = $this->wbtefunctions->wbte_get_custom_price_info();
		$options      = get_option( 'wbte_options' );
		$show_sku     = ( strlen( $options[ 'wbte_use_sku_main_page' ] ) > 0 ) ? $options[ 'wbte_use_sku_main_page' ] : 'no';
		$show_vendors = ( strlen( $options[ 'wbte_vendor_integration' ] ) > 0 ) ? $options[ 'wbte_vendor_integration' ] : 'no';
		$disable_desc = ( strlen( $options[ 'wbte_disable_description' ] ) > 0 ) ? $options[ 'wbte_disable_description' ] : 'no';
		$desc_label   = ( 'no' === $disable_desc ) ? __( 'Description ID SKU', 'woo-bulk-table-editor' ) : __( 'Show ID & SKU', 'woo-bulk-table-editor' );
		?>
		<tr>
			<th class="th-first"><input type="checkbox" id="checkall" onclick="checkVisible();"></th>
			<th style="width:20%;"><a href="#" onclick="sort(1,'text');">
				<?php esc_html_e( 'Name', 'woo-bulk-table-editor' ); ?></a> <i id="s1" class="fas fa-sort" style="padding-right:10px;"></i>
				<input type="checkbox" id="wbte-chk-show-desc" onclick="wbte_show_hide_desc();"> <?php echo esc_attr( $desc_label ); ?>
			</th>
			<th><a href="#" onclick="sort(2,'number');"><?php esc_html_e( 'Stock', 'woo-bulk-table-editor' ); ?></a> <i id="s2" class="fas fa-sort"></i></th>
			<th><a href="#" onclick="sort(3,'number');"><?php esc_html_e( 'Price', 'woo-bulk-table-editor' ); ?></a> <i id="s3" class="fas fa-sort"></i></th>
			<th><a href="#" onclick="sort(4,'number');"><?php esc_html_e( 'Sale price', 'woo-bulk-table-editor' ); ?></a> <i id="s4" class="fas fa-sort"></i></th>
			<th class="th-center" style="width: 170px;"><a href="#" onclick="sort(5,'date');"><?php esc_html_e( 'Sale start date', 'woo-bulk-table-editor' ); ?></a> <i id="s5" class="fas fa-sort"></i></th>
			<th class="th-center" style="width: 170px;"><a href="#" onclick="sort(6,'date');"><?php esc_html_e( 'Sale end date', 'woo-bulk-table-editor' ); ?></a> <i id="s6" class="fas fa-sort"></i></th>
			<th style="width:60px;" class="th-center"><a href="#" onclick="sort(7,'percent');"><?php esc_html_e( 'Sale %', 'woo-bulk-table-editor' ); ?></a> <i id="s7" class="fas fa-sort"></i></th>
		<?php
		$stock_sort = 8;
		if ( 'yes' === $custom_price['active'] && 'no' === $show_sku ) {
			$stock_sort++;	
			?>
			<th class="th-center"><a href="#" onclick="sort(8,'number');"><?php echo esc_html( $custom_price['name'] ); ?></a> <i id="s8" class="fas fa-sort"></i> </th>
			<?php
		} elseif ( 'yes' === $show_sku ) {
			$stock_sort++;
			?>
			<th class="th-center"><a href="#" onclick="sort(8,'text');"><?php esc_html_e( 'SKU', 'woo-bulk-table-editor' ); ?></a> <i id="s8" class="fas fa-sort"></i> </th>
			<?php
		} 
		if ( 'yes' === $show_vendors ) {
			$stock_sort++;
			?>
			<th class="th-center"><?php esc_html_e( 'Vendor', 'woo-bulk-table-editor' ); ?></th>
			<?php
		}
		?>
		<th class="th-center"><a href="#" onclick="sort(<?php echo esc_js( $stock_sort ); ?>,'number');"><?php esc_html_e( 'Stock value', 'woo-bulk-table-editor' ); ?></a> <i id="s<?php echo esc_js( $stock_sort ); ?>" class="fas fa-sort"></i> </th>
		</tr>

		<?php
	}

	/**
	 * Get table
	 *
	 */
	public function wbte_get_table() {
		?>
		<div class="display-desktop"> <!-- Table -->
			<table class="wp-list-table widefat fixed striped posts" id="wbtetable" style="width:100%;">
				<thead>
					<?php $this->wbte_get_table_head(); ?>
					<?php $this->wbte_get_table_head_info(); ?>
				</thead>
				<tbody id="the-list"> 
					<?php $this->wbtefunctions->wbte_loop_products(); ?>
				</tbody>
					<?php $this->wbte_get_table_footer(); ?>
			</table>
			<div id="wbte-paging-bottom" class="div-paging" style="margin-top: 6px;">
			</div>

			</div>
		<div>
		<?php
	}

	/**
	 * Get mobile view
	 */
	public function wbte_get_mobile_view() {

		?>
		<div class="display-mobile">
			<div class="updated woocommerce-info display-mobile" style="width: 98%;">
				<?php esc_html_e( 'This plugin currently does not support mobile views. ', 'woo-bulk-table-editor' ); ?>
				<?php esc_html_e( 'Please use screen size larger than 700px', 'woo-bulk-table-editor' ); ?>
			</div>
		</div>
		<?php

	}


}
