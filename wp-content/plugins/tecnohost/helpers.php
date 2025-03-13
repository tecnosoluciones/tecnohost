<?php

function domains_help(){
    global $TWhois,$THosting;

    if(isset($_POST["options_service_input"])){
        $arg = [
            array('name'=>'options_service_input','value'=>$_POST["options_service_input"]),
            array('name'=>'organi','value'=>trim($_POST["organi"])),
            array('name'=>'domain_ex','value'=>trim($_POST["domain_ex"])),
            array('name'=>'keyword_one','value'=>trim($_POST["keyword_one"])),
            array('name'=>'keyword_two','value'=>trim($_POST["keyword_two"]))
        ];
        THSession::create($arg);
    }


    $extension_domain = setOption('domain_ex');
    $organi = setOption('organi');
    $keyword_one = setOption('keyword_one');
    $keyword_two = setOption('keyword_two');


    $TWhois->helperByName($organi,$keyword_one,$keyword_two,$extension_domain);
    $current = 'Ayudante de Nombres';


    breadcrumbs($current);

    echo '<div class="container-tecnohost">
                <div class="description">
                    <h2>Selección de Dominios</h2>
                    <p>Por favor marque la casilla <b>"Registrar"</b> en la pestaña <b>"Registro"</b> para los <b>nombres de dominio</b> que
                        desea adquirir y/o marque la casilla "Transferir" en la pestaña <b>"Transferencia"</b> para los <b>nombres de
                            dominio</b> que desea <b>transferir</b>. A su vez, seleccione el período de tiempo por el cual desea
                        registrar/transferir cada uno de dichos nombres de dominio. Finalmente, haga clic en el botón de compra para <b>"Agregar a la Orden"</b>.
                        Si desea conocer los dominios que ya están registrados así como la información de quienes los registraron,
                        seleccione la pestaña <b>"Transferencia"</b> y luego haga clic en el ícono de la lupa al lado de cada <b>nombre de
                            dominio</b>.
                    </p>
                </div>
           </div>
    <ul class="tabs">
        <li class="active"><a href="#availables">Registro de Dominio ('.count($TWhois->domains_available).')</a></li>
        <li ><a  href="#renevales">Renovación de Dominio ('.count($TWhois->domains_reneval).')</a></li>
        <li ><a  href="#transferables">Transferencia de Dominio ('.count($TWhois->domains_transferable).')</a></li>
    </ul>

    <div class="tab_container container-tecnohost">
        <form name="domains"  method="post">

            <div id="availables" class="tab_content">';

    if($TWhois->listDomainAvailable()==null){
        echo "<div class=\"error-domain\">
                                        <p>¡UPPS! No encontramos dominios para registrar. Por favor, verifique los dominios a transferir.</p>
                                      </div>";

    }else{
        echo $TWhois->listDomainAvailable();
    }

    echo '</div> 
                       <div style="display:none;" id="renevales" class="tab_content">';

    if($TWhois->listDomainReneval()==null){
        echo "<div class=\"error-domain\">
                                        <p>¡UPPS! No encontramos dominios para renovar. Por favor, verifique los dominios disponibles.</p>
                                      </div>";

    }else{
//            $html.="<div id='ajax-list-domain-transferable'></div>";//$TWhois->listDomainTransferable();
        echo $TWhois->listDomainReneval();
    }
    echo '</div>
            <div id="transferables" class="tab_content" style="display: none;">';

    if($TWhois->listDomainTransferable()==null){
        echo "<div class=\"error-domain\">
                                        <p>¡UPPS! No encontramos dominios para transferir. Por favor, verifique los dominios disponibles.</p>
                                      </div>";

    }else{
        echo $TWhois->listDomainTransferable();
    }

    echo   '</div>
        </form>
    </div>'.totalOrder();
    echo '<div class="container-add-order">
        <button id="add-order" class="tecnohost-add-order">Agregar a la Orden</button>

            <!--               <a href="javascript:history.back()">Regresar a la opción anterior</a>-->
            <a href="/contratacion/?category=ayudante">Regresar a la opción anterior</a>
        </div>';

    echo  tecnoFAQ(16);

}

