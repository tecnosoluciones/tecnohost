<?php

/**
 * Gravity Flow WooCommerce Create Coupon Step
 *
 * @package     GravityFlow
 * @subpackage  Classes/Step
 * @copyright   Copyright (c) 2015-2019, Steven Henty S.L.
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License
 * @since       1.2
 */

if ( class_exists( 'Gravity_Flow_Step' ) && function_exists( 'WC' ) ) {

	class Gravity_Flow_Step_Woocommerce_Create_Coupon extends Gravity_Flow_Step {
		/**
		 * A unique key for this step type.
		 *
		 * @since 1.2
		 *
		 * @var string
		 */
		public $_step_type = 'woocommerce_create_coupon';

		/**
		 * Set a custom icon in the step settings.
		 * 32px x 32px
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function get_icon_url() {
			return '<i class="woocommerce" aria-hidden="true"></i>';
		}

		/**
		 * Returns the label for the step.
		 *
		 * @since 1.2
		 *
		 * @return string
		 */
		public function get_label() {
			return esc_html__( 'Create Coupon', 'gravityflowwoocommerce' );
		}

		/**
		 * Add settings to the step.
		 *
		 * @since 1.2
		 *
		 * @return array
		 */
		public function get_settings() {
			$settings_api                 = $this->get_common_settings_api();
			$workflow_notification_fields = $settings_api->get_setting_notification(
				array(
					'name_prefix'      => 'workflow',
					'label'            => __( 'Coupon Notification', 'gravityflowwoocommerce' ),
					'tooltip'          => __( 'Enable this setting to send the coupon via email.', 'gravityflowwoocommerce' ),
					'checkbox_label'   => __( 'Enabled', 'gravityflowwoocommerce' ),
					'checkbox_tooltip' => '',
					'send_to_fields'   => true,
					'resend_field'     => false,
					'default_message'  => esc_html__( 'You\'ve got new coupon(s): {workflow_woocommerce_coupon}.', 'gravityflowwoocommerce' ),
				)
			);

			$coupon_fields = array(
				array(
					'name'                => 'coupon_template',
					'class'               => 'medium',
					'label'               => esc_html__( 'Coupon Template', 'gravityflowwoocommerce' ),
					'type'                => 'text',
					/* translators: 1: Open link tag 2: Close link tag */
					'tooltip'             => sprintf( esc_html__( 'Enter a Coupon Code from %1$sWooCommerce Coupons%2$s. New coupons will be created by copying its data.', 'gravityflowwoocommerce' ), '<a href="' . admin_url( 'edit.php?post_type=shop_coupon' ) . '" target="_blank">', '</a>' ),
					'required'            => true,
					'validation_callback' => array( $this, 'validate_coupon_code' ),
				),
				array(
					'name'    => 'coupon_prefix',
					'class'   => 'small',
					'label'   => esc_html__( 'Coupon Prefix', 'gravityflowwoocommerce' ),
					'type'    => 'text',
					/* translators: 1: Open HTML <code> tag 2: Close HTML </code> tag */
					'tooltip' => sprintf( esc_html__( 'New coupon code will be generated in the following format: Prefix%1$scoupon_code%2$sSuffix.', 'gravityflowwoocommerce' ), '<code>', '</code>' ),
				),
				array(
					'name'          => 'coupon_code_length',
					'class'         => 'small',
					'label'         => esc_html__( 'Coupon Code Length', 'gravityflowwoocommerce' ),
					'type'          => 'text',
					/* translators: 1: Open HTML <code> tag 2: Close HTML </code> tag */
					'tooltip'       => sprintf( esc_html__( 'Set the length of %1$scoupon_code%2$s. Prefix and suffix are not included. The default length is 10, please set a number that is not less than 10 to avoid coupon code duplication.', 'gravityflowwoocommerce' ), '<code>', '</code>' ),
					'default_value' => '10',
					'required'      => true,
				),
				array(
					'name'    => 'coupon_suffix',
					'class'   => 'small',
					'label'   => esc_html__( 'Coupon Suffix', 'gravityflowwoocommerce' ),
					'type'    => 'text',
					/* translators: 1: Open HTML <code> tag 2: Close HTML </code> tag */
					'tooltip' => sprintf( esc_html__( 'New coupon code will be generated in the following format: Prefix%1$scoupon_code%2$sSuffix.', 'gravityflowwoocommerce' ), '<code>', '</code>' ),
				),
				array(
					'name'          => 'quantity',
					'class'         => 'small',
					'label'         => esc_html__( 'Quantity', 'gravityflowwoocommerce' ),
					'type'          => 'text',
					'tooltip'       => esc_html__( 'Set how many coupons you would like to duplicate from the coupon template.', 'gravityflowwoocommerce' ),
					'default_value' => '1',
					'required'      => true,
				),
			);

			$settings = array(
				'title'       => esc_html__( 'WooCommerce Create Coupon', 'gravityflowwoocommerce' ),
				'description' => $this->get_settings_description(),
				'fields'      => array_merge( $coupon_fields, $workflow_notification_fields ),
			);

			return $settings;
		}

		/**
		 * Process the step.
		 *
		 * @since 1.2
		 */
		public function process() {
			$this->log_debug( __METHOD__ . '(): starting' );

			$this->create_coupon();
			$this->send_coupon_notification();

			return true;
		}

		/**
		 * Uses the Gravity Forms Add-On Framework to write a message to the log file for the Gravity Flow WooCommerce extension.
		 *
		 * @since 1.2
		 *
		 * @param string $message The message to be logged.
		 */
		public function log_debug( $message ) {
			gravity_flow_woocommerce()->log_debug( $message );
		}

		/**
		 * Validate if coupon code exists.
		 *
		 * @since 1.2
		 *
		 * @param array  $field Field object.
		 * @param string $field_setting Coupon code.
		 */
		public function validate_coupon_code( $field, $field_setting = '' ) {
			$coupon_id = wc_get_coupon_id_by_code( $field_setting );

			if ( $coupon_id === 0 ) {
				gravity_flow()->set_field_error( $field, esc_html__( 'No coupon exists.', 'gravityflowwoocommerce' ) );
			}
		}

		/**
		 * Create coupon(s).
		 *
		 * @since 1.2
		 */
		public function create_coupon() {
			$coupon_template_id = wc_get_coupon_id_by_code( $this->coupon_template );
			$coupon_template    = get_post( $coupon_template_id );

			for ( $i = 0; $i < $this->quantity; $i ++ ) {
				$new_coupon_id = $this->duplicate_coupon( $coupon_template );
				$this->set_coupon_meta( $new_coupon_id, $coupon_template );
			}

			// Add coupon codes to the entry note.
			$coupon_codes = gform_get_meta( $this->get_entry_id(), 'workflow_woocommerce_coupon_code' );
			/* translators: Coupon codes */
			$note = $this->get_name() . ': ' . sprintf( esc_html__( 'Created new coupon code(s): %s.', 'gravityflowwoocommerce' ), implode( ', ', $coupon_codes ) );
			$this->add_note( $note );
		}

		/**
		 * Duplicate coupon from coupon template.
		 *
		 * @since 1.2
		 *
		 * @param WP_Post $coupon_template Coupon object.
		 *
		 * @return int
		 */
		public function duplicate_coupon( $coupon_template ) {
			// Create unique coupon code.
			$coupon_code = $this->coupon_prefix . wp_generate_password( $this->coupon_code_length, false ) . $this->coupon_suffix;

			$args = array(
				'post_title'   => $coupon_code,
				'post_excerpt' => ( ! empty( $coupon_template->post_excerpt ) ) ? $coupon_template->post_excerpt : '',
				'post_status'  => 'publish',
				'post_type'    => 'shop_coupon',
			);

			$coupon_id = wp_insert_post( $args );

			if ( ! is_wp_error( $coupon_id ) ) {
				$this->log_debug( __METHOD__ . '(): new coupon created.' );

				$coupon_codes = gform_get_meta( $this->get_entry_id(), 'workflow_woocommerce_coupon_code' );
				if ( empty( $coupon_codes ) ) {
					$coupon_codes = array();
				}
				$coupon_codes[] = $coupon_code;
				gform_update_meta( $this->get_entry_id(), 'workflow_woocommerce_coupon_code', $coupon_codes );
			}

			return $coupon_id;
		}

		/**
		 * Set post meta for coupon.
		 *
		 * @since 1.2
		 *
		 * @param int     $coupon_id Coupon ID.
		 * @param WP_Post $coupon_template Coupon object.
		 */
		public function set_coupon_meta( $coupon_id, $coupon_template ) {
			global $wpdb;

			/*
			 * Duplicate all post meta from coupon template, except the ones start with _.
			 */
			$post_meta_infos = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%d",
					$coupon_template->ID
				)
			);
			if ( count( $post_meta_infos ) !== 0 ) {
				$sql_query     = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				$sql_query_sel = array();

				foreach ( $post_meta_infos as $meta_info ) {
					$meta_key   = $meta_info->meta_key;
					$meta_value = $meta_info->meta_value;
					if ( substr( $meta_key, 0, 1 ) === '_' || $meta_key === 'usage_count' ) {
						// skip private meta fields and don't copy usage_count.
						continue;
					}

					$sql_query_sel[] = $wpdb->prepare( 'SELECT %d, %s, %s', $coupon_id, $meta_key, $meta_value );
				}

				$sql_query .= implode( ' UNION ALL ', $sql_query_sel );

				$result = $wpdb->query( $sql_query );

				if ( $result ) {
					$this->log_debug( __METHOD__ . '(): coupon meta updated.' );
				}
			}
		}

		/**
		 * Sends the coupon notification, if enabled.
		 *
		 * @since 1.2
		 */
		public function send_coupon_notification() {
			if ( ! $this->workflow_notification_enabled ) {
				return;
			}

			$type      = 'workflow';
			$assignees = $this->get_notification_assignees( $type );

			if ( empty( $assignees ) ) {
				return;
			}

			$notification = $this->get_notification( $type );
			$this->send_notifications( $assignees, $notification );

			$note = esc_html__( 'Sent Coupon Notification: ', 'gravityflowwoocommerce' ) . $this->get_name();
			$this->add_note( $note );
		}

		/**
		 * Adds coupon template information to the step settings area.
		 *
		 * @since 1.2
		 *
		 * @return string
		 */
		public function get_settings_description() {
			$coupon_template = $this->get_setting( 'coupon_template' );

			if ( $coupon_template === '' ) {
				return;
			}

			$coupon_template    = new WC_Coupon( $coupon_template );
			$product_ids        = $coupon_template->get_product_ids();
			$product_categories = $coupon_template->get_product_categories();
			$usage_limit        = ( $coupon_template->get_usage_limit() ) ? $coupon_template->get_usage_limit() : '&infin;';
			$expiry_date        = $coupon_template->get_date_expires();

			return sprintf(
				'<div class="delete-alert alert_blue"><strong>%s</strong><br /><br /><ul><li><strong>%s:</strong> %s</li><li><strong>%s:</strong> %s</li><li><strong>%s:</strong> %s</li><li><strong>%s:</strong> %s</li><li><strong>%s:</strong> %s</li><li><strong>%s:</strong> %s</li></ul></div>',
				esc_html__( 'Coupon Template Information', 'gravityflowwoocommerce' ),
				esc_html__( 'Coupon type', 'gravityflowwoocommerce' ),
				wc_get_coupon_type( $coupon_template->get_discount_type() ),
				esc_html__( 'Coupon Amount', 'gravityflowwoocommerce' ),
				wc_price( $coupon_template->get_amount() ),
				esc_html__( 'Products', 'gravityflowwoocommerce' ),
				( empty( $product_ids ) ) ? '-' : gravity_flow_woocommerce()->get_product_names( $product_ids ),
				esc_html__( 'Product Categories', 'gravityflowwoocommerce' ),
				( empty( $product_categories ) ) ? '-' : gravity_flow_woocommerce()->get_product_category_names( $product_categories ),
				esc_html__( 'Usage / Limit', 'gravityflowwoocommerce' ),
				$coupon_template->get_usage_count() . ' / ' . $usage_limit,
				esc_html__( 'Expiry Date', 'gravityflowwoocommerce' ),
				( empty( $expiry_date ) ) ? '-' : $coupon_template->get_date_expires()
			);
		}
	}

	Gravity_Flow_Steps::register( new Gravity_Flow_Step_Woocommerce_Create_Coupon() );
}
