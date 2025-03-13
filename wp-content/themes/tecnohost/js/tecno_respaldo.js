
jQuery(function(){
    var URLdomain = window.location.host;
    var prot = location.protocol;
    var URLactual = window.location.pathname;

    jQuery.ajax({
        type:'post',
        url: prot+'//'+ URLdomain + '/wp-json/moneda_en_uso/v1/moneda',
        data: {},
        success: function(resultado){
            if(resultado === "COP"){
                jQuery('select#wcj-currency-select option[value="COP"]').attr("selected", true);
            }
            else{
                jQuery('select#wcj-currency-select option[value="USD"]').attr("selected", true);
            }
        }
    });

    jQuery("div#img_Char_bar img").attr("onclick", "return lh_inst.lh_openchatWindow()");

    if(document.referrer.slice(8,17) != "tecnohost" && document.referrer.length != 0){
        localStorage.setItem("refe-ridor",document.referrer);
    }else{
        if(localStorage.getItem("refe-ridor") === document.referrer.slice(8,17)){
            localStorage.setItem("refe-ridor","https://tecnohost.net/");
        }
        else{
            localStorage.setItem("refe-ridor",document.referrer);
        }
    }

    jQuery("#col_was a").click(function(){
        localStorage.setItem("pais","Colombia");
        localStorage.setItem("path",URLactual);
    });

    jQuery("#usa_was a").click(function(){
        localStorage.setItem("pais","United States");
        localStorage.setItem("path",URLactual);
    });

    jQuery("#ven_was a").click(function(){
        localStorage.setItem("pais","Venezuela, Bolivarian Republic Of");
        localStorage.setItem("path",URLactual);
    });

    if(URLactual == "/") {
        jQuery("section#panl_rev").hide();
        jQuery("div#pl_hosp a").removeAttr("href");
        jQuery("div#pl_rev a").removeAttr("href");
        //jQuery("div#pl_hosp a").attr("id","active");

        jQuery("div#pl_rev").click(function(){
            jQuery("div#pl_hosp a").attr("id","active");
            jQuery("div#pl_rev a").attr("id","active");
            jQuery("section#panl_hosp").hide();
            jQuery("section#panl_rev").show();
        });

        jQuery("div#pl_hosp").click(function(){
            jQuery("div#pl_rev a").removeAttr("id");
            jQuery("div#pl_hosp a").removeAttr("id");
            jQuery("section#panl_hosp").show();
            jQuery("section#panl_rev").hide();
        });

    }

    if(URLactual == "/dominios/" || URLactual == "/hosting/") {
        jQuery("section#panl_rev").hide();
        jQuery("div#pl_hosp a").removeAttr("href");
        jQuery("div#pl_rev a").removeAttr("href");
        //jQuery("div#pl_hosp a").attr("id","active");

        jQuery("div#pl_rev").click(function(){
            jQuery("div#pl_hosp a").attr("id","active");
            jQuery("div#pl_rev a").attr("id","active");
            jQuery("section#panl_hosp").hide();
            jQuery("section#panl_rev").show();
        });

        jQuery("div#pl_hosp").click(function(){
            jQuery("div#pl_rev a").removeAttr("id");
            jQuery("div#pl_hosp a").removeAttr("id");
            jQuery("section#panl_hosp").show();
            jQuery("section#panl_rev").hide();
        });

    }

    if(URLactual == "/carrito/"){
        jQuery("td.product-thumbnail a").removeAttr("href");
     //   jQuery("td.product-name a").removeAttr("href");
    }

    if(URLactual == "/whatsapp-contacto/"){
        console.log(localStorage.getItem("pais"));
        console.log(localStorage.getItem("exitoso"));
        console.log(localStorage.getItem("path"));
        console.log(document.referrer);
        var paisva = localStorage.getItem("pais");
        var exitoso = localStorage.getItem("exitoso");
        var opcion = localStorage.getItem("opcion");
        if(exitoso == 1){
            var formulario = document.getElementById("w");
            var datoa = formulario[0];

            if (datoa.value=="enviar"){
                //formulario.submit();
                return true;
            } else {
                if(paisva == "Colombia"){
                    console.log(paisva);
                    localStorage.removeItem("pais");
                    localStorage.removeItem("path");
                    localStorage.removeItem("exitoso");
                    if(opcion == "col_uno"){
                        location.href =localStorage.getItem("ruta");
                        window.location.replace(localStorage.getItem("ruta"));
                    }
                    else if(opcion == "col_dos"){
                        location.href =localStorage.getItem("ruta");
                        window.location.replace(localStorage.getItem("ruta"));
                    }
                }
                if(paisva == "United States"){
                    localStorage.removeItem("pais");
                    localStorage.removeItem("path");
                    localStorage.removeItem("exitoso");
                    if(opcion == "usa_uno"){
                        location.href =localStorage.getItem("ruta");
                        window.location.replace(localStorage.getItem("ruta"));
                    }
                }
                if(paisva == "Venezuela, Bolivarian Republic Of"){
                    localStorage.removeItem("pais");
                    localStorage.removeItem("path");
                    localStorage.removeItem("exitoso");
                    if(opcion == "ven_uno"){
                        location.href =localStorage.getItem("ruta");
                        window.location.replace(localStorage.getItem("ruta"));
                    }
                    else if(opcion == "ven_dos"){
                        location.href =localStorage.getItem("ruta");
                        window.location.replace(localStorage.getItem("ruta"));
                    }
                }
                return false;
            }
            localStorage.removeItem("exitoso");
        }

        jQuery("#refe-ridor").val(localStorage.getItem("refe-ridor"));
        jQuery("#refe-ridor").attr("value", localStorage.getItem("refe-ridor"));

        jQuery("#pais").val(localStorage.getItem("pais"));
        jQuery("#pais").attr("value", localStorage.getItem("pais"));

        jQuery("button[name='mauticform[submit]']").click(function(){
            if(jQuery('#element_10').is(':checked')){
                var formulario = document.getElementById("w");
                var datoa = formulario[0];

                if (datoa.value=="enviar"){
                    //formulario.submit();
                    //return true;
                } else {
                    var URLdomain = window.location.host;
                    var prot = location.protocol;
                    var pathname = localStorage.getItem("path");
                    var correo_mauticform = document.getElementsByName("correousuario");
                    var referidor = localStorage.getItem("refe-ridor");
                    var nombre = document.getElementsByName("mauticform[nombre]");
                    var apellido = document.getElementsByName("mauticform[apellido]");
                    var correo = document.getElementsByName("mauticform[correo_electronico]");
                    var telefono = document.getElementsByName("mauticform[telefono_de_contacto]");
                    var pais = localStorage.getItem("pais"); // campo presneten en la landing page de https://tecnosoluciones.com/index.php/libro/

                    if(correo_mauticform[0].value == ""){
                        jQuery("div#boton button").attr("class", "cargando");
                        jQuery("form.appnitro").removeAttr("id");
                        jQuery("div#boton button").html("");
                        jQuery("div#boton button").append("Procesando <img src='https://tecnosoluciones.com/wp-content/uploads/2019/01/GIF-Cargando_Portal-TS-2018_VERDE.gif'/>");
                        jQuery.ajax({
                            type:'post',
                            url: prot+'//'+ URLdomain + '/registro_blog.php',
                            data: {
                                nombre : nombre[0].value,
                                apellido : apellido[0].value,
                                telefono : telefono[0].value,
                                correo : correo[0].value,
                                pais : pais,
                                referidor : referidor,
                                servicio : pathname,
                                url : jQuery(location).attr('href'),
                                tecnosoluciones : 2,
                                correo_mauticform: correo[0].value
                            },
                            success: function(resultado){
                                if(resultado == 1){
                                    localStorage.setItem("exitoso","1");
                                    if(paisva == "Colombia"){
                                        var reenviar = 'https://api.whatsapp.com/send?phone=573148286676&text=Hola,%20mi%20nombre%20es%20'+nombre[0].value+'%20'+apellido[0].value+',%20mi%20correo%20es%20'+correo[0].value+',%20he%20visto%20sus%20servicios%20en%20tecnohost.net%20y%20quisiera%20hacerles%20esta%20pregunta%20para%20su%20empresa%20en%20Colombia:';
                                    }
                                    if(paisva == "United States"){
                                        var reenviar = 'https://api.whatsapp.com/send?phone=12094183266&text=Hola,%20mi%20nombre%20es%20'+nombre[0].value+'%20'+apellido[0].value+',%20mi%20correo%20es%20'+correo[0].value+',%20he%20visto%20sus%20servicios%20en%20tecnohost.net%20y%20quisiera%20hacerles%20esta%20pregunta%20para%20su%20empresa%20en%20USA:%20';
                                    }
                                    if(paisva == "Venezuela, Bolivarian Republic Of") {
                                        var reenviar = 'https://api.whatsapp.com/send?phone=584143456865&text=Hola,%20mi%20nombre%20es%20'+nombre[0].value+'%20'+apellido[0].value+',%20mi%20correo%20es%20'+correo[0].value+',%20he%20visto%20sus%20servicios%20en%20tecnohost.net%20y%20quisiera%20hacerles%20esta%20pregunta%20para%20su%20empresa%20en%20Venezuela:%20';
                                    }
                                    localStorage.setItem("ruta", reenviar);
                                    localStorage.removeItem("refe-ridor");
                                    //formulario.submit();
                                    return true;
                                }
                                else{
                                    localStorage.setItem("exitoso","0");
                                    return false;
                                }
                            }
                        });
                    }
                    else{
                        return false;
                    }
                    //return false;
                }
            }else{
                alert("Disculpe debe aceptar los terminos y condiciones para poder enviar el formulario");
            }
        });
    }

    if(URLactual == "/pagina-de-respuesta-epayco/"){
        var estado = getParameterByName('x_transaction_state');

        if(estado){
            switch (estado) {
                default:

                    var orden = getParameterByName('x_id_factura');
                    var refe_re = getParameterByName('x_ref_payco');
                    var descripción = getParameterByName('x_description');
                    var total = getParameterByName('x_amount_ok');
                    var moneda = getParameterByName('x_currency_code');
                    var entidad = getParameterByName('x_franchise');
                    jQuery.ajax({
                        type:'post',
                        url: prot+'//'+ URLdomain + '/wp-json/respuesta_orden/v1/orden_epayco',
                        data: {id_orden: orden, referencia: refe_re, estado: estado, descripción : descripción, total : total, moneda : moneda, entidad: entidad},
                        success: function(resultado){
                            console.log(resultado);
                            jQuery("#resp_epayco").html("");
                            jQuery("#resp_epayco").append(resultado);
                        }
                    });
                    break;
            }
        }
        else{
            let mensaje = "<div id='msn_alert_epayco'>" +
                "<img src="+prot+"//"+URLdomain+"/wp-content/uploads/2019/02/dog.jpg' " +
                "class='attachment-full size-full' alt='' srcset='"+prot+"//"+URLdomain+"/wp-content/uploads/2019/02/dog.jpg 914w, " +
                ""+prot+"//"+URLdomain+"/wp-content/uploads/2019/02/dog-300x113.jpg 300w, "+prot+"//"+URLdomain+"/wp-content/uploads/2019/02/dog-768x290.jpg 768w, " +
                ""+prot+"//"+URLdomain+"/wp-content/uploads/2019/02/dog-416x157.jpg 416w' " +
                "sizes='(max-width: 914px) 100vw, 914px' width='914' height='345'>" +
                "<h1>Algo va mal ponte en contacto con el administrador de tecnohost para ayudarte!</h1>" +
                "</div>";
            jQuery("#resp_epayco").html("");
            jQuery(".elementor-2253").css("background-color", "#eee")
            jQuery("#resp_epayco").append(mensaje);
        }
    }

    if(URLactual == "/lostpassword/"){
        jQuery("div#tb-logo-container").hide()
        jQuery("div#login-left").hide()
        jQuery("div#login-title").hide()
        jQuery("div#login-top").hide()
        jQuery("ul.tml-links").hide()
    }

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    jQuery("#men_pa nav ul.sub-menu li a").click(function(){
        var val_clas = jQuery(this)[0].children[0].classList[1];
        var val_id = jQuery(this)[0].children[0];
        var num = jQuery(this)[0];

        if(val_clas == "fa-whatsapp"){
            if(jQuery(val_id).attr('id') == "usa_uno"){
                localStorage.setItem("pais","United States");
            }
            else if(jQuery(val_id).attr('id') == "col_uno" || jQuery(val_id).attr('id') == "col_dos"){
                localStorage.setItem("pais","Colombia");
            }
            else if(jQuery(val_id).attr('id') == "ven_uno" || jQuery(val_id).attr('id') == "ven_dos"){
                localStorage.setItem("pais","Venezuela, Bolivarian Republic Of");
            }

            localStorage.setItem("opcion",jQuery(val_id).attr('id'));

            localStorage.setItem("path","/contacto/");
        }
    });

    jQuery("#men_pa_m nav ul.sub-menu li a").click(function(){
        var val_clas = jQuery(this)[0].children[0].classList[1];
        var val_id = jQuery(this)[0].children[0];
        var num = jQuery(this)[0];

        if(val_clas == "fa-whatsapp"){
            if(jQuery(val_id).attr('id') == "usa_uno"){
                localStorage.setItem("pais","United States");
            }
            else if(jQuery(val_id).attr('id') == "col_uno" || jQuery(val_id).attr('id') == "col_dos"){
                localStorage.setItem("pais","Colombia");
            }
            else if(jQuery(val_id).attr('id') == "ven_uno" || jQuery(val_id).attr('id') == "ven_dos"){
                localStorage.setItem("pais","Venezuela, Bolivarian Republic Of");
            }

            localStorage.setItem("opcion",jQuery(val_id).attr('id'));

            localStorage.setItem("path","/Contaco/");
        }
    });

    jQuery("a#transf").click(function(){
        localStorage.removeItem("producto");
        localStorage.removeItem("estado");
        localStorage.setItem("producto",jQuery(this).parent().parent().children("h2").text());
        localStorage.setItem("estado","transferencia");
        console.log(localStorage.getItem("producto"));
        console.log(localStorage.getItem("estado"));
    });

    jQuery("a#regist").click(function(){
        localStorage.removeItem("producto");
        localStorage.removeItem("estado");
        localStorage.setItem("producto",jQuery(this).parent().parent().children("h2").text());
        localStorage.setItem("estado","registro");
        console.log(localStorage.getItem("producto"));
        console.log(localStorage.getItem("estado"));
    });

    jQuery("a#cat").click(function(){
        localStorage.removeItem("producto");
        localStorage.removeItem("estado");
        localStorage.setItem("producto",jQuery(this).parent().parent().children("h2").text());
        localStorage.setItem("estado","hosting");
        console.log(localStorage.getItem("producto"));
        console.log(localStorage.getItem("estado"));
    });

    if(URLactual.substr(0,11) == "/mi-cuenta/"){
        jQuery("td.woocommerce-table__product-name.product-name a").removeAttr("href");
        //jQuery("td.product-name a").removeAttr("href");
        jQuery("p.order-again a").attr("href", "/contratacion");
    }

    jQuery("div#btn_bsc div.elementor-icon").click(function(){
        if(jQuery("div#btn_bsc div.elementor-icon").attr('id') == "activo"){
            jQuery("section#sec_searc").hide();
            jQuery("div#btn_bsc div.elementor-icon").attr("id","");
        }
        else {
            jQuery("section#sec_searc").show();
            jQuery("div#btn_bsc div.elementor-icon").attr("id", "activo");
        }
    });

    jQuery("div#activo").click(function(){
        jQuery("section#sec_searc").hide();
        jQuery("div#btn_bsc div.elementor-icon").attr("id","");
    });

    jQuery("section#sec_searc").hover(function(){

    }, function(){
        jQuery("section#sec_searc").hide();
        jQuery("div#btn_bsc div.elementor-icon").attr("id","");
    });

    var paises = jQuery("div#men_pa nav.elementor-nav-menu--main ul.elementor-nav-menu li.menu-item-has-children");

    for(var i=0; i<paises.length;i++){
        if(parseInt(i) == 0){
            var imagen = "<img src='https://tecnosoluciones.com/wp-content/uploads/2018/09/1PORTADA_TS-2018_09.png' id='img_pais' alt='' title=''/>";
        }else if(parseInt(i) == 1){
            var imagen = "<img src='https://tecnosoluciones.com/wp-content/uploads/2018/09/1PORTADA_TS-2018_11.png' id='img_pais' alt='' title=''/>";
        }else if(parseInt(i) == 2){
            var imagen = "<img src='https://tecnosoluciones.com/wp-content/uploads/2018/09/1PORTADA_TS-2018_13.png' id='img_pais' alt='' title=''/>";
        }
        jQuery(jQuery(jQuery("div#men_pa nav.elementor-nav-menu--main ul.elementor-nav-menu li.menu-item-has-children")[i])[0].children[0]).text("");
        jQuery(jQuery(jQuery("div#men_pa nav.elementor-nav-menu--main ul.elementor-nav-menu li.menu-item-has-children")[i])[0].children[0]).append(imagen);
    }

    var paises = jQuery("div#men_pa_m nav.elementor-nav-menu--dropdown ul.elementor-nav-menu li.menu-item-has-children");

    for(var i=0; i<paises.length;i++){
        if(parseInt(i) == 0){
            var imagen = "<img src='https://tecnosoluciones.com/wp-content/uploads/2018/09/1PORTADA_TS-2018_09.png' id='img_pais' alt='' title=''/>";
        }else if(parseInt(i) == 1){
            var imagen = "<img src='https://tecnosoluciones.com/wp-content/uploads/2018/09/1PORTADA_TS-2018_11.png' id='img_pais' alt='' title=''/>";
        }else if(parseInt(i) == 2){
            var imagen = "<img src='https://tecnosoluciones.com/wp-content/uploads/2018/09/1PORTADA_TS-2018_13.png' id='img_pais' alt='' title=''/>";
        }
        jQuery(jQuery(jQuery("div#men_pa_m nav.elementor-nav-menu--dropdown ul.elementor-nav-menu li.menu-item-has-children")[i])[0].children[0]).text("");
        jQuery(jQuery(jQuery("div#men_pa_m nav.elementor-nav-menu--dropdown ul.elementor-nav-menu li.menu-item-has-children")[i])[0].children[0]).append(imagen);
    }

    jQuery("#vac_cart").click(function(){
        jQuery(".woocommerce form.woocommerce-cart-form").append('<div class="button loading cart-hosting-loader"></div>')
    });

    jQuery("#vac_cart").click(function(){
        jQuery.ajax({
            url : dcms_vars.ajaxurl,
            type: 'post',
            data: {
                action : 'clear_carrito',
            },
            success: function(resultado){
                location.reload();
            }
        });
    });

});