function domains($mode = 'default'){

        global $TWhois,$THosting;
    $packeges = $THosting->ListHosting();


    if( !THSession::hasSession('whois_domain')){

        $arg = [
            array('name'=>'whois_domain','value'=>$_POST['whois_domain']),
            array('name'=>'options_service_input','value'=>trim($_POST["options_service_input"])),
            array('name'=>'mode_domain','value'=>trim($_POST["mode_domain"]))
        ];
        THSession::create($arg);
    }
    if (THSession::hasSession('whois_domain') and isset($_POST['whois_domain'])){
        $arg = [
            array('name'=>'whois_domain','value'=>$_POST['whois_domain']),
            array('name'=>'options_service_input','value'=>trim($_POST["options_service_input"])),
            array('name'=>'mode_domain','value'=>trim($_POST["mode_domain"]))
        ];
        THSession::create($arg);
    }

//Para los combos
    if( isset($_POST['options_service_input']) and $_POST['options_service_input']=='dyh'){
        $arg = [
            array('name'=>'options_service_input','value'=>trim($_POST["options_service_input"])),
            array('name'=>'step_combo','value'=>1)
        ];
        THSession::create($arg);
    }


    $whois_domain = setOption('whois_domain');

    if($TWhois->filterDomain($whois_domain) or $mode=='combo_ser'){


        $TWhois->appointWhois($whois_domain);
        $current = ($mode=='combo')? 'Combo de Hospedaje + Dominio' : 'Selección de Dominios';


        $mode_domain = setOption('mode_domain');

        if(isset($_POST['mode_domain']) or THSession::hasSession('mode_domain')){

            if($mode_domain=='t'){
                $url= 'transferencia';
                $current = 'Transferencia de Dominio';

                $active_t = 'active';
                $active_r = '';

                $tab_content_r = 'style="display:none;"';
                $tab_content_t = 'style="display:block;"';
            }elseif ($mode_domain=='r'){
                $url= 'registro';
                $current = 'Registro de Dominio';
                $active_r = 'active';
                $active_t = '';

                $tab_content_t = 'style="display:none;"';
                $tab_content_r = 'style="display:block;"';
            }elseif ($mode_domain=='c'){
                $url= 'combo';
                $current = 'Combo de Hospedaje + Dominio';
                $active_r = 'active';
                $active_t = '';

                $tab_content_t = 'style="display:none;"';
                $tab_content_r = 'style="display:block;"';
            }else{
                $url= 'combo';
                $active_r = 'active';
                $active_t = '';

                $tab_content_r = 'style="display:block;"';
                $tab_content_t = 'style="display:none;"';
            }

        }else{
            $url= 'combo';
            $active_r = 'active';
            $active_t = '';

            $tab_content_t = 'style="display:none;"';
            $tab_content_r = 'style="display:block;"';
        }


        //breadcrumbs($current);

        $html = breadcrumbs($current).'<div class="button loading plan-hosting-loader"></div><div class="container-tecnohost">
                  <div class="description">
                        <h2>Selección de Dominios</h2>
                        <p>Por favor marque la casilla <b>"Registrar"</b> en la pestaña <b>"Registro"</b> para los <b>nombres de dominio</b> que
                            desea adquirir y/o marque la casilla "Transferir" en la pestaña <b>"Transferencia"</b> para los <b>nombres de
                            dominio</b> que desea <b>transferir</b>. A su vez, seleccione el período de tiempo por el cual desea
                            registrar/transferir cada uno de dichos nombres de dominio';
        if($mode =='combo'){ $html.='y en cualquiera de las dos pestañas seleccione el "Dominio Principal"'; }
        $html.='.Finalmente, haga clic en el botón de compra para <b>"Agregar a la Orden"</b>.
                            Si desea conocer los dominios que ya están registrados así como la información de quienes los registraron,
                            seleccione la pestaña <b>"Transferencia"</b> y luego haga clic en el ícono de la lupa al lado de cada <b>nombre de
                            dominio</b>.
                        </p>
                  </div>
            </div>

        <ul class="tabs">
            <li class="'.$active_r.'"><a href="#availables">Registro de Dominio ('.count($TWhois->domains_available).')</a></li>
            <li><a  href="#renevales">Renovación de Dominio ('.count($TWhois->domains_reneval).')</a></li>
            <li class="'.$active_t.'"><a  href="#transferables">Transferencia de Dominio ('.count($TWhois->domains_transferable).')</a></li>
        </ul>

            <div class="tab_container container-tecnohost">
                <form name="domains"  method="post">';
        if ($mode =='combo') {
            $html.='<input type="hidden" name="options_service_input" id="options_service_input"  value="h">
                        <input type="hidden" name="whois_domain" id="whois_domain" >
                        <input type="hidden" name="mode_hosting" value="1" >';
        }
        if ($mode =='combo_ser') {
            if(THSession::getSession('type_other_service')==2){

                $html.='<input type="hidden" name="whois_domain_d" id="whois_domain_d" value="'.THSession::getSession('whois_domain_d').'">';
                $type_other_service = 'redirect';
            }else if (THSession::getSession('type_other_service')==1){
                $type_other_service = 'ssl';
            }else{
                $type_other_service = 'ssl';
            }

            $html.='<input type="hidden" name="options_service_input" id="options_service_input"  value="'.$type_other_service.'">
                        <input type="hidden" name="whois_domain" id="whois_domain" >
                        <input type="hidden" name="whois_domain_o" id="whois_domain_o" >';
        }
        $html.='<div id="availables" class="tab_content" '.$tab_content_r.'>';

        if($TWhois->listDomainAvailable()==null){
            $html.="<div class=\"error-domain\">
                                        <p>¡UPPS! No encontramos dominios para registrar. Por favor, verifique los dominios a transferir.</p>
                                      </div>";

        }else{
//            $html.="<div id='ajax-list-domain-avaible'></div>";//$TWhois->listDomainAvailable();
            $html.=$TWhois->listDomainAvailable();
        }

        $html.='</div> 
                       <div style="display:none;" id="renevales" class="tab_content" '.$tab_content_t.'>';

        if($TWhois->listDomainReneval()==null){
            $html.="<div class=\"error-domain\">
                                        <p>¡UPPS! No encontramos dominios para renovar. Por favor, verifique los dominios disponibles.</p>
                                      </div>";

        }else{
//            $html.="<div id='ajax-list-domain-transferable'></div>";//$TWhois->listDomainTransferable();
            $html.=$TWhois->listDomainReneval();
        }

        $html.='</div>
                        <div id="transferables" class="tab_content" '.$tab_content_t.'>';
        if($TWhois->listDomainTransferable()==null){
            $html.="<div class=\"error-domain\">
                                        <p>¡UPPS! No encontramos dominios para transferir. Por favor, verifique los dominios disponibles.</p>
                                      </div>";

        }else{
           $html.=$TWhois->listDomainTransferable();
        }
        $html.='</div>
                 </form>
            </div>'.totalOrder();
        $html.='<div class="container-add-order">
               <button id="add-order" class="tecnohost-add-order">Agregar a la Orden</button>
<!--               <a href="&back=true">Regresar a la opción anterior</a>-->
               <a href="javascript:history.back()">Regresar a la opción anterior</a>
           </div>'.tecnoFAQ(64);
            //header('Content-type: application/json; charset=utf-8');
           echo $html;
          //  die();

//        echo "<script>
//                    jQuery.ajax({
//                    url:'/wp-json/domains/v1/domains-avaibles',
//                    method: \"GET\",
//                    data: null,
//                   // contentType: \"application/json; charset=utf-8\",
//                    beforeSend: function() {
//                        console.log('cargando');
//                    },
//                    success:function(data){
//                       console.log(data);
//                    }
//            });
//        </script>";
    }else{
        return json_encode(array('response'=>'dominio inválido, recuerde no colocar "http://", "wwww" o algun punto'));
            die();
        //echo __('dominio inválido, recuerde no colocar "http://", "wwww" o algun punto');
    }
}
/*add_action( 'rest_api_init', function () {
  register_rest_route( 'whois/v1', '/domain', array(
    'methods' => 'POST',
    'callback' => 'domains',
  ) );
} );
*/
function hosting(){

    global $THosting, $TWhois;
    $url = 'combo';

    if( !THSession::hasSession('whois_domain') and isset($_POST['whois_domain'])){

        $arg = [
            array('name'=>'whois_domain','value'=>$_POST['whois_domain']),
            array('name'=>'options_service_input','value'=>trim($_POST["options_service_input"])),
            array('name' => 'mode_hosting', 'value' => $_POST['mode_hosting']),
//            array('name'=>'domain_ex','value'=>$_POST["domain_ex"])
        ];
        THSession::create($arg);
    }
    if (THSession::hasSession('whois_domain') and isset($_POST['whois_domain'])){
        $arg = [
            array('name'=>'whois_domain','value'=>$_POST['whois_domain']),
            array('name'=>'options_service_input','value'=>trim($_POST["options_service_input"])),
//           array('name'=>'domain_ex','value'=>$_POST["domain_ex"]),
            array('name' => 'mode_hosting', 'value' => $_POST['mode_hosting'])

        ];
        THSession::create($arg);
    }

    //para combo
    if ((!THSession::hasSession('mode_hosting') and isset($_POST['mode_hosting'])) or (isset($_POST['mode_hosting']) and THSession::hasSession('mode_hosting') )) {
        $arg = [
            array('name' => 'mode_hosting', 'value' => $_POST['mode_hosting']),
            array('name' => 'whois_domain', 'value' => $_POST['whois_domain']),
            array('name' => 'step_combo', 'value' => 2),
        ];
        THSession::create($arg);
    }


    if(THSession::getSession('options_service_input')=='dyh' or $_POST['mode_hosting']==1 or THSession::getSession('mode_hosting')==1){
        $current = 'Combo de Hospedaje + Dominio';
        $domain_complete = setOption('whois_domain');
    }else{
        $url = 'hosting';
        $current = 'Planes de Hospedajes';
        $domain_complete = setOption('whois_domain');//.'.'.setOption('domain_ex');
    }


    breadcrumbs($current);


    echo '<div class="button loading plan-hosting-loader"></div>
    <div class="container-tecnohost">
        <div class="description">
            <h2>Selección de Plan de Hospedaje</h2>
            <p>Usted puede escoger la contratación anual o mensual de su <b>Plan de Hospedaje</b> Web. Por favor marque la casilla
                <b>"Seleccionar"</b> en la pestaña correspondiente para el <b>Plan de Hospedaje</b> que desea adquirir. Una vez que haya
                seleccionado su plan, se le presentará abajo la información de precios. Finalmente, haga clic en el botón para agregar su pedido a la orden. Para conocer las ventajas de adquirir
                la modalidad Anual así como otros detalles de interés por favor asegúrese de leer los Tips Importantes indicados
                más abajo.
            </p>
        </div>
    </div>
    <ul class="tabs">
        <li class="active"><a href="#anual">Hospedaje Anual</a></li>
<!--        <li><a  href="#mensual">Hospedaje Mensual</a></li>-->
    </ul>

    <div class="tab_container hospedaje container-tecnohost">

    <form method="POST">
            <input type="hidden" id="domain" value="'.$domain_complete.'">
            <div id="anual" class="tab_content">';

    $THosting->setDomain($domain_complete);
    $packeges = $THosting->ListHosting();

    if(/*!$TWhois->viewWhois($domain_complete) and*/ (!THSession::hasSession('mode_hosting')) and THSession::getSession('options_service_input')!='h'/*or (THSession::getSession('mode_hosting')!=1)*/){


        //Para cuando no encuentra dominio desde el hosting y debe ir a combos
        $arg = [array('name' => 'step_combo', 'value' => 1)];
        THSession::create($arg);
        $ext = $TWhois->extractExtension($domain_complete);
    //Primero verifica si existe en el carrito
    $service_name = 'Dominio .'.$ext;
    if(check_others_cart($domain_complete,$service_name)) {

        echo '<div class="error-domain">
                        <p>La búsqueda realizada indica que el nombre de dominio <b>' . $domain_complete . '</b> no ha sido registrado.
                             Sin embargo, se encuentra agregado al carrito de compras. 
                        </p>
                </div>';
        echo $packeges;


    }else {
            echo '<div class="error-domain">
                            <input type="hidden" name="options_service_input" value="dyh">
                            <input name="whois_domain" type="hidden"  value="' . $_POST['whois_domain'] . '">
                            <input name="domain_ex" type="hidden"  value="' . $ext . '">
                            <p>La búsqueda realizada indica que el nombre de dominio <b>' . $domain_complete . '</b> no ha sido registrado
                                por una persona o empresa. Por favor, pulse
                                <a href="/contratacion/?category=hosting">AQUÍ</a> para escoger
                                otro nombre de dominio o pulse <button type="submit">AQUÍ</button> para adquirir este dominio o uno equivalente con otra extensión.
    
                            </p>
                    </div>';
        }
    }else{

        echo $packeges;
    }

    echo '</div>
<!--            <div id="mensual" class="tab_content">-->
<!--                <p>Para Hospedajes mensuajes</p>-->
<!--            </div>-->
        </form>
    </div>'.totalOrder();

    echo '<div class="container-add-order">
        <button id="add-order-hosting" class="tecnohost-add-order" >Agregar a la Orden </button>
        <a href="javascript:history.back()">Regresar a la opción anterior</a>
    </div>'.tecnoFAQ(66);

}




