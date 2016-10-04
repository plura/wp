<?php
/**
 *
 * Plura Admin 2000-2006 (c) Plura
 *
 * @author	Plura	geral@plura.pt
 * @version 1.00
 */
class Template {



	public $vars;



	public function __construct(){}
	

	
	public static function replace_var($var, $template_vars, $separator){
		
		foreach ($template_vars as $key => $val) {
			
			$var = str_replace('::' . $key . '::', $val, $var);
		
		}
		
		return $var;
	
	}
	
	
	
   /**
	* changes relative urls to absolutes urls.
	* @param	string	 	path to template file
	* @param	array		array of template vars to substitute 'dynamic' keywords in template file
	* @param	string 		separator that indicates 'dynamic' keywords in template
	* @param	mixed		if true, turns relative links to absolute. If a string is given instead, a folder is 
	*						added to the absolut path
	*/
	public static function replace_file($path, $template_vars, $separator, $relative2absolute=false){
		
		$content = self::getContent($path);

		foreach ($template_vars as $key => $val) {
			
			$content = str_replace('::' . $key . '::', $val, $content);
		
		}
		
		if(is_string($relative2absolute)) {
			
			return self::relative2absolute($content, $relative2absolute);
			
		} elseif($relative2absolute) {
			
			return self::relative2absolute($content);
			
		}
		
		return $content;
	}
	
	
	
	public static function getContent($path){
		
		$fd = fopen($path, 'r');
		
		$content = fread( $fd, filesize($path));
		
		fclose($fd);
		
		return $content;
	
	}
		
	
   /**
	* changes relative urls to absolutes urls.
	* @param	string	 	string to have its links replaced
	* @param	string		string to add a specific folder to the absolute string
	* @param	string 		attribute types targeted
	*/
	public static function relative2absolute($string, $folder=NULL, $types='src|background'){
		
		$domain = "http://" . $_SERVER['HTTP_HOST'] .  substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], "/")+1);

		if(!is_null($folder)) {
			
			$domain .= $folder;
			
		}

		return preg_replace('#(' . $types . ')="([^:"]*)("|(?:(?:%20|\s|\+)[^"]*"))#','$1="' . $domain . '/$2$3', $string);
	}
}
?>