jQuery("li.est_no_list input#input_1_20").blur(function() {
    jQuery("li.est_def input#input_1_26").val(jQuery("li.est_no_list input#input_1_20").val());
    jQuery("li.est_def input#input_1_26").attr("value", jQuery("li.est_no_list input#input_1_20").val());
});
jQuery("li.est_usa  select#input_1_18").blur(function() {
    jQuery('li.est_def input#input_1_26').val(jQuery("li.est_usa  select#input_1_18 option:selected").text());
    jQuery('li.est_def input#input_1_26').attr("value", jQuery("li.est_usa  select#input_1_18 option:selected").text());
});
jQuery("li.est_col  select#input_1_24").blur(function() {
    jQuery('li.est_def input#input_1_26').val(jQuery("li.est_col  select#input_1_24 option:selected").text());
    jQuery('li.est_def input#input_1_26').attr("value", jQuery("li.est_col  select#input_1_24 option:selected").text());
});
jQuery("li.est_ven  select#input_1_25").blur(function() {
    jQuery('li.est_def input#input_1_26').val(jQuery("li.est_ven  select#input_1_25 option:selected").text());
    jQuery('li.est_def input#input_1_26').attr("value", jQuery("li.est_ven  select#input_1_25 option:selected").text());
});

jQuery("button#cop_regist_administra").click(function(){
    jQuery("#input_5_2").val(jQuery("#input_5_33").val());
    jQuery("#input_5_3").val(jQuery("#input_5_32").val());
    jQuery("#input_5_31").val(jQuery("#input_5_4").val());
    jQuery("#input_5_30").val(jQuery("#input_5_6").val());
    jQuery("#input_5_29").val(jQuery("#input_5_5").val());
    jQuery("#input_5_28").val(jQuery("#input_5_7").val());
    jQuery("#input_5_27").val(jQuery("#input_5_17").val());
    jQuery("#input_5_26").val(jQuery("#input_5_16").val());
    jQuery("#input_5_25").val(jQuery("#input_5_15").val());
    jQuery("#input_5_24").val(jQuery("#input_5_14").val());
    jQuery("#input_5_23").val(jQuery("#input_5_18").val());
    jQuery("#input_5_22").val(jQuery("#input_5_13").val());
});

