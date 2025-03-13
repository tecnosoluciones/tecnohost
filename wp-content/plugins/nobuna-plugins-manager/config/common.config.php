<?php

define('NOBUNA_PLUGINS_FOLDER', sprintf('%s/%s', WP_PLUGIN_DIR, NOBUNA_PLUGINS_DOMAIN));
define('NOBUNA_PLUGINS_MAIN_FILE', sprintf('%s/nobuna-plugins-manager.php', NOBUNA_PLUGINS_FOLDER));
define('NOBUNA_PLUGINS_LOCALE_FOLDER', sprintf('%s/languages/', NOBUNA_PLUGINS_DOMAIN));

define('NOBUNA_TYPE_PLUGIN', 'plugin');
define('NOBUNA_TYPE_THEME', 'theme');

define('NOBUNA_ACTION_DOWNLOAD', 'plugin');
define('NOBUNA_ACTION_BACKUP', 'theme');

define('NOBUNA_KEY_OPTION_KEY', 'nobuna_key');
define('NOBUNA_SECRET_OPTION_KEY', 'nobuna_secret');
define('NOBUNA_DOWNLOADS_COUNT_OPTION_KEY', 'nobuna_downloads_count');
define('NOBUNA_BACKUPS_COUNT_OPTION_KEY', 'nobuna_backups_count');
define('NOBUNA_REQUESTS_PROTOCOL_OPTION_KEY', 'nobuna_requests_protocol');
define('NOBUNA_ITEMS_PER_PAGE_OPTION_KEY', 'nobuna_items_per_page');
define('NOBUNA_USE_ADMIN_MENU_OPTION_KEY', 'nobuna_use_admin_menu');

define('NOBUNA_DOWNLOADS_COUNT_DEFAULT', 2);
define('NOBUNA_BACKUPS_COUNT_DEFAULT', 2);
define('NOBUNA_ITEMS_PER_PAGE_DEFAULT', 20);
define('NOBUNA_REQUESTS_PROTOCOL_DEFAULT', 'https');
define('NOBUNA_PROTOCOL_HTTPS', 'https');
define('NOBUNA_PROTOCOL_HTTP', 'http');


define('NOBUNA_DEFAULT_DATE_FORMAT', 'F j, Y');
define('NOBUNA_DEFAULT_TIME_FORMAT', 'g:i a');


