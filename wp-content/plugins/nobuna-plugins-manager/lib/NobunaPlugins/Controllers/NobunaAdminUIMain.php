<?php

namespace NobunaPlugins\Controllers;

use Exception;
use NBHelpers\Number;
use NobunaPlugins\Model\NobunaSettings;
use NobunaPlugins\Model\NobunaAPIConnector;
use NobunaPlugins\Model\NobunaProduct;
use NobunaPlugins\Controllers\NobunaPluginsApp;
use NobunaPlugins\Model\NobunaBackup;
use NobunaPlugins\Exceptions\NobunaError;
use NobunaPlugins\Model\HTMLResult;
use NobunaPlugins\Model\NobunaDownloadTasks;

class NobunaAdminUIMain extends NobunaAdminUIBase {

    const SLUG = 'nobuna-plugins';

    public static function ShouldAddCommonItems() {
        return static::IsMyPage();
    }

    protected static function MyLocalizedStrings() {
        return array(
            'LoadingPlugins' => __nb('Loading plugins list...'),
            'Downloading' => __nb('Downloading...'),
            'Installing' => __nb('Installing...'),
        );
    }

    public static function Init() {
        static::AddAjaxMethod('get_products', 'MainPluginsListAjax');
        static::AddAjaxMethod('download_product', 'DownloadProductAjax');
        static::AddAjaxMethod('install_product', 'InstallProductAjax');
        static::AddAjaxMethod('remove_product', 'RemoveProductAjax');
        if (static::IsMyPage()) {
            static::AddNBAdminStyle();
            static::AddScript('nobuna_plugins.js');
        }
    }

    public static function Index() {
        $out = '';
        $out .= '<h1 class="nobuna">' . __nb('Nobuna Plugins Manager') . '</h1>' . PHP_EOL;
        $out .= '<img style="display: none;" src="' . static::GetImageUrl('loading.gif') . '" />' . PHP_EOL;
        $out .= '<div style="margin-top: 1em; white-space: nowrap !important;"><input id="search-products" type="text" placeholder="' . __nb('Search') . '" />'
                . ' <button onclick="nb_search_plugins();">' . __nb('Search') . '</button>'
                . ' <button onclick="jQuery(\'#search-products\').val(\'\'); nb_refresh_plugins();">' . __nb('Clear') . '</button>'
                . ' <span id="nobuna-search-loading" class="nb-hidden">' . static::GetMiniLoadingImageTag() . '</span></div>' . PHP_EOL;
        $out .= '<div id="nb-data">' . __nb('Loading plugins information...') . '</div>' . PHP_EOL;
        $out .= '<p><button id="nb-refresh" class="button button-primary button-large" name="refresh">' .
                __nb('Refresh list') . '</button></p>' . PHP_EOL;
        echo $out;
    }

    // <editor-fold defaultstate="collapsed" desc="Plugins List">
    private static function PluginsList($products) {
        $out = '';
        $out .= '<table class="wp-list-table widefat plugins">' . PHP_EOL;
        $out .= ' <thead><tr>' . PHP_EOL;
        $out .= '  <th scope="col" id="name" class="manage-column column-name column-primary">' . __nb('Plugin') . '</th>' . PHP_EOL;
        $out .= '  <th scope="col" id="description" class="manage-column">' . __nb('Installed') . '</th>' . PHP_EOL;
        $out .= '  <th scope="col" id="description" class="manage-column">' . __nb('Downloaded') . '</th>' . PHP_EOL;
        $out .= '  <th scope="col" id="description" class="manage-column">' . __nb('Last') . '</th>' . PHP_EOL;
        $out .= '  <th scope="col" id="description" class="manage-column">' . __nb('Active') . '</th>' . PHP_EOL;
        $out .= '  <th scope="col" id="description" class="manage-column">' . __nb('Size') . '</th>' . PHP_EOL;
        $out .= '  <th scope="col" id="description" class="manage-column">' . __nb('Options') . '</th>' . PHP_EOL;
        $out .= '  </tr></thead>' . PHP_EOL;
        $out .= ' <tbody id="the-list">' . PHP_EOL;
        reset($products);
        foreach ($products as $product) {
            $out .= static::PluginRow($product);
        }
        $out .= ' </tbody>' . PHP_EOL;
        $out .= '</table>' . PHP_EOL;
        return $out;
    }

