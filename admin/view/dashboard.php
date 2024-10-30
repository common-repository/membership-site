<?php
include_once 'header.php';
?>
<div class="container-dashboard">
  <div class="row ml-0"><a href="<?php echo admin_url('admin.php?page=' . 'membership-site' . '-add-edit-membership-level&membersoniclite_action=addnewlevel'); ?>" class="mt-1 mb-1 membersoniclink text-white text-sm-center btn-sm float-left mr-2"><?php _e('Add New Membership Level', 'membership-site'); ?></a></div>
  <div class="col-12">
    <div class="row">
      <div class="col-8">
        <table class="table table-striped">
          <thead>
            <tr>
              <th style="text-align:left"> <?php _e('Product Title', 'membership-site'); ?>
              <th style="text-align:center"> <?php _e('Unique Product Code', 'membership-site'); ?>
              </th>
              <th style="text-align:center"> <?php _e('Members Today', 'membership-site'); ?>
              </th>
              <th style="text-align:center"> <?php _e('Members Total', 'membership-site'); ?>
              </th>
              <th style="text-align:center"> <?php _e('Actions', 'membership-site'); ?>
              </th>
            </tr>
          </thead>
          <?php
          include_once MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/add-edit-membership-level.php';
          $add_edit_membership_level = new membership_site_add_edit_membership_level;
          $all_membership_levels = $add_edit_membership_level->get_all_membership_levels();
          if (!empty($all_membership_levels)) {
            ?>
            <tr>
              <?php foreach ($all_membership_levels as $level) : ?>
                <td>
                  <?php
                      $levelname = $level->membership_level_name;
                      echo '<strong>' . $levelname . '</strong>';
                      ?>
                </td>
                <td>
                  <?php
                      echo '<em>' . $level->membership_level_key . '</em>';
                      ?>
                </td>
                <td style=" text-align:center">
                  <?php $today = intval($add_edit_membership_level->get_membership_member_count_currentdate($level->id));
                      echo $today;
                      $todaytot = $todaytot + $today; ?>
                </td>
                <td style=" text-align:center">
                  <?php $alltime = intval($add_edit_membership_level->get_membership_member_count($level->id));
                      echo $alltime;
                      $alltimetot = $alltimetot + $alltime; ?>
                </td>
                <td style=" text-align:center">
                  <a href="<?php echo admin_url('admin.php?page=' . 'membership-site' . '-add-edit-membership-level&membersoniclite_action=editlevel&id=' . $level->id); ?>" title="Edit Membership" class="edit-link"><i class="dashicons dashicons-edit text-primary"></i>
                  </a>
                  <a href="<?php echo admin_url('admin.php?page=' . 'membership-site' . '-dashboard&membersoniclite_action=clonelevel&id=' . $level->id . '&levelname=' . $levelname); ?>" title="Clone Membership" class="edit-link"><i class="dashicons dashicons-plus text-success"></i>
                  </a>
                  <a href="<?php echo admin_url('admin.php?page=' . 'membership-site' . '-dashboard&membersoniclite_action=deletelevel&id=' . $level->id); ?>" title="Delete Member" onclick='return confirm("Are you sure to delete this Member Level? Click Yes to continue or No to cancel")'><i class="dashicons dashicons-trash text-danger"></i>
                  </a>
                </td>
            </tr>
          <?php
            endforeach;
            ?>
          <tr>
            <td>
              <strong><?php _e('Total Members', 'membership-site'); ?></strong>
            </td>
            <td>
            </td>
            <td style=" text-align:center">
              <?php
                echo $todaytot;
                ?>
            </td>
            <td style=" text-align:center">
              <?php
                echo $alltimetot;
                ?>
            </td>
            <td></td>
          </tr>
        <?php
        } else {
          ?>
          <tr class="alternate">
            <td style=" text-align:center">
              <?php _e(' No Records.', 'membership-site'); ?></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        <?php
        }
        ?>
        </table>
      </div>
      <div class="col-4">
        <a href="https://www.pluginresults.com/special" target="_blank"><img src="<?php echo MEMBERSONICLITE_PLUGIN_URL; ?>/assets/images/one-ad.png" alt=""></a>
      </div>
    </div>
  </div>
</div>

</div>