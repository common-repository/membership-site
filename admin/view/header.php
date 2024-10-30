<div id="fb-root"></div>
<script>
      (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=302882273151220";
            fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
</script>
<?php
$image_folder = MEMBERSONICLITE_PLUGIN_URL . "/assets/images/";
?>
<div class="wrap qazert">
      <div class="plugin-heading"><span><img style="width:30%;" src="<?php echo $image_folder; ?>ms-lite-logo.png" /></span><span class="wso-title-style"></span>
            <div class="col-12 pb-2">
                  <div class="row">

                        <div class="col-6">
                              <div class="facebook-likebutton-wsomember">
                                    <div class="fb-like" data-href="https://www.facebook.com/MemberSonic?sk=app_402427893139160" data-send="true" data-width="450" data-show-faces="false"></div>
                              </div>
                        </div>
                        <div class="col-6">
                              <div class="pluginsupport">
                                    <a href="https://one.pluginresults.com/knowledgebase_category/membersonic-lite/" target="_blank" class=" text-white text-sm-center btn-sm float-right mr-2">
                                          <?php _e('TUTORIALS', 'membership-site'); ?>
                                    </a> <a href="https://support.pluginresults.com/" target="_blank" class=" text-white text-sm-center btn-sm float-right mr-2">
                                          <?php _e('SUPPORT ', 'membership-site'); ?>
                                    </a>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
      <nav class="nav  bg-dark mb-2">
            <a class="nav-link text-white <?php echo (sanitize_text_field($_GET['page']) == '' . 'membership-site' . '-dashboard') ? 'active' : ''; ?>" href="<?php echo admin_url('admin.php?page=' . 'membership-site' . '-dashboard'); ?>"><?php _e('Dashboard', 'membership-site'); ?></a>

            <a class="nav-link text-white <?php echo (sanitize_text_field($_GET['page']) ==  'membership-site' . '-general_settings') ? 'active' : ''; ?>" href="<?php echo admin_url('admin.php?page=' . 'membership-site' . '-general_settings'); ?>"><?php _e('General Settings', 'membership-site'); ?></a>

            <a class="nav-link text-white <?php echo (sanitize_text_field($_GET['page']) ==  'membership-site' . '-content_protection') ? 'active' : ''; ?>" href="<?php echo admin_url('admin.php?page=' . 'membership-site' . '-content_protection'); ?>"><?php _e('Content Protection', 'membership-site'); ?></a>
            <a class="nav-link text-white <?php echo (sanitize_text_field($_GET['page']) ==  'membership-site' . '-manage-users' || sanitize_text_field($_GET['page']) == 'membership-site' . '-manage-users-import') ? 'active' : ''; ?>" href="<?php echo admin_url('admin.php?page=' . 'membership-site' . '-manage-users'); ?>"><?php _e('Members', 'membership-site'); ?></a>
            <a class="nav-link text-white" target="_blank" href="https://www.pluginresults.com/pro-upgrade/"><?php _e('Upgrade', 'membership-site'); ?></a>

      </nav>