<?php

class admin_settings_nsc_ivl
{
    private $settings;
    private $prefix;
    private $plugin_dir;

    public function __construct()
    {
        $this->plugin_dir = PLUGIN_PATH_NSC_IVL;
        $this->plugin_configs = new plugin_configs_nsc_ivl();
        $this->settings = $this->plugin_configs->return_plugin_configs_without_db_settings_nsc_ivl();
        $this->prefix = $this->settings->plugin_prefix;
    }

    public function execute_wordpress_actions_nsc_ivl()
    {
        add_action('admin_init', array($this, 'register_settings_nsc_ivl'));
        add_action('admin_menu', array($this, 'add_admin_menu_nsc_ivl'));
    }

    public function create_admin_page_nsc_ivl()
    {
        $settings_object = $this->settings;
        $form_fields = new admin_html_formfields_nsc_ivl;
        require $this->plugin_dir . "/admin/tpl/admin.php";
    }
    public function add_admin_menu_nsc_ivl()
    {
        add_options_page($this->settings->settings_page_configs->page_title, $this->settings->settings_page_configs->menu_title, $this->settings->settings_page_configs->capability, $this->settings->plugin_slug, array($this, "create_admin_page_nsc_ivl"));
    }

    public function register_settings_nsc_ivl()
    {
        //settings werden mit db values angereichert
        $this->settings = $this->plugin_configs->return_plugin_configs_nsc_ivl();
        $input_cleaner = new clean_input_validation_nsc_ivl();

        foreach ($this->settings->setting_page_fields->tabs as $tab) {
            foreach ($tab->tabfields as $field) {
                $functionForValidation = array($input_cleaner, "sanitize_user_input_nsc_ivl");
                if ($field->extra_validation_name !== false) {
                    $functionForValidation = array($input_cleaner, $field->extra_validation_name);
                }
                if ($field->save_in_db === true) {
                    register_setting($this->settings->plugin_slug . $tab->tab_slug, $this->prefix . $field->field_slug, $functionForValidation);
                }
            }
        }
    }

    public function add_settings_link_nsc_ivl($links)
    {
        $settings_link = '<a href="options-general.php?page=' . $this->settings->plugin_slug . '">' . __('Settings') . '</a>';
        array_push($links, $settings_link);
        return $links;
    }

}
