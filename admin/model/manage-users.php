<?php
class membership_site_manage_users
{
    function __construct()
    { }
    function search_member($search_params)
    {
        $filter = $search_params;
        if ($filter['search_user_details'] != NULL)
            return $filter['search_user_details'];
        if ($filter['filter_by_mlevel'] != NULL)
            return $filter['filter_by_mlevel'];
    }
    function list_all_member($keyword, $data, $pages)
    {
        global $wpdb;
        $sql = "Select * FROM " . WP_MEMBERSONICLITE__USERS . " AS user
                INNER JOIN (select user_id, meta_value AS first_name from " . WP_MEMBERSONICLITE__USERMETA . " WHERE meta_key='first_name')
                AS wp_usermeta_firstname
                INNER JOIN (select user_id, meta_value AS last_name from " . WP_MEMBERSONICLITE__USERMETA . " WHERE meta_key='last_name')
                AS wp_usermeta_lastname
                ON user.ID = wp_usermeta_firstname.user_id AND user.ID = wp_usermeta_lastname.user_id";
        if ($data['search_user_details'] != '') {
            $sql .= " WHERE user_login='$keyword' OR first_name ='$keyword' OR last_name ='$keyword' OR user_email ='$keyword'";
        } else if ($data['filter_by_mlevel'] != '') {
            $sql .= " WHERE user.ID IN (SELECT user_id FROM " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " as member INNER JOIN " .               WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " as details ON details.id = member.wp_membership_id WHERE                    member.wp_membership_id =" . $keyword . " AND member.is_active = 1 ) ";
        }
        $sql .= " GROUP BY user.user_email ORDER BY user.user_registered DESC " . $pages->limit;
        // print_r($pages); //die();
        return $wpdb->get_results($sql);
    }
    function total_members($keyword, $data)
    {
        global $wpdb;
        $countsql = "Select ID FROM " . WP_MEMBERSONICLITE__USERS . " AS user
                    INNER JOIN (select user_id, meta_value AS first_name from " . WP_MEMBERSONICLITE__USERMETA . " WHERE meta_key='first_name')
                    AS wp_usermeta_firstname
                    INNER JOIN (select user_id, meta_value AS last_name from " . WP_MEMBERSONICLITE__USERMETA . " WHERE meta_key='last_name')
                    AS wp_usermeta_lastname
                    ON user.ID = wp_usermeta_firstname.user_id AND user.ID = wp_usermeta_lastname.user_id";
        if ($data['search_user_details'] != '') {
            $countsql .= " WHERE user_login='$keyword' OR first_name ='$keyword' OR last_name ='$keyword' OR user_email ='$keyword'";
        } else if ($data['filter_by_mlevel'] != '') {
            $countsql .= " WHERE user.ID IN (SELECT user_id FROM " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " as member INNER JOIN "                . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " as details ON details.id = member.wp_membership_id WHERE                    member.wp_membership_id =" . $keyword . " AND member.is_active = 1) ";
        }
        $countsql .= " GROUP BY user.user_email";
        return count($wpdb->get_results($countsql));
    }
    function update_user_membershiphtml()
    {
        global $wpdb;
        $sql              = "SELECT id,membership_level_name  FROM " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS;
        $all_membership_levels = $wpdb->get_results($sql);
        $userid = intval($_POST['userid']);
        $sql              = "SELECT wp_membership_id FROM " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " 
                            WHERE user_id = " . $userid . " 
                            AND is_active = 1";
        $userslevels = $wpdb->get_col($sql);

        $return = '<div class="col-12">';
        foreach ($all_membership_levels as $mlevels) {
            $checked = (in_array($mlevels->id, $userslevels)) ? ' checked ' : '';
            $return .= '<div class="row pt-1 pb-1 border-bottom">';

            $return .= '<div class="col-10">';
            $return .= $mlevels->membership_level_name;
            $return .= '</div>';
            $return .= '<div class="col-2">';
            $return .= '<input type="checkbox" ' . $checked . ' class="" name="mlevel" value="' . $mlevels->id . '" />';
            $return .= '</div>';

            $return .= '</div>';
        }
        $return .= '</div>';

        echo $return;
        exit;
    }
    function update_user_membershipsave($freemember='')
    {
        global $wpdb;
        $date = date_i18n('Y-m-d H:i:s', current_time('timestamp'));
        $userid = intval($_POST['userid']);
        /// added through admin
        if($freemember == ''){
            $update = array('is_active' => "0");
            $where = array('user_id' => $userid);
            $wpdb->update(WP_MEMBERSONICLITE__MEMBER_ASSOC, $update, $where);
        }

        $memberlevels = isset($_POST['memberlevels']) ? (array) $_POST['memberlevels'] : array();
        $memberlevels = array_map('intval', $memberlevels);

        foreach ($memberlevels as $mlevelid) {
            $mlevelid = intval($mlevelid);
            $update = array('is_active' => '1');
            $where = array(
                'user_id' => $userid,
                'wp_membership_id' => $mlevelid
            );
            $res = $wpdb->update(WP_MEMBERSONICLITE__MEMBER_ASSOC, $update, $where);
            if (!$res) {
                $insert = array(
                    'is_active' => '1',
                    'user_id' => $userid,
                    'wp_membership_id' => $mlevelid,
                    'created_date' => $date
                );
                $res = $wpdb->insert(WP_MEMBERSONICLITE__MEMBER_ASSOC, $insert);
            }
        }
        require_once(MEMBERSONICLITE_PLUGIN_DIR . '/helper/mailer-helper.php');
        $membersoniclite_mail = new membership_site_mailer();
        $membersoniclite_mail->sendMailnewlevel($userid, '', $mlevelid);
        if($freemember == '') {
            echo json_encode(array('status' => 'success', 'message' => __('Saved', 'membership-site')));
            exit;
        }
        
    }
    function get_all_membership_levels()
    {
        global $wpdb;
        $sql              = "SELECT id,membership_level_name,membership_level_key FROM " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS;
        $all_membership_levels = $wpdb->get_results($sql);
        return $all_membership_levels;
    }
    function save_New_User()
    {
        global $wpdb;
        $email = sanitize_email($_POST['email']);

        $sql = "SELECT ID FROM " . WP_MEMBERSONICLITE__USERS . " WHERE user_email ='" . $email . "'";
        $id = $wpdb->get_var($sql);

        if($id != ''){
            $return  = array('status' => 'error', 'message' => __('Email id Already Registered. Use a different Email Id to create a new account OR login above to activate the memebrship.', 'membership-site'));
            echo json_encode($return);
            exit;
        }

        $date = date_i18n('Y-m-d H:i:s', current_time('timestamp'));
        $firstname = sanitize_text_field($_POST['firstname']);
        $lastname =  sanitize_text_field($_POST['lastname']);
        $name = $firstname.' '.$lastname;
        $password = sanitize_text_field($_POST['password']);
        $mlevelid = intval($_POST['membershipid']);
        $userdata = array(
            'user_pass'             => $password,
            'user_login'            => $email,
            'user_nicename'         => $name,
            'user_email'            => $email,
            'display_name'          => $name,
            'nickname'              => $name,
            'first_name'            => $firstname,
            'last_name'             => $lastname
        );

        $userid = wp_insert_user($userdata);
        $insert = array(
            'is_active' => '1',
            'user_id' => $userid,
            'wp_membership_id' => $mlevelid,
            'created_date' => $date
        );
        $wpdb->insert(WP_MEMBERSONICLITE__MEMBER_ASSOC, $insert);
        require_once(MEMBERSONICLITE_PLUGIN_DIR . '/helper/mailer-helper.php');
        $membersoniclite_mail = new membership_site_mailer();
        $membersoniclite_mail->sendMail($userid, $password, '', $mlevelid);
        $return  = array('status' => 'success', 'message' => __('Registered Successfully. Email Sent.', 'membership-site'));
        echo json_encode ($return);
        exit;
    }
}
