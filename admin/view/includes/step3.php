 <?php
    $default_email_body  = __("Dear [firstname], \n  \n You have successfully registered as one of our [memberlevel] members. \n  \n Please keep this information safe as it contains your username and password.   \n  \n Your Membership Info:  \n  \n U: [username]  \n  \n P: [password]  \n  \n Login URL: [loginurl]  \n  \n Regards,", 'membership-site');
    $default_email_title = __("Here is Your [memberlevel] Login Information.", 'membership-site');
    if (trim($membership_level['email_body'] == ''))
        $email_body = stripslashes($default_email_body);
    else
        $email_body = stripslashes($membership_level['email_body']);
    if (trim($membership_level['email_title'] == ''))
        $email_title = $default_email_title;
    else
        $email_title = $membership_level['email_title']
        ?>
 <form method="post">
     <div class="col-12">
         <div class="row mt-2">
             <div class="col-2"><?php _e('Subject Title', 'membership-site'); ?>
             </div>
             <div class="col-6">
                 <input type="text" name="email_title" class="form-control" value="<?php echo $email_title;  ?>" />
             </div>
         </div>

         <div class="row mt-2">
             <div class="col-2"><?php _e('Message', 'membership-site'); ?></div>
             <div class="col-6">
                 <textarea name="email_body" class="form-control" rows="10" cols="80"><?php echo $email_body; ?></textarea>
             </div>
         </div>

         <div class="row mt-2">
             <div class="col-6"></div>
             <div class="col-2">
                 <?php
                    $uniqid = uniqid('msstep2');
                    wp_nonce_field($uniqid); ?>
                 <input type="hidden" id="uniqid" name="uniqid" value="<?php echo $uniqid; ?>" />
                 <input type="hidden" name="membership_level_id" value="<?php echo $membership_level['id']; ?>" />
                 <input type="hidden" name="mslitetab" value="step3" />
                 <input type="hidden" name="msliteaction" value="savestep3" />
                 <input type="submit" class="btn btn-md btn-ms w-100" id="memsubmitbut" name="submit" value="Save" />
             </div>
         </div>
     </div>
 </form>