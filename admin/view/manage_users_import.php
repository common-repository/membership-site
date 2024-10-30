<?php
include_once 'header.php';
include(MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/manage-users.php');
$membersoniclite_manage_users = new membership_site_manage_users;
$memlevels = $membersoniclite_manage_users->get_all_membership_levels();
if (intval($_POST['importfile']) == "1") {
  include MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/import-members.php';
  $membersoniclite_import = new membership_site_import;
  $res = $membersoniclite_import->import();
}
?>
<h5 class="mt-2">
  <?php
  _e('Import members', 'membership-site');
  ?>
</h5>
<p class="col-12 alert alert-info"><?php _e('Import a comma separated CSV file.  Email field is the only required field', 'membership-site'); ?>. <a style="color: #CF0101;" href="<?php echo MEMBERSONICLITE_PLUGIN_URL . '/admin/view/importsample.txt'; ?>" target="_blank"> Download Sample File</a></p>

<div class="col-12">
  <form name="import_members" class="table" method="POST" enctype="multipart/form-data">
    <div class="row">
      <?php
      $resclass = ($res == 'ok') ? 'notice-success' : 'notice-error ';
      if ($res == 'ok')
        echo '<p class="' . $resclass . ' notice importnotice" >' . __('All data Imported Successfully', 'membership-site') . '</p>';
      if ($res == 'nouser') {
        echo '<p class="' . $resclass . ' notice importnotice" >' . __('Some data import Failed with error "WP Users Not Found". Please check the log here...', 'membership-site') . '<a style="color: #CF0101;" href="' . MEMBERSONICLITE_PLUGIN_URL . '/log/nouser.txt" target="_blank"> Log</a></p>';
        //  _e('Some data import Failed with error "WP Users Not Found". Please check the log here...')
        ?>
      <?php } ?>
    </div>
    <div class="row">
      <div class="col-4">
        <p><?php _e('Create Users if they are New', 'membership-site'); ?></p>
      </div>
      <div class="col-4">
        <p><input type="checkbox" name="createnew" id="createnew" value="1" /></p>
      </div>
    </div>
    <div class="row">
      <div class="col-4">
        <?php _e('Select Membership Level', 'membership-site'); ?>
      </div>
      <div class="col-4">
        <p> <select required name="mlevelkey" class="form-control">
            <option value=""><?php _e('Select', 'membership-site'); ?></option>
            <?php foreach ($memlevels as $mem) {
              echo '<option value="' . $mem->membership_level_key . '">' . $mem->membership_level_name . '</option>';
            }
            ?>
          </select></p>
      </div>
    </div>
    <div class="row">
      <div class="col-4">
        <?php _e('Select CSV File', 'membership-site'); ?>
      </div>
      <div class="col-4">
        <p> <input type="file" name="import_members_txt" class="form-control" id="import_members_txt" required /></p>
      </div>

    </div>
    <div class="row">
      <div class="col-4">
      </div>
      <div class="col-4">
        <?php
        $uniqid = uniqid('msimportusers');
        wp_nonce_field($uniqid); ?>
        <input type="hidden" id="uniqid" name="uniqid" value="<?php echo $uniqid; ?>" />
        <p><input type="hidden" name="importfile" value="1" /><input value="<?php _e('Import Members', 'membership-site'); ?>" type="submit" name="import_members" class=" btn btn-sm btn-ms" /></p>
      </div>

    </div>


  </form>
</div>
</div>