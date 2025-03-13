<?php
/**
 * Gravity Flow Entry Detail
 *
 * @package     GravityFlow
 * @subpackage  Classes/Gravity_Flow
 * @copyright   Copyright (c) 2015-2018, Steven Henty S.L.
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

use Gravity_Flow\Gravity_Flow\Locking;

/**
 * Class Gravity_Flow_Entry_Detail
 */
class Gravity_Flow_Entry_Detail {

	/**
	 * Displays the entry detail page.
	 *
	 * @param array                  $form         The current form.
	 * @param array                  $entry        The current entry.
	 * @param null|Gravity_Flow_Step $current_step Null or the current step.
	 * @param array                  $args         The entry detail page arguments.
	 */
	public static function entry_detail( $form, $entry, $current_step = null, $args = array() ) {

		// In case fields need the GFEntryDetail class.
		require_once( GFCommon::get_base_path() . '/entry_detail.php' );

		$form_id      = absint( $form['id'] );
		$ajax         = false;
		$field_values = null;
		$form         = apply_filters( 'gform_pre_render', $form, $ajax, $field_values );
		$form         = apply_filters( 'gform_pre_render_' . $form_id, $form, $ajax, $field_values );
		$args         = self::get_args( $args );

		$display_empty_fields         = (bool) $args['display_empty_fields'];
		$check_view_entry_permissions = (bool) $args['check_permissions'];
		$show_timeline                = (bool) $args['timeline'];
		$display_instructions         = (bool) $args['display_instructions'];
		$sidebar                      = (bool) $args['sidebar'];

		self::include_scripts();

		?>

		<div class="wrap gf_entry_wrap gravityflow_workflow_wrap gravityflow_workflow_detail">

		<?php

		if ( is_admin() ) {
			$locking = new Locking\Locking();
			$locking->lock_info( $entry['id'], false );
		}

			self::maybe_display_back_link( $args );
			self::maybe_show_header( $form, $args );

			$permission_granted = $check_view_entry_permissions ? self::is_permission_granted( $entry, $form, $current_step ) : true;

			/**
			 * Allows the the permission check to be overridden for the workflow entry detail page.
			 *
			 * @since 2.5.8
			 *
			 * @param bool                   $permission_granted Whether permission is granted to open the entry.
			 * @param array                  $entry              The current entry.
			 * @param array                  $form               The current form.
			 * @param null|Gravity_Flow_Step $current_step       Null or the current step.
			 */
			$permission_granted = apply_filters( 'gravityflow_permission_granted_entry_detail', $permission_granted, $entry, $form, $current_step );

			gravity_flow()->log_debug( __METHOD__ . '() $permission_granted: ' . ( $permission_granted ? 'yes' : 'no' ) );

			if ( ! $permission_granted ) {
				$permission_denied_message = esc_attr__( "You don't have permission to view this entry.", 'gravityflow' );

				// If token was valid but workflow has been cancelled, display either Custom Cancellation Message or Invalid Approval Link Message if they are defined.
				$token   = gravity_flow()->decode_access_token();
				$step_id = rgars( $token, 'scopes/step_id' );

				// Token was valid for a previous step that has an Invalid Approval Link Message.
				if ( rgars( $token, 'scopes/step_id' ) ) {
					$non_current_step = gravity_flow()->get_step( $step_id, $entry );
					if ( $non_current_step && $non_current_step->processed_step_messageEnable ) {
						$permission_denied_message = $non_current_step->processed_step_messageValue;
					}
				}

				// Token was valid for a workflow that has already been cancelled.
				if ( rgars( $token, 'scopes/action' ) == 'cancel_workflow' ) {
					$workflow_status = gform_get_meta( $entry['id'], 'workflow_final_status' );
					if ( $workflow_status == 'cancelled' ) {
						$complete_step = gravity_flow()->get_workflow_complete_step( $form_id, $entry );
						if ( $complete_step->cancellationEnable ) {
							$permission_denied_message = $complete_step->cancellationValue;
						}
					}
				}

				/**
				 * Allows the the permission denied message to be overridden for the workflow entry detail page.
				 *
				 * @since 2.5.8
				 *
				 * @param string                   $permission_denied_message Whether permission is granted to open the entry.
				 * @param null|Gravity_Flow_Step   $current_step              Null or the current step.
				 */
				$permission_denied_message = apply_filters( 'gravityflow_permission_denied_message_entry_detail', $permission_denied_message, $current_step );
				echo $permission_denied_message;
				return;
			}

			$url     = remove_query_arg( array( 'gworkflow_token', 'new_status' ) );
			$classes = self::get_classes( $args );

			?>
				<form id="gform_<?php echo $form_id; ?>" method="post" enctype='multipart/form-data' action="<?php echo esc_url( $url ); ?>">
					<?php wp_nonce_field( 'gforms_save_entry', 'gforms_save_entry' ) ?>
					<input type="hidden" name="step_id" value="<?php echo $current_step ? $current_step->get_id() : ''; ?>" />
					<div id="poststuff">
						<div id="post-body" class="metabox-holder <?php echo $classes; ?>">
							<div id="post-body-content">
								<?php

								/**
								 * Allows customized markup to be included before the entry details grid.
								 *
								 * @since 1.0
								 *
								 * @param array $form  The current form.
								 * @param array $entry The entry currently being displayed.
								 */
								do_action( 'gravityflow_entry_detail_content_before', $form, $entry );

								$editable_fields = array();

								$instructions_step = null;

								if ( $current_step ) {
									$can_update = self::can_update( $current_step );
									if ( $can_update ) {
										$editable_fields = $can_update ? $current_step->get_editable_fields() : array();
										$instructions_step = $current_step;
									} else {
										$instructions_step =  gravity_flow()->get_workflow_start_step( $form_id, $entry );
									}
								} else {
									$instructions_step = gravity_flow()->get_workflow_complete_step( $form_id, $entry );
								}

								if ( $instructions_step && $display_instructions ) {
									self::maybe_show_instructions( $instructions_step, $form, $entry );
								}

								self::entry_detail_grid( $form, $entry, $display_empty_fields, $editable_fields, $current_step );

								do_action( 'gravityflow_entry_detail', $form, $entry, $current_step );

								if ( ! $sidebar ) {
									gravity_flow()->workflow_entry_detail_status_box( $form, $entry, $current_step, $args );
									self::print_button( $entry, $show_timeline, $check_view_entry_permissions );
								}
								?>

								
							</div>
							<div id="postbox-container-1" class="postbox-container">

							<?php
							if ( $sidebar ) {
								gravity_flow()->workflow_entry_detail_status_box( $form, $entry, $current_step, $args );
								self::print_button( $entry, $show_timeline, $check_view_entry_permissions );
							}

							?>
							</div>
							<?php
							self::maybe_show_timeline( $entry, $form, $show_timeline );
							/**
							 * Allows customized markup to be included after the entry details grid.
							 *
							 * @since 2.8.4
							 *
							 * @param array $form  The current form.
							 * @param array $entry The entry currently being displayed.
							 */
							do_action( 'gravityflow_entry_detail_content_after', $form, $entry );
							?>
						</div>
					</div>
				</form>	
		</div>
		<?php
	}

