<?php
class Mail {

	public $toName			= "";
	public $toMail;

	
	public $fromName		= "";
	public $fromMail;
	public $subject;
	public $body;

	public $reSubject; 					//reply subject
	public $reBody; 					//reply body

	public $attachments 	= NULL;
	
	public $html;						//text/html
	public $plain;						//text/plain



	public function __construct(){}


	
   /**
	* mail sender
	*/
	public function send(){
	
		return $this->email($this->toMail, $this->toName, $this->fromName, $this->fromMail, $this->subject, $this->body, $this->attachments);

	}
	
	
	
   /**
	* auto-responder
	*/	
	public function receive(){
		
		return $this->email($this->toName, $this->toMail, $this->fromMail, $this->subject, $this->reBody);

	}
	

	
	//function send_template_mail($fromname, $frommail, $tomail, $subject, $message_url, $template_vars, $attachments=NULL){


	// !! some lines need TWO end of lines [$eol.$eol] !! IMPORTANT !!
	public static function email($tomail, $toname, $fromname, $frommail, $subject, $body, $files=NULL){

		//boundaries
		$mime_boundary_mix = "mix-" . md5(uniqid(time())); //boundary for the mixed part [attachments]
		$mime_boundary_alt = "alt-" . md5(uniqid(time())); //boundary for the alternative part [html/text]

		if(strtoupper(substr(PHP_OS,0,3)=='WIN')) {
			
			$eol = "\r\n"; 
		
		} elseif(strtoupper(substr(PHP_OS,0,3)=='MAC')) {
			
			$eol = "\r";
			
		} else {
			
			$eol = "\n";
			
		}

		//headers
		$headers  = "From: $fromname <$frommail>"												. $eol;
		$headers .= "Reply-To: <$frommail>"														. $eol;
		$headers .= "MIME-Version: 1.0"															. $eol;
		$headers .= "Content-Type: multipart/mixed; boundary=\"{$mime_boundary_mix}\""			. $eol;

		//message
		$message  =	"--{$mime_boundary_mix}"													. $eol .
					"Content-Type: multipart/alternative; boundary=\"{$mime_boundary_alt}\""	. $eol . $eol .
				
					"--{$mime_boundary_alt}"													. $eol . //plain text
					"Content-Type: text/plain; charset=iso-8859-1"								. $eol .
					"Content-Transfer-Encoding: 7bit"											. $eol . $eol .
					strip_tags($body) 															. $eol . $eol .
				
					"--{$mime_boundary_alt}"													. $eol . //html
					"Content-Type: text/html; charset=iso-8859-1"								. $eol .
					"Content-Transfer-Encoding: 7bit"											. $eol . $eol .
					$body 																		. $eol . $eol .
				
					"--{$mime_boundary_alt}--" . $eol . $eol;									//close alternative text/html content


		if(!is_null($files)){
			
			if(is_string($files)) {								//turns string to file info array
				
				$files = array('name' => basename($files), 'tmp_name' => $files, 'type' => self::pmime_content_type($files));
			
			}
			
			if(!is_array($files[key($files)])) {					//turn a single file into an array 4 loop purposes
				
				$files = array($files);				
		
			}
		
			foreach($files as $file){	

				if(file_exists($file['tmp_name']) /*&& is_uploaded_file($file['tmp_name'])*/){			
					
					$f 		= fopen($file['tmp_name'], 'rb');
					$data 	= fread($f, filesize($file['tmp_name']));
					fclose($f);
					$data	= chunk_split(base64_encode($data));

					$message .= "--{$mime_boundary_mix}"										. $eol;
					$message .= "Content-Type: {$file['type']}; name=\"{$file['name']}\""		. $eol;
					$message .= "Content-Transfer-Encoding: base64"								. $eol;
					$message .= "Content-Disposition: attachment; filename=\"{$file['name']}\""	. $eol . $eol;
					$message .= $data															. $eol . $eol;
				
				}
			
			}
		
		}
		
		
		$message .= "--{$mime_boundary_mix}--".$eol.$eol;
	
		
		return mail($tomail, $subject, $message, $headers);
	
	}
	
	
	
	public static function pmime_content_type($filename) {

		$mime_types = array(

			'txt'	=> 'text/plain',
			'htm'	=> 'text/html',
			'html'	=> 'text/html',
			'php'	=> 'text/html',
			'css'	=> 'text/css',
			'js'	=> 'application/javascript',
			'json'	=> 'application/json',
			'xml'	=> 'application/xml',
			'swf'	=> 'application/x-shockwave-flash',
			'flv'	=> 'video/x-flv',

			// images
			'png'	=> 'image/png',
			'jpe'	=> 'image/jpeg',
			'jpeg'	=> 'image/jpeg',
			'jpg'	=> 'image/jpeg',
			'gif'	=> 'image/gif',
			'bmp'	=> 'image/bmp',
			'ico'	=> 'image/vnd.microsoft.icon',
			'tiff'	=> 'image/tiff',
			'tif'	=> 'image/tiff',
			'svg'	=> 'image/svg+xml',
			'svgz'	=> 'image/svg+xml',

			// archives
			'zip' 	=> 'application/zip',
			'rar'	=> 'application/x-rar-compressed',
			'exe' 	=> 'application/x-msdownload',
			'msi' 	=> 'application/x-msdownload',
			'cab' 	=> 'application/vnd.ms-cab-compressed',

			// audio/video
			'mp3' 	=> 'audio/mpeg',
			'qt' 	=> 'video/quicktime',
			'mov' 	=> 'video/quicktime',

			// adobe
			'pdf' 	=> 'application/pdf',
			'psd' 	=> 'image/vnd.adobe.photoshop',
			'ai' 	=> 'application/postscript',
			'eps' 	=> 'application/postscript',
			'ps'	=> 'application/postscript',

			// ms office
			'doc' => 'application/msword',
			'rtf' => 'application/rtf',
			'xls' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',

			// open office
			'odt' => 'application/vnd.oasis.opendocument.text',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		);

		$ext = strtolower(array_pop(explode('.',$filename)));
	
		if (array_key_exists($ext, $mime_types)) {
			return $mime_types[$ext];
		} elseif(function_exists('finfo_open')) {
			$finfo 		= finfo_open(FILEINFO_MIME);
			$mimetype 	= finfo_file($finfo, $filename);
			finfo_close($finfo);
			return $mimetype;
		} else {
			return 'application/octet-stream';
		}
	}	
	
	
   /**
	* For non uploaded files
	* @path		string		filepath
	*/
	/*function format_file_info($file){
		$tmp	= explode('/', $file);
		$name	= $name[count($name)-1];
		
		$tmp	= finfo_open(FILEINFO_MIME);
		$mime	= finfo_file($tmp, $file);
		finfo_close($tmp);
	
		$f = array(
			'name'		=> $name,
			'tmp_name'	=> $file,
			'type'		=> $mime
		);
		
		return $f;
	}*/
	
	
	
   /** 
	* Encodes string to  ISO88591 [ie. header's special characters encoding eg. Tiago Simões]
	* @string		string to be used in function
	*/
	public static function encode_iso88591($string){
		$text = '=?iso-8859-1?q?'; 
		
		for($i = 0 ; $i < strlen($string) ; $i++ ){ 
    		$val = ord($string[$i]); 
			$val = dechex($val); 
			$text .= '='.$val; 
		
		} 
		
		$text .= '?='; 
		
		return $text;
	
	}	
}
?>