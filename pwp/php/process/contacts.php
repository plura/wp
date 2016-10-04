<?php


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


?>