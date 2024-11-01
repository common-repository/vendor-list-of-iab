<?php

class plugin_configs_nsc_ivl
{
    private $config_file_path;
    private $configs_as_object;
    private $configs_as_object_without_db;
    private $active_tab;

    public function get_option_nsc_ivl($option_slug)
    {
        $option_value = false;
        $settings_for_options = $this->return_plugin_configs_without_db_settings_nsc_ivl();
        foreach ($settings_for_options->setting_page_fields->tabs as $tab) {
            foreach ($tab->tabfields as $field) {
                if ($field->field_slug == $option_slug) {
                    $option_value = get_option($settings_for_options->plugin_prefix . $option_slug, $field->pre_selected_value);
                    break;
                }
            }
        }
        return $option_value;
    }

    public function update_option_nsc_ivl($option_slug, $value, $autoload = true)
    {
        $settings_for_options = $this->return_plugin_configs_without_db_settings_nsc_ivl();
        update_option($settings_for_options->plugin_prefix . $option_slug, $value, $autoload);
    }

    public function plugin_prefix_nsc_ivl()
    {
        $this->return_plugin_configs_nsc_ivl();
        return $this->configs_as_object->plugin_prefix;
    }

    public function plugin_slug_nsc_ivl()
    {
        $this->return_plugin_configs_nsc_ivl();
        return $this->configs_as_object->plugin_slug;
    }

    public function return_plugin_configs_nsc_ivl()
    {
        if (empty($this->configs_as_object)) {
            $this->configs_as_object = $this->return_plugin_configs_without_db_settings_nsc_ivl();
            $this->add_current_setting_values();
        }
        return $this->configs_as_object;
    }

    public function return_plugin_configs_without_db_settings_nsc_ivl()
    {
        if (empty($this->configs_as_object_without_db)) {
            $this->configs_as_object_without_db = $this->read_config_file();
            if (empty($this->configs_as_object_without_db)) {
                throw new Exception($this->config_file_path . " was not readable. Make sure it contains valid json.");
            }
        }
        return $this->configs_as_object_without_db;
    }

    public function return_settings_field_nsc_ivl($searched_field_slug)
    {
        $this->return_plugin_configs_nsc_ivl();
        foreach ($this->configs_as_object->setting_page_fields->tabs as $tab) {
            $number_of_fields = count($tab->tabfields);
            for ($i = 0; $i < $number_of_fields; $i++) {
                if ($tab->tabfields[$i]->field_slug == $searched_field_slug) {
                    return $tab->tabfields[$i];
                }
            }
        }
    }

    public function return_plugin_upload_base_dir_nsc_ivl()
    {
        $uploadDirArray = wp_upload_dir();

        $defaultUploadDirPath = realpath($uploadDirArray['basedir']);

        $resultToReturn = $defaultUploadDirPath . "/" . $this->plugin_slug_nsc_ivl() . "/";
        if (!is_dir($resultToReturn)) {
            mkdir($resultToReturn);
        }
        return $resultToReturn;
    }

    private function read_config_file()
    {
        $this->config_file_path = PLUGIN_CONFIGS_PATH_NSC_IVL;
        $settings = file_get_contents($this->config_file_path);
        $settings = json_decode($settings);
        if (empty($settings)) {
            throw new Exception($this->config_file_path . " was not readable. Make sure it contains valid json.");
        }
        return $settings;
    }

    private function get_active_tab()
    {
        $this->active_tab = "";
        if (isset($_GET["tab"])) {
            $this->active_tab = $_GET["tab"];
        } else {
            $this->active_tab = $this->configs_as_object->setting_page_fields->tabs[0]->tab_slug;
        }
    }

    // this fuctions gets the value saved in wordpress db using get_option
    // and adds it to the config object in the pre_selected_value field.
    // if no value is set it sets the default value from config file.
    private function add_current_setting_values()
    {
        $this->get_active_tab();
        $this->configs_as_object->setting_page_fields->active_tab_slug = $this->active_tab;
        $numper_of_tabs = count($this->configs_as_object->setting_page_fields->tabs);
        for ($t = 0; $t < $numper_of_tabs; $t++) {
            $number_of_fields_in_this_tab = count($this->configs_as_object->setting_page_fields->tabs[$t]->tabfields);
            if ($this->active_tab == $this->configs_as_object->setting_page_fields->tabs[$t]->tab_slug) {
                $this->configs_as_object->setting_page_fields->tabs[$t]->active = true;
                $this->configs_as_object->setting_page_fields->active_tab_index = $t;
            }
            for ($f = 0; $f < $number_of_fields_in_this_tab; $f++) {
                $option_slug = $this->configs_as_object->plugin_prefix . $this->configs_as_object->setting_page_fields->tabs[$t]->tabfields[$f]->field_slug;
                $default_value = $this->configs_as_object->setting_page_fields->tabs[$t]->tabfields[$f]->pre_selected_value;
                $wp_option_value = get_option($option_slug, $default_value);
                $this->configs_as_object->setting_page_fields->tabs[$t]->tabfields[$f]->pre_selected_value = $wp_option_value;
            }
        }
    }
}
