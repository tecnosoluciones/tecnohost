<?php
/**
 * Gravity Flow Multi-User Field
 *
 * @package     GravityFlow
 * @copyright   Copyright (c) 2015-2018, Steven Henty S.L.
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

/**
 * Class Gravity_Flow_Field_Multi_User
 */
class Gravity_Flow_Field_Multi_User extends GF_Field_MultiSelect {

	/**
	 * The field type.
	 *
	 * @var string
	 */
	public $type = 'workflow_multi_user';

	/**
	 * Set the storage type to json
	 *
	 * @var string
	 */
	public $storageType = 'json';

	/**
	 * Returns the multiuser field's icon.
	 *
	 * @return string
	 */
	public function get_form_editor_field_icon() {
		return 'dashicons-groups';
	}
	
	/**
	 * Adds the Workflow Fields group to the form editor.
	 *
	 * @param array $field_groups The properties for the field groups.
	 *
	 * @return array
	 */
	public function add_button( $field_groups ) {
		$field_groups = Gravity_Flow_Fields::maybe_add_workflow_field_group( $field_groups );

		return parent::add_button( $field_groups );
	}

	/**
	 * Returns the field button properties for the form editor.
	 *
	 * @return array
	 */
	public function get_form_editor_button() {
		return array(
			'group' => 'workflow_fields',
			'text'  => $this->get_form_editor_field_title(),
		);
	}

	/**
	 * Returns the class names of the settings which should be available on the field in the form editor.
	 *
	 * @return array
	 */
	function get_form_editor_field_settings() {
		return array(
			'conditional_logic_field_setting',
			'prepopulate_field_setting',
			'error_message_setting',
			'enable_enhanced_ui_setting',
			'label_setting',
			'label_placement_setting',
			'admin_label_setting',
			'size_setting',
			'rules_setting',
			'visibility_setting',
			'description_setting',
			'css_class_setting',
			'gravityflow_setting_users_role_filter',
		);
	}

	/**
	 * Returns the field title.
	 *
	 * @return string
	 */
	public function get_form_editor_field_title() {
		return __( 'Multi-User', 'gravityflow' );
	}

	/**
	 * Return the HTML markup for the field choices.
	 *
	 * @param string $value The field value.
	 *
	 * @return string
	 */
	public function get_choices( $value ) {
		if ( $this->is_form_editor() ) {
			// Prevent the choices from being stored in the form meta.
			$this->choices = array();
		}

		return parent::get_choices( $value );
	}

	/**
	 * Get an array of choices containing the users.
	 *
	 * @return array
	 */
	public function get_users_as_choices() {
		$form_id = $this->formId;

		$default_args = array(
			'orderby' => array( 'display_name', 'user_login' ),
			'fields'  => array( 'ID', 'display_name', 'user_login' ),
			'role'    => $this->gravityflowUsersRoleFilter,
		);

		$args            = wp_parse_args( apply_filters( 'gravityflow_get_users_args_user_field', $default_args, $form_id, $this ), $default_args );
		$accounts        = get_users( $args );
		$account_choices = array();
		foreach ( $accounts as $account ) {
			$account_choices[] = array( 'value' => $account->ID, 'text' => $account->display_name );
		}

		return apply_filters( 'gravityflow_user_field', $account_choices, $form_id, $this );
	}

	/**
	 * Return the entry value for display on the entries list page.
	 *
	 * @param string|array $value    The field value.
	 * @param array        $entry    The Entry Object currently being processed.
	 * @param string       $field_id The field or input ID currently being processed.
	 * @param array        $columns  The properties for the columns being displayed on the entry list page.
	 * @param array        $form     The Form Object currently being processed.
	 *
	 * @return string
	 */
	public function get_value_entry_list( $value, $entry, $field_id, $columns, $form ) {

		$user_ids = $this->to_array( $value );

		$display_names = $this->get_display_names( $user_ids );

		$assignee = parent::get_value_entry_list( $display_names, $entry, $field_id, $columns, $form );
		$value    = $this->get_display_name( $assignee );

		return $value;
	}

	/**
	 * Return the entry value which will replace the field merge tag.
	 *
	 * @param string       $value      The field value. Depending on the location the merge tag is being used the following functions may have already been applied to the value: esc_html, nl2br, and urlencode.
	 * @param string       $input_id   The field or input ID from the merge tag currently being processed.
	 * @param array        $entry      The Entry Object currently being processed.
	 * @param array        $form       The Form Object currently being processed.
	 * @param string       $modifier   The merge tag modifier. e.g. value.
	 * @param string|array $raw_value  The raw field value from before any formatting was applied to $value.
	 * @param bool         $url_encode Indicates if the urlencode function may have been applied to the $value.
	 * @param bool         $esc_html   Indicates if the esc_html function may have been applied to the $value.
	 * @param string       $format     The format requested for the location the merge is being used. Possible values: html, text or url.
	 * @param bool         $nl2br      Indicates if the nl2br function may have been applied to the $value.
	 *
	 * @return string
	 */
	public function get_value_merge_tag( $value, $input_id, $entry, $form, $modifier, $raw_value, $url_encode, $esc_html, $format, $nl2br ) {

		$user_ids = $this->to_array( $raw_value );

		$output_arr = array();

		foreach ( $user_ids as $user_id ) {
			$output_arr[] = $modifier == 'value' ? $user_id : Gravity_Flow_Fields::get_user_variable( $user_id, $modifier, $url_encode, $esc_html );
		}

		return GFCommon::implode_non_blank( ', ', $output_arr );
	}