function showCategories(){
    global $woocommerce;

    $texto_e = '';
    $adicion_url = '';
    if(isset($_GET['id_product']) and !empty($_GET['id_product'])){
        $texto_e = "Es necesario que seleccione 'Combo de Hospedaje + Dominio' en caso que no poseea un dominio, caso contrario por favor seleccione 'Planes de Hosting'";
           foreach( WC()->cart->get_cart() as $cart_item ){
        if($_GET['id_product'] == $cart_item['product_id']){
            foreach($cart_item['addons'] as $addon){
                if($addon['name'] == 'Dominio'){
                    $adicion_url = '&dominio='.$addon['value'];
                    break;
                }
                
            }
        }
   
}
    }
    echo  '<div class="row categories-tecnohost container-tecnohost" id="container-categories-tecnohost">
        <div class="col-xs-12"><h2 class="title-categories-tecnohost">Seleccione del listado el servicio que desea contratar</h2>
    <p>'.$texto_e.'</p>    
    </div>
    <div style="width: 100%;display: flex;justify-content: center;">     
<div class="col-xs-6">
            <a  href="/tienda">
                <div class="category">
                    <img src="'.plugins_url( '/images/icons/IMG-TiendaTS-202.png',__FILE__).'" >
                    <p>Plataformas, Módulos y Servicios asociados</p>
                </div>
            </a>
        </div>
    </div>    
        <div class="col-xs-6">
            <a  href="'.get_page_link().'?category=registro">
                <div class="category">
                    <img src="'.plugins_url( '/images/icons/IMG-Categorias-RegDOM_TecnoHost-2019.jpg',__FILE__).'">
                    <p>Registro de Nombres de dominio</p>
                </div>
             </a>
        </div>
        <div class="col-xs-6">
            <a  href="'.get_page_link().'?category=transferencia'.$adicion_url.'">
                <div class="category">
                    <img src="'.plugins_url( '/images/icons/IMG-Categorias-TransfDOM_TecnoHost-2019.jpg',__FILE__).'" >
                    <p>Transferencia de Nombres de Dominios</p>
                </div>
            </a>
        </div>
        <div class="col-xs-6">
            <a  href="'.get_page_link().'?category=hosting'.$adicion_url.'">
                <div class="category">
                    <img src="'.plugins_url( '/images/icons/IMG-Categorias-Hosting_TecnoHost-2019.jpg',__FILE__).'" >
                    <p>Hosting Con CWPanel </p>
                </div>
            </a>
        </div>
        <!--<div class="col-xs-6">
            <a  href="'.get_page_link().'?category=hosting-cpanel'.$adicion_url.'">
                <div class="category">
                    <img src="'.plugins_url( '/images/icons/IMG-Categorias-Hosting_TecnoHost-2019.jpg',__FILE__).'" >
                    <p>Hosting Básico (cPanel)</p>
                </div>
            </a>
        </div>
        <div class="col-xs-6">
            <a  href="'.get_page_link().'?category=hosting-directadmin'.$adicion_url.'">
                <div class="category">
                    <img src="'.plugins_url( '/images/icons/IMG-Categorias-Hosting_TecnoHost-2019.jpg',__FILE__).'" >
                    <p>Hosting Básico (DirectAdmin)</p>
                </div>
            </a>
        </div>-->
        <div class="col-xs-6">
            <a  href="'.get_page_link().'?category=combo'.$adicion_url.'">
                <div class="category">
                    <img src="'.plugins_url( '/images/icons/IMG-Categorias-Hosting+Dominio_TecnoHost-2019.jpg',__FILE__).'" >
                    <p>Combo de Hospedaje + Dominio</p>
                </div>
            </a>
        </div>
        <div class="col-xs-6">
            <a  href="'.get_page_link().'?category=ayudante'.$adicion_url.'">
                <div class="category">
                    <img src="'.plugins_url( '/images/icons/IMG-Categorias-Ayudante-Nombres_TecnoHost-2019.jpg',__FILE__).'">
                    <p>Ayudante de Nombres</p>
                </div>
            </a>
        </div>
        <div class="col-xs-6">
            <a  href="javascript:void(0);">
                <div class="category" id="others">
                    <img src="'.plugins_url( '/images/icons/IMG-Categorias-OtrosSERV_TecnoHost-2019.jpg',__FILE__).'" >
                    <p>Otros Servicios Adicionales</p>
                </div>
            </a>
        </div>
       
    </div>
    <div  style="display: none" class="row categories-tecnohost container-tecnohost" id="others-form">
        <div class="col-xs-6">
            <a  href="'.get_page_link().'?category=certificados'.$adicion_url.'">
                <div class="category other-services">
                    <img src="'.plugins_url( '/images/icons/ICON_CertificadoSSL2_TecnoHost-2019.jpg',__FILE__).'" >
                    <p>Certificados de Seguridad</p>
                </div>
            </a>
        </div>
        <div class="col-xs-6">
            <a  href="'.get_page_link().'?category=redireccion'.$adicion_url.'">
                <div class="category other-services">
                    <img src="'.plugins_url( '/images/icons/ICON_Redireccion-URL_TecnoHost-2019.jpg',__FILE__).'" >
                    <p>Redireccionadores</p>
                </div>
            </a>
        </div>
        
 <a href="javascript:void(0);" class="back-categories" >Regresar a la opción anterior</a>

    </div>';

}