    private static function ShouldUpgrade($installed, $last) {
        $comp = version_compare(static::CleanVersion($installed), static::CleanVersion($last));
        if($comp == -1) {
            return TRUE;
        }
        return FALSE;
    }
    
    private static function CleanVersion($v) {
        return preg_replace('/(\.0+)+$/', '', $v);
    }
    
    private static function PluginRow(NobunaProduct $product) {
        $out = '';
        $out .= '<tr id="nb-product-' . $product->id . '" class="nb-product" data-nbid="' . $product->id . '">' . PHP_EOL;
        $out .= ' <td class="plugin-title column-primary">' . PHP_EOL;
        $out .= '  <div><b>' . $product->product_name . '</b></div>' . PHP_EOL;
        $out .= '  <div>' . __nb('Available Downloads: %s', $product->available_downloads) . '</div>' . PHP_EOL;
        $out .= ' </td>' . PHP_EOL;
        $out .= ' <td class="">' . PHP_EOL;
        $installedVersion = $product->installedVersion();
        $last_version = $product->last_version;
        $style = static::ShouldUpgrade($installedVersion, $last_version) ? ' style="color: red" ' : '';
        $out .= '  <div'.$style.'>' . PHP_EOL;
        $out .= ($installedVersion !== NULL ? $installedVersion : '') . PHP_EOL;
        $out .= '  </div>' . PHP_EOL;
        $out .= ' </td>' . PHP_EOL;
        $out .= ' <td class="">' . PHP_EOL;
        $out .= '  <div>' . PHP_EOL;
        $downloadedVersion = $product->lastDownloadedVersion();
        $out .= ($downloadedVersion !== NULL ? $downloadedVersion : '') . PHP_EOL;
        $out .= '  </div>' . PHP_EOL;
        $out .= ' </td>' . PHP_EOL;
        $out .= ' <td class="">' . PHP_EOL;
        $out .= '  <div>' . $product->last_version . '</div>' . PHP_EOL;
        $out .= ' </td>' . PHP_EOL;
        $out .= ' <td class="">' . PHP_EOL;
        $out .= '  <div>' . PHP_EOL;
        $activeStatus = $product->isActive();
        switch ($activeStatus) {
            case NobunaProduct::ACTIVATION_STATUS_UNKNOWN:
                $out .= '<div alt="f460" class="dashicons dashicons-minus" title="' . __nb('Unknown') . '"></div>';
                break;
            case NobunaProduct::ACTIVATION_STATUS_OFF:
                $out .= '<div alt="f158" class="dashicons dashicons-no red" title="' . __nb('Off') . '"></div>';
                break;
            case NobunaProduct::ACTIVATION_STATUS_ON:
                $out .= '<div alt="f147" class="dashicons dashicons-yes green" title="' . __nb('On') . '"></div>';
                break;
        }
        $out .= '  </div>' . PHP_EOL;
        $out .= ' </td>' . PHP_EOL;
        $out .= ' <td class="">' . PHP_EOL;
        $out .= '  <div>' . Number::FormatBytes($product->file_size) . '</div>' . PHP_EOL;
        $out .= ' </td>' . PHP_EOL;
        $out .= ' <td class="">' . PHP_EOL;
        $out .= static::GetProductOptions($product);
        $out .= ' </td>' . PHP_EOL;
        $out .= '</tr">' . PHP_EOL;
        return $out;
    }