	/**
	 * Merges the specified arguments with the defaults.
	 *
	 * @param array $args The arguments specified when calling the detail page.
	 *
	 * @return array
	 */
	public static function get_args( $args ) {
		$defaults = array(
			'display_empty_fields' => true,
			'check_permissions'    => true,
			'show_header'          => true,
			'timeline'             => true,
			'display_instructions' => true,
			'sidebar'              => true,
			'step_status'          => true,
			'workflow_info'        => true,
			'back_link'            => false,
			'back_link_text'       => __( 'Return to list', 'gravityflow' ),
			'back_link_url'        => null,
		);

		$args = array_merge( $defaults, $args );

		/**
		 * Allow the entry detail arguments to be overridden.
		 *
		 * @since 2.5
		 *
		 * @param array $args The entry detail page arguments.
		 */
		$args = apply_filters( 'gravityflow_entry_detail_args', $args );

		gravity_flow()->log_debug( __METHOD__ . '() args: ' . print_r( $args, true ) );

		return $args;
	}

	/**
	 * Outputs the inline scripts.
	 */
	public static function include_scripts() {
		?>

		<script type="text/javascript">

			if (typeof ajaxurl == 'undefined') {
				ajaxurl = <?php echo json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
			}

			function DeleteFile(leadId, fieldId, deleteButton) {
				if (confirm(<?php echo json_encode( __( "Would you like to delete this file? 'Cancel' to stop. 'OK' to delete", 'gravityflow' ) ); ?>)) {
					var fileIndex = jQuery(deleteButton).parent().index();
					var mysack = new sack("<?php echo admin_url( 'admin-ajax.php' )?>");
					mysack.execute = 1;
					mysack.method = 'POST';
					mysack.setVar("action", "rg_delete_file");
					mysack.setVar("rg_delete_file", "<?php echo wp_create_nonce( 'rg_delete_file' ) ?>");
					mysack.setVar("lead_id", leadId);
					mysack.setVar("field_id", fieldId);
					mysack.setVar("file_index", fileIndex);
					mysack.onError = function () {
						alert(<?php echo json_encode( __( 'Ajax error while deleting file.', 'gravityflow' ) ) ?>)
					};
					mysack.runAJAX();

					return true;
				}
			}

			function EndDeleteFile(fieldId, fileIndex) {
				var previewFileSelector = "#preview_existing_files_" + fieldId + " .ginput_preview";
				var $previewFiles = jQuery(previewFileSelector);
				var rr = $previewFiles.eq(fileIndex);
				$previewFiles.eq(fileIndex).remove();
				var $visiblePreviewFields = jQuery(previewFileSelector);
				if ($visiblePreviewFields.length == 0) {
					jQuery('#preview_' + fieldId).hide();
					jQuery('#upload_' + fieldId).show('slow');
				}
			}

			function ToggleShowEmptyFields() {
				if (jQuery("#gentry_display_empty_fields").is(":checked")) {
					createCookie("gf_display_empty_fields", true, 10000);
					document.location = document.location.href;
				}
				else {
					eraseCookie("gf_display_empty_fields");
					document.location = document.location.href;
				}
			}

			function createCookie(name, value, days) {
				if (days) {
					var date = new Date();
					date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
					var expires = "; expires=" + date.toGMTString();
				}
				else var expires = "";
				document.cookie = name + "=" + value + expires + "; path=/";
			}

			function eraseCookie(name) {
				createCookie(name, "", -1);
			}

		</script>
		<?php
	}

