<?php
class Mail {

	public $toName			= "";
	public $toMail;
	
	public $fromName		= "";
	public $fromMail;

	public $replyTo;
	
	public $subject;
	public $body;

	public $files 			= NULL;
	
	public $eol				= PHP_EOL;


	public function __construct(){}


	
   /**
	* mail sender
	*/
	public function send(){
	
		return $this->email($this->toMail, $this->toName, $this->fromMail, $this->fromName, $this->subject, $this->body, array(
			'eol'		=> $this->eol,
			'files' 	=> $this->files,
			'reply_to'	=> $this->replyTo		
		));

	}
	
	
	
   /**
	* auto-responder
	*/	
	public function reverse( $args = NULL ){

		$subject	= $args && $args['subject'] ? $args['subject'] : $this->subject;

		$body		= $args && $args['body'] ? $args['body'] : $this->body;

		$to_mail	= $this->replyTo ? $this->replyTo : $this->fromMail;		
		
		return $this->email($to_mail, $this->fromName, $this->toMail, $this->toName, $subject, $body, $args);

	}

	

	// !! some lines need TWO end of lines [$eol.$eol] !! IMPORTANT !!
	public static function email($tomail, $toname, $frommail, $fromname, $subject, $body, $args = NULL){

		$defaults = array(
			'encoding'	=> 'iso-8859-1',
			'eol'		=> "\n",//PHP_EOL,
			'files'		=> false,			
			'from_mail'	=> $frommail,
			'from_name'	=> $fromname,
			'reply_to'	=> false,			
			'subject'	=> $subject,
			'to_mail'	=> $tomail,
			'to_name'	=> $toname
		);

		$args = !$args ? $defaults : array_merge( $defaults, $args );

		if ( is_string( $body ) ) {

			$body = array('html' => $body, 'text' => strip_tags( $body ) ); 

		}

		//boundaries
		$mime_boundary_mix = "mix-" . md5( uniqid( time() ) ); //boundary for the mixed part [attachments]
		$mime_boundary_alt = "alt-" . md5( uniqid( time() ) ); //boundary for the alternative part [html/text]


		//headers
		$headers	= array();	
		$headers[]	= "From: " . $args['from_name'] . "<" . $args['from_mail'] . ">";		
    	
    	if ( $args['reply_to'] ) {

    		$headers[]	= "Reply-To: " . $args['reply_to'];

    	}

		$headers[]	= "MIME-Version: 1.0";
		$headers[]	= "Subject: " . $args['subject'];
		//$headers[]	= "X-Mailer: PHP/" . phpversion();
		//$headers[]	= "Content-Type: multipart/mixed; boundary=\"{$mime_boundary_mix}\"";
		$headers[]	= "Content-Type: multipart/alternative; boundary=\"{$mime_boundary_alt}\"";		

		$headers	= implode( $args['eol'], $headers);


		//message
		$message	= array();
		//$message[]	= "--{$mime_boundary_mix}";
		//$message[]	= "Content-Type: multipart/alternative; boundary=\"{$mime_boundary_alt}\"";
		
		$message[]	= "--{$mime_boundary_alt}";											//text/plain
		$message[]	= "Content-Type: text/plain; charset=" . $args['encoding'];
		$message[]	= "Content-Transfer-Encoding: 7bit";				
		$message[]	= $body['text'];
		
		$message[]	= "--{$mime_boundary_alt}";											//text/html
		$message[]	= "Content-Type: text/html; charset=" . $args['encoding'];
		$message[]	= "Content-Transfer-Encoding: 7bit";	
		$message[]	= $body['html'];
		
		$message[]	= "--{$mime_boundary_alt}--";										//close alternative text/html content


		if( $args['files'] ){

			$files = $args['files'];
			
			if( is_string( $files ) ) {													//turns string to file info array
				
				$files = array(
					'name'		=> basename( $args['files'] ), 
					'tmp_name'	=> $files, 
					'type'		=> self::pmime_content_type( $files )
				);
			
			}
			
			if( !is_array( $files[ key( $files ) ] ) ) {			//turn a single file into an array 4 loop purposes
				
				$files = array( $files );				
		
			}
		
			foreach($files as $file){	

				if( file_exists( $file['tmp_name'] ) ) { //&& is_uploaded_file($file['tmp_name'])){			
					
					$f 		= fopen($file['tmp_name'], 'r');
					$data 	= fread($f, filesize($file['tmp_name']));
					fclose($f);
					$data	= chunk_split( base64_encode( $data ) );

					$message[]	= "--{$mime_boundary_mix}";
					$message[]	= "Content-Type: {$file['type']}; name=\"{$file['name']}\"";
					$message[]	= "Content-Transfer-Encoding: base64";
					$message[]	= "Content-Disposition: attachment; filename=\"{$file['name']}\"";
					$message[]	= $data;
				
				}
			
			}
		
		}

		$message	= implode( $args['eol'], $message);


		$subject	=  self::encode_iso88591( $args['subject'] );

		//params
		$params		= "-f " . $args['from_mail'];

		
		return @mail( $args['to_mail'], $subject, $message, $headers/*, $params*/);
	
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
    		
    		$val = ord( $string[$i] ); 
			
			$val = dechex($val); 
			
			$text .= '='.$val; 
		
		}
		
		$text .= '?='; 
		
		return $text;
	
	}	
}
?>