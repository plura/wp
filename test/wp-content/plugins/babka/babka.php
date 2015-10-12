<?php
/*
	Plugin Name: BABKA Custom Types
	Description: Site specific code changes for babka.pt
*/


/* Start Adding Functions Below this Line */
//include_once( dirname( __FILE__ ) . "/fn.php");

include_once( dirname( __FILE__ ) . "/../qtranslate-x/qtranslate.php");


include_once( dirname( __FILE__ ) . "/p/init.php");

include_once( dirname( __FILE__ ) . "/babka_custom_parties.php");

include_once( dirname( __FILE__ ) . "/babka_custom_services.php");

include_once( dirname( __FILE__ ) . "/babka_custom_testimonials.php");


/*function pwp_add_categories_to_attachments() {
    
	register_taxonomy_for_object_type( 'category', 'attachment' );

}

add_action( 'init' , 'pwp_add_categories_to_attachments' );*/


//permit excerpt for pages
//don't forget to enable 'excerpt' on the top left corner admin options
add_post_type_support( 'page', 'excerpt');


?>