
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

/*Producto tipo Servidores*/
jQuery(window).scroll(function(){

		        if(jQuery(window).scrollTop()>620){
		
		            jQuery('.order-summary').css('position', 'fixed');
		            jQuery('.order-summary').css('top', '70px');
		           //$('#scroller-right').css('position', 'fixed');
		           // $('#scroller-right').css('top', 0);
		        } else {
		           jQuery('.order-summary').css('position', 'relative');
		            jQuery('.order-summary').css('top', 0);
		            //$('#scroller-right').css('position', 'relative');
		           // $('#scroller-right').css('top', 0);
		        }
		    });
jQuery(".btn-check-domain").click(function(){
    
    var domain = jQuery("#domain-register-input").val();
    var tls = jQuery("#register-tld-input").val();
    var domainoption = jQuery("input[name='domainoption']:checked").val();
    
    
    if(domainValidate(domain) || domain=== "" ){
        jQuery("#domain-register-input").addClass('error-domain-check');
        jQuery("#result-domain").html("<div class='container-check-domain'><span class='domain-failed-message'>Por favor, ingrese un nombre de dominio sin extensión. </span></div>");
    }else{
        jQuery.ajax({
                url: WPURLS.ajaxUrl,
                method: "POST",
                data: {
                    action: "check_domain_server",
                    domain: domain,
                    tls: tls,
                    domainoption: domainoption
                },
                async: true,
                //contentType: "application/json; charset=utf-8",
                beforeSend: function() {
                    jQuery(".btn-check-domain").attr('disabled','disabled');
                    jQuery("#result-domain").html("<div class='container-loader-check-domain'><div class='loader'></div> <span>Verificando Disponibilidad...</span></div>");
                    
                },
                success:function(data){
                     jQuery(".btn-check-domain").removeAttr('disabled');
                     jQuery("#result-domain").html("<div class='container-check-domain'>"+data.data.message+"</div>");
                }
            });
    }
    
    
      
    
});

  jQuery(document).on("change", ".domain-period-select", function(event) { 

    let variation_id = jQuery(this).val();
    
    let price = jQuery("[data-"+variation_id+"]").data(variation_id);
    jQuery(".dynamic-price").html(price);
  });

  jQuery(document).on("click", ".btn-success-domain", function(event) { 
    var domain = jQuery("#domain-register-input").val();
    var tls = jQuery("#register-tld-input").val();
    var domainoption = jQuery("input[name='domainoption']:checked").val();
    var period_year = jQuery(".domain-period-select").find(':selected').val();

    domain_full = domain + tls;
    
    domain_type = 'Niguno';
    if(domainoption == '1'){
        domain_type = 'Registro';
    }else if(domainoption == '0'){
        domain_type = 'Transferencia';
    }
    var checkDomainJSON = JSON.stringify([{'product_id': period_year,'quantity':1, 'variation_id': period_year, 'cart_item_data':{'addons':[{'name': 'Operación', 'value': domain_type},{'name':'Nombre del Dominio','value':domain_full,'field_name': 'addon-'+ period_year +'-nombre-del-dominio-0'}]}}]);

      jQuery.ajax({
                url: WPURLS.ajaxUrl,
                method: "POST",
                data: {
                    action: "iniciarl_proceso",
                    datos: checkDomainJSON
                },
                async: true,
                beforeSend: function() {
                    jQuery(".btn-success-domain").attr('disabled','disabled');
                    jQuery(".btn-success-domain").html("<div class='loader'></div> <span>Agregando Dominio...</span>");
                    
                },
                success:function(data){
                    jQuery("input[name*='dominio']").val(domain + tls);
                    jQuery("input[name*='dominio']").prop('readonly', true);

                     jQuery(".btn-check-domain").removeAttr('disabled');
                     jQuery(".step-domain").fadeOut();
                     jQuery(".step-server").fadeIn();
                }
            });
    
});
    function calculate_total(){
        var recurrencia = jQuery("#select-recuerrencia-server").find(':selected').data('recurrencia');
        var price1 = jQuery("#select-recuerrencia-server").find(':selected').data('price');
        var price2 = jQuery("#select-recuerrencia-server").find(':selected').data('period');
        var addons = 0;
        
        jQuery(".item-price").each(function (i) {
              addons = parseFloat(addons) + parseFloat(jQuery(this).attr('data-price'))
          });
        

        var subtotal = price1 +  addons*recurrencia;
        var total = price1 + price2 + (addons*recurrencia);
        
        jQuery("#price-server-variations").html(formatearNumero(subtotal.toFixed(2)))

        jQuery("#total-server").html(formatearNumero(total.toFixed(2)));
        
   }
  jQuery(document).on("change", ".calculate-total", function(event) { 
      
      var recurrencia = jQuery("#select-recuerrencia-server").find(':selected').data('recurrencia');
      //Mensualidad
    var price_html = jQuery("#select-recuerrencia-server").find(':selected').data('price-html');
    var price = jQuery("#select-recuerrencia-server").find(':selected').data('price');
    jQuery("#price-server-variations").html(price_html);
    jQuery("#price-server-variations").attr('data-price', price);
    
    //configuración inicial
    var period_html = jQuery("#select-recuerrencia-server").find(':selected').data('period-html');
    var period = jQuery("#select-recuerrencia-server").find(':selected').data('period');
    jQuery("#setup-server").html(period_html);
    
    
    
    //Label recurrencia
    switch(recurrencia){
                                             
        case 1:
          recurrencia = 'Mensual';
         break;
        case 3:
          recurrencia = 'Trimestral';
         break;
                 
        case 6:
          recurrencia = 'Semestral';
         break;
                 
        case 12:
          recurrencia = 'Anual';
         break;
        
        default:
          recurrencia = 'Mensual';
         break;                              
     }
             jQuery("#pago-recurrencia-label").html(recurrencia)

    
    
    //Asignar variación
    jQuery("input[name=variation_id]").val(jQuery("#select-recuerrencia-server").find(':selected').val())
    
    //Recalcular
    calculate_total();
    
    
  });
  
  jQuery(document).on("change", ".wc-pao-addon-select", function(event) { 

    var prices = jQuery(this).find(':selected').data('price');
    if(prices === ''){
      return 0;
    }
    if(jQuery(this).val()==''){
        prices = 0;
        jQuery('.'+jQuery(this).attr('id')).html(formatearNumero(prices.toFixed(2)))
        jQuery('.'+jQuery(this).attr('id')).attr('data-price', 0)
    }
    else{
        jQuery('.'+jQuery(this).attr('id')).html(formatearNumero(prices.toFixed(2)))
        jQuery('.'+jQuery(this).attr('id')).attr('data-price', prices)
    }
    
 calculate_total();
  });
  
  jQuery(document).on("click", ".wc-pao-addon-checkbox", function(event) { 

    var price = jQuery(this).data('price');

    if(jQuery(this).is(":checked")){
        jQuery('.'+jQuery(this).attr('id')).html(formatearNumero(price.toFixed(2)))
        jQuery('.'+jQuery(this).attr('id')).attr('data-price', price)
    }else{
        price = 0;
        jQuery('.'+jQuery(this).attr('id')).html(formatearNumero(price.toFixed(2)))
        jQuery('.'+jQuery(this).attr('id')).attr('data-price', price)
    }
    calculate_total();
  });
  
  //Agregar al carrito
  jQuery(document).on("click", ".btn-success-server", function(event) { 
      event.preventDefault();
     jQuery(this).prop('disabled', true);
    /*var complemento = jQuery("input.complemento-adicional:checked").map(function(){
                return {'product_id': jQuery(this).val(), 'quantity': 1};
            }).get();*/
         var complemento = jQuery("input.complemento-adicional:checked").val();
       
    var complementoJSON = JSON.stringify(complemento);

    if(jQuery("input.input-text").val()!=''){
        jQuery.ajax({
                url: WPURLS.ajaxUrl,
                method: "POST",
                data: {
                    action: "iniciarl_proceso",
                    datos: complementoJSON
                },
                async: false,
                success:function(data){
                jQuery(this).prop('disabled', false);
                jQuery('form.variations_form').submit();

                }
            });
    }
    
    
    
      
    
});


