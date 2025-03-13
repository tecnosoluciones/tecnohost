<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */
$variable = get_woocommerce_currency();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>
<?php

    if(substr($_SERVER['REQUEST_URI'],'1','23') == "?page=gf_activation&key" || strpos($_SERVER['REQUEST_URI'],'payu') !== false){
        $id = "no_regla";
    }
    else{
        $id = "";
    }

?>
<body <?php body_class(); ?>>

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site" >
	<?php do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header <?php echo $id; ?>" role="banner" style="<?php storefront_header_styles(); ?>" <?php echo $id; ?>>

		<?php
		/**
		 * Functions hooked into storefront_header action
		 *
		 * @hooked storefront_header_container                 - 0
		 * @hooked storefront_skip_links                       - 5
		 * @hooked storefront_social_icons                     - 10
		 * @hooked storefront_site_branding                    - 20
		 * @hooked storefront_secondary_navigation             - 30
		 * @hooked storefront_product_search                   - 40
		 * @hooked storefront_header_container_close           - 41
		 * @hooked storefront_primary_navigation_wrapper       - 42
		 * @hooked storefront_primary_navigation               - 50
		 * @hooked storefront_header_cart                      - 60
		 * @hooked storefront_primary_navigation_wrapper_close - 68
		 */
        echo do_shortcode('[elementor-template id="40"]');
		?>

	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'storefront_before_content' );
	?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            var row = '<?php echo $variable;?>';
        });
    </script>
	<div id="content" class="site-content <?php echo $id; ?>" tabindex="-1">
		<div class="col-full" id="<?php echo $id; ?>">

		<?php
		do_action( 'storefront_content_top' );
