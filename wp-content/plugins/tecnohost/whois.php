<?php
//$product = wc_get_product( $product_id );
//var_dump($product);


class TWhois
{


    public $servers = [
       "com" => array("whois.verisign-grs.com", "whois.crsnic.net"),
        "us" => array("whois.nic.us", "whois.nic.us"),
        "web.ve" => array("whois.nic.ve", "whois.nic.ve"),
        "com.co" => array("whois.nic.co", "whois.nic.co"),
        "net" => array("whois.verisign-grs.com", "whois.verisign-grs.com"),
        "co.ve" => array("whois.nic.ve", "whois.nic.ve"),
        "biz" => array("whois.nic.biz", "whois.nic.biz"),
        "co" => array("whois.nic.co", "whois.nic.co"),
        "info.ve" => array("whois.nic.ve", "whois.nic.ve"),
        "org" => array("whois.pir.org", "whois.publicinterestregistry.net"),
        "com.ve" => array("whois.nic.ve", "whois.nic.ve"),
        "net.co" => array("whois.nic.co", "whois.nic.co"),
        "net.ve" => array("whois.nic.ve", "whois.nic.ve"),
        "info" => array("whois.afilias.net", "whois.afilias.info"),
        "org.ve" => array("whois.nic.ve", "whois.nic.ve"),
        "xyz" => array("whois.nic.xyz", "whois.nic.xyz"),
        "online" => array("whois.nic.online", "whois.nic.online"),
        "shop" => array("whois.nic.shop", "whois.nic.shop"),
        "top" => array("whois.nic.top", "whois.nic.top"),
        "cloud" => array("whois.nic.cloud", "whois.nic.cloud"),
        "pro" => array("whois.nic.pro", "whois.nic.pro"),
        "mobi" => array("whois.nic.mobi", "whois.nic.mobi"),
        "me" => array("whois.nic.me", "whois.nic.me"),
        "eu" => array("whois.eu", "whois.eu"),
        "io" => array("whois.nic.io", "whois.nic.io"),
        "tel" => array("whois.nic.tel", "whois.nic.tel"),
        "tv" => array("whois.nic.tv", "whois.verisign-grs.com"),
        "coach" => array("whois.nic.coach", "whois.nic.coach"),
        "com.cn" => array("whois.nic.cn", "whois.nic.cn"),
        "cn" => array("whois.nic.cn", "whois.nic.cn"),
        "es" => array("whois.nic.es", "whois.nic.es"),
        "travel" => array("whois.nic.travel", "whois.nic.travel"),
        "co.ve" => array("whois.nic.ve", "whois.nic.ve")
    ];


    public $extensions = ['com', 'us', 'web.ve', 'com.co', 'net', 'co.ve', 'biz', 'co', 'info.ve', 'org', 'com.ve', 'net.co', 'net.ve', 'info', 'org.ve', 'xyz', 'online', 'shop', 'top', 'cloud', 'pro', 'mobi', 'me', 'eu', 'io', 'tel', 'tv', 'coach', 'com.cn', 'cn', 'es', 'travel', 'co.ve' ];

    public $domains_available = [];

    public $domains_transferable = [];

    public $domains_reneval = [];

    public $domain;

    public $helpName = false;

    public $extensioHelpName = false;


    public $viewWhois = array();

