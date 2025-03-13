<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
require_once(WPMFAD_PLUGIN_DIR . '/class/wpmfDropbox.php');
require_once(WPMFAD_PLUGIN_DIR . '/class/wpmfHelper.php');
require_once(WPMFAD_PLUGIN_DIR . '/class/Dropbox/autoload.php');

/**
 * Class WpmfAddonDropboxAdmin
 * This class that holds most of the admin functionality for Dropbox
 */
class WpmfAddonDropboxAdmin extends WpmfAddonDropbox
{

    /**
     * WpmfAddonDropboxAdmin constructor.
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
        add_action('wp_ajax_wpmf-dbxdownload-file', array($this, 'downloadFile'));
        add_action('wp_ajax_nopriv_wpmf-dbxdownload-file', array($this, 'downloadFile'));
        add_action('wp_ajax_wpmf_dropbox_sync_folders', array($this, 'syncFoldersLibrary'));
        add_action('wp_ajax_wpmf_dropbox_sync_files', array($this, 'syncFilesLibrary'));
        add_action('wp_ajax_wpmf_dropbox_sync_remove_items', array($this, 'syncRemoveItems'));
        add_action('wp_ajax_wpmf_dropbox_sync_full', array($this, 'autoSyncWithCrontabMethod'));
        add_action('wp_ajax_nopriv_wpmf_dropbox_sync_full', array($this, 'autoSyncWithCrontabMethod'));
    }

    /**
     * Action hooks
     *
     * @return void
     */
    public function actionHooks()
    {
        add_action('admin_init', array($this, 'createRootDriveFolder'));
        add_action('wp_enqueue_scripts', array($this, 'frontendStyleScript'));
        add_action('enqueue_block_editor_assets', array($this, 'addEditorAssets'), 9999);
        add_action('add_attachment', array($this, 'addAttachment'), 10, 1);
        add_action('wpmf_create_folder', array($this, 'createFolderLibrary'), 10, 4);
        add_action('wpmf_before_delete_folder', array($this, 'deleteFolderLibrary'), 10, 1);
        add_action('wpmf_update_folder_name', array($this, 'updateFolderNameLibrary'), 10, 2);
        add_action('wpmf_move_folder', array($this, 'moveFolderLibrary'), 10, 3);
        add_action('wpmf_attachment_set_folder', array($this, 'moveFileLibrary'), 10, 3);
        add_action('delete_attachment', array($this, 'deleteAttachment'), 10);
        add_action('wpmfSyncDropbox', array($this, 'autoSyncWithCrontabMethod'));
    }

    /**
     * Filter hooks
     *
     * @return void
     */
    public function filterHooks()
    {
        add_filter('wpmfaddon_dbxsettings', array($this, 'renderSettings'), 10, 4);
        add_filter('wpmf_dropbox_import', array($this, 'importFile'), 10, 5);
        add_filter('the_content', array($this, 'theContent'));
        add_filter('wp_update_attachment_metadata', array($this, 'wpGenerateAttachmentMetadata'), 10, 2);
    }

    /**
     * Create root drive folder
     *
     * @return void
     */
    public function createRootDriveFolder()
    {
        $params = get_option('_wpmfAddon_dropbox_config');
        if (!empty($params['dropboxToken'])) {
            $inserted = wp_insert_term('Dropbox', WPMF_TAXO, array('parent' => 0, 'slug' => 'dropbox'));
            if (!is_wp_error($inserted)) {
                $root_id = $inserted['term_id'];
                add_term_meta($root_id, 'wpmf_drive_root_id', 'root');
                add_term_meta($root_id, 'wpmf_drive_root_type', 'dropbox');
            }
        }
    }

    /**
     * Add script to open video in new window
     *
     * @param string $content Content of current post/page
     *
     * @return mixed
     */
    public function theContent($content)
    {
        if (strpos($content, 'wpmf_dbx_video')) {
            wp_enqueue_script('wpmf-openwindow');
        }
        return $content;
    }

    /**
     * Dropbox settings html
     *
     * @param string $html          HTML
     * @param object $Dropbox       WpmfAddonDropbox class
     * @param array  $dropboxconfig Dropbox config
     * @param string $dropbox_error Dropbox error message
     *
     * @return string
     */
    public function renderSettings($html, $Dropbox, $dropboxconfig, $dropbox_error = '')
    {
        if (empty($dropboxconfig['dropboxKey'])) {
            $dropboxconfig['dropboxKey'] = '';
        }

        if (empty($dropboxconfig['dropboxSecret'])) {
            $dropboxconfig['dropboxSecret'] = '';
        }

        ob_start();
        require_once 'templates/settings_dropbox.php';
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
            'wpmfdropbox_blocks',
            plugins_url('assets/blocks/wpmfdropbox/block.js', dirname(__FILE__)),
            array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-data', 'wp-editor' ),
            WPMFAD_VERSION
        );

        wp_enqueue_style(
            'wpmfdropbox_blocks',
            plugins_url('assets/blocks/wpmfdropbox/style.css', dirname(__FILE__)),
            array(),
            WPMFAD_VERSION
        );

        $params = array(
            'l18n' => array(
                'btnopen' => __('Dropbox Media', 'wpmfAddon'),
                'dropbox_drive' => __('Dropbox', 'wpmfAddon'),
                'edit' => __('Edit', 'wpmfAddon'),
                'remove' => __('Remove', 'wpmfAddon')
            ),
            'vars' => array()
        );

        wp_localize_script('wpmfdropbox_blocks', 'wpmfdbxblocks', $params);
    }

    /**
     * Load scripts on frontend
     *
     * @return void
     */
    public function frontendStyleScript()
    {
        wp_register_script(
            'wpmf-openwindow',
            plugins_url('/assets/js/frontend_openwindow.js', dirname(__FILE__)),
            array(),
            WPMFAD_VERSION,
            true
        );
        wp_localize_script('wpmf-openwindow', 'wpmfaddonlang', array(
            'wpmf_images_path' => plugins_url('assets/images', dirname(__FILE__)),
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }

    /**
     * Logout dropbox app
     *
     * @return void
     */
    public function dbxLogout()
    {
        $dropbox = new WpmfAddonDropbox();
        $dropbox->logout();
    }
}
