<?php


switch ( get_the_ID() ) {

case 2:

	include( dirname( __FILE__ ) . "/page/contacts.php" );

	break;

default:

	the_content();

}

?>