jQuery("button#cop_administr_contact_tecni").click(function(){
    jQuery("#input_5_39").val(jQuery("#input_5_2").val());
    jQuery("#input_5_40").val(jQuery("#input_5_3").val());
    jQuery("#input_5_41").val(jQuery("#input_5_31").val());
    jQuery("#input_5_42").val(jQuery("#input_5_30").val());
    jQuery("#input_5_43").val(jQuery("#input_5_29").val());
    jQuery("#input_5_44").val(jQuery("#input_5_28").val());
    jQuery("#input_5_45").val(jQuery("#input_5_27").val());
    jQuery("#input_5_46").val(jQuery("#input_5_26").val());
    jQuery("#input_5_47").val(jQuery("#input_5_25").val());
    jQuery("#input_5_48").val(jQuery("#input_5_24").val());
    jQuery("#input_5_38").val(jQuery("#input_5_23").val());
    jQuery("#input_5_37").val(jQuery("#input_5_22").val());
});

jQuery("button#cop_administr_contact_factur").click(function(){
    jQuery("#input_5_50").val(jQuery("#input_5_2").val());
    jQuery("#input_5_51").val(jQuery("#input_5_3").val());
    jQuery("#input_5_52").val(jQuery("#input_5_31").val());
    jQuery("#input_5_53").val(jQuery("#input_5_30").val());
    jQuery("#input_5_54").val(jQuery("#input_5_29").val());
    jQuery("#input_5_55").val(jQuery("#input_5_28").val());
    jQuery("#input_5_56").val(jQuery("#input_5_27").val());
    jQuery("#input_5_57").val(jQuery("#input_5_26").val());
    jQuery("#input_5_58").val(jQuery("#input_5_25").val());
    jQuery("#input_5_59").val(jQuery("#input_5_24").val());
    jQuery("#input_5_60").val(jQuery("#input_5_23").val());
    jQuery("#input_5_61").val(jQuery("#input_5_22").val());
});

