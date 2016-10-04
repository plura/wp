<?php

if ( isset( $_REQUEST['data'] ) && !empty( $_REQUEST['data'] ) ) {


	$return = array();


	foreach($_REQUEST['data'] as $img) {

		$a	 = pwp_image_info( $img['url'] );
		
		if($_REQUEST['size'] == 'large' && isset( $a['sizes']['large'] ) ) {

		
			$f = $a['sizes']['large']['file'];
		

		} elseif(($_REQUEST['size'] == 'medium' && isset($a['sizes']['medium'])) || ($_REQUEST['size'] == 'large' && !isset($a['sizes']['large']))){
		

			$f = $a['sizes']['medium']['file'];
		

		} elseif( $_REQUEST['size'] == 'thumbnail' ) {
		

			$f = $a['sizes']['thumbnail']['file'];
		

		} else {
		

			$f = $a['file'];//preg_replace("/^.*\/(.*)$/","$1", $a['file']);
		

		}
		

		$url = preg_replace("/(^.*\/)(.*)$/", "$1", $img['url']) . $f;


		$return[] = array("id" => $img['id'], "url" => $url);


	}

}

?>