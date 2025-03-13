<?php
/**
* Tools class
*/
class Learndash_Restrict_Content_Pro_Tools
{
	
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'retroactive_access_menu' ) );
		add_action( 'admin_notices', array( $this, 'retroactive_access_notice' ) );
		add_action( 'wp_ajax_learndash_restrict_content_pro_retroactive', array( $this, 'retroactive_access_process' ) );
		add_action( 'admin_head', array( $this, 'retroactive_access_page_style' ) );
	}

	/**
	 * Add tools page
	 */
	public function retroactive_access_menu()
	{
		add_submenu_page( 'learndash_restrict_content_pro_not_exists', __( 'LearnDash Restrict Content Pro', 'learndash-restrict-content-pro' ), __( 'LearnDash Restrict Content Pro', 'learndash-restrict-content-pro' ), 'manage_options', 'learndash-restrict-content-pro', array( $this, 'learndash_restrict_content_pro_page') );
	}

	/**
	 * Output admin notice
	 */
	public function retroactive_access_notice()
	{
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'learndash-restrict-content-pro' ) {
			return;
		}

		$options = get_option( 'learndash_restrict_content_pro', array() );

		if ( isset( $options['retroactive_access'] ) && $options['retroactive_access'] == 1 ) {
			return;
		}

		?>

		<div id="message" class="notice notice-warning">
			<p><?php printf( __( 'After configuring LearnDash - Restrict Content Pro integration settings, please <a href="%s">click here</a> to start retroactive member access process.', 'learndash-restrict-content-pro' ), 'admin.php?page=learndash-restrict-content-pro&action=retroactive_access&_wpnonce=' . wp_create_nonce( 'learndash_restrict_content_pro' ) ); ?></p>
		</div>

		<?php
	}

	/**
	 * Output tools page HTML and scripts
	 */
	public function learndash_restrict_content_pro_page()
	{
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'retroactive_access' )
		{
			if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'learndash_restrict_content_pro' ) ) {
				return;
			}

			?>

			<div class="wrap">
				<div class="learndash-restrict-content-pro-message">
					<p><?php _e( 'The process is being done. Please be patient.', 'learndash-restrict-content-pro' ); ?></p>
				</div>
				<div class="learndash-restrict-content-pro-progress-bar-wrapper">
					<div class="learndash-restrict-content-pro-spinner"></div>
					<div class="learndash-restrict-content-pro-progress-bar"></div>
					<div class="clear"></div>
				</div>
			</div>

			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					$( window ).load( function() {
						process_step( 1 );
					} );

					function process_step( step ) {
						$.ajax( {
							url: ajaxurl,
							type: 'POST',
							dataType: 'json',
							data: {
								'action': 'learndash_restrict_content_pro_retroactive',
								'step': step,
							},
							success: function( response ) {
								if ( 'done' == response.step ) {
									$( '.learndash-restrict-content-pro-progress-bar' ).animate( {
										width: response.percentage + '%' },
										50, function() {
										// Animation complete.
									} );

									setTimeout( function() {
										$( '.learndash-restrict-content-pro-message' ).remove();
										$( '.learndash-restrict-content-pro-progress-bar' ).remove();
										$( '.learndash-restrict-content-pro-spinner' ).remove();

										$( '.learndash-restrict-content-pro-progress-bar-wrapper' ).html( '<p>' + '<?php _e( 'The process is complete.', 'learndash-restrict-content-pro' ); ?>' + '</p>');
									}, 2000 );
								} else {
									$( '.learndash-restrict-content-pro-progress-bar' ).animate( {
										width: response.percentage + '%' },
										50, function() {
										// Animation complete.
									} );

									process_step( parseInt( response.step ) );
								}
							}
						} )
						.fail( function( response ) {
							if ( window.console && window.console.log ) {
								console.log( response );
							}
						} );
					}
				});
			</script>

			<?php
		}
	}

	/**
	 * Proceed retroactive course access process
	 */
	public function retroactive_access_process()
	{
		$per_batch  = 100;
		$step       = intval( $_POST['step'] );
		$offset     = $per_batch * ( $step - 1 );
		$total      = rcp_count_members();
		$percentage = $step / ( $total / $per_batch ) * 100;
		$percentage = $percentage > 100 ? 100 : $percentage;
		
		if ( ceil( $total / $per_batch ) < $step ) {
			$options = get_option( 'learndash_restrict_content_pro', array() );
			$options['retroactive_access'] = 1;
			update_option( 'learndash_restrict_content_pro', $options );

			echo json_encode( array( 'percentage' => 100, 'step' => 'done' ) );
		} else {
			$step += 1;

			// global $wpdb;
			// $mepr_db = new MeprDb();
			// $query = "SELECT user_id, product_id FROM {$mepr_db->transactions} WHERE status = 'complete' LIMIT {$per_batch} OFFSET {$offset}";
			// $members = $wpdb->get_results( $query, OBJECT );
			$members = rcp_get_members( $status = 'active', $subscription = null, $offset, $per_batch );

			foreach ( $members as $member ) {
				$member   = new RCP_Member( $member->ID );
				$level_id = $member->get_subscription_id();

				$level   = new RCP_Levels();
				$courses = maybe_unserialize( $level->get_meta( $level_id, '_learndash_restrict_content_pro_courses', true ) );

				foreach ( $courses as $course ) {
					Learndash_Restrict_Content_Pro_Integration::add_course_access( $course, $member->ID );
				}
			}

			echo json_encode( array( 'percentage' => $percentage, 'step' => $step ) );
		}

		wp_die();
	}

	/**
	 * Tools page style
	 */
	public function retroactive_access_page_style()
	{
		if ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != 'retroactive_access' ) {
			return;
		}

		?>

		<style type="text/css">
			.learndash-restrict-content-pro-progress-bar-wrapper {
				padding: 10px;
			}

			.learndash-restrict-content-pro-progress-bar {
				background-color: #3498db;
				border: 1px solid #fff;
				margin-top: 5px;
				height: 15px;
				width: 0;
				max-width: 97%;
				float: left;
			}

			.learndash-restrict-content-pro-spinner {
				border: 4px solid #f3f3f3; /* Light grey */
			    border-top: 4px solid #3498db; /* Blue */
			    background-color: #fff;
			    border-radius: 50%;
			    margin-right: 15px;
			    width: 20px;
			    height: 20px;
			    animation: spin 1s linear infinite;
			    float: left;
			}

			@keyframes spin {
				0% { transform: rotate( 0deg ); }
				100% { transform: rotate( 360deg ); }
			}
		</style>

		<?php
	}
}

new Learndash_Restrict_Content_Pro_Tools();