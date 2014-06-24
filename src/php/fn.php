<?php
function pfiles($s, $path=NULL, $rename=false){
	$files = explode('|',$s);			//separate items

	foreach($files as $key=>$value){
		$a = explode(';', $value);
		$p = is_null($path)? "" : $path;
		$files[$key] = array(
			'name' 		=> $a[0],
			'tmp_name'	=> $p . $a[1],
			'file'		=> $a[0],
			'tmp'		=> $a[1],
			'type'		=> pmime_content_type($p . $a[1])
		);
		//if($rename) rename($p . $a[1], $p . $a[0]);
	}
	
	return $files;
}



function pmail($tmail, $tname, $fmail, $fname, $subject, $body, $files=NULL, $reply=FALSE){
	$mime_boundary_mix = "mix-" . md5(uniqid(time())); 		//boundary for the mixed part 		[files]
	$mime_boundary_alt = "alt-" . md5(uniqid(time())); 		//boundary for the alternative part	[html/text]
	
	if(strtoupper(substr(PHP_OS,0,3)=='WIN'))		$eol="\r\n"; 
	elseif(strtoupper(substr(PHP_OS,0,3)=='MAC'))	$eol="\r"; 
	else 											$eol="\n";

	//$headers = "To: $tname <$tmail>".$eol;					//headers
	$headers = "From: $fname <$fmail>".$eol;
	$headers.= "Reply-To: <$fmail>".$eol;
	$headers.= "MIME-Version: 1.0".$eol;
	$headers.= "Content-Type: multipart/mixed; boundary=\"{$mime_boundary_mix}\"".$eol;	
	
	$message =	"--{$mime_boundary_mix}".$eol .
				"Content-Type: multipart/alternative; boundary=\"{$mime_boundary_alt}\"".$eol.$eol .
				"--{$mime_boundary_alt}".$eol .				//plain text
				"Content-Type: text/plain; charset=iso-8859-1".$eol .
				"Content-Transfer-Encoding: 7bit".$eol.$eol .
				strip_tags($body) .$eol.$eol .
				"--{$mime_boundary_alt}".$eol .				//html
				"Content-Type: text/html; charset=iso-8859-1".$eol .
				"Content-Transfer-Encoding: 7bit".$eol.$eol .
				$body .$eol.$eol .
				"--{$mime_boundary_alt}--".$eol.$eol;				//close alternative text/html content

	if(!is_null($files)){
		if(is_string($files))								//turns string to file info array
			$files = array('name'=>basename($files), 'tmp_name'=>$files, 'type'=>pmime_content_type($files));
		if(!is_array($files[key($files)]))					//turn a single file into an array 4 loop purposes
			$files = array($files);			

		foreach($files as $file){	
			if(file_exists($file['tmp_name']) /*&& is_uploaded_file($file['tmp_name'])*/){			
				$f 		= fopen($file['tmp_name'], 'rb');
				$data	= fread($f, filesize($file['tmp_name']));
				fclose($f);
				$data	= chunk_split(base64_encode($data));
				
				$message .= "--{$mime_boundary_mix}".$eol;
				$message .= "Content-Type: {$file['type']}; name=\"{$file['name']}\"".$eol;
				$message .= "Content-Transfer-Encoding: base64".$eol;
				$message .= "Content-Disposition: attachment; filename=\"{$file['name']}\"".$eol.$eol;
				$message .= $data.$eol.$eol;				
			}
		}
	}

	$message .= "--{$mime_boundary_mix}--".$eol.$eol;

	return @mail($tmail, $subject, $message, $headers);
}



function pmime_content_type($filename) {

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
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'exe' => 'application/x-msdownload',
		'msi' => 'application/x-msdownload',
		'cab' => 'application/vnd.ms-cab-compressed',

		// audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',

		// adobe
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',

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
		$finfo = finfo_open(FILEINFO_MIME);
		$mimetype = finfo_file($finfo, $filename);
		finfo_close($finfo);
		return $mimetype;
	} else {
		return 'application/octet-stream';
	}
}



/**
* Plura™ Array 2 JSON
* transforms array to json string [for cases where json_encode does not exist]
* @param	$a		array value
* http://www.php.net/manual/en/function.json-encode.php#89908
*/
function parray2json($a){
	
	$construct 		= array();
	
	$associative	= count(array_diff(array_keys($a), array_keys(array_keys($a))));
	
	foreach($a as $key=>$value){
		
		//format the key
		if(is_numeric($key))	$key = '"key_' . $key . '"';
		
		else					$key = '"' . $key . '"';
		
		//format the value
		if(is_array($value)){
			
			$value = parray2json($value);
		
		} elseif(is_string($value)) {
		
			$value = preg_replace(array('/\//','/\s\s+/'), array('\/',""), '"'.addslashes($value).'"');
		
		} elseif(is_numeric($value)){
				
			$value = '"'.addslashes($value).'"';
		
		}
		
		$construct[] = $associative? "$key:$value" : $value;
	}
	
	if($associative)
	
		return "{ " . implode( ", ", $construct ) . " }";
		
	else
	
		return "[ " . implode( ", ", $construct ) . " ]";
}
?>
