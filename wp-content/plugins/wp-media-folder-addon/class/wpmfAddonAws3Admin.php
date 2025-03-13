<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
require_once(WPMFAD_PLUGIN_DIR . '/class/wpmfAws3.php');

use WP_Media_Folder\Aws\S3\Exception\S3Exception;

/**
 * Class WpmfAddonOneDriveAdmin
 * This class that holds most of the admin functionality for OneDrive
 */
class WpmfAddonAws3Admin
{

    /**
     * Amazon settings
     *
     * @var array
     */
    public $aws3_settings = array();

    /**
     * Amazon default settings
     *
     * @var array
     */
    public $aws3_config_default = array();

    /**
     * WpmfAddonOneDriveAdmin constructor.
     */
    public function __construct()
    {
        if (is_plugin_active('wp-media-folder/wp-media-folder.php')) {
            $this->runUpgrades();
            $aws3config                = get_option('_wpmfAddon_aws3_config');
            $this->aws3_config_default = array(
                'signature_version'        => 'v4',
                'version'                  => '2006-03-01',
                'region'                   => 'us-east-1',
                'bucket'                   => 0,
                'credentials'              => array(
                    'key'    => '',
                    'secret' => ''
                ),
                'copy_files_to_bucket'     => 0,
                'remove_files_from_server' => 0,
                'attachment_label'         => 0
            );

            if (is_array($aws3config)) {
                $this->aws3_settings = array_merge($this->aws3_config_default, $aws3config);
            } else {
                $this->aws3_settings = $this->aws3_config_default;
            }

            $this->actionHooks();
            $this->filterHooks();
            $this->handleAjax();
        }
    }

    /**
     * Ajax action
     *
     * @return void
     */
    public function handleAjax()
    {
        add_action('wp_ajax_wpmf-get-buckets', array($this, 'getBucketsList'));
        add_action('wp_ajax_wpmf-create-bucket', array($this, 'createBucket'));
        add_action('wp_ajax_wpmf-delete-bucket', array($this, 'deleteBucket'));
        add_action('wp_ajax_wpmf-remove-file-server', array($this, 'removeFilesFromServer'));
        add_action('wp_ajax_wpmf-remove-file-server-after-upload', array($this, 'removeFilesAfterUpload'));
        add_action('wp_ajax_wpmf-select-bucket', array($this, 'selectBucket'));
        add_action('wp_ajax_wpmf-uploadto-s3', array($this, 'uploadToS3'));
        add_action('wp_ajax_wpmf-s3-replace-local-url', array($this, 'replaceLocalUrl'));
        add_action('wp_ajax_wpmf-download-s3', array($this, 'downloadObject'));
    }

    /**
     * Action hooks
     *
     * @return void
     */
    public function actionHooks()
    {
        if (!empty($this->aws3_settings['copy_files_to_bucket'])) {
            add_action('add_attachment', array($this, 'addAttachment'), 10, 1);
        }

        add_action('admin_enqueue_scripts', array($this, 'loadAdminScripts'));
        add_action('add_meta_boxes', array($this, 'attachmentMetaBox'));
    }

    /**
     * Filter hooks
     *
     * @return void
     */
    public function filterHooks()
    {
        add_filter('wpmfaddon_aws3settings', array($this, 'renderSettings'), 10, 1);
        add_filter('delete_attachment', array($this, 'deleteAttachment'), 20);
        add_filter('wp_get_attachment_url', array($this, 'wpGetAttachmentUrl'), 99, 2);
        add_filter('get_attached_file', array($this, 'getAttachedFile'), 10, 2);
        add_filter('wpmf_get_attached_file', array($this, 'getAttachedS3File'), 20, 3);
        add_filter('wpmf_get_attached_file', array($this, 'imageEditorDownloadFile'), 10, 3);
        add_filter('wpmf_get_attached_file', array($this, 'regenerateThumbnails'), 10, 3);
        add_filter('wpmf_get_attached_file', array($this, 'cropImage'), 10, 3);
        add_filter('wp_calculate_image_srcset', array($this, 'wpCalculateImageSrcset'), 10, 5);
        add_filter('wp_calculate_image_srcset_meta', array($this, 'wpCalculateImageSrcsetMeta'), 10, 4);
        add_filter('wp_prepare_attachment_for_js', array($this, 'wpPrepareAttachmentForJs'), 99, 3);
        add_filter('wp_update_attachment_metadata', array($this, 'wpUpdateAttachmentMetadata'), 110, 2);
    }

    /**
     * Filters the attachment data prepared for JavaScript.
     * Base on /wp-includes/media.php
     *
     * @param array          $response   Array of prepared attachment data.
     * @param integer|object $attachment Attachment ID or object.
     * @param array          $meta       Array of attachment meta data.
     *
     * @return mixed $response
     */
    public function wpPrepareAttachmentForJs($response, $attachment, $meta)
    {
        $infos = get_post_meta($attachment->ID, 'wpmf_awsS3_info', true);
        if (empty($infos)) {
            return $response;
        }

        $response['aws3_infos'] = $infos;
        return $response;
    }

    /**
     * Alter the image meta data to add srcset support for object versioned S3 URLs
     *
     * @param array   $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
     * @param array   $size_array    Array of width and height values in pixels (in that order).
     * @param string  $image_src     The 'src' of the image.
     * @param integer $attachment_id The image attachment ID to pass to the filter
     *
     * @return array
     */
    public function wpCalculateImageSrcsetMeta($image_meta, $size_array, $image_src, $attachment_id)
    {
        if (empty($image_meta['file'])) {
            return $image_meta;
        }

        if (false !== strpos($image_src, $image_meta['file'])) {
            return $image_meta;
        }

        //  return if not on s3
        $infos = get_post_meta($attachment_id, 'wpmf_awsS3_info', true);
        if (empty($infos)) {
            return $image_meta;
        }

        $image_meta['file'] = rawurlencode(wp_basename($image_meta['file']));
        if (!empty($image_meta['sizes'])) {
            $image_meta['sizes'] = array_map(function ($size) {
                $size['file'] = rawurlencode($size['file']);
                return $size;
            }, $image_meta['sizes']);
        }

        return $image_meta;
    }

    /**
     * Replace local URLs with S3 ones for srcset image sources
     *
     * @param array   $srcs          Source
     * @param array   $size_array    Array of width and height values in pixels (in that order).
     * @param string  $image_src     The 'src' of the image.
     * @param array   $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
     * @param integer $attachment_id The image attachment ID to pass to the filter
     *
     * @return array
     */
    public function wpCalculateImageSrcset($srcs, $size_array, $image_src, $image_meta, $attachment_id)
    {
        if (!is_array($srcs)) {
            return $srcs;
        }

        //  return if not on s3
        $infos = get_post_meta($attachment_id, 'wpmf_awsS3_info', true);
        if (empty($infos)) {
            return $srcs;
        }

        foreach ($srcs as $width => $source) {
            $size = $this->getImageSizeByWidth($image_meta['sizes'], $width, wp_basename($source['url']));
            if (!empty($size)) {
                $url                 = wp_get_attachment_image_src($attachment_id, $size);
                $srcs[$width]['url'] = $url[0];
            } else {
                $url                 = wp_get_attachment_url($attachment_id);
                $srcs[$width]['url'] = $url;
            }
        }

        return $srcs;
    }

