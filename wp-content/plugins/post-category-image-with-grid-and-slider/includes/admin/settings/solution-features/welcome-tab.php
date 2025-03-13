<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="pciwgas_welcome_tabs" class="pciwgas-vtab-cnt pciwgas_welcome_tabs pciwgas-clearfix">
	


	<!-- <div class="pciwgas-deal-offer-wrap">
		<h3 style="font-weight: bold; font-size: 30px; color:#ffef00; text-align:center; margin: 15px 0 5px 0;">Why Invest Time On Free Version?</h3>

		<h3 style="font-size: 18px; text-align:center; margin:0; color:#fff;">Explore Post Category Image with Essential Bundle Free for 5 Days.</h3>			

		<div class="pciwgas-deal-free-offer">
			<a href="<?php //echo esc_url( PCIWGAS_PLUGIN_BUNDLE_LINK ); ?>" target="_blank" class="pciwgas-sf-free-btn"><span class="dashicons dashicons-cart"></span> Try Pro For 5 Days Free</a>
		</div>
	</div> -->

	<div class="pciwgas-black-friday-banner-wrp">
		<a href="<?php echo esc_url( PCIWGAS_PLUGIN_BUNDLE_LINK ); ?>" target="_blank"><img style="width: 100%;" src="<?php echo esc_url( PCIWGAS_URL ); ?>assets/images/black-friday-banner.png" alt="black-friday-banner" /></a>
	</div>

	<!-- Start - Welcome Box -->
	<div class="pciwgas-welcome-wrap" style="padding: 30px;border-radius: 10px;border: 1px solid #e5ecf6;">
		<div class="pciwgas-welcome-inr pciwgas-center">
			<div style="font-size: 24px; font-weight:700; margin-bottom: 15px;">Display <span class="pciwgas-blue">post categories with grid and slider</span> layout. Also given option to upload image for post category.</div>
			<h5 class="pciwgas-content" style="font-size: 20px; font-weight:700; margin-bottom: 15px;">Experience <span class="pciwgas-blue">2 Layouts</span>, <span class="pciwgas-blue">20+ stunning designs.</span></h5>
		</div>
		<div style="margin: 30px 0; text-transform: uppercase; text-align:center;">
			<a href="<?php echo esc_url( $pciwgas_add_link ); ?>" class="pciwgas-sf-btn">Launch Post Category Image With Free Features</a>
		</div>
	</div>

</div>