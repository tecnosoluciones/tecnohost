<?php

namespace NBHelpers;

class System {

    public static function SetMinimumMemoryLimit($new_memory_limit) {
        $memory_limit = @ini_get('memory_limit');

        if ($memory_limit > -1) {
            $unit = strtolower(substr($memory_limit, -1));
            $new_unit = strtolower(substr($new_memory_limit, -1));

            $memory_limit = intval($memory_limit);
            $new_memory_limit = intval($new_memory_limit);

            if ('m' == $unit) {
                $memory_limit *= 1048576;
            } elseif ('g' == $unit) {
                $memory_limit *= 1073741824;
            } elseif ('k' == $unit) {
                $memory_limit *= 1024;
            }

            if ('m' == $new_unit) {
                $new_memory_limit *= 1048576;
            } else if ('g' == $new_unit) {
                $new_memory_limit *= 1073741824;
            } else if ('k' == $new_unit) {
                $new_memory_limit *= 1024;
            }

            if ((int) $memory_limit < (int) $new_memory_limit) {
                @ini_set('memory_limit', $new_memory_limit);
            }
        }
    }

}