    /**
     * Helper function to find size name from width and filename
     *
     * @param array  $sizes    List sizes
     * @param string $width    Width
     * @param string $filename File name
     *
     * @return null|string
     */
    public function getImageSizeByWidth($sizes, $width, $filename)
    {
        foreach ($sizes as $size_name => $size) {
            if ($width === (int) $size['width'] && $filename === $size['file']) {
                return $size_name;
            }
        }

        return null;
    }

    /**
     * Check if the plugin need to run an update of db or options
     *
     * @return void
     */
    public function runUpgrades()
    {
        $version = get_option('wpmf_addon_version', '1.0.0');
        // Up to date, nothing to do
        if ($version === WPMFAD_VERSION) {
            return;
        }

        if (version_compare($version, '2.2.0', '<')) {
            global $wpdb;
            $wpdb->query('CREATE TABLE `' . $wpdb->prefix . 'wpmf_s3_queue` (
                      `id` int(11) NOT NULL,
                      `post_id` int(11) NOT NULL,
                      `destination` text NOT NULL,
                      `date_added` varchar(14) NOT NULL,
                      `date_done` varchar(14) DEFAULT NULL,
                      `status` tinyint(1) NOT NULL
                    ) ENGINE=InnoDB');

            $wpdb->query('ALTER TABLE `' . $wpdb->prefix . 'wpmf_s3_queue`
                          ADD UNIQUE KEY `id` (`id`),
                          ADD KEY `date_added` (`date_added`,`status`);');

            $wpdb->query('ALTER TABLE `' . $wpdb->prefix . 'wpmf_s3_queue`
                          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
        }

        // Set default options values
        $options = get_option('wp-media-folder-addon-tables');
        if (!$options) {
            add_option(
                'wp-media-folder-addon-tables',
                array(
                    'wp_posts' => array(
                        'post_content' => 1,
                        'post_excerpt' => 1
                    )
                )
            );
        }
        update_option('wpmf_addon_version', WPMFAD_VERSION);
    }

    /**
     * Includes styles and some scripts
     *
     * @return void
     */
    public function loadAdminScripts()
    {
        global $current_screen;
        if (!empty($current_screen->base) && $current_screen->base === 'settings_page_option-folder') {
            wp_enqueue_style(
                'wpmf-magnific-popup',
                WPMF_PLUGIN_URL . '/assets/css/display-gallery/magnific-popup.css',
                array(),
                '0.9.9'
            );

            wp_enqueue_script(
                'wpmf-magnific-popup',
                WPMF_PLUGIN_URL. '/assets/js/display-gallery/jquery.magnific-popup.min.js',
                array('jquery'),
                '0.9.9',
                true
            );

            wp_enqueue_script(
                'wpmf-circle-progress',
                plugins_url('assets/js/circle-progress.js', dirname(__FILE__)),
                array('jquery'),
                WPMFAD_VERSION
            );

            wp_enqueue_script(
                'wpmf-aws3-option',
                plugins_url('/assets/js/aws3-option.js', dirname(__FILE__)),
                array('jquery', 'wpmf-script-option', 'wpmf-magnific-popup', 'wpmf-circle-progress'),
                WPMFAD_VERSION
            );

            wp_localize_script('wpmf-aws3-option', 'wpmfS3', array(
                'l18n' => array(
                    'bucket_selected'  => esc_html__('Selected bucket', 'wpmfAddon'),
                    'sync_process_text' => esc_html__('Syncronization on the way, please wait', 'wpmfAddon'),
                    'bucket_select'    => esc_html__('Select bucket', 'wpmfAddon'),
                    'no_upload_s3_msg' => esc_html__('Please enable (Copy to Amazon S3) option', 'wpmfAddon'),
                    'sync_btn_text' => esc_html__('Synchronize with Amazon S3', 'wpmfAddon'),
                    'upload_to_s3' => esc_html__('Uploading the files to S3...', 'wpmfAddon'),
                    'download_from_s3' => esc_html__('Downloading the files from S3...', 'wpmfAddon'),
                    'update_local_url' => esc_html__('Updating content...', 'wpmfAddon'),
                    'delete_local_files' => esc_html__('Deleting the files on server...', 'wpmfAddon'),
                ),
                'vars' => array(
                    'not_sync_msg' => esc_html__('Sync to S3 need enable Remove after Amazon S3 upload option', 'wpmfAddon')
                )
            ));
        }
    }

    /**
     * Get S3 complete percent
     *
     * @return array
     */
    public function getS3CompletePercent()
    {
        global $wpdb;
        $all_attachments    = $wpdb->get_var('SELECT COUNT(ID) FROM ' . $wpdb->posts . ' WHERE post_type = "attachment"');
        $all_cloud_attachments = $wpdb->get_var('SELECT COUNT(ID) FROM ' . $wpdb->posts . ' as p INNER JOIN ' . $wpdb->postmeta . ' as pm ON p.ID = pm.post_id WHERE post_type = "attachment" AND pm.meta_key = "wpmf_drive_id" AND pm.meta_value != ""');
        $count_attachment    = $all_attachments - $all_cloud_attachments;
        $count_attachment_s3 = $wpdb->get_var('SELECT COUNT(ID) FROM ' . $wpdb->posts . ' as p INNER JOIN ' . $wpdb->postmeta . ' as pm ON p.ID = pm.post_id WHERE p.post_type = "attachment" AND pm.meta_key = "wpmf_awsS3_info" AND pm.meta_value !=""');
        if ($count_attachment_s3 >= $count_attachment) {
            $s3_percent = 100;
        } else {
            if ((int) $count_attachment === 0) {
                $s3_percent = 0;
            } else {
                $s3_percent = ceil($count_attachment_s3 / $count_attachment * 100);
            }
        }

        $local_files_count = $all_attachments - $all_cloud_attachments - $count_attachment_s3;
        return array('local_files_count' => $local_files_count, 's3_percent' => (int) $s3_percent);
    }

    /**
     * Update new URL attachment in database
     *
     * @param integer $post_id     Attachment ID
     * @param string  $file_path   Files path
     * @param string  $destination Destination
     * @param boolean $retrieve    Retrieve
     * @param array   $tables      All tables in database
     *
     * @return void
     */
    public function updateAttachmentUrlToDatabase($post_id, $file_path, $destination, $retrieve, $tables)
    {
        global $wpdb;
        $infos = get_post_meta($post_id, 'wpmf_awsS3_info', true);
        if (empty($infos)) {
            return;
        }

        $meta   = get_post_meta($post_id, '_wp_attachment_metadata', true);
        // get attachted file
        if (!empty($meta) && !empty($meta['file'])) {
            $attached_file = $meta['file'];
        } else {
            $attached_file = get_post_meta($post_id, '_wp_attached_file', true);
        }

        $old_url = str_replace(
            str_replace('\\', '/', get_home_path()),
            str_replace('\\', '/', home_url()) . '/',
            str_replace('\\', '/', $file_path)
        );

        $new_url = str_replace(rtrim(home_url(), '/'), $destination, $old_url);
        $new_url = urldecode($this->encodeFilename($new_url));

        if ($retrieve) {
            $search_url = $new_url;
            $replace_url = $old_url;
        } else {
            $search_url = $old_url;
            $replace_url = $new_url;
        }

        if ($search_url === '' || $replace_url === '') {
            return;
        }

        // ===========================
        foreach ($tables as $table => &$columns) {
            if (!count($columns)) {
                continue;
            }

            // Get the primary key of the table
            $key = $wpdb->get_row('SHOW KEYS FROM  ' . esc_sql($table) . ' WHERE Key_name = "PRIMARY"');

            // No primary key, we can't do anything in this table
            if ($key === null) {
                continue;
            }

            $key = $key->Column_name;

            foreach ($columns as $column => $column_value) {
                if ($column === 'key') {
                    continue;
                }

                // Search for serialized strings
                $query = 'SELECT ' . esc_sql($key) . ',' . esc_sql($column) . ' FROM ' . esc_sql($table) . ' WHERE
' . esc_sql($column) . ' REGEXP \'s:[0-9]+:".*(' . esc_sql(preg_quote($search_url)) . '|' . esc_sql(preg_quote($attached_file)) . ').*";\'';

                // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Query escaped previously
                $results = $wpdb->get_results($query, ARRAY_N);

                if (count($results)) {
                    foreach ($results as $result) {
                        $unserialized_var = unserialize($result[1]);
                        if ($unserialized_var !== false) {
                            // We're sure this is a serialized value, proceed it here
                            unset($columns[$column]);
                            // Actually replace string in all available strin array and properties
                            $unserialized_var = $this->replaceStringRecursive($unserialized_var, $search_url, $replace_url);
                            // Serialize it back
                            $serialized_var = serialize($unserialized_var);
                            // Update the database with new serialized value
                            $nb_rows = $wpdb->query($wpdb->prepare(
                                'UPDATE ' . esc_sql($table) . ' SET ' . esc_sql($column) . '=%s WHERE ' . esc_sql($key) . '=%s AND meta_key NOT IN("_wp_attached_file", "_wp_attachment_metadata")',
                                array($serialized_var, $result[0])
                            ));
                        }
                    }
                }
            }

            if (count($columns)) {
                $columns_query = array();

                foreach ($columns as $column => $column_value) {
                    // Relative urls
                    $columns_query[] = '`' . $column . '` = replace(`' . esc_sql($column) . '`, "' . esc_sql($search_url) . '", "' . esc_sql($replace_url) . '")';
                }

                $query = 'UPDATE `' . esc_sql($table) . '` SET ' . implode(',', $columns_query);

                // Ignore attachments meta column
                if ($table === $wpdb->prefix . 'postmeta') {
                    $query .= ' WHERE meta_key NOT IN("_wp_attached_file", "_wp_attachment_metadata")';
                }

                // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Query escaped previously
                $wpdb->query($query);
            }
        }
    }

    /**
     * Onedrive settings html
     *
     * @param string $html HTML
     *
     * @return string
     */
    public function renderSettings($html)
    {
        $connect    = false;
        $s3_percent = $this->getS3CompletePercent();
        try {
            $aws3 = new WpmfAddonAWS3();
            if (isset($_POST['btn_wpmf_save'])) {
                if (empty($_POST['wpmf_nonce'])
                    || !wp_verify_nonce($_POST['wpmf_nonce'], 'wpmf_nonce')) {
                    die();
                }
                if (!empty($_POST['aws3_config'])) {
                    $oldConfigs = get_option('_wpmfAddon_aws3_config');
                    if (empty($oldConfigs)) {
                        $oldConfigs = array();
                    }
                    $newConfigs = array_merge($oldConfigs, $_POST['aws3_config']);
                    update_option('_wpmfAddon_aws3_config', $newConfigs);
                }
                $aws3 = new WpmfAddonAWS3();
            }

            $aws3config = get_option('_wpmfAddon_aws3_config');
            if (is_array($aws3config)) {
                $aws3config = array_merge($this->aws3_config_default, $aws3config);
            } else {
                $aws3config = $this->aws3_config_default;
            }

            $copy_files_to_bucket     = $aws3config['copy_files_to_bucket'];
            $remove_files_from_server = $aws3config['remove_files_from_server'];
            $attachment_label         = $aws3config['attachment_label'];
            // get all buckets
            if (!empty($aws3config['credentials']['key']) && !empty($aws3config['credentials']['secret'])) {
                $list_buckets = $aws3->listBuckets();
                if (!empty($aws3config['bucket'])) {
                    $location_name = $aws3->regions[$aws3config['region']];
                }

                $connect = true;
            }
        } catch (S3Exception $e) {
            $connect = false;
            $msg     = $e->getAwsErrorMessage();
        }

        ob_start();
        require_once 'templates/settings_aws3.php';
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /**
     * Add the S3 meta box to the attachment screen
     *
     * @return void
     */
    public function attachmentMetaBox()
    {
        add_meta_box(
            's3-actions',
            __('Amazon Infos', 'wpmfAddon'),
            array($this, 'metaBox'),
            'attachment',
            'side',
            'core'
        );
    }

    /**
     * Render the S3 attachment meta box
     *
     * @return void
     */
    public function metaBox()
    {
        require_once 'templates/attachment-metabox.php';
    }

    /**
     * Upload attachment to s3
     *
     * @param object  $aws3    S3 class object
     * @param integer $post_id Attachment ID
     * @param array   $data    Attachment meta data
     *
     * @return array
     */
    public function doUploadToS3($aws3, $post_id, $data)
    {
        $parent_path = $this->getFolderS3Path($post_id);
        try {
            $file_paths = $this->getAttachmentFilePaths($post_id, $data);
            $infos = get_post_meta($post_id, 'wpmf_awsS3_info', true);
            if (!empty($infos)) {
                foreach ($file_paths as $size => $file_path) {
                    if (!file_exists($file_path)) {
                        continue;
                    }

                    try {
                        $aws3->uploadObject(
                            array(
                                'ACL'          => 'public-read',
                                'Bucket'       => $this->aws3_settings['bucket'],
                                'Key'          => $parent_path . basename($file_path),
                                'SourceFile'   => $file_path,
                                'ContentType'  => get_post_mime_type($post_id),
                                'CacheControl' => 'max-age=31536000',
                                'Expires'      => date('D, d M Y H:i:s O', time() + 31536000),
                                'Metadata'     => array(
                                    'attachment_id' => $post_id,
                                    'size'          => $size
                                )
                            )
                        );
                    } catch (S3Exception $e) {
                        $res = array('status' => false, 'msg' => esc_html($e->getAwsErrorMessage()));
                        return $res;
                    }
                }
            }

            $res = array('status' => true);
        } catch (\S3Exception $e) {
            $res = array('status' => false, 'msg' => esc_html($e->getAwsErrorMessage()));
        }

        return $res;
    }

    /**
     * Add a file to the queue
     *
     * @param integer $post_id     Attachment id
     * @param string  $destination Destination
     *
     * @return void
     */
    public function addToQueue($post_id, $destination)
    {
        global $wpdb;
        $check = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wpmf_s3_queue WHERE post_id=%d', array($post_id)));
        if (empty($check)) {
            $wpdb->insert(
                $wpdb->prefix . 'wpmf_s3_queue',
                array(
                    'post_id'     => $post_id,
                    'date_added'  => round(microtime(true) * 1000),
                    'destination' => $this->encodeFilename($destination),
                    'date_done'   => null,
                    'status'      => 0
                ),
                array(
                    '%d',
                    '%d',
                    '%s',
                    '%d',
                    '%d'
                )
            );
        }
    }

    /**
     * Update attachment metadata
     *
     * @param array   $data    Meta data
     * @param integer $post_id Attachment ID
     *
     * @return array
     */
    public function wpUpdateAttachmentMetadata($data, $post_id)
    {
        if (is_null($data)) {
            $data = wp_get_attachment_metadata($post_id, true);
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- No action, nonce is not required
        if (!empty($_POST['wpmf_folder'])) {
            $folder_id = (int) $_POST['wpmf_folder'];
            $cloud_id = wpmfGetCloudFolderID($folder_id);
            if ($cloud_id) {
                return $data;
            }
        }

        $infos      = get_post_meta($post_id, 'wpmf_awsS3_info', true);
        if (empty($infos)) {
            return $data;
        }

        $aws3 = new WpmfAddonAWS3();
        if ($aws3->doesBucketExist($this->aws3_settings['bucket'])) {
            $return = $this->doUploadToS3($aws3, $post_id, $data);
            if (!$return['status']) {
                return $data;
            }
        }
        return $data;
    }

    /**
     * Add attachment to cloud
     *
     * @param integer $attachment_id Attachment ID
     *
     * @return void
     */
    public function addAttachment($attachment_id)
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- No action, nonce is not required
        if (!empty($_POST['wpmf_folder'])) {
            $folder_id = (int)$_POST['wpmf_folder'];
            $cloud_id = wpmfGetCloudFolderID($folder_id);
            if (!$cloud_id) {
                if (!empty($this->aws3_settings['bucket'])) {
                    $this->addMetaInfo($attachment_id);
                }
            }
        } else {
            if (!empty($this->aws3_settings['bucket'])) {
                $this->addMetaInfo($attachment_id);
            }
        }
    }

    /**
     * Add meta info
     *
     * @param integer $attachment_id Attachment ID
     *
     * @return void
     */
    public function addMetaInfo($attachment_id)
    {
        $parent_path = $this->getFolderS3Path($attachment_id);
        $file_path = get_attached_file($attachment_id);
        update_post_meta($attachment_id, 'wpmf_awsS3_info', array(
            'Acl'    => 'public-read',
            'Region' => $this->aws3_settings['region'],
            'Bucket' => $this->aws3_settings['bucket'],
            'Key'    => $parent_path . basename($file_path)
        ));

        $infos = get_post_meta($attachment_id, 'wpmf_awsS3_info', true);
        if (!empty($infos)) {
            if (isset($infos['Region']) && $infos['Region'] !== 'us-east-1') {
                $destination = 'https://s3-' . $infos['Region'] . '.amazonaws.com/' . $infos['Bucket'] . '/WP Media Folder - ' . sanitize_title(get_bloginfo('name'));
            } else {
                $destination = 'https://s3.amazonaws.com/' . $infos['Bucket'] . '/WP Media Folder - ' . sanitize_title(get_bloginfo('name'));
            }

            $this->addToQueue($attachment_id, $destination);
        }
    }

    /**
     * Get all text assimilated columns from database
     *
     * @param boolean $all Retrive only prefix tables or not
     *
     * @return array|null|object
     */
    public function getDbColumns($all)
    {
        global $wpdb;
        $extra_query = '';

        // Not forced to retrieve all tables
        if (!$all) {
            $extra_query = ' AND TABLE_NAME LIKE "' . $wpdb->prefix . '%" ';
        }

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Nothing to prepare
        return $wpdb->get_results('SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE DATA_TYPE IN ("varchar", "text", "tinytext", "mediumtext", "longtext") AND TABLE_SCHEMA = "' . DB_NAME . '" ' . $extra_query . ' ORDER BY TABLE_NAME', OBJECT);
    }


    /**
     * Get the columns that can contain images
     *
     * @return array
     */
    public function getDefaultDbColumns()
    {
        global $wpdb;
        $columns = $this->getDbColumns(false);
        $final_columns = array();

        $exclude_tables = array($wpdb->prefix . 'users', $wpdb->prefix . 'term_taxonomy', $wpdb->prefix . 'term_relationships', $wpdb->prefix . 'terms', $wpdb->prefix . 'wpmf_s3_queue');
        foreach ($columns as $column) {
            if (in_array($column->TABLE_NAME, $exclude_tables)) {
                continue;
            }
            $matches = array();
            preg_match('/varchar\(([0-9]+)\)/', $column->COLUMN_TYPE, $matches);

            if (count($matches) && (int) $matches[1] < 40) {
                continue;
            }

            if (!isset($final_columns[$column->TABLE_NAME])) {
                $final_columns[$column->TABLE_NAME] = array();
            }

            if ($column->TABLE_NAME === $wpdb->posts) {
                if (in_array($column->COLUMN_NAME, array('post_mime_type', 'pinged', 'to_ping', 'post_password', 'post_title', 'post_name'))) {
                    continue;
                }
            }

            if ($column->TABLE_NAME === $wpdb->postmeta) {
                if (in_array($column->COLUMN_NAME, array('meta_key'))) {
                    continue;
                }
            }

            if ($column->TABLE_NAME === $wpdb->termmeta) {
                if (in_array($column->COLUMN_NAME, array('meta_key'))) {
                    continue;
                }
            }

            if ($column->TABLE_NAME === $wpdb->options) {
                if (in_array($column->COLUMN_NAME, array('option_name'))) {
                    continue;
                }
            }

            if ($column->TABLE_NAME === $wpdb->usermeta) {
                if (in_array($column->COLUMN_NAME, array('meta_key'))) {
                    continue;
                }
            }

            if ($column->TABLE_NAME === $wpdb->commentmeta) {
                if (in_array($column->COLUMN_NAME, array('meta_key'))) {
                    continue;
                }
            }

            if ($column->TABLE_NAME === $wpdb->comments) {
                if (in_array($column->COLUMN_NAME, array('comment_author', 'comment_author_email', 'comment_author_url', 'comment_author_IP', 'comment_agent'))) {
                    continue;
                }
            }

            if ($column->TABLE_NAME === $wpdb->links) {
                if (in_array($column->COLUMN_NAME, array('link_rel', 'link_rss', 'link_name'))) {
                    continue;
                }
            }

            $final_columns[$column->TABLE_NAME][$column->COLUMN_NAME] = 1;
        }

        return $final_columns;
    }

    /**
     * Update File Size
     *
     * @param integer $post_id Attachment ID
     *
     * @return void
     */
    public function updateFileSize($post_id)
    {
        $meta      = get_post_meta($post_id, '_wp_attachment_metadata', true);
        $file_path = get_attached_file($post_id, true);
        if (file_exists($file_path)) {
            $filesize  = filesize($file_path);
            if ($filesize > 0) {
                $meta['filesize'] = $filesize;
                update_post_meta($post_id, '_wp_attachment_metadata', $meta);
            }
        }
    }

    /**
     * Remove file from server after upload
     *
     * @return void
     */
    public function removeFilesAfterUpload()
    {
        if (empty($_POST['wpmf_nonce'])
            || !wp_verify_nonce($_POST['wpmf_nonce'], 'wpmf_nonce')) {
            die();
        }

        $configs = get_option('_wpmfAddon_aws3_config');
        if (empty($configs['remove_files_from_server'])) {
            wp_send_json(array('status' => true));
        }

        global $wpdb;
        $limit     = 5;
        $s3_queues = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wpmf_s3_queue WHERE status=0 LIMIT %d', array($limit)));
        if (empty($s3_queues)) {
            wp_send_json(array('status' => true));
        }

        $k            = 0;
        $check_remove = true;
        foreach ($s3_queues as $row) {
            // update file size
            $this->updateFileSize($row->post_id);
            $meta       = get_post_meta($row->post_id, '_wp_attachment_metadata', true);
            $file_paths = $this->getAttachmentFilePaths($row->post_id, $meta);
            foreach ($file_paths as $size => $file_path) {
                if (!file_exists($file_path)) {
                    continue;
                }

                if (!is_writable($file_path)) {
                    continue;
                }

                // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- fix warning when not have permission unlink
                $unlink = @unlink($file_path);
                if (!$unlink) {
                    $check_remove = false;
                }
            }

            if ($check_remove) {
                $wpdb->update(
                    $wpdb->prefix . 'wpmf_s3_queue',
                    array(
                        'status'    => 1,
                        'date_done' => round(microtime(true) * 1000)
                    ),
                    array('id' => $row->id),
                    array(
                        '%d',
                        '%d'
                    ),
                    array('%d')
                );
            }
            $k ++;
        }

        if ($k >= $limit) {
            wp_send_json(array('status' => false));
        } else {
            wp_send_json(array('status' => true));
        }
    }

    /**
     * Remove file from server after sync
     *
     * @return void
     */
    public function removeFilesFromServer()
    {
        if (empty($_POST['wpmf_nonce'])
            || !wp_verify_nonce($_POST['wpmf_nonce'], 'wpmf_nonce')) {
            die();
        }

        $configs = get_option('_wpmfAddon_aws3_config');
        if (empty($configs['remove_files_from_server'])) {
            wp_send_json(array('status' => true));
        }

        // update database
        global $wpdb;
        $files = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wpmf_s3_queue WHERE status = 2 LIMIT 10');
        if (empty($files)) {
            wp_send_json(array('status' => true, 'continue' => false));
        }

        foreach ($files as $file) {
            // update file size
            $this->updateFileSize($file->post_id);
            $meta       = get_post_meta($file->post_id, '_wp_attachment_metadata', true);
            $file_paths = $this->getAttachmentFilePaths($file->post_id, $meta);
            foreach ($file_paths as $size => $file_path) {
                if (!file_exists($file_path)) {
                    continue;
                }

                if (!is_writable($file_path)) {
                    continue;
                }

                // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- fix warning when not have permission unlink
                @unlink($file_path);
                $wpdb->update(
                    $wpdb->prefix . 'wpmf_s3_queue',
                    array(
                        'status'    => 1,
                        'date_done' => round(microtime(true) * 1000)
                    ),
                    array('id' => $file->id),
                    array(
                        '%d',
                        '%d'
                    ),
                    array('%d')
                );
            }
        }

        $count = $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'wpmf_s3_queue WHERE status = 1 OR status = 2');
        $count1 = $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'wpmf_s3_queue WHERE status = 1');
        $percent          = ($count1 / $count) * 100;
        if ($percent > 100) {
            $percent = 100;
        }

        wp_send_json(array('status' => true, 'continue' => true, 'percent' => (int) $percent));
    }

    /**
     * Recursively parse a variable to replace a string
     *
     * @param mixed  $var     Variable to replace string into
     * @param string $search  String to search
     * @param string $replace String to replace with
     *
     * @return mixed
     */
    public function replaceStringRecursive($var, $search, $replace)
    {
        switch (gettype($var)) {
            case 'string':
                return str_replace($search, $replace, $var);

            case 'array':
                foreach ($var as &$property) {
                    $property = self::replaceStringRecursive($property, $search, $replace);
                }
                return $var;

            case 'object':
                foreach (get_object_vars($var) as $property_name => $property_value) {
                    $var->{$property_name} = self::replaceStringRecursive($property_value, $search, $replace);
                }
                return $var;
        }
        return '';
    }

    /**
     * Delete Attachment
     *
     * @param integer $post_id Attachment ID
     *
     * @return void
     */
    public function deleteAttachment($post_id)
    {
        $infos = get_post_meta($post_id, 'wpmf_awsS3_info', true);
        global $wpdb;
        // delete in wpmf_s3_queue table
        $wpdb->delete($wpdb->prefix . 'wpmf_s3_queue', array('post_id' => $post_id), array('%d'));
        if (!empty($infos)) {
            try {
                set_time_limit(0);
                // delete on s3 server
                $aws3       = new WpmfAddonAWS3();
                $file_paths = $this->getAttachmentFilePaths($post_id);
                foreach ($file_paths as $size => $file_path) {
                    $aws3->deleteObject(
                        array(
                            'Bucket' => $infos['Bucket'],
                            'Key'    => dirname($infos['Key']) . '/' . basename($file_path)
                        )
                    );
                }
            } catch (S3Exception $e) {
                echo esc_html($e->getAwsErrorMessage());
            }
        }
    }

    /**
     * Get file paths for all attachment versions.
     *
     * @param integer       $attachment_id Attachment ID
     * @param array|boolean $meta          Meta data
     *
     * @return array
     */
    public function getAttachmentFilePaths($attachment_id, $meta = false)
    {
        $file_path = get_attached_file($attachment_id, true);
        $paths     = array(
            'original' => $file_path,
        );

        if (empty($meta)) {
            $meta = get_post_meta($attachment_id, '_wp_attachment_metadata', true);
        }

        if (is_wp_error($meta)) {
            return $paths;
        }

        // Get file name of original path
        $file_name = wp_basename($file_path);

        // If file edited, current file name might be different.
        if (isset($meta['file'])) {
            $paths['file'] = str_replace($file_name, wp_basename($meta['file']), $file_path);
        }

        // Sizes
        if (isset($meta['sizes'])) {
            foreach ($meta['sizes'] as $size => $file) {
                if (isset($file['file'])) {
                    $paths[$size] = str_replace($file_name, $file['file'], $file_path);
                }
            }
        }

        // Get backup size
        $backups = get_post_meta($attachment_id, '_wp_attachment_backup_sizes', true);
        if (is_array($backups)) {
            foreach ($backups as $size => $file) {
                if (isset($file['file'])) {
                    $paths[$size] = str_replace($file_name, $file['file'], $file_path);
                }
            }
        }

        // Remove duplicates
        $paths = array_unique($paths);
        return $paths;
    }

    /**
     * Get folder breadcrumb
     *
     * @param integer $post_id Attachment ID
     *
     * @return string
     */
    public function getFolderS3Path($post_id)
    {
        $attached  = get_attached_file($post_id);
        $attached  = str_replace('\\', '/', $attached);
        $attached  = str_replace(basename($attached), '', $attached);
        $home_path = str_replace('\\', '/', get_home_path());
        $path      = str_replace($home_path, '', $attached);
        $path      = str_replace('//', '', $path);
        return 'WP Media Folder - ' . sanitize_title(get_bloginfo('name')) . '/' . $path;
    }

    /**
     * Get folder breadcrumb
     *
     * @param integer $id     Folder id
     * @param integer $parent Folder parent
     * @param string  $string Current breadcrumb
     *
     * @return string
     */
    public function getCategoryDir($id, $parent, $string)
    {
        if (!empty($parent)) {
            $term   = get_term($parent, WPMF_TAXO);
            $string = $this->getCategoryDir($id, $term->parent, $term->name . '/' . $string);
        }

        return $string;
    }

    /**
     * Create a bucket
     *
     * @return void
     */
    public function createBucket()
    {
        if (empty($_POST['wpmf_nonce'])
            || !wp_verify_nonce($_POST['wpmf_nonce'], 'wpmf_nonce')) {
            die();
        }

        if (isset($_POST['name']) && $_POST['name'] !== '') {
            $name = $_POST['name'];
            $args = array('Bucket' => $name);
            if (isset($_POST['region'])) {
                $args['CreateBucketConfiguration'] = array('LocationConstraint' => $_POST['region']);
            }

            try {
                $aws3 = new WpmfAddonAWS3($_POST['region']);
                $aws3->createBucket($args);
                // select bucket after create
                $aws3config = get_option('_wpmfAddon_aws3_config');
                if (is_array($aws3config)) {
                    $aws3config['bucket'] = $name;
                    $aws3config['region'] = $_POST['region'];
                    update_option('_wpmfAddon_aws3_config', $aws3config);
                }
                $location_name = $aws3->regions[$_POST['region']];
                wp_send_json(array('status' => true, 'msg' => esc_html__('Created bucket success!', 'wpmfAddon'), 'location_name' => $location_name));
            } catch (S3Exception $e) {
                wp_send_json(array(
                    'status' => false,
                    'msg'    => esc_html($e->getAwsErrorMessage())
                ));
            }
        }
    }

    /**
     * Delete a bucket
     *
     * @return void
     */
    public function deleteBucket()
    {
        if (empty($_POST['wpmf_nonce'])
            || !wp_verify_nonce($_POST['wpmf_nonce'], 'wpmf_nonce')) {
            die();
        }

        if (isset($_POST['name']) && $_POST['name'] !== '') {
            $name = $_POST['name'];

            try {
                $aws3   = new WpmfAddonAWS3();
                $region = $aws3->getBucketLocation(
                    array('Bucket' => $name)
                );
                $args   = get_option('_wpmfAddon_aws3_config');
                if ($region !== $args['region']) {
                    $aws3 = new WpmfAddonAWS3($region);
                }

                $list_objects = $aws3->listObjects(array('Bucket' => $name));
                if (!empty($list_objects['Contents'])) {
                    foreach ($list_objects['Contents'] as $list_object) {
                        $aws3->deleteObject(array(
                            'Bucket' => $name,
                            'Key'    => $list_object['Key']
                        ));
                    }
                }

                $result = $aws3->deleteBucket(array(
                    'Bucket' => $name
                ));

                wp_send_json(array('status' => true));
            } catch (S3Exception $e) {
                wp_send_json(array('status' => false, 'msg' => esc_html($e->getAwsErrorMessage())));
            }
        }
        wp_send_json(array('status' => false, 'msg' => esc_html__('Delete failed!', 'wpmfAddon')));
    }

    /**
     * Select a bucket
     *
     * @return void
     */
    public function selectBucket()
    {
        if (empty($_POST['wpmf_nonce'])
            || !wp_verify_nonce($_POST['wpmf_nonce'], 'wpmf_nonce')) {
            die();
        }

        $aws3       = new WpmfAddonAWS3();
        try {
            $res = $aws3->getPublicAccessBlock(array('Bucket' => $_POST['bucket']));
        } catch (S3Exception $e) {
            $err = $e->getAwsErrorMessage();
            if (empty($err)) {
                wp_send_json(array(
                    'status' => false,
                    'msg'    => esc_html__('This bucket is not public. Please select other bucket!', 'wpmfAddon')
                ));
            }

            $aws3config = get_option('_wpmfAddon_aws3_config');
            if (is_array($aws3config)) {
                $aws3config['bucket'] = $_POST['bucket'];
                $region               = $aws3->getBucketLocation(
                    array('Bucket' => $_POST['bucket'])
                );

                $aws3config['region'] = $region;
                update_option('_wpmfAddon_aws3_config', $aws3config);
                wp_send_json(array(
                    'status' => true,
                    'bucket' => $aws3config['bucket'],
                    'region' => $aws3->regions[$aws3config['region']]
                ));
            }
        }

        wp_send_json(array('status' => false, 'msg' => esc_html__('Select bucket failed!', 'wpmfAddon')));
    }

    /**
     * Get buckets list
     *
     * @return void
     */
    public function getBucketsList()
    {
        if (empty($_POST['wpmf_nonce'])
            || !wp_verify_nonce($_POST['wpmf_nonce'], 'wpmf_nonce')) {
            die();
        }

        $aws3         = new WpmfAddonAWS3();
        $list_buckets = $aws3->listBuckets();
        $aws3config   = get_option('_wpmfAddon_aws3_config');
        $html         = '';
        if (!empty($list_buckets['Buckets'])) {
            foreach ($list_buckets['Buckets'] as $bucket) {
                if (isset($aws3config['bucket']) && $aws3config['bucket'] === $bucket['Name']) {
                    $html .= '<tr class="row_bucket bucket-selected" data-bucket="' . esc_attr($bucket['Name']) . '">';
                } else {
                    $html .= '<tr class="row_bucket aws3-select-bucket" data-bucket="' . esc_attr($bucket['Name']) . '">';
                }

                $html .= '<td>' . esc_html($bucket['Name']) . '</td>';
                $html .= '<td>' . esc_html($bucket['CreationDate']) . '</td>';
                if (isset($aws3config['bucket']) && $aws3config['bucket'] === $bucket['Name']) {
                    $html .= '<td><label class="btn-select-bucket">' . esc_html__('Selected bucket', 'wpmfAddon') . '</label></td>';
                } else {
                    $html .= '<td><label class="btn-select-bucket">' . esc_html__('Select bucket', 'wpmfAddon') . '</label></td>';
                }
                $html .= '<td><a class="delete-bucket wpmfqtip" data-alt="' . esc_html__('Delete bucket', 'wpmfAddon') . '" data-bucket="' . esc_attr($bucket['Name']) . '"><i class="material-icons"> delete_outline </i></a></td>';
                $html .= '</tr>';
            }
        }

        wp_send_json(array('status' => true, 'html' => $html, 'buckets' => $list_buckets['Buckets']));
    }

    /**
     * Download attachment from s3 s3
     *
     * @return void
     */
    public function downloadObject()
    {
        if (empty($_POST['wpmf_nonce'])
            || !wp_verify_nonce($_POST['wpmf_nonce'], 'wpmf_nonce')) {
            die();
        }

        /**
         * Filter check capability of current user to regenerate image thumbnail
         *
         * @param boolean The current user has the given capability
         * @param string  Action name
         *
         * @return boolean
         *
         * @ignore Hook already documented
         */
        $wpmf_capability = apply_filters('wpmf_user_can', current_user_can('manage_options'), 'download_object');
        if (!$wpmf_capability) {
            wp_send_json(array('status' => false, 'msg' => 'You not have permission!', 'wpmfAddon'));
        }

        set_time_limit(0);
        global $wpdb;
        $file = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'wpmf_s3_queue WHERE status = 1 LIMIT 1');
        if ($file) {
            $aws3 = new WpmfAddonAWS3();
            $infos      = get_post_meta($file->post_id, 'wpmf_awsS3_info', true);
            if (empty($infos)) {
                // delete this row
                $wpdb->update(
                    $wpdb->prefix . 'wpmf_s3_queue',
                    array(
                        'status'    => 0
                    ),
                    array('id' => $file->id),
                    array(
                        '%d',
                    ),
                    array('%d')
                );
                wp_send_json(array('status'  => true, 'continue' => true));
            }

            $file_paths = $this->getAttachmentFilePaths($file->post_id);
            // get tables
            $tables = self::getDefaultDbColumns();
            foreach ($file_paths as $file_path) {
                if (file_exists($file_path)) {
                    continue;
                }

                $aws3->getObject(array(
                    'Bucket' => $infos['Bucket'],
                    'Key'    => dirname($infos['Key']) . '/' . basename($file_path),
                    'SaveAs' => $file_path
                ));

                $this->updateAttachmentUrlToDatabase($file->post_id, $file_path, $file->destination, true, $tables);
            }

            delete_post_meta($file->post_id, 'wpmf_awsS3_info');
            // update status queue
            $wpdb->update(
                $wpdb->prefix . 'wpmf_s3_queue',
                array(
                    'status'    => 0
                ),
                array('id' => $file->id),
                array(
                    '%d',
                ),
                array('%d')
            );

            $count = $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'wpmf_s3_queue WHERE status = 1 OR status = 0');
            $count1 = $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'wpmf_s3_queue WHERE status = 0');
            $percent          = ($count1 / $count) * 100;
            sleep(2);
            wp_send_json(array('status'  => true, 'continue' => true, 'percent' => $percent));
        } else {
            wp_send_json(array('status' => true, 'continue' => false));
        }
    }

    /**
     * Sync media library with s3
     *
     * @return void
     */
    public function uploadToS3()
    {
        if (empty($_POST['wpmf_nonce'])
            || !wp_verify_nonce($_POST['wpmf_nonce'], 'wpmf_nonce')) {
            die();
        }

        /**
         * Filter check capability of current user to regenerate image thumbnail
         *
         * @param boolean The current user has the given capability
         * @param string  Action name
         *
         * @return boolean
         *
         * @ignore Hook already documented
         */
        $wpmf_capability = apply_filters('wpmf_user_can', current_user_can('manage_options'), 'upload_to_s3');
        if (!$wpmf_capability) {
            wp_send_json(
                array(
                    'status' => false,
                    'msg'    => esc_html__('Permission defined!', 'wpmfAddon')
                )
            );
        }

        $aws3config = get_option('_wpmfAddon_aws3_config');
        if (empty($aws3config['copy_files_to_bucket'])) {
            wp_send_json(
                array(
                    'status' => false,
                    'msg'    => esc_html__('Please enable (Copy to Amazon S3) option', 'wpmfAddon')
                )
            );
        }

        if (empty($aws3config['bucket'])) {
            wp_send_json(
                array(
                    'status' => false,
                    'msg'    => esc_html__('Please select an Amazon bucket to start using S3 server', 'wpmfAddon')
                )
            );
        }

        set_time_limit(0);
        $paged = isset($_POST['paged']) ? (int) $_POST['paged'] : 1;
        $limit            = 1;
        $offset = ($paged - 1) * $limit;
        $k                = 0;
        $query = new WP_Query(array(
            'posts_per_page' => $limit,
            'offset' => $offset,
            'post_type' => 'attachment',
            'post_status' => 'any',
            'orderby' => 'ID',
            'order' => 'DESC',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'wpmf_drive_id',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key'     => 'wpmf_awsS3_info',
                    'compare' => 'NOT EXISTS'
                )
            )
        ));

        $attachments = $query->get_posts();
        $count = count($attachments);
        // return if empty local file
        if ($count === 0) {
            wp_send_json(array(
                'status'                   => true,
                'continue' => false,
                's3_percent'               => 100
            ));
        }

        try {
            global $wpdb;
            $aws3       = new WpmfAddonAWS3();
            $s3_percent = $this->getS3CompletePercent();

            foreach ($attachments as $attachment) {
                $data = wp_get_attachment_metadata($attachment->ID, true);
                // do upload to s3
                $this->addMetaInfo($attachment->ID);
                $return = $this->doUploadToS3($aws3, $attachment->ID, $data);
                if ($return['status']) {
                    $k ++;
                }
            }

            $process_percent = 0;
            if (isset($_POST['local_files_count'])) {
                $process_percent = (1 / (int) $_POST['local_files_count']) * 100;
            }

            wp_send_json(array(
                'status'                   => true,
                'continue' => true,
                'percent'               => $process_percent,
                's3_percent' => $s3_percent['s3_percent']
            ));
        } catch (S3Exception $e) {
            wp_send_json(
                array(
                    'status' => false,
                    'msg'    => esc_html($e->getAwsErrorMessage())
                )
            );
        }
    }

    /**
     * Update local URL to S3 URL
     *
     * @return void
     */
    public function replaceLocalUrl()
    {
        // update database
        global $wpdb;
        $file = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'wpmf_s3_queue WHERE status = 0 LIMIT 1');
        if ($file) {
            $data       = get_post_meta($file->post_id, '_wp_attachment_metadata', true);
            $file_paths = $this->getAttachmentFilePaths($file->post_id, $data);
            // get tables
            $tables = self::getDefaultDbColumns();
            foreach ($file_paths as $size => $file_path) {
                if (!file_exists($file_path)) {
                    continue;
                }

                $this->updateAttachmentUrlToDatabase($file->post_id, $file_path, $file->destination, false, $tables);
            }

            $wpdb->update(
                $wpdb->prefix . 'wpmf_s3_queue',
                array(
                    'status'    => 2
                ),
                array('id' => $file->id),
                array(
                    '%d',
                ),
                array('%d')
            );

            $count = $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'wpmf_s3_queue WHERE status = 0 OR status = 2');
            $count1 = $wpdb->get_var('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'wpmf_s3_queue WHERE status = 2');
            $percent          = ($count1 / $count) * 100;
            if ($percent > 100) {
                $percent = 100;
            }

            sleep(1);
            wp_send_json(array('status' => true, 'continue' => true, 'percent' => (int) $percent));
        } else {
            $aws3config = get_option('_wpmfAddon_aws3_config');
            $remove = !empty($aws3config['remove_files_from_server']) ? true : false;
            wp_send_json(array('status' => true, 'continue' => false, 'remove' => $remove));
        }
    }

    /**
     * Encode file names according to RFC 3986 when generating urls
     *
     * @param string $file File name
     *
     * @return string Encoded filename
     */
    public function encodeFilename($file)
    {
        $url = parse_url($file);

        if (!isset($url['path'])) {
            // Can't determine path, return original
            return $file;
        }

        $file = str_replace(' ', '+', $file);
        if (isset($url['query'])) {
            // Manually strip query string, as passing $url['path'] to basename results in corrupt � characters
            $file_name = wp_basename(str_replace('?' . $url['query'], '', $file));
        } else {
            $file_name = wp_basename($file);
        }

        if (false !== strpos($file_name, '%')) {
            // File name already encoded, return original
            return $file;
        }

        $encoded_file_name = rawurlencode($file_name);
        if ($file_name === $encoded_file_name) {
            // File name doesn't need encoding, return original
            return $file;
        }

        return str_replace($file_name, $encoded_file_name, $file);
    }

    /**
     * Get attachment URL
     *
     * @param string  $url     Old URL
     * @param integer $post_id Attachment ID
     *
     * @return string
     */
    public function wpGetAttachmentUrl($url, $post_id)
    {
        $infos = get_post_meta($post_id, 'wpmf_awsS3_info', true);
        if (!empty($infos)) {
            $filename = basename($url);
            if (isset($infos['Region']) && $infos['Region'] !== 'us-east-1') {
                $new_url = $this->encodeFilename('https://s3-' . $infos['Region'] . '.amazonaws.com/' . $infos['Bucket'] . '/' . $infos['Key']);
            } else {
                $new_url = $this->encodeFilename('https://s3.amazonaws.com/' . $infos['Bucket'] . '/' . $infos['Key']);
            }

            $filename2 = basename($new_url);
            return str_replace($filename2, $filename, $new_url);
        }

        return $url;
    }

    /**
     * Get attachment path
     *
     * @param string  $file          Attachment path
     * @param integer $attachment_id Attachment ID
     *
     * @return string
     */
    public function getAttachedFile($file, $attachment_id)
    {
        $infos = get_post_meta($attachment_id, 'wpmf_awsS3_info', true);
        if (file_exists($file) || empty($infos)) {
            return $file;
        }

        $url = wp_get_attachment_url($attachment_id);
        // return the URL by default
        $file = apply_filters('wpmf_get_attached_file', $url, $file, $attachment_id);
        return $file;
    }

    /**
     * Download attachment from s3 to server when regenerate thumbnail
     *
     * @param string  $url           Attachment URL
     * @param string  $file          Attachment path
     * @param integer $attachment_id Attachment ID
     *
     * @return mixed
     */
    public function regenerateThumbnails($url, $file, $attachment_id)
    {
        if (!$this->processAction(array(
            'wpmf_regeneratethumbnail',
            'regeneratethumbnail',
            'wpmf_duplicate_file'
        ), true)) {
            return $url;
        }

        // download attachment from s3 to server
        $infos = get_post_meta($attachment_id, 'wpmf_awsS3_info', true);
        $file  = $this->downloadAttachment($infos, $file);
        if ($file) {
            // Return the file if successfully downloaded from S3
            return $file;
        };

        return $url;
    }

    /**
     * Download image crop when open crop modal
     *
     * @param string  $url           Attachment URL
     * @param string  $file          Attachment path
     * @param integer $attachment_id Attachment ID
     *
     * @return string
     */
    public function imageEditorDownloadFile($url, $file, $attachment_id)
    {
        if (!$this->isAjax()) {
            return $url;
        }

        // restores image
        $infos = get_post_meta($attachment_id, 'wpmf_awsS3_info', true);
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- No action, nonce is not required
        if (isset($_POST['do']) && $_POST['do'] === 'restore') {
            $backup_sizes      = get_post_meta($attachment_id, '_wp_attachment_backup_sizes', true);
            $filename          = $backup_sizes['full-orig']['file'];
            $orig_infos        = $infos;
            $orig_infos['Key'] = dirname($infos['Key']) . '/' . $filename;
            $orig_file         = dirname($file) . '/' . $filename;

            // Copy the original file back to the server
            $this->downloadAttachment($orig_infos, $orig_file);

            // Download attachment from s3
            $new_file = $this->downloadAttachment($infos, $file);
            if ($new_file) {
                return $new_file;
            };
        }

        $action = filter_input(INPUT_GET, 'action') ?: filter_input(INPUT_POST, 'action');
        if (in_array($action, array('image-editor', 'imgedit-preview'))) {
            global $wpdb;
            // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection, WordPress.PHP.DevelopmentFunctions.error_log_debug_backtrace -- Get function loader
            foreach (debug_backtrace() as $fun) {
                if (isset($fun['function']) && $fun['function'] === '_load_image_to_edit_path') {
                    // Download attachment from s3
                    $new_file = $this->downloadAttachment($infos, $file);
                }
            }
        }

        return $url;
    }

    /**
     * Check crop action
     *
     * @return boolean
     */
    public function isCrop()
    {
        $head_crop = $this->processAction(array('custom-header-crop'), true);
        $img_crop  = $this->processAction(array('crop-image'), true, array('site-icon', 'custom_logo'));
        if (!$head_crop && !$img_crop) {
            return false;
        }

        return true;
    }

    /**
     * Download attachment from s3 to server when crop image
     *
     * @param string  $url           Attachment URL
     * @param string  $file          Attachment path
     * @param integer $attachment_id Attachment ID
     *
     * @return mixed
     */
    public function cropImage($url, $file, $attachment_id)
    {
        if (!$this->isCrop()) {
            return $url;
        }

        // download attachment from s3 to server
        $infos = get_post_meta($attachment_id, 'wpmf_awsS3_info', true);
        $file  = $this->downloadAttachment($infos, $file);
        if ($file) {
            return $file;
        };

        return $url;
    }

    /**
     * Download attachment from S3
     *
     * @param array  $infos Attachment s3 infos
     * @param string $file  Attachment path
     *
     * @return boolean
     */
    public function downloadAttachment($infos, $file)
    {
        $dir = dirname($file);
        if (!wp_mkdir_p($dir)) {
            return false;
        }

        try {
            $aws3 = new WpmfAddonAWS3();
            $aws3->getObject(array(
                'Bucket' => $infos['Bucket'],
                'Key'    => $infos['Key'],
                'SaveAs' => $file,
            ));
        } catch (S3Exception $e) {
            return false;
        }

        return $file;
    }

    /**
     * Check the current request
     *
     * @param array             $actions Actions list
     * @param boolean           $ajax    Is ajax
     * @param null|string|array $key     Context key
     *
     * @return boolean
     */
    public function processAction($actions, $ajax, $key = null)
    {
        if ($ajax !== $this->isAjax()) {
            return false;
        }

        $method = 'GET';
        // phpcs:disable WordPress.Security.NonceVerification.Recommended, WordPress.Security.NonceVerification.Missing -- No action, nonce is not required
        if (isset($_GET['action'])) {
            $action = $this->filterInput('action');
        } elseif (isset($_POST['action'])) {
            $method = 'POST';
            $action = $this->filterInput('action', INPUT_POST);
        } else {
            return false;
        }
        // phpcs:enable
        $check = true;
        if (!is_null($key)) {
            $global  = constant('INPUT_' . $method);
            $context = $this->filterInput('context', $global);

            if (is_array($key)) {
                $check = in_array($context, $key);
            } else {
                $check = ($key === $context);
            }
        }

        return (in_array(sanitize_key($action), $actions) && $check);
    }

    /**
     * Gets a specific external variable by name and optionally filters it
     *
     * @param string  $var     Variable Name
     * @param integer $type    Variable type
     * @param integer $filter  Filter
     * @param mixed   $options Options
     *
     * @return mixed
     */
    public function filterInput($var, $type = INPUT_GET, $filter = FILTER_DEFAULT, $options = array())
    {
        return filter_input($type, $var, $filter, $options);
    }

    /**
     * Is this an AJAX
     *
     * @return boolean
     */
    public function isAjax()
    {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return true;
        }

        return false;
    }

    /**
     * Get attachment path from S3
     *
     * @param string  $url           Attachment URL
     * @param string  $file          Attachment path
     * @param integer $attachment_id Attachment ID
     *
     * @return string
     */
    public function getAttachedS3File($url, $file, $attachment_id)
    {
        if ($url === $file) {
            return $file;
        }

        $infos = get_post_meta($attachment_id, 'wpmf_awsS3_info', true);
        if (!empty($infos)) {
            $s3Url = 's3';
            $s3Url .= str_replace('-', '', $infos['Region']);
            return $s3Url . '://' . $infos['Bucket'] . '/' . $infos['Key'];
        }

        return $url;
    }
}
