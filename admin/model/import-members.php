<?php
class membership_site_import
{
	function import()
	{
        if (!wp_verify_nonce($_POST['_wpnonce'], $_POST['uniqid'])) {
            exit;
        }
	   global $wpdb;
       include_once ( MEMBERSONICLITE_PLUGIN_DIR . '/front_end/model/front_model.php');
       $membersoniclite_front_model = new membership_site_front_model();
       $general_settings = get_option('wp_wso_general_settings');
       $nouser = array();
	   if ($_FILES['import_members_txt']['error'] == UPLOAD_ERR_OK  && is_uploaded_file($_FILES['import_members_txt']['tmp_name']))
       {  
            $lines  = file($_FILES['import_members_txt']['tmp_name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 
            foreach($lines as $line)
            {
                $dat = explode(',',$line);
                if(trim($dat[0]) == '') continue;
                $email = sanitize_email(trim($dat[0]));
                $id = email_exists($email);
                if(trim($dat[1]) == '')
                    $dat[1] = $dat[0];
                $password = md5(uniqid().rand());
                $crnew = intval($_POST['createnew']);
                if(!$id && $crnew != "1")
                {
                    $nouser[] = $dat[0];
                    continue;
                }
                $data = array(
            				'username' => sanitize_text_field($dat[1]),
            				'first_name' => sanitize_text_field($dat[2]),
            				'last_name' => sanitize_text_field($dat[3]),
            				'email' => $email,
            				'password' => $password,
            				'member_level_key' => sanitize_text_field($_POST['mlevelkey']),
                            'profile_id' => 'import',
            				'no_multi_emails' => 0);
           
                $ret = $membersoniclite_front_model->new_member_via_ipn($data);        
            }
        }
        if(!empty($nouser))
        {
            $data = implode("\r\n",$nouser);
            
            return 'nouser';
        }
        return 'ok';
    }
 }