function form_domain_search($options_service_input,$class,$id,$placeholder,$descripcion_html='',$mode_domain=false){

    $con_servicio = '';
    if(isset($_GET['dominio']) and !empty($_GET['dominio'])){
        $dominio = explode('.', $_GET['dominio']);
        $con_servicio = 'value="'.$dominio[0].'" readonly="true"';

    }
    
    
    if($id===true)
        $id = $class;
    $form = ' 
        <div class="'.$class.'" id="'.$id.'">
             <div class="container-tecnohost">
                 <div class="description">
                     <p>
                     '.$descripcion_html.'
                     </p>
                </div>
            </div>
           <form  method="POST">
               <div class="container-form">
                    <input type="hidden" name="options_service_input"  value="'.$options_service_input.'">
                    <input type="hidden" name="mode_domain"  value="'.$mode_domain.'">
                    <div class="container-whois">
                        <input id="whois_domain" '.$con_servicio.' name="whois_domain" type="text" autocomplete="off" required placeholder="'.$placeholder.'">
                        <i id="icon_domain_check" class="fa" aria-hidden="true"></i>
                    </div>
                    <label for="" id="whois_domain_message" class="whois_domain_message"></label>
                    <button class="button_domain_check" id="button_domain_check" type="submit">Buscar&nbsp;<i class="fa fa-search"></i></button>
                    <a href="javascript:history.back()" class="back-categories" >Regresar a la opción anterior</a>
               </div>
            </form>
        </div>
        ';
    return $form;
}