	/**
	 * Output the header, if enabled.
	 *
	 * @param array $form The current form.
	 * @param array $args The arguments to be used when rendering the page.
	 */
	public static function maybe_show_header( $form, $args ) {
		$show_header = (bool) $args['show_header'];
		if ( ! $show_header ) {
			return;
		}

		?>
		<h2 class="gf_admin_page_title">
			<img width="45" height="22"
			     src="<?php echo gravity_flow()->get_base_url(); ?>/images/gravity-flow-icon-cropped.svg"
			     style="margin-right:5px;"/>
			<?php esc_html_e( $form['title'] ); ?><span
				class="gf_admin_page_formid">ID: <?php echo absint( $form['id'] ); ?></span>
		</h2>

		<?php GFCommon::display_admin_message();

		gravity_flow()->toolbar();
	}

	/**
	 * Displays the back link on entry detail page if enabled.
	 *
	 * @since 2.5
	 * 
	 * @param array   $args    The properties for the page currently being displayed.
	 */
	public static function maybe_display_back_link( $args ) {
		$back_link = (bool) $args['back_link'];
		$back_link_text = $args['back_link_text'];
		$back_link_url = $args['back_link_url'];

		if ( ! $back_link || is_admin() ) {
			return;
		}

		$url = is_null( $back_link_url ) ? remove_query_arg( array( 'gworkflow_token', 'new_status', 'view', 'lid', 'id' ) ) : $back_link_url;

		/**
		 * Allows customization of the back link
		 *
		 * Useful in cases where the access into entry detail page is not based out of gravityflow shortcode.
		 *
		 * @since 2.5
		 *
		 * @var string $url    The customized URL to redirect user to when clicking the back link
		 * @var array  $args   The shortcode attributes for the current page
		 *
		 * @return string
		 */
		$url = apply_filters( 'gravityflow_back_link_url_entry_detail', $url, $args );

		printf( '<div class="gravityflow-back-link-container"><a class="back-link" href="%s">%s</a></div>', esc_url( $url ), esc_html( $back_link_text ) );

		return;
	}


	/**
	 * Checks if the current user has permission to view the entry details.
	 *
	 * @param array                  $entry        The current entry.
	 * @param array                  $form         The current form.
	 * @param Gravity_Flow_Step|null $current_step The step currently being displayed.
	 *
	 * @return bool
	 */
	public static function is_permission_granted( $entry, $form, $current_step ) {
		global $current_user;

		$permission_granted = false;
		$assignee_key       = '';
		$user_id            = $current_user->ID;

		if ( empty( $user_id ) ) {
			if ( $token = gravity_flow()->decode_access_token() ) {
				$assignee_key = sanitize_text_field( $token['sub'] );
				list( $type, $user_id ) = rgexplode( '|', $assignee_key, 2 );
			}
		} else {
			$assignee_key = 'user_id|' . $user_id;
		}

		gravity_flow()->log_debug( __METHOD__ . '() checking permissions.  $current_user->ID: ' . $current_user->ID . ' created_by: ' . $entry['created_by'] . ' assignee key: ' . $assignee_key );

		if ( ! empty( $user_id ) && $entry['created_by'] == $user_id ) {
			$permission_granted = true;
		} else {

			$is_assignee = $current_step ? $current_step->is_assignee( $assignee_key ) : false;

			gravity_flow()->log_debug( __METHOD__ . '() $is_assignee: ' . ( $is_assignee ? 'yes' : 'no' ) );

			$full_access = GFAPI::current_user_can_any( array(
				'gform_full_access',
				'gravityflow_status_view_all',
			) );

			gravity_flow()->log_debug( __METHOD__ . '() $full_access: ' . ( $full_access ? 'yes' : 'no' ) );

			if ( $is_assignee || $full_access ) {
				$permission_granted = true;
			}
		}

		return $permission_granted;
	}

	/**
	 * Determines if the role or status permits the user to update field values on this step.
	 *
	 * @param Gravity_Flow_Step $current_step The step this entry is currently on.
	 *
	 * @return bool
	 */
	public static function can_update( $current_step ) {
		$assignees = $current_step->get_assignees();
		$can_update = false;
		foreach ( $assignees as $assignee ) {
			if ( $assignee->is_current_user() ) {
				$can_update = true;
				break;
			}
		}

		return $can_update;
	}

	/**
	 * Displays the step instructions, if appropriate.
	 *
	 * @param Gravity_Flow_Step $current_step         The step this entry is currently on.
	 * @param array             $form                 The current form.
	 * @param array             $entry                The current entry.
	 */
	public static function maybe_show_instructions( $current_step, $form, $entry ) {

		$workflow_status = gform_get_meta( $entry['id'], 'workflow_final_status' );

		$nl2br = apply_filters( 'gravityflow_auto_format_instructions', true );
		$nl2br = apply_filters( 'gravityflow_auto_format_instructions_' . $form['id'], $nl2br );

		// Determine whether to display instructions or cancellation message into the based on workflow status and complete step settings.
		if ( $workflow_status == 'cancelled' && $current_step->get_type() == 'workflow_complete' && $current_step->cancellationEnable ) {
			$instructions = $current_step->cancellationValue;
		} elseif ( ! $current_step->instructionsEnable ) {
				return;
		} else {
			$instructions = $current_step->instructionsValue;
		}

		$instructions = GFCommon::replace_variables( $instructions, $form, $entry, false, true, $nl2br );
		$instructions = do_shortcode( $instructions );
		$instructions = self::maybe_sanitize_instructions( $instructions );

		if ( $instructions ) {
		?>
		<div class="postbox gravityflow-instructions">
			<div class="inside">
				<?php echo $instructions; ?>
			</div>
		</div>

		<?php
		}
	}

