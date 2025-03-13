function enviarip ()
{
    var ip = jQuery("#ip").val();
    if ( ip != "")
    {
        jQuery.ajax(
        {
            url:"/wp-json/verificar-ip/v1/search",
            type:"POST",
            data:{ip:ip},
            success: function (respuesta)
            {
                jQuery("#respuesta").html(respuesta);
            },
            beforeSend: function ()
            {
                jQuery("#respuesta").html("<img src='/wp-content/plugins/verificadorIp/images/loading.gif'/>&nbsp;Buscando...");
                jQuery("#respuesta").addClass("respuesta").addClass("alert").addClass("alert-info");
            }
        });   
    }
    else
    {
        alert("Por favor, ingrese su IP");
    }
}
function cancelarip()
{
    jQuery("#ip").val("");
    jQuery("#respuesta").html("");
    jQuery("#respuesta").removeClass("respuesta").removeClass("alert").removeClass("alert-info");
}