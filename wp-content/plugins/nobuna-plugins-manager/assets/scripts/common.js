
function _nb(key) {
    if (typeof nb_lang !== 'undefined' && nb_lang[key]) {
        return nb_lang[key];
    }
    return key;
}

function nb_set_url_params(params_to_set) {
    const params = new URLSearchParams(location.search);
    for(var index in params_to_set) {
        params.set(index, params_to_set[index]);
    }
    window.history.replaceState({}, '', `${location.pathname}?${params}`);
}

function nb_set_url_param(key, value) {
    var d = {};
    d[key] = value;
    nb_set_url_params(d);
}

function nb_get_url_param(key, default_value) {
    const params = new URLSearchParams(location.search);
    var res = params.get(key);
    return res === null ? default_value : res;
}

function nb_img_path(img_key) {
    var key = img_key + '_img_path';
    if (typeof nb_imgs !== 'undefined' && nb_imgs[key]) {
        return nb_imgs[key];
    }
    return '';
}

function nb_jloading_img(is_mini) {
    var img = jQuery('<img>');
    var name = is_mini ? 'miniloading' : 'loading';
    var path = nb_img_path(name);
    if (path !== '') {
        img.attr('src', path);
    }
    return img;
}

//function nb_jwpbody_content() {
//    return jQuery('#wpbody');
//    return jQuery('#wpbody-content');
//}

function nb_prepend_towpbody(jContent, slide) {
//    var body = nb_jwpbody_content();
    var body = jQuery('h1.nobuna');
    if(slide) {
        jContent.hide();
        body.after(jContent);
        jContent.slideDown('fast');
    } else {
        body.after(jContent);
    }
}

function nb_jnotice_item(type, is_dismissible, jContent) {
    var tag = jQuery('<div class="notice nobuna-notice notice-' + type + '"></div>');
    if (is_dismissible) {
        var clsButton = jQuery('<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice</span></button>');
        tag.addClass('is-dismissible');
        tag.append(clsButton);
        clsButton.on('click', function () {
            tag.slideUp('slow', function () {
                tag.remove();
            });
        });
    }
    tag.prepend(jContent);
    return tag;
}

function nobuna_response_is_error(nobuna_response) {
    return nobuna_response['isError'] === true;
}

var disclaimer_set = false;
var iframe_set = false;
function nobuna_handle_response(nobuna_response) {
    if (!nobuna_response['messages']) {
        return;
    }
    var messages = nobuna_response['messages'];
    var info = messages['info'];
    var error = messages['error'];
    var warning = messages['warning'];
    var success = messages['success'];
    var disclaimer = messages['disclaimer'];
    var iframe = messages['iframe'];
    nobuna_handle_notices(info, nb_jnotice_info);
    nobuna_handle_notices(warning, nb_jnotice_warning);
    nobuna_handle_notices(error, nb_jnotice_error);
    nobuna_handle_notices(success, nb_jnotice_success);
    if(disclaimer !== null && !disclaimer_set) {
        disclaimer_set = true;
        nb_prepend_towpbody(nb_jnotice_disclaimer(true, jQuery(disclaimer)), false);
    }
    if(iframe !== null && !iframe_set) {
        iframe_set = true;
        nb_prepend_towpbody(jQuery(iframe), false);
    }
}

function nobuna_handle_notices(notices, method) {
    for (var i = 0; i < notices.length; i++) {
        var notice = method(true, jQuery(notices[i]));
        nb_prepend_towpbody(notice, true);
        if (method === nb_jnotice_success || method === nb_jnotice_info) {
            setTimeout(function () {
                notice.fadeOut('slow', function () {
                    notice.remove();
                });
            }, 5000);
        }
    }
}

function nb_jnotice_info(is_dismissible, jContent) {
    return nb_jnotice_item('info', is_dismissible, jContent);
}

function nb_jnotice_success(is_dismissible, jContent) {
    return nb_jnotice_item('success', is_dismissible, jContent);
}

function nb_jnotice_warning(is_dismissible, jContent) {
    return nb_jnotice_item('warning', is_dismissible, jContent);
}

function nb_jnotice_error(is_dismissible, jContent) {
    return nb_jnotice_item('error', is_dismissible, jContent);
}

function nb_jnotice_disclaimer(is_dismissible, jContent) {
    return nb_jnotice_item('warning', is_dismissible, jContent);
}


function nb_show_info(message, is_dismissible) {
    var d = is_dismissible ? true : false;
    nb_prepend_towpbody(nb_jnotice_info(d, message));
}

function nb_show_warning(message, is_dismissible) {
    var d = is_dismissible ? true : false;
    nb_prepend_towpbody(nb_jnotice_warning(d, message));
}

function nb_show_error(message, is_dismissible) {
    var d = is_dismissible ? true : false;
    nb_prepend_towpbody(nb_jnotice_error(d, jQuery('<p>' + message + '</p>')));
}

function nobuna_resize_iframe(obj) {
    obj.style.height = obj.contentWindow.document.body.style.height;
    obj.style.width = obj.contentWindow.document.body.style.width;
}
