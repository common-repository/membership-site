<?php

/**
 * 
 * create tables
 *
 * @param null
 * @return null
 */
class membership_site_db_model
{
    function create_table()
    {
        global $wpdb;
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_jvzoo_bonusdata (
					id int(11) NOT NULL AUTO_INCREMENT,
					cust_name varchar(255) NOT NULL,
					cust_email varchar(255) NOT NULL,
					type varchar(255) NOT NULL,
					prod_name varchar(255) NOT NULL,
					amount float NOT NULL,
					tran_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_jvzoo_detail (
					id int(11) NOT NULL AUTO_INCREMENT,
					entry_date datetime NOT NULL,
					mslevel_gateway varchar(255) NOT NULL,
					product varchar(255) NOT NULL,
					campaign varchar(255) NOT NULL,
					plugin_type text NOT NULL,
					ipndataurl text NOT NULL,
					PRIMARY KEY (id)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_sm_category_protection (
					id bigint(20) NOT NULL AUTO_INCREMENT,
					type_name varchar(50) DEFAULT NULL,
					type_id int(11) DEFAULT NULL,
					wp_membership_id int(11) DEFAULT NULL,
					is_protected tinyint(4) DEFAULT NULL,
					ar_mess tinyint(4) DEFAULT NULL,
					drip_feeds int(11) DEFAULT NULL,
					drip_date varchar(10) DEFAULT NULL,
					drip_day_exp int(4) DEFAULT NULL,
					drip_date_exp varchar(10) DEFAULT NULL,
					created_by bigint(20) DEFAULT NULL,
					created_date datetime DEFAULT NULL,
					PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_sm_cat_drip_ar (
					cat_id int(9) NOT NULL,
					subject varchar(500) DEFAULT NULL,
					body text,
					sign text,
					PRIMARY KEY (cat_id)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_sm_content_protection (
					id bigint(20) NOT NULL AUTO_INCREMENT,
					type_name varchar(50) DEFAULT NULL,
					type_id int(11) DEFAULT NULL,
					wp_membership_id int(11) DEFAULT NULL,
					is_protected tinyint(4) DEFAULT NULL,
					ar_mess tinyint(4) DEFAULT NULL,
					drip_feeds int(11) DEFAULT NULL,
					drip_date varchar(10) DEFAULT NULL,
					drip_day_exp int(4) DEFAULT NULL,
					drip_date_exp varchar(10) DEFAULT NULL,
					created_by bigint(20) DEFAULT NULL,
					created_date datetime DEFAULT NULL,
					PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_sm_drip_ar (
					post_id int(9) NOT NULL,
					subject varchar(500) DEFAULT NULL,
					body text,
					sign text,
					PRIMARY KEY (post_id)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_sm_email_broadcast (
					id int(9) NOT NULL AUTO_INCREMENT,
					subject varchar(500) DEFAULT NULL,
					body text,
					signature text,
					mlevel varchar(256) DEFAULT NULL,
					status varchar(10) DEFAULT 'Queued',
					created_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_sm_login_limit (
					id int(11) NOT NULL AUTO_INCREMENT,
					user_id int(11) DEFAULT NULL,
					ip varchar(20) DEFAULT NULL,
					created_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_sm_membership_details (
					id bigint(20) NOT NULL AUTO_INCREMENT,
					membership_level_name varchar(250) NOT NULL,
					redirect_page varchar(255) DEFAULT NULL,
					membership_level_key varchar(60) DEFAULT NULL,
					clickbank_product_id varchar(60) DEFAULT NULL,
					jvzoo_product_id varchar(60) DEFAULT NULL,
					WSOPRO_prod_code varchar(60) DEFAULT NULL,
					spbas_product_id varchar(60) DEFAULT NULL,
					spbas_tier_id varchar(60) DEFAULT NULL,
					subscribe_aweber bigint(20) DEFAULT NULL,
					email_title text,
					email_body text,
					integrate_to_spbas bigint(20) DEFAULT NULL,
					integrate_to_clickbank bigint(20) DEFAULT NULL,
					integrate_to_jvzoo bigint(20) DEFAULT NULL,
					integrate_to_WSOPRO bigint(20) DEFAULT NULL,
					webinar_enable bigint(20) DEFAULT NULL,
					webinar_serverno bigint(20) DEFAULT NULL,
					webinarkey1 varchar(255) DEFAULT NULL,
					webinarkey2 varchar(255) DEFAULT NULL,
					webinarkey3 varchar(255) DEFAULT NULL,
					webinarkey4 varchar(255) DEFAULT NULL,
					webinarkey5 varchar(255) DEFAULT NULL,
					webinarkey6 varchar(255) DEFAULT NULL,
					arweb_code text,
					arname_fields text,
					aremail_fields text,
					arpost_url text,
					arhidden_fields text,
					created_by bigint(20) DEFAULT NULL,
					created_date datetime DEFAULT NULL,
					pp_coupon_codes text,
					other_info text,
					PRIMARY KEY (id)
					) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;";
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_sm_membership_paypal (
					id int(11) NOT NULL AUTO_INCREMENT,
					wp_membership_level_id int(11) DEFAULT NULL,
					button_type varchar(80) DEFAULT NULL,
					currency_type varchar(60) DEFAULT NULL,
					description text,
					amount decimal(10,2) NOT NULL,
					payment_period varchar(80) DEFAULT NULL,
					payment_button_image varchar(255) DEFAULT NULL,
					trial_price decimal(10,2) NOT NULL,
					trial_period varchar(80) DEFAULT NULL,
					trial_duration int(11) DEFAULT NULL,
					number_of_payments int(11) DEFAULT NULL,
					button_key varchar(255) DEFAULT NULL,
					PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_sm_membership_transaction (
					id int(11) NOT NULL AUTO_INCREMENT,
					order_from varchar(255) DEFAULT NULL,
					order_type varchar(255) DEFAULT NULL,
					product_id int(11) DEFAULT NULL,
					order_id varchar(80) DEFAULT NULL,
					order_date datetime DEFAULT NULL,
					amount decimal(10,2) DEFAULT NULL,
					currency_type varchar(80) DEFAULT NULL,
					order_processor varchar(80) DEFAULT NULL,
					customer_id int(11) DEFAULT NULL,
					customer_email varchar(80) DEFAULT NULL,
					customer_fname varchar(80) DEFAULT NULL,
					customer_lname varchar(80) DEFAULT NULL,
					customer_address varchar(255) DEFAULT NULL,
					customer_city varchar(80) DEFAULT NULL,
					customer_country varchar(80) DEFAULT NULL,
					customer_ip varchar(80) DEFAULT NULL,
					purchase_map_flat varchar(80) DEFAULT NULL,
					purchase_map varchar(80) DEFAULT NULL,
					product_name_first varchar(80) DEFAULT NULL,
					product_type_first varchar(80) DEFAULT NULL,
					product_first_amount decimal(10,2) DEFAULT NULL,
					product_name_second varchar(80) DEFAULT NULL,
					product_type_second varchar(80) DEFAULT NULL,
					product_frequency varchar(80) DEFAULT NULL,
					product_second_amount decimal(10,2) DEFAULT NULL,
					created_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_sm_member_assoc (
					id bigint(20) NOT NULL AUTO_INCREMENT,
					user_id bigint(20) DEFAULT NULL,
					wp_membership_id int(11) DEFAULT NULL,
					is_active int(1) DEFAULT '1',
					profile_id varchar(255) DEFAULT NULL,
					created_by bigint(20) DEFAULT NULL,
					cancel_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					sub_exp_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					created_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$sql[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."77_sm_transactions (
					id int(11) NOT NULL AUTO_INCREMENT,
					Trans_Date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					Trans_Type varchar(255) DEFAULT NULL,
					Order_ID varchar(255) DEFAULT NULL,
					Platform varchar(255) DEFAULT NULL,
					Order_Type varchar(255) DEFAULT NULL,
					Cust_Name varchar(255) DEFAULT NULL,
					Cust_Email varchar(255) DEFAULT NULL,
					Amt float DEFAULT NULL,
					Prod_ID varchar(255) DEFAULT NULL,
					Product_Name varchar(255) DEFAULT NULL,
					Frequency varchar(255) DEFAULT NULL,
					data_dump text,
					PRIMARY KEY (id)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
         	$sql[] = 'ALTER TABLE  ' . WP_MEMBERSONICLITE_MEMBERSHIP_PAYPAL . ' 
            		CHANGE  amount  amount DECIMAL( 10, 2 ) NOT NULL ,
            		CHANGE  trial_price  trial_price DECIMAL( 10, 2 ) NOT NULL';
        	$sql[] = 'ALTER TABLE  ' . WP_MEMBERSONICLITE__MEMBER_ASSOC . '  
            		ADD  is_active INT( 1 ) 
            		NOT NULL DEFAULT  \'1\' 
					AFTER  wp_membership_id'; 
			$sql[] = 'ALTER TABLE ' . WP_MEMBERSONICLITE__MEMBER_ASSOC . '
					ADD sub_exp_date DATETIME 
					NOT NULL DEFAULT \'0000-00-00 00:00:00\' 
					AFTER  created_by;';
			$sql[] = 'ALTER TABLE ' . WP_MEMBERSONICLITE__MEMBER_ASSOC . ' 
					ADD cancel_date DATETIME 
					NOT NULL DEFAULT \'0000-00-00 00:00:00\' AFTER created_by;';
			$sql[] = 'ALTER TABLE ' . WP_MEMBERSONICLITE__MEMBER_ASSOC . ' 
					CHANGE created_date created_date DATETIME 
					NOT NULL DEFAULT \'0000-00-00 00:00:00\';';
			$sql[] = 'ALTER TABLE ' . WP_MEMBERSONICLITE_CONTENT_PROTECTION . '
						ADD drip_day_exp INT(4) NULL AFTER drip_date';
			$sql[] = 'ALTER TABLE ' . WP_MEMBERSONICLITE_CONTENT_PROTECTION . ' 
						ADD drip_date_exp VARCHAR(10) 
						CHARACTER SET latin1 
						COLLATE latin1_swedish_ci NULL DEFAULT NULL 
						AFTER drip_day_exp';	
			$sql[] = 'ALTER TABLE '.$wpdb->prefix.'77_sm_category_protection 
						CHANGE id id BIGINT(20) NOT NULL AUTO_INCREMENT';  	
//echo '<pre>'.print_r($sql,true).'</pre>';exit;
			$sql[] = "ALTER TABLE ".$wpdb->prefix."77_sm_membership_details ADD thrive_product_id  int(11) NULL AFTER pp_coupon_codes;";
			$sql[] = "ALTER TABLE ".$wpdb->prefix."77_sm_membership_details ADD thrive_product_type VARCHAR(255) NULL AFTER thrive_product_id;";
			$sql[] = "ALTER TABLE ".$wpdb->prefix."77_sm_transactions ADD currency VARCHAR(3) NULL AFTER Amt";
            foreach($sql as $q)
            {
                $wpdb->query($q); 
            }
    }
}
