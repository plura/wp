<?php

function pwp_args( $defaults , $args = false ) {

	if ( is_array( $args ) ) {

		return array_merge( $defaults, $args );

	}

	return $defaults;

}



function pwp_currentURL() {
   
    $currentURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
   
    $currentURL .= $_SERVER["SERVER_NAME"];
 
    if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
    
        $currentURL .= ":".$_SERVER["SERVER_PORT"];
    
    } 

    return $currentURL .= $_SERVER["REQUEST_URI"];
}



function pwp_trans( $content, $lang = NULL, $default = NULL ) {

    if (!function_exists('qtranxf_use')) {

        return $content;

    }

    if ( is_null( $lang ) ) {

        $lang = qtranxf_getLanguage();

    }

    return qtranxf_use( $lang, $content, false);

}

?>