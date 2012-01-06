<?php
	function Util_Format_Sanatize( $string, $flag )
	{
		switch ( $flag )
		{
			case ( "a" ):
				return ( is_array( $string ) ) ? $string : Array() ;
				break ;
			case ( "n" ):
				return preg_replace( "/[^0-9]/i", "", trim( $string, "\x00" ) ) ;
				break ;
			case ( "ln" ):
				return preg_replace( "/[\$%=<>\(\)\[\]\|\{\}]/i", "", trim( $string, "\x00" ) ) ;
				break ;
			case ( "e" ):
				return preg_replace( "/[^a-z0-9_.\-@]/i", "", Util_Format_Trim( trim( $string, "\x00" ) ) ) ;
				break ;
			case ( "v" ):
				return preg_replace( "/( )|(%20)|(%00)|(%3Cv%3E)|(<v>)/", "", Util_Format_Trim( trim( $string, "\x00" ) ) ) ;
				break ;
			case ( "base_url" ):
				return preg_replace( "/[\$\!`\"<>'\(\)\?;]/i", "", Util_Format_Trim( preg_replace( "/hphp/i", "http", trim( $string, "\x00" ) ) ) ) ;
				break ;
			case ( "url" ):
				return preg_replace( "/[\$\!`\"<>'\(\); ]/i", "", Util_Format_Trim( preg_replace( "/hphp/i", "http", trim( $string, "\x00" ) ) ) ) ;
				break ;
			case ( "title" ):
				return preg_replace( "/[\$=\!<>;]/i", "", Util_Format_Trim( preg_replace( "/hphp/i", "http", trim( $string, "\x00" ) ) ) ) ;
				break ;
			case ( "htmltags" ):
				return Util_Format_ConvertTags( trim( $string, "\x00" ) ) ;
				break ;
			case ( "notags" ):
				return strip_tags( trim( $string, "\x00" ) ) ;
				break ;
			case ( "htmle" ):
				return htmlentities( trim( $string, "\x00" ) ) ;
				break ;
			default:
			{
				return trim( $string, "\x00" ) ;
			}
		}
	}

	function Util_Format_Trim( $string )
	{
		$string = preg_replace( "/(\r\n)|(\r)|(\n)/", "", $string ) ; 
		return $string ;
	}

	function Util_Format_ConvertTags( $string )
	{
		$string = preg_replace( "/</", "&lt;", $string ) ; 
		$string = preg_replace( "/>/", "&gt;", $string ) ;
		return $string ;
	}

	function Util_Format_ConvertQuotes( $string )
	{
		$string = preg_replace( "/\"/", "&quot;", $string ) ; 
		$string = preg_replace( "/'/", "&#39;", $string ) ;
		return $string ;
	}

	function Util_Format_GetVar( $varname )
	{
		$varout = 0 ;

		// is_string

		if ( isset( $_POST[$varname] ) )
			$varout = $_POST[$varname] ;
		else if ( isset( $_GET[$varname] ) )
			$varout = $_GET[$varname] ;

		if ( get_magic_quotes_gpc() && !is_array( $varout ) )
			$varout = stripslashes( $varout ) ;
		return $varout ;
	}

?>