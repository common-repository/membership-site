 <?php
  include_once(MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/metabox-class.php');
  $membersoniclite_metabox = new membership_site_metabox();
  //$membersoniclite_metabox-> metabox_scripts();
  /* print_r($memlevels);
  print_r($protectcontent); */
  ?>
 <div class="qazert">
   <p>
     Generate Shortcode: <?php echo "&nbsp;&nbsp;&nbsp;" . '<a   href="#" class="shortcodepopbut btn btn-md btn-default" data-toggle="modal" data-target="#mslitemetaModal" ><img src="' . MEMBERSONICLITE_PLUGIN_URL . '/assets/images/popbutton.png" id="msshortcode_but"/></a>'; ?>
   </p>
   <table class="metabox_table table table-condensed table-responsive">
     <tr>
       <th class="memlevname">Memberships</th>
       <th class="text-right"><?php _e('Protect', 'membership-site') ?>
         <input type="hidden" name="edit_post_screen" value="true" />
         <input type="checkbox" id="product_assoc_SA" value="" /></th>
     </tr>
     <?php

      foreach ($memlevels as $memlevel) {   ?>
       <tr>
         <td class="memlevname">
           <?php
              echo $memlevel->membership_level_name;
              $keydata =   $membersoniclite_metabox->ms_search_object($memlevel->id, $protectcontent);
              ?>
         </td>
         <td class="text-right"><input type="checkbox" name="product_assoc[<?php echo $memlevel->id; ?>]" value="1" <?php if ($keydata->wp_membership_id == $memlevel->id) echo  ' checked=checked'  ?> class="ms_post_meta_checkbox" /></td>
       </tr>
     <?php } ?>
   </table>

 </div>