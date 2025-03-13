<?php

class THosting{


    public $domain;



    public function setDomain($domain){
            $this->domain = $domain;
    }

    public function ListHosting(){


        $hosting='
            <table>
                <tr>
                    <th>'.__("Plan").'</th>
                    <th>'.__("Descripción").'</th>
                    <th>'.__("Precio (".get_woocommerce_currency().")").'</th>
                    <th>'.__("Seleccionar").'</th>
                </tr>
                ';
        $args = array(
            'post_type'      => 'product',
            'product_cat'    => 'hosting-estandar,hosting-revendedores',
            'posts_per_page' =>-1,
            'order'       =>'ASC'
        );
        
        if( isset($_POST['type_hosting']) )
        {
            //Direct Admin
           if( $_POST['type_hosting'] == 1 )
            { 
                $category = 'hosting-basico-directadmin';
                
            //cPanel
            }else{
                $category = 'hosting-basico-cpanel';
            }
            $args = array(
                'post_type'      => 'product',
                'product_cat'    => $category,
                'posts_per_page' =>-1,
                'order'       =>'ASC'
            );
            
        }
        
        $loop = new WP_Query($args);
        if ( $loop->have_posts() ) {

            while ( $loop->have_posts() ) : $loop->the_post();
                $pid = get_the_ID();
                $product = new WC_Product( $pid );
                $service_price = $product->price;
                $service_price_html = $product->get_price_html();

                //if(check_hosting_cart($this->domain,$product->sku)){
                if(1!=1){
                    $hosting.= '<tr class="disabled-service">';
                    $disabled = 'disabled';
                    $addcart = '<p>Este servicio ya se encuentra en el carrito de compras con el dominio: '.$this->domain.'</p>';
                }else{
                    $hosting.= '<tr>';
                    $disabled = '';
                    $addcart = '';
                }
                    $checked = '';
                    if (isset($_POST['id_product']) AND $pid==$_POST['id_product']) $checked = 'checked';
                    
                    
                $hosting.="<td class='hosting-sku'><b>$product->sku</b>".$addcart."</td>
                              <td class='description'>$product->short_description</td>
                              <td class='domain'>$service_price_html</td>
                              <td class='select hosting'>
                                    <label class=\"radio\">
                                        <input $disabled type='radio' name='hosting-register' $checked data-price='$service_price' id='$pid' value='$pid' data-service='$product->sku'>
                                    <span class=\"check\"></span>
                                    </label>
                              </td>
                           </tr>";
            endwhile;
            wp_reset_postdata();

        }
        $hosting.="</table>";
        return $hosting;
    }
}


$THosting= new THosting();