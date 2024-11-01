<?php

class shortcode_nsc_ivl
{
    private $helper;
    private $user_cleaner;

    public function __construct()
    {
        $this->plugin_configs = new plugin_configs_nsc_ivl();
        $this->user_cleaner = new clean_input_validation_nsc_ivl();
        $this->vendor_list = new iab_vendor_list_nsc_ivl();
    }

    public function add_short_code_nsc_ivl()
    {
        add_shortcode('iab_vendor_list_table_nsc_ivl', array($this, 'iab_vendor_list_table_processor_nsc_ivl'));
    }

    public function iab_vendor_list_table_processor_nsc_ivl()
    {
        $vendors = $this->vendor_list->get_vendor_list_nsc_ivl();
        $features_mapper = $this->vendor_list->get_features_nsc_ivl();
        $purpose_mapper = $this->vendor_list->get_purposes_nsc_ivl();

        $tr_css_class = esc_attr($this->plugin_configs->get_option_nsc_ivl("tr_css_class"));
        $th_css_class = esc_attr($this->plugin_configs->get_option_nsc_ivl("th_css_class"));
        $td_css_class = esc_attr($this->plugin_configs->get_option_nsc_ivl("td_css_class"));
        $ul_list_css_class = esc_attr($this->plugin_configs->get_option_nsc_ivl("ul_list_css_class"));

        $string = "<table id='iab_vendor_list_nsc_ivl' class='" . esc_attr($this->plugin_configs->get_option_nsc_ivl("table_css_class")) . "'>";
        $string .= "<tr class='" . $tr_css_class . "'>";
        $string .= "<th class='" . $th_css_class . "'>" . $this->plugin_configs->get_option_nsc_ivl("th_vendor_name") . "</th>";
        $string .= "<th class='" . $th_css_class . "'>" . $this->plugin_configs->get_option_nsc_ivl("th_leg_purpose") . "</th>";
        $string .= "<th class='" . $th_css_class . "'>" . $this->plugin_configs->get_option_nsc_ivl("th_purpose") . "</th>";
        $string .= "<th class='" . $th_css_class . "'>" . $this->plugin_configs->get_option_nsc_ivl("th_features") . "</th>";
        $string .= "<th class='" . $th_css_class . "'>" . $this->plugin_configs->get_option_nsc_ivl("th_privacy_link") . "</th>";
        $string .= "</tr>";
        foreach ($vendors->vendors as $vendor) {
            $string .= "<tr class='" . $tr_css_class . "'>";
            $string .= "<td class='" . $td_css_class . "'>" . $vendor->name . "</td>";
            $string .= "<td class='" . $td_css_class . "'><ul class='" . $ul_list_css_class . "'>";
            foreach ($vendor->legIntPurposeIds as $leg_purpose_id) {
                $string .= "<li>" . $purpose_mapper[$leg_purpose_id]->name . "</li>";
            }
            $string .= "</ul></td>";
            $string .= "<td class='" . $td_css_class . "'><ul>";
            foreach ($vendor->purposeIds as $purpose_id) {
                $string .= "<li>" . $purpose_mapper[$purpose_id]->name . "</li>";
            }
            $string .= "</ul></td>";
            $string .= "<td class='" . $td_css_class . "'><ul class='" . $ul_list_css_class . "'>";
            foreach ($vendor->featureIds as $feature_id) {
                $string .= "<li>" . $features_mapper[$feature_id]->name . "</li>";
            }
            $string .= "</ul></td>";
            $string .= "<td class='" . $td_css_class . "'><a target='_blank' href='" . $vendor->policyUrl . "'>" . $this->plugin_configs->get_option_nsc_ivl("td_privacy_link") . "</td>";
            $string .= "<tr>";
        }
        $string .= "</table>";
        return $string;
    }
}
