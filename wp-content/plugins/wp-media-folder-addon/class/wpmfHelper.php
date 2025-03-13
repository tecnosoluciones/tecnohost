<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');

/**
 * Class WpmfAddonHelper
 */
class WpmfAddonHelper
{

    /**
     * Get cloud configs
     *
     * @return mixed
     */
    public static function getAllCloudConfigs()
    {
        $default = array(
            'googleClientId'     => '',
            'googleClientSecret' => ''
        );
        return get_option('_wpmfAddon_cloud_config', $default);
    }

    /**
     * Save cloud configs
     *
     * @param array $data Data config
     *
     * @return boolean
     */
    public static function saveCloudConfigs($data)
    {
        $result = update_option('_wpmfAddon_cloud_config', $data);
        return $result;
    }

    /**
     * Get cloud configs by name
     *
     * @param string $name Sever name
     *
     * @return array|null
     */
    public static function getDataConfigBySeverName($name)
    {
        $googleDriveParams = array();
        if (self::getAllCloudConfigs()) {
            foreach (self::getAllCloudConfigs() as $key => $val) {
                if (strpos($key, 'google') !== false) {
                    $googleDriveParams[$key] = $val;
                }
            }

            $result = null;
            switch ($name) {
                case 'google':
                    $result = $googleDriveParams;
                    break;
            }
            return $result;
        }
        return null;
    }

    /**
     * Get all cloud configs
     *
     * @return mixed
     */
    public static function getAllCloudParams()
    {
        return get_option('_wpmfAddon_cloud_category_params');
    }

    /**
     * Set cloud configs
     *
     * @param array $cloudParams Cloud params
     *
     * @return boolean
     */
    public static function setCloudConfigsParams($cloudParams)
    {
        $result = update_option('_wpmfAddon_cloud_category_params', $cloudParams);
        return $result;
    }

    /**
     * Get google drive params
     *
     * @return mixed
     */
    public static function getGoogleDriveParams()
    {
        $params = self::getAllCloudParams();
        return isset($params['googledrive']) ? $params['googledrive'] : false;
    }

    /**
     * Save Cloud configs
     *
     * @param string       $key Key
     * @param string|array $val Value
     *
     * @return void
     */
    public static function setCloudParam($key, $val)
    {
        $params       = self::getAllCloudConfigs();
        $params[$key] = $val;
        self::saveCloudConfigs($params);
    }


    /**
     * Get termID
     *
     * @param string $googleDriveId Id of folder
     *
     * @return boolean
     */
    public static function getTermIdGoogleDriveByGoogleId($googleDriveId)
    {
        $returnData   = false;
        $googleParams = self::getGoogleDriveParams();
        if ($googleParams) {
            foreach ($googleParams as $key => $val) {
                if ($val['idCloud'] === $googleDriveId) {
                    $returnData = $val['termId'];
                }
            }
        }
        return $returnData;
    }

    /**
     * Get google drive data by term id
     *
     * @param integer $termId Term id
     *
     * @return boolean
     */
    public static function getGoogleDriveIdByTermId($termId)
    {
        $returnData   = false;
        $googleParams = self::getGoogleDriveParams();
        if ($googleParams) {
            foreach ($googleParams as $key => $val) {
                if ((int) $val['termId'] === (int) $termId) {
                    $returnData = $val['idCloud'];
                }
            }
        }
        return $returnData;
    }

    /**
     * Get category id by cloud ID
     *
     * @param string $cloud_id Cloud id
     *
     * @return boolean
     */
    public static function getCatIdByCloudId($cloud_id)
    {
        $returnData   = false;
        $googleParams = self::getGoogleDriveParams();
        if ($googleParams) {
            foreach ($googleParams as $key => $val) {
                if ($val['idCloud'] === $cloud_id) {
                    $returnData = $val['termId'];
                }
            }
        }
        return $returnData;
    }

    /**
     * Get all google drive id
     *
     * @return array
     */
    public static function getAllGoogleDriveId()
    {
        $returnData   = array();
        $googleParams = self::getGoogleDriveParams();
        if ($googleParams) {
            foreach ($googleParams as $key => $val) {
                $returnData[] = $val['idCloud'];
            }
        }
        return $returnData;
    }

    /**
     * Sync interval
     *
     * @return float
     */
    public static function curSyncInterval()
    {
        //get last_log param
        $config = self::getAllCloudConfigs();
        if (isset($config['last_log']) && !empty($config['last_log'])) {
            $last_log  = $config['last_log'];
            $last_sync = (int) strtotime($last_log);
        } else {
            $last_sync = 0;
        }

        $time_new     = (int) strtotime(date('Y-m-d H:i:s'));
        $timeInterval = $time_new - $last_sync;
        $curtime      = $timeInterval / 60;

        return $curtime;
    }

    /**
     * Get extension
     *
     * @param string $file File name
     *
     * @return string
     */
    public static function getExt($file)
    {
        $dot = strrpos($file, '.') + 1;

        return substr($file, $dot);
    }

