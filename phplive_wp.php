<?php
	/* (c) OSI Codes Inc. */
	/* http://www.osicodesinc.com */
	/* Dev team: 615 */
	error_reporting(0) ;
	$version = 1.4 ;
	include_once( "./API/Util_Format.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$name = Util_Format_Sanatize( Util_Format_GetVar( "name" ), "ln" ) ;
	$email = Util_Format_Sanatize( Util_Format_GetVar( "email" ), "e" ) ;

	if ( $action == "verify_url" )
	{
		$phplive_url = Util_Format_Sanatize( Util_Format_GetVar( "phplive_url" ), "base_url" ) ;
		$str_len = strlen( $phplive_url ) ;
		$last = ( $str_len ) ? $phplive_url[$str_len-1] : "" ;
		if ( ( $last == "/" ) || ( $last == "\\" ) )
			$phplive_url = substr( $phplive_url, 0, $str_len - 1 ) ;

		if ( preg_match( "/(http:\/\/)|(https:\/\/)/i", $phplive_url ) )
		{
			$phplive_url_encoded = urlencode( $phplive_url ) ;
			// http://10.0.0.5/osicodes/website2/phplive_wp.php?action=verify_url&phplive_url=$phplive_url_encoded
			$tags = get_meta_tags( "http://www.phplivesupport.com/phplive_wp.php?action=verify_url&phplive_url=$phplive_url_encoded" ) ;

			$description = isset( $tags["description"] ) ? $tags["description"] : "" ;
			$author = isset( $tags["author"] ) ? $tags["author"] : "" ;

			$phplive_url = preg_replace( "/\/setup$/", "", $phplive_url ) ;

			if ( preg_match( "/(PHP Live)/", $description ) )
			{
				if ( $author == "osicodesinc" )
					$json_data = "json_data = { \"status\": 1, \"phplive_url\": \"$phplive_url\", \"error\": \"\" };" ;
				else
					$json_data = "json_data = { \"status\": 0, \"error\": \"Please upgrade your PHP Live! system for WordPress integration.\" };" ;
			}
			else if ( $description )
				$json_data = "json_data = { \"status\": 0, \"error\": \"Invalid PHP Live URL.  Please try again.\" };" ;
			else
				$json_data = "json_data = { \"status\": 0, \"error\": \"report\" };" ;
		}
		else
			$json_data = "json_data = { \"status\": 0, \"error\": \"Invalid PHP Live! URL.  Please try again.\" };" ;

		print $json_data ;
		exit ;
	}
	else if ( $action == "create" )
	{
		$name = preg_replace( "/\"/", "", Util_Format_Sanatize( Util_Format_GetVar( "name" ), "ln" ) ) ;
		$email = Util_Format_Sanatize( Util_Format_GetVar( "email" ), "e" ) ;
		$login = Util_Format_Sanatize( Util_Format_GetVar( "login" ), "ln" ) ;
		$loc = Util_Format_Sanatize( Util_Format_GetVar( "loc" ), "base_url" ) ;
		$now = time() ;

		$login = preg_replace( "/[@\.]/", "", $login ) ;

		if ( !$name || !$email || !$login )
			$json_data = "json_data = { \"status\": 0, \"error\": \"Blank input is invalid.\" };" ;
		else if ( !preg_match( "/@/", $email ) )
			$json_data = "json_data = { \"status\": 0, \"error\": \"Email format is invalid. (example: someone@somewhere.com)\" };" ;
		else
		{
			// http://10.0.0.5/osicodes/website3/phplive_wp.php?action=create&name=$name&email=$email&login=$login&password=$password&loc=$loc&$now
			$tags = get_meta_tags( "http://www.phplivesupport.com/phplive_wp.php?action=create&name=$name&email=$email&login=$login&$now" ) ;
			$description = isset( $tags["description"] ) ? $tags["description"] : "Invalid server data.,,," ;
			LIST( $error, $trial, $login ) = explode( ",", $description ) ;

			if ( !$error && $trial && $login )
				$json_data = "json_data = { \"status\": 1, \"trial\": \"$trial\", \"login\": \"$login\" };" ;
			else if ( $error )
				$json_data = "json_data = { \"status\": 0, \"error\": \"$error\" };" ;
			else
				$json_data = "json_data = { \"status\": 0, \"error\": \"report\" };" ;
		}

		print $json_data ;
		exit ;
	}

	// check for file access
	$php_version = phpversion() ;
	$disabled_functions = ini_get( "disable_functions" ) ;
	$ini_safe_mode = ini_get("safe_mode") ;
	$php_safe_mode = preg_match( "/on/i", $ini_safe_mode ) ? 1 : 0 ;
	$php_fopen = ini_get( "allow_url_fopen" ) ;
	$php_agent = isset( $_SERVER["HTTP_USER_AGENT"] ) ? $_SERVER["HTTP_USER_AGENT"] : "-" ;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
<title> PHP Live! WordPress Plugin </title>

<meta name="description" content="PHP Live!">
<meta name="keywords" content="powered by: PHP Live!  www.phplivesupport.com">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="Stylesheet" href="./libs/css/base_setup.css?<?php echo $version ?>">
<script type="text/javascript" src="./libs/js/global.js?<?php echo $version ?>"></script>
<script type="text/javascript" src="./libs/js/framework.js?<?php echo $version ?>"></script>
<script type="text/javascript" src="./libs/js/framework_cnt.js?<?php echo $version ?>"></script>

<script type="text/javascript">
<!--
	$(document).ready(function()
	{
		toggle_menu() ;
		show_div('trial') ;

		if ( typeof( parent.phplive_wp ) != "undefined" )
			$('body').show() ;
	});

	function toggle_menu( themenu )
	{
		$('#body_sub_title').html( "<img src=\"./libs/pics/icons/phplive.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> PHP Live! Account" ) ;
	}

	function input_text_listen( e )
	{
		var key = -1 ;
		var shift ;

		key = e.keyCode ;
		shift = e.shiftKey ;

		if ( !shift && ( ( key == 13 ) || ( key == 10 ) ) )
			do_login() ;
	}

	function show_div( thediv )
	{
		var divs = Array( "trial", "url", "about", "server" ) ;
		for ( c = 0; c < divs.length; ++c )
		{
			$('#account_'+divs[c]).hide() ;
			$('#menu_'+divs[c]).removeClass('op_submenu_focus').addClass('op_submenu') ;
		}

		$('#account_'+thediv).show() ;
		$('#menu_'+thediv).removeClass('op_submenu').addClass('op_submenu_focus') ;
		$('#btn_trial').attr('disabled', false) ;
		$('#btn_url').attr('disabled', false) ;
	}

	function do_create()
	{
		var json_data = new Object ;
		var unique = unixtime() ;
		var name = encodeURIComponent( $('#name').val() ) ;
		var email = encodeURIComponent( $('#email').val() ) ;
		var login = encodeURIComponent( $('#login').val() ) ;
		var password = encodeURIComponent( $('#password').val() ) ;
		var loc = encodeURIComponent( location.href ) ;

		if ( check_email( $('#email').val() ) )
		{
			$('#btn_trial').attr('disabled', true) ;

			$.ajax({
			type: "POST",
			url: "./phplive_wp.php",
			data: "action=create&name="+name+"&email="+email+"&login="+login+"&password="+password+"&loc="+loc+"&"+unique,
			success: function(data){
				eval( data ) ;

				if ( json_data.status )
					parent.phplive_wp_seturl( "https://"+json_data.trial+".phplivesupport.com/"+json_data.login ) ;
				else
				{
					$('#btn_trial').attr('disabled', false) ;

					if ( json_data.error == "report" )
					{
						show_div('server') ;
						$('#connect_error').show() ;
					}
					else
						do_alert( 0, json_data.error ) ;
				}
			},
			error:function (xhr, ajaxOptions, thrownError){
				do_alert( 0, "Connection to server was lost.  Please reload the page." ) ;
				$('#btn_trial').attr('disabled', false) ;
			} });
		}
		else
			alert( "Email format is invalid. (example: someone@somewhere.com)" ) ;
	}

	function do_verify_url()
	{
		var json_data = new Object ;
		var unique = unixtime() ;
		var phplive_url = encodeURIComponent( $('#phplive_url').val().replace( /index.php/, "" ) ) ;

		$('#btn_url').attr('disabled', true) ;

		$.ajax({
		type: "POST",
		url: "./phplive_wp.php",
		data: "action=verify_url&phplive_url="+phplive_url+"&"+unique,
		success: function(data){
			eval( data ) ;

			if ( json_data.status )
				parent.phplive_wp_seturl( json_data.phplive_url ) ;
			else
			{
				$('#btn_url').attr('disabled', false) ;

				if ( json_data.error == "report" )
				{
					show_div('server') ;
					$('#connect_error').show() ;
				}
				else
					do_alert( 0, json_data.error ) ;
			}
		},
		error:function (xhr, ajaxOptions, thrownError){
			do_alert( 0, "Connection to server was lost.  Please reload the page." ) ;
			$('#btn_url').attr('disabled', false) ;
		} });
	}

//-->
</script>
</head>
<body style="overflow: auto; display: none;">

<div id="body" style="padding-bottom: 60px;">
	<div style="width: 100%; z-Index: 10;">

		<div class="op_submenu_wrapper">
			<div class="op_submenu" onClick="show_div('trial')" id="menu_trial">Create an Account</div>
			<div class="op_submenu" onClick="show_div('url')" id="menu_url">I Already Have a PHP Live! URL</div>
			<div class="op_submenu" onClick="show_div('about')" id="menu_about">About PHP Live!</div>
			<div class="op_submenu" onClick="show_div('server')" id="menu_server">Server Info</div>
			<div style="clear: both"></div>
		</div>

		<form method="POST" action="phplive_wp.php?submit" id="theform">
		<div id="account_trial" style="display: none;">
			<div class="info_info" style="margin-top: 15px;"><div class="info_title_info">PHP Live! enables real-time chat communication with your website visitors.  Create your 10 day FREE trial account and enable live chat for your WordPress.  If you already have a PHP Live! URL, please <a href="JavaScript:void(0)" onClick="show_div('url')">provide the URL here</a>.</div></div>

			<table cellspacing=0 cellpadding=5 border=0 style="margin-top: 25px;">
			<tr>
				<td>Name</td>
				<td> <input type="text" class="input" name="name" id="name" size="30" maxlength="40" value="<?php echo $name ?>" onKeyPress="return noquotestags(event)" onKeyup="input_text_listen(event);"></td>
				<td>Email</td>
				<td> <input type="text" class="input" name="email" id="email" size="30" maxlength="160" value="<?php echo $email ?>" onKeyPress="return justemails(event)" onKeyup="input_text_listen(event);"></td>
			</tr>
			<tr>
				<td>Login</td>
				<td> <input type="text" class="input" name="login" id="login" size="30" maxlength="15" value="" onKeyPress="return nospecials(event)" onKeyup="input_text_listen(event);"></td>
				<td>Password</td>
				<td> <input type="text" class="input" name="password" id="password" size="30" maxlength="35" value="password will be emailed to you" onKeyPress="return noquotes(event)" onKeyup="input_text_listen(event);" disabled></td>
			</tr>
			<tr>
				<td colspan=2><div style="font-size: 10px;">please provide letters and numbers only for the login</div></td>
			</tr>
			<tr>
				<td></td><td colspan=3><div style="margin-top: 10px;"><input type="button" id="btn_trial" class="btn_login" value="Create Trial Account" onClick="do_create()"></div></td>
			</tr>
			<tr>
				<td></td><td colspan=3><div style="padding-top: 25px;"><img src="./libs/pics/info.png" width="16" height="16" border="0" alt=""> <a href="http://www.phplivesupport.com/help_desk.php" target="snew">Frequently Asked Question and Answers (FAQ)</a></div></td>
			</tr>
			</table>
		</div>
		<div id="account_url" style="display: none;">
			<div class="info_info" style="margin-top: 15px;"><div class="info_title_info">If you have an existing Trial Account, On Demand Client Account or the Source Code Download Client Account, simply provide your PHP Live! URL below to link the system with your WordPress.
			<div class="info_box" style="margin-top: 15px;"><b>PHP Live! URL example:</b> <i>http://www.YourDomain.com/phplive</i></div>
			</div></div>

			<table cellspacing=0 cellpadding=5 border=0 style="margin-top: 25px;">
			<tr>
				<td>Your PHP Live! URL</td>
				<td> <input type="text" class="input" name="phplive_url" id="phplive_url" size="50" maxlength="255" value="http://" onKeyPress="return noquotestags(event)" onKeyup="input_text_listen(event);" onFocus="if ( this.value == 'http://' ) { this.value = '' ; }" onBlur="if ( this.value == '' ) { this.value = 'http://' ; }"></td>
			</tr>
			<tr>
				<td></td><td colspan=3><div style="margin-top: 10px;"><input type="button" id="btn_url" class="btn_login" value="Verify" onClick="do_verify_url()"></div></td>
			</tr>
			</table>
		</div>
		<div id="account_about" style="display: none;">
			<div style="margin-top: 15px; margin-bottom: 25px;">
				<div><img src="./libs/pics/logo.png" width="161" height="45" border="0" alt=""></div>
				<div style="margin-top: 5px;">PHP Live! enables live help and live customer support chat communication on your website. Integrate an interactive real-time chat capability and provide one-on-one chat assistance to your website visitors. Live chat is rapidly becoming the website standard and because PHP Live! is 100% web based, if you have a web browser, you can provide live chat assistance directly from your WordPress.
				
				<div style="margin-top: 15px;"><img src="./libs/pics/screen_themes.gif" width="753" height="145" border="0" alt=""></div>
				
				<div style='margin-top: 25px;'>
					<img src="./libs/pics/main_grey.png" width="20" height="10" border="0" alt=""><a href="http://www.phplivesupport.com/?&plk=pi-13-9kt-m" target="new">PHP Live! Home</a>
					<img src="./libs/pics/main_grey.png" width="20" height="10" border="0" alt=""><a href="http://www.phplivesupport.com/features.php?&plk=pi-13-9kt-m" target="new">Features</a>
					<img src="./libs/pics/main_grey.png" width="20" height="10" border="0" alt=""><a href="http://www.phplivesupport.com/pricing.php?&plk=pi-13-9kt-m" target="new">Package Selections</a>
				</div>

				</div>
			</div>
		</div>
		<div id="account_server" style="display: none;">
			<div style="margin-top: 15px; margin-bottom: 25px;">
				<textarea id="server_info" rows="7" style="width: 70%" class="input" onMouseDown="setTimeout(function(){ $('#server_info').select(); }, 200);">PHP Version: <?php echo $php_version ?>

Safe Mode: <?php echo $php_safe_mode ?>

Allow URL open: <?php echo $php_fopen ?>

Disabled Functions: <?php print "$disabled_functions " ; ?>

User Agent: <?php echo $php_agent ?></textarea>

				<?php if ( !$php_fopen ): ?>
				<div class="info_error" style="margin-top: 25px;">
					"allow_url_fopen" is disabled on your server.  In order for the PHP Live! plugin to work properly, this value should be on.  Here is what you'll want to do:
					<ol>
						<li> locate the <b>php.ini</b> file on your web server and find the line that contains <code>allow_url_fopen</code>
						<li> turn on the value so it now reads <code>allow_url_fopen = On</code>
						<li> save the file and restart your web server
					</ol>
				</div>

				<?php else: ?>
				<div id="connect_error" style="display: none; margin-top: 25px;" class="info_error">Could not connect to server.  Contact <a href="mailto:tech@osicodesinc.com" style="color: #FFFFFF;">tech@osicodesinc.com</a> and include the above server values for review or <a href="JavaScript:void(0)" onClick="phplive_launch_chat_0(0)" style="color: #FFFFFF;">chat with us live</a>.</div>
				

				<?php endif ; ?>

				<div style="margin-top: 25px;">PHP Live! plugin not working?  Contact <a href="mailto:tech@osicodesinc.com">tech@osicodesinc.com</a> and include the above server values for review or <a href="JavaScript:void(0)" onClick="phplive_launch_chat_0(0)">chat with us live</a>.</div>
			</div>
		</div>
		</form>
	
		<div style="text-align: right;"><img src="./libs/pics/agent.png" width="16" height="16" border=0> <span style="color: #99A6C6; text-decoration: underline;"><!-- BEGIN PHP Live! code, (c) OSI Codes Inc. --><span id="phplive_btn_1328742422" onClick="phplive_launch_chat_0(0)" style="color: #99A6C6; text-decoration: underline; cursor: pointer;"></span><script type="text/javascript">(function() { var phplive_e_1328742422 = document.createElement("script") ; phplive_e_1328742422.type = "text/javascript" ; phplive_e_1328742422.async = true ; phplive_e_1328742422.src = "https://www.osicodesinc.com/apps/phplive/js/phplive_v2.js.php?q=0|1328742422|2|Click%20for%20Live%20Support" ; document.getElementById("phplive_btn_1328742422").appendChild( phplive_e_1328742422 ) ; })() ;</script><!-- END PHP Live! code, (c) OSI Codes Inc. --></span></div>
	</div>
</div>

</body>
</html>
