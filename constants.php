<?php
  global $wpdb;
  if (!defined('MEMBERSONICLITE_PLUGIN_URL'))
  define('MEMBERSONICLITE_PLUGIN_URL', plugins_url('', __FILE__));
  if (!defined('MEMBERSONICLITE_PLUGIN_DIR'))
  define('MEMBERSONICLITE_PLUGIN_DIR', dirname(__FILE__));
    if (!defined('MEMBERSONICLITE_PAGE_SLUG_ADMIN'))
  define('MEMBERSONICLITE_PAGE_SLUG_ADMIN', 'wp_MEMBERSONICLITE_manage_membership'); 
  if (!defined('MEMBERSONICLITE_VERSION'))
  define('MEMBERSONICLITE_VERSION', get_bloginfo('version')); 

  // DATABASE TABLES CONSTANTS
  if (!defined('WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS'))
  define('WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS', $wpdb->prefix . '77_sm_membership_details');
  if (!defined('WP_MEMBERSONICLITE_CONTENT_PROTECTION'))
  define('WP_MEMBERSONICLITE_CONTENT_PROTECTION', $wpdb->prefix . '77_sm_content_protection');
  if (!defined('WP_MEMBERSONICLITE__MEMBER_ASSOC'))
  define('WP_MEMBERSONICLITE__MEMBER_ASSOC', $wpdb->prefix . '77_sm_member_assoc');
  if (!defined('WP_MEMBERSONICLITE_MEMBERSHIP_PAYPAL'))
  define('WP_MEMBERSONICLITE_MEMBERSHIP_PAYPAL', $wpdb->prefix . '77_sm_membership_paypal');
  if (!defined('WP_MEMBERSONICLITE__USERS'))
  define('WP_MEMBERSONICLITE__USERS', $wpdb->prefix . 'users');
  if (!defined('WP_MEMBERSONICLITE__USERMETA'))
  define('WP_MEMBERSONICLITE__USERMETA', $wpdb->prefix . 'usermeta');
  if (!defined('WP_MEMBERSONICLITE__USER_ASSOC'))
  define('WP_MEMBERSONICLITE__USER_ASSOC', $wpdb->prefix . '77_sm_user_assoc');
  if(!defined('WP_MEMBERSONICLITE_LOGIN_LIMIT'))
  define('WP_MEMBERSONICLITE_LOGIN_LIMIT', $wpdb->prefix. '77_sm_login_limit');
  if(!defined('WP_MEMBERSONICLITE_EMAIL_BROADCAST'))
  define('WP_MEMBERSONICLITE_EMAIL_BROADCAST', $wpdb->prefix. '77_sm_email_broadcast');
  if(!defined('WP_MEMBERSONICLITE_DRIP_AR'))
  define('WP_MEMBERSONICLITE_DRIP_AR', $wpdb->prefix. '77_sm_drip_ar');