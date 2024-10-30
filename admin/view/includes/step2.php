<form method="post">

    <p class="alert alert-info mt-2"> <?php _e('Check each page or post that you want to protect.  Any page or post with no boxes checked will be unprotected.', 'membership-site'); ?>
    </p>
    <div class="col-12">
        <div class="row bg-dark p-2 text-white mb-1">
            <div class="col-6">
                <span> <?php _e('Pages', 'membership-site'); ?> </span>
            </div>
            <div class="col-2  text-center">
                <span> <?php _e('Protected?', 'membership-site'); ?> </span>
            </div>
        </div>
    </div>
    <div class="col-12 contprotectioncont">
        <?php
        if (!is_array($pages))
    $pages = array();
    if (!is_array($protected_ids))
    $protected_ids = array();
        foreach ($pages as $key => $page) { ?>
            <div class="row">
                <div class="col-6">
                    <p class="page-title-name0"><?php echo '<a target="_blank"  href="' . admin_url() . 'post.php?post=' . $page->ID . '&action=edit" ><i class="dashicons dashicons-edit text-info"></i></a> <a  target="_blank"   class="page-title-name0 text-info" href="' . get_permalink($page->ID) . '">' . $page->post_title . '</a>
                        '; ?>
                    </p>
                </div>
                <div class="col-2">
                    <p class="text-center">
                        <?php //echo '<pre>'.$page->ID.print_r($getProtectedPages[$page->ID],true).'</pre>'; 
                            ?><input type="checkbox" name="pages_name[<?php echo $key; ?>]" <?php echo (in_array($page->ID, $protected_ids)) ? 'checked=checked' : ''; ?> />
                        <input type="hidden" name="pages_id[]" value="<?php echo $page->ID; ?>">
                    </p>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    <div class="col-12">
        <div class="row bg-dark p-2 text-white mb-1">
            <div class="col-6">
                <span> <?php _e('Posts', 'membership-site'); ?> </span>
            </div>
            <div class="col-2  text-center ">
                <span> <?php _e('Protected?', 'membership-site'); ?> </span>
            </div>
        </div>
    </div>
    <div class="col-12 contprotectioncont">
        <?php
        if (!is_array($posts))
    $posts = array();
    
        foreach ($posts as $post_key => $post) {
            ?>
            <div class="row">
                <div class="col-6">
                    <p class="post-title-name0"><?php echo '<a  target="_blank"  href="' . admin_url() . 'post.php?post=' . $post->ID . '&action=edit" ><i class="dashicons dashicons-edit text-info"></i></a><a  target="_blank"   class="page-title-name0 text-info" href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a>'; ?></p>
                </div>
                <div class="col-2">
                    <p class="text-center">
                        <input type="checkbox" name="posts_name[<?php echo $post_key; ?>]" <?php echo (in_array($post->ID, $protected_ids)) ? 'checked=checked' : ''; ?>>
                        <input type="hidden" name="posts_id[]" value="<?php echo $post->ID; ?>">
                    </p>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <?php
    $otherinfo        = json_decode($membership_level['other_info'], true);
    $remove_level_ids = explode("|", $otherinfo['remove_level']);
    ?>
    <div class="col-12 d-none">
        <h5><?php _e('Auto Level Removal', 'membership-site'); ?></h5>
        <strong><?php _e('Remove from Levels (When someone joins this level)', 'membership-site'); ?></strong>
    </div>
    <div class="col-12 d-none">
        <div class="row  bg-dark p-2 text-white mb-1">
            <div class="col-6">
                <?php _e('Membership Level', 'membership-site'); ?>
            </div>
            <div class="col-2 text-center">

                <?php _e('Remove', 'membership-site'); ?>
            </div>
        </div>
    </div>
    <div class="col-12  contprotectioncont  d-none">
        <?php
        foreach ($all_membership_levels as $key => $obj) {
            if ($membership_level['id'] == $obj->id)
                continue;
            ?>
            <div class="row">
                <div class="col-6">
                    <p class="text-info"><?php echo '<a  target="_blank"   class="page-title-name0" href="' . admin_url('admin.php?page=membersoniclite_action=editlevel&id=' . $obj->id) . '" title="Edit Member" >' . $obj->membership_level_name . '</a>';  ?></p>
                </div>
                <div class="col-2">
                    <p class="text-center"><input type="checkbox" name="remove_level[<?php echo $obj->id; ?>]" <?php if (in_array($obj->id, $remove_level_ids)) echo ' checked=checked'; ?> /></p>
                </div>

            </div>
        <?php
        }
        ?>
    </div>
    <div class="col-12 mt-2">
        <div class="row">
            <div class="col-9">
            </div>
            <div class="col-2">
                <?php
                $uniqid = uniqid('msstep2');
                wp_nonce_field($uniqid); ?>
                <input type="hidden" id="uniqid" name="uniqid" value="<?php echo $uniqid; ?>" />
                <input type="hidden" name="membership_level_id" value="<?php echo $membership_level['id']; ?>" />
                <input type="hidden" name="mslitetab" value="step2" />
                <input type="hidden" name="msliteaction" value="savestep2" />
                <input type="submit" class="btn btn-md btn-ms w-100" id="memsubmitbut" name="submit" value="Save" />
            </div>
        </div>
    </div>
</form>