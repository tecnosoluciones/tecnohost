<?php
/**
 * REST API Reports Stats controller
 * Handles requests to the '/reports/composites/stats' endpoint.
 *
 * @package  WooCommerce Composite Products
 * @since    8.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Automattic\WooCommerce\Admin\API\Reports\ParameterException;

/**
 * WC_CP_Analytics_Revenue_Stats_REST_Controller class.
 *
 * @version 8.8.0
 */
class WC_CP_Analytics_Revenue_Stats_REST_Controller extends WC_REST_Reports_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc-analytics';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'reports/composites/stats';

	/**
	 * Mapping between external parameter name and name used in query class.
	 *
	 * @var array
	 */
	protected $param_mapping = array(
		'products' => 'product_includes',
	);

	/**
	 * Get all items.
	 *
	 * @param WP_REST_Request $request Request data.
	 * @return array|WP_Error
	 */
	public function get_items( $request ) {

		$query_args = array(
			'fields' => array(
				'items_sold',
				'composited_items_sold',
				'net_revenue',
				'orders_count',
				'products_count',
			),
		);

		$registered = array_keys( $this->get_collection_params() );
		foreach ( $registered as $param_name ) {
			if ( isset( $request[ $param_name ] ) ) {
				if ( isset( $this->param_mapping[ $param_name ] ) ) {
					$query_args[ $this->param_mapping[ $param_name ] ] = $request[ $param_name ];
				} else {
					$query_args[ $param_name ] = $request[ $param_name ];
				}
			}
		}

		$query = new WC_CP_Analytics_Revenue_Stats_Query( $query_args );
		try {
			$report_data = $query->get_data();
		} catch ( ParameterException $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}

		$out_data = array(
			'totals'    => get_object_vars( $report_data->totals ),
			'intervals' => array(),
		);

		foreach ( $report_data->intervals as $interval_data ) {
			$item                    = $this->prepare_item_for_response( $interval_data, $request );
			$out_data['intervals'][] = $this->prepare_response_for_collection( $item );
		}

		$response = rest_ensure_response( $out_data );
		$response->header( 'X-WP-Total', (int) $report_data->total );
		$response->header( 'X-WP-TotalPages', (int) $report_data->pages );

		$page      = $report_data->page_no;
		$max_pages = $report_data->pages;

		// nosemgrep: audit.php.wp.security.xss.query-arg
		$base = add_query_arg( $request->get_query_params(), rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ) );
		if ( $page > 1 ) {
			$prev_page = $page - 1;
			if ( $prev_page > $max_pages ) {
				$prev_page = $max_pages;
			}
			$prev_link = add_query_arg( 'page', $prev_page, $base );
			$response->link_header( 'prev', $prev_link );
		}
		if ( $max_pages > $page ) {
			$next_page = $page + 1;
			$next_link = add_query_arg( 'page', $next_page, $base );
			$response->link_header( 'next', $next_link );
		}

		return $response;
	}

	/**
	 * Prepare a report object for serialization.
	 *
	 * @param Array           $report  Report data.
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public function prepare_item_for_response( $report, $request ) {
		$data = $report;

		$context = ! empty( $request[ 'context' ] ) ? $request[ 'context' ] : 'view';
		$data    = $this->add_additional_fields_to_object( $data, $request );
		$data    = $this->filter_response_by_context( $data, $context );

		// Wrap the data in a response object.
		$response = rest_ensure_response( $data );

		/**
		 * Filter a report returned from the API.
		 *
		 * Allows modification of the report data right before it is returned.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param object           $report   The original report object.
		 * @param WP_REST_Request  $request  Request used to generate the response.
		 */
		return apply_filters( 'woocommerce_rest_prepare_report_composites_stats', $response, $report, $request );
	}

	/**
	 * Get the Report's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$data_values = array(
			'items_sold'            => array(
				'title'       => __( 'Items Sold', 'woocommerce-composite-products' ),
				'description' => __( 'Number of items sold.', 'woocommerce-composite-products' ),
				'type'        => 'integer',
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
				'indicator'   => true,
			),
			'composited_items_sold' => array(
				'title'       => __( 'Composited Items Sold', 'woocommerce-composite-products' ),
				'description' => __( 'Number of items sold.', 'woocommerce-composite-products' ),
				'type'        => 'integer',
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
				'indicator'   => true,
			),
			'net_revenue'           => array(
				'description' => __( 'Net Sales.', 'woocommerce-composite-products' ),
				'type'        => 'number',
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
				'format'      => 'currency',
			),
			'orders_count'          => array(
				'description' => __( 'Number of orders.', 'woocommerce-composite-products' ),
				'type'        => 'integer',
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
			),
		);

		$segments = array(
			'segments' => array(
				'description' => __( 'Reports data grouped by segment condition.', 'woocommerce-composite-products' ),
				'type'        => 'array',
				'context'     => array( 'view', 'edit' ),
				'readonly'    => true,
				'items'       => array(
					'type'       => 'object',
					'properties' => array(
						'segment_id'    => array(
							'description' => __( 'Segment identificator.', 'woocommerce-composite-products' ),
							'type'        => 'integer',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
						),
						'segment_label' => array(
							'description' => __( 'Human readable segment label, either product or variation name.', 'woocommerce-composite-products' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
							'enum'        => array( 'day', 'week', 'month', 'year' ),
						),
						'subtotals'     => array(
							'description' => __( 'Interval subtotals.', 'woocommerce-composite-products' ),
							'type'        => 'object',
							'context'     => array( 'view', 'edit' ),
							'readonly'    => true,
							'properties'  => $data_values,
						),
					),
				),
			),
		);

		$totals = array_merge( $data_values, $segments );

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'report_products_stats',
			'type'       => 'object',
			'properties' => array(
				'totals'    => array(
					'description' => __( 'Totals data.', 'woocommerce-composite-products' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
					'properties'  => $totals,
				),
				'intervals' => array(
					'description' => __( 'Reports data grouped by intervals.', 'woocommerce-composite-products' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'interval'       => array(
								'description' => __( 'Type of interval.', 'woocommerce-composite-products' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
								'enum'        => array( 'day', 'week', 'month', 'year' ),
							),
							'date_start'     => array(
								'description' => __( "The date the report start, in the site's timezone.", 'woocommerce-composite-products' ),
								'type'        => 'date-time',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'date_start_gmt' => array(
								'description' => __( 'The date the report start, as GMT.', 'woocommerce-composite-products' ),
								'type'        => 'date-time',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'date_end'       => array(
								'description' => __( "The date the report end, in the site's timezone.", 'woocommerce-composite-products' ),
								'type'        => 'date-time',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'date_end_gmt'   => array(
								'description' => __( 'The date the report end, as GMT.', 'woocommerce-composite-products' ),
								'type'        => 'date-time',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'subtotals'      => array(
								'description' => __( 'Interval subtotals.', 'woocommerce-composite-products' ),
								'type'        => 'object',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
								'properties'  => $totals,
							),
						),
					),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Set the default results to 0 if API returns an empty array
	 *
	 * @param Mixed $results Report data.
	 * @return object
	 */
	public function set_default_report_data( $results ) {
		if ( empty( $results ) ) {
			$results                                = new \stdClass();
			$results->total                         = 0;
			$results->totals                        = new \stdClass();
			$results->totals->items_sold            = 0;
			$results->totals->composited_items_sold = 0;
			$results->totals->net_revenue           = 0;
			$results->totals->orders_count          = 0;
			$results->intervals                     = array();
			$results->pages                         = 1;
			$results->page_no                       = 1;
		}
		return $results;
	}

	/**
	 * Get the query params for collections.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params               = array();
		$params['context']    = $this->get_context_param( array( 'default' => 'view' ) );
		$params['page']       = array(
			'description'       => __( 'Current page of the collection.', 'woocommerce-composite-products' ),
			'type'              => 'integer',
			'default'           => 1,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
			'minimum'           => 1,
		);
		$params['per_page']   = array(
			'description'       => __( 'Maximum number of items to be returned in result set.', 'woocommerce-composite-products' ),
			'type'              => 'integer',
			'default'           => 10,
			'minimum'           => 1,
			'maximum'           => 100,
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['after']      = array(
			'description'       => __( 'Limit response to resources published after a given ISO8601 compliant date.', 'woocommerce-composite-products' ),
			'type'              => 'string',
			'format'            => 'date-time',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['before']     = array(
			'description'       => __( 'Limit response to resources published before a given ISO8601 compliant date.', 'woocommerce-composite-products' ),
			'type'              => 'string',
			'format'            => 'date-time',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['order']      = array(
			'description'       => __( 'Order sort attribute ascending or descending.', 'woocommerce-composite-products' ),
			'type'              => 'string',
			'default'           => 'desc',
			'enum'              => array( 'asc', 'desc' ),
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['orderby']    = array(
			'description'       => __( 'Sort collection by object attribute.', 'woocommerce-composite-products' ),
			'type'              => 'string',
			'default'           => 'date',
			'enum'              => array(
				'date',
				'net_revenue',
				'orders_count',
				'items_sold',
				'composited_items_sold',
			),
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['interval']   = array(
			'description'       => __( 'Time interval to use for buckets in the returned data.', 'woocommerce-composite-products' ),
			'type'              => 'string',
			'default'           => 'week',
			'enum'              => array(
				'hour',
				'day',
				'week',
				'month',
				'quarter',
				'year',
			),
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['products']   = array(
			'description'       => __( 'Limit result to items with specified product ids.', 'woocommerce-composite-products' ),
			'type'              => 'array',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
			'items'             => array(
				'type' => 'integer',
			),
		);
		$params['segmentby']  = array(
			'description'       => __( 'Segment the response by additional constraint.', 'woocommerce-composite-products' ),
			'type'              => 'string',
			'enum'              => array(
				'product',
				'category',
				'variation',
			),
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['fields']     = array(
			'description'       => __( 'Limit stats fields to the specified items.', 'woocommerce-composite-products' ),
			'type'              => 'array',
			'sanitize_callback' => 'wp_parse_slug_list',
			'validate_callback' => 'rest_validate_request_arg',
			'items'             => array(
				'type' => 'string',
			),
		);

		return $params;
	}
}
