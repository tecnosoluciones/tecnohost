function nb_refresh_files() {
    var loader = nb_jloading_img(true);
    var main_container = jQuery('#main-file-list');
    jQuery('.div-pagination').append(loader);
    
    var data = {
        'action': 'nobuna_get_files',
        'p': nb_get_url_param('p', 1),
        'page': nb_get_url_param('page')
    };
    
    jQuery.post(ajaxurl, data, function (response) {
        nobuna_handle_response(response);
        if(response['data'] && response['data']['html']) {
            main_container.html(response['data']['html']);
        }
    }, 'json').error(function () {
        nb_show_error(_nb('UnknownError'));
    }).always(function() {
    });
}

function nb_files_go_to_page(num) {
    nb_set_url_param('p', num);
    nb_refresh_files();
}

function nb_remove_file_item(id, type) {
    var link = jQuery('#link-' + type + '-' + id);
    var loading = nb_jloading_img(true);
    
    link.after(loading);
    link.addClass('nb-hidden');
    
    var data = {
        'action': 'nobuna_remove_' + type,
        'id': id,
        'page': nb_get_url_param('page')
    };
    
    var success = false;
    jQuery.post(ajaxurl, data, function (response) {
        nobuna_handle_response(response);
        success = !nobuna_response_is_error(response);
    }, 'json').error(function (a,b,c) {
        nb_show_error(_nb('UnknownError'));
    }).always(function() {
        link.removeClass('nb-hidden');
        loading.remove();
        if(success === true) {
            var item = jQuery('#'+type+'-id-' + id);
            item.fadeOut('fast', function() {
                item.remove();
                nb_refresh_files();
            });
        }
    });
}

function nb_set_items_per_page() {
    var items = jQuery('#items-per-page').val();
    
    var data = {
        'action': 'nobuna_set_items_per_page',
        'items-per-page': items,
        'page': nb_get_url_param('page')
    };
    
    jQuery.post(ajaxurl, data, function (response) {
        nb_refresh_files();
    }, 'json');
}

jQuery(document).ready(function ($) {
//    nb_refresh_files();
});



