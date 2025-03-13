<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
require_once(WPMFAD_PLUGIN_DIR . '/class/wpmfGoogle.php');
require_once(WPMFAD_PLUGIN_DIR . '/class/wpmfHelper.php');
require_once(WPMFAD_PLUGIN_DIR . '/class/Google/autoload.php');

/**
 * Class WpmfAddonGoogle
 * This class that holds most of the admin functionality for Google drive
 */
class WpmfAddonGoogle extends WpmfAddonGoogleDrive
{

    /**
     * WpmfAddonGoogle constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->actionHooks();
        $this->filterHooks();
        $this->handleAjax();
    }

    /**
     * Ajax action
     *
     * @return void
     */
    public function handleAjax()
    {
        add_action('wp_ajax_wpmf-download-file', array($this, 'downloadFile'));
        add_action('wp_ajax_nopriv_wpmf-download-file', array($this, 'downloadFile'));
        add_action('wp_ajax_wpmf-preview-file', array($this, 'previewFile'));
        add_action('wp_ajax_nopriv_wpmf-preview-file', array($this, 'previewFile'));
        add_action('wp_ajax_wpmf_google_sync_folders', array($this, 'syncFoldersLibrary'));
        add_action('wp_ajax_wpmf_google_sync_files', array($this, 'syncFilesLibrary'));
        add_action('wp_ajax_wpmf_google_sync_remove_items', array($this, 'syncRemoveItems'));
        add_action('wp_ajax_wpmf_google_sync_full', array($this, 'autoSyncWithCrontabMethod'));
        add_action('wp_ajax_nopriv_wpmf_google_sync_full', array($this, 'autoSyncWithCrontabMethod'));
    }

    /**
     * Action hooks
     *
     * @return void
     */
    public function actionHooks()
    {
        add_action('admin_init', array($this, 'createRootDriveFolder'));
        add_action('enqueue_block_editor_assets', array($this, 'addEditorAssets'), 9999);
        add_action('add_attachment', array($this, 'addAttachment'), 10, 1);
        add_action('wpmf_create_folder', array($this, 'createFolderLibrary'), 10, 4);
        add_action('wpmf_before_delete_folder', array($this, 'deleteFolderLibrary'), 10, 1);
        add_action('wpmf_update_folder_name', array($this, 'updateFolderNameLibrary'), 10, 2);
        add_action('wpmf_move_folder', array($this, 'moveFolderLibrary'), 10, 3);
        add_action('wpmf_attachment_set_folder', array($this, 'moveFileLibrary'), 10, 3);
        add_action('delete_attachment', array($this, 'deleteAttachment'), 10);
        add_action('wpmfSyncGoogle', array($this, 'autoSyncWithCrontabMethod'));
    }

    /**
     * Filter hooks
     *
     * @return void
     */
    public function filterHooks()
    {
        add_filter('wpmf_google_import', array($this, 'importFile'), 10, 5);
        add_filter('wpmfaddon_ggsettings', array($this, 'renderSettings'), 10, 3);
        add_filter('wpmfaddon_synchronization_settings', array($this, 'renderSynchronizationSettings'), 10, 1);
        add_filter('wp_update_attachment_metadata', array($this, 'wpGenerateAttachmentMetadata'), 10, 2);
    }

    /**
     * Create root drive folder
     *
     * @return void
     */
    public function createRootDriveFolder()
    {
        $params = get_option('_wpmfAddon_cloud_config');
        if (!empty($params['googleCredentials']) && !empty($params['googleBaseFolder'])) {
            $inserted = wp_insert_term('Google Drive', WPMF_TAXO, array('parent' => 0, 'slug' => 'google-drive'));
            if (!is_wp_error($inserted)) {
                $root_id = $inserted['term_id'];
                add_term_meta($root_id, 'wpmf_drive_root_id', $params['googleBaseFolder']);
                add_term_meta($root_id, 'wpmf_drive_root_type', 'google_drive');
            }
        }
    }

