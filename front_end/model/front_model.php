<?php
require_once(MEMBERSONICLITE_PLUGIN_DIR . '/helper/mailer-helper.php');
class membership_site_front_model
{
    protected $current_user_id;
    function __construct()
    {
        global $current_user;
        get_currentuserinfo();
        $this->current_user_id = $current_user->ID;
    }
    function getMlevels_forUser($userid)
    {
        global $wpdb;
        if ($userid != '') {
            $sql       = "SELECT * FROM " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " as member INNER JOIN " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " as details ON details.id = member.wp_membership_id WHERE user_id =" . $userid . " AND member.is_active = 1";
            $getlevels = $wpdb->get_results($sql);
            return $getlevels;
        }
    }
    function check_member_exist($data)
    {
        global $wpdb;
        $sql    = "SELECT user_login FROM " . WP_MEMBERSONICLITE__USERS . " WHERE user_login ='" . $data['email'] . "'";
        $member = $wpdb->get_results($sql);
        return $member;
    }
    function check_member_email_exist($data)
    {
        global $wpdb;
        $sql    = "SELECT user_email FROM " . WP_MEMBERSONICLITE__USERS . " WHERE user_email ='" . $data['email'] . "'";
        $member = $wpdb->get_results($sql);
        return $member;
    }
    function getMemberShipId($member_level_key)
    {
        global $wpdb;
        $sql          = "SELECT * FROM " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " WHERE membership_level_key ='" . $member_level_key . "'";
        $member_level = $wpdb->get_results($sql);
        return $member_level;
    }
    function getMemberShipKey($member_level_id)
    {
        global $wpdb;
        $sql    = "SELECT * FROM " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " WHERE id ='" . $member_level_id . "'";
        $member = $wpdb->get_results($sql);
        return $member;
    }
    function member_exist_via_ipn($data)
    {
        global $wpdb;
        $sql    = "SELECT ID FROM " . WP_MEMBERSONICLITE__USERS . " WHERE user_email ='" . $data['email'] . "'";
        $member = $wpdb->get_results($sql);
        return $member;
    }
    function new_member_via_ipn($data)
    {
        global $wpdb;
        if (!empty($this->current_user_id)) {
            $createdby = $this->current_user_id;
        } else {
            $createdby = 0;
        }
        $password              = '';
        $membership_level_data = $this->getMemberShipId($data['member_level_key']);
        $check_exist = $this->member_exist_via_ipn($data);
        if (isset($membership_level_data)) {
            if (empty($check_exist)) {
                $user_id = wp_create_user($data['username'], $data['password'], $data['email']);
                update_user_meta($user_id, 'first_name', $data['first_name']);
                update_user_meta($user_id, 'last_name', $data['last_name']);
                update_user_meta($user_id, 'uniqueid', $data['uniqueid']);
                $password = $data['password'];
            } else {
                $user_id = $check_exist[0]->ID;
            }
            //	 wp_set_auth_cookie($user_id);
            $checkLevelexist = $this->CheckMemberLevelExistId($user_id, $membership_level_data[0]->id);
            // insert if not in tbale already
            if (empty($checkLevelexist)) {
                $profile_id = '';
                if (isset($data['profile_id'])) {
                    $profile_id = $data['profile_id'];
                }
                if (isset($data['sale_id'])) {
                    $profile_id = $data['sale_id'];
                }
                if (isset($data['ctransreceipt'])) {
                    $profile_id = $data['ctransreceipt'];
                }
                $insert_data = array(
                    'user_id' => $user_id,
                    'wp_membership_id' => $membership_level_data[0]->id,
                    'is_active' => 1,
                    'profile_id' => $profile_id,
                    'created_by' => $createdby
                );
                //	  $insert_data_type = array('%s');		
                $wpdb->insert(WP_MEMBERSONICLITE__MEMBER_ASSOC, $insert_data); //, $insert_data_type);//
            }
            // update only is_active if already present
            else {
                $update_data = array(
                    'is_active' => 1
                );
                $where = array(
                    'user_id' => $user_id,
                    'wp_membership_id' => $membership_level_data[0]->id
                );
                //	  $insert_data_type = array('%s');		
                $wpdb->update(WP_MEMBERSONICLITE__MEMBER_ASSOC, $update_data, $where);
            }
            $this->process_newuser_integration($user_id, $password, $membership_level_data, $data);
            $this->remove_member_level($user_id, $membership_level_data[0]->other_info);
           // create member external website
            $membership_level_data = $this->getMemberShipId($data['member_level_key']);
            $other_info = json_decode($membership_level_data[0]->other_info);
            //	  return $membership_level_data[0];
            $email =  sanitize_email($data['email']);
            $pass1 = $pass2 = sanitize_text_field($data['password']);
            $key = $membership_level_data[0]->membership_level_key;
            do_action('membersonic_add_user_levels', $user_id, $membership_level_data[0]->id);
            $return = array(
                'redirecturl' => $membership_level_data[0]->redirect_page,
                'aweberform' => $aweberFormResult,
                'check' => $membership_level_data[0]->subscribe_aweber,
            );
            return $return;
        }
    }
    function remove_member_level($userid, $mLevelsTBrem)
    {
        $otherinfo = json_decode($mLevelsTBrem, true);
        $remove_level_ids = explode("|", $otherinfo['remove_level']);
        foreach ($remove_level_ids as $key => $val) {
            $this->remove_user_membership($userid, $val);
        }
        return;
    }
    function remove_user_membership($user_id, $membership_id)
    {
        if ($user_id != '' && $membership_id != '') {
            global $wpdb;
            $sql    = "UPDATE  " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " SET  is_active =  0 WHERE  user_id = '" . $user_id . "'  AND wp_membership_id = '" . $membership_id . "'";
            $wpdb->query($sql);
            do_action('membersonic_remove_user_levels', $user_id, $membership_id);
        }
        return;
    }
    function process_newuser_integration($user_id, $password = '', $membership_level_data, $data)
    {
        $membersoniclite_mail = new membership_site_mailer();
        $generalsettings = get_option('wp_wso_general_settings');
        $other_info = json_decode($membership_level_data[0]->other_info);
        if (trim($password) != '')
            $membersoniclite_mail->sendMail($user_id, $password, '', $membership_level_data[0]->id);
        else
            $membersoniclite_mail->sendMailnewlevel($user_id, '', $membership_level_data[0]->id);
        return;
    }
    function cancel_User_Membership($pcodemkey, $email )
    {
        global $wpdb;
        $q1  = "SELECT id FROM " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " WHERE membership_level_key = '" . $pcodemkey . "'";
        $mid  = $wpdb->get_var($q1);
        $q2  = "SELECT ID FROM " . WP_MEMBERSONICLITE__USERS . " WHERE user_email = '" . $email  . "'";
        $uid = $wpdb->get_var($q2); 
        $sql    = "UPDATE  " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " SET  is_active =  0 WHERE  user_id = '" . $uid . "'  AND wp_membership_id = '" .  $mid . "'";
        $wpdb->query($sql); 
        
    }
    function CheckMemberLevelExistId($user_id, $membership_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " WHERE user_id = " . $user_id . " AND wp_membership_id=" . $membership_id;
        return $wpdb->get_results($sql);
    }
    function getPaypalButtonDetails($buttonKey)
    {
        global $wpdb;
        $sql           = "SELECT * FROM " . WP_MEMBERSONICLITE_MEMBERSHIP_PAYPAL . " WHERE button_key ='" . $buttonKey . "'";
        $buttonDetails = $wpdb->get_results($sql);
        return $buttonDetails;
    }
 
    function encryptPassword($password)
    {
        //   $key = 'password to (en/de)crypt';
        $encrypted            = md5($password);
        $_SESSION['password'] = $password;
        //base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $password, MCRYPT_MODE_CBC, md5(md5($key))));
        return $encrypted;
    }
    function generatePasswd($numAlpha = 6, $numNonAlpha = 2)
    {
        $listAlpha    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $listNonAlpha = ',;:!?.$/*-+&@_+;./*&?$-!,';
        return str_shuffle(substr(str_shuffle($listAlpha), 0, $numAlpha) . substr(str_shuffle($listNonAlpha), 0, $numNonAlpha));
    }
}
