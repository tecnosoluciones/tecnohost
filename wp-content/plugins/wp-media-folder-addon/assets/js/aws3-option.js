(function ($) {
    var wpmfs3Module = {
        current_page_download_s3: 1,
        process_bar_width_uploadS3: 0,
        process_bar_width_downloadS3: 0,
        complete_cicle_percent: 0,
        status_sync_s3: false,
        status_download_s3: false,
        circleProgress: function (s3sync_ok) {
            $('.syncs3-circle-bar').circleProgress({
                value: s3sync_ok / 100,
                size: 100,
                thickness: 8,
                startAngle: Math.PI * 1.5,
                fill: {
                    gradient: ["#34e0ff", "#5dadff"]
                }
            }).on('circle-animation-progress', function (event, progress) {
                $(this).find('strong').html(Math.round(s3sync_ok) + '<i>%</i>');
            });
        },

        selectBucket: function () {
            $('.btn-select-bucket').unbind('click').bind('click', function () {
                var $this = $(this);
                if ($this.closest('tr').hasClass('bucket-selected')) {
                    return;
                }
                var bucket = $this.closest('tr').data('bucket');
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'wpmf-select-bucket',
                        bucket: bucket,
                        wpmf_nonce: wpmf.vars.wpmf_nonce
                    },
                    beforeSend: function () {
                        if (!$('.select-bucket-spinner').length) {
                            $this.closest('td').append('<span class="spinner select-bucket-spinner"></span>');
                        }
                    },
                    success: function (res) {
                        if (res.status) {
                            $('.select-bucket-spinner').remove();
                            $('.current_bucket').text(res.bucket);
                            $('.lb-current-region').text(res.region);
                            $('.row_bucket').removeClass('bucket-selected').addClass('aws3-select-bucket');
                            $('.row_bucket .btn-select-bucket').text(wpmfS3.l18n.bucket_select);
                            $this.closest('.row_bucket').addClass('bucket-selected').removeClass('aws3-select-bucket');
                            $this.closest('.row_bucket').find('.btn-select-bucket').text(wpmfS3.l18n.bucket_selected);
                            $.magnificPopup.close();
                            wpmfs3Module.selectBucket();
                        } else {
                            if (res.msg !== "undefined") {
                                alert(res.msg);
                                $('.select-bucket-spinner').remove();
                            }
                        }
                    }
                });
            });
        },

        deleteBucket: function () {
            $('.delete-bucket').on('click', function () {
                var $this = $(this);
                if ($this.closest('tr').hasClass('bucket-selected')) {
                    return;
                }
                var bucket_name = $this.data('bucket');
                showDialog({
                    title: wpmfoption.l18n.confirm_delete_bucket,
                    negative: {
                        title: wpmfoption.l18n.cancel
                    },
                    positive: {
                        title: wpmfoption.l18n.delete_bucket,
                        onClick: function () {
                            $this.closest('tr').find('.spinner-delete-bucket').show();
                            $.ajax({
                                url: ajaxurl,
                                method: 'POST',
                                data: {
                                    action: 'wpmf-delete-bucket',
                                    name: bucket_name,
                                    wpmf_nonce: wpmf.vars.wpmf_nonce
                                },
                                success: function (res) {
                                    if (res.status) {
                                        $this.closest('tr').remove();
                                        $('.aws3_buckets_list .bucket-option[value="' + bucket_name + '"]').remove();
                                        if (!$('.table-list-buckets tbody tr').length) {
                                            $('.msg-no-bucket').addClass('show');
                                        } else {
                                            $('.msg-no-bucket').removeClass('show');
                                        }
                                    } else {
                                        if (typeof res.msg !== "undefined") {
                                            alert(res.msg);
                                        }
                                    }
                                }
                            });
                        }
                    }
                });
            });
        },

        /**
         * Do download s3
         *
         * @param paged current page
         */
        downloadS3: function (paged = 0) {
            wpmfs3Module.status_download_s3 = true;
            $('.wpmf-process-bar-download-s3-full').show();
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: "wpmf-download-s3",
                    paged: paged,
                    wpmf_nonce: wpmf.vars.wpmf_nonce
                },
                beforeSend: function() {
                    $('.wpmf-process-bar-download-s3-full').show();
                    if (!$('.wpmf-snackbar[data-id="download_s3_snackbar"]').length) {
                        wpmfSnackbarModule.show({
                            id: 'download_s3_snackbar',
                            content: wpmfS3.l18n.download_from_s3,
                            auto_close: false,
                            is_progress: true
                        });
                    }
                },
                success: function (response) {
                    /* Check status and set progress bar */
                    if (response.status) {
                        if (response.continue) {
                            if (typeof response.percent !== "undefined") {
                                wpmfs3Module.process_bar_width_downloadS3 = parseFloat(response.percent);
                            }

                            wpmfs3Module.updateProcessDownload(wpmfs3Module.process_bar_width_downloadS3);
                            wpmfs3Module.downloadS3();
                        } else {
                            let $snack = wpmfSnackbarModule.getFromId('download_s3_snackbar');
                            wpmfSnackbarModule.close($snack);
                            $('.wpmf-process-bar-download-s3-full').hide();
                            wpmfs3Module.updateProcessDownload(100);
                        }
                    } else {
                        alert(response.msg);
                    }
                },
                error: function () {
                    wpmfs3Module.downloadS3();
                }
            });
        },

        updateProcessDownload: function(percent)
        {
            if (percent > 100) {
                percent = 100;
            }

            $('.wpmf-process-bar-download-s3').css('width', percent + '%');
            $('.wpmf-process-bar-download-s3-full span').html(parseInt(percent) + '%');
        },

        /**
         * Do sync s3
         *
         * @param paged current page
         */
        uploadToS3: function (paged = 1) {
            if (!wpmfs3Module.status_sync_s3) {
                return;
            }

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: "wpmf-uploadto-s3",
                    paged: paged,
                    local_files_count: $('.s3_process_sync_wrap').data('local-files-count'),
                    wpmf_nonce: wpmf.vars.wpmf_nonce
                },
                beforeSend: function() {
                    $('.wpmf-process-bar-syncs3-full, .spinner-syncS3').show();
                    if (!$('.wpmf-snackbar[data-id="upload_to_s3_snackbar"]').length) {
                        wpmfSnackbarModule.show({
                            id: 'upload_to_s3_snackbar',
                            content: wpmfS3.l18n.upload_to_s3,
                            auto_close: false,
                            is_progress: true
                        });
                    }
                },
                success: function (response) {
                    if (typeof response.s3_percent !== "undefined") {
                        if (wpmfs3Module.complete_cicle_percent === 0) {
                            wpmfs3Module.circleProgress(response.s3_percent);
                            $('.status-text-s3-sync span').text(response.s3_percent);
                        } else {
                            if (response.s3_percent > wpmfs3Module.complete_cicle_percent) {
                                wpmfs3Module.circleProgress(response.s3_percent);
                                $('.status-text-s3-sync span').text(response.s3_percent);
                            }
                        }
                        wpmfs3Module.complete_cicle_percent = response.s3_percent;
                    }

                    /* Check status and set progress bar */
                    if (response.status) {
                        if (response.continue) {
                            if (typeof response.percent !== "undefined") {
                                wpmfs3Module.process_bar_width_uploadS3 += parseFloat(response.percent);
                            }

                            if (wpmfs3Module.process_bar_width_uploadS3 > 100) {
                                wpmfs3Module.process_bar_width_uploadS3 = 100;
                            }
                            
                            wpmfs3Module.updateProcessSync(wpmfs3Module.process_bar_width_uploadS3);
                            wpmfs3Module.uploadToS3(parseInt(paged) + 1);
                        } else {
                            let $snack = wpmfSnackbarModule.getFromId('upload_to_s3_snackbar');
                            wpmfSnackbarModule.close($snack);
                            $('.wpmf-process-bar-syncs3-full, .spinner-syncS3').hide();
                            wpmfs3Module.updateProcessSync(100);
                            wpmfs3Module.replaceLocalUrl();
                        }
                    } else {
                        alert(response.msg);
                    }
                },
                error: function () {
                    wpmfs3Module.uploadToS3(parseInt(paged) + 1);
                }
            });
        },

        /**
         * Do sync s3
         */
        replaceLocalUrl: function () {
            if (!wpmfs3Module.status_sync_s3) {
                return;
            }

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: "wpmf-s3-replace-local-url"
                },
                beforeSend: function() {
                    wpmfs3Module.process_bar_width_uploadS3 = 0;
                    $('.wpmf-process-bar-syncs3-full, .spinner-syncS3').show();
                    if (!$('.wpmf-snackbar[data-id="update_local_url"]').length) {
                        wpmfSnackbarModule.show({
                            id: 'update_local_url',
                            content: wpmfS3.l18n.update_local_url,
                            auto_close: false,
                            is_progress: true
                        });
                    }
                },
                success: function (response) {
                    if (response.status) {
                        if (response.continue) {
                            if (typeof response.percent !== "undefined") {
                                wpmfs3Module.process_bar_width_uploadS3 = parseInt(response.percent);
                            }

                            wpmfs3Module.updateProcessSync(wpmfs3Module.process_bar_width_uploadS3);
                            wpmfs3Module.replaceLocalUrl();
                        } else {
                            let $snack = wpmfSnackbarModule.getFromId('update_local_url');
                            wpmfSnackbarModule.close($snack);
                            wpmfs3Module.updateProcessSync(100);
                            if (response.remove) {
                                wpmfs3Module.awsRemoveFilesFromServer();
                            } else {
                                $('.wpmf-process-bar-syncs3-full, .spinner-syncS3').hide();
                                $('.btn-dosync-s3 label').html(wpmfS3.l18n.sync_btn_text);
                                wpmfs3Module.status_sync_s3 = false;
                            }
                        }
                    } else {
                        alert(response.msg);
                    }
                },
                error: function () {
                    wpmfs3Module.replaceLocalUrl();
                }
            });
        },

        /**
         * Remove files from server
         */
        awsRemoveFilesFromServer: function () {
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "wpmf-remove-file-server",
                    wpmf_nonce: wpmf.vars.wpmf_nonce
                },
                beforeSend: function() {
                    wpmfs3Module.process_bar_width_uploadS3 = 0;
                    $('.wpmf-process-bar-syncs3-full, .spinner-syncS3').show();
                    if (!$('.wpmf-snackbar[data-id="delete_local_files"]').length) {
                        wpmfSnackbarModule.show({
                            id: 'delete_local_files',
                            content: wpmfS3.l18n.delete_local_files,
                            auto_close: false,
                            is_progress: true
                        });
                    }
                },
                success: function (response) {
                    if (response.status) {
                        if (response.continue) {
                            if (typeof response.percent !== "undefined") {
                                wpmfs3Module.process_bar_width_uploadS3 = parseInt(response.percent);
                            }

                            wpmfs3Module.updateProcessSync(wpmfs3Module.process_bar_width_uploadS3);
                            wpmfs3Module.awsRemoveFilesFromServer();
                        } else {
                            let $snack = wpmfSnackbarModule.getFromId('delete_local_files');
                            wpmfSnackbarModule.close($snack);
                            $('.wpmf-process-bar-syncs3-full, .spinner-syncS3').hide();
                            $('.btn-dosync-s3 label').html(wpmfS3.l18n.sync_btn_text);
                            wpmfs3Module.status_sync_s3 = false;
                        }
                    }
                }
            });
        },

        updateProcessSync: function(percent)
        {
            if (percent > 100) {
                percent = 100;
            }

            $('.wpmf-process-bar-syncs3').css('width', percent + '%');
            $('.wpmf-process-bar-syncs3-full span').html(parseInt(percent) + '%');
        },

        getListBuckets: function () {
            $('.table-list-buckets table tbody').append('<tr><td colspan="4" style="text-align: center"><span class="spinner" style="float: none;visibility: visible"></span></td></tr>');
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'wpmf-get-buckets',
                    wpmf_nonce: wpmf.vars.wpmf_nonce
                },
                success: function (res) {
                    if (res.status) {
                        if (res.status) {
                            $('.table-list-buckets table tbody').html(res.html);
                            wpmfs3Module.deleteBucket();
                            wpmfs3Module.selectBucket();
                        }
                    }
                }
            });
        }
    };

    $(document).ready(function ($) {
        var s3sync_ok = parseInt($('#s3sync_ok').val());
        wpmfs3Module.circleProgress(s3sync_ok);
        wpmfs3Module.selectBucket();
        wpmfs3Module.deleteBucket();

        // dowwnload from s3
        $('.btn-open-popup-download').magnificPopup({
            type: 'inline',
            midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
        });

        $('.btn-download-s3').on('click', function () {
            wpmfs3Module.downloadS3(wpmfs3Module.current_page_download_s3);
        });

        $('.btn-cancel-popup-download-s3').on('click', function () {
            if (wpmfs3Module.status_download_s3) {
                location.reload();
            } else {
                $.magnificPopup.close();
            }
        });

        // run sync s3
        $('.btn-dosync-s3').on('click', function () {
            var enable = $(this).data('enable');
            var $this = $(this);
            if (parseInt(enable) === 0) {
                alert(wpmfS3.l18n.no_upload_s3_msg);
                return;
            }

            wpmfs3Module.status_sync_s3 = true;
            $this.find('label').html(wpmfS3.l18n.sync_process_text);
            if (wpmfs3Module.status_sync_s3) {
                wpmfs3Module.uploadToS3(1);
            }
        });

        $('.aws3-manage-bucket').magnificPopup({
            type: 'inline',
            midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
        });

        $('.create-bucket-btn').on('click', function () {
            var bucket_name = $('.new-bucket-name').val();
            var region = $('.new-bucket-region').val();
            if (bucket_name === '') {
                return;
            }

            $('.create-bucket-spinner').show().css('visibility', 'visible');
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'wpmf-create-bucket',
                    name: bucket_name,
                    region: region,
                    wpmf_nonce: wpmf.vars.wpmf_nonce
                },
                success: function (res) {
                    $('.create-bucket-spinner').hide();
                    if (!res.status) {
                        alert(res.msg);
                    } else {
                        $('.aws3_buckets_list').append('<option class="bucket-option" value="' + bucket_name + '">' + bucket_name + '</option>');
                        if (!$('.table-list-buckets tbody tr').length) {
                            $('.msg-no-bucket').addClass('show');
                        } else {
                            $('.msg-no-bucket').removeClass('show');
                        }

                        $('.current_bucket').text(bucket_name);
                        $('.lb-current-region').text(res.location_name);
                        wpmfs3Module.getListBuckets();
                    }
                }
            });
        });

        $('.cancel-bucket-btn').on('click', function () {
            $.magnificPopup.close();
        });

        $('[name="aws3_config[copy_files_to_bucket]"]').on('click', function () {
            if (!$(this).is(':checked')) {
                $('[name="aws3_config[remove_files_from_server]"]').prop('checked', false);
            }
        });
    });
})(jQuery);