/*Producto tipo Servidores*/

jQuery("#clear-cart").click(function(){
          jQuery.ajax({
            url: url_site+'/wp-json/cocart/v1/item',
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


          /*  if ( jQuery("#cart_item_key").length){
                for(var i =0; i<6;i++){
                   jQuery.ajax({
                    url: url_site + '/wp-json/cocart/v1/item',
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
                
            }*/

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
            url: WPURLS.ajaxUrl+'?id_group='+group,
            method: "POST",
            data: {
                action: "iniciarl_proceso",
                datos: checkDomainJSON
            },
            async: true,
            //contentType: "application/json; charset=utf-8",
            beforeSend: function () {
                jQuery('#add-order').text('Agregando a la Orden...');
                jQuery('.tecnohost-add-order').addClass('loading');
            },
            success: function (data) {
                /!*Verificar el localstorage para detectar si existe algún servicio, de ser así deben eliminarse*!/
                if("producto" in localStorage && "estado" in localStorage){
                    localStorage.removeItem("producto");
                    localStorage.removeItem("estado");
                }
                /!*Verificamos si se trata de un combo de dominios y hosting*!/
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
            if ( jQuery("#id_group").length){
                group = jQuery("#id_group").val();
            }
            jQuery.ajax({
                url: WPURLS.ajaxUrl,
                method: "POST",
                data: {
                    action: "iniciarl_proceso",
                    datos: checkDomainJSON
                },
                async: true,
                //contentType: "application/json; charset=utf-8",
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

           /* if ( jQuery("#cart_item_key").length){
                for(var i =0; i<6;i++){
                   jQuery.ajax({
                    url: url_site + '/wp-json/cocart/v1/item',
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
                
            }*/
            
            if ( jQuery("#id_group").length){
                group = jQuery("#id_group").val();
            }

        if(!check_select_hosting()){
            alert('Por favor, seleccione un paquete de hosting');
        }else{
            jQuery.ajax({
                url: WPURLS.ajaxUrl+'?id_group='+group,
                method: "POST",
                data: {
                    action: "iniciarl_proceso",
                    datos: hostingJSON
                },
                async: true,
                //contentType: "application/json; charset=utf-8",
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

function validar_cambio_vps(){
    let metas = JSON.parse(jQuery("form#cambio_servidor").attr("data-meta"));
    let product_id = jQuery('input[name="product_id"]').val();
    for ([key, value] of Object.entries(metas)) {
        switch (key){
            case"pa_plan":
                pa_plan = jQuery("select#select-recuerrencia-server option[data-tipo='"+value+"']");
                pa_plan.attr("selected", true).change();
                jQuery("button.btn-success-server.single_add_to_cart_button").attr("disabled","disabled");
            break;
            case"Hostname":
                hostaname = jQuery("input#addon-"+product_id+"-hostname-1");
                hostaname.val(value);
                hostaname.attr("value",value);
            break;
            case"Dominio":
            case"Nombre del Dominio":
                dominio = jQuery("input#addon-"+product_id+"-dominio-2");
                dominio.val(value);
                dominio.attr("value",value);
            break;
            case"Prefijo NS1":
                nsone = jQuery("input#addon-"+product_id+"-prefijo-ns1-3");
                nsone.val(value);
                nsone.attr("value",value);
            break;
            case"Prefijo NS2":
                nstwo = jQuery("input#addon-"+product_id+"-prefijo-ns2-4");
                nstwo.val(value);
                nstwo.attr("value",value);
            break;
            case"Sistema operativo":
                sis_opera = jQuery(".wc-pao-addon-"+key.replace(" ", "-").toLowerCase()+" select option[data-label='"+value+"']");
                sis_opera.attr("selected", true).change();
            break;
            case"Panel de Hosting WHM/cPanel":
                panl_hosting = jQuery(".wc-pao-addon-panel-de-hosting-whm-cpanel select option[data-label='"+value+"']");
                panl_hosting.attr("selected", true).change();
            break;
            case"Servidor Web LiteSpeed":
                servi_litespee = jQuery(".wc-pao-addon-servidor-web-litespeed select option[data-label='"+value+"']");
                servi_litespee.attr("selected", true).change();
            break;
            case"Filtro de SpamExperts":
                filtro_spam = jQuery(".wc-pao-addon-filtro-de-spamexperts select option[data-label='"+value+"']");
                filtro_spam.attr("selected", true).change();
            break;
            case"Instalador de Aplicaciones Adicional":
                insntalad_app_add = jQuery(".wc-pao-addon-instalador-de-aplicaciones-adicional select option[data-label='"+value+"']");
                insntalad_app_add.attr("selected", true).change();
            break;
            case"Gestor de Temas Gráficos para cPanel":
                insntalad_app_add = jQuery(".wc-pao-addon-gestor-de-temas-graficos-para-cpanel select option[data-label='"+value+"']");
                insntalad_app_add.attr("selected", true).change();
            break;
            case"Software de Soporte y Facturación":
                insntalad_app_add = jQuery(".wc-pao-addon-software-de-soporte-y-facturacion select option[data-label='"+value+"']");
                insntalad_app_add.attr("selected", true).change();
            break;
            case"Sistema Operativo (Mensual)":
                sist_ope_men = jQuery(".wc-pao-addon-sistema-operativo-mensual select option[data-label='"+value+"']");
                sist_ope_men.attr("selected", true).change();
            break;
            case"Respaldo Adicional (Mensual)":
                respal_adi_men = jQuery(".wc-pao-addon-respaldo-adicional-mensual select option[data-label='"+value+"']");
                respal_adi_men.attr("selected", true).change();
            break;
            case"Servidor Web LiteSpeed (Mensual)":
                ser_lite_men = jQuery(".wc-pao-addon-servidor-web-litespeed-mensual select option[data-label='"+value+"']");
                ser_lite_men.attr("selected", true).change();
            break;
            case"Panel de Hosting WHM/cPanel (Mensual)":
                panel_host_mens = jQuery(".wc-pao-addon-panel-de-hosting-whm-cpanel-mensual select option[data-label='"+value+"']");
                panel_host_mens.attr("selected", true).change();
            break;
            case"Memoria RAM Adicional (Mensual)":
                mem_ram_men = jQuery(".wc-pao-addon-memoria-ram-adicional-mensual select option[data-label='"+value+"']");
                mem_ram_men.attr("selected", true).change();
            break;
            case"Gestión de Servicios (Mensual)":
                gesti_ser_men = jQuery(".wc-pao-addon-gestion-de-servicios-mensual select option[data-label='"+value+"']");
                gesti_ser_men.attr("selected", true).change();
            break;
            case"Certificado SSL Adicional (Mensual)":
                cert_ssl_men = jQuery(".wc-pao-addon-certificado-ssl-adicional-mensual input");
                cert_ssl_men.click();
            break;
            case"Microsoft SQL Web":
                insntalad_app_add = jQuery(".wc-pao-addon-microsoft-sql-web input");
                insntalad_app_add.click();
            break;
            case"Certificado SSL Adicional":
                insntalad_app_add = jQuery(".wc-pao-addon-certificado-ssl-adicional input");
                insntalad_app_add.click();
            break;
            case"Monitor de URL":
                moni_url = jQuery(".wc-pao-addon-monitor-de-url input");
                moni_url.click();
            break;
            case"Dirección IP Adicional":
                direc_ip_ad = jQuery(".wc-pao-addon-direccion-ip-adicional input");
                direc_ip_ad.click();
            break;
            case"Ancho de Banda Adicional - 1TB":
                anch_band_onetb = jQuery(".wc-pao-addon-ancho-de-banda-adicional-1tb input");
                anch_band_onetb.click();
            break;
            case"Gestión de Servicios":
                gest_serv = jQuery(".wc-pao-addon-gestion-de-servicios input");
                gest_serv.click();
            break;
            case"Espacio en Disco (VPS) - 10 GB":
                ten_gb_vps_dis = jQuery(".wc-pao-addon-espacio-en-disco-vps-10-gb input");
                ten_gb_vps_dis.click();
            break;
        }
    }
    for ([key, value] of Object.entries(metas)) {
        switch (key){
            case"pa_plan":
                pa_plan = jQuery("select#select-recuerrencia-server option[data-tipo='"+value+"']");
                pa_plan.attr("disabled","disabled");
                break;
            case"Hostname":
                hostaname = jQuery("input#addon-"+product_id+"-hostname-1");
                hostaname.attr("style","pointer-events: none;");
                break;
            case"Dominio":
            case"Nombre del Dominio":
                dominio = jQuery("input#addon-"+product_id+"-dominio-2");
                dominio.attr("style","pointer-events: none;");
                break;
            case"Prefijo NS1":
                nsone = jQuery("input#addon-"+product_id+"-prefijo-ns1-3");
                nsone.attr("style","pointer-events: none;");
                break;
            case"Prefijo NS2":
                nstwo = jQuery("input#addon-"+product_id+"-prefijo-ns2-4");
                nstwo.attr("style","pointer-events: none;");
                break;
            case"Sistema operativo":
                jQuery('button.btn-success-server.single_add_to_cart_button').removeAttr('disabled');
                sis_opera = jQuery(".wc-pao-addon-"+key.replace(" ", "-").toLowerCase()+" select option[data-label='"+value+"']");
                sis_opera.attr("style","pointer-events: none;");
                break;
            case"Panel de Hosting WHM/cPanel":
                panl_hosting = jQuery(".wc-pao-addon-panel-de-hosting-whm-cpanel select option[data-label='"+value+"']");
                panl_hosting.attr("style","pointer-events: none;");
                break;
            case"Servidor Web LiteSpeed":
                servi_litespee = jQuery(".wc-pao-addon-servidor-web-litespeed select option[data-label='"+value+"']");
                servi_litespee.attr("style","pointer-events: none;");
                break;
            case"Filtro de SpamExperts":
                filtro_spam = jQuery(".wc-pao-addon-filtro-de-spamexperts select option[data-label='"+value+"']");
                filtro_spam.attr("style","pointer-events: none;");
                break;
            case"Instalador de Aplicaciones Adicional":
                insntalad_app_add = jQuery(".wc-pao-addon-instalador-de-aplicaciones-adicional select option[data-label='"+value+"']");
                insntalad_app_add.attr("style","pointer-events: none;");
                break;
            case"Gestor de Temas Gráficos para cPanel":
                insntalad_app_add = jQuery(".wc-pao-addon-gestor-de-temas-graficos-para-cpanel select option[data-label='"+value+"']");
                insntalad_app_add.attr("style","pointer-events: none;");
                break;
            case"Software de Soporte y Facturación":
                insntalad_app_add = jQuery(".wc-pao-addon-software-de-soporte-y-facturacion select option[data-label='"+value+"']");
                insntalad_app_add.attr("style","pointer-events: none;");
            break;
            case"Sistema Operativo (Mensual)":
                sist_ope_men = jQuery(".wc-pao-addon-sistema-operativo-mensual select option[data-label='"+value+"']");
                sist_ope_men.attr("style","pointer-events: none;");
            break;
            case"Respaldo Adicional (Mensual)":
                respal_adi_men = jQuery(".wc-pao-addon-respaldo-adicional-mensual select option[data-label='"+value+"']");
                respal_adi_men.attr("style","pointer-events: none;");
            break;
            case"Servidor Web LiteSpeed (Mensual)":
                ser_lite_men = jQuery(".wc-pao-addon-servidor-web-litespeed-mensual select option[data-label='"+value+"']");
                ser_lite_men.attr("style","pointer-events: none;");
            break;
            case"Panel de Hosting WHM/cPanel (Mensual)":
                panel_host_mens = jQuery(".wc-pao-addon-panel-de-hosting-whm-cpanel-mensual select option[data-label='"+value+"']");
                panel_host_mens.attr("style","pointer-events: none;");
            break;
            case"Memoria RAM Adicional (Mensual)":
                mem_ram_men = jQuery(".wc-pao-addon-memoria-ram-adicional-mensual select option[data-label='"+value+"']");
                mem_ram_men.attr("style","pointer-events: none;");
            break;
            case"Gestión de Servicios (Mensual)":
                gesti_ser_men = jQuery(".wc-pao-addon-gestion-de-servicios-mensual select option[data-label='"+value+"']");
                gesti_ser_men.attr("style","pointer-events: none;");
            break;
        }
    }
    jQuery("#loader").css("display","none");
}

function validar_cambio_precio(){
    jQuery("#loader").show();
    total_cambi = jQuery('span#total-server');
    setTimeout(function(){
        total_cambi.text(jQuery("span#price-server-variations").text());
        jQuery("#loader").hide();
    }, 500,total_cambi);
}


