<?php
//foreach($_REQUEST as $key => $value) $$key = $value;
//define('PATH', preg_replace(array("/^(.*\/.*)?\/(wp-content.*)\/(.*)$/", "/([A-Za-z0-9-_])*/"), array("$2", "."), $_SERVER['PHP_SELF']));

/*$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );

define( 'PATH', $parse_uri[0] . 'wp-load.php' );*/

define( 'PATH', explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] )[0] );

include_once(PATH . "wp-load.php");

include_once('fn.php');
include_once('functions/phplist.php');
include_once('functions/wp.php');

if ( file_exists( PATH . "wp-content/plugins/qtranslate-x/qtranslate.php") ) {

	include_once(PATH . "wp-content/plugins/qtranslate-x/qtranslate.php");

}

if(file_exists('../../_content/vars.php')) include_once('../../_content/vars.php');


if(!isset($_REQUEST['type'])) { 

	print "no type!";

	exit; 

}


switch($_REQUEST['type']){
	
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
		//include_once(PATH . "wp-load.php");
}




switch( $_REQUEST['type'] ) {
	
	
	case 'contacts':
	
		include("process/contacts.php");	
		
		break;
		

	case 'content':
	
		include("process/content.php");	
		
		break;


	case 'phplist':
		
		include("process/posts.php");
		
		break;
		

	case 'posts':
	
		include("process/posts.php");
		
		break;
		
	
	case 'gallery':
	
		include("process/gallery.php");
		
		break;
	
	
	case 'lightbox':
	
		include("process/lightbox.php");
		
		break;

}


if ($return && is_array( $return ) ) {
	
	echo json_encode($return, JSON_NUMERIC_CHECK);
	
}

?>