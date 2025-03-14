jQuery(document).ready(function ($) {
    'use strict';

    if (wooMultiCurrencyParams.currencyByPaymentImmediately) {

        $(document.body).on('updated_checkout', function (event, response) {
            if (typeof response === 'undefined') return;
            if (response.hasOwnProperty('wmc_update_checkout') && response.wmc_update_checkout) {
                $(document.body).trigger('update_checkout');
            }
        });

        $(document.body).on('payment_method_selected', function (event, response) {
            // let selectedPaymentMethod = $('.woocommerce-checkout input[name="payment_method"]:checked').val();
            // if (selectedPaymentMethod === 'ppcp-gateway') {
            $(document.body).trigger('update_checkout');
            // }
        });
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    window.woocommerce_multi_currency = {
        init: function () {
            this.design();
            this.checkPosition();
            this.click_to_expand();
            this.cacheInit();
        },

        cacheInit() {
            if (wooMultiCurrencyParams.enableCacheCompatible === '1') {
                this.cacheCompatible();
                $(document).on('append.infiniteScroll', () => {
                    this.cacheCompatible();
                });
            } else if (wooMultiCurrencyParams.enableCacheCompatible === '2') {
                this.overrideSwitcher();
                this.cacheCompatibleByJSON();

                $(document).on('append.infiniteScroll', () => {
                    this.cacheCompatibleByJSON();
                });
            }
        },
        disableCurrentCurrencyLink() {
            $('.wmc-active a').on('click', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
            });
        },

        design: function () {
            var windowsize = jQuery(window).width();
            if (windowsize <= 768) {
                jQuery('.woocommerce-multi-currency.wmc-sidebar').on('click', function () {
                    jQuery(this).toggleClass('wmc-hover');
                    let body_overflow = jQuery('html body').css("overflow");
                    if (jQuery(this).hasClass('wmc-hover')) {
                        jQuery('html').css({'overflow': 'hidden'});
                    } else {
                        jQuery('.woocommerce-multi-currency.wmc-sidebar').css('display', 'none');
                        setTimeout(function () {
                            jQuery('.woocommerce-multi-currency.wmc-sidebar').css('display', 'initial');
                        }, 100);
                        if ('clip' === body_overflow) {
                            jQuery('html').css({'overflow': ''});
                        } else {
                            jQuery('html').css({'overflow': 'visible'});
                        }
                    }
                })
            } else {
                /*replace hover with mouseenter mouseleave in some cases to work correctly*/
                let currencyBar = jQuery('.woocommerce-multi-currency.wmc-sidebar');

                if (currencyBar.hasClass('wmc-collapse') && wooMultiCurrencyParams?.click_to_expand_currencies_bar) {
                    currencyBar.on('click', function () {
                        jQuery(this).toggleClass('wmc-hover');
                    }).on('mouseleave', function () {
                        jQuery(this).removeClass('wmc-hover');
                    })
                } else {
                    currencyBar.on('mouseenter', function () {
                        let $this = jQuery(this);
                        $this.addClass('wmc-hover');
                    }).on('mouseleave', function () {
                        let $this = jQuery(this);
                        $this.removeClass('wmc-hover');
                    })
                }

            }
        },

        checkPosition: function () {
            jQuery('.woocommerce-multi-currency .wmc-currency-wrapper').on('mouseenter', function () {
                let $wrapper = $(this), $shortcode_container = $wrapper.closest('.woocommerce-multi-currency');
                if (!$shortcode_container.hasClass('wmc-currency-trigger-click')) {
                    if (this.getBoundingClientRect().top / $(window).height() > 0.5) {
                        $shortcode_container.find('.wmc-sub-currency').addClass('wmc-show-up');
                    } else {
                        $shortcode_container.find('.wmc-sub-currency').removeClass('wmc-show-up');
                    }
                }
            });
        },

        click_to_expand() {
            $(document.body).on('click', function (event) {
                $('.wmc-currency-trigger-click-active').removeClass('wmc-currency-trigger-click-active')
            });
            $(document.body).on('click', '.wmc-currency-trigger-click', function (event) {
                let $shortcode_container = $(this);
                event.stopPropagation();
                $shortcode_container.toggleClass('wmc-currency-trigger-click-active');
                if (this.getBoundingClientRect().top / $(window).height() > 0.5) {
                    $shortcode_container.find('.wmc-sub-currency').addClass('wmc-show-up');
                } else {
                    $shortcode_container.find('.wmc-sub-currency').removeClass('wmc-show-up');
                }
            });
        },
        cacheCompatible() {
            if (typeof wc_checkout_params !== 'undefined') {
                if (parseInt(wc_checkout_params.is_checkout) === 1) {
                    return;
                }
            }

            // if (typeof wc_add_to_cart_params !== 'undefined') {
            //     if (parseInt(wc_add_to_cart_params.is_cart) === 1) {
            //         return;
            //     }
            // }

            let pids = [];
            let simpleCache = $('.wmc-cache-pid');
            if (simpleCache.length) {
                simpleCache.each(function (i, element) {
                    let wmc_product_id = $(element).data('wmc_product_id');
                    if (wmc_product_id) {
                        pids.push(wmc_product_id);
                    }
                });
            }

            let variationCache = $('.variations_form');
            if (variationCache.length) {
                variationCache.each(function (index, variation) {
                    let data = $(variation).data('product_variations');
                    if (data.length) {
                        data.forEach((element) => {
                            pids.push(element.variation_id);
                        });
                    }
                });
            }

            let $shortcodes = $('.woocommerce-multi-currency.wmc-shortcode').not('.wmc-list-currency-rates'),
                shortcodes = [];
            $shortcodes.map(function () {
                let $shortcode = $(this);
                shortcodes.push({
                    layout: $shortcode.data('layout') ? $shortcode.data('layout') : 'default',
                    flag_size: $shortcode.data('flag_size'),
                    dropdown_icon: $shortcode.data('dropdown_icon'),
                    custom_format: $shortcode.data('custom_format'),
                    direction: $shortcode.data('direction'),
                });
            });
            if (pids.length) pids = [...new Set(pids)]; //remove duplicate element

            let exchangePrice = [];
            $('.wmc-cache-value').each(function (i, element) {
                exchangePrice.push({
                    price: $(element).data('price'),
                    original_price: $(element).data('original_price'),
                    currency: $(element).data('currency'),
                    product_id: $(element).data('product_id'),
                    keep_format: $(element).data('keep_format')
                });
            });
            exchangePrice = [...new Set(exchangePrice.map(JSON.stringify))].map(JSON.parse);

            $.ajax({
                url: wooMultiCurrencyParams.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'wmc_get_products_price',
                    pids: pids,
                    shortcodes: shortcodes,
                    wmc_current_url: $('.wmc-current-url').val(),
                    exchange: exchangePrice,
                    wc_filter_price: $('.widget-area .woocommerce.widget_price_filter form .price_slider_wrapper').length !== 0 || '',
                    wc_filter_price_meta: wooMultiCurrencyParams.filter_price_meta_query !== '' ? JSON.parse(wooMultiCurrencyParams.filter_price_meta_query) : '',
                    wc_filter_price_tax: wooMultiCurrencyParams.filter_price_tax_query !== '' ? JSON.parse(wooMultiCurrencyParams.filter_price_tax_query) : '',
                    wc_filter_price_search: wooMultiCurrencyParams.filter_price_search_query !== '' ? JSON.parse(wooMultiCurrencyParams.filter_price_search_query) : '',
                    wc_filter_price_action: $('.widget-area .woocommerce.widget_price_filter form').length !== 0 ?
                        $('.widget-area .woocommerce.widget_price_filter form').attr('action') : '',
                    extra_params: wooMultiCurrencyParams.extra_params,
                },
                xhrFields: {
                    withCredentials: true
                },
                success(res) {
                    if (res.success) {
                        let prices = res.data.prices || '',
                            currentCurrency = res.data.current_currency || '',
                            filter_price_data = res.data.wc_filter_price || '',
                            exSc = res.data.exchange || '';

                        if (shortcodes.length > 0 && shortcodes.length === res.data.shortcodes.length) {
                            for (let i = 0; i < shortcodes.length; i++) {
                                $shortcodes.eq(i).replaceWith(res.data.shortcodes[i]);
                            }
                        }
                        if (wooMultiCurrencyParams.switchByJS !== '1') {
                            $('.wmc-currency a').unbind();
                        }

                        if (currentCurrency) {
                            /*Sidebar*/
                            $('.wmc-sidebar .wmc-currency').removeClass('wmc-active');
                            $(`.wmc-sidebar .wmc-currency[data-currency=${currentCurrency}]`).addClass('wmc-active');
                            /*Product price switcher*/
                            $('.wmc-price-switcher .wmc-current-currency i').removeClass().addClass('vi-flag-64 flag-' + res.data.current_country);
                            $(`.wmc-price-switcher .wmc-hidden`).removeClass('wmc-hidden');
                            $(`.wmc-price-switcher .wmc-currency[data-currency=${currentCurrency}]`).addClass('wmc-hidden');
                            let symbolData = $(`.wmc-price-switcher .wmc-currency[data-currency=${currentCurrency}]`).attr('data-symbol');
                            $('.wmc-price-switcher .wmc-current-currency .wmc-prd-switcher-display').html(symbolData);

                            let $price_switcher = $('.wmc-price-switcher').not('.wmc-approximate-price-switcher');
                            $price_switcher.find('.wmc-current-currency i').removeClass().addClass('vi-flag-64 flag-' + res.data.current_country);
                            $price_switcher.find(`.wmc-hidden`).removeClass('wmc-hidden');
                            $price_switcher.find(`.wmc-currency[data-currency=${currentCurrency}]`).addClass('wmc-hidden');
                            $(`select.wmc-nav option[data-currency=${currentCurrency}]`).prop('selected', true);
                            $('body').removeClass(`woocommerce-multi-currency-${wooMultiCurrencyParams.current_currency}`).addClass(`woocommerce-multi-currency-${currentCurrency}`);
                        }
                        // woocommerce_multi_currency.disableCurrentCurrencyLink();
                        // if (typeof woocommerce_multi_currency_switcher !== 'undefined') {
                        //     woocommerce_multi_currency_switcher.init();
                        // }

                        if (prices) {
                            // $('.wmc-approximately').remove();

                            for (let id in prices) {
                                $(`.wmc-cache-pid[data-wmc_product_id=${id}]`).replaceWith(prices[id]);
                            }

                            $('.variations_form').each((i, form) => {
                                let data = $(form).data('product_variations');
                                if (data) {
                                    data.map((element) => {
                                        let pid = element.variation_id;
                                        element.price_html = prices[pid];
                                        return element
                                    });
                                    $(form).data('product_variations', data);
                                }
                            });

                            $('.variations select').trigger('change.wc-variation-form');
                        }

                        if (exSc) {
                            for (let i in exSc) {
                                $(`.wmc-cache-value[data-price="${exSc[i]['price']}"][data-product_id="${exSc[i]['product_id']}"][data-keep_format="${exSc[i]['keep_format']}"][data-original_price="${exSc[i]['original_price']}"][data-currency="${exSc[i]['currency']}"]`).replaceWith(exSc[i]['shortcode']);
                            }
                        }

                        if (filter_price_data !== '') {
                            $('.widget-area .woocommerce.widget_price_filter form').replaceWith(filter_price_data);
                            jQuery(document.body).trigger('curcy_price_slider_update', [ res.data.currency_symbol ] );
                            jQuery(document.body).trigger("init_price_filter", [res.data]);
                        }

                        jQuery(document.body).trigger("wmc_cache_compatible_finish", [res.data]);
                    }
                }
            });
        },

        overrideSwitcher() {
            let currentCurrency = getCookie('wmc_current_currency');
            if (!currentCurrency) return;

            {
                $('.wmc-list-currencies .wmc-currency').removeClass('wmc-active');
                $(`.wmc-list-currencies .wmc-currency[data-currency=${currentCurrency}]`).addClass('wmc-active');
            }

            //Plain horizontal
            {
                $('.woocommerce-multi-currency.wmc-shortcode.plain-horizontal .wmc-currency').removeClass('wmc-active');
                $(`.woocommerce-multi-currency.wmc-shortcode.plain-horizontal .wmc-currency a[data-currency=${currentCurrency}]`).parent().addClass('wmc-active');
            }

            //Common Plain vertical
            {
                $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical .wmc-currency').removeClass('wmc-hidden');
                $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical .wmc-currency a[data-currency=${currentCurrency}]`).parent().addClass('wmc-hidden');
            }

            //Plain vertical
            {
                $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout0 .wmc-current-currency-code').text(currentCurrency);
            }

            //Layout4
            {
                let flagClass = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout4 .wmc-currency a[data-currency=${currentCurrency}] i`).attr('class');
                $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout4 .wmc-current-currency i.wmc-current-flag').removeClass().addClass(flagClass);
            }

            //Layout5
            {
                let flagClass = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout5 .wmc-currency a[data-currency=${currentCurrency}] i`).attr('class');
                $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout5 .wmc-current-currency i.wmc-current-flag').removeClass().addClass(flagClass);
                let subCurrencyCode = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout5 .wmc-currency a[data-currency=${currentCurrency}] .wmc-sub-currency-code`).first().text();
                $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout5 .wmc-current-currency .wmc-current-currency-code').text(subCurrencyCode);
            }

            //Layout6
            {
                $('.woocommerce-multi-currency.wmc-shortcode.plain-horizontal.layout6 .wmc-currency').removeClass('wmc-active');
                $(`.woocommerce-multi-currency.wmc-shortcode.plain-horizontal.layout6 .wmc-currency a[data-currency=${currentCurrency}]`).parent().addClass('wmc-active');
            }

            //Layout7
            {
                let currencySymbol = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.vertical-currency-symbols .wmc-currency a[data-currency=${currentCurrency}]`).first().text();
                $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.vertical-currency-symbols .wmc-current-currency .wmc-current-currency-symbol`).text(currencySymbol);
            }

            //Layout8
            {
                let currencySymbol = $(`.woocommerce-multi-currency.wmc-shortcode.vertical-currency-symbols-circle .wmc-currency a[data-currency=${currentCurrency}]`).first().text();
                $(`.woocommerce-multi-currency.wmc-shortcode.vertical-currency-symbols-circle .wmc-current-currency`).text(currencySymbol);
            }

            //Layout9
            {
                let beforeCurrentCurrency = true;
                $('.woocommerce-multi-currency.wmc-shortcode.layout9 .wmc-currency').removeClass('wmc-current-currency wmc-active wmc-left wmc-right');
                $('.woocommerce-multi-currency.wmc-shortcode.layout9 .wmc-currency').each(function (i, el) {
                    let a = $(el).find('a');
                    let dataCurrency = a.attr('data-currency');
                    let symbol = a.text();

                    symbol = symbol.replace(dataCurrency, '').trim();

                    if (currentCurrency === dataCurrency) {
                        $(el).css('z-index', 999);
                        $(el).addClass('wmc-current-currency wmc-active');
                        a.text(`${currentCurrency} ${symbol}`);
                        beforeCurrentCurrency = false;
                    } else {
                        a.text(symbol);
                        if (beforeCurrentCurrency) {
                            $(el).addClass('wmc-left');
                            $(el).css('z-index', i);
                        } else {
                            $(el).addClass('wmc-right');
                            $(el).css('z-index', 99 - i);
                        }
                    }
                });
            }

            //Layout10
            {
                let flagClass = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10 .wmc-currency a[data-currency=${currentCurrency}] i`).attr('class');
                $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10 .wmc-current-currency i.wmc-current-flag').removeClass().addClass(flagClass);

                let customFormat = $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10').data('custom_format');
                if (customFormat) {
                    let subCurrencyName = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10 .wmc-currency a[data-currency=${currentCurrency}] .wmc-sub-currency-name`).first().text();
                    let subCurrencyCode = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10 .wmc-currency a[data-currency=${currentCurrency}] .wmc-sub-currency-code`).first().text();
                    let subCurrencySymbol = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10 .wmc-currency a[data-currency=${currentCurrency}] .wmc-sub-currency-symbol`).first().text();
                    $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10 .wmc-current-currency .wmc-currency-name').text(`${subCurrencyName}`);
                    $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10 .wmc-current-currency .wmc-currency-code').text(`${subCurrencyCode}`);
                    $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10 .wmc-current-currency .wmc-currency-symbol').text(` ${subCurrencySymbol}`);
                } else {
                    let subCurrencyCode = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10 .wmc-currency a[data-currency=${currentCurrency}] .wmc-sub-currency-name`).first().text();
                    let subCurrencySymbol = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10 .wmc-currency a[data-currency=${currentCurrency}] .wmc-sub-currency-symbol`).first().text();
                    $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10 .wmc-current-currency .wmc-text-currency-text').html(`(${subCurrencyCode})`);
                    $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout10 .wmc-current-currency .wmc-text-currency-symbol').html(` ${subCurrencySymbol}`);
                }
            }

            //Layout11
            {
                let flagClass = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11 .wmc-currency a[data-currency=${currentCurrency}] i`).attr('class');
                $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11 .wmc-current-currency i.wmc-current-flag').removeClass().addClass(flagClass);

                let customFormat = $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11').data('custom_format');
                if (customFormat) {
                    let subCurrencyName = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11 .wmc-currency a[data-currency=${currentCurrency}] .wmc-sub-currency-name`).first().text();
                    let subCurrencyCode = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11 .wmc-currency a[data-currency=${currentCurrency}] .wmc-sub-currency-code`).first().text();
                    let subCurrencySymbol = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11 .wmc-currency a[data-currency=${currentCurrency}] .wmc-sub-currency-symbol`).first().text();
                    $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11 .wmc-current-currency .wmc-currency-name').text(`${subCurrencyName}`);
                    $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11 .wmc-current-currency .wmc-currency-code').text(`${subCurrencyCode}`);
                    $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11 .wmc-current-currency .wmc-currency-symbol').text(` ${subCurrencySymbol}`);
                } else {
                    let subCurrencyCode = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11 .wmc-currency a[data-currency=${currentCurrency}] .wmc-sub-currency-name`).first().text();
                    let subCurrencySymbol = $(`.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11 .wmc-currency a[data-currency=${currentCurrency}] .wmc-sub-currency-symbol`).first().text();
                    $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11 .wmc-current-currency .wmc-text-currency-text').html(`(${subCurrencyCode})`);
                    $('.woocommerce-multi-currency.wmc-shortcode.plain-vertical.layout11 .wmc-current-currency .wmc-text-currency-symbol').html(` ${subCurrencySymbol}`);
                }
            }

            $('select.wmc-nav').val(currentCurrency);

            {
                $('.wmc-price-switcher .wmc-currency.wmc-hidden').removeClass('wmc-hidden');
                $(`.wmc-price-switcher .wmc-currency[data-currency=${currentCurrency}]`).addClass('wmc-hidden');
                let flagClass = $(`.wmc-price-switcher .wmc-currency[data-currency=${currentCurrency}] i`).attr('class'),
                    symbolData = $(`.wmc-price-switcher .wmc-currency[data-currency=${currentCurrency}]`).attr('data-symbol');
                $('.wmc-price-switcher .wmc-current-currency i').removeClass().addClass(flagClass);
                $('.wmc-price-switcher .wmc-current-currency .wmc-prd-switcher-display').html(symbolData);
            }

        },

        cacheCompatibleByJSON() {
            let currentCurrency = getCookie('wmc_current_currency');
            if (!currentCurrency) {
                setTimeout(function () {
                    woocommerce_multi_currency.cacheCompatibleByJSON();
                }, 1000);

                return;
            }

            function overridePrice() {
                $('.wmc-wc-price').each(function (i, el) {
                    let listPrice = $(el).find('.wmc-price-cache-list').attr('data-wmc_price_cache');

                    if (listPrice) {
                        try {
                            listPrice = JSON.parse(listPrice);
                            let price = listPrice[currentCurrency];
                            if (price) {
                                $(el).after(price);
                                $(el).remove();
                            }
                        } catch (e) {

                        }
                    }
                });
            }

            overridePrice();

            $('.variations_form').on('show_variation', function () {
                overridePrice();
            })
        }
    };

    woocommerce_multi_currency.init();
    // Refresh when page is shown after back button (safari)
    $(window).on('pageshow', function (e) {
        woocommerce_multi_currency.cacheInit();
    });

    $(document.body).on('wmc_currency_reload_cache_compatible', function (e) {
        woocommerce_multi_currency.cacheInit();
    });
});