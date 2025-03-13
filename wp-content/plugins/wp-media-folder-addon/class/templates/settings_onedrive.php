<div class="content-wpmf-onedrive">
    <?php
    $appInfo = $onedriveDrive->getClient();
    $hasToken = $onedriveDrive->loadToken();
    $btnconnect = '';

    if (is_wp_error($appInfo)) {
        echo '<div id="message" class="error"><p>' . esc_html($appInfo->get_error_message()) . '</p></div>';
        return false;
    }

    if ($appInfo) {
        $authUrl = $onedriveDrive->getAuthUrl();
        if (!is_wp_error($authUrl)) {
            $btnconnect = '<a class="ju-button orange-button waves-effect waves-light btndrive wpmf_onedrive_login" href="#"
         onclick="window.location.assign(\'' . $authUrl . '\',\'foo\',\'width=600,height=600\');return false;">' . __('Connect OneDrive', 'wpmfAddon') . '</a>';
        }
    }

    ?>

    <div id="config_onedrive" class="div_list wpmf_width_100">
        <?php
        if (!empty($onedrive_config['OneDriveClientId']) && !empty($onedrive_config['OneDriveClientSecret'])) {
            if (isset($onedrive_config['connected']) && (int)$onedrive_config['connected'] === 1) {
                $client = $onedriveDrive->startClient();
                $btndisconnect = '<a class="ju-button no-background orange-button waves-effect waves-light btndrive wpmf_onedrive_logout" href="#">' . __('Disconnect OneDrive', 'wpmfAddon') . '</a>';
                $driveInfo = $onedriveDrive->getDriveInfo();
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
                <h4 data-alt="<?php esc_attr_e('Define the type of link use by default when you insert a cloud media in a page or post. Public link will generate a public accessible link for your file and affect the appropriate rights on the cloud file. Private link will hide the cloud link to keep the original access right of your file', 'wpmfAddon') ?>" class="wpmfqtip"><?php esc_html_e('Media link type', 'wpmfAddon') ?></h4>
                <div>
                    <select name="onedrive_link_type">
                        <option value="public" <?php selected($onedrive_config['link_type'], 'public') ?>><?php esc_html_e('Public link', 'wpmfAddon') ?></option>
                        <option value="private" <?php selected($onedrive_config['link_type'], 'private') ?>><?php esc_html_e('Private link', 'wpmfAddon') ?></option>
                    </select>
                </div>
            </div>

            <div>
                <h4><?php esc_html_e('OneDrive Client ID', 'wpmfAddon') ?></h4>
                <div>
                    <input title name="OneDriveClientId" type="text"
                           class="onedriveconfig regular-text wpmf_width_100 p-lr-20"
                           value="<?php echo esc_attr($onedrive_config['OneDriveClientId']) ?>">

                    <p class="description" id="tagline-description">
                        <?php esc_html_e('Insert your OneDrive Application Id here.
                     You can find this Id in the OneDrive dev center', 'wpmfAddon') ?>
                    </p>
                </div>
            </div>

            <div class="m-t-60">
                <h4><?php esc_html_e('OneDrive Client Secret', 'wpmfAddon') ?></h4>
                <div>
                    <input title name="OneDriveClientSecret" type="text"
                           class="onedriveconfig regular-text wpmf_width_100 p-lr-20"
                           value="<?php echo esc_attr($onedrive_config['OneDriveClientSecret']) ?>">

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
                           value="<?php echo esc_attr(admin_url()); ?>"
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
        $('.wpmf_onedrive_logout').click(function () {
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'wpmf_onedrive_logout'
                },
                success: function (response) {
                    window.location.href += "one_drive_box";
                    location.reload(true);
                }
            });
        });
    });
</script>