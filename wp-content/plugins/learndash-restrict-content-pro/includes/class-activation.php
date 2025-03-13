<?php
/**
* Activation class
*/
class Learndash_Restrict_Content_Pro_Activation
{
	
	public function __construct()
	{
		register_activation_hook( LEARNDASH_RESTRICT_CONTENT_PRO_FILE, array( $this, 'activation' ) );
	}

	public function activation()
	{
		if ( ! $this->is_rcp_active() ) {
			deactivate_plugins( LEARNDASH_RESTRICT_CONTENT_PRO_FILE, true );
			add_action( 'admin_notices', array( $this, 'required_notice' ) );
		}
	}

	private function is_rcp_active()
	{
		 if ( is_plugin_active( WP_PLUGIN_DIR . 'restrict-content-pro/restrict-content-pro.php' ) )
		 {
		 	return true;
		 } else {
		 	return false;
		 }
	}

	public function required_notice()
	{
		?>
		
		<div id="message" class="error notice is-dismissible">
			<p><?php _e( 'Restrict Content Pro plugin is required to be activated first.', 'learndash-restrict-content-pro' ); ?></p>
		</div>

		<?php
	}
}

new Learndash_Restrict_Content_Pro_Activation();