    public function appointWhois($domain)
    {
        global $woocommerce_wpml;
        $this->domain = $domain;


        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            // Put your plugin code here
            $args = array(
                'post_type' => 'product',
               // 'post__in' => array(79869),
                'product_cat' => 'Dominios',
                'posts_per_page' => -1,
                'order_by' => 'date',
                'order' => 'ASC',
            );

            $loop = new WP_Query($args);

            if ($loop->have_posts()) {

                while ($loop->have_posts()) : $loop->the_post();

                    $pid = get_the_ID();

                    $product = wc_get_product($pid);

                    //Verifica si el producto es variable
                    if ( $product->is_type( 'variable' ) ) {

                        $pid = get_the_ID();
                        $server = get_post_meta($pid,'server_domain', true);
                        $tls = get_post_meta($pid,'domain_tls', true);
                        $limit_transferencia = get_post_meta($pid,'limit_transferencia', true);
                        $variations = $product->get_available_variations();

                        $product_v = wc_get_product($variations[0]['variation_id']);
 //var_dump($product_v->get_price_html());
//$price = $woocommerce_wpml->multi_currency->prices->get_product_price_in_currency( $variations[0]['variation_id'], 'USD' );
// var_dump($variations);


                        $product_array = array(
                            'id_product'                    => $pid,
                            'product_name'                  => $product->get_name(),
                            'is_variable'                   => $product->is_type( 'variable' ),
                            'variations'                    => $variations,
                            'product_price_variation'       => $variations[0]['display_price'],//$product->price
                            'product_price_variation_html'  => $variations[0]['price_html'],//$product->get_price_html()
                            'registro_costo'                => get_post_meta($pid,'registro_costo_'.get_woocommerce_currency(), true),
                            'domain_tls'                    => $tls,
                            'server_domain'                 => $server,
                            'domain'                        => $domain,
                            'limit_transferencia'           => $limit_transferencia,
                        );
                        //Verificar sólo los que tengan el server y tls definidos
                        if(!empty($tls) or !empty($server)){
                            //Verificar WHOIS
                        
                            $respon = $this->checkWhois($domain, $tls, $server);

                            //Si el servidor whois no responde false, verifica todo lo demás
                            if($respon != false AND !stristr($respon, 'restricted')){

                                if (stristr($respon, 'Status: free') or
                                    stristr($respon, 'is free') or
                                    stristr($respon, 'no match') or
                                    stristr($respon, 'not found') or
                                    stristr($respon, 'Available') or
                                    stristr($respon, 'nothing found') or
                                    stristr($respon, 'Status: invalid') or
                                    stristr($respon, 'No Data Found') or
                                    stristr($respon, 'No Data Found >>>') or
                                    stristr($respon, 'Neither object nor interpretation control keyword found') or
                                    stristr($respon, 'does not exist: DOMAIN NOT FOUND') or
                                    stristr($respon, 'is available! Choose a registrar from below to register') or
                                    stristr($respon, 'The queried object does not exist') or
                                    stristr($respon, 'Status: AVAILABLE') or
                                    stristr($respon, 'DOMAIN NOT FOUND') or
                                    stristr($respon, 'Domain not found')

                                ) {
                                    array_push($this->domains_available, $product_array);
                            }else{
                                    $this->viewWhois[$domain.".".$tls] = $respon;

                                    //Asignarlo a renovación o transferencia
                                    if(getStatusSubscriptions($domain.".".$tls)){
                                        array_push($this->domains_reneval, $product_array);
                                    }else {
                                        switch(getPanelDomain($domain.".".$tls)){
                                            case 'no found':
                                                array_push($this->domains_transferable, $product_array);
                                                break;
                                            case 'vencido':
                                                array_push($this->domains_reneval, $product_array);
                                                break;
                                            case 'no vencido':
                                                array_push($this->domains_reneval, $product_array);
                                                break;

                                        }

                                    }
                                }
                            }else{
                                continue;
                            }

                        }


                    }

                endwhile;

            }else{
                echo __('No products found');
            }
            wp_reset_postdata();

        }

    }
    public function appointWhois_original($domain)
    {
        $this->domain = $domain;

        for ($i = 0; $i < count($this->extensions); $i++) {

//            $respon = $this->checkWhois($domain, $this->servers[$i][$this->extensions[$i]][0], $this->extensions[$i]);
            $respon = $this->checkWhois($domain, $this->extensions[$i]);

            if($respon != false){
                if (stristr($respon, 'Status: free')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } elseif (stristr($respon, ' is free')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } elseif (stristr($respon, 'no match')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } elseif (stristr($respon, 'not found')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } elseif (strstr($respon, 'Available')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } elseif (stristr($respon, 'no existe')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } elseif (stristr($respon, 'nothing found')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } elseif (stristr($respon, 'Status: invalid')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } elseif (stristr($respon, 'No Data Found')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } elseif (stristr($respon, 'No Data Found >>>')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } elseif (stristr($respon, 'Neither object nor interpretation control keyword found')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } elseif (stristr($respon, 'does not exist: DOMAIN NOT FOUND')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } elseif (stristr($respon, 'is available! Choose a registrar from below to register')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                }elseif (stristr($respon, 'The queried object does not exist')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                }elseif (stristr($respon, 'Status: AVAILABLE')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                }elseif (stristr($respon, 'DOMAIN NOT FOUND')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                } else {
                    $this->viewWhois[$domain.".".$this->extensions[$i]] = $respon;

                    //Asignarlo a renovación o transferencia
                    if(getStatusSubscriptions($domain.".".$this->extensions[$i])){
                        array_push($this->domains_reneval, $this->extensions[$i]);
                    }else {
                        switch(getPanelDomain($domain.".".$this->extensions[$i])){
                            case 'no found':
                                array_push($this->domains_transferable, $this->extensions[$i]);
                                break;
                            case 'vencido':
                                array_push($this->domains_reneval, $this->extensions[$i]);
                                break;
                            case 'no vencido':
                                array_push($this->domains_reneval, $this->extensions[$i]);
                                break;

                        }

                    }
                }
            }else{
                continue;
            }
        }

    }

    public function getDomain()
    {

        return $this->domain;
    }

    public function checkWhois($domain, $extension, $server)
    {
        $domain = $domain . '.' . $extension;

        if($server == "whois.nic.ve"){

            $pagina_inicio = file_get_contents('http://whois.nic.ve/whois/domain/'.$domain, true);
            $pagina_inicio = preg_replace("[\n\n|\n\n\n|\r|\n\r]", "", $pagina_inicio);
            if(strpos($pagina_inicio, 'not found') != false){
                return 'not found';
            }else{

                return  substr(strip_tags($pagina_inicio), 936, strlen($pagina_inicio));

            }

        }


       if (($fp = fsockopen($server, 43, $errno, $errstr, 5)) != false) {
                
               /* fwrite($fp, "$domain\r\n");
                stream_set_timeout($fp, 2);

                $out = "";
                while (!feof($fp)) {
                    $out .= fgets($fp);
                }
                fclose($fp);*/
                fwrite($fp, "$domain\r\n");
                stream_set_timeout($fp, 2);
                $out = fread($fp, 2000);
                $info = stream_get_meta_data($fp);
                fclose($fp);

                if ($info['timed_out']) {
                    return false;
                } 
                
                 if($extension == 'coach'){
                return substr($out, 0, 500);
            }
            if($extension == 'eu'){
                return substr($out, 2050, -1);
            }

                return $out;
            }

        return false;

    }
    public function _checkWhois($domain, $extension)
    {
        $domain = $domain . '.' . $extension;

        $server = $this->servers[$extension][0];

        if($server == "whois.nic.ve"){

            $pagina_inicio = file_get_contents('http://whois.nic.ve/whois/domain/'.$domain, true);
            $pagina_inicio = preg_replace("[\n\n|\n\n\n|\r|\n\r]", "", $pagina_inicio);
            if(strpos($pagina_inicio, 'not found') != false){
                return 'not found';
            }else{

                return  substr(strip_tags($pagina_inicio), 936, strlen($pagina_inicio));

            }

        }


//if($extension == 'info' or $extension == 'org'){

        if(1==1){
            if (($fp = fsockopen($server, 43, $errno, $errstr, 5)) == false) {
                return false;
            }else{

                fwrite($fp, "$domain\r\n");
                stream_set_timeout($fp, 2);

                $out = "";
                while (!feof($fp)) {
                    $out .= fgets($fp);
                }
                fclose($fp);

            }
            
            return $out;
        }else{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,  $server);
            curl_setopt($ch, CURLOPT_PORT, 43);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $domain . "\r\n");
            $data = curl_exec($ch);

            if (curl_error($ch)) return false;

            curl_close($ch);
            if($server == 'whois.nic.ve'){
                return substr ( $data, 0, 1600 );
            }

            return $data;
        }



    }


    public function hasDomainTransferable()
    {

        if (sizeof($this->domains_transferable)>0)
            return true;

        return false;
    }

    public function hasDomainAvailable()
    {

        if (sizeof($this->domains_available)>0)
            return true;

        return false;
    }

    public function hasDomainReneval()
    {

        if (sizeof($this->domains_reneval)>0)
            return true;

        return false;
    }


    public function filterDomain($domain)
    {

        $domain = trim($domain);

        if (preg_match('/\./i', $domain)) {
            return false;
        } elseif (substr(strtolower($domain), 0, 7) == "http://") {
            return false;
        } elseif (substr(strtolower($domain), 0, 4) == "www.") {
            return false;
        }

        return true;
    }


    public function listDomainAvailable()
    {
        global $woocommerce;
        $domains_available = '';

        if ($this->hasDomainAvailable()) {

            $domains_available .= '
            <table>
                <tr>
                    <th>' . __("Nombre de Dominio") . '</th>
                    <th>' . __("Período") . '</th>
                    <th>' . __("Precio por Período (" . get_woocommerce_currency_symbol() . ")") . '</th>
                    <th>' . __("Descuento 1er Año (" . get_woocommerce_currency_symbol() . ")") . '</th>
                    <th>' . __("Precio por Período con Descuento 1er Año (" . get_woocommerce_currency_symbol() . ")") . '</th>
                    <th>' . __("Registrar") . '</th>';
            $domains_available .= (THSession::hasSession('options_service_input') and (THSession::getSession('options_service_input') == 'dyh' or THSession::getSession('options_service_input') == 'dys')) ? '<th>' . __("Dom. Principal") . '</th>' : '';
            $domains_available .= '</tr>';

            for ($i = 0; $i<count($this->domains_available); $i++){

                $pid            = $this->domains_available[$i]['id_product'];
                $service_name   = $this->domains_available[$i]['product_name'];
                $variations     = $this->domains_available[$i]['variations'];
                $extension_extr = $this->domains_available[$i]['domain_tls'];

                //precios
                $service_price      = $variations[0]['display_price'];
                $service_price_html = $variations[0]['price_html'];

                //descuento
                $coupon                 = get_post_meta($pid, 'registro_costo_' . get_woocommerce_currency(), true);
                $price_discount_html    = wc_price($coupon);
                $price_discount         = $coupon;

                $data_price_cupon_total = array();

                foreach ($variations as $variation_ => $value) {
                    if($price_discount == ""){
                        $price_discount = 0;
                    }
                    $data_price_cupon_total[$variation_] = array('variation_id' => $value['variation_id'],
                                'price_html' => wc_price($value['display_price'] - $price_discount),
                                'price_html_plane' => $value['display_price'] - $price_discount
                            );
                }

                $price_total = wc_price($service_price - $price_discount);
                $price_total_plano = $service_price - $price_discount;

                if ($this->helpName) {
                    $exten_available = $this->extensioHelpName;
                }else{
                    $exten_available = $extension_extr;
                }

                if ($exten_available == $extension_extr) {

                    $domain_complete = (!$this->helpName) ? $this->getDomain() . '.' . $extension_extr : $this->domains_available[$i]['domain'].'.'.$extension_extr;


                    //Si existe este dominio en el carrito
                    if (check_domain_cart($domain_complete)) {
                        $domains_available .= '<tr class="disabled-service">';
                        $disabled           = 'disabled';
                        $addcart            = '<p>Este servicio ya se encuentra en el carrito de compras. <a target="_blank" href="/carrito">Ver carrito</a></p>';

                    }else{
                        $domains_available .= '<tr>';
                        $disabled           = '';
                        $addcart            = '';
                    }

                    $domains_available .= '<td class="domain available"><b>' . $domain_complete . '</b>' . $addcart . '</td>';
                    $domains_available .= '<td class="domain available">
                                                <select class="custom-select period_year" data-type="avail" name="interval" data-extention="' . $exten_available . '">
                                                  ' . woo_display_variation_dropdown_on_shop_page($pid, "periodicidad", 5) . '
                                                </select>
                                           </td>';
                    if (!empty($price_discount)) {
                        $price_tmp      = $price_coupon_ = $service_price - $price_discount;
                        $price_coupon_  = $price_discount_html;
                    }else{
                        $price_tmp      = $service_price;
                        $price_coupon_  = '';
                    }

                    $domains_available .= "<td class='domain available period' data-variations='" . json_encode($variations) . "' data-price='" . $price_tmp . "'  data-domain='" . $domain_complete . "' id='" . $pid . "'>" . $service_price_html . "</td>";

                    $domains_available .= "<td class='domain available coupon' data-coupon='" . $price_discount . "'>" . $price_coupon_ . "</td>";
                    $domains_available .= "<td class='domain available period_total' data-price='" . $price_total_plano . "' data-price-cupon-total='" . json_encode($data_price_cupon_total) . "'>
                                                  " . $price_total . "
                                           </td>";
                    $domains_available .= '<td class="domain available">
                                                <label class="checkbox">
                                                     <input data-price="'.$price_total_plano.'" ' . $disabled . ' class="period" type="checkbox" name="domain-register[]" id="domain-register[]" value="' . $pid . '" data-service="' . $service_name . '" data-helper=' . $domain_complete . '>                                              
                                                     <span class="check"></span>
                                                 </label>
                                           </td>';
                    if (THSession::hasSession('options_service_input') and (THSession::getSession('options_service_input') == 'dyh' or THSession::getSession('options_service_input') == 'dys')) {
                        $domains_available .= '<td class="domain available">
                                                        <label class="radio">
                                                            <input data-price="'.$price_total_plano.'" ' . $disabled . ' type="radio" class="period_radio"  name="domain-primary"  value="' . $domain_complete . '"   data-id=' . $pid . '>                                              
                                                            <span class="check"></span>
                                                        </label>
                                                 </td>';
                    }

                    $domains_available .= '</tr>';

                }
            }
                $domains_available .= '</table>';

        }else{
            return null;
        }

        return $domains_available;

    }

    public function extractExtension($name)
    {


        $pos = strpos($name, '.');

        if (!$pos)
            return false;

        $length_name_domain = strlen($name);

        $extension = substr($name, $pos + 1, $length_name_domain);

        return $extension;
    }
    public function extractDomain($name)
    {


        $pos = strpos($name, '.');

        if (!$pos)
            return false;

        $length_name_domain = strlen($name);

        $domain = substr($name, 0 , $pos);

        return $domain;
    }

    public function listDomainTransferable()
    {


        if ($this->hasDomainTransferable()) {

            $data_whois_container = '';

            if (THSession::hasSession('options_service_input') and (THSession::getSession('options_service_input') == 'dyh' or THSession::getSession('options_service_input') == 'dys')) {
                $domain_primary = '<th>' . __("Dom. Principal") . '</th>';
            } else {
                $domain_primary = '';
            }

            $domains_transferable = '
            <table>
                <tr>
                    <th>' . __("Nombre de Dominio") . '</th>
                    <th>' . __("Datos de Registro") . '</th>
                    <th>' . __("Período") . '</th>
                    <th>' . __("Precio por Período (" . get_woocommerce_currency() . ")") . '</th>
                    <th>' . __("Tarifa Transferencia (" . get_woocommerce_currency() . ")") . '</th>
                    <th>' . __("Precio con Tarifa de Transferencia para 1er Año (" . get_woocommerce_currency() . ")") . '</th>
                    <th>' . __("Transferir") . '</th>
                    ' . $domain_primary . '
                </tr>';


            for ($i = 0; $i<count($this->domains_transferable); $i++){

                $pid            = $this->domains_transferable[$i]['id_product'];
                $service_name   = $this->domains_transferable[$i]['product_name'];
                $variations     = $this->domains_transferable[$i]['variations'];
                $extension_extr = $this->domains_transferable[$i]['domain_tls'];
                $variations     = $this->domains_transferable[$i]['variations'];
                $extension_extr = $this->domains_transferable[$i]['domain_tls'];
                $limit_periodo  = $this->domains_transferable[$i]['limit_transferencia'];

                //precios
                $service_price      = $variations[0]['display_price'];
                $service_price_html = $variations[0]['price_html'];


                //tarifa transferencia

                $costo_transferencia = (empty(get_post_meta($pid, 'transferencia_costo_' . get_woocommerce_currency(), true))) ? '' : get_post_meta($pid, 'transferencia_costo_' . get_woocommerce_currency(), true);
                if($costo_transferencia == ""){
                    $costo_transferencia = 0;
                }
                $costo_transferencia_html = (empty($costo_transferencia)) ? '' : wc_price($costo_transferencia);


                $data_tarifa_total = array();

                foreach ($variations as $variation_ => $value) {

                    $data_tarifa_total[$variation_] = array('variation_id' => $value['variation_id'],
                        'price_html' => wc_price($value['display_price'] + $costo_transferencia),
                        'price_html_plane' => $value['display_price'] + $costo_transferencia
                    );
                }

                $costo_total_html = wc_price($service_price + $costo_transferencia);
                $costo_total = $service_price + $costo_transferencia;

                $service_price = $costo_total;



                if ($this->helpName){
                    $exten_trans = $this->extensioHelpName;
                }else{
                    $exten_trans = $extension_extr;
                }

                if ($exten_trans == $extension_extr) {

                    $domain_complete = (!$this->helpName) ? $this->getDomain() . '.' . $extension_extr : $this->domains_transferable[$i]['domain'].'.'.$extension_extr;

                    $data_whois = $this->viewWhois["$domain_complete"];

                    $data_whois_container .= '<div id="popup-t-' . $i . '"  class="popup-whois" style="display: none;">
                                                   <div class="content-popup">
                                                       <div class="close"><a href="#" class="close-whois"><i class="fa fa-times"></i></a></div>
                                                       <div>
                                                           <pre class="preview">' . $data_whois . '</pre>
                                                       </div>
                                                   </div>
                                              </div>';

                    //Si existe este dominio en el carrito
                    if (check_domain_cart($domain_complete)) {
                        $domains_transferable  .= '<tr class="disabled-service">';
                        $disabled               = 'disabled';
                        $addcart                = '<p>Este servicio ya se encuentra en el carrito de compras. <a href="/carrito">Ver carrito</a></p>';
                    }else{
                        $domains_transferable  .= '<tr>';
                        $disabled               = '';
                        $addcart                = '';
                    }

                    $domains_transferable .= '<td class="domain transferable"><b>' . $domain_complete . '</b>' . $addcart . '</td>';
                    $domains_transferable .= '<td class="domain transferable"><i  data-id="popup-t-' . $i . '" class="open fa fa-search"></i></td>';
                    $domains_transferable .= '<td class="domain transferable">
                                                    <select name="interval" class="custom-select period_year" data-type="transf" data-extencion="' . $exten_trans . '">
                                                           ' . woo_display_variation_dropdown_on_shop_page($pid, "periodicidad", $limit_periodo) . '
                                                    </select>                                              
                                               </td>';
                    $domains_transferable .= "<td class='domain transferable period' data-variations='" . json_encode($variations) . "' data-price='" . $service_price . "' data-domain='" . $domain_complete . "' id='" . $pid . "'>" . $service_price_html . "</td>";
                    $domains_transferable .= "<td class='domain transferable tarifa' data-tarifa='" . $costo_transferencia . "' >" . $costo_transferencia_html . "</td>";
                    $domains_transferable .= "<td class='domain transferable tarifa_total' data-tarifa-total='" . json_encode($data_tarifa_total) . "' data-price='" . $costo_total . "'>" . $costo_total_html . "</td>";

                    $domains_transferable .= '<td class="domain transferable">
                                                    <label class="checkbox">
                                                         <input data-price="'.$costo_total.'" ' . $disabled . ' type="checkbox" class="period" name="domain-register[]" id="domain-register[]" value="' . $pid . '" data-service="' . $service_name . '" data-helper=' . $domain_complete . '>                                              
                                                         <span class="check"></span>
                                                    </label>
                                              </td>';
                    if (THSession::hasSession('options_service_input') and (THSession::getSession('options_service_input') == 'dyh' or THSession::getSession('options_service_input') == 'dys')) {
                        $domains_transferable .= '<td class="domain transferable">
                                                            <label class="radio">
                                                                 <input data-price="'.$costo_total.'" ' . $disabled . ' type="radio" class="period_radio"  name="domain-primary"  value="' . $domain_complete . '" data-id=' . $pid . ' >                                              
                                                                 <span class="check"></span>
                                                            </label>
                                                        </td>';
                    }

                    $domains_transferable .= '</tr>';


                }

            }
                $domains_transferable .= '</table>' . $data_whois_container;;

        }else{
            return null;
        }

        return $domains_transferable;

    }

    public function listDomainReneval()
    {


        if ($this->hasDomainReneval()) {


            $data_whois_container = '';

            $domains_transferable = '
            <table>
                <tr>
                    <th>' . __("Nombre de Dominio") . '</th>
                    <th>' . __("Datos de Registro") . '</th>
                    <th>' . __("Período") . '</th>
                    <th>' . __("Precio (" . get_woocommerce_currency() . ")") . '</th>
                    <th>' . __("Renovar") . '</th>
                </tr>';


            for ($i = 0; $i<count($this->domains_reneval); $i++){

                $pid            = $this->domains_reneval[$i]['id_product'];
                $service_name   = $this->domains_reneval[$i]['product_name'];
                $variations     = $this->domains_reneval[$i]['variations'];
                $extension_extr = $this->domains_reneval[$i]['domain_tls'];
                $variations     = $this->domains_reneval[$i]['variations'];



                //precios
                $service_price      = $variations[0]['display_price'];
                $service_price_html = $variations[0]['price_html'];



                if ($this->helpName) {
                    $exten_trans = $this->extensioHelpName;
                }else{
                    $exten_trans = $extension_extr;
                }

                if ($exten_trans == $extension_extr) {

                    $domain_complete = (!$this->helpName) ? $this->getDomain() . '.' . $extension_extr : $this->domains_reneval[$i]['domain'].'.'.$extension_extr;

                    $data_whois = $this->viewWhois["$domain_complete"];


                    $data_whois_container .= '<div id="popup-r-' . $i . '"  class="popup-whois" style="display: none;">
                                                                <div class="content-popup">
                                                                    <div class="close"><a href="#" class="close-whois"><i class="fa fa-times"></i></a></div>
                                                                    <div>
                                                                        <pre>' . $data_whois . '</pre>
                                                                    </div>
                                                                </div>
                                              </div>';


                    //Check estado de la suscripción
                    $message_status = null;
                    switch (getStatusSubscriptions($domain_complete)) {

                        case 'pending':
                            $message_status = "<p>Usted ya tiene este dominio en un pedido previo. 
                                       Por favor proceda al pago en el <a target='_blank' href='/mi-cuenta/my-subscriptions/'>panel</a> del Cliente.</p>";
                            break;
                        case 'completed':
                        case 'active':
                            $message_status = "<p>Usted ya tiene registrado este dominio. Para renovarlo, por favor ingrese en el
                                                        <a target='_blank' href='/mi-cuenta/my-subscriptions/'>panel</a> del Cliente.</p>";
                            break;
                        case 'cancelled':
                            $message_status = "<p>Su dominio no puede ser renovado en este momento porque se venció y es 
                                        posible que no se pueda recuperar. Por favor cree un <a target='_blank' href='https://csc.tecnosoluciones.com/'>Ticket</a> para solicitar más información.</p>";
                            break;
                    }
                     //Check si esta vencido
                    if (getPanelDomain($domain_complete) == 'vencido')
                        $message_status = "<p>Su dominio no puede ser renovado en este momento porque se venció y es posible que 
                                            no se pueda recuperar. Por favor cree un <a target='_blank' href='https://csc.tecnosoluciones.com/'>Ticket</a> para solicitar más información.</p>";


                    //Si existe este dominio en el carrito
                    if (check_domain_cart($domain_complete)) {
                        $domains_transferable .= '<tr class="disabled-service">';
                        $disabled = 'disabled';
                        $addcart = '<p>Este servicio ya se encuentra en el carrito de compras. <a href="/carrito">Ver carrito</a></p>';

                    }elseif($message_status != null) {
                        $domains_transferable .= '<tr class="disabled-service">';
                        $disabled = 'disabled';
                        $addcart = $message_status;
                    }else{
                        $domains_transferable .= '<tr>';
                        $disabled = '';
                        $addcart = '';
                    }

                    $domains_transferable .= '<td class="domain transferable"><b>' . $domain_complete . '</b>' . $addcart . '</td>';
                    $domains_transferable .= '<td class="domain transferable"><i  data-id="popup-r-' . $i . '" class="open fa fa-search"></i></td>';
                    $domains_transferable .= '<td class="domain transferable">
                                                          <select name="interval" class="custom-select period_year" data-type="reno" data-extencion="' . $exten_trans . '">
                                                           ' . woo_display_variation_dropdown_on_shop_page($pid, "periodicidad", 5) . '
                                                          </select>                                              
                                                        </td>';
                    $domains_transferable .= "<td class='domain transferable period' data-variations='" . json_encode($variations) . "' data-price='" . $service_price . "' data-domain='" . $domain_complete . "' id='" . $pid . "'>" . $service_price_html . "</td>";
                    $domains_transferable .= '<td class="domain transferable">
                                                            <label class="checkbox">
                                                                <input data-price="'.$service_price.'" ' . $disabled . ' type="checkbox" class="period" name="domain-register[]" id="domain-register[]" value="' . $pid . '" data-service="' . $service_name . '" data-helper=' . $domain_complete . '>                                              
                                                                <span class="check"></span>
                                                             </label>
                                                        </td>';
                    if (THSession::hasSession('options_service_input') and (THSession::getSession('options_service_input') == 'dyh' or THSession::getSession('options_service_input') == 'dys')) {
                        $domains_transferable .= '<td class="domain transferable">
                                                            <label class="radio">
                                                                 <input data-price="'.$service_price.'" ' . $disabled . ' type="radio" class="period_radio"  name="domain-primary"  value="' . $domain_complete . '" data-id=' . $pid . ' >                                              
                                                                 <span class="check"></span>
                                                            </label>
                                                        </td>';
                    }

                    $domains_transferable .= '</tr>';


                }


            }
                        $domains_transferable .= '</table>' . $data_whois_container;;

        }else{
            return null;
        }

        return $domains_transferable;

    }


    /**
     * Ayudante de nombres de dominio
     */
    public function helperByName_($company, $keyword1, $keyword2, $extension){


        // remove whitespace from either side of each variable
        $company    = trim($company);
        $keyword1   = trim($keyword1);
        $keyword2   = trim($keyword2);
        $extension  = trim($extension);

        $this->helpName = true;

        $this->extensioHelpName = $extension;

        $cdomains = array(
            $company,
            $company . $keyword1,
            $company . "-" . $keyword1,
            $keyword1 . $company,
            $keyword1 . "-" . $company,
            $company . $keyword2,
            $company . "-" . $keyword2,
            $keyword2 . $company,
            $keyword2 . "-" . $company,
            $keyword1,
            $keyword2,
            $keyword1 . $keyword2,
            $keyword2 . $keyword1,
            $keyword1 . "-" . $keyword2,
            $keyword2 . "-" . $keyword1
        );

        // remove any duplicates :)
        $domains = array_unique($cdomains);


        for ($i = 0; $i < count($domains); $i++) {

            $domain = strtolower($domains[$i]);
            $server = $this->extracSeverWhois($extension);
            $this->domain[$i] = $domain;

            $respon = $this->checkWhois($domain, $extension, $server);

            if($respon!=false){
                if (stristr($respon, 'Status: free')) {
                    array_push($this->domains_available, $domain.'.'.$extension);
                } elseif (stristr($respon, ' is free')) {
                    array_push($this->domains_available, $domain.'.'.$extension);
                } elseif (stristr($respon, 'no match')) {
                    array_push($this->domains_available, $domain.'.'.$extension);
                } elseif (stristr($respon, 'not found')) {
                    array_push($this->domains_available, $domain.'.'.$extension);
                } elseif (strstr($respon, 'Available')) {
                    array_push($this->domains_available, $domain.'.'.$extension);
                } elseif (stristr($respon, 'no existe')) {
                    array_push($this->domains_available, $domain.'.'.$extension);
                } elseif (stristr($respon, 'nothing found')) {
                    array_push($this->domains_available, $domain.'.'.$extension);
                } elseif (stristr($respon, 'Status: invalid')) {
                    array_push($this->domains_available, $domain.'.'.$extension);
                } elseif (stristr($respon, 'No Data Found')) {
                    array_push($this->domains_available, $domain.'.'.$extension);
                } elseif (stristr($respon, 'Neither object nor interpretation control keyword found')) {
                    array_push($this->domains_available, $domain.'.'.$extension);
                }elseif (stristr($respon, 'does not exist')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                }elseif (stristr($respon, 'is available! Choose a registrar from below to register')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                }elseif (stristr($respon, 'The queried object does not exist')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                }elseif (stristr($respon, 'Status: AVAILABLE')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                }elseif (stristr($respon, 'DOMAIN NOT FOUND')) {
                    array_push($this->domains_available, $this->extensions[$i]);
                }else{
                    $this->viewWhois[$domain.".".$extension] = $respon;
                    array_push($this->domains_transferable, $domain.'.'.$extension);
                }
            }else{
                continue;
            }

        }

    }


    public function helperByName($company, $keyword1, $keyword2, $extension)
    {
        // remove whitespace from either side of each variable
        $company    = trim($company);
        $keyword1   = trim($keyword1);
        $keyword2   = trim($keyword2);
        $extension  = trim($extension);

        $this->helpName = true;

        $this->extensioHelpName = $extension;

        $cdomains = array(
            $company,
            $company . $keyword1,
            $company . "-" . $keyword1,
            $keyword1 . $company,
            $keyword1 . "-" . $company,
            $company . $keyword2,
            $company . "-" . $keyword2,
            $keyword2 . $company,
            $keyword2 . "-" . $company,
            $keyword1,
            $keyword2,
            $keyword1 . $keyword2,
            $keyword2 . $keyword1,
            $keyword1 . "-" . $keyword2,
            $keyword2 . "-" . $keyword1
        );

        // remove any duplicates :)
        $domains = array_unique($cdomains);


        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            // Put your plugin code here
            $args = array(
                'post_type' => 'product',
                'product_cat' => 'Dominios',
                'posts_per_page' => -1,
                'order_by' => 'title',
                'order' => 'ASC',
            );

            $loop = new WP_Query($args);

            if ($loop->have_posts()) {

                while ($loop->have_posts()) : $loop->the_post();

                    $pid = get_the_ID();

                    $product = wc_get_product($pid);

                    //Verifica si el producto es variable
                    if ( $product->is_type( 'variable' ) ) {

                        $pid = get_the_ID();
                        $server = get_post_meta($pid,'server_domain', true);
                        $tls = get_post_meta($pid,'domain_tls', true);
                        $variations = $product->get_available_variations();


                        //Verificar sólo los que tengan el server y tls definidos
                        if(!empty($tls) or !empty($server)) {
                            if($tls == $extension){
                                for ($i = 0; $i < count($domains); $i++) {

                                    $domain = strtolower($domains[$i]);
                                    $this->domain[$i] = $domain;

                                    $product_array = array(
                                        'id_product'                    => $pid,
                                        'product_name'                  => $product->get_name(),
                                        'is_variable'                   => $product->is_type( 'variable' ),
                                        'variations'                    => $variations,
                                        'product_price_variation'       => $variations[0]['display_price'],//$product->price
                                        'product_price_variation_html'  => $variations[0]['price_html'],//$product->get_price_html()
                                        'registro_costo'                => get_post_meta($pid,'registro_costo_'.get_woocommerce_currency(), true),
                                        'domain_tls'                    => $tls,
                                        'server_domain'                 => $server,
                                        'domain'                        => $domain,
                                    );

                                    //Verificar WHOIS
                                    $respon = $this->checkWhois($domain, $tls, $server);


                            //Si el servidor whois no responde false, verifica todo lo demás
                            if ($respon != false) {

                                if (stristr($respon, 'Status: free') or
                                    stristr($respon, 'is free') or
                                    stristr($respon, 'no match') or
                                    stristr($respon, 'not found') or
                                    stristr($respon, 'Available') or
                                    stristr($respon, 'nothing found') or
                                    stristr($respon, 'Status: invalid') or
                                    stristr($respon, 'No Data Found') or
                                    stristr($respon, 'No Data Found >>>') or
                                    stristr($respon, 'Neither object nor interpretation control keyword found') or
                                    stristr($respon, 'does not exist: DOMAIN NOT FOUND') or
                                    stristr($respon, 'is available! Choose a registrar from below to register') or
                                    stristr($respon, 'The queried object does not exist') or
                                    stristr($respon, 'Status: AVAILABLE') or
                                    stristr($respon, 'DOMAIN NOT FOUND')
                                ) {
                                    array_push($this->domains_available, $product_array);
                                } else {
                                    $this->viewWhois[$domain . "." . $tls] = $respon;

                                    //Asignarlo a renovación o transferencia
                                    if (getStatusSubscriptions($domain . "." . $tls)) {
                                        array_push($this->domains_reneval, $product_array);
                                    } else {
                                        switch (getPanelDomain($domain . "." . $tls)) {
                                            case 'no found':
                                                array_push($this->domains_transferable, $product_array);
                                                break;
                                            case 'vencido':
                                                array_push($this->domains_reneval, $product_array);
                                                break;
                                            case 'no vencido':
                                                array_push($this->domains_reneval, $product_array);
                                                break;

                                        }

                                    }
                                }
                            } else {
                                continue;
                            }
                            }
                        }
                        }


                    }

                endwhile;

            }else{
                echo __('No products found');
            }
            wp_reset_postdata();

        }

    }

    public function extracSeverWhois($extension){

        for ($i = 0; $i < count($this->servers); $i++) {
            if(isset($this->servers[$extension]))
                return $this->servers[$extension][0];
        }
        return false;
    }

    public function viewWhois($domain){

        $extension = $this->extractExtension($domain);
        $server = $this->extracSeverWhois($extension);
        $domain = $this->extractDomain($domain);

        $respon = $this->checkWhois($domain, $extension, $server);

        if($respon != false) {

            if (stristr($respon, 'Status: free') or
                stristr($respon, 'is free') or
                stristr($respon, 'no match') or
                stristr($respon, 'not found') or
                stristr($respon, 'Available') or
                stristr($respon, 'nothing found') or
                stristr($respon, 'Status: invalid') or
                stristr($respon, 'No Data Found') or
                stristr($respon, 'No Data Found >>>') or
                stristr($respon, 'Neither object nor interpretation control keyword found') or
                stristr($respon, 'does not exist: DOMAIN NOT FOUND') or
                stristr($respon, 'is available! Choose a registrar from below to register') or
                stristr($respon, 'The queried object does not exist') or
                stristr($respon, 'Status: AVAILABLE') or
                stristr($respon, 'DOMAIN NOT FOUND')
            ) {
                return false;
            } else {
                return $respon;
            }
        }


    }


    public function checkPurchaseType($domain, $tls = 'net.co', $server = 'whois.nic.co')
    {

        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

            $domain = $this->extractDomain($domain);
            if (!empty($tls) or !empty($server)) {
                //Verificar WHOIS
                $respon = $this->checkWhois($domain, $tls, $server);

                //Si el servidor whois no responde false, verifica todo lo demás
                if ($respon != false) {

                    if (stristr($respon, 'Status: free') or
                        stristr($respon, 'is free') or
                        stristr($respon, 'no match') or
                        stristr($respon, 'not found') or
                        stristr($respon, 'Available') or
                        stristr($respon, 'nothing found') or
                        stristr($respon, 'Status: invalid') or
                        stristr($respon, 'No Data Found') or
                        stristr($respon, 'No Data Found >>>') or
                        stristr($respon, 'Neither object nor interpretation control keyword found') or
                        stristr($respon, 'does not exist: DOMAIN NOT FOUND') or
                        stristr($respon, 'is available! Choose a registrar from below to register') or
                        stristr($respon, 'The queried object does not exist') or
                        stristr($respon, 'Status: AVAILABLE') or
                        stristr($respon, 'DOMAIN NOT FOUND')
                    ) {
                        return 'registrar';
                    } else {

                        //Asignarlo a renovación o transferencia
                        if (getStatusSubscriptions($domain, $tls)) {
                            return 'renovar';
                        } else {
                            switch (getPanelDomain($domain, $tls)) {
                                case 'no found':
                                    return 'transferir';
                                    break;
                                case 'vencido':
                                    return 'renovar';
                                    break;
                                case 'no vencido':
                                    return 'renovar';
                                    break;

                            }

                        }
                    }
                }

            }

        }


    }


}

$TWhois= new TWhois();


