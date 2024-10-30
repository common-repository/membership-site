<div class="qazert frontwidth">
  <div class="col-12 alert border-dark">
    <?php _e('To access this content, please create your membership profile. If you are already a registered user, Login below.', 'membership-site'); ?>
  </div>
  <div class="col-12">
    <form name="msloginform" class="wp-smloginform" id="msloginform" action="" method="post">
      <div id="uservalidation_error" class="alert row mb-2"></div>
      <div class="row mt-2">
        <label> <?php _e('Username:', 'membership-site'); ?> </label>
        <input type="text" required class="form-control" name="log" id="msuser_login" value="" />
      </div>
      <div class="row mt-2">
        <label class="sort-error-label"><?php _e('Password:', 'membership-site'); ?></label>
        <input type="password" required class="form-control" name="pwd" id="msuser_pass">
      </div>
      <div class="row mt-2">
        <input type="hidden" id="redirect-too" name="redirect_to" value="<?php echo $general_settings['paypal_members_area']; ?>" />
        <input type="hidden" class="levelIdlogin" id="mslevelId" name="mlevellogin" value="<?php echo $membershipid; ?>" />
        <input type="hidden" name="testcookie" value="1" />
        <input type="submit" value="<?php _e('LOGIN', 'membership-site'); ?>" name="wp-submit" id="wp-submit-align" class="btn btn-block">
      </div>
    </form>
  </div>
  <div style="clear: both;"></div>
  <div class="row col-12 mt-4">
    <h4><?php _e('New Member Registration', 'membership-site'); ?></h4>
  </div>
  <div class="col-12">
    <form method="POST" id="newregistration" name="newregistration">
      <div class="row mt-2">
        <label><?php _e('First Name:', 'membership-site'); ?></label>
        <input type="text" id="msfirst_name" required name="first_name_new" class="form-control name_first" autocomplete="off" />
        <label id="first_name_error"></label>
      </div>
      <div class="row mt-2">
        <label><?php _e('Last Name:', 'membership-site'); ?></label>
        <input type="text" id="mslast_name" required name="last_name_new" class="name_last form-control " autocomplete="off" />
      </div>
      <div class="row mt-2">
        <label><?php _e('Email Id:', 'membership-site'); ?></label>
        <input type="email" id="msemail" required name="email_id_new" class="msmailid form-control" autocomplete="off" />
      </div>
      <div class="row mt-2">
        <label><?php _e('Password :', 'membership-site'); ?></label>
        <input type="password" id="mspassword1" required name="password_text1_new" class="pass_word1 form-control" autocomplete="off" />
        <label id="password_text1_error"></label>
      </div>
      <div class="row mt-2">
        <label><?php _e('Password (repeat):', 'membership-site'); ?></label>
        <input type="password" id="mspassword2" required name="password_text2_new" class="pass_word2 form-control" autocomplete="off" />
        <label id="password_text2_error"></label>
      </div>
      <div class="row mt-2">
        <?php
        $uniqid = uniqid('freereg' . $membershipid);
        wp_nonce_field($uniqid); ?>
        <input type="hidden" id="uniqid" name="uniqid" value="<?php echo $uniqid; ?>" />
        <input type="hidden" class="levelId" name="mlevel" id="membership_id" value="<?php echo $membershipid; ?>" /></td>
        <td><input type="submit" class="btn btn-block" id="member_add" name="new_member_register" value="<?php _e('Sign Up', 'membership-site'); ?>" />
          <div id="result" class="alert col-12 mt-2"></div>
      </div>
    </form>
  </div>
</div>