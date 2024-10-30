<?php
require_once MEMBERSONICLITE_PLUGIN_DIR . "/admin/model/general-settings.php";
if (!class_exists('membersoniclite_mailer')) {
	class membership_site_mailer
	{
		function __construct()
		{
			add_filter('wp_mail_from_name', array(&$this, 'ms_from_name'));
		}
		function ms_from_name()
		{
			$general_settings = get_option('wp_wso_general_settings');
			if ($general_settings['admin_email_address'] == '')
				$from = get_option('blogname');
			else
				$from = $general_settings['admin_display_name'];
			return $from;
		}
		/// front end add new member - free form, paypal, jvzoo etc.
		function sendMail($user_id, $password, $from = '', $membershipLevel_id, $resend = false)
		{
			global $current_user;
			$general_settings = get_option('wp_wso_general_settings');
			$membersoniclite_general_settings = new membership_site_general_settings();
			$user_data = $membersoniclite_general_settings->get_user_mail_info($user_id, $membershipLevel_id);
			$otherinfo = json_decode($user_data[0]->other_info);
			if ($otherinfo->blockwelcomeemails == '1')
				return;
			$user_info = get_userdata($user_id);
			$to = trim($user_info->user_email);
			if ($resend) {
				$subject = str_replace('[memberlevel]', '', $user_data[0]->email_title);
				$replace_params = array(
					'[firstname]' => $user_info->user_firstname,
					'[username]' => $user_info->user_login,
					'[password]' => $password,
					'[loginurl]' => $general_settings['login_page']
				);
				$email_body = str_replace('[memberlevel]', '', stripslashes($this->generate_mail_template($replace_params, $user_data[0]->email_body)));
				$txt = array(
					$user_id,
					$membershipLevel_id,
					$user_data,
					$subject,
					$email_body
				);
			} else {
				$replace_subject = array('[memberlevel]' => $user_data[0]->membership_level_name);
				$subject = $this->generate_mail_template_subject($replace_subject, $user_data[0]->email_title);
				$replace_params = array(
					'[firstname]' => $user_info->user_firstname,
					'[memberlevel]' => $user_data[0]->membership_level_name,
					'[username]' => $user_info->user_login,
					'[password]' => $password,
					'[loginurl]' => $general_settings['login_page']
				);
				$email_body = stripslashes($this->generate_mail_template($replace_params, $user_data[0]->email_body));
			}
			$from_email = $this->get_fromEmail_info($general_settings);

			$this->admin_autoresponder($user_id, $membershipLevel_id, home_url());
			$headers  = 'From: ' . $from_email[0] . ' <' . $from_email[1] . '>' . "\r\n" .
				'Reply-To: ' . $from_email[1] . "\r\n" . 'X-Mailer: PHP/' . phpversion();
			add_filter('wp_mail_content_type', array(&$this, 'set_content_type'));
			$sent = wp_mail($to, $subject, nl2br($email_body), $headers);
			remove_filter('wp_mail_content_type', array(&$this, 'set_content_type'));
			return;
		}
		///admin add new memebers
		function sendMailnewlevel($user_id, $from = '', $membershipLevel_id)
		{
			$general_settings = get_option('wp_wso_general_settings');
			$membersoniclite_general_settings = new membership_site_general_settings();
			$user_data = $membersoniclite_general_settings->get_user_mail_info($user_id, $membershipLevel_id);
			$otherinfo = json_decode($user_data[0]->other_info);

			$user_info = get_userdata($user_id);
			$to = trim($user_info->user_email);
			if ($otherinfo->blockwelcomeemails == '1')
				return;
			if (trim($otherinfo->email_title2) == '')
				$otherinfo->email_title2 = 'Your [memberlevel] is activated!';
			if (trim($otherinfo->email_body2) == '')
				$otherinfo->email_body2 = "Dear [firstname], \n
                Your [memberlevel] membership is successfully activated.  \n
                You can use the present login info to access the membership.   \n
                Regards, ";
			$subject = str_replace('[memberlevel]', $user_data[0]->membership_level_name, $otherinfo->email_title2);
			$replace_params = array(
				'[firstname]' => $user_info->user_firstname,
				'[memberlevel]' => $user_data[0]->membership_level_name,
			);
			$email_body = stripslashes($this->generate_mail_template($replace_params, $otherinfo->email_body2));
			//		$subject         = 'Your membership is activated!';
			$from_email = $this->get_fromEmail_info($general_settings);
			$this->admin_autoresponder($user_id, $membershipLevel_id, home_url());
			$headers  = 'From: ' . $from_email[0] . ' <' . $from_email[1] . '>' . "\r\n" .
				'Reply-To: ' . $from_email[1] . "\r\n" . 'X-Mailer: PHP/' . phpversion();
			add_filter('wp_mail_content_type', array(&$this, 'set_content_type'));
			$sent = wp_mail($to, $subject, nl2br($email_body), $headers);
			remove_filter('wp_mail_content_type', array(&$this, 'set_content_type'));
		}
		function admin_autoresponder($user_id, $membershipLevel_id, $hostname)
		{
			global $current_user;
			get_currentuserinfo();
			$logged_in_userid = $current_user->ID;
			$membersoniclite_general_settings = new membership_site_general_settings();
			$user_data = $membersoniclite_general_settings->get_user_mail_info($user_id, $membershipLevel_id);
			$general_settings = get_option('wp_wso_general_settings');
			$user_info = get_userdata($user_id);
			//$to = trim($user_info->user_email);
			$subject = __('New member added to ', 'membership-site') . $user_data[0]->membership_level_name;
			$email_body = __('A New member added to ', 'membership-site');
			$email_body .= "\r\n" . __('Memberlevel: ', 'membership-site') . $user_data[0]->membership_level_name . "\r\n" . __('Domain: ', 'membership-site') . $hostname . "\r\n" .
				__('Username: ', 'membership-site') . $user_info->user_login;
			$to = $from_email = $this->get_fromEmail_info($general_settings);
			$headers  = 'From: ' . $from_email[0] . ' <' . $from_email[1] . '>' . "\r\n" .
				'Reply-To: ' . $from_email[1] . "\r\n" . 'X-Mailer: PHP/' . phpversion();
			add_filter('wp_mail_content_type', array(&$this, 'set_content_type'));
			$sent = wp_mail($to[1], $subject, nl2br($email_body), $headers);
			remove_filter('wp_mail_content_type', array(&$this, 'set_content_type'));
			return;
			//     return $result;
		}
		function generate_mail_template($replace_params, $mail_body)
		{
			$send_message = '';
			$message = str_replace(
				array_keys($replace_params),
				array_values($replace_params),
				$mail_body
			);
			$send_message .= '<p>' . $message . '</p>';
			return $send_message;
		}
		function generate_mail_template_subject($replace_subject, $mail_subject)
		{
			$message = '';
			$message = str_replace(
				array_keys($replace_subject),
				array_values($replace_subject),
				$mail_subject
			);
			return $message;
		}
		function admin_autoresponder_generate_mail_template($replace_params, $mail_body)
		{
			$send_message = '';
			$message = str_replace(
				array_keys($replace_params),
				array_values($replace_params),
				$mail_body
			);
			$send_message .= '<p>' . $message . '</p>';
			return $send_message;
		}
		function admin_autoresponder_generate_mail_template_subject($replace_subject, $mail_subject)
		{
			$message = '';
			$message = str_replace(
				array_keys($replace_subject),
				array_values($replace_subject),
				$mail_subject
			);
			return $message;
		}
		function get_fromEmail_info($general_settings)
		{
			if ($general_settings['admin_email_address'] == '') {
				$admin_email = get_option('admin_email');
				$admin_name = get_option('blogname');
				$from = array($admin_name, $admin_email);
			} else {
				$admin_email = $general_settings['admin_email_address'];
				$admin_name = $general_settings['admin_display_name'];
				$from = array($admin_name, $admin_email);
			}
			return $from;
		}
		function html2text($embody)
		{
			require_once(MEMBERSONICLITE_PLUGIN_DIR . '/lib/html2text.php');
			$h2t =  html2text($embody);
			$text = $h2t->get_text();
		}
		function set_content_type($content_type)
		{
			return 'text/html';
		}
	}
}
