jQuery(document).ready(function ($) {
    'use strict';
    let cc_update_slider = 0, cc_reload_symbol = '';
    /*Fix custom symbol of price range in WooCommerce price filter widget*/
    $(document.body).on('price_slider_updated', function (event, min, max) {
        if (cc_update_slider === 1 && cc_reload_symbol !== '') {
            $('.price_slider_amount span.from').html(accounting.formatMoney(min, {
                symbol: cc_reload_symbol,
                decimal: woocommerce_price_slider_params.currency_format_decimal_sep,
                thousand: woocommerce_price_slider_params.currency_format_thousand_sep,
                precision: woocommerce_price_slider_params.currency_format_num_decimals,
                format: woocommerce_price_slider_params.currency_format
            }));

            $('.price_slider_amount span.to').html(accounting.formatMoney(max, {
                symbol: cc_reload_symbol,
                decimal: woocommerce_price_slider_params.currency_format_decimal_sep,
                thousand: woocommerce_price_slider_params.currency_format_thousand_sep,
                precision: woocommerce_price_slider_params.currency_format_num_decimals,
                format: woocommerce_price_slider_params.currency_format
            }));
        } else {
            if (woocommerce_price_slider_params.currency_format_symbol.indexOf('#PRICE#') > -1) {

                $('.price_slider_amount span.from').html(accounting.formatMoney(min, {
                    symbol: woocommerce_price_slider_params.currency_format_symbol.replace('#PRICE#', ''),
                    decimal: woocommerce_price_slider_params.currency_format_decimal_sep,
                    thousand: woocommerce_price_slider_params.currency_format_thousand_sep,
                    precision: woocommerce_price_slider_params.currency_format_num_decimals,
                    format: woocommerce_price_slider_params.currency_format
                }));

                $('.price_slider_amount span.to').html(accounting.formatMoney(max, {
                    symbol: woocommerce_price_slider_params.currency_format_symbol.replace('#PRICE#', ''),
                    decimal: woocommerce_price_slider_params.currency_format_decimal_sep,
                    thousand: woocommerce_price_slider_params.currency_format_thousand_sep,
                    precision: woocommerce_price_slider_params.currency_format_num_decimals,
                    format: woocommerce_price_slider_params.currency_format
                }));
            }
        }
        cc_update_slider = 0;
    });

    $(document.body).on('curcy_price_slider_update', function (event, symbol) {
        cc_update_slider = 1;
        cc_reload_symbol = symbol;
    })
});