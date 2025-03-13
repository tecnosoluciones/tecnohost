<?php
/**
* Integration class
*/
class Learndash_Restrict_Content_Pro_Integration
{
	
	public function __construct()
	{
		// Forms and save meta hooks
		add_action( 'rcp_add_subscription_form', array( $this, 'learndash_add_row' ) );
		add_action( 'rcp_edit_subscription_form', array( $this, 'learndash_edit_row' ) );
		add_action( 'rcp_add_subscription', array( $this, 'save_level_meta' ), 10, 2 );
		add_action( 'rcp_edit_subscription_level', array( $this, 'save_level_meta' ), 10, 2 );

		// Associate or disasociate course when RCP member status is changed
		// For RCP < 3.0
		add_action( 'rcp_set_status', array( $this, 'update_course_access' ), 10, 4 );
		// For RCP > 3.0+
		add_action( 'rcp_transition_membership_status', array( $this, 'transition_membership_status' ), 10, 3 );
	}

	/**
	 * Output course select HTML on RCP subscription add page
	 */
	public function learndash_add_row() {
		$courses = $this->get_learndash_courses();
		?>

		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="rcp-role"><?php _e( 'Courses', 'learndash-restrict-content-pro' ); ?></label>
			</th>
			<td>
				<select name="_learndash_restrict_content_pro_courses[]" multiple="multiple">
					<?php foreach ( $courses as $course ) : ?>
					<option value="<?php echo esc_attr( $course->ID ); ?>">
						<?php echo esc_attr( $course->post_title ); ?>
					</option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php _e( 'LearnDash courses you want to associate this membership level with. Hold ctrl on Windows or cmd on Mac to select multiple courses.', 'learndash-restrict-content-pro' ); ?></p>
			</td>
		</tr>

		<?php
	}

	/**
	 * Output course select HTML on RCP subscription edit page
	 * 
	 * @param  object  $level Restrict Content Pro sub level object
	 */
	public function learndash_edit_row( $level ) {
		$courses = $this->get_learndash_courses();
		$level_obj = new RCP_Levels();
		$saved_courses = maybe_unserialize( $level_obj->get_meta( $level->id, '_learndash_restrict_content_pro_courses', true ) );
		?>
		
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="rcp-role"><?php _e( 'Courses', 'learndash-restrict-content-pro' ); ?></label>
			</th>
			<td>
				<select name="_learndash_restrict_content_pro_courses[]" multiple="multiple">
					<?php foreach ( $courses as $course ) : ?>
					<option value="<?php echo esc_attr( $course->ID ); ?>" <?php $this->selected_course( $course->ID, $saved_courses ); ?>>
						<?php echo esc_attr( $course->post_title ); ?>
					</option>
					<?php endforeach; ?>
				</select>
				<p class="description"><?php _e( 'LearnDash courses you want to associate this membership level with. Hold ctrl on Windows or cmd on Mac to select multiple courses.', 'learndash-restrict-content-pro' ); ?></p>
			</td>
		</tr>

		<?php
	}

	/**
	 * Save Restrict Content Pro meta
	 * 
	 * @param  int    $level_id ID of RCP_Levels
	 * @param  array  $product  Restrict Content Pro product information
	 */
	public function save_level_meta( $level_id, $args ) {
		$level = new RCP_Levels();

		$old_courses = maybe_unserialize( $level->get_meta( $level_id, '_learndash_restrict_content_pro_courses', true ) );
		$new_courses = array_map( 'sanitize_text_field', $args['_learndash_restrict_content_pro_courses'] );

		// Update associated course in DB so that it will be executed in cron
		$course_update_queue = get_option( 'learndash_restrict_content_pro_course_access_update', array() );

		$course_update_queue[] = array(
			'level_id' 		=> $level_id,
			'old_courses'   => $old_courses,
			'new_courses'   => $new_courses
		);

		update_option( 'learndash_restrict_content_pro_course_access_update', $course_update_queue );

		$level->update_meta( $level_id, '_learndash_restrict_content_pro_courses', $new_courses );
	}

