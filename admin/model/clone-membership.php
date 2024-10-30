<?php

/**
 * 
 * Clone membership level
 */
class membership_site_clone_membership_level
{
      /**
       * @construct
       */
      function __construct()
      {
            global $current_user;
            get_currentuserinfo();
            $this->user_id = $current_user->ID;
      }
      /**
       * 
       * Saves  clone
       * 
       * @return void redirect
       */
      function clone()
      {
            global $wpdb;
            $levelname = sanitize_text_field($_GET['levelname']) . ' copy';
            $levelid = intval($_GET['id']);
            $membership_level_key = uniqid('wso_');
            $sql = "INSERT INTO " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . "
          ( membership_level_name, redirect_page, membership_level_key, clickbank_product_id, jvzoo_product_id, WSOPRO_prod_code, spbas_product_id, spbas_tier_id, subscribe_aweber, email_title, email_body, integrate_to_spbas, integrate_to_clickbank, integrate_to_jvzoo, integrate_to_WSOPRO, webinar_enable, webinar_serverno, webinarkey1, webinarkey2, webinarkey3, webinarkey4, webinarkey5, webinarkey6, arweb_code, arname_fields, aremail_fields, arpost_url, arhidden_fields, created_by, created_date, other_info
          )
     SELECT '$levelname', redirect_page, '$membership_level_key', clickbank_product_id, jvzoo_product_id, WSOPRO_prod_code, spbas_product_id, spbas_tier_id, subscribe_aweber, email_title, email_body, integrate_to_spbas, integrate_to_clickbank, integrate_to_jvzoo, integrate_to_WSOPRO, webinar_enable, webinar_serverno, webinarkey1, webinarkey2, webinarkey3, webinarkey4, webinarkey5, webinarkey6, arweb_code, arname_fields, aremail_fields, arpost_url, arhidden_fields, created_by, created_date, other_info
      FROM " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " WHERE id = $levelid";
            $wpdb->query($sql);
            $newid = $wpdb->insert_id;
            $date = date("Y-m-d H:i:s");
            $sql = "INSERT INTO " . WP_MEMBERSONICLITE_CONTENT_PROTECTION . "
          ( type_name, type_id, wp_membership_id , is_protected, drip_feeds, created_by, created_date, drip_date
          )
     SELECT type_name, type_id, '$newid' , is_protected, drip_feeds, created_by, '$date', drip_date
      FROM " . WP_MEMBERSONICLITE_CONTENT_PROTECTION . " WHERE wp_membership_id = $levelid";
            $wpdb->query($sql);
 
            $url = admin_url('admin.php?page=' . 'membership-site' . '-dashboard');

            wp_redirect($url);
            exit;
            
      }
}
