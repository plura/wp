<?php
foreach($_REQUEST as $key => $value) $$key = $value;


if(!isset($type)) exit;

/**
* @param	dir			upload directory
* @param	unique		unique id
*/

switch($type){
	
	case 'upload':
	
		switch($action){
	
			case 'process':
	
				if(is_uploaded_file($_FILES['Filedata']['tmp_name'])){

					if($unique){
						
						$id = preg_replace('/.*(\..*)$/', uniqid().'$1', $_FILES['Filedata']['name']);
					
					} else {
						
						$id = $_FILES['Filedata']['name'];
						
					}

					if(!isset($path)) {
						
						$path = "";
						
					}
	
					$uploadFile	= $path . basename($id);
				
					$result 	= copy($_FILES['Filedata']['tmp_name'], $uploadFile);
					
					if($result){
					
						echo	"file=" . basename($id) 				. "&" .
								"name=" . $_FILES['Filedata']['name']	. "&" .
								"path=" . $path							. "&" .
								"success=true";
						
					} else {
		
						echo	"success=false";
						
					}
				}
		
				break;
		
				case 'delete':
		
					$result = unlink($path . $file);
		
					echo '{"success":"' . ($result? 'true' : 'false') . '"}';
	
					break;
				
		}
}
?>
