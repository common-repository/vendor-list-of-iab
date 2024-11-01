<?php

class uninstaller_nsc_ivl
{

    public function delete_options_nsc_ivl()
    {
        $plugin_configs = new plugin_configs_nsc_ivl;
        $settings = $plugin_configs->return_plugin_configs_without_db_settings_nsc_ivl();
        $prefix = $settings->plugin_prefix;
        foreach ($settings->setting_page_fields->tabs as $tab) {
            foreach ($tab->tabfields as $fields) {
                delete_option($prefix . $fields->field_slug);
            }
        }
    }

    public function remove_directory_nsc_ivl()
    {
        $plugin_configs = new plugin_configs_nsc_ivl;
        $path = $plugin_configs->return_plugin_upload_base_dir_nsc_ivl();
        // The preg_replace is necessary in order to traverse certain types of folder paths (such as /dir/[[dir2]]/dir3.abc#/)
        // The {,.}* with GLOB_BRACE is necessary to pull all hidden files (have to remove or get "Directory not empty" errors)
        $files = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path) . '/{,.}*', GLOB_BRACE);
        foreach ($files as $file) {
            if ($file == $path . '/.' || $file == $path . '/..') {continue;} // skip special dir entries
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);
        return;
    }
}
