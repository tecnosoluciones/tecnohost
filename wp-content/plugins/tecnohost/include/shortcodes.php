<?php

/* Registro de shortcode para la contratacion */

function process_servers_single(){
    global $TWhois, $product;
    
    $title = $product->get_name();
    $description = $product->get_description();
    if(method_exists($product, 'get_available_variations')){
            $available_variations = $product->get_available_variations();
    }else{
        $available_variations = [];
    }
    $categories = get_the_terms( $product->get_id(), 'product_cat' );
    $moneda = get_woocommerce_currency_symbol();
    $image = get_the_post_thumbnail_url($product->get_id());

    $subscription_cambio = false;
    //Para renovaciones
    $metas = "";
    $id_cambio = "";
    $titulo_cambio = "";
    $text_ciclo = "Elija el ciclo de facturación";
    if(strpos($_SERVER['QUERY_STRING'], 'switch-subscription') !== false || explode("=",$_SERVER['QUERY_STRING'])[0] == "switch-subscription") {
        $subscription_cambio = true;
        $id_suscripcion = $_REQUEST['switch-subscription'];
        $metas = validar_campos_switch_vps($id_suscripcion);
        $text_ciclo = "Elija el nuevo ciclo de facturación. Actualemente su plan de facturación es <span id='ciclo_actual'>".$metas['pa_plan']."</span>";
        $metas = json_encode($metas);
        $id_cambio = "id='cambio_servidor'";
        $titulo_cambio = "Cambio de Ciclo";

        echo "
            <div id='loader'>
                <div class='spinner'>
                    <div class='loaders l1'></div>
                    <div class='loaders l2'></div>
                </div>
            </div>
            <script >
                jQuery(window).on( 'load', function() {
                    jQuery('.woocommerce-notices-wrapper').insertAfter(jQuery('.storefront-breadcrumb'));
                    jQuery('.woocommerce-notices-wrapper').attr('style', 'position:relative !important;');
                   
                    jQuery('.woocommerce-info').attr('style', 'margin-bottom:0;');
                    validar_cambio_vps();
                });
                
                jQuery('body').on('change','select#select-recuerrencia-server',function(){
                    setTimeout(validar_cambio_precio(), 500);
                    jQuery('button.btn-success-server.single_add_to_cart_button').removeAttr('disabled');
                });
                
                jQuery('body').on('change', '.wc-pao-addons-container select', function(){
                    setTimeout(validar_cambio_precio(), 500);
                });
                
                jQuery('body').on('change', '.wc-pao-addons-container :checkbox', function(){
                    setTimeout(validar_cambio_precio(), 500);
                });
                
                jQuery('body').on('change', '.complementos-adicionales :checkbox', function(){
                    setTimeout(validar_cambio_precio(), 500);
                });
                
                jQuery('body').on('keypress','form#cambio_servidor input',function(){
                    return false;
                });
            </script>
        ";
    }

    ?>
        <div class="product-server">
            
            <div class="step-domain" style="<?php if($subscription_cambio) echo "display: none";?>">
            
                <div class="container-selection-options">
            
                    <div class="option-domain">
                         <div class="radio-container-domain">
                            <label class="radio">
                                <input type="radio" checked name="domainoption" value="1" checked="" >Registrar un nuevo dominio
                                <span class="check"></span>
                            </label>
                            <label class="radio">
                                <input type="radio" name="domainoption"  value="0" >Transferir su dominio desde otro registrar
                                <span class="check"></span>
                            </label>
                            <label class="radio">
                                <input type="radio" name="domainoption"  value="2"  >Yo usaré mi propio dominio
                                <span class="check"></span>
                            </label>
                        </div>
                        <div class="domain-input-group" id="domainregister">
                                <input type="text" id="domain-register-input" value="" placeholder="Por favor, ingrese su nombre de dominio">
                                <select id="register-tld-input" name="register-tld-input">
                                    <?php
                                     foreach($TWhois->servers as $tls => $servers){
                                         
                                         ?>
                                         <option value=".<?php echo $tls;?>" >.<?php echo $tls;?></option>
                                         <?php
                                     }
                                    ?>    
                                    
                                </select>
                                <button type="submit" class="btn-check-domain single_add_to_cart_button">
                                    Verificar Dominio
                                </button>
                        </div>
                    </div>
               
                </div>
                <div class="result-domain" id="result-domain">
                
                </div>
                
            </div> 
            <div class="step-server" style="<?php if(!$subscription_cambio) echo "display: none"; else "display:block";?>">
                <form class="variations_form cart" <?php echo $id_cambio; ?> data-meta='<?php echo $metas; ?>' action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" >
                    <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />

                    <table class="group_table">
                        <tr class="product">
                            <td><input class="input-text qty text" type="hidden" name="quantitya" value="1" /></td>
                        </tr>
                    </table>
                    <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
                    <input type="hidden" name="variation_id" class="variation_id" value="<?php echo $available_variations[0]['variation_id'];?>" />
                    <input type="hidden" name="addon-110178-nombre-del-dominio-0" id="domain_cart" value="dominio.com" />
                 <h2>Configurar <?php echo $titulo_cambio; ?></h2>
                 <div class="row">
                     <div class="col-7">
                        <span>Configure sus opciones deseadas y continúe con su proceso de compra.</span>
                        <div class="product-info">
                            <div class="product-head">
                                <img src="<?php echo $image;?>" />    
                                <h3 class="product-title"><?php echo $categories[0]->name; ?> - <?php echo $title; ?>  </h3>
                            </div>
                            <p>
                            <?php echo $description; ?>    
                            </p>
                        </div>
                        <div class="periodicidad-servidor">
                            <span><?php echo $text_ciclo; ?></span>
                            <select id="select-recuerrencia-server" class="calculate-total custom-select" name="register-tld-input">
                                    <?php
                                    $i = 0;
                                     foreach($available_variations as $key => $variation){
                                        $subscription_period = apply_filters( 'woocommerce_subscriptions_product_sign_up_fee', get_post_meta($variation["variation_id"], "_subscription_sign_up_fee", true), wc_get_product($variation));

                                        if($key==0){
                                             $configuracion_inicial = $subscription_period;
                                         }
                                         switch($variation['attributes']['attribute_pa_plan']){
                                             
                                             case 'mensual':
                                                 $recurrencia = 1;
                                                 $tipo = "data-tipo='mensual'";
                                                 break;
                                            case 'trimestral':
                                                 $recurrencia = 3;
                                                $tipo = "data-tipo='trimestral'";
                                                 break;
                                                 
                                            case 'semestral':
                                                 $recurrencia = 6;
                                                $tipo = "data-tipo='semestral'";
                                                 break;
                                                 
                                            case 'anual':
                                                 $recurrencia = 12;
                                                $tipo = "data-tipo='anual'";
                                                 break;
                                                 
                                            default: 
                                                 $recurrencia = 1;
                                                $tipo = "";
                                                 break;
                                         }
                                         ?>
                                         <option <?php echo $tipo;?> value="<?php echo $variation['variation_id'];?>" data-recurrencia="<?php echo $recurrencia;?>" data-period-html='<?php echo wc_price($subscription_period); ?>' data-period='<?php echo $subscription_period; ?>' data-price-html='<?php echo wc_price($variation['display_regular_price']);?>' data-price="<?php echo $variation['display_regular_price'];?>">
                                            <?php
                                                if($subscription_cambio){
                                                    echo str_replace("/","cada",explode(" y ",$variation['price_html'])[0]);
                                                }
                                                else{
                                                    echo $variation['price_html'];
                                                }
                                            ?>
                                         </option>
                                         <?php
                                         $i++;
                                     }
                                    ?>    
                                    
                                </select>
                        </div>
                     <?php 
                     
                     
                     do_action( 'woocommerce_before_add_to_cart_button' ); 

                     if($product->get_cross_sell_ids()){
                     ?>
                     <h2 class="wc-pao-addon-heading">Complementos Adicionales</h2>
                     <?php
                     }
                     ?>
                         <div class="complementos-adicionales">
                        <?php
                        $arrayCrosssellProduct = [];
                         foreach( $product->get_cross_sell_ids() as $id):
                            $crosssellProduct = wc_get_product( $id );
                            $subscription_period_price = apply_filters( 'woocommerce_subscriptions_product_sign_up_fee', get_post_meta($id, "_subscription_sign_up_fee", true), wc_get_product($id));

                            $arrayCrosssellProduct[] = array('id'=>$id, 'title'=>$crosssellProduct->get_name(), 'price'=>$crosssellProduct->get_price(), 'price_html'=>$crosssellProduct->get_price_html(), 'cuota_inicial'=>$subscription_period_price);
                            ?>
                                <div class="item">
                                    <div class="item-head">
                                 <input type="checkbox" id="<?php echo "ca-".$id;?>" class="complemento-adicional wc-pao-addon-field wc-pao-addon-checkbox" name="complemento_adicional[]" data-raw-price="<?php echo $crosssellProduct->get_price();?>" data-price="<?php echo $crosssellProduct->get_price();?>" value="<?php echo $id;?>">
                                    <?php
                                        echo "<h2>".$crosssellProduct->get_name()."<h2>";
                                    ?>  
                                    </div>
                            <?php
                            echo "<span>".$crosssellProduct->get_price_html()."</span>";
                            ?>
                                </div>
                             <?php endforeach;?>
                         </div>
                    </div>
                    <div class="col-5">
                     <div class="order-summary">
                         <h2>Resumen del Servidor</h2>
                         <div class="summary-container">
                         <h3 class="product-title"><?php echo $title; ?></h3>
                         <div class="container-addons">
                        <?php
                            $args = array("post_type" => "global_product_addon", "title" => $categories[0]->name, 'posts_per_page '=>1);
                            $query = get_posts( $args );
                            //var_dump($query);
                            if($query){
                                $postid = $query[0]->ID;
                            
                                $product_attr = get_post_meta( $postid, '_product_addons' );

                                $count = 0; //campos de hostname y dns
                                foreach($product_attr[0] as $key=>$value){
                                    
                                    if($value['type']=='multiple_choice' or $value['type']=='checkbox'){
                                
                                $name = $value['name'];
                                if($value['type']=='checkbox'){
                                     $class = "".$product->get_id().'-'.$count.'-0';
                                }else{
                                    $class = "addon-".$product->get_id().'-'.$count;
                                }
                                
                        ?>
                        
                            <div class="clearfix">
                                <span class="pull-left float-left"><p>&nbsp;» <?php echo $name;?></p></span>
                                <span class="pull-right float-right"><strong><?php echo $moneda;?> <span class="item-price <?php echo $class;?>" data-price="0" >0.00</span></strong></span>
                            </div>
                        <?php
                                    }
                                    $count++;
                                }
                            }   
                        ?>
                        
                        <?php

                            if($arrayCrosssellProduct){
                                foreach($arrayCrosssellProduct as $key=>$value){

                                $name = $value['title'];
                                $class = "ca-".$value['id'];
                                
                        ?>
                        
                            <div class="clearfix">
                                <span class="pull-left float-left"><p>&nbsp;» <?php echo $name;?></p></span>
                                <span class="pull-right float-right"><strong><?php echo $moneda;?> <span class="item-price <?php echo $class;?>" data-price="0" >0.00</span></strong></span>
                            </div>
                        <?php
                                }
                            }   
                        ?>
                        </div>
                            <div class="divider"></div> 
                      
                            <div class="clearfix">
                               <span class="pull-left float-left"><p>Total Recurrente (<span id="pago-recurrencia-label">Mensual</span>) :</p></span>
                               <strong> <span> <?php echo $moneda;?>  <span id="price-server-variations" data-price="<?php echo $product->get_price();?>" class="pull-right float-right"><?php echo wc_price($product->get_price());?></span></span></strong>
                            </div>
                            <div class="divider"></div>
                             <?php
                                if(!$subscription_cambio) {
                                    ?>
                                    <div class="clearfix">
                                        <span class="pull-left float-left"><p>Costo de Instalación:</p></span>
                                        <strong>
                                            <span id="setup-server" class="pull-right float-right">
                                                <?php echo wc_price($configuracion_inicial); ?>
                                            </span>
                                        </strong>
                                    </div>
                                    <div class="divider"></div>
                                    <?php
                                }
                            ?>
                            <div class="total">
                              
                                <span> <?php echo $moneda;?> <span class="float-right" id="total-server" data-total="0"> <?php echo wc_price($configuracion_inicial+$product->get_price());?></span></span>
                            </div>
                             <div class="btn-success-container">
                                    <button type="button" class="btn-success-server single_add_to_cart_button">
                                            Continuar &nbsp;
                                    </button>
                                </div> 
                            
                         </div>
                     </div>
                    </div>
                     
                     
                 </div>
                 </form>
            </div>  
                 
            
            
            
        </div>
    
    <?php    
}



