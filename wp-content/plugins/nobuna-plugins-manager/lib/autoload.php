<?php

function nobuna_plugins_autoloader($class) {
    $cls = str_replace('\\', '/', $class);
    $path = sprintf('%s/%s.php', __DIR__, $cls);
    if(file_exists($path)) {
        require_once($path);
    } else if(is_nobuna_debug()) {
//        echo sprintf('You should reconsider creating this class: %s', $class);
//        die();
    }
}

spl_autoload_register('nobuna_plugins_autoloader');