	/**
	 * Cron job: update user course access
	 */
	public static function cron_update_course_access()
	{
		// Get course update queue
		$updates = get_option( 'learndash_restrict_content_pro_course_access_update', array() );

		foreach ( $updates as $key => $update ) {
			// Get members
			$members = rcp_get_members( $status = 'active', $update['level_id'] );

			$old_courses = $update['old_courses'] ?: array();
			$new_courses = $update['new_courses'] ?: array();

			// Remove or give access for each member
			foreach ( $members as $member ) {
				foreach ( $old_courses as $course_id ) {
					self::remove_course_access( $course_id, $member->ID );
				}

				foreach ( $new_courses as $course_id ) {
					self::add_course_access( $course_id, $member->ID );
				}
			}

			unset( $updates[ $key ] );
		}

		update_option( 'learndash_restrict_content_pro_course_access_update', $updates );
	}

	/**
	 * Update user course access when member is updated
	 * 
	 * @param  string $new_status New member status
	 * @param  int    $member_id  Member user ID
	 * @param  string $old_status Old member status
	 * @param  object $member     Member user object
	 */
	public function update_course_access( $new_status, $member_id, $old_status, $member ) {
		$level_id = $member->get_subscription_id();

		$level = new RCP_Levels();

		$ld_courses = maybe_unserialize( $level->get_meta( $level_id, '_learndash_restrict_content_pro_courses', true ) );

		// If no LearnDash course associated, exit
		if ( empty( $ld_courses ) ) {
			return;
		}

		if ( 'active' == $new_status || 'free' == $new_status ) {
			foreach ( $ld_courses as $course_id ) {
				self::add_course_access( $course_id, $member_id );
			}
		} else {
			foreach ( $ld_courses as $course_id ) {
				self::remove_course_access( $course_id, $member_id );
			}
		}
		
	}

	/**
	 * Update user course access when member is updated
	 *
	 * For RCP 3.0+
	 * 
	 * @param  string $old_status    Old membership status
	 * @param  string $new_status    New membership status
	 * @param  int    $membership_id Membership ID
	 * @return void
	 */
	public function transition_membership_status( $old_status, $new_status, $membership_id ) {
		$membership = rcp_get_membership( $membership_id );
		$customer = $membership->get_customer();
		$user_id  = $customer->get_user_id();

		$level_id = $membership->get_object_id();

		$level = new RCP_Levels();

		$ld_courses = maybe_unserialize( $level->get_meta( $level_id, '_learndash_restrict_content_pro_courses', true ) );

		// If no LearnDash course associated, exit
		if ( empty( $ld_courses ) ) {
			return;
		}

		if ( 'active' == $new_status || 'free' == $new_status ) {
			foreach ( $ld_courses as $course_id ) {
				self::add_course_access( $course_id, $user_id );
			}
		} else {
			foreach ( $ld_courses as $course_id ) {
				self::remove_course_access( $course_id, $user_id );
			}
		}
	}

	/**
	 * Add course access
	 * 
	 * @param int $course_id ID of a course
	 * @param int $user_id   ID of a user
	 */
	public static function add_course_access( $course_id, $user_id ) {
		ld_update_course_access( $user_id, $course_id );
	}

	/**
	 * Add course access
	 * 
	 * @param int $course_id ID of a course
	 * @param int $user_id   ID of a user
	 */
	public static function remove_course_access( $course_id, $user_id ) {
		ld_update_course_access( $user_id, $course_id, $remove = true );
		
	}

	/**
	 * Get all LearnDash courses
	 * 
	 * @return object LearnDash course
	 */
	private function get_learndash_courses()
	{
		global $wpdb;
		$query = "SELECT posts.* FROM $wpdb->posts posts WHERE posts.post_type = 'sfwd-courses' AND posts.post_status = 'publish' ORDER BY posts.post_title";

		return $wpdb->get_results( $query, OBJECT );
	}

	/**
	 * Check if a course belong to a courses array
	 * If true, output HTML attribute checked="checked"
	 * 
	 * @param  int    $course_id     Course ID
	 * @param  array  $courses_array Course IDs array
	 */
	private function checked_course( $course_id, $courses_array )
	{
		if ( in_array( $course_id, $courses_array ) ) {
			echo 'checked="checked"';
		}
	}

	/**
	 * Check if a course belong to a courses array
	 * If true, output HTML attribute selected="selected"
	 * 
	 * @param  int    $course_id     Course ID
	 * @param  array  $courses_array Course IDs array
	 */
	private function selected_course( $course_id, $courses_array )
	{
		if ( in_array( $course_id, $courses_array ) ) {
			echo 'selected="selected"';
		}
	}
}

new Learndash_Restrict_Content_Pro_Integration();