	/**
	 * Sanitizes the instructions if sanitization is activated using the gravityflow_sanitize_instructions filter.
	 *
	 * @since 1.6.2
	 *
	 * @param string $instructions The step instructions.
	 *
	 * @return string
	 */
	public static function maybe_sanitize_instructions( $instructions ) {
		$sanitize_instructions = false;

		/**
		 * Allows sanitization to be turned on or off for the instructions.
		 *
		 * Adds an additional layer of security.
		 *
		 * @since 1.6.2
		 *
		 * @param bool $sanitize_instructions Whether to sanitize the confirmation message. default: false
		 */
		$sanitize_instructions = apply_filters( 'gravityflow_sanitize_instructions', $sanitize_instructions );
		if ( $sanitize_instructions ) {
			$instructions = wp_kses_post( $instructions );
		}

		return $instructions;
	}

	/**
	 * Retrieve the css classes to be added to the div#post-body.
	 *
	 * @param array $args The arguments to be used when rendering the page.
	 *
	 * @return string
	 */
	public static function get_classes( $args ) {
		$sidebar               = (bool) $args['sidebar'];
		$display_workflow_info = (bool) $args['workflow_info'];
		$display_step_info     = (bool) $args['step_status'];

		$classes = $sidebar ? 'columns-2' : 'columns-1';
		if ( $sidebar ) {
			$classes .= ' gravityflow-has-sidebar';
		} else {
			$classes .= ' gravityflow-no-sidebar';
		}

		if ( $display_workflow_info ) {
			$classes .= ' gravityflow-has-workflow-info';
		} else {
			$classes .= ' gravityflow-no-workflow-info';
		}

		if ( $display_step_info ) {
			$classes .= ' gravityflow-has-step-info';
		} else {
			$classes .= ' gravityflow-no-step-info';
		}

		return $classes;
	}

	/**
	 * Displays the print button and include timeline checkbox, if applicable.
	 *
	 * @param array $entry The current entry.
	 * @param bool  $show_timeline                Indicates if the timeline should be displayed.
	 * @param bool  $check_view_entry_permissions Indicates if the user/assignee requires permission to view the entry.
	 */
	public static function print_button( $entry, $show_timeline, $check_view_entry_permissions ) {

		if ( is_user_logged_in() || $check_view_entry_permissions ) :
			?>

			<!-- begin print button -->
			<div class="detail-view-print">
				<a href="javascript:;"
				   onclick="var notes_qs = jQuery('#gform_print_notes').is(':checked') ? '&timelines=1' : ''; var url='<?php echo admin_url( 'admin-ajax.php' ) ?>?action=gravityflow_print_entries&lid=<?php echo absint( $entry['id'] ); ?>' + notes_qs; printPage(url);"
				   class="button"><?php esc_html_e( 'Print', 'gravityflow' ) ?></a>

				<?php if ( $show_timeline ) { ?>

					<input type="checkbox" name="print_notes" value="print_notes" checked="checked"
					       id="gform_print_notes"/>
					<label for="print_notes"><?php esc_html_e( 'include timeline', 'gravityflow' ) ?></label>
				<?php } ?>

			</div>
			<!-- end print button -->

		<?php endif;
	}

	/**
	 * Displays the timeline notes, if enabled.
	 *
	 * @param array $entry         The current entry.
	 * @param array $form          The current form.
	 * @param bool  $show_timeline Indicates if the timeline should be displayed.
	 */
	public static function maybe_show_timeline( $entry, $form, $show_timeline ) {
		if ( ! $show_timeline ) {
			return;
		}

		?>
		<div id="postbox-container-2" class="postbox-container">
			<div class="postbox gravityflow-timeline">
				<h3>
					<label for="name"><?php esc_html_e( 'Timeline', 'gravityflow' ); ?></label>
				</h3>

				<div class="inside">
					<?php self::timeline( $entry, $form ); ?>
				</div>

			</div>
		</div>
		<?php
	}

	/**
	 * Gets and displays the notes for the current entry.
	 *
	 * @since 1.7.1-dev Removed unused emails code. Updated to use new method for getting the notes.
	 *
	 * @param array $entry The current entry.
	 * @param array $form  The current form.
	 */
	public static function timeline( $entry, $form ) {
		$notes = self::get_timeline_notes( $entry );
		self::notes_grid( $notes );
	}

	/**
	 * Get the timeline notes for the current entry.
	 *
	 * @param array $entry The current entry.
	 *
	 * @return array
	 */
	public static function get_timeline_notes( $entry ) {

		return Gravity_Flow_Common::get_timeline_notes( $entry );
	}

