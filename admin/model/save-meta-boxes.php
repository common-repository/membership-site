<?php
class membership_site_meta_boxes
{
  function __construct(){

  }
  function save_meta_box()
  {
    global $wpdb;
    $id = intval($_POST['ID']);
    
    $sql = "DELETE FROM " . WP_MEMBERSONICLITE_CONTENT_PROTECTION . " WHERE type_id = '" . $id . "'";
    $wpdb->query($sql);
    if (!empty($_POST['product_assoc'])) {

      $memberlevels = isset($_POST['product_assoc'])? array_keys($_POST['product_assoc']) : array();
      $memberlevels = array_map('intval', $memberlevels);

      foreach ($memberlevels as  $val) {
        $insert_content = array(
          'type_name' => sanitize_text_field($_POST['post_type']),
          'type_id' => intval($id),
          'is_protected' => '1',
          'wp_membership_id' => sanitize_text_field($val),
          'created_by' => sanitize_text_field($_POST['post_author']),
          'created_date' => date_i18n('Y-m-d H:i:s', current_time('timestamp'))
        );
        $wpdb->insert(WP_MEMBERSONICLITE_CONTENT_PROTECTION, $insert_content);
      }
    }
  }
}
