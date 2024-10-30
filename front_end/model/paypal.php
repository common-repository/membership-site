<?php
class membership_site_paypalIPN
{
	function __construct()
	{ }
	function checkIPN($raw_post_data)
	{
		$sandbox = false;
		$ipn_response = !empty($_POST) ? $_POST : false;
		$postback = array('cmd' => '_notify-validate');
		$postback += stripslashes_deep($ipn_response);
		$actionurl = "https://www.paypal.com/cgi-bin/webscr";
		if ($sandbox) {
			$actionurl = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		}
		$params = array(
			'body' => $postback,
			'sslverify' => false,
			'timeout' => 60,
			'httpversion' => '1.1',
			'compress' => false,
			'decompress' => false,
			'user-agent' => 'MSLITE/1.0'
		);
		$response = wp_remote_post($actionurl, $params);

		if (is_wp_error($response) || strcmp($response, "VERIFIED")  != 0) {
			wp_die("PayPal IPN Request Failure", "PayPal IPN", array('response' => 200));
			return;
		}

		///////////// End if recurng payment
		if ($postback['txn_type'] == 'recurring_payment') {
			header("HTTP/1.1 200 OK");
			exit;
		}
		$payer_email = urldecode($postback['payer_email']);
		$receiver_email = urldecode($postback['receiver_email']);
		return $postback;
	}

	function add_MemberIPN($postback)
	{
		require_once(MEMBERSONICLITE_PLUGIN_DIR . '/front_end/model/front_model.php');
		$membersoniclite_front_model = new membership_site_front_model();
		$payer_email = urldecode($postback['payer_email']);
		$receiver_email = urldecode($postback['receiver_email']);

		if (
			$postback['txn_type'] == 'subscr_cancel' ||
			$postback['txn_type'] == 'recurring_payment_expired' ||
			$postback['payment_status'] == 'Refunded' ||
			$postback['txn_type'] == 'subscr_failed' ||
			$postback['txn_type'] == 'recurring_payment_suspended' ||
			$postback['txn_type'] == 'recurring_payment_failed'
		) {
			$this->cancel_membership($postback, $membersoniclite_front_model, $payer_email);
			header("HTTP/1.1 200 OK");
			exit;
		}
		$check = 'fail';
		if ($postback['txn_type'] == 'web_accept')
			$check = $this->check_webaccept($postback, $membersoniclite_front_model);
		if ($postback['txn_type'] == 'subscr_signup')
			$check = $this->check_subscr_signup($postback, $membersoniclite_front_model);
		if ($check != 'fail') {
			$password_unique_code = $this->generatePasswd(8, 0);
			$data = array(
				'first_name'       => sanitize_text_field($postback['first_name']),
				'last_name'        => sanitize_text_field($postback['last_name']),
				'username'         => sanitize_text_field($payer_email),
				'email'            => sanitize_email($payer_email),
				'password'         => sanitize_text_field($password_unique_code),
				'member_level_key' => sanitize_text_field($check[0]),
				'admin_email'      => sanitize_email($receiver_email),
				'uniqueid'         => sanitize_text_field($check[1]),
				'no_multi_emails'  => 0
			);
			if ($postback['txn_type'] == 'subscr_signup')
				$data['profile_id'] = $postback['subscr_id'];
			$this->checkMlevels_forUser($payer_email, $check[0]);
			$membersoniclite_front_model->new_member_via_ipn($data);
			header("HTTP/1.1 200 OK");
			exit;
		}
	}
	function cancel_membership($postback, $membersoniclite_front_model, $payer_email)
	{
		$item_number  = urldecode($postback['item_number']);
		$getids = explode('-', $item_number);
		if (isset($getids[0])) {
			$membersoniclite_front_model->cancel_User_Membership($getids[0], $payer_email);


			header("HTTP/1.1 200 OK");
			exit;
		}
	}

	function check_subscr_signup($postback, $membersoniclite_front_model)
	{
		$item_number  = urldecode($postback['item_number']);
		$getids = explode("-", $item_number);


		/*  if hosted button */
		if ($postback['custom'] != 'mshosted') return $getids;
		return 'fail';
	}

	function check_webaccept($postback, $membersoniclite_front_model)
	{
		$item_number  = urldecode($postback['item_number']);
		$getids = explode("-", $item_number);
		if ($postback['custom'] != 'mshosted') return $getids;

		return $getids;
	}

	function generatePasswd($numAlpha = 6, $numNonAlpha = 2)
	{
		$listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$listNonAlpha = ',;:!?.$/*-+&@_+;./*&?$-!,';
		return str_shuffle(substr(str_shuffle($listAlpha), 0, $numAlpha) . substr(str_shuffle($listNonAlpha), 0, $numNonAlpha));
	}
	function checkMlevels_forUser($email, $mlevel)
	{
		global $wpdb;
		$sql       = "SELECT COUNT( member.id )  FROM " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " as member 
                    INNER JOIN " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " as details ON details.id = member.wp_membership_id 
                    WHERE member.user_id = ( SELECT ID from $wpdb->users WHERE  user_email = '" . $email . "') 
                        AND member.is_active = 1
                        AND details.membership_level_key = '" . $mlevel . "'";
		$id = $wpdb->get_var($sql);


		if ($id != '0') {
			header("HTTP/1.1 200 OK");
			exit;
		} else {
			return;
		}
	}
}