function form_domain_search_ex($options_service_input,$class,$id,$placeholder,$descripcion_html=''){

    global $TWhois;

    $con_servicio = '';
    if(isset($_GET['dominio']) and !empty($_GET['dominio'])){
        $con_servicio = 'value="'.$_GET['dominio'].'" readonly="true"';

    }
    
    if($id===true)
        $id = $class;

    $extension= ($options_service_input == 'ssl')?get_extension():'';
    
    $form_type_hosting = false;
     
    if( isset($_GET['category']) and ($_GET['category'] == 'hosting-directadmin' or $_GET['category'] == 'hosting-cpanel') )
    {
        $type_hosting = ($_GET['category'] == 'hosting-directadmin') ? 1 : 2;
        
        $form_type_hosting = '<input type="hidden" name="type_hosting"  value="'.$type_hosting.'">';
    }

    $form = '
        <div class="categories  '.$class.'" id="'.$id.'">
            <div class="container-tecnohost">
                 <div class="description">
                     <p>'.$descripcion_html.'</p>
                </div>
            </div>
           <form  method="POST">
                <input type="hidden" name="options_service_input"  value="'.$options_service_input.'">
                <input type="hidden" name="mode_hosting"  value="0">
                '.$form_type_hosting.'
                <div class="domain-ex">
                    <div class="container-whois">
                        <input id="whois_domain_ex" '.$con_servicio.' name="whois_domain" type="text" autocomplete="off" required placeholder="'.$placeholder.'">
                        <i id="icon_domain_check_ex" class="fa" aria-hidden="true"></i>
                    </div>
                    '.$extension.'
                </div>
                <label for="" id="whois_domain_message_ex" class="whois_domain_message"></label>
                <button class="button_domain_check" id="button_domain_check_ex" type="submit">Buscar&nbsp;<i class="fa fa-search"></i></button>
                 <a href="javascript:history.back()" class="back-categories" >Regresar a la opción anterior</a>
            </form>
        </div>
        ';
    return $form;
}

function get_extension(){

    global $TWhois;

    $extension = '<select name="domain_ex" class="domain_ex_help">';

    foreach ($TWhois->extensions as $ext){
        $extension.= '<option value="'.$ext.'">.'.$ext.'</option>';
    }
    $extension.= '</select>';

    return $extension;
}

