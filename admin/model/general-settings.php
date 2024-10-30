<?php

/**
 * 
 * general settings class
 *
 */
class membership_site_general_settings
{
    function __construct()
    {
        global $current_user;
        get_currentuserinfo();
        $this->user_id = $current_user->ID;
    }
    function save_settings()
    {
        $gensettings = array(
            'login_page' => esc_url_raw($_POST['login_page']),
            'paypal_members_area' => esc_url_raw($_POST['paypal_members_area']),
            'paypal_access_denied' => esc_url_raw($_POST['paypal_access_denied']),
            'content_not_yet_available' => esc_url_raw($_POST['content_not_yet_available']),
            'hide_protected_content_links' => sanitize_text_field($_POST['hide_protected_content_links']),
            'admin_email_address' => sanitize_text_field($_POST['admin_email_address']),
            'admin_display_name' => sanitize_text_field($_POST['admin_display_name']),
            'admin_signup_notifications' => sanitize_text_field($_POST['admin_signup_notifications']),
        );
        update_option('wp_wso_general_settings', $gensettings);
        $serverurl = $_SERVER['HTTP_HOST'];
        update_option('wp_wso_server_url', $serverurl);
        $this->message = '<div class="success_msg">
    <p style="color:#009900;">' . __('Successfully General Settings Inserted', 'membership-site') . ' </p>
</div>';

        return;
    }
    function get_user_mail_info($user_id, $membershipLevel)
    {
        global $wpdb;
        $sql = " SELECT * FROM " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " AS sma
		 INNER JOIN " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " AS smd ON sma.wp_membership_id = smd.id 
		 WHERE sma.user_id =" . $user_id . " AND sma.wp_membership_id = " . $membershipLevel . " AND sma.is_active = 1";
        return $wpdb->get_results($sql);
    }
}