	/**
	 * Get the avatar for the user or step which added the current note.
	 *
	 * @since 1.7.1-dev
	 *
	 * @param int|bool               $user_id The user ID or false for other types of assignees and step notes.
	 * @param Gravity_Flow_Step|bool $step    A step object or false if the type of step which added the note no longer exists.
	 *
	 * @return string
	 */
	public static function get_avatar( $user_id, $step ) {
		if ( $user_id ) {
			$avatar = get_avatar( $user_id, 65 );
		} else {
			$step_icon = $step ? $step->get_icon_url() : gravity_flow()->get_base_url() . '/images/gravityflow-icon-blue.svg';

			/**
			 * Allows the step icon to be filtered for the timeline.
			 *
			 * @param string                 $step_icon The step icon HTML or image URL.
			 * @param Gravity_Flow_Step|bool $step      A step object or false if the type of step which added the note no longer exists.
			 */
			$step_icon = apply_filters( 'gravityflow_timeline_step_icon', $step_icon, $step );

			if ( strpos( $step_icon, 'http' ) !== false ) {
				$avatar = sprintf( '<img class="avatar avatar-65 photo" src="%s" style="width:65px;height:65px;" />', $step_icon );
			} else {
				$avatar = sprintf( '<span class="avatar avatar-65 photo">%s</span>', $step_icon );
			}
		}

		return sprintf( '<div class="gravityflow-note-avatar">%s</div>', $avatar );
	}

	/**
	 * Format the note body for display.
	 *
	 * @since 1.7.1-dev
	 *
	 * @param object $note         The note properties.
	 * @param string $display_name The user display name, step or workflow label.
	 *
	 * @return string
	 */
	public static function get_note_body( $note, $display_name ) {
		return sprintf(
			'<div class="gravityflow-note-body-wrap"><div class="gravityflow-note-body">%s<div class="gravityflow-note-body">%s</div></div></div>',
			self::get_note_header( $display_name, $note->date_created ),
			nl2br( esc_html( $note->value ) )
		);
	}

	/**
	 * Format the note display name and creation date for display.
	 *
	 * @since 1.7.1-dev
	 *
	 * @param string $display_name The assignee display name, step or workflow label.
	 * @param string $date_created The date and time the note was created.
	 *
	 * @return string
	 */
	public static function get_note_header( $display_name, $date_created ) {
		return sprintf(
			'<div class="gravityflow-note-header"><div class="gravityflow-note-title">%s</div><div class="gravityflow-note-meta">%s</div></div>',
			esc_html( $display_name ),
			esc_html( Gravity_Flow_Common::format_date( $date_created, '', false, true ) )
		);
	}

	/**
	 * Display the workflow notes for the current entry.
	 *
	 * @since 1.7.1-dev Updated for notes stored in the entry meta.
	 *
	 * @param array  $notes       The workflow notes.
	 * @param bool   $is_editable Unused.
	 * @param null   $emails      Unused.
	 * @param string $subject     Unused.
	 */
	public static function notes_grid( $notes, $is_editable = false, $emails = null, $subject = '' ) {
		if ( empty( $notes ) ) {
			return;
		}

		foreach ( $notes as $note ) {
			$user_id      = $note->user_id;
			$step         = Gravity_Flow_Common::get_timeline_note_step( $note );
			$display_name = Gravity_Flow_Common::get_timeline_note_display_name( $note, $step );
			$step_type    = $step ? $step->get_type() : $display_name;

			echo sprintf(
				'<div id="gravityflow-note-%s" class="gravityflow-note gravityflow-note-%s">%s%s</div>',
				esc_attr( $note->id ),
				esc_attr( $step_type ),
				self::get_avatar( $user_id, $step ),
				self::get_note_body( $note, $display_name )
			);
		}
	}

	/**
	 * Display the detail grid, the table which will contain the fields.
	 *
	 * @param array                  $form                       The current form.
	 * @param array                  $entry                      The current entry.
	 * @param bool|false             $allow_display_empty_fields Indicates if empty fields should be displayed.
	 * @param array                  $editable_fields            An array of field IDs which the user can edit.
	 * @param Gravity_Flow_Step|null $current_step               Null or the current step.
	 */
	public static function entry_detail_grid( $form, $entry, $allow_display_empty_fields = false, $editable_fields = array(), $current_step = null ) {
		$form_id = absint( $form['id'] );

		$display_empty_fields = false;
		if ( $allow_display_empty_fields ) {
			$display_empty_fields = rgget( 'gf_display_empty_fields', $_COOKIE );
		}

		$display_empty_fields = (bool) apply_filters( 'gravityflow_entry_detail_grid_display_empty_fields', $display_empty_fields, $form, $entry );

		$step_class = empty( $current_step ) ? 'gravityflow-workflow-complete' : 'gravityflow-step-' . $current_step->get_type();

		?>

		<input type="hidden" name="action" id="action" value="" />
		<input type="hidden" name="save" id="action" value="Update" />
		<input type="hidden" name="screen_mode" id="screen_mode" value="<?php echo esc_attr( rgpost( 'screen_mode' ) ) ?>" />

		<table cellspacing="0" class="widefat fixed entry-detail-view <?php echo esc_attr( $step_class ) ?>">
			<thead>
			<tr>
				<th id="details">
					<?php
					$title = sprintf( '%s : %s %s', esc_html( $form['title'] ), __( 'Entry # ', 'gravityflow' ), absint( $entry['id'] ) );
					echo apply_filters( 'gravityflow_title_entry_detail', $title, $form, $entry );
					?>
				</th>
				<th style="width:140px; font-size:10px; text-align: right;">
					<?php
					if ( $allow_display_empty_fields ) {
						?>
						<input type="checkbox" id="gentry_display_empty_fields" <?php echo $display_empty_fields ? "checked='checked'" : '' ?> onclick="ToggleShowEmptyFields();" />&nbsp;&nbsp;
						<label for="gentry_display_empty_fields"><?php _e( 'show empty fields', 'gravityflow' ) ?></label>
					<?php
					}
					?>
				</th>
			</tr>
			</thead>

			<?php
			if ( empty( $editable_fields ) ) {
				?>
				<tbody>
				<?php
				self::fields( $form, $entry, $display_empty_fields, $current_step, 'table' );
				?>
				</tbody>
				<?php
			} else {
				self::entry_editor( $form, $entry, $current_step, $display_empty_fields );
			}

			?>
			</table>

	<?php
	}

