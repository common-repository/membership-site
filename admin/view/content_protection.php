<?php include_once 'header.php';
include(MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/content-protection.php');
$memebrsoniclite_content_protection = new membership_site_content_protection;
if ($_POST['msliteaction'] == 'savecontentprotection') {
  $memebrsoniclite_content_protection->save_protected_content();
}
$protected_ids = $memebrsoniclite_content_protection->get_protected_ids();
$all_membership_levels = $memebrsoniclite_content_protection->get_all_membership_levels();
global $wpdb;
$sql = "SELECT ID, post_title from $wpdb->posts WHERE post_status = 'publish' AND post_type = 'page' ORDER BY 'post_title' ASC";
$pages = $wpdb->get_results($sql);
$sql = "SELECT  ID, post_title from $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY 'post_title' ASC";
$posts = $wpdb->get_results($sql);
?>
<form method="post">
  <p class="alert alert-info mt-2"> <?php _e('Check each page or post that you want to protect.  Any page or post with no boxes checked will be unprotected.', 'membership-site'); ?>
  </p>
  <div class="col-12">
    <div class="row bg-dark p-2 text-white mb-1">
      <div class="col-4">
        <span> <?php _e('Pages', 'membership-site'); ?> </span>
      </div>
      <?php 
      if(!is_array($all_membership_levels))
  $all_membership_levels = array();
      foreach ($all_membership_levels as $levelname) { ?>
        <div class="col  text-center">
          <span> <?php echo $levelname ?> </span>
        </div>
      <?php } ?>
    </div>
  </div>
  <div class="col-12 contprotectioncont">
    <?php
    // echo '<pre>' .print_r($protected_ids,true).'</pre>'; 
    foreach ($pages as $key => $page) { ?>
      <div class="row">
        <div class="col-4">
          <p class="page-title-name0"><?php echo '<a target="_blank"  href="' . admin_url() . '/post.php?post=' . $page->ID . '&action=edit" ><i class="dashicons dashicons-edit"></i></a> <a  target="_blank"   class="page-title-name0" href="' . get_permalink($page->ID) . '">' . $page->post_title . '</a>'; ?>
          </p>
        </div>
        <?php foreach ($all_membership_levels as $levelid => $pgidarr) { ?>
          <div class="col">
            <p class="text-center">
              <?php //echo '<pre>'.$page->ID.print_r($getProtectedPages[$page->ID],true).'</pre>'; 
                  if (!is_array($protected_ids['page'][$levelid]))
                    $protected_ids['page'][$levelid] = array();
                  ?>
              <input type="checkbox" name="protected[page][<?php echo $levelid; ?>][<?php echo $page->ID; ?>]" <?php echo (in_array($page->ID, $protected_ids['page'][$levelid])) ? ' checked=checked ' : ''; ?> />
            </p>
          </div>
        <?php } ?>
      </div>
    <?php
    }
    ?>
  </div>

  <div class="col-12">
    <div class="row bg-dark p-2 text-white mb-1">
      <div class="col-4">
        <span> <?php _e('Posts', 'membership-site'); ?> </span>
      </div>
      <?php foreach ($all_membership_levels as $levelname) { ?>
        <div class="col  text-center">
          <span> <?php echo $levelname ?> </span>
        </div>
      <?php } ?>
    </div>
  </div>
  <div class="col-12 contprotectioncont">
    <?php
    // echo '<pre>' .print_r($protected_ids,true).'</pre>'; 
    foreach ($posts as $key => $post) { ?>
      <div class="row">
        <div class="col-4">
          <p class="page-title-name0"><?php echo '<a target="_blank"  href="' . admin_url() . '/post.php?post=' . $post->ID . '&action=edit" ><i class="dashicons dashicons-edit"></i></a> <a  target="_blank"   class="page-title-name0" href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a>'; ?>
          </p>
        </div>
        <?php foreach ($all_membership_levels as $levelid => $pgidarr) { ?>
          <div class="col">
            <p class="text-center">
              <?php //echo '<pre>'.$post->ID.print_r($getProtectedPages[$post->ID],true).'</pre>'; 
                  ?><input type="checkbox" name="protected[post][<?php echo $levelid; ?>][<?php echo $post->ID; ?>]" <?php echo (in_array($post->ID, $protected_ids['post'][$levelid])) ? ' checked=checked ' : ''; ?> />
            </p>
          </div>
        <?php } ?>
      </div>
    <?php
    }
    ?>
  </div>

  <div class="col-12 mt-2">
    <div class="row">
      <div class="col-10">
      </div>
      <div class="col-2">
        <input type="hidden" name="msliteaction" value="savecontentprotection" />
        <input type="submit" class="btn btn-md btn-ms w-100" id="memsubmitbut" name="submit" value="Save" />
      </div>
    </div>
  </div>
</form>