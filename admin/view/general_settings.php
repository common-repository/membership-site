<?php
if (sanitize_text_field($_POST['msliteaction']) == 'saveadminsettings') {
      include(MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/general-settings.php');
      $membersonic_lite_general_settings = new membership_site_general_settings;
      $membersonic_lite_general_settings->save_settings();
}
include_once 'header.php';
$general_settings = get_option('wp_wso_general_settings');
//echo '<pre>'.print_r($general_settings,true).'</pre>';
?>
<div class="nav flex-column nav-pills gensetvpill bg-dark" id="v-pills-tab" role="tablist" aria-orientation="vertical">
      <a class="nav-link active text-white" id="v-pills-redirectpages-tab" data-toggle="pill" href="#v-pills-redirectpages" role="tab" aria-controls="v-pills-redirectpages" aria-selected="true">Redirect Pages</a>
      <a class="nav-link text-white" data-tab="paypalint" id="v-pills-paypalintegration-tab" data-toggle="pill" href="#v-pills-paypalintegration" role="tab" aria-controls="v-pills-paypalintegration" aria-selected="false">PayPal Integration</a>
      <a class="nav-link text-white" id="v-pills-adminsettings-tab" data-toggle="pill" href="#v-pills-adminsettings" role="tab" aria-controls="v-pills-adminsettings" aria-selected="false">Admin Settings</a>
      <a class="nav-link text-white" data-tab="maileroption" id="v-pills-maileroptions-tab" data-toggle="pill" href="#v-pills-maileroptions" role="tab" aria-controls="v-pills-maileroptions" aria-selected="false">Mailer Options</a>
</div>
<form class="generalsettings" method="POST" id="msgensettings">
      <div class="tab-content  gensetvpillcont" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-redirectpages" role="tabpanel" aria-labelledby="v-pills-redirectpages-tab">
                  <div class="col-12">
                        <div class="row">
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    _e('Login Page:', 'membership-site');
                                    ?>
                              </div>
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    $login_page = isset($general_settings['login_page']) ? $general_settings['login_page'] : '' ?>
                                    <select class="form-control" name="login_page" required>
                                          <option value="">Select</option> <?php $pages = get_pages($arg);
                                                                              foreach ($pages as $page) {        ?> <option value="<?php echo get_permalink($page->ID); ?>" <?php echo get_permalink($page->ID) == $login_page ? 'selected=selected' : ''; ?>> <?php echo $page->post_title;                    ?> </option> <?php  }  ?>
                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    _e('After Login Page:', 'membership-site');
                                    ?>
                              </div>
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    $paypal_members_area = isset($general_settings['paypal_members_area']) ? $general_settings['paypal_members_area'] : '' ?>
                                    <select class="form-control" name="paypal_members_area" required>
                                          <option value="">Select</option> <?php $pages = get_pages($arg);
                                                                              foreach ($pages as $page) {        ?> <option value="<?php
                                                                                                                                          echo get_permalink($page->ID);
                                                                                                                                          ?>" <?php
                                                                                                                                                      echo get_permalink($page->ID) == $paypal_members_area ? 'selected=selected' : '';
                                                                                                                                                      ?>> <?php echo $page->post_title;                    ?> </option> <?php  }  ?>
                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    _e('Access Denied Page:', 'membership-site');
                                    ?>
                              </div>
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    $paypal_access_denied = isset($general_settings['paypal_access_denied']) ? $general_settings['paypal_access_denied'] : '' ?>
                                    <select class="form-control" name="paypal_access_denied" required>
                                          <option value="">Select</option> <?php $pages = get_pages($arg);
                                                                              foreach ($pages as $page) {        ?> <option value="<?php
                                                                                                                                          echo get_permalink($page->ID);
                                                                                                                                          ?>" <?php echo get_permalink($page->ID) == $paypal_access_denied ? 'selected=selected' : ''; ?>> <?php echo $page->post_title; ?> </option> <?php  }  ?>
                                    </select>
                              </div>
                        </div>
                        <div class="row ">
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    _e('Content Not Yet Available Page:', 'membership-site');
                                    ?>
                              </div>
                              <div class="col-4 pt-1 pb-1">
                                    <?php
                                    $content_not_yet_available = isset($general_settings['content_not_yet_available']) ? $general_settings['content_not_yet_available'] : '' ?>
                                    <select class="form-control" name="content_not_yet_available" required>
                                          <option value="">Select</option>
                                          <?php $pages = get_pages($arg);
                                          foreach ($pages as $page) {  ?>
                                                <option value="<?php echo get_permalink($page->ID); ?>" <?php echo get_permalink($page->ID) == $content_not_yet_available ? 'selected=selected' : ''; ?>> <?php echo $page->post_title; ?> </option>
                                          <?php  }  ?>
                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    _e('Hide pages of other Membership Levels:', 'membership-site');
                                    ?>
                              </div>
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    $hide_protected_content_links = isset($general_settings['hide_protected_content_links']) ? $general_settings['hide_protected_content_links'] : '' ?>
                                    <input type="checkbox" value="on" name="hide_protected_content_links" <?php
                                                                                                echo $hide_protected_content_links == "on" ? 'checked=checked' : '';
                                                                                                ?> />
                              </div>
                        </div>
                  </div>
            </div>
            <div class="tab-pane fade" id="v-pills-paypalintegration" role="tabpanel" aria-labelledby="v-pills-paypalintegration-tab">

                  <div class="alert alert-info">
                        <h5> To integrate PayPal, create a hosted button inside your paypal account and follow the instructions below.</h5>
                        <p><strong>PayPal Hosted Buttons Settings:</strong></p>
                        <p> <a target="_blank" href="<?php echo MEMBERSONICLITE_PLUGIN_URL; ?>/assets/images/PayPal-payment-button-1.png"><img style="border: 1px #666 solid;" src="<?php echo MEMBERSONICLITE_PLUGIN_URL; ?>/assets/images/PayPal-payment-button-1.png" class="w-25"></a>&nbsp;&nbsp;<a target="_blank" href="<?php echo MEMBERSONICLITE_PLUGIN_URL; ?>/assets/images/PayPal-payment-button-2.png"><img style="border: 1px #666 solid;" src="<?php echo MEMBERSONICLITE_PLUGIN_URL; ?>/assets/images/PayPal-payment-button-2.png" class="w-25"></a></p>
                        <p><strong> IMPORTANT: If your PayPal integration is not working, please follow the instructions below and then contact us. </strong></p>
                        <ol style="list-style-position:inside">
                              <li><strong>
                                          YOUR PayPal IPN URL: </strong> <span style="color:#f00">
                                          <?php echo trailingslashit(home_url()); ?>?mspaypalipn=1 </span></li>
                              <li> Use the IPN Similator to check the PayPal IPN URL. If you get any errors, please post the same on our support desk. <strong><br><a href="https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNSimulator/" target="_blank">
                                                CLICK HERE</a>
                                          for instructions to use IPN simulator. </strong></li>
                              <li>Check the <strong><a target="_blank" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=_display-ipns-history">IPN history page</a></strong> for that failed transaction and upload a screenshot of the page.</li>
                        </ol>
                  </div>

            </div>
            <div class="tab-pane fade" id="v-pills-adminsettings" role="tabpanel" aria-labelledby="v-pills-adminsettings-tab">
                  <form class="generalsettings" method="POST" id="msgensettings">
                        <div class="alert alert-info"><?php
                                                      _e('Use this section to identify your admin account to your members.  Check the Send Signup Notifications box if you wish to recieve email notifications each time a member signs up to your site.', 'membership-site');
                                                      ?> </div>
                        <div class="row">
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    _e('Admin Email Address: ', 'membership-site');
                                    ?>
                              </div>
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    $admin_email_address = isset($general_settings['admin_email_address']) ? $general_settings['admin_email_address'] : '' ?>
                                    <input type="text" name="admin_email_address" class="form-control" value="<?php echo $admin_email_address;       ?>" />
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    _e('Admin display name: ', 'membership-site');
                                    ?>
                              </div>
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    $admin_display_name = isset($general_settings['admin_display_name']) ? $general_settings['admin_display_name'] : '' ?>
                                    <input type="text" name="admin_display_name" class="form-control" value="<?php echo $admin_display_name; ?>" />
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    _e('Send Signup Notifications?', 'membership-site');
                                    ?>
                              </div>
                              <div class="col-4 pt-1 pb-1 ">
                                    <?php
                                    $admin_signup_notifications = isset($general_settings['admin_signup_notifications']) ? $general_settings['admin_signup_notifications'] : '' ?>
                                    <input type="checkbox" name="admin_signup_notifications" value="on" <?php echo ($admin_signup_notifications == "on") ? ' checked=checked' : ''; ?> />
                              </div>
                        </div>
            </div>
            <div class="tab-pane fade" id="v-pills-maileroptions" role="tabpanel" aria-labelledby="v-pills-maileroptions-tab">
                  <div class="alert alert-info">
                        <?php _e('SWIFT SMTP in Membersonic is depreciated.  All outgoing emails will be sent using wp_mail function only.<br/> If you wish to continue using SMTP, install this free plugin <strong><a target="_blank" href="https://wordpress.org/plugins/wp-mail-smtp/">WP MAIL SMTP</a></strong>.', 'membership-site'); ?> </div>
            </div>
            <div class="row">
                  <div class="col-4 pt-1 pb-1 "></div>
                  <div class="col-4 pt-1 pb-1 ">
                        <input type="hidden" name="msliteaction" value="saveadminsettings" />
                        <input type="submit" class="btn btn-md btn-ms w-100" id="memsubmitbut" name="submit" value="Save" />
                  </div>
            </div>
</form>
</div>
</div>
<script>
      jQuery('.nav-link').click(function() {
            var tab = jQuery(this).attr('data-tab');
            if (tab == 'paypalint' || tab == 'maileroption')
                  jQuery('#memsubmitbut').hide();
            else
                  jQuery('#memsubmitbut').show();
      })

</script>