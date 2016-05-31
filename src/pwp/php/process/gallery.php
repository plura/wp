<?php


$files	= get_children('post_type=attachment&post_mime_type=image&post_parent=' . $_GET['id']);

$return	= array();

foreach($files as $file){

	$img			= pwp_image_info($file->ID);
	
	$img['caption']	= $file->post_excerpt;

	array_push($return, $img);

}

?>