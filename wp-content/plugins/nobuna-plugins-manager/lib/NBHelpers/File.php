<?php

namespace NBHelpers;

use InvalidArgumentException;

class File {

    public $path = NULL;
    private $handler = NULL;
    private $mode = NULL;

    /**
     * Returns FALSE if something failed while writing the file
     * @param mixed $content
     * @param string $path
     * @return boolean
     */
    public static function SaveFileContents($content, $path) {
        $d = @fopen($path, 'wb');
        if($d !== FALSE) {
            $w = @fwrite($d, $content);
            @fclose($d);
            if($w !== FALSE) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /**
     * @param string $path_to_dir
     * @return int
     * @throws InvalidArgumentException
     */
    public static function FolderSize($path_to_dir) {
        if(!is_dir($path_to_dir)) {
            throw new InvalidArgumentException(sprintf('Invalid argument $path_to_dir: %s', $path_to_dir));
        }
        $size = 0;
        foreach (glob(rtrim($path_to_dir, '/') . '/*') as $fileOrFolder) {
            $size += is_file($fileOrFolder) ? filesize($fileOrFolder) : static::FolderSize($fileOrFolder);
        }
        return $size;
    }

    /**
     * 
     * @param string $path
     * @return bool TRUE if success
     */
    public static function CreateDirectoryFromPath($path) {
        $dir = dirname($path);
        return static::CreateDirectory($dir);
    }

    /**
     * 
     * @param string $path
     * @return bool TRUE if success
     */
    public static function CreateDirectory($dir) {
        $res = TRUE;
        if (!is_dir($dir)) {
            $res = mkdir($dir, 0777, TRUE);
        }
        return $res;
    }

    /**
     * 
     * @param string $path
     * @return bool TRUE if ok, FALSE if not
     * @throws InvalidArgumentException
     */
    public static function DeleteDirOrFile($path) {
        $is_dir = is_dir($path);
        $exists = file_exists($path);
        if (!$exists) {
            return TRUE;
        }
        if ($is_dir) {
            if (substr($path, strlen($path) - 1, 1) != '/') {
                $path .= '/';
            }
            $files = glob($path . '{,.}[!.,!..]*', GLOB_MARK|GLOB_BRACE);
            foreach ($files as $file) {
                static::DeleteDirOrFile($file);
            }
            return rmdir($path);
        } else {
            return unlink($path);
        }
    }

    public function __destruct() {
        if ($this->handler !== NULL) {
            fclose($this->handler);
        }
    }

    private function checkPath() {
        if ($this->path === NULL) {
            throw new \Exception('Path is not defined');
        }
    }

    private function getHandler($mode) {
        $this->checkPath();
        if ($this->handler === NULL) {
            $this->handler = fopen($this->path, $mode);
            $this->mode = $mode;
        } else if ($this->handler !== NULL && $this->mode !== $mode) {
            fclose($this->handler);
            $this->handler = fopen($this->path, $mode);
            $this->mode = $mode;
        }
        return $this->handler;
    }

    public function getJsonContent() {
        $this->checkPath();
        if (!file_exists($this->path)) {
            return NULL;
        }
        $content = file_get_contents($this->path);
        $res = json_decode($content, TRUE);
        return $res;
    }

    public function setJsonContent($content) {
        $jData = json_encode($content);
        file_put_contents($this->path, $jData);
    }

    public function getLine() {
        $line = fgets($this->getHandler('r'));
        return $line;
    }

    public function getJsonLine() {
        $line = $this->getLine();
        if ($line !== FALSE) {
            $data = json_decode(trim($line), TRUE);
            return $data;
        }
        return FALSE;
    }

}