    public static function GetProductOptions(NobunaProduct $product) {
        $options = array();
        $has_download = $product->has_download();
        if ($product->removable === TRUE) {
            $rout = '   <span class="remove">' . PHP_EOL;
            $rout .= '    <a class="remove" onclick="nb_remove_product(' . $product->id . '); return false;">' . __nb('Hide') . '</a>' . PHP_EOL;
            $rout .= '   </span>' . PHP_EOL;
            $options[] = $rout;
        }
        if ($has_download === FALSE && $product->available_downloads > 0) {
            $aout = '   <span class="download">' . PHP_EOL;
            $aout .= '    <a class="download" onclick="nb_download_product(' . $product->id . '); return false;">' . __nb('Download') . '</a>' . PHP_EOL;
            $aout .= '   </span>' . PHP_EOL;
            $options[] = $aout;
        }
        if ($has_download === FALSE && $product->available_downloads == 0) {
            $pout = '   <span class="download">' . PHP_EOL;
            $pout .= '    <a class="download" target="_blank" href="' . $product->purchase . '" >' . __nb('Purchase') . '</a>' . PHP_EOL;
            $pout .= '   </span>' . PHP_EOL;
            $options[] = $pout;
        }
        if ($has_download !== FALSE) {
            $installed_current = $product->isInstalledCurrentVersion();
            $button_text = $installed_current ? __nb('Reinstall') : __nb('Install');
            $iout = '   <span class="install">' . PHP_EOL;
            $iout .= '    <a class="install" onclick="nb_install_product(' . $product->id . '); return false;">' . $button_text . '</a>' . PHP_EOL;
            $iout .= '   </span>' . PHP_EOL;
            $options[] = $iout;
        }
        return sprintf('<div id="nb-options-%d">%s</div>', $product->id, implode(' | ', $options));
    }
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Ajax methods">
    public static function MainPluginsListAjax() {
        $p = $_POST;
        $query = NULL;
        if (isset($p['q']) && is_string($p['q']) && strlen($p['q']) >= 1) {
            $query = $p['q'];
        }

        $result = HTMLResult::GlobalResult();

        try {
            if ($query !== NULL) {
                $products = NobunaAPIConnector::SearchProducts($query);
            } else {
                $products = NobunaAPIConnector::GetProductInfo();
            }
        } catch (Exception $e) {
            $products = new NobunaError(NobunaError::MAIN_ERROR_GET_PRODUCT_INFO,
                    __nb('Unknown error 01'));
        }

        if (!$result->isError) {
            $result->addData('html', static::PluginsList($products));
        }

        $result->printJson();
        wp_die();
    }

    public static function DownloadProductAjax() {
        $result = HTMLResult::GlobalResult();
        
        $product_id = intval($_POST['product_id']);
        $product = new NobunaProduct($product_id);
        
        try {
            $download = NobunaAPIConnector::DownloadProductById($product_id);
        } catch (Exception $e) {
            $download = new NobunaError(NobunaError::MAIN_ERROR_DOWNLOAD, 
                    __nb('Unknown error 02'));
        }
        
        if(!NobunaError::IsNobunaError($download)) {
            $res = NobunaDownloadTasks::CheckFilesCount(NobunaSettings::Shared(), $download);
            if(NobunaError::IsNobunaError($res)) {
                $warning = __nb('Alert, there was an error trying to remove old downloads: %s', $res->render());
                $result->addWarning($warning);
            }
        }
        
        if(!$result->isError) {
            try {
                NobunaBackup::FixByMainName($download->main_name, $product->id);
            } catch (Exception $ex) {
                // TODO: Handle the exception
            }
            $result->addSuccess(__nb('%s successfuly downloaded', $product->product_name));
        }

        $product = NobunaAPIConnector::RefreshProductInfo($product);
        $result->addData('row', static::PluginRow($product));
        
        $result->printJson();
        wp_die();
    }

    public static function InstallProductAjax() {
        $result = HTMLResult::GlobalResult();
        
        $product_id = intval($_POST['product_id']);
        NobunaAPIConnector::PingProductId($product_id); // Unhide the product
        $product = new NobunaProduct($product_id);
        
        if ($product->has_download() === FALSE) {
            $result->setError(new NobunaError(NobunaError::MAIN_ERROR_REMOVED_FILE, 
                    __nb('This file has been removed, download it again please')));
        } else {
            try {
                NobunaPluginsApp::InstallPackage($product);
            } catch (Exception $e) {
                $error = __nb('We could not install this product. Please, contact Nobuna support.');
                $result->setError(new NobunaError(NobunaError::MAIN_ERROR_COULD_NOT_INSTALL, $error));
            }
        }

        $result->addData('row', static::PluginRow($product));
        if(!$result->isError) {
            $result->addSuccess(__nb('%s successfuly installed.', $product->product_name));
        }

        $result->printJson();
        wp_die();
    }

    public static function RemoveProductAjax() {
        $result = HTMLResult::GlobalResult();

        $product_id = intval($_POST['product_id']);
        $product = new NobunaProduct($product_id);

        try {
            $remove_result = NobunaAPIConnector::RemoveProduct($product_id);
        } catch (Exception $ex) {
            $result->setError(new NobunaError(NobunaError::MAIN_ERROR_REMOVE_PRODUCT, 
                    __nb('Unknown error 03')));
        }
        
        if($result->isError) {
            $result->addData('row', static::PluginRow($product));
        } else {
            $result->addSuccess(__nb('%s successfuly hidden.', $product->product_name));
        }
        
        $result->printJson();
        wp_die();
    }
    // </editor-fold>
}