jQuery("div#table_dom p.add_to_cart_inline").each(function(e){
    jQuery(this).children("a.add_to_cart_button").hide()
    botones = "" +
        "<a href=\"/contratacion/?category=transferencia\" class=\"button\" id=\"transf\">Transferir</a>" +
        "<a href=\"/contratacion/?category=registro\" class=\"button\" id=\"regist\">Registrar</a>"
    jQuery(this).append(botones)

})

jQuery(window).scroll(function(){
    if(jQuery(window).width() < 768){

    }
    else{
        if(this.pageYOffset > 25){
            jQuery("#menu_movible").attr("style", "position: fixed; z-index: 99999; width: 100%; top: 0;")
            jQuery("#menu_movible div#idiomas").hide()
            jQuery("#menu_movible img").attr("style", "width: 50%;")
            jQuery("section#currency_section").attr("style", "display: none;")

            jQuery("div#movil_blo").attr("style", "align-items: center;")

            jQuery("div#hm_mn a").attr("style", "font-size: 14px;")
            jQuery("div#hm_mn").attr("style", "top: 5px;")

            jQuery("div#logo_men a").attr("style", "text-align: center;")
            jQuery("section#menu_movible img").attr("style", "width: 50%; margin-left: 100px;")
            jQuery("div#sec_men .elementor-column-wrap.elementor-element-populated").attr("style", "margin: 5px 0;")
            jQuery("div#container_menu .elementor-element-populated").attr("style", "padding: 0;")
            jQuery("div#movil_blo .elementor-element-populated").attr("style", "padding-bottom: 0;")

            jQuery("div#menu .elementor-widget-container").attr("style", "margin-top: 0;")

        }
        else{
            jQuery("#menu_movible").removeAttr("style")

            jQuery("div#sec_men .elementor-column-wrap.elementor-element-populated").removeAttr("style")
            jQuery("#menu_movible div#idiomas").show()
            jQuery("#menu_movible img").removeAttr("style")

            jQuery("div#menu a").removeAttr("style")
            jQuery("div#movil_blo .elementor-element-populated").removeAttr("style");
            jQuery("div#container_menu .elementor-element-populated").removeAttr("style")
            jQuery("div#hm_mn a").removeAttr("style")
            jQuery("div#btn_bsc i").removeAttr("style")
            jQuery("div#btn_bsc").removeAttr("style")
            jQuery("div#hm_mn").removeAttr("style")
            jQuery("section#currency_section").removeAttr("style")
            jQuery("div#logo_men a").removeAttr("style")
            jQuery("div#btn_bsc").removeAttr("style")
            jQuery("div#menu .elementor-widget-container").removeAttr("style")
            jQuery("div#movil_blo").removeAttr("style")
        }
    }
});