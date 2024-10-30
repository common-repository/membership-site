<div class="qazert frontwidth">
  <?php
  $general_settings = get_option('wp_wso_general_settings');
  $current_user     = wp_get_current_user();
  $userloginName    = $current_user->user_login;
  $userid           = $current_user->ID;
  if (is_user_logged_in()) {
    ?>
    <div id="log-out-Widget">
      <div id="membership-info">
        <label class="welcome-header">
          <?php _e('Welcome ', 'membership-site'); ?>
          <?php $name = ($current_user->first_name == '') ? $current_user->display_name : $current_user->first_name;
            echo $name . ','; ?>
        </label>
        <br />
        <label> <?php _e('You have access to the following:', 'membership-site'); ?> </label>
        <ul>
          <?php
            global $wpdb;
            $sql       = "SELECT membership_level_name, other_info FROM " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " as member INNER JOIN " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " as details ON details.id = member.wp_membership_id WHERE user_id =" . $userid . " AND member.is_active = 1";
            $getlevels = $wpdb->get_results($sql);
            foreach ($getlevels as $levels) :
              ?>
            <li>
              <?php
                  $otherinfo            = json_decode($levels->other_info, true);
                  $after_login_memlevel = $otherinfo['after_login_memlevel'];
                  $after_login_memlevel = isset($after_login_memlevel) ? $after_login_memlevel : '';
                  echo '<a href="' . $after_login_memlevel . '">' . $levels->membership_level_name . '</a>';
                  ?>
            </li>
          <?php
            endforeach;
            ?>
        </ul>
        <a href="<?php echo wp_logout_url(); ?>&redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?>" title="Logout" id="logout-button"> <?php _e('Click here to Logout', 'membership-site'); ?> </a>
      </div>
    </div>
  <?php
  } else if (!is_user_logged_in()) {
    ?>
    <div class="col-12">
      <form name="msloginform" class="wp-smloginform" id="msloginform" action="" method="post">
        <div id="uservalidation_error" class="alert row mb-2"></div>
        <div class="row mt-2">
          <label> <?php _e('Username:', 'membership-site'); ?> </label>
          <input type="text" required class="form-control" name="log" id="msuser_login" value="<?php echo attribute_escape(stripslashes($user_login)); ?>" />
        </div>
        <div class="row mt-2">
          <label class="sort-error-label"><?php _e('Password:', 'membership-site'); ?></label>
          <input type="password" required class="form-control" name="pwd" id="msuser_pass">
        </div>
        <div class="row mt-2">
          <input type="hidden" id="redirect-too" name="redirect_to" value="<?php echo $general_settings['paypal_members_area']; ?>" />
          <input type="hidden" name="testcookie" value="1" />
          <input type="submit" value="<?php _e('LOGIN', 'membership-site'); ?>" name="wp-submit" id="wp-submit-align" class="btn btn-block">
        </div>
      </form>
    </div>
  <?php
  }
  ?>
</div>