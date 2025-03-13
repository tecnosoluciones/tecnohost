function nobuna_backup_plugin() {
    var jSelf = jQuery(this);
    var loader = nb_jloading_img(true);
    loader.attr('style', 'width: 16px !important; height: 16px !important;');
    jSelf.parent().prepend(loader);
    jSelf.unbind('click', nobuna_backup_plugin);
    var plugin_name = jSelf.attr('data-plugin');
    if (!plugin_name) {
        return;
    }

    var data = {
        'action': 'nobuna_backup',
        'plugin': plugin_name,
    };

    jQuery.post(ajaxurl, data, function (response) {
        nobuna_handle_response(response);
    }, 'json').error(function (a, b, c) {
    }).always(function () {
        loader.fadeOut('slow', function () {
            jSelf.on('click', nobuna_backup_plugin);
            loader.remove();
        });
    });
}

jQuery(document).ready(function ($) {
    jQuery('.backup[data-plugin]').each(function (key, value) {
        jQuery(value).on('click', nobuna_backup_plugin);
    });
});
