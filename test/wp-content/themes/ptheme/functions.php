<?php

include( dirname( __FILE__ ) . '/functions/fn.php' );


include( dirname( __FILE__ ) . '/pwp/functions.php' );


add_action( 'init', 'my_add_excerpts_to_pages' );

function my_add_excerpts_to_pages() {

	add_post_type_support( 'page', 'excerpt' );

}


function pwp_enqueue_custom_scripts_and_styles() {

    wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/bootstrap/bootstrap-3.3.7-dist/css/bootstrap.min.css' );

    wp_enqueue_style( 'fontawesome', get_stylesheet_directory_uri() . '/fonts/font-awesome-4.6.3/css/font-awesome.min.css' );    

    wp_enqueue_script( 'bootstrap', get_stylesheet_directory_uri() . '/bootstrap/bootstrap-3.3.7-dist/js/bootstrap.min.js', array('jquery') );

}

?>