<?php
include_once 'header.php';
include(MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/manage-users.php');
$membersoniclite_manage_users = new membership_site_manage_users;
$all_membership_levels = $membersoniclite_manage_users->get_all_membership_levels();

$filterkw            = $membersoniclite_manage_users->search_member($data);
require_once MEMBERSONICLITE_PLUGIN_DIR . '/helper/paginator.class.php';
$pages = new membership_site;
$pages->items_total = $membersoniclite_manage_users->total_members($filterkw, $data);
$pages->paginate();
$list_member         = $membersoniclite_manage_users->list_all_member($filterkw, $data, $pages);
?>
<div class="col-12 mt-2 mb-2">
  <div class="row"> <button class="btn btn-sm btn-ms mr-2" data-toggle="modal" data-target="#addnewmemberModal"> <?php _e('Add New Member', 'membership-site'); ?></button> <a href="<?php echo admin_url('admin.php?page='.'membership-site'.'-manage-users-import'); ?>" class="btn btn-sm btn-ms"> <?php _e('Import', 'membership-site'); ?></a> </div>
</div>
<div class="container-dashboard">
  <div class="list-search" name="listsearch">
    <table class="table table-responsive table-hover">
      <thead>
        <tr>
          <th width="25%"> <?php _e('Name', 'membership-site'); ?> </th>
          <th width="15%"> <?php _e('Username', 'membership-site'); ?> </th>
          <th width="15%"> <?php _e('Email', 'membership-site'); ?> </th>
          <th width="10%"> <?php _e('Access', 'membership-site'); ?> </th>
        </tr>
      </thead>
      <?php
      if (!empty($list_member)) {
        foreach ($list_member as $value) { ?>
          <tr>
            <td>
              <?php
                  $url = admin_url('user-edit.php?user_id=' . $value->ID);
                  echo '<a  target="_blank" href="' . $url . '">' . $value->first_name . " " . $value->last_name . '</a>'; ?>
            </td>
            <td><span> <?php echo $value->user_login; ?> </span></td>
            <td><?php echo $value->user_email; ?></td>

            <td>
              <button type="button" class="btn btn-sm btn-ms" data-name="<?php echo $value->first_name . ' ' . $value->last_name; ?>" data-userid="<?php echo $value->ID; ?>" data-toggle="modal" data-target="#updateMemberModal">
                Update Membership
              </button>
            </td>
          </tr> <?php } ?>
        <tr>
          <td colspan="8" class="mspaginator text-right"><?php echo $pages->display_pages(); ?></td>
        </tr> <?php } else { ?> <tr class="alternate">
          <td colspan="6"><?php _e('No Records.', 'membership-site'); ?></td>
        </tr> <?php } ?>
    </table>
  </div>
  <div class="msmodal-backdrop d-none"></div>
  <div class="modal fade" id="addnewmemberModal" tabindex="-1" role="dialog" aria-labelledby="addnewmemberModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addnewmemberModalLabel"><?php _e('Add New Member', 'membership-site'); ?></h5> <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
        <form method="post" id="add_new_member">
          <div class="modal-body">
            <table class="table">
              <tbody>
                <tr>
                  <td><?php _e(' First Name', 'membership-site'); ?></td>
                  <td><input type="text" required id="first_name" class="form-control" value="" /></td>
                </tr>

                <tr>
                  <td><?php _e('Last Name', 'membership-site'); ?></td>
                  <td><input type="text" required id="last_name" class="form-control" value="" /></td>
                </tr>

                <tr>
                  <td><?php _e('Email', 'membership-site'); ?></td>
                  <td><input type="email" required id="email" class="form-control" value="" id="email-address-add" /></td>
                </tr>

                <tr>
                  <td><?php _e('Password', 'membership-site'); ?></td>
                  <td><input type="password" required id="password" class="form-control" id="password" autocomplete="off" />
                </tr>

                <tr>
                  <td><?php _e('MemberShip Level', 'membership-site'); ?> <sup class="mandatory-star">*</sup></td>
                  <td>
                    <select required id="membership_id" class="form-control">
                      <?php
                      if (!empty($all_membership_levels)) {
                        foreach ($all_membership_levels as $membership_level) { ?>
                          <option value="<?php echo $membership_level->id; ?>">
                            <?php echo $membership_level->membership_level_name; ?>
                          </option>
                      <?php }
                      } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td>
                    <div id="success_mem"></div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <span class="result text-success"></span>
            <?php $uniqid = uniqid('freereg' . $membershipid);
            wp_nonce_field($uniqid); ?>
            <input type="hidden" id="uniqid" name="uniqid" value="<?php echo $uniqid; ?>" />
            <button type="button" class=" btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            <input class=" btn btn-sm btn-ms" type="submit" name="add_new_member" value="<?php _e('Add Member', 'membership-site'); ?>" /> </div>
        </form>
      </div>
    </div>
  </div>



  <div class="modal fade" id="updateMemberModal" tabindex="-1" role="dialog" aria-labelledby="updateMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateMemberModalLabel">Update User - <span id="usersname"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body updatemembershipbody">

        </div>
        <div class="modal-footer">
          <input type="hidden" name="msluserid" id="msluserid" value="" />
          <span class="result text-success"></span>
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-ms btn-sm" id="updateMemberbutton">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  jQuery('#updateMemberModal').on('show.bs.modal', function(event) {
    jQuery('.result').text('');
    jQuery('.msmodal-backdrop').removeClass('d-none').addClass('show');

    var button = jQuery(event.relatedTarget)
    var userid = button.data('userid')
    var name = button.data('name')

    var modal = jQuery(this)
    modal.find('#msluserid').val(userid);
    modal.find('#usersname').text(name);
    modal.find('.updatemembershipbody').text('Loading...');
    var data = {
      'action': 'mslite_updateUserMembershiphtml',
      'userid': userid
    };
    jQuery.post(ajaxurl, data, function(response) {
      modal.find('.updatemembershipbody').html(response);
    });
  })
  jQuery('#updateMemberModal').on('hide.bs.modal', function(event) {
    jQuery('.msmodal-backdrop').addClass('d-none').removeClass('show');
  });
  jQuery('#updateMemberbutton').click(function() {
    var userid = jQuery('#msluserid').val();
    var memberlevels = [];
    jQuery.each(jQuery("input[name='mlevel']:checked"), function() {
      memberlevels.push(jQuery(this).val());
    });
    console.log(memberlevels)
    var data = {
      'action': 'mslite_updateUserMembershipsave',
      'userid': userid,
      'memberlevels': memberlevels
    };
    jQuery.post(ajaxurl, data, function(ret) {
      var response = JSON.parse(ret);
      console.log(response.status);
      if (response.status == 'success')
        jQuery('.result').text(response.message);
      else
        jQuery('.result').text('Error.');
    });
  })

  jQuery('#addnewmemberModal').on('show.bs.modal', function(event) {
    jQuery('.result').text('');
    jQuery('.msmodal-backdrop').removeClass('d-none').addClass('show');
  })
  jQuery('#addnewmemberModal').on('hide.bs.modal', function(event) {
    jQuery('.msmodal-backdrop').addClass('d-none').removeClass('show');
  });
  jQuery('#add_new_member').submit(function(e) {
    e.preventDefault();
    var firstname = jQuery('#first_name').val();
    var lastname = jQuery('#last_name').val();
    var email = jQuery('#email').val();
    var membershipid = jQuery('#membership_id').val();
    var password = jQuery('#password').val();
    var sec = jQuery('#_wpnonce').val();
    var uniqid = jQuery('#uniqid').val();

    var data = {
      'action': 'mslite_saveNewUser',
      'firstname': firstname,
      'lastname': lastname,
      'email': email,
      'password': password,
      'membershipid': membershipid,
      'security': sec,
      'uniqid': uniqid
    };
    console.log(data);
    jQuery.post(ajaxurl, data, function(ret) {
      var response = JSON.parse(ret);
      console.log(response);
      if (response.status == 'success')
        jQuery('.result').text(response.message);
      else
        jQuery('.result').text('Error.');

    });
  })
</script>