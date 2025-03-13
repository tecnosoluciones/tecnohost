jQuery(document).ready(function() {
    jQuery('#tabla').dataTable({
        pageLength: 5,
        lengthMenu: [ 5, 10, 25],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.1/i18n/es_es.json'
        }
    });

    jQuery("table#tabla_emails").dataTable({
        pageLength: 5,
        lengthMenu: [ 5, 10, 25],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.1/i18n/es_es.json'
        }
    });
} );

jQuery(document).on("click",".close", function(e,a){
    cond = jQuery(this).attr("data-close")
    antigupos = jQuery(this).attr("data-pos-antig")
    jQuery("[data-email='"+cond+"']").hide()

    jQuery(".container_email .close").removeAttr("data-pos-antig")
    jQuery("[data-email='"+cond+"']").parent().removeAttr("style")
    jQuery("div#capa_negra").removeAttr("style")
    jQuery("#capa_negra").remove()
})

jQuery(".mostr_tab").click(function(e,a){
    id = jQuery(this).attr("data-tabla")
    tipo = jQuery(this).attr("data-tipo")
    jQuery(".girar_emails").show()
    jQuery(".tabl_emails").remove()

    jQuery.ajax({
        url : dcms_vars.ajaxurl,
        type: 'post',
        data: {
            action : 'get_emails',
            id : id, tipo : tipo
        },
        async: true,
        success: function(resultado){
            let datos = JSON.parse(resultado);
            jQuery(".girar_emails").hide()
            jQuery(".tabla_emails").append(datos)
            jQuery("table#tabla_emails").dataTable({
                pageLength: 5,
                lengthMenu: [ 5, 10, 25],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.1/i18n/es_es.json'
                }
            });
        }
    });
})

jQuery(document).on("click",".abrir_ema", function(e,a){
    jQuery(".email_content_my_accoun").hide()
    cond = jQuery(this).attr("data-action")

    jQuery.ajax({
        url : dcms_vars.ajaxurl,
        type: 'post',
        data: {
            action : 'get_emails_contentenido',
            id : cond
        },
        async: true,
        success: function(resultado){
            let datos = JSON.parse(resultado);
            jQuery("[data-email='"+cond+"']").append(datos)
            jQuery("[data-email='"+cond+"']").parent().attr("style","height:"+jQuery("[data-email='"+cond+"']").height()+"px;")
            jQuery("div#capa_negra").attr("style","height:"+(parseInt(jQuery("html").height())-parseInt(107))+"px;")
        }
    });
    jQuery("[data-email='"+cond+"']").show()

    antig_pos = jQuery(this).offset().top

    jQuery(".container_email .close").attr("data-pos-antig",antig_pos)
    jQuery("html, body").animate({
        scrollTop: jQuery("body").offset().top
    }, 2000);
    jQuery("html").prepend('<div id="capa_negra"></div>')
})