    /**
     * Render google drive settings
     *
     * @param string $html         HTML
     * @param object $googleDrive  WpmfAddonGoogleDrive class
     * @param array  $googleconfig Google drive config
     *
     * @return string
     */
    public function renderSettings($html, $googleDrive, $googleconfig)
    {
        if (empty($googleconfig['googleClientId'])) {
            $googleconfig['googleClientId'] = '';
        }

        if (empty($googleconfig['googleClientSecret'])) {
            $googleconfig['googleClientSecret'] = '';
        }

        ob_start();
        require_once 'templates/settings_google_drive.php';
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /**
     * Render synchronization settings
     *
     * @param string $html HTML
     *
     * @return string
     */
    public function renderSynchronizationSettings($html)
    {
        $odv_settings = get_option('_wpmfAddon_onedrive_config');
        $odvbn_settings = get_option('_wpmfAddon_onedrive_business_config');
        $dropbox_settings = get_option('_wpmfAddon_dropbox_config');
        $google_settings = get_option('_wpmfAddon_cloud_config');

        $sync_method = wpmfGetOption('sync_method');
        $sync_periodicity = wpmfGetOption('sync_periodicity');
        ob_start();
        require_once 'templates/synchronization.php';
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /**
     * Enqueue styles and scripts for gutenberg
     *
     * @return void
     */
    public function addEditorAssets()
    {
        wp_enqueue_script(
            'wpmfgoogle_blocks',
            plugins_url('assets/blocks/wpmfgoogle/block.js', dirname(__FILE__)),
            array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-data', 'wp-editor' ),
            WPMFAD_VERSION
        );

        wp_enqueue_style(
            'wpmfgoogle_blocks',
            plugins_url('assets/blocks/wpmfgoogle/style.css', dirname(__FILE__)),
            array(),
            WPMFAD_VERSION
        );

        $params = array(
            'l18n' => array(
                'btnopen' => __('Google Drive Media', 'wpmfAddon'),
                'google_drive' => __('Google Drive', 'wpmfAddon'),
                'edit' => __('Edit', 'wpmfAddon'),
                'remove' => __('Remove', 'wpmfAddon')
            ),
            'vars' => array()
        );

        wp_localize_script('wpmfgoogle_blocks', 'wpmfblocks', $params);
    }

    /**
     * Access google drive
     *
     * @return void
     */
    public function ggAuthenticated()
    {
        $google      = new WpmfAddonGoogleDrive();
        $credentials = $google->authenticate();
        $google->storeCredentials($credentials);
        $data                     = $this->getParams();
        //Check if WPMF folder exists and create if not
        if (empty($data['googleBaseFolder'])) {
            $folder                   = $google->createFolder('WP Media Folder - ' . get_bloginfo('name'));
            $data['googleBaseFolder'] = $folder->id;
        } else {
            $client = $this->getClient($data);
            $service     = new WpmfGoogle_Service_Drive($client);
            $folder     = $service->files->get($data['googleBaseFolder']);
            if (is_wp_error($folder)) {
                $folder                   = $google->createFolder('WP Media Folder - ' . get_bloginfo('name'));
            }

            $data['googleBaseFolder'] = $folder->id;
        }

        $data['connected']  = 1;
        $this->setParams($data);
        $this->redirect(admin_url('options-general.php?page=option-folder#google_drive_box'));
    }

    /**
     * Get google config
     *
     * @return mixed
     */
    public function getParams()
    {
        return WpmfAddonHelper::getAllCloudConfigs();
    }

    /**
     * Set google config
     *
     * @param array $data Data to set config
     *
     * @return void
     */
    public function setParams($data)
    {
        WpmfAddonHelper::saveCloudConfigs($data);
    }

    /**
     * Redirect url
     *
     * @param string $location URL
     *
     * @return void
     */
    public function redirect($location)
    {
        if (!headers_sent()) {
            header('Location: ' . $location, true, 303);
        } else {
            // phpcs:ignore WordPress.Security.EscapeOutput -- Content already escaped in the method
            echo "<script>document.location.href='" . str_replace("'", '&apos;', $location) . "';</script>\n";
        }
    }

    /**
     * Logout google drive app
     *
     * @return void
     */
    public function ggLogout()
    {
        $google = new WpmfAddonGoogleDrive();
        $google->logout();
        $data                      = $this->getParams();
        $data['connected']  = 0;
        $this->setParams($data);
        $this->redirect(admin_url('options-general.php?page=option-folder#google_drive_box'));
    }
}
