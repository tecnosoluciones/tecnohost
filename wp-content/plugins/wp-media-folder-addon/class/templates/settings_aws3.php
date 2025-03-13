<?php
wp_enqueue_script('wpmf-popup');
wp_enqueue_style('wpmf-css-popup');
?>
<div>
    <div id="download-s3-popup" class="white-popup mfp-hide">
        <h3><?php esc_html_e('Retrieve amazon S3 media', 'wpmfAddon') ?></h3>
        <p class="description"><?php esc_html_e('This action will retrieve all your media from your Amazon S3 bucket and copy it back to your server, links to media will be reverted back to the original local image. This is useful when you want to remove the Amazon integration only.', 'wpmfAddon') ?></p>
        <div class="wpmf-process-bar-full wpmf-process-bar-download-s3-full s3_process_download_wrap">
            <div class="wpmf-process-bar wpmf-process-bar-download-s3" data-w="0"></div>
            <span>0%</span>
        </div>
        <div class="action_download_s3">
            <a class="ju-button wpmf-small-btn btn-cancel-popup-download-s3"><?php esc_html_e('Cancel', 'wpmfAddon') ?></a>
            <a class="ju-button orange-button wpmf-small-btn btn-download-s3"><?php esc_html_e('Retrieve amazon S3 media', 'wpmfAddon') ?></a>
        </div>
    </div>
    <div id="manage-bucket" class="white-popup mfp-hide">
        <div class="table-list-buckets m-b-40">
            <h3><?php esc_html_e('Select an existing bucket', 'wpmfAddon') ?></h3>
            <table class="wpmf_width_100">
                <thead>
                <tr>
                    <th><?php esc_html_e('Bucket name', 'wpmfAddon') ?></th>
                    <th><?php esc_html_e('Date created', 'wpmfAddon') ?></th>
                    <th style="width: 30%"></th>
                    <th style="width: 10%"></th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($list_buckets['Buckets'])) :
                    foreach ($list_buckets['Buckets'] as $bucket) :
                        ?>
                        <tr class="row_bucket <?php echo (isset($aws3config['bucket']) && $aws3config['bucket'] === $bucket['Name']) ? 'bucket-selected' : 'aws3-select-bucket' ?>"
                            data-bucket="<?php echo esc_attr($bucket['Name']) ?>">
                            <td><?php echo esc_html($bucket['Name']) ?></td>
                            <td><?php echo esc_html($bucket['CreationDate']) ?></td>
                            <td>
                                <?php if (isset($aws3config['bucket']) && $aws3config['bucket'] === $bucket['Name']) : ?>
                                    <label class="btn-select-bucket">
                                        <?php esc_html_e('Selected bucket', 'wpmfAddon') ?>
                                    </label>
                                <?php else : ?>
                                    <label class="btn-select-bucket">
                                        <?php esc_html_e('Select bucket', 'wpmfAddon') ?>
                                    </label>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="delete-bucket wpmfqtip"
                                   data-alt="<?php esc_html_e('Delete bucket', 'wpmfAddon') ?>"
                                   data-bucket="<?php echo esc_attr($bucket['Name']) ?>"><i class="material-icons">delete_outline</i></a>
                                <img src="<?php echo esc_url(WPMFAD_PLUGIN_URL . 'assets/images/spinner.gif') ?>"
                                     class="spinner-delete-bucket">
                            </td>
                        </tr>
                        <?php
                    endforeach;
                endif; ?>
                </tbody>
            </table>
        </div>

        <div>
            <h3><?php esc_html_e('Create a new bucket', 'wpmfAddon') ?></h3>
            <div>
                <label>
                    <input type="text" class="wpmf_width_100 new-bucket-name"
                           placeholder="<?php esc_html_e('New bucket name', 'wpmfAddon') ?>">
                </label>
            </div>
        </div>

        <div>
            <h3><?php esc_html_e('Region', 'wpmfAddon') ?></h3>
            <div>
                <label>
                    <select class="new-bucket-region">
                        <?php
                        if (!empty($aws3->regions)) {
                            foreach ($aws3->regions as $k_regions => $v_region) :
                                ?>
                                <option value="<?php echo esc_attr($k_regions); ?>"><?php echo esc_html($v_region); ?></option>
                                <?php
                            endforeach;
                        }
                        ?>
                    </select>
                </label>
            </div>
        </div>

        <div class="wpmf_width_100 m-t-20 action-aws-btn">
            <button type="button"
                    class="ju-button wpmf-small-btn cancel-bucket-btn"><?php esc_html_e('Cancel', 'wpmfAddon') ?></button>
            <button type="button"
                    class="ju-button orange-button wpmf-small-btn create-bucket-btn"><?php esc_html_e('Create', 'wpmfAddon') ?></button>
            <span class="spinner create-bucket-spinner"></span>
        </div>
    </div>

    <div class="wpmf_width_100 wpmf-inline">
        <div class="ju-settings-option">
            <div class="wpmf_row_full">
                <input type="hidden" name="aws3_config[copy_files_to_bucket]" value="0">
                <label data-alt="<?php esc_html_e('When a file is uploaded to your media library, a copy will be sent to Amazon bucket. On frontend the media will be loaded from the Amazon server', 'wpmfAddon'); ?>"
                       class="ju-setting-label text"><?php esc_html_e('Copy to Amazon S3', 'wpmfAddon') ?></label>
                <div class="ju-switch-button">
                    <label class="switch">
                        <input type="checkbox" id="cb_option_mediafolder" name="aws3_config[copy_files_to_bucket]"
                               value="1"
                            <?php
                            if (isset($copy_files_to_bucket) && (int) $copy_files_to_bucket === 1) {
                                echo 'checked';
                            }
                            ?>
                        >
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="ju-settings-option wpmf_right m-r-0">
            <div class="wpmf_row_full">
                <input type="hidden" name="aws3_config[remove_files_from_server]" value="0">
                <label data-alt="<?php esc_html_e('When a file has been uploaded to the Amazon S3 bucket, the local copy will be deleted', 'wpmfAddon'); ?>"
                       class="ju-setting-label text"><?php esc_html_e('Remove after Amazon S3 upload', 'wpmfAddon') ?></label>
                <div class="ju-switch-button">
                    <label class="switch">
                        <input type="checkbox" id="cb_option_mediafolder" name="aws3_config[remove_files_from_server]"
                               value="1"
                            <?php
                            if (isset($remove_files_from_server) && (int) $remove_files_from_server === 1) {
                                echo 'checked';
                            }
                            ?>
                        >
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="ju-settings-option wpmf-no-margin">
            <div class="wpmf_row_full">
                <input type="hidden" name="aws3_config[attachment_label]" value="0">
                <label data-alt="<?php esc_html_e('Apply a label on each media to visually see that the media is on Amazon S3', 'wpmfAddon'); ?>"
                       class="ju-setting-label text"><?php esc_html_e('Amazon attachment label', 'wpmfAddon') ?></label>
                <div class="ju-switch-button">
                    <label class="switch">
                        <input type="checkbox" id="cb_option_mediafolder" name="aws3_config[attachment_label]"
                               value="1"
                            <?php
                            if (isset($attachment_label) && (int) $attachment_label === 1) {
                                echo 'checked';
                            }
                            ?>
                        >
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="sync-aws3-wrap">
        <div class="s3-process-wrap">
            <div class="s3-process-left">
                <label class="status-text-s3-sync"><span><?php echo esc_html($s3_percent['s3_percent']); ?></span><?php esc_html_e('% of your Media Library has been uploaded to Amazon S3', 'wpmfAddon') ?>
                </label>
                <div class="s3-button-sync-wrap">
                    <div class="wpmf_row_full">
                        <div
                                data-enable="<?php echo !empty($aws3config['copy_files_to_bucket']) ? 1 : 0 ?>"
                                data-alt="<?php esc_attr_e('Synchronize the whole media library with Amazon S3 Note that it applies the options if checked above like removing media from local server.', 'wpmfAddon') ?>"
                                class="wpmfqtip ju-button wpmf-small-btn <?php echo ($connect) ? 'btn-dosync-s3 btn-sync-s3' : 'btn-dosync-s3-disabled' ?>">
                            <labeL><?php esc_html_e('Synchronize with Amazon S3', 'wpmfAddon') ?></labeL><span
                                    class="spinner spinner-syncS3" style="visibility: visible"></span></div>
                    </div>
                </div>
            </div>
            <div class="s3-process-right">
                <div class="syncs3-circle-bar"><strong></strong></div>
                <input type="hidden" id="progressController" value="<?php echo esc_attr($s3_percent['s3_percent']) ?>"/>
                <input type="hidden" id="s3sync_ok" value="<?php echo esc_attr($s3_percent['s3_percent']) ?>"/>
            </div>
        </div>

        <div class="wpmf-process-bar-full wpmf-process-bar-syncs3-full s3_process_sync_wrap" data-local-files-count="<?php echo esc_attr($s3_percent['local_files_count']) ?>">
            <div class="wpmf-process-bar wpmf-process-bar-syncs3" data-w="0"></div>
            <span>0%</span>
        </div>
    </div>
    <div class="wpmf_width_100 wpmf-inline p-d-20 aws3-connect-wrap">
        <h4><?php esc_html_e('Access Key ID', 'wpmfAddon') ?></h4>
        <div>
            <input title="<?php esc_attr_e('Access Key ID', 'wpmfAddon') ?>" autocomplete="off"
                   name="aws3_config[credentials][key]" type="text" class="regular-text wpmf_width_100 p-lr-20"
                   value="<?php echo esc_attr($aws3config['credentials']['key']) ?>">
        </div>

        <div class="m-t-60">
            <h4><?php esc_html_e('Secret Access Key', 'wpmfAddon') ?></h4>
            <div>
                <input title="<?php esc_attr_e('Secret Access Key', 'wpmfAddon') ?>" autocomplete="off"
                       name="aws3_config[credentials][secret]" type="text"
                       class="regular-text wpmf_width_100 p-lr-20"
                       value="<?php echo esc_attr($aws3config['credentials']['secret']) ?>">
            </div>
        </div>

        <?php
        if (!$connect && !empty($aws3config['credentials']['key']) && !empty($aws3config['credentials']['secret'])) {
            echo '<p class="wpmf-warning"><b>' . esc_html__('Connection failed: ', 'wpmfAddon') . '</b>' . esc_html($msg) . '</p>';
        }
        ?>

        <?php if ($connect) : ?>
            <div class="m-t-60">
                <h4><?php esc_html_e('Bucket', 'wpmfAddon') ?></h4>
                <div>
                    <label>
                        <?php if (!empty($list_buckets['Buckets'])) : ?>
                            <?php if (!empty($aws3config['bucket'])) : ?>
                                <b class="current_bucket"><?php echo esc_html($aws3config['bucket']); ?></b>
                            <?php else : ?>
                                <b class="current_bucket"><?php esc_html_e('Please select an Amazon bucket to start using S3 server', 'wpmfAddon') ?></b>
                            <?php endif; ?>
                        <?php else : ?>
                            <b class="current_bucket"></b>
                        <?php endif; ?>
                        <?php
                        if (!empty($location_name) && !empty($list_buckets['Buckets'])) {
                            echo '<span class="lb-current-region">' . esc_html($location_name) . '</span>';
                        } else {
                            echo '<span class="lb-current-region"></span>';
                        }
                        ?>
                        <?php if (empty($list_buckets['Buckets'])) : ?>
                            <div class="msg-no-bucket show">
                                <label><?php esc_html_e('No bucket found, please add a bucket to be able to use this feature', 'wpmfAddon') ?></label>
                                <a class="ju-button orange-button wpmf-small-btn aws3-manage-bucket"
                                   href="#manage-bucket">
                                    <?php esc_html_e('Add bucket', 'wpmfAddon') ?>
                                </a>
                            </div>
                        <?php else : ?>
                            <div class="msg-no-bucket">
                                <label><?php esc_html_e('No bucket found, please add a bucket to be able to use this feature', 'wpmfAddon') ?></label>
                                <a class="ju-button orange-button wpmf-small-btn aws3-manage-bucket"
                                   href="#manage-bucket">
                                    <?php esc_html_e('Add bucket', 'wpmfAddon') ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <a class="ju-button orange-button wpmf-small-btn aws3-manage-bucket"
                           href="#manage-bucket">
                            <?php esc_html_e('Bucket settings and selection', 'wpmfAddon') ?>
                        </a>
                        <?php if (!empty($aws3config['bucket']) && !empty($list_buckets['Buckets'])) : ?>
                            <a class="ju-button wpmf-small-btn aws3-view-console"
                               href="https://console.aws.amazon.com/s3/buckets/<?php echo esc_html($aws3config['bucket'] . '/') ?>"
                               target="_blank"><?php esc_html_e('View console', 'wpmfAddon') ?></a>
                        <?php endif; ?>
                    </label>
                </div>
            </div>
        <?php endif; ?>
        <div>
            <a target="_blank" class="m-t-30 ju-button no-background orange-button waves-effect waves-light"
               href="https://www.joomunited.com/documentation/wp-media-folder-cloud-addon#toc-v-amazon-s3-integration">
                <?php esc_html_e('Read the online documentation', 'wpmfAddon') ?>
            </a>
            <?php
            if ($connect) :
                ?>
                <a class="m-t-30 ju-button no-background waves-effect waves-light btn-open-popup-download"
                   href="#download-s3-popup"
                >
                    <?php esc_html_e('Retrieve amazon S3 media', 'wpmfAddon') ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>