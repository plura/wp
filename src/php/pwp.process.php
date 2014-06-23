<?php
foreach($_REQUEST as $key => $value) $$key = $value;
define('PATH', preg_replace(array("/^(.*\/.*)?\/(wp-content.*)\/(.*)$/", "/([A-Za-z0-9-_])*/"), array("$2", "."), $_SERVER['PHP_SELF']));

include_once('fn.php');
include_once('fn.phplist.php');
include_once('pwp.fn.php');
if(file_exists('../../_content/vars.php')) include_once('../../_content/vars.php');

if(!isset($_GET['type'])){ print "no type!"; exit; }


switch($_GET['type']){
	
	case 'contacts':
	case 'phplist':
		
		$tomail			= isset($tomail)? $tomail : (isset($pwppro_mail)? $pwppro_mail : 'info@' . $_SERVER['HTTP_HOST']);
		$toname			= isset($toname)? $toname : (isset($pwppro_mail_name)? $pwppro_mail_name : $_SERVER['HTTP_HOST']);
		$tosubj			= isset($tosubj)? $tosubj : (isset($pwppro_mail_subj)? $pwppro_mail_subj : $_SERVER['HTTP_HOST'] . ': CONTACT FORM');
	
		$phplist_data	= array(
			'email'			=> $email,
			'attribute1'	=> $name,
			'lists'			=> isset($list)? $list : (isset($pwppro_phplist_list)? $pwppro_phplist_list : '1')
		);

		$phplist_config = array(
			'phplist_url'	=> isset($pwppro_phplist_url)? $pwppro_phplist_url : 'http://' . $_SERVER['HTTP_HOST'] . '/news/',
			'msg_success'	=> "$name, o seu email foi inserido com sucesso na nossa mailinglist. Obrigado."
		);
	
		break;
	
	case 'content':
	case 'gallery':
	case 'lightbox':
	case 'posts':
		//this file is included here because it conflicts with the $name var used above
		//[turns it into a permalink ie. Tiago Simões to tiago-simoes !]
		include_once(PATH . "/wp-load.php");
}
		


switch($type){
	
	
	case 'contacts':
	
		$body 		= "
			<b>NOME:</b> " 		. utf8_decode(urldecode($_GET['name'])) . "
			<b>EMAIL:</b> "  	. $_GET['email']						. "
			<b>MESSAGE:</b> "	. utf8_decode(urldecode($_GET['message']));

		$result 	= pmail($tomail, $toname, $_GET['email'], $_GET['name'], $tosubj, nl2br($body));
		
		if($result){

			$r = array("success"=>'true', "alert"=> "<b>$name</b>, a sua mensagem foi enviada com sucesso.");
		
		} else {
			
			$r = array("success"=>'false', "alert"=> "<b>$name</b>, ouve um erro no envio da sua mensagem.");

		}
		
		if (isset($_GET['mailinglist']) && $result) {
			
			$r['alert'] .= ' ' . phplist_add($phplist_data, $phplist_config);
		
		}

		echo json_encode($r);
		
		break;



	case 'content':
	
		if(!isset($id)) $id = 2;
	
		$q 		= new WP_Query("page_id=$id");
	
		$p 		= $q->get_queried_object();

		echo '{"content":'. json_encode(wpautop($p->post_content)) . '}';	
		
		break;



	case 'phplist':
		
		$r = phplist_add($phplist_data, $phplist_config);
		
		echo $r;
		
		break;
		
		

	case 'posts':
	
		//$query = 'tag=top&numberposts=3orderby=modified';
		//$query = 'category=101&numberposts=3&orderby=modified';
		//$query = "posts_per_page=6&post_type=models";

		if(function_exists('json_encode'))	{
			
			echo json_encode(pwp_ref($query));
			
		} else {
			
			echo parray2json(pwp_ref($query));
			
		}
		
		break;
		
		
	
	case 'gallery':
	
		$files	= get_children('post_type=attachment&post_mime_type=image&post_parent=' . $_GET['id']);
	
		$a		= array();
	
		foreach($files as $file){
		
			$img			= pwp_image_info($file->ID);
			
			$img['caption']	= $file->post_excerpt;
		
			array_push($a, $img);
		
		}
	
		echo json_encode($a);
		
		break;
	
	
	case 'lightbox':
	
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
		
		break;
}
?>