<?php

namespace NobunaPlugins\Controllers;

use NobunaPlugins\Model\NobunaDownload;
use NobunaPlugins\Model\NobunaBackup;

class NobunaAdminFile {
    
    public $id;
    public $name;
    public $date;
    public $version;
    public $size;
    public $path;
    
    public static function FromNobunaDownload(NobunaDownload $download) {
        $f = new NobunaAdminFile;
        $f->id = $download->id;
        $f->date = $download->download_date;
        $f->name = $download->getProductNameOrMainName();
        $f->path = $download->file_path;
        $f->size = $download->file_size;
        $f->version = $download->product_version;
        return $f;
    }
    
    public static function FromNobunaBackup(NobunaBackup $backup) {
        $f = new NobunaAdminFile;
        $f->id = $backup->id;
        $f->date = $backup->created_on;
        $f->name = $backup->getProductNameOrMainName();
        $f->path = $backup->path;
        $f->size = $backup->size();
        $f->version = $backup->version;
        return $f;
    }
    
}
