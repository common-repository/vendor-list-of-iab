<?php
/*
Plugin Name: Vendor List of IAB
Description: This plugin gives you a shortcode to include the vendor list everywhere on your page as table, e.g. for your privacy policy.
Author: Nikel Schubert
Version: 1.0
Author URI: https://nikel.co/
Text Domain: iab-vendor-list-nsc
License: GPL3

Vendor List of IAB is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

Vendor List of IAB is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Vendor List of IAB. If not, see https://www.gnu.org/licenses/gpl-3.0.en.html.
 */
if (!defined('ABSPATH')) {
    exit;
}

define('PLUGIN_PATH_NSC_IVL', plugin_dir_path(__FILE__));
define('PLUGIN_CONFIGS_PATH_NSC_IVL', PLUGIN_PATH_NSC_IVL . "/plugin-config.json");
define('PLUGIN_URL_NSC_IVL', plugin_dir_url(__FILE__));

require dirname(__FILE__) . "/class/class-plugin-configs-nsc_ivl.php";
require dirname(__FILE__) . "/class/class-input-validation-nsc_ivl.php";
require dirname(__FILE__) . "/class/class-admin-html-formfields-nsc_ivl.php";
require dirname(__FILE__) . "/class/class-admin-settings-nsc_ivl.php";
require dirname(__FILE__) . "/class/class-cookies-nsc_ivl.php";
require dirname(__FILE__) . "/class/class-iab-vendor-list-nsc_ivl.php";
require dirname(__FILE__) . "/class/class-shortcode-nsc_ivl.php";

$plugin_nsc_ivl = new iab_vendor_list_nsc_ivl();

if (isset($_POST["action"]) && $_POST["action"] == "update") {
    $plugin_nsc_ivl->download_vendor_list_from_iab_nsc_ivl();
}

$nsc_cookies = new cookies_nsc_ivl();
add_action('get_header', array($nsc_cookies, 'save_last_visit_cookie_nsc_ivl'));

//creates admin page
$backendpage_nsc_ivl = new admin_settings_nsc_ivl;
$backendpage_nsc_ivl->execute_wordpress_actions_nsc_ivl();

add_filter("plugin_action_links_" . plugin_basename(__FILE__), array($backendpage_nsc_ivl, 'add_settings_link_nsc_ivl'));

add_action('admin_enqueue_scripts', 'enqueue_admin_script_on_admin_page_nsc_ivl');
function enqueue_admin_script_on_admin_page_nsc_ivl($hook)
{
    if ($hook == 'settings_page_settings_nsc_ivl') {
        wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . '/admin/js/admin.js');
    }
}

//for shortcode
$shortcode_nsc_ivl = new shortcode_nsc_ivl;
$shortcode_nsc_ivl->add_short_code_nsc_ivl();
