<?php

if (defined('WP_UNINSTALL_PLUGIN') === false) {
    echo "no way";
    exit;
}

define('PLUGIN_PATH_NSC_IVL', plugin_dir_path(__FILE__));
define('PLUGIN_CONFIGS_PATH_NSC_IVL', PLUGIN_PATH_NSC_IVL . "/plugin-config.json");
define('PLUGIN_URL_NSC_IVL', plugin_dir_url(__FILE__));

require dirname(__FILE__) . "/class/class-plugin-configs-nsc_ivl.php";
require dirname(__FILE__) . "/class/class-uninstall-nsc_ivl.php";

$uninstaller = new uninstaller_nsc_ivl();

$uninstaller->delete_options_nsc_ivl();
$uninstaller->remove_directory_nsc_ivl();