	/**
	 * Handles displaying the relevant non-editable and editable fields for the current step.
	 *
	 * @param array             $form                 The current form.
	 * @param array             $entry                The current entry.
	 * @param Gravity_Flow_Step $current_step         The step this entry is currently on.
	 * @param bool              $display_empty_fields Indicates if fields without a value should be displayed.
	 */
	public static function entry_editor( $form, $entry, $current_step, $display_empty_fields ) {
		?>
		<tbody>
			<tr>
				<td colspan="2">
					<?php
					require_once( 'class-entry-editor.php' );
					$entry_editor = new Gravity_Flow_Entry_Editor( $form, $entry, $current_step, $display_empty_fields );
					$entry_editor->render_edit_form();
					?>
				</td>
			</tr>
			<?php
			self::maybe_show_products_summary( $form, $entry, $current_step );
			?>
		</tbody>
		<?php
	}

	/**
	 * Displays the products summary table if enabled for the current step.
	 *
	 * @param array             $form         The current form.
	 * @param array             $entry        The current entry.
	 * @param Gravity_Flow_Step $current_step The step this entry is currently on.
	 */
	public static function maybe_show_products_summary( $form, $entry, $current_step ) {
		$summary_enabled = true;
		$complete_step = gravity_flow()->get_workflow_complete_step( $form['id'] );
		$processing_step = ( ! $current_step && $complete_step ) ? $complete_step : $current_step;

		if ( $processing_step ) {
			$meta = $processing_step->get_feed_meta();
			if ( isset( $meta['display_order_summary'] ) && ! $processing_step->display_order_summary ) {
				$summary_enabled = false;
			}
		}

		if ( ! $summary_enabled ) {
			return;
		}

		$products = GFCommon::get_product_fields( $form, $entry );

		if ( empty( $products['products'] ) ) {
			return;
		}

		$form_id = $form['id'];

		/**
		 * Enables the order summary label to be changed.
		 *
		 * @since unknown
		 *
		 * @param string $order_summary_label The order summary label.
		 * @param int    $form_id             The current form ID.
		 */
		$order_summary_label = gf_apply_filters( array( 'gform_order_label', $form_id ), __( 'Order', 'gravityflow' ), $form_id );

		ob_start();
		?>
		<tr>
			<td colspan="2" class="gravityflow-order-summary"><?php echo $order_summary_label; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="entry-view-field-value lastrow">
				<?php self::products_summary( $form, $entry, $products ) ?>
			</td>
		</tr>
		<?php
		$order_summary = ob_get_clean();

		/**
		 * Enables the order summary markup to be customized.
		 *
		 * @since 2.3.4
		 *
		 * @var string $markup   The order summary markup.
		 * @var array  $form     Current form object.
		 * @var array  $entry    Current entry object.
		 * @var array  $products Current order summary object.
		 * @var string $format   Format that should be used to display the summary ('html' or 'text').
		 */
		$order_summary = gf_apply_filters( array( 'gform_order_summary', $form['id'] ), $order_summary, $form, $entry, $products, 'html' );

		if ( class_exists( 'GP_Ecommerce_Fields' ) ) {
			// Restore the order label class after it was changed by GPEF.
			$order_summary = str_replace( 'entry-view-field-name', 'gravityflow-order-summary', $order_summary );
		}

		echo $order_summary;
	}

