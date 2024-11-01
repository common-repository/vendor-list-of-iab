<?php

class iab_vendor_list_nsc_ivl
{
    private $plugin_configs;
    private $vendor_list_path;
    private $input_cleaner;
    private $vendor_list_object;
    private $purposes_list_object;

    public function __construct()
    {
        $this->plugin_configs = new plugin_configs_nsc_ivl();
        $this->input_cleaner = new clean_input_validation_nsc_ivl();
    }

    public function download_vendor_list_from_iab_nsc_ivl()
    {
        $this->download_vendorlist();
        $this->download_purpose_list();

        $this->plugin_configs->update_option_nsc_ivl("vendor_list_last_downloaded", time());

    }

    public function get_vendor_list_nsc_ivl()
    {
        if (empty($this->vendor_list_object)) {
            $this->vendor_list_object = $this->return_json_file_as_object($this->get_vendor_list_path());
        }
        return $this->vendor_list_object;
    }

    public function get_purposes_nsc_ivl()
    {
        $purposes = $this->get_purpose_list_nsc_ivl();
        $purposes_assoc_array = array();
        foreach ($purposes->purposes as $purpose) {
            $purposes_assoc_array[$purpose->id] = $purpose;
        }
        return $purposes_assoc_array;
    }

    public function get_features_nsc_ivl()
    {
        $purposes = $this->get_purpose_list_nsc_ivl();
        $features_assoc_array = array();
        foreach ($purposes->features as $feature) {
            $features_assoc_array[$feature->id] = $feature;
        }
        return $features_assoc_array;
    }

    private function get_purpose_list_nsc_ivl()
    {
        if (empty($this->purposes_list_object)) {
            $this->purposes_list_object = $this->return_json_file_as_object($this->get_purpose_list_path());
        }
        return $this->purposes_list_object;
    }

    private function download_vendorlist()
    {
        $download_url = "https://vendorlist.consensu.org/vendorlist.json";
        $vendorlist_json = $this->download_json($download_url);
        if ($vendorlist_json != "{}") {
            $this->plugin_configs->update_option_nsc_ivl("vendor_list_version_active", json_decode($vendorlist_json)->vendorListVersion);
            $this->plugin_configs->update_option_nsc_ivl("vendor_list_last_updated", json_decode($vendorlist_json)->lastUpdated);
        }
        file_put_contents($this->get_vendor_list_path(), $vendorlist_json);
    }

    private function download_purpose_list()
    {
        $language = $this->plugin_configs->get_option_nsc_ivl("language_code_overwrite");
        if (empty($language)) {
            $language = explode("_", get_locale())[0];
        }

        $download_url_purposes = "https://vendorlist.consensu.org/purposes-" . $language . ".json";

        if ($language == "en") {
            $download_url_purposes = "https://vendorlist.consensu.org/vendorlist.json";
        }

        $purpose_list_json = $this->download_json($download_url_purposes);
        file_put_contents($this->get_purpose_list_path(), $purpose_list_json);
    }

    private function download_json($download_url)
    {
        $json_list = file_get_contents($download_url);
        if ($this->input_cleaner->check_if_json_is_valid_nsc_ivl($json_list) === false) {
            $json_list = "{}";
        }
        return $json_list;
    }

    private function return_json_file_as_object($path)
    {
        if (!file_exists($path)) {
            $this->download_vendor_list_from_iab_nsc_ivl();
        }
        if (!file_exists($path)) {
            $object = json_decode("{}");
        } else {
            $object = json_decode(file_get_contents($path));
        }
        return $object;
    }

    private function get_purpose_list_path()
    {
        if (empty($this->purpose_list_path)) {
            $target = $this->plugin_configs->return_plugin_upload_base_dir_nsc_ivl();
            $this->purpose_list_path = $target . "/purposelist.json";
        }
        return $this->purpose_list_path;
    }

    private function get_vendor_list_path()
    {
        if (empty($this->vendor_list_path)) {
            $target = $this->plugin_configs->return_plugin_upload_base_dir_nsc_ivl();
            $this->vendor_list_path = $target . "/vendorlist.json";
        }
        return $this->vendor_list_path;
    }

}
