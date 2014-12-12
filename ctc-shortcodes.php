<?php
/*
    Plugin Name: CTC Shortcodes
    Description: Plugin to display Church Theme Content within your theme using shortcodes. Requires <strong>Church Theme Content</strong> plugin.
    Version: 1.0
    Author: Justin R. Serrano
*/

// No direct access
if ( !defined( 'ABSPATH' ) ) exit;

require_once( sprintf( "%s/ctc-shortcodes-class.php", dirname(__FILE__) ) );

if( class_exists( 'CTC_Shortcodes' ) ) {
	new CTC_Shortcodes();
}

?>