	/**
	 * Displays the markup for form fields.
	 *
	 * @param array                  $form                 The current form.
	 * @param array                  $entry                The current entry.
	 * @param bool                   $display_empty_fields Indicates if empty fields should be displayed.
	 * @param Gravity_Flow_Step|null $current_step         Null or the current step.
	 * @param string                 $format               The requested format: table.
	 */
	public static function fields( $form, $entry, $display_empty_fields, $current_step, $format ) {
		$form_id                 = absint( $form['id'] );
		$count                   = 0;
		$field_count             = sizeof( $form['fields'] );
		$has_product_fields      = false;

		$display_fields_step = false;

		$is_assignee = $current_step ? $current_step->is_user_assignee() : false;

		$complete_step = gravity_flow()->get_workflow_complete_step( $form_id, $entry );
		if ( ! $is_assignee ) {
			if ( $current_step ) {
				// Display fields from current step after a POST, otherwise use the start step settings.
				$display_fields_step = ! empty( $_POST ) ? $current_step : gravity_flow()->get_workflow_start_step( $form_id, $entry );
				if ( $current_step->get_current_assignee_status() == 'complete' || $current_step->get_current_assignee_status() == 'approved' ) {
					$display_fields_step = $complete_step;
				}
			} else {
				$display_fields_step = $complete_step;
			}
		}

		foreach ( $form['fields'] as &$field ) {
			/* @var GF_Field $field */

			// Not needed as we're always adminOnly.
			$field->adminOnly = false;

			$is_product_field = GFCommon::is_product_field( $field->type );

			$display_field = $current_step && $is_assignee ? self::is_display_field( $field, $current_step, $form, $entry, $is_product_field ) : self::is_display_field( $field, $display_fields_step, $form, $entry, $is_product_field );

			$field->gravityflow_is_display_field = $display_field;

			switch ( RGFormsModel::get_input_type( $field ) ) {
				case 'section' :

					if ( ! self::is_section_empty( $field, $current_step, $form, $entry, $display_empty_fields ) ) {
						$count ++;
						$is_last = $count >= $field_count ? true : false;
						?>
						<tr>
							<td colspan="2"
							    class="entry-view-section-break<?php echo $is_last ? ' lastrow' : '' ?>"><?php echo esc_html( $field->label ) ?></td>
						</tr>
						<?php
					}

					break;

				case 'captcha':
				case 'password':
				case 'page':
					// Ignore captcha, password, page field.
					break;

				case 'html':
					if ( $display_field ) {
						$content = GFCommon::replace_variables( $field->content, $form, $entry, false, true, false, 'html' );
						$content = do_shortcode( $content );
						?>
						<tr>
							<td colspan="2" class="entry-view-field-value"><?php echo $content ?></td>
						</tr>
						<?php
					}

					break;
				default :

					if ( $is_product_field ) {
						$has_product_fields = true;
					}

					if ( ! $display_field ) {
						continue 2;
					}

					$value         = RGFormsModel::get_lead_field_value( $entry, $field );
					$display_value = self::get_display_value( $value, $field, $entry, $form );

					if ( $display_empty_fields || ! empty( $display_value ) || $display_value === '0' ) {
						$count ++;
						$is_last  = $count >= $field_count && ! $has_product_fields ? true : false;
						$last_row = $is_last ? ' lastrow' : '';

						$display_value = empty( $display_value ) && $display_value !== '0' ? '&nbsp;' : $display_value;

						$content = '
                                <tr>
                                    <td colspan="2" class="entry-view-field-name">' . esc_html( self::get_label( $field, $entry ) ) . '</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="entry-view-field-value' . $last_row . '">' . $display_value . '</td>
                                </tr>';

						$content = apply_filters( 'gform_field_content', $content, $field, $value, $entry['id'], $form['id'] );
						echo $content;
					}

					break;
			}
		}

		if ( $format == 'table' ) {
			self::maybe_show_products_summary( $form, $entry, $current_step );
		}

	}

	/**
	 * Determine if the current section is empty.
	 *
	 * @param GF_Field               $section_field        The section field properties.
	 * @param Gravity_Flow_Step|null $current_step         The current step for this entry.
	 * @param array                  $form                 The form for the current entry.
	 * @param array                  $entry                The entry being processed for display.
	 * @param bool                   $display_empty_fields Indicates if empty fields should be displayed.
	 *
	 * @return bool
	 */
	public static function is_section_empty( $section_field, $current_step, $form, $entry, $display_empty_fields ) {
		$cache_key = "Gravity_Flow_Entry_Detail::is_section_empty_{$form['id']}_{$section_field->id}_{$display_empty_fields}";
		$value     = GFCache::get( $cache_key );

		if ( $value !== false ) {
			return $value == true;
		}

		$section_fields = GFCommon::get_section_fields( $form, $section_field->id );

		foreach ( $section_fields as $field ) {
			if ( $field->type == 'section' ) {
				continue;
			}

			$is_product_field = GFCommon::is_product_field( $field->type );
			$display_field    = self::is_display_field( $field, $current_step, $form, $entry, $is_product_field );

			if ( ! $display_field ) {
				continue;
			}

			$value         = RGFormsModel::get_lead_field_value( $entry, $field );
			$display_value = self::get_display_value( $value, $field, $entry, $form );

			if ( rgblank( $display_value ) && ! $display_empty_fields ) {
				continue;
			}

			GFCache::set( $cache_key, 0 );

			return false;
		}

		GFCache::set( $cache_key, 1 );

		return true;
	}

	/**
	 * Determine if the field should be displayed.
	 *
	 * @param GF_Field               $field            The field properties.
	 * @param Gravity_Flow_Step|null $current_step     The current step for this entry.
	 * @param array                  $form             The form for the current entry.
	 * @param array                  $entry            The entry being processed for display.
	 * @param bool                   $is_product_field Is the current field one of the product field types.
	 *
	 * @return bool
	 */
	public static function is_display_field( $field, $current_step, $form, $entry, $is_product_field ) {
		return Gravity_Flow_Common::is_display_field( $field, $current_step, $form, $entry, $is_product_field );
	}

	/**
	 * Get the field value to be displayed.
	 *
	 * @param mixed    $value The field value from the entry.
	 * @param GF_Field $field The field properties.
	 * @param array    $entry The entry being processed for display.
	 * @param array    $form  The form for the current entry.
	 *
	 * @return string
	 */
	public static function get_display_value( $value, $field, $entry, $form ) {
		if ( $field->type == 'product' && $field->has_calculation() ) {
			$product_name = trim( $value[ $field->id . '.1' ] );
			$price        = trim( $value[ $field->id . '.2' ] );
			$quantity     = trim( $value[ $field->id . '.3' ] );

			if ( empty( $product_name ) ) {
				$value[ $field->id . '.1' ] = $field->get_field_label( false, $value );
			}

			if ( empty( $price ) ) {
				$value[ $field->id . '.2' ] = '0';
			}

			if ( empty( $quantity ) ) {
				$value[ $field->id . '.3' ] = '0';
			}
		}

		$input_type = $field->get_input_type();
		if ( $input_type == 'hiddenproduct' ) {
			$display_value = $value[ $field->id . '.2' ];
		} else {
			$display_value = GFCommon::get_lead_field_display( $field, $value, $entry['currency'] );
		}

		$display_value = apply_filters( 'gform_entry_field_value', $display_value, $field, $entry, $form );

		return $display_value;
	}

