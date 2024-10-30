<?php  /*
Plugin Name: Membersonic Lite
Plugin URI: https://www.membersonic.com
Description: Membersonic Lite is a membership site plugin designed for rapid setup and secure delivery of products and membership content to your members.
Version: 2.0.2
Author: Plugin Results
Author URI: https://www.pluginresults.com
*/

include_once "constants.php";
//IMPORTANT: only change when Db is altered
$ms_db_version = '2.3';
$installed_ver = get_option("ms_db_version");
if ($installed_ver != $ms_db_version) {
    include_once MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/db.php';
    $membersonicLite_db = new membership_site_db_model();
    $membersonicLite_db->create_table($ms_db_version);
}
include_once 'membersonic_class.php';
$membersonicLite__membership = new membership_site_membership();
?>