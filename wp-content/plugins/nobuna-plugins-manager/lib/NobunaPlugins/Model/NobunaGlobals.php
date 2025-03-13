<?php

namespace NobunaPlugins\Model;

use NobunaPlugins\Exceptions\NobunaError;

class NobunaGlobals {

    public static function UseGlobalResult() {
        return HTMLResult::UseGlobal();
    }

    public static function SetGlobalError(NobunaError $error) {
        if (static::UseGlobalResult()) {
            HTMLResult::GlobalResult()->setError($error);
        }
    }

    public static function SetGlobalWarning($msg) {
        if (static::UseGlobalResult()) {
            HTMLResult::GlobalResult()->addWarning($msg);
        }
    }

    public static function SetGlobalDisclaimer($msg) {
        if (static::UseGlobalResult() && strlen(trim($msg)) > 0) {
            HTMLResult::GlobalResult()->addDisclaimer($msg);
        }
    }

    public static function SetGlobalIFrame($uri) {
        if (static::UseGlobalResult() && strlen(trim($uri)) > 0) {
            HTMLResult::GlobalResult()->addIFrame(trim($uri));
        }
    }

}
