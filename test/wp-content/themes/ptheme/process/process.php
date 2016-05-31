<?php

define('PATH', preg_replace(array("/^(.*\/.*)?\/(wp-content.*)\/(.*)$/", "/([A-Za-z0-9-_])*/"), array("$2", "."), $_SERVER['PHP_SELF']));

include_once(PATH . "/wp-load.php");


if (isset($_GET['type'])) {


	switch( $_GET['type']) {


	}


}


if ($return && is_array($return)) {
	
	echo json_encode($return, JSON_NUMERIC_CHECK);
	
}


?>