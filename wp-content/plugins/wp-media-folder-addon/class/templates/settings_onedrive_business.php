<div class="content-wpmf-onedrive">
    <?php
    $appInfo = $onedriveBusinessDrive->getClient();
    $hasToken = $onedriveBusinessDrive->loadToken();
    $btnconnect = '';

    if (is_wp_error($appInfo)) {
        echo '<div id="message" class="error"><p>' . esc_html($appInfo->get_error_message()) . '</p></div>';
        return false;
    }

    if ($appInfo) {
        $authUrl = $onedriveBusinessDrive->getAuthUrl();
        if (!is_wp_error($authUrl)) {
            $btnconnect = '<a class="ju-button orange-button waves-effect waves-light btndrive wpmf_onedrive_login" href="#"
         onclick="window.location.assign(\'' . $authUrl . '\',\'foo\',\'width=600,height=600\');return false;">' . __('Connect OneDrive Business', 'wpmfAddon') . '</a>';
        }
    }

    ?>

    <div id="config_onedrive_business" class="div_list wpmf_width_100">
        <?php
        if (!empty($business_config['OneDriveClientId']) && !empty($business_config['OneDriveClientSecret'])) {
            if (isset($business_config['connected']) && (int)$business_config['connected'] === 1) {
                $client = $onedriveBusinessDrive->startClient();
                $btndisconnect = '<a class="ju-button no-background orange-button waves-effect waves-light btndrive wpmf_onedrive_business_logout" href="#">' . __('Disconnect OneDrive Business', 'wpmfAddon') . '</a>';
                $driveInfo = $onedriveBusinessDrive->getDriveInfo();
                // phpcs:disable WordPress.Security.EscapeOutput -- Content already escaped in the method
                if (!$driveInfo || is_wp_error($driveInfo)) {
                    echo $btnconnect;
                } else {
                    echo $btndisconnect;
                }
                // phpcs:enable
            } else {
                echo $btnconnect; // phpcs:disable WordPress.Security.EscapeOutput -- Content already escaped in the method
            }
        }
        ?>
        <div class="wpmf_width_100 ju-settings-option">
            <div>
                <h4><?php esc_html_e('OneDrive Client ID', 'wpmfAddon') ?></h4>
                <div>
                    <input title name="OneDriveBusinessClientId" type="text"
                           class="onedrivebusinessconfig regular-text wpmf_width_100 p-lr-20"
                           value="<?php echo esc_attr($business_config['OneDriveClientId']) ?>">
                    <p class="description" id="tagline-description">
                        <?php esc_html_e('Insert your OneDrive Application Id here.
                     You can find this Id in the OneDrive dev center', 'wpmfAddon') ?>
                    </p>
                </div>
            </div>

            <div class="m-t-60">
                <h4><?php esc_html_e('OneDrive Client Secret', 'wpmfAddon') ?></h4>
                <div>
                    <input title name="OneDriveBusinessClientSecret" type="text"
                           class="onedrivebusinessconfig regular-text wpmf_width_100 p-lr-20"
                           value="<?php echo esc_attr($business_config['OneDriveClientSecret']) ?>">
                    <p class="description" id="tagline-description">
                        <?php esc_html_e('Insert your OneDrive Secret here.
                     You can find this secret in the OneDrive dev center', 'wpmfAddon') ?>
                    </p>
                </div>
            </div>

            <div class="m-t-60">
                <h4><?php esc_html_e('Redirect URIs', 'wpmfAddon') ?></h4>
                <div>
                    <input title name="redirect_uris" type="text" id="home" readonly
                           value="<?php echo esc_attr(admin_url('upload.php')); ?>"
                           class="regular-text wpmf_width_100 p-lr-20 code">
                </div>
            </div>

            <a target="_blank" class="m-t-50 ju-button no-background orange-button waves-effect waves-light"
               href="https://www.joomunited.com/documentation/wp-media-folder-cloud-addon#toc-iv-onedrive-integration">
                <?php esc_html_e('Read the online documentation', 'wpmfAddon') ?>
            </a>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.wpmf_onedrive_business_logout').click(function () {
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'wpmf_onedrive_business_logout'
                },
                success: function (response) {
                    window.location.href += "one_drive_box";
                    location.reload(true);
                }
            });
        });
    });
</script>