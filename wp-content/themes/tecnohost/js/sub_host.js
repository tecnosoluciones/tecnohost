/**
 * Ejemplo de Ajax en wordpress
 *
 * jQuery.ajax({
        url : dcms_vars.ajaxurl,
        type: 'post',
        data: {
            action : 'dcms_ajax_readmore',
            id_post: id
        },
        success: function(resultado){
            jQuery("#img_cat_blg img").attr("src", resultado)
        }

    });
 * */



if(jQuery(".plan_tab").length){
    jQuery(".plan_tab").each(function(){
        var color = jQuery(this).find(jQuery(".elementor-price-table .elementor-price-table__header")).css("background-color");
        jQuery(this).find(jQuery(".elementor-price-table .elementor-price-table__price")).css("background-color", color);
        jQuery(this).find(jQuery(".elementor-price-table .elementor-price-table__footer")).css("background-color", color);
    })
}

jQuery(window).on( "load", function() {

    jQuery("select#chang_plan").change(function(){
        limpiar()
        jQuery('#esp_w').show()
        var can = jQuery(this).val()
        var sus_actual = jQuery("div#clon").attr("data-id-prod").replace("product-", "")
        var interval = setInterval(girar, 800);
        verificar_beneficio(can, sus_actual, interval);
    })

    var select = jQuery('select#chang_plan');
    select.html(select.find('option').sort(function(x, y) {
        // to change to descending order switch "<" for ">"
        return jQuery(x).attr("data-cont") > jQuery(y).attr("data-cont") ? 1 : -1;
    }));

    jQuery('select#chang_plan option:contains("Selecciona una opción")').prop('selected', true)
})


if(jQuery("div#btn_c_plmd").length){
    if(jQuery("form.cart.grouped_form").attr("action")){
        marcar_plan_actual(jQuery("form.cart.grouped_form").attr("action").split('=')[1].split('&')[0])
    }
}

function marcar_plan_actual(lru){
    jQuery.ajax({
        url : dcms_vars.ajaxurl,
        type: 'post',
        data: {
            action : 'con_plan',
            id_ped : lru
        },
        success: function(resultado){
            let datos = JSON.parse(resultado);
            jQuery('div#clon').attr("data-id-prod", "product-"+datos.id_producto)
            let agregar = "<div id='msj_mejo'>"+datos['msn']+"</div>";
            jQuery('div#clon').append(agregar)
            jQuery("div#btn_c_plmd tr").each(function(e){
                let name = jQuery(this).find('label[for="'+jQuery(this).attr('id')+'"]').text()
                let precio = jQuery(this).find('span.woocommerce-Price-amount.amount').text()
                let detalle = jQuery(this).find('.subscription-details').text()
                jQuery("div[data-id-prod='"+jQuery(this).attr('id')+"'] h3").prepend("<span id='produ_name'>"+name+"</span>")
                jQuery("div[data-id-prod='"+jQuery(this).attr('id')+"'] span.woocommerce-Price-amount.amount").text(precio)
                jQuery("div[data-id-prod='"+jQuery(this).attr('id')+"'] h3").append(detalle)
                jQuery('div[data-id-prod="product-'+datos.id_producto+'"]').attr("data-css","selecon");
            })
            jQuery('div#plans_s').append(datos.mensaje)
            crear_select()
        }
    });
}

function crear_select(){

    var selec_title = "Seleccionar Plan"
    var selec_option = "Selecciona una opción"
    var contador = 1;

    let imprimir = "<h2 id='slc_plan'>"+selec_title+"</h2>" +
        "<select id='chang_plan'><option data-cont='0' selected>"+selec_option+"</option>"
    jQuery("div#btn_c_plmd tr").each(function(e){
        let name = jQuery(this).find('label[for="'+jQuery(this).attr('id')+'"]').text()
        let precio = jQuery(this).find('span.woocommerce-Price-amount.amount').text()
        let detalle = jQuery(this).find('.subscription-details').text()
        if(jQuery("div[data-id-prod='"+jQuery(this).attr("id")+"']").attr("data-id-prod") !== jQuery(this).attr("id")){
            if(parseInt(jQuery(this).attr("id").replace("product-", "")) == "470"){
                j = 1;
            }
            else if(parseInt(jQuery(this).attr("id").replace("product-", "")) == "910"){
                j = 9;
            }
            else{
                if(parseInt(contador) == 1){
                    j = parseInt(contador);
                    contador++;
                }
                else if(parseInt(contador) == 8){
                    contador+2;
                }
                else{
                    j = parseInt(contador) + 1;
                    contador++;
                }

            }

            imprimir += "<option data-cont='"+j+"' data-id='"+jQuery(this).attr("id")+"' value='"+jQuery(this).attr("id").replace("product-", "")+"'>" +
                ""+name+"" +
                "" +detalle +"" +
                "</option>"
        }
    })
    imprimir += "</select>"

    jQuery("div#selc_plan .elementor-text-editor").append(imprimir)
}

function verificar_beneficio(id_prd, sus_actual, interval){

    jQuery.ajax({
        url : dcms_vars.ajaxurl,
        type: 'post',
        data: {
            action : 'con_dif_plan',
            id_ped : id_prd,
            id_a_ped : sus_actual
        },
        success: function(resultado){
            let datos = JSON.parse(resultado);
            jQuery("div#con_mesk_mejor .elementor-text-editor").html("")
            jQuery("div#con_mesk_mejor .elementor-text-editor").append(datos.mensjae)
            jQuery('#esp_w').hide()
            clearInterval(interval)
            jQuery("input[name='quantity["+id_prd+"]']").attr("value",1)
            jQuery("input[name='quantity["+id_prd+"]']").val(1)
        }
    });
}

function limpiar(){
    jQuery("div#btn_c_plmd tr").each(function(e){
        jQuery(this).find("input[name^='quantity']").attr("value", 0)
    })
}

function girar(){
    var jQueryelem = jQuery('#esp_w i');
    jQuery({ deg: 0 }).animate({ deg: 359 }, {
        duration: 600,
        step: function (now) {
            var scale = (2 * now / 359);
            jQueryelem.css({
                transform: 'rotate(' + now + 'deg)'
            });
        }
    });
}

jQuery("div#table_dom").each(function(e){
    jQuery('#esp_w').show()
    var interval = setInterval(girar, 800);
    jQuery.ajax({
        type:'post',
        url : dcms_vars.ajaxurl,
        data: {
            action : 'desc_id',
        },
        success: function(resultado){
            let datos = JSON.parse(resultado);
            clearInterval(interval)
            jQuery(".storefront-pricing-table.columns-1").html("")
            jQuery(".storefront-pricing-table.columns-1").show()
            jQuery(".storefront-pricing-table.columns-1").append(datos['elementos'])
            jQuery('#esp_w').hide()
        }
    });
})

jQuery(function() {

});