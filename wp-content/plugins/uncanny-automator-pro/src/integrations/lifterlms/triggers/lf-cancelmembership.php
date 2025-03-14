<?php

namespace Uncanny_Automator_Pro;

/**
 * Class LF_CANCELMEMBERSHIP
 *
 * @package Uncanny_Automator_Pro
 */
class LF_CANCELMEMBERSHIP {

	/**
	 * Integration code
	 *
	 * @var string
	 */
	public static $integration = 'LF';

	private $trigger_code;
	private $trigger_meta;

	/**
	 * Set up Automator trigger constructor.
	 */
	public function __construct() {

		//migrate old keys to new key
		add_action(
			'admin_init',
			function () {
				if ( 'yes' === get_option( 'llsm_cancel_membership_action_migrated', 'no' ) ) {
					return;
				}
				global $wpdb;
				$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value = %s WHERE meta_value = %s AND meta_key LIKE %s", 'llms_user_removed_from_membership', 'llms_subscription_cancelled_by_student', 'add_action' ) );
				update_option( 'llsm_cancel_membership_action_migrated', 'yes' );
			},
			99
		);

		$this->trigger_code = 'LFCANCELMEMBERSHIP';
		$this->trigger_meta = 'LFMEMBERSHIPS';
		$this->define_trigger();
	}

	/**
	 * Define and register the trigger by pushing it into the Automator object
	 */
	public function define_trigger() {
		$trigger = array(
			'author'              => Automator()->get_author_name( $this->trigger_code ),
			'support_link'        => Automator()->get_author_support_link( $this->trigger_code, 'integration/lifterlms/' ),
			'integration'         => self::$integration,
			'code'                => $this->trigger_code,
			'is_pro'              => true,
			/* translators: Logged-in trigger - LifterLMS */
			'sentence'            => sprintf( esc_attr__( 'A user cancels {{a membership:%1$s}}', 'uncanny-automator-pro' ), $this->trigger_meta ),
			/* translators: Logged-in trigger - LifterLMS */
			'select_option_name'  => esc_attr__( 'A user cancels {{a membership}}', 'uncanny-automator-pro' ),
			'action'              => 'llms_user_removed_from_membership',
			'priority'            => 20,
			'accepted_args'       => 4,
			'validation_function' => array( $this, 'lf_cancel_membership' ),
			'options_callback'    => array( $this, 'load_options' ),
		);

		Automator()->register->trigger( $trigger );
	}

	/**
	 * @return array[]
	 */
	public function load_options() {
		return Automator()->utilities->keep_order_of_options(
			array(
				'options' => array(
					Automator()->helpers->recipe->lifterlms->options->all_lf_memberships( esc_attr__( 'Membership', 'uncanny-automator-pro' ), $this->trigger_meta ),
				),
			)
		);
	}

	/**
	 * Validation function when the trigger action is hit
	 *
	 * @param $user_id
	 * @param $product_id
	 * @param $trigger
	 * @param $new_status
	 *
	 * @return void
	 */
	public function lf_cancel_membership( $user_id, $product_id, $trigger_type, $new_status ) {
		if ( 'cancelled' !== $new_status ) {
			return;
		}

		if ( ! isset( $product_id ) ) {
			return;
		}

		if ( 0 === $user_id ) {
			// Its a logged in recipe and
			// user ID is 0. Skip process
			return;
		}

		$recipes             = Automator()->get->recipes_from_trigger_code( $this->trigger_code );
		$required_membership = Automator()->get->meta_from_recipes( $recipes, $this->trigger_meta );
		$matched_recipe_ids  = array();

		//Add where Membership Matches
		foreach ( $recipes as $recipe_id => $recipe ) {
			foreach ( $recipe['triggers'] as $trigger ) {
				$trigger_id = $trigger['ID'];
				if ( absint( $product_id ) === absint( $required_membership[ $recipe_id ][ $trigger_id ] ) ||
					 intval( $required_membership[ $recipe_id ][ $trigger_id ] ) === intval( '-1' ) ) {
					$matched_recipe_ids[] = array(
						'recipe_id'  => $recipe_id,
						'trigger_id' => $trigger_id,
					);
				}
			}
		}

		if ( ! empty( $matched_recipe_ids ) ) {
			foreach ( $matched_recipe_ids as $matched_recipe_id ) {
				$args = array(
					'code'             => $this->trigger_code,
					'meta'             => $this->trigger_meta,
					'user_id'          => $user_id,
					'recipe_to_match'  => $matched_recipe_id['recipe_id'],
					'trigger_to_match' => $matched_recipe_id['trigger_id'],
					'post_id'          => $product_id,
					'is_signed_in'     => true,
				);

				Automator()->process->user->maybe_add_trigger_entry( $args );
			}
		}
	}

}
