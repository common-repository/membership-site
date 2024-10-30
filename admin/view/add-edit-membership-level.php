<?php
global $wpdb;
include_once MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/add-edit-membership-level.php';
$add_edit_membership_level = new membership_site_add_edit_membership_level;
$id = intval($_GET['id']);
if ($id != '') {
  $membership_level = $add_edit_membership_level->get_membership_level($id);
  $protected_ids = $add_edit_membership_level->get_protected_ids($id);
}
$all_membership_levels = $add_edit_membership_level->get_all_membership_levels();
//echo '<pre>' . print_r($membership_level, 1) . '</pre>';
include_once 'header.php';
global $wpdb;
$sql = "SELECT * from $wpdb->posts WHERE post_status = 'publish' AND post_type = 'page' ORDER BY 'post_title' ASC";
$pages = $wpdb->get_results($sql);
$sql = "SELECT * from $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY 'post_title' ASC";
$posts = $wpdb->get_results($sql);

if (sanitize_text_field($_GET['membersoniclite_action']) == 'editlevel') {
  $page_head    = __('Edit Membership Level', 'membership-site');
}
?>

<div class="page-title">
  <h5>
    <?php
    global $wpdb;
    $sql = "SELECT membership_level_key FROM " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " WHERE id ='" . intval($_GET['id']) . "'";
    $membership_level_key = $wpdb->get_var($sql);
    echo $page_head;
    $otherinfo            = json_decode($membership_level['other_info'], true);
    ?>
  </h5>
  <p><?php if (sanitize_text_field($_GET['membersoniclite_action']) != 'addnewlevel') echo ' [' . $membership_level_key . ']'; ?></p>
</div>
<div class="container-dashboard">
  <?php
  $image_folder = MEMBERSONICLITE_PLUGIN_URL . "/images/";
  ?>
  <ul class="nav nav-tabs" id="msliteTab" role="tablist">
    <li class="nav-item msltabsnav-item">
      <a class="nav-link active" id="step1-tab" data-toggle="tab" href="#step1" role="tab" aria-controls="step1" aria-selected="true"><?php _e('Step 1: Configuration', 'membership-site'); ?></a>
    </li>
    <li class="nav-item msltabsnav-item">
      <a class="nav-link" id="step2-tab" data-toggle="tab" href="#step2" role="tab" aria-controls="step2" aria-selected="false">
        <?php _e('Step 2: Content Access Settings', 'membership-site'); ?></a>
    </li>
    <li class="nav-item msltabsnav-item">
      <a class="nav-link" id="step3-tab" data-toggle="tab" href="#step3" role="tab" aria-controls="step2" aria-selected="false">
        <?php _e('Step 3: Customize Welcome Email', 'membership-site'); ?>
      </a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step2-tab">
      <?php include_once('includes/step1.php') ?>
    </div>
    <div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
      <?php include_once('includes/step2.php') ?>

    </div>
    <div class="tab-pane fade" id="step3" role="tabpanel" aria-labelledby="step3-tab">
      <?php include_once('includes/step3.php') ?>

    </div>
  </div>
</div>

</div>
<script>
  <?php
  $tb = sanitize_text_field(trim($_GET['tb']));
  $tab = ($tb == '') ? 'step1' : $tb;
  ?>
  jQuery('#<?php echo $tab ?>-tab').trigger('click');
</script>