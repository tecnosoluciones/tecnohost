<?php

namespace NobunaPlugins\Controllers;

use NBHelpers\Date;
use NBHelpers\Number;
use NBHelpers\PaginationSet;
use NobunaPlugins\Exceptions\NobunaError;
use NobunaPlugins\Model\NobunaSettings;

abstract class NobunaAdminUIFiles extends NobunaAdminUIBase {

//    abstract protected static function GetTitle();

//    abstract protected static function GetType();

//    abstract protected static function RemoveItem($item_id);

//    abstract protected static function _GetFileGroups();

    protected static function MyLocalizedStrings() {
        return array(
            'LoadingFiles' => __nb('Loading files...'),
        );
    }

    public static function Init() {
        if (static::IsMyPage()) {
            static::AddAjaxMethod('nobuna_remove_' . static::GetType(), 'RemoveItemAjax');
            static::AddAjaxMethod('nobuna_get_files', 'GetFilesAjax');
            static::AddAjaxMethod('nobuna_set_items_per_page', 'SetItemsPerPageAjax');
            static::AddNBAdminStyle();
            static::AddScript('nobuna_files.js');
        }
    }

    public static function Index() {
        $out = '';
        $out .= '<h1 class="nobuna">' . static::GetTitle() . '</h1>' . PHP_EOL;
        $out .= sprintf('<div id="main-file-list">%s</div>' . PHP_EOL, static::GetCompleteFilesHTML());
        echo $out;
    }

    private static function GetFileGroups() {
        static $groups = NULL;
        if ($groups === NULL) {
            $groups = NobunaAdminFileSetSet::SetFromGroups(static::_GetFileGroups(), static::GetType());
        }
        return $groups;
    }

    private static function GetPagination() {
        $items_per_page = array(10, 20, 50, 100, 500, 1000);
        $opt_items = NobunaSettings::Shared()->items_per_page;
        $out = '';
        $out .= '<div class="div-pagination">' . PHP_EOL;
        $out .= '<span><button onclick="nb_refresh_files();">' . __nb('Refresh list') . '</button></span> | ' . PHP_EOL;
        $out .= '<label>' . __nb('Items per page') . '</label>' . PHP_EOL;
        $out .= '<select onchange="nb_set_items_per_page();" id="items-per-page">' . PHP_EOL;
        foreach ($items_per_page as $count) {
            $out .= sprintf('<option value="%d" %s>%d</option>' . PHP_EOL, $count, $opt_items == $count ? 'selected' : '', $count);
        }
        $out .= '</select> ' . PHP_EOL;
        $pgs = static::GetPagesHTML();
        $out .= $pgs !== '' ? '<span> ' . $pgs . '</span>' . PHP_EOL : '';
        $out .= '</div>' . PHP_EOL;
        return $out;
    }

    /**
     * @staticvar PaginationSet $set
     * @return PaginationSet
     */
    private static function GetPaginationSet() {
        static $set = NULL;
        if ($set === NULL) {
            $fileGroups = static::GetFileGroups();
            $itemsCount = $fileGroups->adminFilesCount();
            $currentPage = static::GetCurrentPage();
            $itemsPerPage = static::ItemsPerPage();
            $set = new PaginationSet($itemsPerPage, $itemsCount, $currentPage, 'javascript:nb_files_go_to_page({page})');
        }
        return $set;
    }

    private static function GetPagesHTML() {
        $set = static::GetPaginationSet();
        $html = $set->getListHTML();
        return $html;
    }

    private static function GetCurrentPage() {
        if (isset($_REQUEST['p']) && intval($_REQUEST['p']) > 0) {
            return intval($_REQUEST['p']);
        }
        return 1;
    }

    public static function GetFilesAjax() {
        $result = static::GlobalResult();
        $result->addData('html', static::GetCompleteFilesHTML());
        $result->printJson();
        wp_die();
    }

    public static function GetCompleteFilesHTML() {
        $result = static::GlobalResult();
        $html = '';
        $html .= static::GetPagination();
        $html .= '<br />' . PHP_EOL;
        $html .= sprintf('<div id="file-list">%s</div>', static::GetFilesHTML());
        $html .= '<br />' . PHP_EOL;
        $html .= static::GetPagination();
        return $html;
    }

    /**
     * @return string
     */
    protected static function GetFilesHTML() {
        $sets = static::GetFileGroups();
        if($sets->adminFilesCount() <= 0) {
            return sprintf('<p>%s</p>', __nb('There are not files yet'));
        }
        $type = static::GetType();
        $paginationSet = static::GetPaginationSet();
        $offsetIndex = $paginationSet->offsetIndex();
        $lastItemIndex = $paginationSet->lastItemIndex();

        $date_format = sprintf('%s - %s', get_option('date_format', NOBUNA_DEFAULT_DATE_FORMAT), get_option('time_format', NOBUNA_DEFAULT_TIME_FORMAT));
        $gmt_offset = get_option('gmt_offset') * 60 * 60;

        $titles = array(
            __nb('Plugin'),
            __nb('Date'),
            __nb('Version'),
            __nb('Size'),
            '',
        );

        $t = 0;
        $g = 0;
        foreach ($sets as $set) {
            /* @var $set NobunaAdminFileSet */
            $i = 0;
            foreach ($set as $adminFile) {
                if($t <= $offsetIndex) {
                    $t++;
                    continue;
                }
                if($t > $lastItemIndex) {
                    break;
                }
                /* @var $adminFile NobunaAdminFile */
                $row = array('id' => $type . '-id-' . $adminFile->id, 'class' => $g % 2 !== 0 ? 'row-background-light-grey' : '');
                $date = Date::UtcDateFromMysqlDateTime($adminFile->date);
                Date::AddSeconds($date, $gmt_offset);
                $links = array(
                    static::GetDownloadLink($adminFile->path, $adminFile->id),
                    static::GetRemoveLink($type, $adminFile->id),
                );
                $columns = array(
                    array('content' => $adminFile->name),
                    array('content' => $date->format($date_format)),
                    array('content' => $adminFile->version),
                    array('content' => Number::FormatBytes($adminFile->size)),
                    array('content' => implode(' | ', $links)),
                );
                $row['columns'] = $columns;
                $rows[] = $row;

                $i++;
                $t++;
            }
            $g++;
        }

        
        $table = static::GetTableHTML($titles, $rows);
        return $table;
    }

    protected static function ItemsPerPage() {
        return NobunaSettings::Shared()->items_per_page;
    }

    public static function RemoveItemAjax() {
        $p = $_POST;
        $id = isset($p['id']) ? intval($p['id']) : NULL;
        if ($id === NULL) {
            static::SetGlobalError(new NobunaError(NobunaError::FILES_EC_INVALID_ID, __nb('Invalid ID')));
        } else {
            static::RemoveItem($id);
        }

        static::GlobalResult()->printJson();
        wp_die();
    }

    public static function SetItemsPerPageAjax() {
        $items = isset($_POST['items-per-page']) ? intval($_POST['items-per-page']) : 20;
        NobunaSettings::Shared()->setItemsPerPage($items);
        static::GlobalResult()->printJson();
        wp_die();
    }

    protected static function GetRemoveLink($type, $id) {
        $js_method = sprintf('nb_remove_file_item(%d, \'%s\'); return false;', $id, $type);
        $link = sprintf('<a id="link-%s-%d" class="remove" onclick="%s">%s</a>', $type, $id, $js_method, __nb('Remove'));
        return $link;
    }

    protected static function GetDownloadLink($path, $id) {
        $link = sprintf('<a class="download" href="/%s">%s</a>', $path, __nb('Download'));
        return $link;
    }

}