add_shortcode('process_servers_single', 'process_servers_single');


function servers_list(){
    // Setup your custom query
    
    if(empty(get_query_var( 'product_cat' ))){
        $args = array( 
            'post_type' => 'product', 
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'product_cat' => 'servidores',
            'orderby'=> 'ID',
            'order'=> 'ASC',
            );
    }else{
        $args = array( 
            'order'=> 'ASC',
        'orderby'=> 'ID',
        'post_type' => 'product', 
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'product_cat' => 'servidores',
        'tax_query' => array( array(
            'taxonomy'         => 'product_cat',
            'field'            => 'slug',
            'terms'            => get_query_var( 'product_cat' ),
        )),
        
        );
    }

$loop = new WP_Query( $args );
?>
<div class="container-products-server">
<ul>
<?php
while ( $loop->have_posts() ) : $loop->the_post();
 global $product;
 $description = get_the_content();
?>
    <li>
    <?php /*if (has_post_thumbnail($loop->post->ID)) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog');*/?>
    <div class="header-server">
     <a href="<?php echo get_permalink( $loop->post->ID ) ?>">
        <?php the_title(); ?>
    </a>
    </div> 
    <div class="description-server"><?php echo $description; ?></div>   
    <span class="price"><?php echo $product->get_price_html(); ?></span>
    <div class="add-to-cart-server">
        <a href="<?php echo get_permalink( $loop->post->ID ) ?>">
            COMPRAR
        </a>
    </div>   
    </li>  
    
<?php 


    endwhile; 

    ?>
</ul>
</div>
<?php
    wp_reset_query(); // Remember to reset
}


add_shortcode('servers_list', 'servers_list');


function servers_category_list(){
    
        ?>
        <div class="block-category-servers">
            <header><i class="fa fa-server" aria-hidden="true"></i> Servidores</header>
            <ul class="list-categories">
                <?php
                echo wp_list_categories( array('orderby' => 'id','order' => 'ASC','taxonomy' => 'product_cat', 'title_li'  => '', 'include'=> [ 446, 447, 448, 449, 514]) );
                ?>
            </ul>
        </div>
        <?php
    
}
add_shortcode('servers_category_list', 'servers_category_list');


?>