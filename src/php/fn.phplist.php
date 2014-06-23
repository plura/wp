<?php
/**
* API to subscribe a user to PHPList mailinlist
* the data parameter will look:
*
*	$data = array(
*		'email'			=>  email@domain.com,
*		'attribute1'	=>	My Name				the atribute id [ie. attribute1, attribute2..] required [ie. Name].
*		'lists'			=> '1,2'				string of list ids separated by commas
*	);\
*
* @param	data	array with post data
* @param	data	array with phplist configuration details [admin log, admin pass, phplist url]
*/
function phplist_add($data=NULL, $config=NULL){
	
	$config_defaults = array(
		'phplist_user'			=> "admin",
		'phplist_pass'			=> "phplist",
		'phplist_url'			=> 'http://' . $_SERVER['HTTP_HOST'] . '/news/',
		'msg_success'			=> "Thank you for subscribing to our mailing list.",
		'msg_error'				=> NULL,	//if !in_null becomes the only error message returned
		'msg_error_add'			=> "Error adding user details in mailinglist database.",
		'msg_error_curl'		=> "CURL library not detected on system. Need to compile php with cURL in order to use this plug-in",
		'msg_error_data_null'	=> "No data was given.",
		'msg_error_data_type'	=> "Error data type.",
		'msg_error_list_null'	=> "No subscription lists were given.",	
		'msg_error_mail_null'	=> "Error mail.",	
		'msg_error_signin'		=> "Error loging in mailinglist admin."
	);
	
	$data_defaults	= array(
		'htmlemail'				=> "1",				//default html email
		'makeconfirmed'			=> "1",				//If set to 1 it will confirm user bypassing confirmation email 
		'subscribe'				=> "Subscribe"
	);
	
	$return_msg	= "";
	
	$config		= !is_null($config)? array_merge($config_defaults, $config) : $config_defaults;
	
	if(!function_exists('curl_exec')){
		$return_msg	.= is_null($config['msg_error'])? $config['msg_error_curl'] 	: $config['msg_error'];
	} else if(is_null($data)){ 
		$return_msg .= is_null($config['msg_error'])? $config['msg_error_data_null']: $config['msg_error'];
	} else if(!is_array($data)){ 
		$return_msg .= is_null($config['msg_error'])? $config['msg_error_data_type']: $config['msg_error'];	
	} else if(empty($data['email'])){
		$return_msg .= is_null($config['msg_error'])? $config['msg_error_mail_null']: $config['msg_error'];
	} else if(!isset($data['lists'])){
		$return_msg .= is_null($config['msg_error'])? $config['msg_error_list_null']: $config['msg_error'];
	} else {	
	
		//Login to phplist as admin and save cookie using CURLOPT_COOKIEFILE 
		//NOTE: Must log in as admin in order to bypass email confirmation
		$url 						= $config['phplist_url'] . "admin/?"; 
		$ch							= curl_init(); 
		$login_data 				= array(); 
		$login_data["login"]		= $config['phplist_user']; 
		$login_data["password"]		= $config['phplist_pass']; 
		curl_setopt($ch, CURLOPT_POST, 				1); 
		curl_setopt($ch, CURLOPT_URL, 				$url);    
		curl_setopt($ch, CURLOPT_POSTFIELDS, 		$login_data); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,	1); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, 		""); //Enable Cookie Parser.  
   
		//File does not need to exist - http://curl.netmirror.org/libcurl/c/libcurl-tutorial.html for more info 
		$result						= curl_exec($ch); 
		//echo("Result was: $result"); //debug 
		if(curl_errno($ch)){ 
			$return_msg .= $data['msg_error_signin']; 
		} else { 
		
			$post_data = array_merge($data_defaults, $data);
		
			foreach(explode(',', $data['lists']) as $key=>$value){
				$k 				= "list[" . $value . "]";
				$post_data[$k]	= "signup";
			}

			//simulate post to subscriber form.
			$post_data["emailconfirm"]	= $data['email'];
			$url 						= $config['phplist_url'] . "?p=subscribe"; 

			curl_setopt($ch, CURLOPT_POST, 				1); 
			curl_setopt($ch, CURLOPT_URL, 				$url);    
			curl_setopt($ch, CURLOPT_POSTFIELDS, 		$post_data); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,	1); 
			$result = curl_exec($ch); 

			//echo('Result was: ' .$result); 
			if(curl_errno($ch)){ 
				$return_msg .= $config['msg_error_add']; 
			} else {
				$return_msg .= $config['msg_success'];		
			}
		}

		//) Clean up 
		curl_close($ch);
	}

	return $return_msg;
}
?>
