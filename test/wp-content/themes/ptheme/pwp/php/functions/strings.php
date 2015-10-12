<?php


//formats url string to html
function pwp_string2html($string) {

	return nl2br( htmlentities( $string, ENT_QUOTES, "UTF-8" ) );

}



function pwp_label($label, $lang = false) {

	if (isset($GLOBALS['labels'])) {

		$labels = $GLOBALS['labels'];

		if (is_array($labels) && array_key_exists($label, $labels) && array_key_exists($lang, $labels[$label])) {

			return $labels[$label][$lang];

		}

	}

	return NULL;

};


function pwp_label2html($label, $lang=false) {

	$l = pwp_label($label, $lang);

	if (!is_null($l)) {

		return pwp_string2html( $l );

	}

	return $l;

}




















?>