    /**
     * Strips the last extension off of a file name
     *
     * @param string $file The file name
     *
     * @return string  The file name without the extension
     */
    public static function stripExt($file)
    {
        return preg_replace('#\.[^.]*$#', '', $file);
    }

    /*----------- Dropbox -----------------*/
    /**
     * Get all dropbox configs
     *
     * @return mixed
     */
    public static function getAllDropboxConfigs()
    {
        $default = array(
            'dropboxKey'        => '',
            'dropboxSecret'     => '',
            'dropboxSyncTime'   => '5',
            'dropboxSyncMethod' => 'sync_page_curl'
        );
        return get_option('_wpmfAddon_dropbox_config', $default);
    }

    /**
     * Save dropbox config
     *
     * @param array $data Data config
     *
     * @return boolean
     */
    public static function saveDropboxConfigs($data)
    {

        $result = update_option('_wpmfAddon_dropbox_config', $data);
        return $result;
    }

    /**
     * Get dropbox config
     *
     * @param string $name Dropbox name
     *
     * @return array|null
     */
    public static function getDataConfigByDropbox($name)
    {
        $DropboxParams = array();

        if (self::getAllDropboxConfigs()) {
            foreach (self::getAllDropboxConfigs() as $key => $val) {
                if (strpos($key, 'dropbox') !== false) {
                    $DropboxParams[$key] = $val;
                }
            }
            $result = null;
            switch ($name) {
                case 'dropbox':
                    $result = $DropboxParams;
                    break;
            }
            return $result;
        }
        return null;
    }

    /**
     * Set dropbox config
     *
     * @param array $dropboxParams Params of dropbox
     *
     * @return boolean
     */
    public static function setDropboxConfigsParams($dropboxParams)
    {
        $result = update_option('_wpmfAddon_dropbox_category_params', $dropboxParams);
        return $result;
    }

    /**
     * Get dropbox params
     *
     * @return mixed
     */
    public static function getDropboxParams()
    {
        return get_option('_wpmfAddon_dropbox_category_params', array());
    }

    /**
     * Get id by termID
     *
     * @param integer $termId Folder id
     *
     * @return boolean
     */
    public static function getDropboxIdByTermId($termId)
    {
        $returnData = false;
        $dropParams = self::getDropboxParams();
        if ($dropParams && isset($dropParams[$termId])) {
            $returnData = $dropParams[$termId]['idDropbox'];
        }
        return $returnData;
    }

    /**
     * Get dropbox folder id
     *
     * @param integer $termId Folder id
     *
     * @return boolean
     */
    public static function getIdFolderByTermId($termId)
    {
        $returnData = false;
        $dropParams = self::getDropboxParams();
        if ($dropParams && isset($dropParams[$termId])) {
            $returnData = $dropParams[$termId]['id'];
        }
        return $returnData;
    }

    /**
     * Get term id by Path
     *
     * @param string $path Path
     *
     * @return boolean|integer|string
     */
    public static function getTermIdByDropboxPath($path)
    {
        $dropbox_list = self::getDropboxParams();
        $result       = false;
        $path         = strtolower($path);
        if (!empty($dropbox_list)) {
            foreach ($dropbox_list as $k => $v) {
                if (strtolower($v['idDropbox']) === $path) {
                    $result = $k;
                }
            }
        }
        return $result;
    }

    /**
     * Get path by id
     *
     * @param string $id Dropbox file id
     *
     * @return boolean
     */
    public static function getPathByDropboxId($id)
    {
        $dropbox_list = self::getDropboxParams();
        $result       = false;
        if (!empty($dropbox_list)) {
            foreach ($dropbox_list as $k => $v) {
                if ($v['id'] === $id) {
                    $result = $v['idDropbox'];
                }
            }
        }

        return $result;
    }

    /**
     * Set dropbox file infos
     *
     * @param array $params Params
     *
     * @return boolean
     */
    public static function setDropboxFileInfos($params)
    {
        $result = update_option('_wpmfAddon_dropbox_fileInfo', $params);
        return $result;
    }

    /**
     * Get dropbox infos
     *
     * @return mixed
     */
    public static function getDropboxFileInfos()
    {
        return get_option('_wpmfAddon_dropbox_fileInfo');
    }

    /**
     * Sync interval dropbox
     *
     * @return float
     */
    public static function curSyncIntervalDropbox()
    {
        //get last_log param
        $config = self::getAllDropboxConfigs();
        if (isset($config['last_log']) && !empty($config['last_log'])) {
            $last_log  = $config['last_log'];
            $last_sync = (int) strtotime($last_log);
        } else {
            $last_sync = 0;
        }

        $time_new     = (int) strtotime(date('Y-m-d H:i:s'));
        $timeInterval = $time_new - $last_sync;
        $curtime      = $timeInterval / 60;
        return $curtime;
    }
}
