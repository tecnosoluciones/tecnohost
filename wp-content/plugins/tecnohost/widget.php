<?php

class TecnoWhois extends WP_Widget {
    // class constructor
    public function __construct() {

        $widget_ops = array(
            'classname' => 'tecno-whois',
            'description' => '',
        );
        parent::__construct( 'tecno-whois', 'TecnoWhois', $widget_ops );
    }

    // output the widget content on the front-end
    public function widget( $args, $instance ) {

        $p = get_post($instance['tsv_whois_widget_select']);
        $page_name = $p->post_title;
//var_dump($_SESSION);
        print '<aside id="tsv-whois" class="tsv-whois-widget container-tecnohost">
            <form action="'.get_site_url() .'/'.$page_name.'/" method="POST">
            <input type="hidden" name="mode_domain"  value="c">
                <div class="options_service">
                    <label class="radio">
                    <input type="radio" checked name="options_service_input"  value="dyh"><label>Dominios y Hosting</label>
                        <span class="check"></span>
                    </label>
                    <label class="radio">
                    <input type="radio" name="options_service_input"  value="h"><label>Sólo Hosting</label>
                        <span class="check"></span>
                    </label>
                    <label class="radio">
                    <input type="radio" name="options_service_input"  value="d"><label>Sólo Dominios</label>
                        <span class="check"></span>
                    </label>
                </div>
                   <div class="widget_container">
                    <div class="container-whois">
                        <input id="whois_domain" name="whois_domain" type="text" autocomplete="off" required placeholder="'.$instance['tsv_whois_widget'].'">
                        <i id="icon_domain_check" class="fa" aria-hidden="true"></i>    
                    </div>
                    <!--<select name="domain_ex" id="domain_ex_widget" class="domain_ex_widget domain_ex"><option value="com">.com</option><option value="net">.net</option><option value="org">.org</option><option value="com.co">.com.co</option><option value="net.co">.net.co</option><option value="co">.co</option><option value="biz">.biz</option><option value="us">.us</option><option value="info">.info</option></select>-->
                </div>
                <label for="" id="whois_domain_message" class="whois_domain_message"></label>
                <button class="button_domain_check" id="button_domain_check" type="submit">Buscar &nbsp;<i class="fa fa-search"></i>  </button>
            </form>
        </aside>';



    }

    // output the option form field in admin Widgets screen
    public function form( $instance ) {

        $args = array('selected'=>esc_attr($instance['tsv_whois_widget_select']),'echo'=>0,'name'=>$this->get_field_name('tsv_whois_widget_select'),'class'=> 'widefat','id'=> $this->get_field_id('tsv_whois_widget_select'));

        print '<p>
                    <label for="'.$this->get_field_id('tsv_whois_widget').'">Placeholder</label>
                    <input type="text" class="widefat" id="'.$this->get_field_id('tsv_whois_widget').'" name="'.$this->get_field_name('tsv_whois_widget').'" value="'.esc_attr($instance['tsv_whois_widget']).'">
                    <label for="'.$this->get_field_id('tsv_whois_widget_select').'">Show page</label>
                    '.wp_dropdown_pages($args).'
               </p>';


    }

    // save options
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance["tsv_whois_widget"] = strip_tags($new_instance["tsv_whois_widget"]);
        $instance["tsv_whois_widget_select"] = strip_tags($new_instance["tsv_whois_widget_select"]);

        return $instance;

    }
}