	/**
	 * Return the entry value for display on the entry detail page and for the {all_fields} merge tag.
	 *
	 * @param string     $value    The field value.
	 * @param string     $currency The entry currency code.
	 * @param bool|false $use_text When processing choice based fields should the choice text be returned instead of the value.
	 * @param string     $format   The format requested for the location the merge is being used. Possible values: html, text or url.
	 * @param string     $media    The location where the value will be displayed. Possible values: screen or email.
	 *
	 * @return string
	 */
	public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ) {

		if ( empty( $value ) || $format == 'text' ) {
			return $value;
		}

		$user_ids = $this->to_array( $value );

		$display_names = $this->get_display_names( $user_ids );

		return parent::get_value_entry_detail( $display_names, $currency, $use_text, $format, $media );
	}

	/**
	 * Gets the display name for the selected user.
	 *
	 * @param int $user_id The array of user ID.
	 *
	 * @return string
	 */
	public function get_display_name( $user_id ) {
		if ( empty( $user_id ) ) {
			return '';
		}
		$user  = get_user_by( 'id', $user_id );
		$value = is_object( $user ) ? $user->display_name : $user_id;

		return $value;
	}

	public function get_display_names( $user_ids ) {
		$display_names = array();

		foreach ( $user_ids as $user_id ) {
			$display_names[] = $this->get_display_name( $user_id );
		}

		return $display_names;
	}

	/**
	 * Format the entry value before it is used in entry exports and by framework add-ons using GFAddOn::get_field_value().
	 *
	 * @param array      $entry    The entry currently being processed.
	 * @param string     $input_id The field or input ID.
	 * @param bool|false $use_text When processing choice based fields should the choice text be returned instead of the value.
	 * @param bool|false $is_csv   Indicates if the value is going to be used in the .csv entries export.
	 *
	 * @return string
	 */
	public function get_value_export( $entry, $input_id = '', $use_text = false, $is_csv = false ) {
		if ( empty( $input_id ) ) {
			$input_id = $this->id;
		}

		$value = json_decode( rgar( $entry, $input_id ), true );

		if ( $use_text == true || $is_csv == true ) {
			$display_names = $this->get_display_names( $value );

			return GFCommon::implode_non_blank( ', ', $display_names );
		}

		if ( $use_text == false && $is_csv == false ) {
			return rgar( $entry, $input_id );
		}

		return GFCommon::implode_non_blank( ', ', $value );
	}

	/**
	 * Sanitize the field settings when the form is saved.
	 */
	public function sanitize_settings() {
		parent::sanitize_settings();
		if ( ! empty( $this->gravityflowUsersRoleFilter ) ) {
			$this->gravityflowUsersRoleFilter = wp_strip_all_tags( $this->gravityflowUsersRoleFilter );
		}
	}

	/**
	 * Add the users as choices.
	 *
	 * @since 1.7.1-dev
	 */
	public function post_convert_field() {
		parent::post_convert_field();
		if ( ! $this->is_form_editor() ) {
			$this->choices = $this->get_users_as_choices();
		}
	}

	/**
	 * Validate the field value. It must be one of the choices.
	 *
	 * Return the result (bool) by setting $this->failed_validation.
	 * Return the validation message (string) by setting $this->validation_message.
	 *
	 * @since 2.5.2
	 *
	 * @param string|array $value The field value from get_value_submission().
	 * @param array        $form  The Form Object currently being processed.
	 */
	public function validate( $value, $form ) {
		if ( ! empty( $value ) ) {
			$values = wp_list_pluck( $this->get_users_as_choices(), 'value' );

			foreach ( (array) $value as $_value ) {
				if ( ! in_array( $_value, $values ) ) {
					$this->failed_validation  = true;
					$this->validation_message = esc_html__( 'Invalid selection. Please select one of the available choices.', 'gravityflow' );

					break;
				}
			}
		}
	}

	/**
	 * Returns a formatted version of the field value to be added to the Zapier request body.
	 *
	 * @since 2.7.5
	 *
	 * @param array $entry The entry being sent to Zapier.
	 *
	 * @return array
	 */
	public function get_value_zapier_formatted( $entry ) {
		return $this->get_display_names( $this->to_array( rgar( $entry, (string) $this->id ) ) );
	}

}

GF_Fields::register( new Gravity_Flow_Field_Multi_User() );
