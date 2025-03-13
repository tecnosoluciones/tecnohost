jQuery(window).on( "load", function() {
    if(parseInt(window.location.href.indexOf("v2")) > 0 ){
        btn_volver_flujos = '<a class="btn_volver" href="'+window.location.origin+'/bandeja-de-entrada/">volver</a>'
    }
    else{
        btn_volver_flujos = '<a class="btn_volver" href="'+window.location.origin+'/bandeja-de-entrada/">volver</a>'
    }
    jQuery("#postbox-container-1").append(btn_volver_flujos)
})

jQuery(function(){
    ordenar_cam()

    /*jQuery("tr.etiq_lab td.entry-view-field-name").each(function(){
        camtit = jQuery(this).text()

        if(camtit === "ID del pedido" || camtit === "ID de la suscripción"){
            control = jQuery(this).siblings().text().split("ID")
            jQuery(this).siblings().text("")
            jQuery(this).siblings().text(control[0])
        }
    })*/

    jQuery("td.workflow_final_status.column-workflow_final_status").each(function(){
        if(jQuery(this).children().text() === "Pendiente"){
            jQuery(this).children().addClass(jQuery(this).children().text())
        }
        else if(jQuery(this).children().text() === "Completado"){
            jQuery(this).children().addClass(jQuery(this).children().text())
        }
        else if(jQuery(this).children().text() === "Aprobada"){
            jQuery(this).children().addClass(jQuery(this).children().text())
        }
        else if(jQuery(this).children().text() === "Cancelado"){
            jQuery(this).children().addClass(jQuery(this).children().text())
        }
    })

    jQuery("div#minor-publishing a").attr("href", "#")
})

function ordenar_cam(){
    jQuery("td.entry-view-section-break").each(function(){
        id_titulos = jQuery(this).text().toLowerCase().split(' ').join('_')
        jQuery(jQuery(this).parent()).attr("id", id_titulos)
        jQuery(jQuery(this).parent()).attr("class", "tit_zon")
        jQuery(this).attr("colspan", 4)
    })

    jQuery("table.entry-detail-view tr").each(function(){
        jQuery(jQuery(this).find("td.entry-view-field-name")).parent().attr("class","etiq_lab")
    })

    jQuery(".etiq_lab").siblings().each(function(){
        elemento = jQuery(this).find("td.entry-view-field-value").get(0)
        if(typeof elemento != "undefined"){
            if(typeof jQuery(jQuery(this).get(0).previousElementSibling).attr("class") != "undefined"){
                jQuery(jQuery(jQuery(this).get(0).previousElementSibling).get(0)).append(elemento)
            }
        }
    })

    jQuery(".etiq_lab").siblings().each(function(){
        if(parseInt(jQuery(this).get(0).childElementCount) == 0){
            jQuery(this).remove()
        }
    })

    jQuery("div#minor-publishing h4").text().trim()

    jQuery("div#minor-publishing h4").hide()

    proceso_ = jQuery("div#post-body-content th#details").text().split(':').join('').split('Entrada').join('').split('#').join('').split(/[0-9]/).join('').trim()

    paso_ = " " + jQuery("div#minor-publishing h4").text().trim()

    if(parseInt(paso_.length) === 1 || parseInt(paso_.length) === 0){
        paso_ = " Proceso Terminado"
    }

    elemento_ = "<div><h3 id='tt_pro'><strong>Proceso:</strong> "+proceso_+"</h3> <h3 id='tt_pas'><strong>Paso: </strong>"+paso_+"</h3></div>"

    jQuery("div#post-body-content").prepend(elemento_)

    jQuery("div#post-body-content th#details").hide()

    if(typeof jQuery("div#post-body-content th#details").get(0) != "undefined"){
        jQuery(jQuery("div#post-body-content th#details").get(0).nextElementSibling).hide()
    }

    jQuery("div.salto_linea").parent().parent().attr("class",jQuery("div.salto_linea").attr("class"))

    if(parseInt(jQuery(".wpdm-downloads").length) != 0){
        jQuery(jQuery(".wpdm-downloads").get(0).previousElementSibling).attr("id", "mig_pwpmd")
    }

    var tt_resul_wpmd = "<div class='row'>" +
        "<div class='col-md-12' id='tt_res'>" +
        "<h3>Resultados</h3>" +
        "</div>" +
        "</div>";

    jQuery("form#srcp").after(tt_resul_wpmd)

    var tt_resul_prin_wpmd = "<div class='row'>" +
        "<div class='col-md-12' id='tt_res'>" +
        "<h3>Búsqueda de Archivos</h3>" +
        "</div>" +
        "</div>";

    jQuery("form#srcp").before(tt_resul_prin_wpmd)

    jQuery("input[name='_gravityflow_admin_action']").parent().parent().parent().attr("id", "adm_pan")

    jQuery(".etiq_lab").siblings().each(function(){
        if(jQuery(this).find("td.entry-view-field-name").text() == "Proyecto para asignar")
            jQuery(this).hide()
    })
}