<div class="wrap">

<h1><?php echo $settings_object->settings_page_configs->page_title ?></h1>
<p>This plugin downloads the IAB Vendor list and displays it as table. Use this shortcode to display it everywhere needed: <strong>[iab_vendor_list_table_nsc_ivl]</strong>, e.g. data privacy policy or consent banner. If you need to style the table you can
give the different elements of the table custom css classes.</p>
<p>Some links:<br>
<a href="https://vendorlist.consensu.org/vendorinfo.json" target="_blank">Raw vendor info file</a><br>
<a href="https://vendorlist.consensu.org/purposes-de.json" target="_blank">Raw translation file: german example</a><br>
<a href="https://github.com/InteractiveAdvertisingBureau/GDPR-Transparency-and-Consent-Framework/blob/master/Consent%20string%20and%20vendor%20list%20formats%20v1.1%20Final.md" target="_blank">Github page with official documentation</a>
</p>
<p>
<h4>Usage</h4>
Put shortcode: <strong>[iab_vendor_list_table_nsc_ivl]</strong> anywhere on your site. If needed change the table headlines in the settings area.
</p>


<h2 class="nav-tab-wrapper">
<?php
//tabs are created
foreach ($settings_object->setting_page_fields->tabs as $tab) {
    $activeTab = "";
    if ($tab->active === true) {
        $activeTab = 'nav-tab-active';
    }
    echo '<a href="?page=' . $settings_object->plugin_slug . '&tab=' . $tab->tab_slug . '" class="nav-tab ' . $activeTab . '" >' . $tab->tabname . '</a>';
}
$active_tab_index = $settings_object->setting_page_fields->active_tab_index;

?>
</h2>
<p><?php echo $settings_object->setting_page_fields->tabs[$active_tab_index]->tab_description ?></p>
<form action="<?php echo $settings_object->setting_page_fields->tabs[$active_tab_index]->form_action ?>" method="post">
<?php
settings_fields($settings_object->plugin_slug . $settings_object->setting_page_fields->tabs[$active_tab_index]->tab_slug);
?>

<?php submit_button();?>

<table class="form-table">
<?php foreach ($settings_object->setting_page_fields->tabs[$active_tab_index]->tabfields as $field_configs) {?>
 <tr>
  <th scope="row">
    <?php echo $field_configs->name ?>
  </th>
  <td>
    <fieldset>
     <label>
      <?php echo $form_fields->return_form_field_nsc_ivl($field_configs, $settings_object->plugin_prefix); ?>
     </label>
     <p class="description"><?php echo esc_html($field_configs->helpertext) ?></p>
    </fieldset>
  </td>
 </tr>
<?php }?>
</table>
</form>
<?php

?>