function form_domain_search_help_name($options_service_input,$class,$id,$descripcion_html=''){

    global $TWhois;

    if($id===true)
        $id = $class;

    $extension=get_extension();

    $form = '
        <div class="'.$class.'" id="'.$id.'">
            <div class="description-help-name">
                <h2>Ayudante de Nombres</h2>
                <!--<h3>¡La herramienta le ayudará a encontrar un buen nombre de dominio para su empresa !</h3>-->
             </div>
            <div class="container-tecnohost">
                 <div class="description">
                     <p>'.$descripcion_html.'</p>
                </div>
            </div>
           <form  method="POST">
                <input type="hidden" name="whois_domain_help"  value="1">
                <input type="hidden" name="options_service_input"  value="'.$options_service_input.'">
                <input type="hidden" name="whois_domain">
                <div class="domain-help">
                        <input  name="organi" type="text" autocomplete="off" required placeholder="'.__('Nombre de la empresa, producto o servicio').'">
                        <input type="text" name="keyword_one" required class="keyword" placeholder="Teclee una palabra descriptiva">
                        <input type="text" name="keyword_two" required class="keyword" placeholder="Teclee otra palabra descriptiva">
                </div>
                <div class="extensions"><label>Seleccione el tipo de dominio:</label>'.$extension.'</div>
                <button class="button_domain_check"  type="submit">Buscar&nbsp;<i class="fa fa-search"></i></button>
                 <a href="/contratacion" class="back-categories" >Regresar a la opción anterior</a>
            </form>
        </div>
        ';
    return $form;
}

function form_domain_redirect($options_service_input, $class, $id, $placeholder, $descripcion_html=''){

    global $TWhois;

    if($id===true)
        $id = $class;

    $extension= get_extension();

    $form = '
        <div class="categories  '.$class.'" id="'.$id.'">
            <div class="container-tecnohost">
                 <div class="description">
                     <p>'.$descripcion_html.'</p>
                </div>
            </div>
           <form  method="POST">
                <input type="hidden" name="options_service_input"  value="'.$options_service_input.'">
                <input type="hidden" name="whois_domain" >
                <label for="whois_domain_ex">Dominio</label>
                <div class="domain-ex">
                    <div class="container-whois">
                        <input id="whois_domain_o" name="whois_domain_o" type="text" autocomplete="off" required placeholder="'.$placeholder.'">
                        <i id="icon_domain_check_ex" class="fa" aria-hidden="true"></i>
                    </div>
                    '.$extension.'
                </div>
                <label for="domain_dest">URL destino</label>
                <div class="domain-ex">
                    <div class="container-whois">
                        <input name="whois_domain_d" type="text" autocomplete="off" required placeholder="Escriba su URL destino.  Ej. http://urldestino.com">
                    </div>
                </div>
                <label for="" id="whois_domain_message_ex" class="whois_domain_message"></label>
                <button class="button_domain_check" id="button_domain_check_redirect" type="submit">Buscar&nbsp;<i class="fa fa-search"></i></button>
                 <a href="/contratacion" class="back-categories" >Regresar a la opción anterior</a>
            </form>
        </div>
        ';
    return $form;
}
function otherServices(){

    global  $TWhois;


    if(isset($_POST["options_service_input"])){

        //si es certificado ssl
        if($_POST["options_service_input"]=='ssl'){
            $type_other_service = 1;
        }else{
            $type_other_service = 2;
        }


        $arg = [
            array('name'=>'options_service_input','value'=>$_POST["options_service_input"]),
            array('name'=>'whois_domain_o','value'=>trim($_POST["whois_domain_o"])),
            array('name'=>'whois_domain_d','value'=>trim($_POST["whois_domain_d"])),
            array('name'=>'whois_domain','value'=>trim($_POST["whois_domain"])),
            array('name'=>'domain_ex','value'=>trim($_POST["domain_ex"])),
            array('name'=>'type_other_service','value'=>$type_other_service)
        ];
        THSession::create($arg);
    }

    $options_service_input = setOption('options_service_input');
    $domain_ex = setOption('domain_ex');
    $whois_domain = setOption('whois_domain');
    $whois_domain_o = setOption('whois_domain_o');
    $whois_domain_d = setOption('whois_domain_d');
    $type_other_service = setOption('type_other_service');


    $domain_complete = ($options_service_input=='redirect')?$whois_domain_o.".".$domain_ex: $whois_domain.".".$domain_ex;

//Si el dominio tiene punto quiere decir que viene desde el combo de "otros servicios"
    if(preg_match('/\./i', $whois_domain)){

        $domain_complete = $whois_domain;

    }
    if($options_service_input == 'ssl'){
        $url = 'certificados';
        $category = 'Certificados de Seguridad';
        $arg = [array('name'=>'whois_domain_d','value'=>false)];
        THSession::create($arg);
    }elseif($options_service_input == 'redirect'){
        $url = 'redireccion';
        $category = 'Redireccionamientos';
        $whois_domain = $TWhois->extractDomain($domain_complete);
    }else{
        $url = 'certificados';
        $arg = [array('name'=>'whois_domain_d','value'=>false)];
        THSession::create($arg);
        $category = 'Certificados de Seguridad';
    }


    if(!$TWhois->viewWhois($domain_complete) and !check_domain_cart($domain_complete)){


        echo '<div class="container-tecnohost">
                       <div class="error-domain">
                           <form method="POST">
                               <input type="hidden" name="options_service_input" value="dys">
                               <input name="whois_domain" type="hidden"  value="'.$whois_domain.'">
                               <p>  La búsqueda realizada indica que el nombre de dominio <b>'.$domain_complete.'</b> no ha sido registrado
                                   por una persona o empresa. Por favor, pulse
                                   <a href="/contratacion/?category='.$url.'">AQUÍ</a> para escoger
                                   otro nombre de dominio o pulse <button type="submit">AQUÍ</button> para contratar el mismo.

                               </p>
                           </form>
                       </div>
                   </div>';

    }else{

        breadcrumbs($category);

        $html = '<div class="tab_content container-tecnohost">
                          <form>
                              <input type="hidden" id="domain" value="'.$domain_complete.'">';


        if(isset($_POST['whois_domain_d']) or THSession::getSession('whois_domain_d')!=false) {
            $html.='<input type="hidden" id="domain_d" value="'.$whois_domain_d.'">';

        }

        $html.='<table>
                                   <tr>
                                       <th>'.__("Código").'</th>
                                       <th>'.__("Descripción").'</th>
                                       <th>Precio ('.get_woocommerce_currency().')</th>
                                       <th>'.__("Selecionar").'</th>
                                   </tr>';


        $args = array(
            '
                                       }post_type' => 'product',
            'product_cat' => $category,
            'posts_per_page' => -1
        );
        $loop = new WP_Query($args);
        if ($loop->have_posts()) {

            while ($loop->have_posts()) : $loop->the_post();
                $pid = get_the_ID();
                $product = new WC_Product($pid);
                $service_name = get_the_title();
                $service_price = $product->price;
                $service_code = $product->sku;
                $service_price_html = $product->get_price_html();

                if(check_others_cart($domain_complete, $service_name)){

                    $html.='<tr class="disabled-service">';
                    $disabled = 'disabled';
                    $addcart = '<p>Este servicio ya se encuentra en el carrito de compras con el dominio: '.$domain_complete.'</p>';
                }else{
                    $html.='<tr>';
                    $disabled = '';
                    $addcart = '';
                }

                $html.='<td class="service available"><b>'.$service_code.'</b>'.$addcart.'</td>
                                               <td class="service available">'.$service_name.'</td>
                                               <td class="service available" data-price="'.$service_price.'" id="'.$pid.'">'.$service_price_html.'</td>
                                               <td class="service available">
                                                  <label class="radio">
                                                     <input data-price="'.$service_price.'" type="radio" '.$disabled.' name="service-register[]"  value="'.$pid.'">
                                                     <span class="check"></span>
                                                  </label>
                                               </td>
                                           </tr>';

            endwhile;
        }
        $html.='</table>
                              </form>
                      </div>'.totalOrder();


    }

    $html.='<div class="container-add-order">
                           <button id="add-order-others" class="tecnohost-add-order" >Agregar a la Orden </button>
                           <a href="javascript:history.back()">Regresar a la opción anterior</a>
                        </div>
                  </div>';
    echo $html;

}


