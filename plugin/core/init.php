<?php

include( dirname( __FILE__ ) . "/functions.php");


//translate taxonomies
add_action('admin_init', 'pwp_qtranslate_edit_taxonomies');


//allow attachments categories
add_action( 'init' , 'pwp_add_categories_to_attachments' );


//permit excerpt for pages
//don't forget to enable 'excerpt' on the top left corner admin options
add_post_type_support( 'page', 'excerpt');

?>