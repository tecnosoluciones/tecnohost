<?php


class VerificarIp extends WP_Widget {
    // class constructor
    public function __construct() {

        $widget_ops = array(
            'classname' => 'verificar-ip',
            'description' => '',
        );
        parent::__construct( 'verificar-ip', 'Verificardor de Ip', $widget_ops );
    }

    // output the widget content on the front-end
    public function widget( $args, $instance ) {


        echo '<div class="container-ip">
            <h4>Verifique si su IP est&aacute; bloqueada</h4>
            <form class="form-inline" method="POST" action="" role="form">

                    <div class="form-group">
                        <input id="ip" class="form-control input_verificaip" value="'.getClientIP().'" placeholder="'.$instance['placeholder_verificar_ip'].'">
                    </div>
                     <div class="container-boton">
                         <button type="button" onclick="enviarip();">Buscar&nbsp;<i class="fa fa-search"></i></button>
                         <button type="button" class="btn-default" onclick="cancelarip();">Cancelar&nbsp;<i class="fa fa-times"></i></button>
                     </div>

            </form>
            <p>Usted se est&aacute; conectando desde la IP: <i><b>'.getClientIP().'</b></i></p>
            <div id="respuesta" class="response-ip"> </div>
        </div>';


    }

    // output the option form field in admin Widgets screen
    public function form( $instance ) {


        echo '<p>
                    <label for="'.$this->get_field_id('placeholder_verificar_ip').'">Placeholder</label>
                    <input type="text" class="widefat" id="'.$this->get_field_id('placeholder_verificar_ip').'" name="'.$this->get_field_name('placeholder_verificar_ip').'" value="'.esc_attr($instance['placeholder_verificar_ip']).'">
               </p>';


    }

    // save options
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance["placeholder_verificar_ip"] = strip_tags($new_instance["placeholder_verificar_ip"]);

        return $instance;

    }
}
