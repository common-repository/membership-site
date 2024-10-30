<?php

class membership_site_add_edit_membership_level
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
     * Saves Step 1 of Membership Level
     *
     * @param string $data array
     * @return void redirect
     */
    function savestep1()
    {
        if (!wp_verify_nonce($_POST['_wpnonce'], $_POST['uniqid'])) {
            exit('nonce');
        }
        global $wpdb;
        $id = intval($_POST['membership_level_id']);
        $other_info['paid_memlevel'] = sanitize_text_field($_POST['paid_memlevel']);
        $other_info['after_login_memlevel'] = sanitize_text_field($_POST['after_login_memlevel']);
        $other_info = json_encode($other_info);

        $data['redirect_page'] = (trim($_POST['redirect_pagesel']) == '') ? esc_url_raw($_POST['redirect_page']): esc_url_raw($_POST['redirect_pagesel']);

        if ($id == 0) {
            $membership_level_key = (sanitize_text_field($_POST['membership_level_key']) == '') ? uniqid('wso_') : '';
            $insert_member    = array(
                'membership_level_key' => $membership_level_key,
                'membership_level_name' => sanitize_text_field($_POST['membership_level_name']),
                'redirect_page' => $data['redirect_page'],
                'other_info' => $other_info,
            );
            $insert_data_type = array(
                '%s',
                '%s',
                '%s',
                '%s'
            );
            $wpdb->insert(WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS, $insert_member, $insert_data_type);
            $lastid = $wpdb->insert_id;
        } else {
            $redirect_page = (trim($_POST['redirect_pagesel']) == '') ? esc_url_raw($_POST['redirect_page']) : esc_url_raw($_POST['redirect_pagesel']);
            $update_memberlevel    = array(
                'membership_level_name' => sanitize_text_field($_POST['membership_level_name']),
                'redirect_page' => $redirect_page,
                'other_info' => $other_info,
            );
            $update_data_type = array(
                '%s',
                '%s',
                '%s'
            );
            $update_where = array(
                'id' => intval($_POST['membership_level_id'])
            );
            $update_where_type = array(
                '%d'
            );
            $wpdb->update(WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS, $update_memberlevel, $update_where, $update_data_type, $update_where_type);
            $lastid = intval($_POST['membership_level_id']);
        }

        $url = admin_url('admin.php?page=' . 'membership-site' . '-add-edit-membership-level&membersoniclite_action=editlevel&tb=' . sanitize_text_field($_POST['mslitetab']) . '&id=' . $lastid);

        wp_redirect($url);
        exit;
    }
    /**
     * 
     * Saves Step 2 of Membership Level
     *
     * @param string $data array
     * @return void redirect
     */
    function savestep2()
    {
        if (!wp_verify_nonce($_POST['_wpnonce'], $_POST['uniqid'])) {
            exit;
        }
        global $wpdb;

        $id = intval($_POST['membership_level_id']);
        $wpdb->delete(WP_MEMBERSONICLITE_CONTENT_PROTECTION, array('wp_membership_id' => $id));

        $page_id_arr = isset($_POST['pages_id']) ? (array) $_POST['pages_id'] : array();
        $page_id_arr = array_map('intval', $page_id_arr);

        $post_id_arr = isset($_POST['posts_id']) ? (array) $_POST['posts_id'] : array();
        $post_id_arr = array_map('intval', $post_id_arr);

        $page_name_arr = isset($_POST['pages_name']) ? (array) $_POST['pages_name'] : array();
        $page_name_arr = array_map('sanitize_text_field', $page_name_arr);

        $post_name_arr = isset($_POST['posts_name']) ? (array) $_POST['posts_name'] : array();
        $post_name_arr = array_map('sanitize_text_field', $post_name_arr);
 
        if (!empty($page_id_arr)) {
            $type_name = "page";
            foreach ($page_id_arr as $key => $value) {
                $insert_content   = array(
                    'type_name' => sanitize_text_field($type_name),
                    'type_id' => intval($value),
                    'is_protected' => 1,
                    'drip_feeds' => '',
                    'wp_membership_id' => intval($id),
                    'created_by' => intval($this->user_id),
                    'created_date' => date_i18n('Y-m-d H:i:s', current_time('timestamp')),
                    'drip_date' => ''
                );
                $insert_data_type = array(
                    '%s'
                );
                if (stripslashes($page_name_arr[$key]) == "on")
                    $wpdb->insert(WP_MEMBERSONICLITE_CONTENT_PROTECTION, $insert_content, $insert_data_type);
            }
        }
        if (!empty($post_id_arr)) {
            $type_name = "post";
            foreach ($post_id_arr as $key => $value) {
                $insert_content   = array(
                    'type_name' => sanitize_text_field($type_name),
                    'type_id' => intval($value),
                    'is_protected' => 1,
                    'drip_feeds' => '',
                    'wp_membership_id' => intval($id),
                    'created_by' => intval($this->user_id),
                    'created_date' => date('Y-m-d H:i:s'),
                    'drip_date' => ''
                );
                $insert_data_type = array(
                    '%s'
                );
                if (sanitize_text_field($post_name_arr[$key]) == "on")
                    $wpdb->insert(WP_MEMBERSONICLITE_CONTENT_PROTECTION, $insert_content, $insert_data_type);
            }
        }
        $url = admin_url('admin.php?page=' . 'membership-site' . '-add-edit-membership-level&membersoniclite_action=editlevel&tb=' . sanitize_text_field($_POST['mslitetab']) . '&id=' . $id);
        wp_redirect($url);
        exit;
    }
    /**
     * 
     * Saves Step 3 of Membership Level
     *
     * @param string $data array
     * @return void redirect
     */
    function savestep3()
    {
        if (!wp_verify_nonce($_POST['_wpnonce'], $_POST['uniqid'])) {
            exit;
        }
        global $wpdb;
        $default_email_body  = __("Dear [firstname], \n  \n You have successfully registered as one of our [memberlevel] members. \n  \n Please keep this information safe as it contains your username and password.   \n  \n Your Membership Info:  \n  \n U: [username]  \n  \n P: [password]  \n  \n Login URL: [loginurl]  \n  \n Regards,", 'membership-site');

        $default_email_title = __("Here is Your [memberlevel] Login Information.", 'membership-site');

        $email_title = (trim($_POST['email_title']) == '') ? sanitize_text_field($default_email_title) : sanitize_text_field($_POST['email_title']);

        $email_body = (trim($_POST['email_body']) == '') ? sanitize_text_field($default_email_body) : sanitize_text_field($_POST['email_body']);

        $update_memberlevel    = array(
            'email_title' => sanitize_text_field($email_title),
            'email_body' => sanitize_textarea_field($email_body)
        );
        $update_data_type = array(
            '%s',
            '%s'
        );
        $update_where = array(
            'id' => intval($_POST['membership_level_id'])
        );
        $update_where_type = array(
            '%s'
        );
        $wpdb->update(WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS, $update_memberlevel, $update_where, $update_data_type, $update_where_type);
        $lastid = intval($_POST['membership_level_id']);

        $url = admin_url('admin.php?page=' . 'membership-site' . '-add-edit-membership-level&membersoniclite_action=editlevel&tb=' . sanitize_text_field($_POST['mslitetab']) . '&id=' . $lastid);
        wp_redirect($url);
        exit;
    }
    /**
     * 
     * get_membership_level
     *
     * @param string $data array
     * @return array membership level data
     */
    function get_membership_level($id)
    {
        global $wpdb;
        $id = intval($id);
        $sql              = "SELECT * FROM " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " WHERE id ='" . $id . "'";
        $membership_level = $wpdb->get_row($sql, ARRAY_A);
        return $membership_level;
    }
    /**
     * 
     * get_protected_ids
     *
     * @param int id
     * @return array protected ids
     */
    function get_protected_ids($id)
    {
        global $wpdb;
        $id = intval($id);
        $protected = array();
        $sql    =   "SELECT type_id
                    FROM " . WP_MEMBERSONICLITE_CONTENT_PROTECTION . " 
                    WHERE wp_membership_id  ='" . $id . "' AND is_protected = '1'";
        $type_ids = $wpdb->get_col($sql);
        foreach ($type_ids as $tids) {
            $protected[] = $tids;
        }
        return $protected;
    }
    /**
     * 
     * get_all_membership_levels
     *
     * @param null
     * @return object all memebrship level data
     */
    function get_all_membership_levels()
    {
        global $wpdb;
        $sql              = "SELECT id,membership_level_name,membership_level_key FROM " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS;
        $all_membership_levels = $wpdb->get_results($sql);
        return $all_membership_levels;
    }

    /********************DASHBOARD******** */
    /**
     * 
     * get_membership_member_count
     *
     * @param int membership level id
     * @return int total memebrs of that level
     */
    function get_membership_member_count($levelid)
    {
        global $wpdb;
        $sql = "SELECT count(*)  
			FROM " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " AS sma
			INNER JOIN " . WP_MEMBERSONICLITE__USERS . " AS wsu ON sma.user_id = wsu.ID  
            WHERE  sma.is_active = 1 
            AND sma.wp_membership_id = '" . $levelid . "'
			GROUP BY wp_membership_id";
        return $wpdb->get_var($sql);
    }
    /**
     * 
     * get_membership_member_count_currentdate
     *
     * @param int membership level id
     * @return int total memebrs of that level today
     */
    function get_membership_member_count_currentdate($levelid)
    {
        global $wpdb;
        $sql = "SELECT count( 1 )  
			FROM " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " AS sma
			INNER JOIN " . WP_MEMBERSONICLITE__USERS . " AS wsu ON sma.user_id = wsu.ID 
			WHERE sma.created_date >= CURDATE()   
			AND  sma.is_active = 1 
            AND sma.wp_membership_id = '" . $levelid . "'
			GROUP BY sma.wp_membership_id";
        return $wpdb->get_var($sql);
    }
    /**
     * 
     * get_membership_member_count_currentdate
     *
     * @param int membership level id
     * @return void
     */
    function delete_membership_level()
    {
        global $wpdb;
        $id = intval($_GET['id']);
        $wpdb->delete(WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS, array('id' => $id));
        $url = admin_url('admin.php?page=' . 'membership-site' . '-dashboard');

        wp_redirect($url);
        exit;
    }
}
