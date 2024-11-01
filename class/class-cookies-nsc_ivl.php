<?php

class cookies_nsc_ivl
{
    public function save_last_visit_cookie_nsc_ivl()
    {
        $wordpressUrl = get_bloginfo('url');
        $hostname = parse_url($wordpressUrl, PHP_URL_HOST);
        $plugin_configs = new plugin_configs_nsc_ivl;
        $vendor_list_version = $plugin_configs->get_option_nsc_ivl("vendor_list_version_active");
        $delete_consent_cookie = $plugin_configs->get_option_nsc_ivl("delete_consent_cookie");
        if (
            $delete_consent_cookie == true
            && isset($_COOKIE["nsc_lv_ivl"])
            && $_COOKIE["nsc_lv_ivl"] < $vendor_list_version
        ) {
            $consent_cookie_name = $plugin_configs->get_option_nsc_ivl("consent_cookie_name");
            $this->delete_cookie($consent_cookie_name);
        }

        if (
            (isset($_COOKIE["nsc_lv_ivl"])
                && $_COOKIE["nsc_lv_ivl"] < $vendor_list_version) ||
            !isset($_COOKIE["nsc_lv_ivl"])
        ) {
            $expiryDate = time() + 60 * 60 * 24 * 720;
            $this->set_cookie("nsc_lv_ivl", $vendor_list_version, $expiryDate, "/", $hostname);
        }
    }

    private function set_cookie($cookiename, $cookievalue, $expiryDate, $path, $cookieDomain, $secure = false)
    {
        setcookie($cookiename, $cookievalue, $expiryDate, $path, $cookieDomain, $secure);
    }

    private function delete_cookie($cookiename)
    {
        unset($_COOKIE[$cookiename]);
        setcookie($cookiename, "sd", time() - 3600, "/", "");
    }
}
