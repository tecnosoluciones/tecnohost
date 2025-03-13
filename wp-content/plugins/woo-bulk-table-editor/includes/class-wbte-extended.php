<?php
/**
 * Bulk Table Editor extended templates
 *
 * @package BulkTableEditor/includes
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/class-wbte-functions.php';

/**
 * Class for extended templates
 */
class WbteExtended {

	/**
	 * WbteFunctions
	 *
	 * @var var $functions.
	 */
	public $functions;

	/**
	 * WbteTemplates
	 *
	 * @var var $templates.
	 */
	public $templates;

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
		$this->functions    = new WbteFunctions();
		$this->wbte_options = get_option( 'wbte_options' );
	}

	/**
	 * Get the extended table
	 *
	 */
	public function wbte_get_table() {
		?>
		<div class="display-desktop"> <!-- Table -->
			<table class="wp-list-table widefat fixed striped posts" id="wbtetable_ext" style="width:100%;">
				<thead>
					<?php $this->wbte_get_table_head(); ?>
					<?php $this->wbte_get_table_head_info(); ?>
				</thead>
				<tbody id="the-list"> 
					<?php $this->functions->wbte_loop_products(); ?>
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
	 * Get table head
	 */
	public function wbte_get_table_head() {

		$disable_desc = ( strlen( $this->wbte_options[ 'wbte_disable_description' ] ) > 0 ) ? $this->wbte_options[ 'wbte_disable_description' ] : 'no';
		$desc_label   = ( 'no' === $disable_desc ) ? __( 'Description ID SKU', 'woo-bulk-table-editor' ) : __( 'Show ID & SKU', 'woo-bulk-table-editor' );
		
		?>
		<tr>
			<th class="th-first"><input type="checkbox" id="checkall" onclick="checkVisible();"></th>
			<th style="width:18%;">
				<a href="#" onclick="sort(1,'text');"><?php esc_html_e( 'Name', 'woo-bulk-table-editor' ); ?></a> <i id="s1" class="fas fa-sort" style="padding-right: 10px;"></i>
				<input type="checkbox" id="wbte-chk-show-desc" onclick="wbte_show_hide_desc();"> <?php echo esc_attr( $desc_label ); ?>
			</th>
			<th class="th-center" style="width: 90px;"><i class="fas fa-star" data-tip="Featured"></i></th>
			<th class="th-center" style="width:12%;"><a href="#" onclick="sort(3,'text');"><?php esc_html_e( 'SKU', 'woo-bulk-table-editor' ); ?></a> <i id="s3" class="fas fa-sort"></i></th>
			<th class="th-center" style="width:12%;"><a href="#" onclick="sort(4,'text');"><?php esc_html_e( 'Tags', 'woo-bulk-table-editor' ); ?></a> <i id="s4" class="fas fa-sort"></i></th>
			<th class="th-center"><?php esc_html_e( 'Backorders', 'woo-bulk-table-editor' ); ?></th>
			<th class="th-center"><?php esc_html_e( 'In stock?', 'woo-bulk-table-editor' ); ?></th>
			<th class="th-center"><?php esc_html_e( 'Visibility', 'woo-bulk-table-editor' ); ?></th>
			<th class="th-center"><a href="#" onclick="sort(8,'number');"><?php esc_html_e( 'Weight', 'woo-bulk-table-editor' ); ?></a> <i id="s7" class="fas fa-sort"></i></th>
			<th class="th-center"><a href="#" onclick="sort(9,'number');"><?php esc_html_e( 'Length', 'woo-bulk-table-editor' ); ?></a> <i id="s8" class="fas fa-sort"></i></th>
			<th class="th-center"><a href="#" onclick="sort(10,'number');"><?php esc_html_e( 'Width', 'woo-bulk-table-editor' ); ?></a> <i id="s9" class="fas fa-sort"></i></th>
			<th class="th-center"><a href="#" onclick="sort(11,'number');"><?php esc_html_e( 'Height', 'woo-bulk-table-editor' ); ?></a> <i id="s10" class="fas fa-sort"></i></th>
			<th class="th-center" style="width:100px;"><i class="fas fa-image"></i></th>
	
		</tr>

		<?php
	}

	/**
	 * Get table head info
	 */
	public function wbte_get_table_head_info() {

		$auto_date  = 'date';
		$auto_focus = '';
		?>
			<tr class="th-info">
				<th></th>
				<th style="vertical-align: middle;"><strong><?php esc_html_e( 'Bulk Editor', 'woo-bulk-table-editor' ); ?></strong><br /><span class="th-desc"><?php esc_html_e( 'Fill in values to apply to all visible products. Double click name for description, ID and SKU.', 'woo-bulk-table-editor' ); ?></span></th>
				<th style="vertical-align: top;">
					<select name="featured_select_type" id="featured_select_type" class="input-select" onchange="bulkSetValues('featured_select', 'ext');">
						<?php $this->functions->wbte_get_bulk_options( 'featured' ); ?>
					</select>
				</th>
				<th style="vertical-align: top;"><input type="text" name="sku_select" id="sku_select" class="input-txt" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
				<br/>
					<select name="sku_select_type" id="sku_select_type" class="input-select" onchange="bulkSetValues('sku_select', 'ext');">
						<?php $this->functions->wbte_get_bulk_options( 'sku' ); ?>
					</select>
				</th>
				<th style="vertical-align: top;"><input type="text" name="tags_select" id="tags_select" class="input-txt" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
				<br/>
					<select name="tags_select_type" id="tags_select_type" class="input-select" onchange="bulkSetValues('tags_select', 'ext');">
						<?php $this->functions->wbte_get_bulk_options( 'tags' ); ?>
					</select>
				</th>
				<th style="vertical-align: top;">
					<select name="backorder_select_type" id="backorder_select_type" class="input-select" onchange="bulkSetValues('backorder_select', 'ext');">
						<?php $this->functions->wbte_get_bulk_options( 'backorder' ); ?>
					</select>
				</th>
				
				<th style="vertical-align: top;">
					<select name="instock_select_type" id="instock_select_type" class="input-select" onchange="bulkSetValues('instock_select', 'ext');">
						<?php $this->functions->wbte_get_bulk_options( 'instock' ); ?>
					</select>
				</th>
				<th style="vertical-align: top;">
					<select name="visibility_select_type" id="visibility_select_type" class="input-select" onchange="bulkSetValues('visibility_select', 'ext');">
						<?php $this->functions->wbte_get_bulk_options( 'visibility' ); ?>
					</select>
				</th>
				<th style="vertical-align: top;"><input type="number" name="weight_select" id="weight_select" class="input-txt" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
				<br/>
					<select name="weight_select_type" id="weight_select_type" class="input-select" onchange="bulkSetValues('weight_select', 'ext');">
						<?php $this->functions->wbte_get_bulk_options( 'numbers' ); ?>
					</select>
				</th>
				<th style="vertical-align: top;"><input type="number" name="length_select" id="length_select" class="input-txt" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
				<br/>
					<select name="length_select_type" id="length_select_type" class="input-select" onchange="bulkSetValues('length_select', 'ext');">
						<?php $this->functions->wbte_get_bulk_options( 'numbers' ); ?>
					</select>
				</th>
				<th style="vertical-align: top;"><input type="number" name="width_select" id="width_select" class="input-txt" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
				<br/>
					<select name="width_select_type" id="width_select_type" class="input-select" onchange="bulkSetValues('width_select', 'ext');">
						<?php $this->functions->wbte_get_bulk_options( 'numbers' ); ?>
					</select>
				</th>
				<th style="vertical-align: top;"><input type="number" name="height_select" id="height_select" class="input-txt" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
				<br/>
					<select name="height_select_type" id="height_select_type" class="input-select" onchange="bulkSetValues('height_select', 'ext');">
						<?php $this->functions->wbte_get_bulk_options( 'numbers' ); ?>
					</select>
				</th>
				<th style="vertical-align: top;">
					<button type="button" id="saveallExt" class="button action" onclick='saveAllExt();' style="width:100%;"><i class="fas fa-database"></i> <?php esc_html_e( 'Save', 'woo-bulk-table-editor' ); ?></button>
					<div class="wbte-saving" id="wbte-saving">
						<progress id="pbar-saving" class="pbar-saving" value="0" max="100"></progress>
					</div>
					<div class="wbte-bulk-images" id="wbte-bulk-images">
						<a href="#" onclick="wbteBulkSetImages();"><i class="fas fa-edit" style="padding-right:5px;padding-left:9px;font-size:medium;"></i></a>
						<a href="#" onclick="wbteBulkRemoveImages();"> <i class="fas fa-trash" style="padding-right:1px;font-size:medium;padding-top:9px;"></i> </a>
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

		$product_name = $product->get_title();
		$product_id   = $product->get_id();
		$disable_desc = ( strlen( $this->wbte_options[ 'wbte_disable_description' ] ) > 0 ) ? $this->wbte_options[ 'wbte_disable_description' ] : 'no';
		$type         = ( $has_attributes ) ? 'var' : 'prod';

		if ( $has_attributes ) {

			$attributes = $product->get_attributes();

			if ( isset( $attributes ) && is_array( $attributes ) ) {
				foreach ( $attributes as $key => $val ) {
					if ( isset( $val ) && is_string( $val ) && strlen( $val ) > 0 ) {
						$product_name .= ', ' . $val;
					}
				}
			}

			$product_id = $product->get_parent_id();
			
			if ( 0 === $product_id ) {
				$product_id = $product->get_id();
			}
		
		}

		$auto_focus = '';
		$tags       = $this->functions->wbte_get_product_tags( $product->get_id() );

		?>
		<tr class="lozad" style="vertical-align: top;" data-type="<?php echo esc_attr( $type ); ?>">
			<td id="<?php echo esc_attr( $product->get_id() ); ?>" style="padding-top:10px;">
				<form id="frm_<?php echo esc_attr( $product->get_id() ); ?>_<?php echo esc_attr( wp_rand( 1, 50000 ) ); ?>" method="post">
				<input name="id" value="<?php echo esc_attr( $product->get_id() ); ?>" type="checkbox">
				<input type="hidden" name="saleprice" value="<?php echo esc_attr( $product->get_sale_price() ); ?>">
				<input type="hidden" name="parent_id" value="<?php echo esc_attr( $has_attributes ? $product->get_parent_id() : '' ); ?>">
			</td>
			<td style="display:flex;width:98%" ondblclick="wbte_show_desc('<?php echo esc_attr( $product->get_id() ); ?>');">
				<?php
				if ( ! $has_attributes ) {
					?>
					<div style="flex:1;width:100%;">
					<input type="text" class="input-txt-name" name="product_name" value="<?php echo esc_html( $product_name ); ?>" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
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
			<td class="th-center">
				<?php
				$featured         = ( $product->get_featured() ) ? '1' : '0';
				$featured_checked = '';
				if ( '1' === $featured ) {
					$featured_checked = 'checked="checked"';
				}
				?>
				<input type="checkbox" <?php echo esc_attr( ( $has_attributes ) ? 'disabled="disabled"' : '' ); ?> name="featured" <?php echo esc_attr( $featured_checked ); ?> value="<?php echo esc_attr( $product->get_featured() ); ?>"  onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
			</td>
			<td>
				<input type="text" class="input-txt" name="sku" value="<?php echo esc_attr( $this->functions->wbte_get_product_sku( $product ) ); ?>" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
			</td>
			<td>
			<input type="text" class="input-txt" name="tags" value="<?php echo esc_attr( $tags ); ?>" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
			</td>
			<td>
				<select name="backorder" class="input-select" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
					<?php $this->functions->wbte_get_bulk_options( 'backorder', $product->get_backorders() ); ?>
				</select>
			</td>
			<td>
				<select name="instock" class="input-select" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
					<?php $this->functions->wbte_get_bulk_options( 'instock', $product->get_stock_status() ); ?>
				</select>
			</td>
			<td>
				<select name="visibility" <?php echo esc_attr( ( $has_attributes ) ? 'disabled="disabled"' : '' ); ?> class="input-select" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
					<?php $this->functions->wbte_get_bulk_options( 'visibility', $product->get_catalog_visibility() ); ?>
				</select>
			</td>
			<td>
				<input type="number" class="input-txt" name="weight" value="<?php echo esc_attr( $product->get_weight() ); ?>" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
			</td>
			<td>
				<input type="number" class="input-txt" name="_length" value="<?php echo esc_attr( $product->get_length() ); ?>" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
			</td>
			<td>
				<input type="number" class="input-txt" name="width" value="<?php echo esc_attr( $product->get_width() ); ?>" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
			</td>
			<td>
				<input type="number" class="input-txt" name="height" value="<?php echo esc_attr( $product->get_height() ); ?>" onmouseover="<?php echo esc_js( $this->functions->wbte_get_autofocus_value( $auto_focus ) ); ?>">
			</td>
			<td class="th-center">
				<input type="hidden" id="product_img_<?php echo esc_attr( $product->get_id() ); ?>" name="product_img" value="<?php echo esc_attr( $product->get_image_id() ); ?>">
				<a href="#" onclick="openMediaLib('<?php echo esc_js( $product->get_id() ); ?>')">
				<?php
				if ( $product->get_image_id() ) {
					if ( ! $has_attributes ) {
						echo wp_kses( get_the_post_thumbnail( $product_id, array( '28', '28' ), array( 'class' => 'wbte-thumb' ) ), $this->functions->wbte_get_allowed_html() );
					} else {
						$variation = new WC_Product_Variation( $product->get_id() );
						echo wp_kses( $variation->get_image( array( '28', '28' ), array( 'class' => 'wbte-thumb' ) ), $this->functions->wbte_get_allowed_html() );
					}
				} else {
					?>
					<img height="28" src="<?php echo esc_attr( wc_placeholder_img_src() ); ?>" class="wbte-thumb wp-post-image">
					<?php
				}
				?>
				</a>
				
				<a href="#" onclick="wbteRemoveThumbnail( <?php echo esc_js( $product->get_id() ); ?> );"> <i class="fas fa-trash" style="float:right;padding-right:3px;font-size:medium;padding-top:9px;"></i> </a>
				<a href="#" onclick="openMediaLib('<?php echo esc_js( $product->get_id() ); ?>')"><i class="fas fa-edit" style="float:right;padding-right:3px;font-size:medium;"></i></a> 
				</form>
			</td>
		</tr>
		<?php

	}

	/**
	 * Get table footer
	 */
	public function wbte_get_table_footer() {
		?>
		<tfoot>
			<tr>
				<td><?php wp_nonce_field( 'footer_id_ext' ); ?></td>
				<td>
				</td>
				<td colspan="11"></td>
			</tr>
		</tfoot>
		<?php
	}
}


