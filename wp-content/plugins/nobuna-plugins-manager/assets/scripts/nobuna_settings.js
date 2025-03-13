function nb_load_folder_size(type) {
    var data = {
        'action': 'nobuna_folder_size',
        'type': type,
    };
    jQuery.post(ajaxurl, data, function (response) {
        if (response['html']) {
            jQuery('#folder-' + type).html(response['html']);
        }
    }, 'json').error(function () {
        nb_show_error(_nb('UnknownError'));
    });
}

jQuery(document).ready(function ($) {
    nb_load_folder_size('backups');
    nb_load_folder_size('downloads');
});

