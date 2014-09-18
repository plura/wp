<?php


function pwp_image_path($data, $size = NULL) {
	
	$uploads = wp_upload_dir();			
	
	if($size === 'large' && isset($a['sizes']['large'])){
			
		$f = $a['sizes']['large']['file'];
			
	} elseif(($size === 'medium' && isset($data['sizes']['medium'])) || ($size === 'large' && !isset($data['sizes']['large']))){
			
		$f = $data['sizes']['medium']['file'];
			
	} elseif($size === 'thumbnail' || ($size === 'medium' && !isset($data['sizes']['medium'])) || ($size === 'large' && !isset($data['sizes']['large']))){
			
		$f = $data['sizes']['thumbnail']['file'];
			
	}	
	
	if (isset($f)) {
		
		return $uploads['baseurl'] . '/' . preg_replace('/[^\/]+$/', '', $data['file']) . $f;		
		
	}
	
	return $uploads['baseurl'] . '/' . $data['file'];
	
}


?>