function totalOrder(){

    global $currency_money_woocommerce;

    $html = '<div class="total-order">
        <!--<div class="row"><label>Sub-total Acumulado USD:</label>&nbsp;<input readonly type="text" id="subtotal" value="0,00"></div>
        <div class="row"><label>IVA 0% Acumulado USD:</label>&nbsp;<input readonly type="text" id="iva" value="0,00"></div>-->
        <div class="row"><label>Total Acumulado '.get_woocommerce_currency().':</label>&nbsp;<input readonly type="text" id="total" value="0,00"></div>
    </div>';

    if(isset($_POST['cart_item_key']))
        $html.='<input type="hidden" id="cart_item_key" value="'.$_POST['cart_item_key'].'"/>';
        
    if(isset($_POST['id_group']))
        $html.='<input type="hidden" id="id_group" value="'.$_POST['id_group'].'"/>';
        
    return $html;

}




function tecnoFAQ($category = 1){

    return "<script type='text/javascript' src='/wp-content/plugins/yith-faq-plugin-for-wordpress-premium/assets/js/yith-faq-shortcode-frontend.min.js?ver=1.0.7'></script>
    <div class='container-tecnohost tecnofaq'>
        <h3 class='title-faq'>Tips Importantes <i class='fa fa-check'></i></h3>
       ".do_shortcode('[yith_faq style="accordion" categories="'.$category.'" show_icon="left" page_size=100]')."
    </div>";

}


function navegatorCart()
{
    global $woocommerce;
     $con_servicio = '';
    if((isset($_GET['dominio']) and !empty($_GET['dominio']) ) or (isset($_GET['id_product']) and !empty($_GET['id_product']) )){
        $con_servicio = 'id="clear-cart"';
    }

    $items = $woocommerce->cart->get_cart();
    $key_cart = 0;
        foreach($items as $item) { 
            
            foreach($item['addons'] as $addon) { 
            if(($addon['name']=='Dominio' and $addon['value']==$_GET['dominio']) or ($item['product_id']==$_GET['id_product']))
                 $key_cart = $item['key'];
               
            } 
        } 
        
    if (WC()->cart->get_cart_contents_count()) {

        echo '<div class="content-navegator-cart">
            <div class="container-tecnohost navegator-cart">
                <!-- <div class="option">
                     <a href="javascript:history.back()">
                         <div class="icon">
                             <i class="fas fa-clipboard-list"></i>
                         </div>
                         <p>Ir a Categorías</p>
                     </a>
                 </div>-->

                <div class="option">
                    <a data-key="'.$key_cart.'" '.$con_servicio.' href="'.wc_get_cart_url().'">
                        <div class="icon">
                            <i class="fa fa-cart-plus"></i>
                        </div>
                        <p>Ir a Carrito</p>
                    </a>
                </div>
            </div>
        </div>';

    }
}

