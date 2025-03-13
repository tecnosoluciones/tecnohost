<?php

namespace Gravity_Flow\Gravity_Flow\Blocks\Registrees;

use Gravity_Flow\Gravity_Flow\Blocks\Block_Registree;
use \GFAPI;

/**
 * Status Block Registree
 *
 * @since 2.8
 */
class Status extends Block_Registree {

	protected $type = 'gravityflow/status';

	/**
	 * Render the block.
	 *
	 * @since 2.8
	 *
	 * @param $attributes
	 * @param $content
	 *
	 * @return string
	 */
	public function render( $attributes, $content ) {
		$shortcode_atts = array();
		foreach ( $attributes as $key => $value ) {
			// Convert camel to snake
			$snake_key                    = strtolower( preg_replace( '/(?<!^)[A-Z]/', '_$0', $key ) );
			$shortcode_atts[ $snake_key ] = $value;
		}

		$saved_form_ids_json      = get_post_meta( get_the_ID(), '_gravityflow_status_forms_json', true );
		$saved_form_ids           = json_decode( $saved_form_ids_json, true );
		$form_ids                 = is_array( $saved_form_ids ) ? wp_list_pluck( $saved_form_ids, 'value' ) : array();
		$shortcode_atts['form']   = join( ',', $form_ids );
		$saved_field_ids_json     = get_post_meta( get_the_ID(), '_gravityflow_status_fields_json', true );
		$saved_field_ids          = json_decode( $saved_field_ids_json, true );
		$fields                   = is_array( $saved_field_ids ) ? wp_list_pluck( $saved_field_ids, 'value' ) : array();
		$shortcode_atts['fields'] = join( ',', $fields );

		$shortcode_atts['display_all'] = get_post_meta( get_the_ID(), '_gravityflow_status_display_all', true );

		$shortcode_atts['allow_anonymous'] = get_post_meta( get_the_ID(), '_gravityflow_status_allow_anonymous', true );

		$shortcode_atts = shortcode_atts( gravity_flow()->get_shortcode_defaults(), $shortcode_atts );

		wp_enqueue_script( 'gravityflow_entry_detail' );
		wp_enqueue_script( 'gravityflow_status_list' );

		$html = '';
		if ( rgget( 'view' ) || ! empty( $entry_id ) ) {
			$html .= gravity_flow()->get_shortcode_status_page_detail( $shortcode_atts );
		} elseif ( is_user_logged_in() || ( $shortcode_atts['display_all'] && $shortcode_atts['allow_anonymous'] ) ) {
			$html .= gravity_flow()->get_shortcode_status_page( $shortcode_atts );
		}

		return $html;
	}

	/**
	 * Register the Fields.
	 *
	 * @since 2.8
	 *
	 * @return void
	 */
	public function register_fields() {
		if ( ! GFAPI::current_user_can_any( 'gravityflow_status_view_all' ) ) {
			return;
		}

		register_meta( 'post', '_gravityflow_status_fields_json', array(
			'show_in_rest'   => true,
			'object_subtype' => 'page',
			'single'         => true,
			'type'           => 'string',
			'auth_callback'  => function () {
				return GFAPI::current_user_can_any( 'gravityflow_status_view_all' );
			},
		) );
		register_meta( 'post', '_gravityflow_status_forms_json', array(
			'show_in_rest'   => true,
			'object_subtype' => 'page',
			'single'         => true,
			'type'           => 'string',
			'auth_callback'  => function () {
				return GFAPI::current_user_can_any( 'gravityflow_status_view_all' );
			},
		) );
		register_meta( 'post', '_gravityflow_status_display_all', array(
			'show_in_rest'   => true,
			'object_subtype' => 'page',
			'single'         => true,
			'type'           => 'boolean',
			'auth_callback'  => function () {
				return GFAPI::current_user_can_any( 'gravityflow_status_view_all' );
			},
		) );
		register_meta( 'post', '_gravityflow_status_allow_anonymous', array(
			'show_in_rest'   => true,
			'object_subtype' => 'page',
			'single'         => true,
			'type'           => 'boolean',
			'auth_callback'  => function () {
				return GFAPI::current_user_can_any( 'gravityflow_status_view_all' );
			},
		) );
	}
}