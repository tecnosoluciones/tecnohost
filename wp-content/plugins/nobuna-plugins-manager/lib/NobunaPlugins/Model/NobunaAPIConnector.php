<?php

namespace NobunaPlugins\Model;

use WP_Error;
use WPHelpers\Package;
use NBHelpers\File;
use NBHelpers\Str;
use NBHelpers\Date;
use NobunaPlugins\Exceptions\UnableToCreateDirectoryException;
use NobunaPlugins\Exceptions\UnableToDownloadFileException;
use NobunaPlugins\Exceptions\UnableToDetectPackageException;
use NobunaPlugins\Model\NobunaDownload;
use NobunaPlugins\Controllers\NobunaPluginsApp;
use NobunaPlugins\Exceptions\NobunaError;
use NobunaPlugins\Model\HTMLResult;
use Exception;

class NobunaAPIConnector {
    
    const ERROR_EMPTY_PRODUCTS = 2000;
    
    public static function SearchProducts($what) {
        if(!is_string($what) || strlen($what) <= 0) {
            return static::GetProductInfo();
        }
        $params = array(
            'n' => $what,
        );
        return static::GetProductInfo($params);
    }
    
    public static function GetProductInfo($params = array()) {
        $request = new NobunaRequest(NOBUNA_PINFO_URL, $params);
        $request->execute();
        if($request->isError()) {
            return $request->error;
        }
        $result = $request->request_result;

        if(!is_array($result) || !isset($result['products']) || !is_array($result['products'])) {
            $error = new NobunaError(static::ERROR_EMPTY_PRODUCTS,
                    __nb('Unknown error AC'));
            NobunaGlobals::SetGlobalError($error);
            return $error;
        }
        if(isset($result['disclaimer'])) {
            NobunaGlobals::SetGlobalDisclaimer($result['disclaimer']);
        }
        if(isset($result['notice'])) {
            NobunaGlobals::SetGlobalIFrame($result['notice']);
        }
        $products = NobunaProductSet::SetFromAPIRequest($result['products']);
        $products->getRequiredUpdates()->update();
        return $products;
    }
    
    public static function RefreshProductInfo(NobunaProduct $product) {
        $products = static::GetProductInfo(array('pid' => $product->id));
        if(!is_wp_error($products)) {
            /* @var $products NobunaProductSet */
            $remote_product = $products->productById($product->id);
            if($remote_product !== NULL) {
                $remote_product->save();
                return $remote_product;
            }
        }
        return $product;
    }
    
    public static function RemoveProduct($product_id) {
        $request = new NobunaRequest(NOBUNA_PREMOVE_URL, array('pid' => $product_id));
        $request->execute();
        if($request->isError()) {
            return $request->error;
        }
        return $request->request_result;
    }
    
    public static function PingProductId($pid) {
        $request = new NobunaRequest(NOBUNA_PDOWNLOAD_URL, array('pid' => $pid, 'ping' => 'true'));
        $request->execute();
    }
    
    /**
     * 
     * @param int $pid
     * @return NobunaDownload
     * @throws UnableToCreateDirectoryException
     * @throws UnableToDownloadFileException
     * @throws UnableToDetectPackageException
     */
    public static function DownloadProductById($pid) {
        // Check if already downloaded
        $product = new NobunaProduct($pid);
        $download = $product->getMyVersionDownload();
        if($download !== NULL && !is_nobuna_debug()) {
            return $download;
        }
        
        // Create downloads folder
        $downloads_folder_path = NobunaPluginsApp::GetDownloadsFolderPath(TRUE);
        $download_path = static::GetNameForDownload($downloads_folder_path, $product);
        File::CreateDirectoryFromPath($download_path);
        if(file_exists($downloads_folder_path['path']) === FALSE) {
            $error_msg = __nb('Unable to create directory: %s', $downloads_folder_path['path']);
            $error = new NobunaError(NobunaError::DOWNLOAD_ERROR_CREATE_FOLDER, $error_msg);
            NobunaGlobals::SetGlobalError($error);
            return $error;
        }

        // Remove file if exists
        File::DeleteDirOrFile($download_path);
        
        // Proceed to download
        $request = new NobunaRequest(NOBUNA_PDOWNLOAD_URL, array('pid' => $pid));
        $request->downloadTo($download_path);

        if($request->isError()) {
            return $request->error;
        }

        // Check if it is a valid package
        $package = new Package($download_path);
        try {
            $package->check();
        } catch (Exception $ex) {
            @unlink($download_path);
            $code = NobunaError::DOWNLOAD_ERROR_CODE_UNABLE_VERIFY;
            $msg = __nb('The product file is not valid, please, contact with Nobuna support: %s', 
                    $product->product_name);
            $error = new NobunaError($code, $msg);
            NobunaGlobals::SetGlobalError($error);
            return $error;
        }
        
        // Create db entry
        $dbDownload = static::CreateDownload($product, $package, $download_path);
        
        return $dbDownload;
    }
    
    protected static function GetNameForDownload($upload_dir, $product) {
        for($i = 0; $i < 50; $i++) {
            $download_path = sprintf('%s/%s/%s-%s-%s', $upload_dir['path'],
                    Str::CreateSlug($product->product_name), 
                    Str::GetRandomCharacters(16, 16), $product->last_version, 
                    $product->file_name);
            if(!file_exists($download_path)) {
                break;
            }
        }
        return $download_path;
    }
    
    /**
     * 
     * @param \NobunaPlugins\Model\NobunaProduct $product
     * @param Package $package
     * @param string $path
     * @return NobunaDownload
     */
    protected static function CreateDownload(NobunaProduct $product, Package $package, $path) {
        $d = new NobunaDownload();
        $d->product_id = $product->id;
        $d->file_path = str_replace(ABSPATH, '', $path);
        $d->download_date = Date::UtcNowMysqlFormatted();
        $d->file_size = filesize($path);
        $d->product_version = $product->last_version;
        $d->main_name = $package->main_name;
        $d->type = $package->type;
        $d->save(TRUE);
        return $d;
    }
    
}
