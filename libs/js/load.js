unixtime = function() { return parseInt(new Date().getTime().toString().substring(0, 10)) ; }

function phplive_wp_check_email( theemail )
{
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i) ;
	return pattern.test( theemail ) ;
}

function phplive_wp_iframe_resize()
{
	var iframe_height = jQuery('body').height() - 165 ;
	jQuery('#iframe_phplive_url').css({'height': iframe_height}) ;
	jQuery('#iframe_phplive_url_start').css({'height': iframe_height}) ;
	jQuery('#iframe_phplive_url_about').css({'height': iframe_height}) ;
	jQuery('#iframe_phplive_url_setup').css({'height': iframe_height}) ;
}

function phplive_wp_seturl( theurl )
{
	var unique = unixtime() ;
	var data = {
		action: 'my_action',
		action_sub: 'set_url',
		phplive_url: theurl
	} ;

	jQuery.post( ajaxurl, data, function(response) {
		eval( response ) ;

		if ( json_data.status )
			parent.location.href = parent.location.href+"&"+unique ;
		else
			alert( json_data.error ) ;
	});
}

function phplive_wp_sethtml()
{
	var htmlcode = jQuery('#phplive_html_code').val() ;
	var htmlcode = escape( htmlcode.replace( /\+/g, "%plus%" ) ) ;
	//var showhide = ( jQuery('#phplive_url_show').attr( "checked" ) ) ? "show" : "hide" ;
	var showhide = "show" ;

	if ( htmlcode.indexOf( "phplive" ) != -1 )
	{
		var data = {
			action: 'my_action',
			action_sub: 'set_html',
			phplive_html_code: htmlcode,
			phplive_url_showhide: showhide
		} ;

		jQuery.post( ajaxurl, data, function(response) {
			eval( response ) ;

			if ( json_data.status )
				jQuery('#div_alert').show().fadeOut("slow").fadeIn("fast").delay(3000).fadeOut("slow").hide() ;
			else
				alert( json_data.error ) ;
		});
	}
	else
	{
		if ( htmlcode )
			alert( "Invalid HTML Code format.  Please copy the code exactly from the PHP Live! Setup." ) ;
		else
			alert( "Blank HTML Code is invalid." ) ;
	}
}

function phplive_wp_reset()
{
	var unique = unixtime() ;
	var data = {
		action: 'my_action',
		action_sub: 'reset',
	} ;

	if ( confirm( "Reset PHP Live! plugin?" ) )
	{
		jQuery.post( ajaxurl, data, function(response) {
			eval( response ) ;

			if ( json_data.status )
				location.href = location.href+"&"+unique ;
			else
				alert( json_data.error ) ;
		});
	}
}


function phplive_wp_launch( thediv )
{
	var divs = Array( "setup", "op", "html", "settings", "about", "start" ) ;
	for ( c = 0; c < divs.length; ++c )
		jQuery('#menu_'+divs[c]).removeClass('phplive_wp_menu_focus').addClass('phplive_wp_menu') ;

	jQuery('#menu_'+thediv).removeClass('phplive_wp_menu').addClass('phplive_wp_menu_focus') ;

	jQuery('#phplive_welcome').hide() ;
	jQuery('#div_alert').hide() ;
	if ( thediv == "html" )
	{
		jQuery('#phplive_setup_body_start').hide() ;
		jQuery('#phplive_setup_body_iframe').hide() ;
		jQuery('#phplive_setup_body_settings').hide() ;
		jQuery('#phplive_setup_body_about').hide() ;
		jQuery('#phplive_setup_body_html').show() ;
	}
	else if ( thediv == "settings" )
	{
		jQuery('#phplive_setup_body_start').hide() ;
		jQuery('#phplive_setup_body_iframe').hide() ;
		jQuery('#phplive_setup_body_html').hide() ;
		jQuery('#phplive_setup_body_about').hide() ;
		jQuery('#phplive_setup_body_settings').show() ;
	}
	else if ( thediv == "about" )
	{
		jQuery('#phplive_setup_body_start').hide() ;
		jQuery('#phplive_setup_body_iframe').hide() ;
		jQuery('#phplive_setup_body_html').hide() ;
		jQuery('#phplive_setup_body_settings').hide() ;
		jQuery('#phplive_setup_body_about').show() ;
	}
	else if ( thediv == "start" )
	{
		if ( !jQuery('#iframe_phplive_url_start').outerWidth() )
			phplive_wp_launch( "" ) ;
		else
		{
			jQuery('#phplive_setup_body_iframe').hide() ;
			jQuery('#phplive_setup_body_html').hide() ;
			jQuery('#phplive_setup_body_settings').hide() ;
			jQuery('#phplive_setup_body_about').hide() ;
			jQuery('#phplive_setup_body_start').show() ;
		}
	}
	else
	{
		jQuery('#phplive_setup_body_start').hide() ;
		jQuery('#phplive_setup_body_html').hide() ;
		jQuery('#phplive_setup_body_settings').hide() ;
		jQuery('#phplive_setup_body_about').hide() ;

		if ( thediv == "setup" )
			jQuery('#iframe_phplive_url_setup').show() ;

		jQuery('#phplive_setup_body_iframe').show() ;
	}
}

(function() {
	phplive_wp_iframe_resize() ;
	jQuery(window).resize(function() { phplive_wp_iframe_resize() ; });

	if ( window.outerWidth != screen.width )
	{
	}
	phplive_wp_launch( "start" ) ;
})();
