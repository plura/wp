<?php

add_action( 'wp_head', 'pwp_head' );

if ( function_exists('pwp_enqueue_custom_scripts_and_styles') ) {

    add_action( 'wp_enqueue_scripts', 'pwp_enqueue_custom_scripts_and_styles' );

}

add_action( 'wp_enqueue_scripts', 'pwp_enqueue_scripts_and_styles' );



function pwp_enqueue_scripts_and_styles() {

    //framework
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'swfobject' );

    //no conflict jquery;
    wp_enqueue_script( 'jquery-noconflict', get_stylesheet_directory_uri() . '/pwp/js/jquery-noconflict.js', array('jquery') );    

    //p.js
    wp_enqueue_script( 'pwp-p', get_stylesheet_directory_uri() . '/pwp/js/p.js', array('jquery', 'jquery-noconflict') );

    wp_enqueue_script( 'pwp-p-wp', get_stylesheet_directory_uri() . '/pwp/js/p.wp.js', array('pwp-p') );

    wp_enqueue_script( 'pwp-p-others', get_stylesheet_directory_uri() . '/pwp/js/p.others.js', array('pwp-p') );        


    //modules: colorbox
    wp_enqueue_style( 'colorbox', get_stylesheet_directory_uri() . '/pwp/js/external/jquery.colorbox/colorbox.css' );      

    wp_enqueue_script( 'colorbox', get_stylesheet_directory_uri() . '/pwp/js/external/jquery.colorbox-min.js', array('jquery'), '1.0.0', true );


    //Plura™: CUSTOM 
    if( file_exists( dirname( __FILE__ ) . "/../_content/scripts.js" ) ) {

        wp_enqueue_script( 'pwp-custom', get_stylesheet_directory_uri() . '/_content/scripts.js', array('jquery'), '1.0.0', true );  

    }

    //MAIN
    wp_enqueue_style( 'main', get_bloginfo('stylesheet_url') ); 

}




//use $ sign in jQuery
function pwp_head(){

    global $wp_query;

    include( dirname(__FILE__) . "/php/includes/js.php");



    /* open | graph */
    if ( file_exists( get_template_directory() . "/includes/og.php" ) ) {

         include(get_template_directory() . "/includes/og.php"); 

    }


    /* head | dev */
    if ( file_exists( get_template_directory() . "/_content/head.dev.php" ) ) { 

        include(get_template_directory() . "/_content/head.dev.php"); 

    } elseif (file_exists( get_template_directory() . "/_content/head.php" ) ) {

        include(get_template_directory() . "/_content/head.php"); 
        
    }


    /* favicon */
    if ( file_exists( PWP_TEMPLATE_DIR . "/images/favicon.ico" ) )  { ?>
        
        <link rel="SHORTCUT ICON" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico"/><?php 
        
    } elseif (file_exists(PWP_TEMPLATE_DIR . "/images/favicon.gif") ) { ?>

        <link rel="SHORTCUT ICON" href="<?php bloginfo('template_directory'); ?>/images/favicon.gif"/><?php
        
    }   elseif ( file_exists(PWP_TEMPLATE_DIR . "/images/favicon.png") ) { ?>

        <link rel="SHORTCUT ICON" href="<?php bloginfo('template_directory'); ?>/images/favicon.png"/><?php
        
    }

}

?>