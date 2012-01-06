function nospecials(e)
{
	var key;
	var keychar;

	if (window.event)
	key = window.event.keyCode;
	else if (e)
		key = e.which;
	else
		return true;

	keychar = String.fromCharCode(key);
	keychar = keychar.toLowerCase();

	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
		return true;

	else if ((("abcdefghijklmnopqrstuvwxyz0123456789_-").indexOf(keychar) > -1))
		return true;
	else
		return false;
}

function justemails(e)
{
	var key;
	var keychar;

	if (window.event)
	key = window.event.keyCode;
	else if (e)
		key = e.which;
	else
		return true;

	keychar = String.fromCharCode(key);
	keychar = keychar.toLowerCase();

	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
		return true;

	else if ((("abcdefghijklmnopqrstuvwxyz0123456789_-\@\.").indexOf(keychar) > -1))
		return true;
	else
		return false;
}

function noquotes(e)
{
	var key;
	var keychar;

	if (window.event)
	key = window.event.keyCode;
	else if (e)
		key = e.which;
	else
		return true;

	keychar = String.fromCharCode(key);
	keychar = keychar.toLowerCase();

	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
		return true;

	else if ((("\"\'").indexOf(keychar) != -1))
		return false;
	else
		return true;
}

function noquotestags(e)
{
	var key;
	var keychar;

	if (window.event)
	key = window.event.keyCode;
	else if (e)
		key = e.which;
	else
		return true;

	keychar = String.fromCharCode(key);
	keychar = keychar.toLowerCase();

	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
		return true;

	else if ((("\"\'<>").indexOf(keychar) != -1))
		return false;
	else
		return true;
}

function check_email( theemail )
{
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i) ;
	return pattern.test( theemail ) ;
}

function do_alert( theflag, thetext )
{
	var message ;

	var div_exists = $('#login_alert_box').length ;
	if ( div_exists )
		$('#login_alert_box').remove() ;

	if ( theflag )
		message = "<div id=\"login_alert_box\" class=\"info_good\" style=\"display: none; position: absolute; top: 0px; left: 0px; text-align: center; padding: 6px; font-size: 14px; font-weight: bold; z-Index: 200;\">"+thetext+"</div>" ;
	else
		message = "<div id=\"login_alert_box\" class=\"info_error\" style=\"display: none; position: absolute; top: 0px; left: 0px; text-align: center; padding: 6px; font-size: 14px; font-weight: bold; z-Index: 200;\">"+thetext+"</div>" ;

	$('body').append( message ) ;
	$('#login_alert_box').center().show().fadeOut("slow").fadeIn("fast").delay(3000).fadeOut("slow").hide() ;
}

unixtime = function() { return parseInt(new Date().getTime().toString().substring(0, 10)) ; }
function microtime( get_as_float )
{
	var now = new Date().getTime() / 1000 ;
	var s = parseInt(now, 10) ;

	return (get_as_float) ? now : (Math.round((now - s) * 1000) / 1000) + ' ' + s ;
}

function pad( number, length )
{
	var str = '' + number ;
	while ( str.length < length )
		str = '0' + str ;
	return str;
}

