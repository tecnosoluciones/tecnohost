<?php
/**
 * Class to create additional product panel in admin
 * @package TPWCP
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'WCTH_Admin' ) ) {
    class WCTH_Admin {

        public function __construct() {
        }

        public function init() {
            // Create the custom tab
            add_filter( 'woocommerce_product_data_tabs', array( $this, 'create_domain_tab' ) );
            // Add the custom fields
            add_action( 'woocommerce_product_data_panels', array( $this, 'display_domain_fields' ) );
            // Save the custom fields
            add_action( 'woocommerce_process_product_meta', array( $this, 'save_fields' ) );

            //Opción en el menú
            add_action('admin_menu',  array( $this, 'th_plugin_setup_menu' ));


        }

        public function get_list_domains() {

            $args = array(
                'post_type' => 'product',
//                'post__in' => array(28987),
                'product_cat' => 'Dominios',
                'posts_per_page' => -1,
                'order_by' => 'title',
                'order' => 'ASC',
            );

            $loop = new WP_Query($args);

            $products = [];

            if ($loop->have_posts()) {

                while ($loop->have_posts()) : $loop->the_post();

                    $pid = get_the_ID();

                    $product = wc_get_product($pid);
                    $products[] = $product->get_name();

                    endwhile;
                }
                return $products;

        }
        public function th_plugin_setup_menu(  ) {

            add_menu_page( 'TecnoHost', 'TecnoHost', 'manage_options', 'tecnohost-setup', array( $this, 'th_setup' ), plugins_url('../images/icon.png', __FILE__));
            add_action( 'admin_init', array( $this, 'register_th_plugin_settings' ) );
        }
        function register_th_plugin_settings() {
            //register our settings
            register_setting( 'tecnohost-setup', 'user_api_onlinenic' );
            register_setting( 'tecnohost-setup', 'pass_api_onlinenic' );
            register_setting( 'tecnohost-setup', 'test_api_onlinenic' );
            register_setting( 'tecnohost-setup', 'api_key_onlinenic' );
            register_setting( 'tecnohost-setup', 'tls_api_onlinenic' );
        }

        public function  th_setup(){
            $test_api_onlinenic = (boolean) get_option('test_api_onlinenic');

            $active_tab = isset($_GET[ 'tab' ])?$_GET[ 'tab' ]:'apiconfig';

        ?>
                            <div class="wrap">
                <h1>Configuración de TecnoHost <?php if(!empty($test_api_onlinenic) and $test_api_onlinenic == true) echo '<div style="background: #e25b2e;display: inline-block;font-size: 14px;color: #fff;padding: 4px;font-weight: 500;">MODO PRUEBAS</div>';?></h1>
                                <h2 class="nav-tab-wrapper">
                                    <a href="?page=tecnohost-setup&tab=apiconfig" class="nav-tab <?php echo $active_tab == 'apiconfig' ? 'nav-tab-active' : ''; ?>">Configuración API onlineNic</a>
                                    <a href="?page=tecnohost-setup&tab=infoDomain" class="nav-tab <?php echo $active_tab == 'infoDomain' ? 'nav-tab-active' : ''; ?>">Información de dominio</a>
                                </h2>

                                <?php

            if( $active_tab == 'apiconfig' ) {

                ?>
                <form method="post" action="options.php">
                    <p>OnlineNIC API. Para más información visite <a target="_blank"
                                                                     href="https://wiki.onlinenic.com/#!index.md">https://wiki.onlinenic.com/#!index.md</a>
                    </p>
                    <?php settings_fields('tecnohost-setup'); ?>
                    <?php do_settings_sections('tecnohost-setup'); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Usuario</th>
                            <td><input type="text" name="user_api_onlinenic"
                                       value="<?php echo esc_attr(get_option('user_api_onlinenic')); ?>"/></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Contraseña</th>
                            <td><input type="password" name="pass_api_onlinenic"
                                       value="<?php echo esc_attr(get_option('pass_api_onlinenic')); ?>"/></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">API KEY</th>
                            <td><input type="text" name="api_key_onlinenic"
                                       value="<?php echo esc_attr(get_option('api_key_onlinenic')); ?>"/></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Ambiente de pruebas</th>
                            <td><input type="checkbox"
                                       name="test_api_onlinenic" <?php if (get_option('test_api_onlinenic') == 'true') echo "checked"; ?>
                                       value="true"/></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">TLS soportados</th>
                            <td>
                                <?php
                                $options_tls = (get_option('tls_api_onlinenic'))?get_option('tls_api_onlinenic'): [];
                              
                                $filas = 5;
                                $i = 1;
                                $total = count($this->get_list_domains());
                                $width = 100 / ($total / $filas);

                                foreach ($this->get_list_domains() as $domain){

                                    $length_name_domain = strlen($domain);
                                    $pos = strpos($domain, '.');

                                    $extension = substr($domain, $pos + 1, $length_name_domain);

                                    $checked = (in_array($extension, $options_tls)) ? 'checked' : '';
                                    echo '<span style="width: '.$width.'%;display: inline-block;">
                                        <input  '.$checked.' type="checkbox" name="tls_api_onlinenic[]" value="'.$extension.'"/> '.$domain.'</span>';

                                    if($filas==$i){
                                        echo "<br>";
                                        $i = 1;
                                    }else{
                                        $i++;
                                    }


                                }
                                ?>
                            </td>
                        </tr>

                    </table>

                    <?php submit_button(); ?>

                </form>

                <?php
            }

            if( $active_tab == 'infoDomain' ) {

                ?>
                <h1>Información de dominio</h1>
                <p>Búsqueda de whois de dominio y detalles de estado</p>
                                <form method="post">
                                    <label for="domain_th">Dominio:</label>
                                    <input required type="text" name="domain_th_api" placeholder="Ejemplo: tecnohost.net">
                                    <?php submit_button(); ?>
                                </form>

                                <?php
                if(isset($_POST['domain_th_api'])){

                    $user_api_onlinenic = get_option('user_api_onlinenic');
                    $pass_api_onlinenic = get_option('pass_api_onlinenic');
                    $api_key_onlinenic = get_option('api_key_onlinenic');

                    if(empty($user_api_onlinenic) or empty($pass_api_onlinenic) or empty($api_key_onlinenic)) $test_api_onlinenic = true;

                    $online_nic_admin = new API_Onlinenic($user_api_onlinenic, $pass_api_onlinenic, $api_key_onlinenic, $test_api_onlinenic);

                    $domain_th = $_POST['domain_th_api'];

                    var_dump($online_nic_admin->CreateContact(array(
                        'ext'=> 'com',
                        'name'=>'Test',
                        'org'=>'TSC',
                        'country'=>'CO',
                        'province'=>'AN',
                        'city'=>'ME',
                        'street'=>'Calle',
                        'postalcode'=>'2121',
                        'voice'=>'+57.1234567',
                        'fax'=>'+57.1234567',
                        'email'=>'test@tecnosoluciones.com',
                        )));
                          die($domain_th);
                    var_dump($online_nic_admin->registerDomain(array(
                        'domain'=> "pruebafgfgfgf433.com",
                        'period'=>5,
                        'dns1'=>'ns1.1ahost.com',
                        'dns2'=>'ns2.1ahost.com',
                        'registrant'=>'oln1625762081',
                        'admin'=>'oln1625762081',
                        'tech'=>'oln1625762081',
                        'billing'=>'oln1625762081'
                        )));
                    die($domain_th);
                    $response = json_decode($online_nic_admin->infoDomain($domain_th), true);
                    if($response['msg'] =='Command completed successfully.'){
                            echo "<br><strong>Dominio</strong>: ".$response['data']['domain']."</br>";
                            echo "<strong>Estado</strong>: ".$response['data']['status']."</br>";
                            echo "<strong>Fecha de registro</strong>: ".$response['data']['regdate']."</br>";
                            echo "<strong>Fecha de expiración</strong>: ".$response['data']['expdate']."</br>";
                            echo "<strong>Información del Administrador</strong>: ".$response['data']['admin']."</br>";
                            echo "<strong>Información del Contacto Ténico</strong>: ".$response['data']['tech']."</br>";
                            echo "<strong>Información del Contacto de Facturación<strong>: ".$response['data']['billing']."</br>";
                            echo "<strong>Información del Registrador</strong>: ".$response['data']['registrant']."</br>";
                            echo "<strong>DNS 1</strong>: ".$response['data']['dns1']."</br>";
                            echo "<strong>DNS 2</strong>: ".$response['data']['dns2']."</br>";
                            echo "<strong>DNS 3</strong>: ".$response['data']['dns3']."</br>";
                            echo "<strong>DNS 4</strong>: ".$response['data']['dns4']."</br>";
                            echo "<strong>DNS 5</strong>: ".$response['data']['dns5']."</br>";
                            echo "<strong>DNS 6</strong>: ".$response['data']['dns6']."</pre>";

                    }else{
                        echo "<pre>No se pudo obtener la información de este dominio <br>";
                        if($response['msg'])
                            echo "<strong>Detalle del error: ".$response['msg']."</strong></pre>";
                    }
                }
            }
                ?>
                </div>
<?php
        }
        /**
         * Add the new tab to the $tabs array
         * @see     https://github.com/woocommerce/woocommerce/blob/e1a82a412773c932e76b855a97bd5ce9dedf9c44/includes/admin/meta-boxes/class-wc-meta-box-product-data.php
         * @param   $tabs
         * @since   1.0.0
         */
        public function create_domain_tab( $tabs ) {
            $tabs['domain'] = array(
                'label'         => __( 'Configurar Dominio', 'tpwcp' ), // The name of your panel
                'target'        => 'domain_panel', // Will be used to create an anchor link so needs to be unique
                'class'         => array( 'domain_tab', 'show_if_simple', 'show_if_variable' ), // Class for your panel tab - helps hide/show depending on product type
                'priority'      => 80, // Where your panel will appear. By default, 70 is last item
            );
            return $tabs;
        }

        /**
         * Display fields for the new panel
         * @see https://docs.woocommerce.com/wc-apidocs/source-function-woocommerce_wp_checkbox.html
         * @since   1.0.0
         */
        public function display_domain_fields() { ?>

            <div id='domain_panel' class='panel woocommerce_options_panel'>
                <div class="options_group">
                    <?php

                                foreach ($this->wcth_get_currencies() as $obj){

                                   woocommerce_wp_text_input(
                                        array(
                                            'id'        => 'transferencia_costo_'.$obj,
                                            'label'     => __( 'Costo de transferencia ('.$obj.')', 'wcth' ),
                                            'type'      => 'text',
                                            'desc_tip'  => __( 'Ingrese el costo de la transferencia para el dominio ('.$rows->option_value.')', 'wcth' )
                                        )
                                    );
                                }

                                ?>
                </div>
                <div class="options_group">
                    <?php
                    foreach ($this->wcth_get_currencies() as $obj){

                                   woocommerce_wp_text_input(
                                        array(
                                            'id'        => 'registro_costo_'.$obj,
                                            'label'     => __( 'Descuento de registro ('.$obj.')', 'wcth' ),
                                            'type'      => 'text',
                                            'desc_tip'  => __( 'Ingrese el descuento del registro para el dominio ('.$rows->option_value.')', 'wcth' )
                                        )
                                    );
                                }
                    ?>
                </div>

                <div class="options_group">
                    <?php

                        woocommerce_wp_text_input(
                            array(
                                'id'            => 'server_domain',
                                'label'         => __( 'Servidor Whois', 'wcth' ),
                                'type'          => 'text',
                                'placeholder'   => 'Ejemplo: whois.nic.ve',
                                'desc_tip'      => __( 'Ingrese el nombre de servidor whois para el TLS', 'wcth' )
                            )
                        );

                        woocommerce_wp_text_input(
                            array(
                                'id'            => 'domain_tls',
                                'label'         => __( 'TLS', 'wcth' ),
                                'type'          => 'text',
                                'placeholder'   => 'Ejemplo: com',
                                'desc_tip'      => __( 'Ingrese el TLS de su dominio', 'wcth' )
                            )
                        );
                        woocommerce_wp_text_input(
                            array(
                                'id'            => 'limit_transferencia',
                                'label'         => __( 'Per&iacute;odo M&aacute;ximo para Transferencia (a&ntilde;os)', 'wcth' ),
                                'type'          => 'number',
                                'placeholder'   => 'Por defecto: 5',
                                'desc_tip'      => __( 'Período Máximo de Transferencia (años)', 'wcth' )
                            )
                        );

                    ?>
                </div>
            </div>

        <?php }

        /**
         * Save the custom fields using CRUD method
         * @param $post_id
         * @since 1.0.0
         */
        public function save_fields( $post_id ) {

            $product = wc_get_product( $post_id );

            // Save the transferencia_costo setting
            foreach ($this->wcth_get_currencies() as $obj){
                $costo = isset( $_POST['transferencia_costo_'.$obj] ) ? $_POST['transferencia_costo_'.$obj] : '';
                $product->update_meta_data( 'transferencia_costo_'.$obj, sanitize_text_field( $costo ) );
                $desucento = isset( $_POST['registro_costo_'.$obj] ) ? $_POST['registro_costo_'.$obj] : '';
                $product->update_meta_data( 'registro_costo_'.$obj, sanitize_text_field( $desucento ) );
            }

            $server = isset( $_POST['server_domain'] ) ? $_POST['server_domain'] : '';
            $product->update_meta_data( 'server_domain', sanitize_text_field( $server ) );

            $domain_tls = isset( $_POST['domain_tls'] ) ? $_POST['domain_tls'] : '';
            $product->update_meta_data( 'domain_tls', sanitize_text_field( $domain_tls ) );

            $limit_transferencia = isset( $_POST['limit_transferencia'] ) ? $_POST['limit_transferencia'] : '';
            $product->update_meta_data( 'limit_transferencia', sanitize_text_field( $limit_transferencia ) );

            $product->save();

        }


        public function wcth_get_currencies(){

            global $wpdb;

            $rows = $wpdb->get_results("select option_name, option_value from {$wpdb->prefix}options WHERE option_name='wcj_multicurrency_total_number'");

            $currencies = array();

            if($rows) {

                foreach ($rows as $obj)

                    $num_currencies = $obj->option_value;;

                for ($i = 1; $i <= $num_currencies;$i++){

                    $rows = $wpdb->get_results("select option_name, option_value from {$wpdb->prefix}options WHERE option_name='wcj_multicurrency_currency_{$i}'");

                    if ($rows != false) {

                        foreach ($rows as $obj)

                            array_push($currencies, $obj->option_value);

                    }
                }



            }

            return $currencies;
        }

    }
}