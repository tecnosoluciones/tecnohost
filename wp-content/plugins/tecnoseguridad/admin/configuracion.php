<?php
/**
 * Created by PhpStorm.
 * User: DESAR01
 * Date: 15/07/2021
 * Time: 9:58 AM
 */
if (! current_user_can ('manage_options')) wp_die (__ ('No tienes suficientes permisos para acceder a esta página.'));
?>
    < <div class="wrap">
    <p><img src="<?php echo get_site_url(); ?>/wp-content/plugins/tecnoseguridad/images/ts_logo.png" alt="Tecno-Soluciones" title="Tecno-Soluciones"></p>
    <h2><?php _e( 'Tecno-Seguridad', 'tecnoseguridad' ) ?></h2>
    <p>Bienvenido a la Documentación de Tecno-Seguridad</p>
    <p>El plugin de forma automatica integra en la <strong>Página de mi cuenta</strong> de Woocommerce un tab llamado <strong>Autenticación en dos factores</strong></p>
    <p>Usted, haciendo uso del hook <strong>[two_factor_ts]</strong> podrá integrar en cualquier otra página el proceso de configuración y activación de la autenticación en dos factores.</p>
</div>
<?php
?>