<?php
$general_settings = get_option('wp_wso_general_settings');
class membership_site_membership
{
	function __construct()
	{
		global $general_settings;
		//	register_activation_hook(__file__, array($this, "wp_membersoniclite_create_table"));
		//	register_deactivation_hook(__file__, array($this, "ms_deactivation"));
		//add_action('init', array($this, 'do_output_buffer'));
		//add_action('init', array($this, 'wp_membersoniclite_WidgetSetup'));
		add_shortcode('REGISTRATION_WSO', array($this, 'registration_shortcode'));
		add_shortcode('MSREGISTRATION', array($this, 'registration_shortcode'));
		add_shortcode('MSLOGIN', array($this, 'login_shortcode'));
		add_shortcode('MSPASSWORDRESET', array($this, 'password_reset_shortcode'));
		add_shortcode('PASSWORDRESET', array($this, 'password_reset_shortcode'));

		add_action('admin_menu', array($this, 'admin_menu'));

		if ($general_settings['hide_protected_content_links'] == 'on') {
			add_action('get_sidebar', array($this, 'sidebar_hide_filters'));
			add_filter('wp_nav_menu_objects', array($this, 'ms_hide_links_navmenu'));
		}

		if (!is_admin())
			add_action('pre_get_posts', array($this, 'search_filter'));

		add_action('wp_head', array($this, 'wp_content_protection'));
		//	add_action('wp_ajax_membersoniclite_ajax', array($this, 'wp_membersoniclite_ajax_helper'));

		add_action('add_meta_boxes', array($this, 'metabox'));
		add_action('save_post', array($this, 'metabox_save'));

		add_action('trash_post', array($this, 'ms_del_protection'));
		add_action('trash_page', array($this, 'ms_del_protection'));
		add_action('delete_user', array($this, 'ms_delete_user'));
		add_filter('wp_mail_content_type', array($this, 'set_content_type'));

		add_action('wp_ajax_membersonic_login', array($this, 'ms_login'));
		add_action('wp_ajax_nopriv_membersonic_login', array($this, 'ms_login'));
		add_action('wp_ajax_mslite_saveNewUser', array($this, 'mslite_saveNewUser'));
		add_action('wp_ajax_nopriv_mslite_saveNewUser', array($this, 'mslite_saveNewUser'));
		add_action('wp_ajax_mslite_updateUserMembershiphtml', array($this, 'update_user_membershiphtml'));
		add_action('wp_ajax_mslite_updateUserMembershipsave', array($this, 'update_user_membershipsave'));

		add_filter('retrieve_password_message', array($this, 'ms_new_retrieve_password_message'), 99, 2);

		add_action('admin_enqueue_scripts', array($this, 'admin_script'));
		add_action('wp_enqueue_scripts', array($this, 'front_script'));
		add_action('admin_footer', array($this, 'metabox_shortcode_pop'));

		if (intval($_GET['mspaypalipn']) == '1')
			add_action('plugins_loaded', array($this, 'paypal_IPN_process'));

		add_action('plugins_loaded', array($this, 'save_memebrshipLevels'));

		if (sanitize_text_field($_GET['membersoniclite_action']) == 'clonelevel')
			add_action('plugins_loaded', array($this, 'clone_membership'));

		if (sanitize_text_field($_GET['membersoniclite_action']) == 'deletelevel')
			add_action('plugins_loaded', array($this, 'delete_membership'));
	}
	function clone_membership()
	{
		include MEMBERSONICLITE_PLUGIN_DIR . "/admin/model/clone-membership.php";
		$clone_membership_level = new membership_site_clone_membership_level;
		$clone_membership_level->clone();
	}
	function delete_membership()
	{
		include_once MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/add-edit-membership-level.php';
		$add_edit_membership_level = new membership_site_add_edit_membership_level;
		$add_edit_membership_level->delete_membership_level();
	}
	function save_memebrshipLevels()
	{
		include_once MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/add-edit-membership-level.php';
		$add_edit_membership_level = new membership_site_add_edit_membership_level;
		if (sanitize_text_field($_POST['msliteaction']) == 'savestep1') {
			$add_edit_membership_level->savestep1();
		}
		if (sanitize_text_field($_POST['msliteaction']) == 'savestep2') {
			$add_edit_membership_level->savestep2();
		}
		if (sanitize_text_field($_POST['msliteaction']) == 'savestep3') {
			$add_edit_membership_level->savestep3();
		}
	}