	/**
	 * Get the label to display for this field. Uses the admin label if the main label is not configured.
	 *
	 * @since unknown
	 * @since 2.8.5   Add entry parameter for live merge tag processing
	 * 
	 * @param GF_Field $field The field properties.
	 * @param array    $entry The current entry.
	 *
	 * @return string
	 */
	public static function get_label( $field, $entry ) {
		if ( strpos( $field->label, '@{' ) !== false && class_exists( 'GP_Populate_Anything_Live_Merge_Tags' ) ) {
			$gp_lmt  = GP_Populate_Anything_Live_Merge_Tags::get_instance();
			$form_id = $field->formId;
			$form    = GFAPI::get_form( $form_id );
			$matches = array();
			preg_match( $gp_lmt->live_merge_tag_regex, $field->label, $matches );
			$merge_tag_match_value = GFCommon::replace_variables( $matches[1], $form, $entry, false, false, false );
			return str_replace( $matches[0], $merge_tag_match_value, $field->label );
		}
		return empty( $field->label ) ? $field->adminLabel : $field->label;
	}

	/**
	 * Displays the product summary table.
	 *
	 * @param array $form     The current form.
	 * @param array $entry    The current entry.
	 * @param array $products The product info for this entry.
	 */
	public static function products_summary( $form, $entry, $products ) {
		$form_id = absint( $form['id'] );
		?>
		<table class="entry-products" cellspacing="0" width="97%">
			<colgroup>
				<col class="entry-products-col1" />
				<col class="entry-products-col2" />
				<col class="entry-products-col3" />
				<col class="entry-products-col4" />
			</colgroup>
			<thead>
			<th scope="col"><?php echo apply_filters( "gform_product_{$form_id}", apply_filters( 'gform_product', __( 'Product', 'gravityflow' ), $form_id ), $form_id ); ?></th>
			<th scope="col" class="textcenter"><?php echo esc_html( apply_filters( "gform_product_qty_{$form_id}", apply_filters( 'gform_product_qty', __( 'Qty', 'gravityflow' ), $form_id ), $form_id ) ); ?></th>
			<th scope="col"><?php echo esc_html( apply_filters( "gform_product_unitprice_{$form_id}", apply_filters( 'gform_product_unitprice', __( 'Unit Price', 'gravityflow' ), $form_id ), $form_id ) ); ?></th>
			<th scope="col"><?php echo esc_html( apply_filters( "gform_product_price_{$form_id}", apply_filters( 'gform_product_price', __( 'Price', 'gravityflow' ), $form_id ), $form_id ) ); ?></th>
			</thead>
			<tbody>
			<?php

			$total = 0;
			foreach ( $products['products'] as $product ) {
				if ( empty( $product['name'] ) ) {
					continue;
				}
				?>
				<tr>
					<td>
						<div class="product_name"><?php echo esc_html( $product['name'] ); ?></div>
						<ul class="product_options">
							<?php
							$price = GFCommon::to_number( $product['price'] );
							if ( is_array( rgar( $product, 'options' ) ) ) {
								$count = sizeof( $product['options'] );
								$index = 1;
								foreach ( $product['options'] as $option ) {
									$price += GFCommon::to_number( $option['price'] );
									$class = $index == $count ? " class='lastitem'" : '';
									$index ++;
									?>
									<li<?php echo $class ?>><?php echo $option['option_label'] ?></li>
									<?php
								}
							}
							$subtotal = floatval( $product['quantity'] ) * $price;
							$total += $subtotal;
							?>
						</ul>
					</td>
					<td class="textcenter"><?php echo esc_html( $product['quantity'] ); ?></td>
					<td><?php echo GFCommon::to_money( $price, $entry['currency'] ) ?></td>
					<td><?php echo GFCommon::to_money( $subtotal, $entry['currency'] ) ?></td>
				</tr>
				<?php
			}
			$total += floatval( $products['shipping']['price'] );
			?>
			</tbody>
			<tfoot>
			<?php
			if ( ! empty( $products['shipping']['name'] ) ) {
				?>
				<tr>
					<td colspan="2" rowspan="2" class="emptycell">&nbsp;</td>
					<td class="textright shipping"><?php echo esc_html( $products['shipping']['name'] ); ?></td>
					<td class="shipping_amount"><?php echo GFCommon::to_money( $products['shipping']['price'], $entry['currency'] ) ?>&nbsp;</td>
				</tr>
				<?php
			}
			?>
			<tr>
				<?php
				if ( empty( $products['shipping']['name'] ) ) {
					?>
					<td colspan="2" class="emptycell">&nbsp;</td>
					<?php
				}
				?>
				<td class="textright grandtotal"><?php _e( 'Total', 'gravityflow' ) ?></td>
				<td class="grandtotal_amount"><?php echo GFCommon::to_money( $total, $entry['currency'] ) ?></td>
			</tr>
			</tfoot>
		</table>
		<?php
	}

}
