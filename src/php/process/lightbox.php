<?php

/*$json = '[{"id":"lightbox_0_0","url":"http://localhost/dev/2009/socgeografialisboa/site/wp/wp-content/uploads/2010/06/1.jpg"},{"id":"lightbox_0_1","url":"http://localhost/dev/2009/socgeografialisboa/site/wp/wp-content/uploads/2010/06/1a.jpg"},{"id":"lightbox_0_2","url":"http://localhost/dev/2009/socgeografialisboa/site/wp/wp-content/uploads/2010/06/2.jpg"},{"id":"lightbox_0_3","url":"http://localhost/dev/2009/socgeografialisboa/site/wp/wp-content/uploads/2010/06/3.jpg"},{"id":"lightbox_0_4","url":"http://localhost/dev/2009/socgeografialisboa/site/wp/wp-content/uploads/2010/06/4.jpg"},{"id":"lightbox_0_5","url":"http://localhost/dev/2009/socgeografialisboa/site/wp/wp-content/uploads/2010/06/5.jpg"},{"id":"lightbox_0_6","url":"http://localhost/dev/2009/socgeografialisboa/site/wp/wp-content/uploads/2010/06/6.jpg"},{"id":"lightbox_0_7","url":"http://localhost/dev/2009/socgeografialisboa/site/wp/wp-content/uploads/2010/06/7.jpg"},{"id":"lightbox_0_8","url":"http://localhost/dev/2009/socgeografialisboa/site/wp/wp-content/uploads/2010/06/8.jpg"},{"id":"lightbox_0_9","url":"http://localhost/dev/2009/socgeografialisboa/site/wp/wp-content/uploads/2010/06/9.jpg"}]';
*/	//$json = '[{"id":"lightbox_0_0","url":"http://www.socgeografialisboa.pt/wp/wp-content/uploads/2009/04/49-tsglp124fig_49-150x150.jpg"}]';
//$size = 'medium';
	

$images 	= json_decode(stripslashes($json), true);


foreach($images as $img) {

	$a	 = pwp_image_info($img['url']);
	
	if($size == 'large' && isset($a['sizes']['large'])){

	
		$f = $a['sizes']['large']['file'];
	

	} elseif(($size == 'medium' && isset($a['sizes']['medium'])) || ($size == 'large' && !isset($a['sizes']['large']))){
	

		$f = $a['sizes']['medium']['file'];
	

	} elseif($size == 'thumbnail'){
	

		$f = $a['sizes']['thumbnail']['file'];
	

	} else {
	

		$f = $a['file'];//preg_replace("/^.*\/(.*)$/","$1", $a['file']);
	

	}
	

	$url = preg_replace("/(^.*\/)(.*)$/", "$1", $img['url']) . $f;


	$b[] = array("id"=>$img['id'],"url"=>$url);


}

echo json_encode($b);




?>