<?php

namespace Uncanny_Automator_Pro;

/**
 * Class LF_DOES_NOT_HAVE_MEMBERSHIP
 *
 * @package Uncanny_Automator_Pro
 */
class LF_DOES_NOT_HAVE_MEMBERSHIP extends Action_Condition {

	/**
	 * Define_condition
	 *
	 * @return void
	 */
	public function define_condition() {

		$this->integration = 'LF';
		/*translators: Token */
		$this->name = __( 'The user does not have {{a membership}}', 'uncanny-automator-pro' );
		$this->code = 'LF_DOES_NOT_HAVE_MEMBERSHIP';
		// translators: A token matches a value
		$this->dynamic_name  = sprintf( esc_html__( 'The user does not have {{a membership:%1$s}}', 'uncanny-automator-pro' ), 'LF_MEMBERSHIP' );
		$this->is_pro        = true;
		$this->requires_user = true;
	}

	/**
	 * Fields
	 *
	 * @return array
	 */
	public function fields() {

		$membership_field_args = array(
			'option_code'              => 'LF_MEMBERSHIP',
			'label'                    => esc_html__( 'Membership', 'uncanny-automator-pro' ),
			'required'                 => true,
			'options'                  => $this->lf_membership_options(),
			'supports_custom_value'    => false,
			'supports_multiple_values' => true,
		);

		$any_or_all_args = array(
			'option_code'           => 'LF_ANYORALL',
			'label'                 => esc_attr__( 'Match', 'uncanny-automator-pro' ),
			'required'              => true,
			'supports_custom_value' => false,
			'options'               => array(
				array(
					'value' => 'all',
					'text'  => esc_attr__( 'All', 'uncanny-automator-pro' ),
				),
				array(
					'value' => 'any',
					'text'  => esc_attr__( 'Any', 'uncanny-automator-pro' ),
				),
			),
		);

		return array(
			// Course field
			$this->field->select_field_args( $membership_field_args ),
			// Any or all
			$this->field->select_field_args( $any_or_all_args ),
		);
	}

	/**
	 * @return array[]
	 */
	public function lf_membership_options() {

		$args = array(
			'post_type'      => 'llms_membership',
			'posts_per_page' => 999, //phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		);

		$options     = array();
		$memberships = Automator()->helpers->recipe->options->wp_query( $args, true, esc_attr__( 'Any membership', 'uncanny-automator' ) );
		foreach ( $memberships as $membership_id => $membership_title ) {
			$options[] = array(
				'value' => $membership_id,
				'text'  => $membership_title,
			);
		}

		return $options;
	}

	/**
	 * Evaluate_condition
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function evaluate_condition() {

		$value   = $this->get_option( 'LF_MEMBERSHIP' );
		$student = llms_get_student( $this->user_id );

		// User does not have any membership.
		if ( ! $student ) {
			return;
		}

		// Get all memberships for the student.
		$memberships_config = $student->get_enrollments( 'membership' );
		$memberships        = $memberships_config['results'];

		// No current memberships all conditions are met.
		if ( empty( $memberships ) ) {
			return;
		}

		// Check if any membership -1 is selected.
		if ( in_array( '-1', $value ) ) {
			$this->condition_failed( __( 'User has memberships', 'uncanny-automator-pro' ) );
			return;
		}

		// Get the relation.
		$relation = $this->get_option( 'LF_ANYORALL' );

		// Check if student has ANY of the selected memberships.
		if ( 'any' === $relation ) {
			$has_any_memberships = ! empty( array_intersect( $value, $memberships ) );
			if ( $has_any_memberships ) {
				$message = sprintf(
					/* translators: %1$s: Membership name(s) */
					__( 'User has one or more of the memberships : %1$s', 'uncanny-automator-pro' ),
					$this->get_option( 'LF_MEMBERSHIP_readable' )
				);
				$this->condition_failed( $message );
			}
			return;
		}

		// Check if student has ALL of the selected memberships.
		if ( 'all' === $relation ) {
			$has_all_memberships = empty( array_diff( $value, $memberships ) );
			if ( $has_all_memberships ) {
				$message = sprintf(
					/* translators: %1$s: Membership name(s) */
					__( 'User has all of the memberships : %1$s', 'uncanny-automator-pro' ),
					$this->get_option( 'LF_MEMBERSHIP_readable' )
				);
				$this->condition_failed( $message );
			}
		}

	}
}
