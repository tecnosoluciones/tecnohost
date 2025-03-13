
jQuery(window).load(function() {
   jQuery('.plan-hosting-loader').fadeOut('slow');
});
jQuery(document).ready(function(){
    var url_site = WPURLS.siteurl;
    jQuery('.woocommerce-mini-cart .cart_item .product-name a').removeAttr('href');

jQuery('input[id*="dominio"]').keyup(function (e) {
    if(jQuery(this).hasClass("_con_exten") === false) {
        var domain = jQuery(this).val();
        if (/[`~!@#$%^&*()_|+\=?;:'",.<>\{\}\[\]\\\/]/.test(domain)) {
            jQuery(this).addClass('error-dominio');
            jQuery(".single_add_to_cart_button").addClass('disabled');
            if (e.which != 13) {
                return false;
            }
        } else {
            jQuery(".single_add_to_cart_button").removeClass('disabled');
            jQuery(this).removeClass('error-dominio');
        }
    }
});



jQuery("#clear-cart").click(function(){
          jQuery.ajax({
            url: url_site+ '/wp-json/tecnohost/v1/remove-to-cart',
            method: "DELETE",
            data: JSON.stringify({'cart_item_key': jQuery(this).data('key')}),
            contentType: "application/json; charset=utf-8",
            success: function (data) {
    //console.log(data)
            }
        });
     });
    //validar dominio
    function domainValidate(domain) {

        if (/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/.test(domain)) {
            return true;
        } else {
            return false;
        }
    }
    //Para formatear precios

    function formatearNumero(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? ',' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return x1 + x2;
    }

    //Comprobación de URL
    function validURL(str) {
        var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
        return !!pattern.test(str);
    }

    /*Verificar el localstorage para detectar si existe algún servicio, de ser así pre-seleccionar*/

    if("producto" in localStorage && "estado" in localStorage){

        var servicio = localStorage.getItem("producto");
        var estado =  localStorage.getItem("estado");


        //calcular el total
        var subtotal=0;
        var total=0;
        var iva=0;


        if(estado == 'hosting'){

            jQuery("input:radio[data-service='"+servicio+"']").prop('checked', true);
            subtotal+=parseFloat(jQuery("input:radio[data-service='"+servicio+"']:checked").data('price'));

        }else{
            jQuery("input:checkbox[data-service='"+servicio+"']").prop('checked', true);
            var select_d = jQuery("input:checkbox[data-service='"+servicio+"']:checked");
            subtotal+=parseFloat(jQuery("#"+select_d.val()).data('price'));

        }
        var n = subtotal.toFixed(2);

        jQuery('#total').val(formatearNumero(n));

    }

    /**
     * Tabs para los listado
     */

    //jQuery(".tab_content").hide(); //Hide all content
    //jQuery("ul.tabs li:first").addClass("active").show(); //Activate first tab
    //jQuery(".tab_content:first").show(); //Show first tab content

    //On Click Event
    jQuery(document).on("click", "ul.tabs li", function(event) { 
        jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class
        jQuery(this).addClass("active"); //Add "active" class to selected tab
        jQuery(".tab_content").hide(); //Hide all tab content

        var activeTab = jQuery(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
        jQuery(activeTab).fadeIn(); //Fade in the active ID content
        return false;
    });

    /**
     * Verifcar dominio - Widget
     */
    var whois_domain = jQuery("#whois_domain");
    var whois_domain_ex = jQuery("#whois_domain_ex");
    var container_whois = jQuery(".container-whois");
    var icon_whois_domain = jQuery("#icon_domain_check");
    var button_domain_check = jQuery("#button_domain_check");
    var button_domain_redirect = jQuery("#button_domain_check_redirect");



    whois_domain.keyup(function(){

        var expre = /[`~!@#$%^&*()_°¬|+=?;:'",\.<>\{\}\[\]\\\/]|\s/gi;


        var options_service_input =  jQuery('input[name="mode_domain"]');

        if(options_service_input.val()  == 'h'){
            whois_domain.attr('placeholder', 'Introduzca su nombre de dominio');
            var ms_error = 'Escriba un nombre de dominio válido, por ejemplo: midominio.com';
            var validateDomain = !domainValidate(whois_domain.val());

        }else{
            whois_domain.attr('placeholder', 'Introduzca el Nombre de Dominio deseado sin la extensión. Ej. sin el .com');
            var ms_error = 'Escriba un nombre de dominio válido: sin extensión, espacios o caracteres especiales.';
            var validateDomain = expre.exec(whois_domain.val());

        }


        if(validateDomain || whois_domain.val().length>255){

            //var ms_error = 'Escriba un nombre de dominio válido: sin extensión, espacios o caracteres especiales.';
            if (whois_domain.val().length>255){
                ms_error = 'El nombre no debe contener más de 255 caracteres';
            }
            icon_whois_domain.removeClass('fa-check');
            icon_whois_domain.addClass('fa-times');
            container_whois.addClass('error');
            jQuery("#whois_domain_message").html(ms_error);
            button_domain_check.attr('disabled',true);
        }else{
            icon_whois_domain.addClass('fa-check');
            icon_whois_domain.removeClass('fa-times');
            container_whois.removeClass('error');
            jQuery("#whois_domain_message").html('');
            button_domain_check.attr('disabled',false);
            /*Efecto loader*/
            jQuery(".button_domain_check").click(function() {
                jQuery(this).html("<div style='display: flex; justify-content:space-between;align-items: center;'> <div class='loader'></div> <span style='text-transform: capitalize;'>Buscando Extensiones... </span></div>");

            });

        }

        if(whois_domain.val()==''){
            icon_whois_domain.removeClass('fa-check');
        }

    });

    /*Formulario para dominios con extensión*/
    whois_domain_ex.keyup(function(){

        var expre = /[`~!@#$%^&*()_°¬|+=?;:'",\.<>\{\}\[\]\\\/]|\s/gi;

        if (whois_domain_ex.val().length>255){
            ms_error = 'El nombre no debe contener más de 255 caracteres';
        }
        var options_service_input = jQuery('input[name=options_service_input]');

        if(options_service_input.val()  == 'ssl'){
            var ms_error = 'Escriba un nombre de dominio sin la extensión';
            var validateDomain = expre.exec(whois_domain_ex.val());

        }else{
            var ms_error = 'Escriba un nombre de dominio válido, por ejemplo: midominio.com';
            var validateDomain = !domainValidate(whois_domain_ex.val());

        }


        if(validateDomain || whois_domain_ex.val().length>255){
            jQuery("#icon_domain_check_ex").removeClass('fa-check');
            jQuery("#icon_domain_check_ex").addClass('fa-times');
            container_whois.addClass('error');
            jQuery("#whois_domain_message_ex").html(ms_error);
            jQuery("#button_domain_check_ex").attr('disabled',true);

        }else{
            jQuery("#icon_domain_check_ex").addClass('fa-check');
            jQuery("#icon_domain_check_ex").removeClass('fa-times');
            container_whois.removeClass('error');
            jQuery("#whois_domain_message_ex").html('');
            jQuery("#button_domain_check_ex").attr('disabled',false);
        }

        if(whois_domain_ex.val()==''){
            jQuery("#icon_domain_check_ex").removeClass('fa-check');
        }

    });

    /*Formulario para dominios de redireccionadores*/

    jQuery("input[name=whois_domain_o]").keyup(function(){


                    var domain = jQuery(this);

                    var expre = /[`~!@#$%^&*()_°¬|+=?;:'",\.<>\{\}\[\]\\\/]|\s/gi;

                    var expre_punto = /\./gi;

                    var ms_error = 'Escriba un nombre de dominio válido: sin extensión, espacios o caracteres especiales.';
                    if (domain.val().length>255 ){
                        ms_error = 'El nombre de dominio no debe contener más de 255 caracteres';
                    }

                    if(expre.exec(domain.val()) || domain.val().length>255){

                        jQuery(this).parent(container_whois).addClass('error');
                        jQuery("#whois_domain_message_ex").html(ms_error);
                        button_domain_redirect.attr('disabled',true);

                    }else{
                        jQuery(this).parent(container_whois).removeClass('error');
                        jQuery("#whois_domain_message_ex").html('');
                        button_domain_redirect.attr('disabled',false);
                    }

        }

    );
   /*Formulario para dominios de redireccionadores*/

    jQuery("input[name=whois_domain_d]").keyup(function(){


                    var domain = jQuery(this);

                    var ms_error = 'Escriba una URL válida';
                    if (domain.val().length>255 ){
                        ms_error = 'El nombre de dominio no debe contener más de 255 caracteres';
                    }

                    if(!validURL(domain.val()) || domain.val().length>255){

                        jQuery(this).parent(container_whois).addClass('error');
                        jQuery("#whois_domain_message_ex").html(ms_error);
                        button_domain_redirect.attr('disabled',true);

                    }else{
                        jQuery(this).parent(container_whois).removeClass('error');
                        jQuery("#whois_domain_message_ex").html('');
                        button_domain_redirect.attr('disabled',false);
                    }

        });




    /**
     * Validar si es combo desde el widget principal
     */
    jQuery("input:radio[name='options_service_input']").click(function(){

            if(this.value == 'h'){
                jQuery('input[name="mode_domain"]').val('h');
                jQuery("input[name='whois_domain']").attr("id","whois_domain_ex");
                    // jQuery('#domain_ex_widget').show();
                }else{
                jQuery("input[name='whois_domain']").attr("id","whois_domain");
                    // jQuery('#domain_ex_widget').hide();
                }
        if(this.value == 'd'){
            jQuery('input[name="mode_domain"]').val('r');
        }else if (this.value == 'dyh'){
            jQuery('input[name="mode_domain"]').val('c');
        }

        //validate
        var expre = /[`~!@#$%^&*()_°¬|+=?;:'",\.<>\{\}\[\]\\\/]|\s/gi;


        var options_service_input =  jQuery('input[name="mode_domain"]');

        if(options_service_input.val()  == 'h'){
            whois_domain.attr('placeholder', 'Introduzca su nombre de dominio');
            var ms_error = 'Escriba un nombre de dominio válido, por ejemplo: midominio.com';
            var validateDomain = !domainValidate(whois_domain.val());

        }else{
            whois_domain.attr('placeholder', 'Introduzca el Nombre de Dominio deseado sin la extensión. Ej. sin el .com');
            var ms_error = 'Escriba un nombre de dominio válido: sin extensión, espacios o caracteres especiales.';
            var validateDomain = expre.exec(whois_domain.val());

        }


        if(validateDomain || whois_domain.val().length>255){

            //var ms_error = 'Escriba un nombre de dominio válido: sin extensión, espacios o caracteres especiales.';
            if (whois_domain.val().length>255){
                ms_error = 'El nombre no debe contener más de 255 caracteres';
            }
            icon_whois_domain.removeClass('fa-check');
            icon_whois_domain.addClass('fa-times');
            container_whois.addClass('error');
            jQuery("#whois_domain_message").html(ms_error);
            button_domain_check.attr('disabled',true);
        }else{
            icon_whois_domain.addClass('fa-check');
            icon_whois_domain.removeClass('fa-times');
            container_whois.removeClass('error');
            jQuery("#whois_domain_message").html('');
            button_domain_check.attr('disabled',false);
            /*Efecto loader*/
            jQuery(".button_domain_check").click(function() {
                jQuery(this).html("<div style='display: flex; justify-content:space-between;align-items: center;'> <div class='loader'></div> <span style='text-transform: capitalize;'>Buscando Extensiones... </span></div>");

            });

        }

        if(whois_domain.val()==''){
            icon_whois_domain.removeClass('fa-check');
        }

    });


    /**
     * Sumar los servicios y calcular el total
     */


        /*Validar si está seleccionado check/radio*/

    function check_select_domain_primary(){

       return jQuery("input[name='domain-primary']").is(':checked');
    }
    function check_select_service(){

       return jQuery("input:checkbox").is(':checked');
    }
    function check_select_service_ot(){

       return jQuery("input:radio").is(':checked');
    }
    function check_select_hosting(){
       return jQuery("input[name='hosting-register']").is(':checked');
    }
    jQuery(document).on("click", "input[name=domain-primary]", function(event) { 


        var id_check = jQuery(this).val();
        var domain_p = jQuery("input[data-helper='"+id_check+"']");

        domain_p.prop('checked', true);


        var subtotal=0;
        var total=0;
        var iva=0;

        jQuery('input[name="domain-register[]"]:checked, input[name="hosting-register"]:checked, input[name="service-register[]"]:checked').each(function(){
            subtotal+=parseFloat(jQuery('#'+jQuery(this).val()).data('price'));

        });

        var n = subtotal.toFixed(2);
        jQuery('#total').val(formatearNumero(n));


    });

    /*Calcular el total*/
    jQuery(document).on("click", "input[name=domain-primary],input[name='domain-register[]'], input[name='hosting-register'], input[name='service-register[]']", function(event) { 

        if(jQuery("input[name=domain-primary]").length){
            var id_check = jQuery(this).val();
            var domain_p = id_check//jQuery("input[data-id="+id_check+"]");

           if(jQuery(this).is(':checked')){
               // domain_p.attr("disabled",false);
           }else{
               domain_p.prop('checked', false);
               // domain_p.attr("disabled",true);
           }
        }

        var subtotal=0;
        var total=0;
        var iva=0;

        jQuery('input[name="domain-register[]"]:checked, input[name="hosting-register"]:checked, input[name="service-register[]"]:checked').each(function(){
            subtotal+=parseFloat(jQuery(this).attr('data-price'));

        });

        var n = subtotal.toFixed(2);
        jQuery('#total').val(formatearNumero(n));

    });

    /**
     * Agregar a carrito de compra a través de API CoCart Woocommerce
     */

    /*jQuery("input[name=domain-primary]").click(function(){


        var id_check = jQuery(this).data('id');
        var check_domain = jQuery("input[value="+id_check+"]");
        check_domain.prop('checked', true);


    });*/
    /**
     * Para agregar servicios de dominios
     *
     */
    jQuery(document).on("click", "#add-order", function(event) {

            var domain;
            var period_year;
            var elemento;
            var group = 0;
            var checkDomain = jQuery('[name="domain-register[]"]:checked').map(function(){

                domain = jQuery(this).data('helper');
                elemento    = jQuery(this).parent().parent().parent();
                period_year = elemento.find(".period_year").val();
            
                
                return {'product_id': period_year,'quantity':1, 'variation_id': period_year, 'cart_item_data':{'addons':[{'name':'Nombre del Dominio','value':domain,'field_name': 'addon-'+this.value +'-nombre-del-dominio-0'}]}};

            }).get();
         


            if ( jQuery("#cart_item_key").length){
              
                   jQuery.ajax({
                    url: url_site + '/wp-json/tecnohost/v1/remove-to-cart',
                    data: JSON.stringify({
                        "cart_item_key" : jQuery("#cart_item_key").val()
                    }),
                    method: "DELETE",
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    complete: function (response) {
                  }
            }); 
                
            }
            

            var checkDomainJSON = JSON.stringify(checkDomain);


        if(!check_select_service()) {

            alert('Por favor, seleccione un servicio');

        }else if(!check_select_domain_primary() && jQuery('#options_service_input').val()=='h'){

            alert('Por favor, seleccione el dominio principal');

        }else if(jQuery("input:radio[name=hosting-register]")[0] && !check_select_hosting()) {

        alert('Por favor, seleccione un paquete de hosting');

        }else{
            
        if ( jQuery("#id_group").length){
                group = jQuery("#id_group").val();
            }

        jQuery.ajax({
           // url: url_site+ '/wp-json/cocart/v1/add-item?id_group='+group,
           url: url_site+ '/wp-json/tecnohost/v1/add-to-cart?id_group='+group,
            method: "POST",
            data: checkDomainJSON,
            contentType: "application/json; charset=utf-8",
            beforeSend: function () {
                jQuery('#add-order').text('Agregando a la Orden...');
                jQuery('.tecnohost-add-order').addClass('loading');
            },
            success: function (data) {
                /*Verificar el localstorage para detectar si existe algún servicio, de ser así deben eliminarse*/
                if("producto" in localStorage && "estado" in localStorage){
                    localStorage.removeItem("producto");
                    localStorage.removeItem("estado");
                }
                /*Verificamos si se trata de un combo de dominios y hosting*/
                if(jQuery('#options_service_input').length){

                   if(jQuery('#options_service_input').val()=='h' || jQuery('#options_service_input').val()=='ssl' || jQuery('#options_service_input').val()=='redirect'){
                      var domain = jQuery('input[name=domain-primary]:checked').val();
                       jQuery('#whois_domain').val(domain);
                       if(jQuery('#options_service_input').val()=='redirect'){
                           jQuery('#whois_domain_o').val(domain);
                       }
                       jQuery('form[name=domains]').submit();
                    }
                }else{
                    window.location.href = url_site + '/carrito/';
                }
            }
        });
        }

        });

    /**
     * Para agregar otros servicios
     *
     */
    jQuery(document).on("click", "#add-order-others", function(event) { 

        var domain_d, domain = jQuery("#domain").val();


        var checkDomain = jQuery('[name="service-register[]"]:checked').map(function(){

            if(jQuery('#domain_d').length){
                 domain_d = jQuery("#domain_d").val();
                return {'product_id':this.value,'quantity':1,'cart_item_data':{'addons':[{'name':'Nombre del Dominio','value':domain},{'name':'Destino','value':domain_d,'field_name': 'addon-'+this.value +'-destino-1'}]}};
            }else{
                return {'product_id':this.value,'quantity':1,'cart_item_data':{'addons':[{'name':'Nombre del Dominio','value':domain,'field_name': 'addon-'+this.value +'-nombre-del-dominio-0'}]}};
            }


        }).get();

        var checkDomainJSON = JSON.stringify(checkDomain);

        if(!check_select_service_ot()){
            alert('Por favor, seleccione un servicio');

        }else{
            jQuery.ajax({
                //url:url_site+ '/wp-json/cocart/v1/add-item',
                url:url_site+ '/wp-json/tecnohost/v1/add-to-cart',
                method: "POST",
                data: checkDomainJSON,
                contentType: "application/json; charset=utf-8",
                beforeSend: function () {
                    jQuery('#add-order-others').text('Agregando a la Orden...');
                    jQuery('.tecnohost-add-order').addClass('loading');
                },
                success:function(data){
                    /*Verificar el localstorage para detectar si existe algún servicio, de ser así deben eliminarse*/
                    if("producto" in localStorage && "estado" in localStorage){
                        localStorage.removeItem("producto");
                        localStorage.removeItem("estado");
                    }
                    window.location.href= url_site + '/carrito/';
                }

            });
        }


    });

    /**
     * Para agregar servicios de hosting
     *
     */
    
    jQuery(document).on("click", "#add-order-hosting", function(event) { 

            var domain = jQuery("#domain").val();
            
            var group = 0;
            
            var hostingId = jQuery('input:radio[name=hosting-register]:checked').val();

            var hosting = [{'product_id':hostingId,'quantity':1, 'cart_item_data':{'addons':[{'name':'Nombre del Dominio','value':domain,'field_name':'571-nombre-del-dominio-0',}]}}];

            var hostingJSON =JSON.stringify(hosting);

            if ( jQuery("#cart_item_key").length){
                   jQuery.ajax({
                    url: url_site + '/wp-json/tecnohost/v1/remove-to-cart',
                    data: JSON.stringify({
                        "cart_item_key" : jQuery("#cart_item_key").val()
                    }),
                    method: "DELETE",
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    complete: function (response) {
                  }
            }); 

                
            }
            
            if ( jQuery("#id_group").length){
                group = jQuery("#id_group").val();
            }

        if(!check_select_hosting()){
            alert('Por favor, seleccione un paquete de hosting');
        }else{
            jQuery.ajax({
                url: url_site+ '/wp-json/tecnohost/v1/add-to-cart?id_group='+group,
                method: "POST",
                data: hostingJSON,
                contentType: "application/json; charset=utf-8",
                beforeSend: function() {
                    jQuery('#add-order-hosting').text('Agregando a la Orden...');
                    jQuery('.tecnohost-add-order').addClass('loading');
                },
                success:function(data){
                    /*Verificar el localstorage para detectar si existe algún servicio, de ser así deben eliminarse*/
                    if("producto" in localStorage && "estado" in localStorage){
                        localStorage.removeItem("producto");
                        localStorage.removeItem("estado");
                    }
                    window.location.href= url_site + '/carrito/';
                }
            });
        }


    });



    /**
     *  Mostrar las sub-categorias
     */


            jQuery(document).on("click", "#others", function(event) { 

            jQuery('#container-categories-tecnohost').slideToggle("slow");
            jQuery('#others-form').show("slow");
        });


    jQuery(".back-categories").click(function(){

        jQuery("#others-form").slideToggle("slow");
        jQuery('#container-categories-tecnohost').show("slow");

    });



    /*Ventana modal para whois*/

    jQuery(document).on("click", ".open", function(event) { 

        var popup = jQuery(this).data('id');
        jQuery("#"+popup).fadeIn();
        // jQuery.ajax({
        //     url:'/wp-json/whois/tc/dominio',
        //     method: "POST",
        //     data: '{"dominio": "tecnosoluciones.com"}',
        //     contentType: "application/json; charset=utf-8",
        //     beforeSend: function() {
        //         jQuery(this).find('.preview').addClass('loading');
        //         jQuery(this).find('.preview').html('Obteniendo datos...');
        //     },
        // success:function(data){
        //         jQuery(this).find('.preview').removeClass('loading');
        //         jQuery(this).find('.preview').html(data);
        //
        //     }
        // });
        return false;
    });

            jQuery(document).on("click", ".close-whois", function(event) { 

        jQuery('.popup-whois').fadeOut();
        return false;
    });


});



/**
 * Cambio realizado para manejar múltiples años como variaciones
 * */

    jQuery(document).on("change", ".period_year", function(event) { 

    let variation_id = jQuery(this).val();
    let extention = jQuery(this).attr("data-extention");

    let elemento    = jQuery(this).parent().parent();
    let data_variations_json = elemento.find(".period").attr("data-variations");
    let obj_variations = JSON.parse(data_variations_json);
    let variationCount= obj_variations.length;

    obj_variations.forEach(function(value_,index_,array_){
        if(variation_id == value_['variation_id']) {
            elemento.find(".period").html(value_['price_html']);
            elemento.find(".period").attr("data-price", value_['display_price']);
        }
    });

        if(jQuery(this).attr("data-type") == 'avail'){

        let data_variations_total_json = elemento.find(".period_total").attr("data-price-cupon-total");

        let obj_variations_total = JSON.parse(data_variations_total_json);

        obj_variations_total.forEach(function(value_,index_,array_){

            if(variation_id == value_['variation_id']){
                elemento.find(".period_total").html(value_['price_html']);
                elemento.find(".period_total").attr("data-price", value_['price_html_plane']);
                elemento.find(".period").attr("data-price", value_['price_html_plane']);
            }
        });

    }

    if(jQuery(this).attr("data-type") == 'transf'){

        let data_variations_total_json = elemento.find(".tarifa_total").attr("data-tarifa-total");

        let obj_variations_total = JSON.parse(data_variations_total_json);

        obj_variations_total.forEach(function(value_,index_,array_){

            if(variation_id == value_['variation_id']){
                elemento.find(".tarifa_total").html(value_['price_html']);
                elemento.find(".tarifa_total").attr("data-price", value_['price_html_plane']);
                elemento.find(".period").attr("data-price", value_['price_html_plane']);
            }
        });

    }


    var subtotal=0;
    var total=0;
    var iva=0;

    jQuery('input[name="domain-register[]"]:checked, input[name="hosting-register"]:checked, input[name="service-register[]"]:checked').each(function(){
        subtotal+=parseFloat(jQuery(this).attr('data-price'));

    });

    var n = subtotal.toFixed(2);
    jQuery('#total').val(formatearNumero(n));

function formatearNumero(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? ',' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return x1 + x2;
    }
});

        jQuery(document).on("change", ".period_year_transf", function(event) { 

    let variation_id = jQuery(this).val();
    let extention = jQuery(this).attr("data-extention");

    let elemento    = jQuery(this).parent().parent();
    let data_variations_json = elemento.find(".period").attr("data-variations");
    let obj_variations = JSON.parse(data_variations_json);
    let variationCount= obj_variations.length;

    obj_variations.forEach(function(value_,index_,array_){
        if(variation_id == value_['variation_id']) {
            elemento.find(".period").html(value_['price_html']);
            elemento.find(".period").attr("data-price", value_['display_price']);
        }
    });

    if(jQuery(this).attr("data-type") == 'transf'){

        let data_variations_total_json = elemento.find(".tarifa_total").attr("data-tarifa-total");

        let obj_variations_total = JSON.parse(data_variations_total_json);

        obj_variations_total.forEach(function(value_,index_,array_){

            if(variation_id == value_['variation_id']){
                elemento.find(".tarifa_total").html(value_['price_html']);
                elemento.find(".tarifa_total").attr("data-price", value_['price_html_plane']);
                elemento.find(".period").attr("data-price", value_['price_html_plane']);
            }
        });

    }


    var subtotal=0;
    var total=0;
    var iva=0;

    jQuery('input[name="domain-register[]"]:checked, input[name="hosting-register"]:checked, input[name="service-register[]"]:checked').each(function(){
        subtotal+=parseFloat(jQuery(this).attr('data-price'));

    });

    var n = subtotal.toFixed(2);
    jQuery('#total').val(formatearNumero(n));

    function formatearNumero(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? ',' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return x1 + x2;
    }
    
    //Remover producto del carrito
    
     
})