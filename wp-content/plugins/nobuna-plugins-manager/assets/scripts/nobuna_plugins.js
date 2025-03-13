function nb_set_options(product_id, options) {
    jQuery('#nb-options-' + product_id).html(options);
}

function nb_set_product_row(product_id, row) {
    jQuery('#nb-product-' + product_id).html(jQuery(row).html());
}

function nb_remove_product_row(product_id) {
    var tag = jQuery('#nb-product-' + product_id);
    tag.fadeOut('slow', function() {
        tag.remove();
    });
}

var nobuna_previous_value = null;
function nb_search_plugins(force_refresh) {
    var search = jQuery('#search-products').val().trim();
    if(nobuna_previous_value !== search || force_refresh === true) {
        nobuna_previous_value = search;
        nb_refresh_plugins(nobuna_previous_value);
    }
}

var nobuna_searching = false;
var nobuna_timeout_handler = null;
function nb_refresh_plugins(search, force_refresh) {
    if(nobuna_searching === true) {
        if(nobuna_timeout_handler !== null) {
            clearTimeout(nobuna_timeout_handler);
        }
        nobuna_timeout_handler = setTimeout(function(){
            nb_refresh_plugins(search);
        }, 100);
        return;
    }
    nobuna_searching = true;
    var query = search ? search : '';
    if(query !== '') {
        jQuery('#nobuna-search-loading').removeClass('nb-hidden');
    }
    jQuery('#nb-data').html(_nb('LoadingPlugins'));
    var data = {
        'action': 'get_products',
        'q': query
    };
	jQuery(document).off('ajaxSend');
    jQuery.post(ajaxurl, data, function (response) {
        if(response) {
            nobuna_handle_response(response);
            if(response['data'] && response['data']['html']) {
                jQuery('#nb-data').html(response['data']['html']);
            } else {
                jQuery('#nb-data').html('');
            }
        } else {
            nb_show_error(_nb('UnknownError'));
        }
    }, 'json').error(function (a,b,c) {
        nb_show_error(_nb('UnknownError'));
    }).always(function() {
        nobuna_searching = false;
        if(query !== '') {
            jQuery('#nobuna-search-loading').addClass('nb-hidden');
        }
    });
}

function nb_process_plugins_response(product_id, response, remove) {
    nobuna_handle_response(response);
    if(response['data'] && response['data']['row']) {
        nb_set_product_row(product_id, response['data']['row']);
    } else if(remove) {
        nb_remove_product_row(product_id);
    }
}

function nb_product_ajax_request(action, product_id, remove) {
    var data = {
        'action': action,
        'product_id': product_id
    };
	jQuery(document).off('ajaxSend');
    jQuery.post(ajaxurl, data, function (response) {
        if(response && response['data']) {
            nb_process_plugins_response(product_id, response, remove);
        } else {
            nb_show_error(_nb('UnknownError'));
        }
    }, 'json').error(function (a,b,c) {
        nb_show_error(_nb('UnknownError'));
    });
}

function nb_download_product(product_id) {
    nb_set_options(product_id, nb_jloading_img());
    nb_product_ajax_request('download_product', product_id);
}

function nb_install_product(product_id) {
    nb_set_options(product_id, nb_jloading_img());
    nb_product_ajax_request('install_product', product_id);
}

function nb_remove_product(product_id) {
    nb_set_options(product_id, nb_jloading_img());
    var remove = true;
    nb_product_ajax_request('remove_product', product_id, remove);
}

jQuery(document).ready(function ($) {
    jQuery('#search-products').keypress(function(e) {
      if(e.which === 13) { nb_search_plugins(); } });
    jQuery('#nb-refresh').on('click', function() {
        nb_search_plugins(true);
    });
    nb_refresh_plugins();
});

