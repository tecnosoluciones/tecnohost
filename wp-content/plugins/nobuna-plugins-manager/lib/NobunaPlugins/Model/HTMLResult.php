<?php

namespace NobunaPlugins\Model;

use NobunaPlugins\Exceptions\NobunaError;

class HTMLResult {
    
    /**
     * @var HTMLMessage
     */
    public $messages;
    
    public $code = 0;
    
    public $isError = FALSE;
    
    public $data = array();
    
    private static $GlobalHTMLResult = NULL;
    
    /**
     * @return HTMLResult
     */
    public static function GlobalResult() {
        if(static::$GlobalHTMLResult === NULL) {
            static::$GlobalHTMLResult = new HTMLResult();
        }
        return static::$GlobalHTMLResult;
    }
    
    public static function UseGlobal() {
        return static::$GlobalHTMLResult !== NULL;
    }
    
    public function __construct() {
        $this->messages = new HTMLMessage;
    }
    
    public function printJson() {
        echo json_encode($this);
    }
    
    public function addData($key, $data) {
        $this->data[$key] = $data;
    }
    
    public function setError(NobunaError $error) {
        $this->addCheckConnectionWarning();
        $this->setCode($error->get_error_code());
        $data = $error->get_error_data();
        if(is_array($data) && isset($data['status']) && $data['status'] == 403) {
            $msg = __nb('Your credentials are not valid. Please, '
                    . '<a href="/wp-admin/admin.php?page=nobuna-plugins-settings">'
                    . 'check your settings in Nobuna Plugins &gt; Settings</a>');
            $this->addError($msg);
        } else {
            $this->addError($error->render(), FALSE);
        }
    }
    
    private function addCheckConnectionWarning() {
        static $added = FALSE;
        if($added === FALSE) {
            $added = TRUE;
            $this->addWarning(sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=nobuna-plugins-settings&check=1#check'),
                    __nb('Check connection to server')));
        }
    }
    
    public function addDisclaimer($text) {
        $this->messages->disclaimer = sprintf('<p><span class="disclaimer">%s</span></p>', $text);
    }
    
    public function addIFrame($uri) {
        $this->messages->iframe = sprintf('<iframe frameborder="0" class="nobuna-iframe" scrolling="no" onload="nobuna_resize_iframe(this);" src="%s"></iframe>', $uri);
    }
    
    public function setCode($code) {
        $this->code = $code;
    }
    
    public function addInfo($msg, $wrap = TRUE) {
        $this->messages->info[] = ($wrap ? '<p>' : '') . $msg . ($wrap ? '<p>' : '');
    }
    
    public function addError($msg, $wrap = TRUE) {
        $this->isError = TRUE;
        $this->messages->error[] = ($wrap ? '<p>' : '') . $msg . ($wrap ? '<p>' : '');
    }
    
    public function addWarning($msg, $wrap = TRUE) {
        $this->messages->warning[] = ($wrap ? '<p>' : '') . $msg . ($wrap ? '<p>' : '');
    }
    
    public function addSuccess($msg, $wrap = TRUE) {
        $this->messages->success[] = ($wrap ? '<p>' : '') . $msg . ($wrap ? '<p>' : '');
    }
    
}