function breadcrumbs($current){

    return '<div class="breadcrumbs">
    <div class="container-tecnohost">
        <p>
            <a href="/contratacion">Categorías</a>|<span>'.$current.'</span>
        </p>
    </div>
</div>';

}


function setOption($option){

    if(isset($_POST[$option]) and !THSession::hasSession($option))
        return $_POST[$option];
    elseif ( isset($_POST[$option]) and THSession::hasSession($option))
        return $_POST[$option];
    else
        return THSession::getSession($option);
}


function check_domain_cart($domain){


    global $TWhois;

    $domain_ex = $TWhois->extractExtension($domain);

    foreach(WC()->cart->get_cart() as $cart_item_key => $values ) {

        $ext = $TWhois->extractExtension($values["addons"][0]['value']);
        //echo "<b>name:".$values['data']->name."</b>";
        if($domain==$values["addons"][0]['value'] and is_int(strpos($domain_ex, $ext))){

        if (preg_match("/\Dominio\b/", $values['data']->name)){
           return true;
       }

        }

    }
    return false;
}

function check_hosting_cart($domain,$hosting){


    global $TWhois;

    foreach(WC()->cart->get_cart() as $cart_item_key => $values ) {

        if($domain==$values["addons"][0]['value'] and $values['data']->sku==$hosting){

            return true;
        }

    }
    return false;
}
function check_others_cart($domain,$service){


    global $TWhois;

    foreach(WC()->cart->get_cart() as $cart_item_key => $values ) {

        if($domain==$values["addons"][0]['value'] and $values['data']->name==$service){

            return true;
        }

    }
    return false;
}

function woo_display_variation_dropdown_on_shop_page($pid, $attrib, $limit = 1)
{

        $limit = (empty($limit))? 5 : $limit;

        $limit = ($limit>8)? 5 : $limit;

        $product = wc_get_product($pid);

//    if ($product->is_type('variable')) {

        $attribute_keys = array_keys($product->get_variation_attributes());
        $variations = $product->get_available_variations();
        $variations_html = '';
        $contador = 0;
        foreach ($variations as $attribute_name => $options) {

            $product_variation = wc_get_product($options['variation_id']);

        if($product_variation->get_attribute( 'pa_'.$attrib)){

            if(($contador>=$limit)) break;
            $variations_html.= "<option value='".$options['variation_id']."'>".$product_variation->get_attribute( 'pa_'.$attrib)."</option>";
        }

        $contador++;

        }

        return $variations_html;

    /*} else {

        echo sprintf('<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
            esc_url($product->add_to_cart_url()),
            esc_attr(isset($quantity) ? $quantity : 1),
            esc_attr($product->id),
            esc_attr($product->get_sku()),
            esc_attr(isset($class) ? $class : 'button'),
            esc_html($product->add_to_cart_text())
        );

    }*/
}


function getStatusSubscriptions($domain, $tls = ''){

    global $wpdb;

    $tls = empty($tls)? '' : '.'.$tls;
    
    $pos = strpos($domain, '.');
    
    if($pos && !empty($tls))
        $domain = $domain;
    else
        $domain = $domain.$tls;
    
    $results = $wpdb->get_results("SELECT
                    woi.order_id                    
                    FROM
                        {$wpdb->prefix}woocommerce_order_itemmeta AS woim INNER JOIN {$wpdb->prefix}woocommerce_order_items AS woi 
                    ON woim.order_item_id = woi.order_item_id
                    WHERE
                    woim.meta_key LIKE '%Nombre del Dominio%' AND woi.order_item_name LIKE '%Dominio%'
                    AND woim.meta_value = '$domain'");
    $id_subscription = null;

    if(count($results)>0){
        foreach($results as $result){
            $subscription = new WC_Subscription($result->order_id);
            $relared_orders_ids_array = $subscription->get_related_orders();

            if($subscription->get_parent_id()!=0){
                $id_subscription = $result->order_id;
                return $subscription->get_status();
            }

        }
    }

    return false;


}

function getPanelDomain($domain, $tls = ''){

    $tls = empty($tls)? '' : '.'.$tls;
    
    $pos = strpos($domain, '.');
    
    if($pos && !empty($tls))
        $domain = $domain;
    else
        $domain = $domain.$tls;
        
    $mydb = new wpdb('domtecno_admin','nXSxTkqDmv23','domtecno_dominios','dominios.tecnosoluciones.com');

    $rows = $mydb->get_results("select domain, expiry_date, tld from domains WHERE domain='{$domain}'");
    //Si el dominio no existe en el panel de dominios
    if($rows!=false){

        foreach ($rows as $obj) :

            $date1 = new DateTime();

            $date2 = new DateTime($obj->expiry_date);

            $diff = $date1->diff($date2);

           if($diff->days<=0)
               return 'vencido';
           else
               return 'no vencido';

        endforeach;
    }
    return 'no found';



}


?>