	function paypal_IPN_process()
	{
		include(MEMBERSONICLITE_PLUGIN_DIR . '/front_end/model/paypal.php');
		$membersoniclite_paypalIPN = new membership_site_paypalIPN;
		$raw_post_data = file_get_contents('php://input');
		$postback = $membersoniclite_paypalIPN->checkIPN($raw_post_data);
		$membersoniclite_paypalIPN->add_MemberIPN($postback);
	}
	function mslite_saveNewUser()
	{
		if (!wp_verify_nonce($_POST['security'], $_POST['uniqid'])) {
			echo json_encode(array('status' => 'error', 'message' => 'Error'));
			exit;
		}
		$type = sanitize_text_field($_POST['type']);
		include(MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/manage-users.php');
		$membersoniclite_manage_users = new membership_site_manage_users;
		$membersoniclite_manage_users->save_New_User();
	}
	function update_user_membershiphtml()
	{
		include(MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/manage-users.php');
		$membersoniclite_manage_users = new membership_site_manage_users;
		$membersoniclite_manage_users->update_user_membershiphtml();
	}
	function update_user_membershipsave()
	{
		include(MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/manage-users.php');
		$membersoniclite_manage_users = new membership_site_manage_users;
		$membersoniclite_manage_users->update_user_membershipsave();
	}
	function ms_new_retrieve_password_message($message, $key)
	{
		$message = str_replace('<', '', $message);
		$message = str_replace('>', '', $message);
		return $message;
	}

	function ms_login()
	{
		include_once('front_end/model/front_model.php');
		$membersoniclite_front_model = new membership_site_front_model();
		$creds = array();
		$creds['user_login'] = sanitize_user($_POST['username']);
		$creds['user_password'] = sanitize_text_field(trim($_POST['password']));
		$user = wp_signon($creds, true);

		if (is_wp_error($user)) {
			echo json_encode(array('status' => 'error', 'message' => $user->get_error_message()));
			exit;
		}


		$all_memberships = $membersoniclite_front_model->getMlevels_forUser($user->ID);
		if (count($all_memberships) == 1) {
			$membership_data = $membersoniclite_front_model->getMemberShipKey($all_memberships[0]->wp_membership_id);
			$other_info = json_decode($membership_data[0]->other_info);
			$redirect_url_memberLevel = $other_info->after_login_memlevel;
		}

		if (!isset($redirect_url_memberLevel)) {
			$setval = get_option('wp_wso_general_settings');
			$redirect_url_memberLevel = $setval['paypal_members_area'];
		}

		$levelId = intval($_POST['levelId']);

		if (intval($_POST['levelId']) != "0") {
			include(MEMBERSONICLITE_PLUGIN_DIR . '/admin/model/manage-users.php');
			$membersoniclite_manage_users = new membership_site_manage_users;
			$data['userid'] = $user->ID;
			$data['memberlevels'][] = intval($levelId);
			$membersoniclite_manage_users->update_user_membershipsave('freemember');
		}

		wp_set_auth_cookie($user->ID, 0, 0);
		echo json_encode(array('status' => 'success', 'redirecturl' => $redirect_url_memberLevel));

		exit;
	}
	function set_content_type($content_type)
	{
		return 'text/html';
	}
	function ms_delete_user($user_id)
	{
		global $wpdb;
		$wpdb->delete(WP_MEMBERSONICLITE__MEMBER_ASSOC, array('user_id' => $user_id), array('%d'));
	}
	function ms_del_protection()
	{
		global $wpdb;
		$pids = isset($_POST['post']) ? (array) $_POST['post'] : array();
		if (empty($pids))
			return true;
		$pids = array_map("intval", $pids);
		$pids = implode(",", $pids);

		$sql = 'DELETE FROM ' . WP_MEMBERSONICLITE_CONTENT_PROTECTION . ' WHERE type_id IN (' . $pids .
			')';
		$wpdb->query($sql);
		$sql = 'DELETE FROM ' . WP_MEMBERSONICLITE_DRIP_AR . ' WHERE post_id IN (' . $pids . ')';
		$wpdb->query($sql);
		return true;
	}
	public function wp_content_protection()
	{
		//////////////////////////////////////////
		global $post, $wpdb;
		//print_r($post); 

		$redirectvalue = get_option('wp_wso_general_settings');
		$redirect      = $redirectvalue['paypal_access_denied'];
		if (trim($redirect) == '') {
			$redirect = home_url();
		}
		$protected_ids = array();
		$pageid        = $post->ID;
		///buddypress fix start
		//  echo '<pre>'.print_r($post,true).'</pre>'; //exit();
		if ($pageid == "0" && $post->post_name == '') {
			return;
		}
		if ($pageid == "0" && $post->post_name != '') {
			$bppage = get_page_by_path($post->post_name);
			$pageid = $bppage->ID;
		}
		///buddypress fix end  
		if ($pageid == '' && is_singular()) {
			$page404id = get_page_by_path('memsonic-page-not-found');
			wp_redirect(get_permalink($page404id));
			exit;
		}
		if (is_user_logged_in()) {
			$logged = wp_get_current_user();
			if ($post && $pageid > 0) {
				$protected_ids = $this->getPortectedTypeId($logged->ID);
				//		echo '<pre>PROTECTED IDS:'.print_r($protected_ids ,true).'</pre>';
				//		echo $post->ID;
				//		echo is_singular();
				if (
					!empty($protected_ids['all']) && in_array($post->ID, $protected_ids['all']) &&
					is_singular()
				) {
					wp_redirect($redirect);
					exit;
				}
			}
		} else {
			if ($pageid > 0) {
				$sql         = "SELECT count(*) FROM " . $wpdb->prefix . "77_sm_content_protection WHERE is_protected = 1 AND type_id = " . $pageid;
				$isProtected = $wpdb->get_var($sql);
				// print_r($isProtected);
				$redirect    = $redirectvalue['paypal_access_denied'];
				if ($isProtected > 0) {
					wp_redirect($redirect);
					exit;
				}
			}
		}
	}

	function getPortectedTypeId($userId)
	{
		global $wpdb;
		$user_mlevels       = $this->get_users_mlevels($userId);
		if (empty($user_mlevels)) {
			$user_mlevels[] = '0';
		}
		$umemlevs       = implode(',', $user_mlevels);
		// get protected posts and pages  belonging to this user's member levels
		$sql            = "SELECT DISTINCT scp.type_id FROM " . $wpdb->prefix . "77_sm_content_protection AS scp INNER JOIN " . $wpdb->prefix . "77_sm_member_assoc AS sma ON scp.wp_membership_id  = sma.wp_membership_id
		WHERE  scp.is_protected = 1
		AND sma.user_id = " . $userId . "
		AND sma.is_active = 1
		AND sma.wp_membership_id IN(" . $umemlevs . ")";
		$content_values = $wpdb->get_results($sql);
		//    echo '<pre>MY CONTENT:'.print_r($content_values,true).'</pre>';
		foreach ($content_values as $value) {
			$mycontent[] = $value->type_id;
		}
		if (empty($mycontent)) {
			$mycontent[] = '0';
		}
		$mycont         = implode(',', $mycontent);
		// get protected posts and pages not belonging to this user's member levels
		$sql            = "SELECT DISTINCT scp.type_id FROM " . $wpdb->prefix . "77_sm_content_protection AS scp  WHERE scp.type_id NOT IN (" . $mycont . ")";
		$content_values = $wpdb->get_results($sql);

		//    echo '<pre>MY CONTENT:'.print_r($content_values,true).'</pre>';
		foreach ($content_values as $value) {
			$NotMyContent[] = $value->type_id;
		}
		if (empty($NotMyContent)) {
			$NotMyContent[] = '0';
		}
		$protected_ids['all'] = $NotMyContent;
		return $protected_ids;
	}

	function get_users_mlevels($userid)
	{
		global $wpdb;
		$membdat = array();
		if ($userid != '') {
			$sql = "SELECT wp_membership_id   FROM " . WP_MEMBERSONICLITE__MEMBER_ASSOC . " 
						WHERE user_id =" . $userid . " 
							AND is_active = 1 AND wp_membership_id != ''";
			$member_data = $wpdb->get_col($sql);
			foreach ($member_data as $mid)
				$membdat[] = $mid;
		}
		//	print_r($membdat); exit;
		return $membdat;
	}
	function admin_script()
	{
		$this->metabox_scripts();
		$allowed = array(
			'membership-site' . '-dashboard',
			'membership-site' . '-add-edit-membership-level',
			'membership-site' . '-general_settings',
			'membership-site' . '-content_protection',
			'membership-site' . '-manage-users',
			'membership-site' . '-manage-users-import'
		);
		if (!in_array(sanitize_text_field($_GET['page']), $allowed)) {
			return;
		}
		echo '<script> var membersoniclite_plugin_url = "' . MEMBERSONICLITE_PLUGIN_URL . '"; </script>';
		remove_all_actions('admin_notices');
		wp_register_style('membersoniclites_admin_btst_style', plugins_url('assets/css/btstr.min.css', __file__), '1.0');
		wp_enqueue_style('membersoniclites_admin_btst_style');
		wp_register_style('membersoniclites_admin_style', plugins_url('assets/css/admin.css', __file__), '1.0');
		wp_enqueue_style('membersoniclites_admin_style');
		echo '<script> var plugin_url = "' . MEMBERSONICLITE_PLUGIN_URL . '"</script>';
		wp_enqueue_script('jquery');
		wp_register_script('membersoniclites_admin_btstr_script', plugins_url('assets/js/btstr.min.js', __file__), '1.0');
		wp_enqueue_script('membersoniclites_admin_btstr_script');

		wp_localize_script('wp_membersoniclite_admin_script_admin', 'objectL10n', array(
			'Free_Registration_Shortcode' => __(
				'Free Registration Shortcode.',
				'membership-site'
			),
			'No_space_between_two_letters' => __(
				'No space between two letters.',
				'membership-site'
			),
			'Please_enter_the_User_Name' => __('Please enter the User Name.', 'membership-site'),
			'Please_enter_the_First_Name' => __(
				'Please enter the First Name.',
				'membership-site'
			),
			'Please_enter_the_Last_Name' => __('Please enter the Last Name.', 'membership-site'),
			'Please_enter_the_Email_Id' => __('Please enter the Email_Id.', 'membership-site'),
			'Please_enter_the_Password' => __('Please enter the Password.', 'membership-site'),
			'Password_has_to_be_atleast_8_characters_long' => __(
				'Password has to be atleast 8 characters long.',
				'membership-site'
			),
			'Working' => __('Working', 'membership-site'),
			'Updated' => __('Updated', 'membership-site'),
			'Add_New_Member' => __('Add New Member', 'membership-site'),
			'IPN_Forwarding_URLs' => __('IPN Forwarding URLs', 'membership-site'),
			'Create_a_Paypal_Button' => __('Create PayPal Coupon Code', 'membership-site'),
			'updatewarning' => __(
				'Access for this member will be changed.  Do you want to proceed?',
				'membership-site'
			),
			'notset' => __(
				'ERROR: Please setup USER CONTROL under GENERAL SETTINGS.',
				'membership-site'
			)
		));
	}

	function front_script()
	{

		global $post;
		if (
			is_a($post, 'WP_Post')
			&& (has_shortcode($post->post_content, 'MSREGISTRATION')
				|| has_shortcode($post->post_content, 'REGISTRATION_WSO')
				|| has_shortcode($post->post_content, 'MSLOGIN')
				|| has_shortcode($post->post_content, 'PASSWORDRESET'))
		) {
			wp_register_style('membersoniclites_front_btst_style', plugins_url('assets/css/btstr.min.css', __file__), '1.0');
			wp_enqueue_style('membersoniclites_front_btst_style');
			//wp_register_style('membersoniclites_front_style', plugins_url('assets/css/front-style.css', __file__), '1.0');
			//	wp_enqueue_style('membersoniclites_front_style');
			echo '<script> var msplugin_url = "' . MEMBERSONICLITE_PLUGIN_URL . '"</script>';

			wp_enqueue_script('jquery');
			wp_register_script('wp_membersoniclite_front_end_script', plugins_url(
				'assets/js/front.js',
				__file__
			), array('jquery'), MEMBERSONICLITE_VERSION);

			wp_enqueue_script('wp_membersoniclite_front_end_script');
			wp_localize_script('wp_membersoniclite_front_end_script', 'objectL10n', array(
				'No_space_between_two_letters' => __(
					'No space between two letters.',
					'membership-site'
				),
				'Please_enter_the_User_Name' => __('Please enter the User Name.', 'membership-site'),
				'Please_enter_the_First_Name' => __(
					'Please enter the First Name.',
					'membership-site'
				),
				'Please_enter_the_Last_Name' => __('Please_enter_the_Last_Name.', 'membership-site'),
				'Please_enter_the_Email_Id' => __('Please enter the Email_Id.', 'membership-site'),
				'Please_enter_the_Password' => __('Please enter the Password.', 'membership-site'),
				'Password_has_to_be_atleast_8_characters_long' => __(
					'Password has to be atleast 8 characters long.',
					'membership-site'
				),
				'Username_already_exist' => __('Username already exist', 'membership-site'),
				'Email_id_already_exist' => __('Email id already exist', 'membership-site'),
				'Please_enter_the_User_Name' => __('Please enter the User Name', 'membership-site'),
				'Please_enter_the_User_Name' => __('Please enter the User Name', 'membership-site'),
				'Please_enter_the_password' => __('Please enter the password', 'membership-site'),
				'Coupon_Code' => __('', 'membership-site'),
				'Successfully_Applied' => __('Successfully Applied', 'membership-site'),
				'Your_Discounted_Price' => __('Your Discounted Price', 'membership-site'),
				'Invalid_Coupon_Code' => __('Invalid CouponCode', 'membership-site'),
				'Registered_Successfully' => __(
					'Registered Successfully. Please check your email',
					'membership-site'
				),
				'No_space' => __('No Space between two letters', 'membership-site'),
				'ajaxurl' => admin_url('admin-ajax.php')
			));

			wp_register_style('wp_membersoniclite_front_end_theme_style', plugins_url(
				'/assets/css/front.css',
				__file__
			), MEMBERSONICLITE_VERSION);
			wp_enqueue_style('wp_membersoniclite_front_end_theme_style');
		}
	}

	function admin_menu()
	{
		add_menu_page(
			'membership-site',
			'Membersonic',
			'manage_options',
			'membership-site' . '-dashboard',
			array($this, 'dashboard'),
			plugins_url(basename(dirname(__file__)) . '/assets/images/tornado.png')
		);
		add_submenu_page(
			'membership-site' . '-dashboard',
			__('Dashboard', 'membership-site'),
			__('Dashboard', 'membership-site'),
			'manage_options',
			'membership-site' . '-dashboard',
			array($this, 'dashboard')
		);
		add_submenu_page(
			'membership-site' . '-general_settings',
			__('Add Edit Membership', 'membership-site'),
			__('Add Edit Membership', 'membership-site'),
			'manage_options',
			'membership-site' . '-add-edit-membership-level',
			array($this, 'add_edit_membership_level')
		);
		add_submenu_page(
			'membership-site' . '-dashboard',
			__(
				'General Settings',
				'membership-site'
			),
			__('General Settings', 'membership-site'),
			'manage_options',
			'membership-site' . '-general_settings',
			array($this, 'general_settings')
		);
		add_submenu_page(
			'membership-site' . '-dashboard',
			__('Content Protection', 'membership-site'),
			__('Content Protection', 'membership-site'),
			'manage_options',
			'membership-site' . '-content_protection',
			array($this, 'content_protection')
		);
		add_submenu_page(
			'membership-site' . '-dashboard',
			__('Member Mgmt', 'membership-site'),
			__('Member Mgmt', 'membership-site'),
			'manage_options',
			'membership-site' . '-manage-users',
			array($this, 'manage_users')
		);
		add_submenu_page(
			'membership-site' . '-manage-users',
			__('Import Members', 'membership-site'),
			__('Import Members', 'membership-site'),
			'manage_options',
			'membership-site' . '-manage-users-import',
			array($this, 'manage_users_import')
		);
	}
	function dashboard()
	{
		include_once('admin/view/dashboard.php');
	}
	function add_edit_membership_level()
	{
		include_once('admin/view/add-edit-membership-level.php');
	}
	function general_settings()
	{
		include_once('admin/view/general_settings.php');
	}
	function content_protection()
	{
		include_once('admin/view/content_protection.php');
	}
	function manage_users()
	{
		include_once('admin/view/manage_users.php');
	}
	function manage_users_import()
	{
		include_once('admin/view/manage_users_import.php');
	}
	/////////SHORTCODES/////////////
	function registration_shortcode($attrs)
	{
		global $wpdb;
		$valId = $attrs['level'];

		$q = "SELECT id, other_info FROM " . $wpdb->prefix . "77_sm_membership_details
                WHERE membership_level_key = '" . $valId . "'";
		$res = $wpdb->get_row($q);
		$otherinfo = json_decode($res->other_info, true);
		$membershipid = $res->id;
		if ($otherinfo['paid_memlevel'] == "1")
			return;
		if ($membershipid != '') {
			ob_start();
			include("front_end/view/shortcode-registation.php");
			$output_string = ob_get_contents();
			ob_end_clean();
			return $output_string;
		}
	}
	function login_shortcode()
	{
		ob_start();
		include_once "front_end/view/shortcode-loginform.php";
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}

	function password_reset_shortcode()
	{
		ob_start();
		include("front_end/view/shortcode-passwordreset.php");
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}

	function sidebar_hide_filters()
	{
		add_filter('get_pages', array($this, 'ms_hide_pages_links'));
		add_filter('the_comments', array($this, 'ms_hide_comments'));
	}
	function ms_hide_links_navmenu($sorted_menu_items, $args = '')
	{
		$mlevids = array();
		$mids = array();
		if (is_user_logged_in()) {
			$logged = wp_get_current_user();
			$mlevids = $this->get_protected_ids($logged->ID);
		}
		foreach ($mlevids as $key => $obj) {
			$mids[] = $mlevids[$key]->type_id;
		}
		foreach ($sorted_menu_items as $key => $obj) {
			if ($this->is_protected($obj->object_id, 'page') != 0 && !in_array($obj->object_id, (array) $mids) && !current_user_can('manage_options')) {
				unset($sorted_menu_items[$key]);
			}
		}
		return array_values($sorted_menu_items);
	}
	function ms_hide_pages_links($page_items)
	{
		$mlevids = array();
		$mids = array();
		if (is_user_logged_in()) {
			$logged = wp_get_current_user();
			$mlevids = $this->get_protected_ids($logged->ID);
		}
		foreach ($mlevids as $key => $obj) {
			$mids[] = $mlevids[$key]->type_id;
		}
		foreach ($page_items as $key => $obj) {
			if (
				$this->is_protected($obj->ID, 'page') != 0 && !in_array($obj->ID, (array) $mids) &&
				!current_user_can('manage_options')
			) {
				unset($page_items[$key]);
			}
		}
		return array_values($page_items);
	}
	function ms_hide_posts_links($post_items)
	{
		$mlevids = array();
		$mids = array();
		if (is_user_logged_in()) {
			$logged = wp_get_current_user();
			$mlevids = $this->get_protected_ids($logged->ID);
		}
		foreach ($mlevids as $key => $obj) {
			$mids[] = $mlevids[$key]->type_id;
		}
		foreach ($post_items as $key => $obj) {
			if (
				$this->is_protected($obj->ID, 'post') != 0 && !in_array($obj->ID, (array) $mids) &&
				!current_user_can('manage_options')
			) {
				unset($post_items[$key]);
			}
		}
		return array_values($post_items);
	}
	function ms_hide_comments($commentcont)
	{
		$mlevids = array();
		$mids = array();
		if (is_user_logged_in()) {
			$logged = wp_get_current_user();
			$mlevids = $this->get_protected_ids($logged->ID);
		}
		if (!empty($mlevids)) {
			foreach ($mlevids as $key => $obj) {
				$mids[] = $mlevids[$key]->type_id;
			}
		}
		//	print_r($mids).'<br/>';//.$obj->comment_post_ID;
		foreach ($commentcont as $key => $obj) {
			if ($this->is_protected($obj->comment_post_ID) != 0 && !in_array($obj->comment_post_ID, (array) $mids) && !current_user_can('manage_options')) {
				unset($commentcont[$key]);
			}
		}
		return array_values($commentcont);
	}
	function get_protected_ids($userid, $ptype = '')
	{
		global $wpdb;
		$sql = "SELECT * 
					FROM  " . WP_MEMBERSONICLITE_CONTENT_PROTECTION . "
					WHERE wp_membership_id
					IN (
						SELECT wp_membership_id
						FROM  " . WP_MEMBERSONICLITE__MEMBER_ASSOC . "
						WHERE user_id =  '" . $userid . "'
						 AND   is_active = '1'
					)
					AND is_protected =  '1' ";
		if ($ptype != '')
			$sql .= " AND type_name = '" . $ptype . "'";
		$getlevelids = $wpdb->get_results($sql);
		return $getlevelids;
	}
	function is_protected($pageid, $ptype = '')
	{
		global $wpdb;
		$sql = "SELECT  COUNT(*) 
					FROM  " . WP_MEMBERSONICLITE_CONTENT_PROTECTION . "
					WHERE type_id  =  '" . $pageid . "' 
					AND is_protected =  '1' ";
		if ($ptype != '')
			$sql .= " AND type_name = '" . $ptype . "'";
		$pid = $wpdb->get_var($sql);
		return $pid;
	}
	function search_filter($query)
	{
		global $wpdb;
		$remove = array();
		$NotMyContent = array();
		if (is_user_logged_in()) {
			$logged = wp_get_current_user();
			$NotMyContent = $this->getPortectedTypeId($logged->ID);
			$remove = $NotMyContent['NotMyContent'];
		} else {
			$sql = "SELECT type_id FROM " . WP_MEMBERSONICLITE_CONTENT_PROTECTION .
				" WHERE is_protected = 1";
			$rem_ids = $wpdb->get_results($sql, ARRAY_N);
			if (!empty($rem_ids)) {
				foreach ($rem_ids as $val)
					$remove[] = $val[0];
			}
		}
		if (!empty($remove)) {
			if (!is_admin() && $query->is_main_query()) {
				if ($query->is_search) {
					$query->set('post__not_in', $remove);
				}
			}
		}
		return true;
	}
	/*********** METABOX  ***************/
	function metabox_scripts()
	{
		if (!is_admin()) return;
		$screen = get_current_screen();
		if ((sanitize_text_field($_GET['action']) == 'edit' || $screen->action == 'add')
			&& ($screen->post_type == 'page' || $screen->post_type == 'post')
		) {
			echo '<script> var plugin_url = "' . MEMBERSONICLITE_PLUGIN_URL . '"</script>';
			wp_register_script('membersoniclites_admin_btst_script', plugins_url('assets/js/btstr.min.js', __file__), '1.0');
			wp_enqueue_script('membersoniclites_admin_btst_script');
			wp_register_script('wp_sm_admin_script_msshortcode',  MEMBERSONICLITE_PLUGIN_URL . '/assets/js/msshortcode.js', MEMBERSONICLITE_VERSION);
			wp_enqueue_script('wp_sm_admin_script_msshortcode');
			wp_register_style('membersoniclites_admin_btst_style', plugins_url('assets/css/btstr.min.css', __file__), '1.0');
			wp_enqueue_style('membersoniclites_admin_btst_style');
			wp_register_style('wp_sm_msshortcode_style', MEMBERSONICLITE_PLUGIN_URL . '/assets/css/msshortcode-style.css', MEMBERSONICLITE_VERSION);
			wp_enqueue_style('wp_sm_msshortcode_style');
		}
	}
	function metabox_shortcode_pop()
	{
		if (!is_admin()) return;
		$screen = get_current_screen();
		if ((sanitize_text_field($_GET['action']) == 'edit' || $screen->action == 'add')
			&& ($screen->post_type == 'page' || $screen->post_type == 'post')
		) {
			include_once MEMBERSONICLITE_PLUGIN_DIR . '/admin/view/msshortcode.php';
		}
	}
	public function metabox()
	{
		$post_types = get_post_types('', 'names');
		foreach ($post_types as $post_type) {
			add_meta_box(
				'membersonic-post-class',
				__('membership-site', 'membership-site'),
				array($this, 'metabox_cont'),
				$post_type,
				'side',
				'high' //
			);
		}
	}
	public function metabox_cont($post)
	{
		global $wpdb;
		$sql            = "SELECT id, membership_level_name FROM  " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS . " ORDER BY membership_level_name";
		$memlevels      = $wpdb->get_results($sql);
		$sql            = "select wp_membership_id FROM " . $wpdb->prefix . "77_sm_content_protection where type_id = '" . $post->ID . "' AND is_protected = '1'";
		$protectcontent = $wpdb->get_results($sql);
		include_once "admin/view/add-metabox.php";
	}
	public function metabox_save($post_id, $post = '')
	{

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
		if (intval($_POST['edit_post_screen']) > 0) {
			require_once("admin/model/save-meta-boxes.php");
			$save_metabox = new membership_site_meta_boxes();
			$save_metabox->save_meta_box();
		}
		return $post_id;
	}
}
/* Class ends */
