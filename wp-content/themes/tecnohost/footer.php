<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

    if(substr($_SERVER['REQUEST_URI'],'1','23') == "?page=gf_activation&key" || strpos($_SERVER['REQUEST_URI'],'payu') !== false){
        $id = "no_regla";
    }
    else{
        $id = "";
    }


?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer <?php echo $id; ?>" role="contentinfo">
		<div class="col-full <?php echo $id; ?>">

			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
            echo do_shortcode('[elementor-template id="85"]');
			?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
