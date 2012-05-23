<?php
	/*
	Plugin Name: PHP Live!
	Plugin URI: http://www.phplivesupport.com
	Description: PHP Live! enables live help and live customer support chat communication on your website.
	Author: osicodesinc
	Author URI: http://www.osicodesinc.com
	Version: 1.4
	*/

	/* (c) OSI Codes Inc. */
	/* http://www.osicodesinc.com */
	/* Dev team: 615 */

	if ( is_admin() ) { require_once(dirname(__FILE__).'/libs/phplive_setup.class.php') ; phplive_admin::get_instance() ; }
	else { require_once( dirname(__FILE__).'/libs/phplive.class.php' ) ; phplive::get_instance() ; }
?>
