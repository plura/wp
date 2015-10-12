<?php

add_theme_support( 'menus' ); //necessary in order to have menu options in admin>appearance

add_theme_support( 'post-thumbnails' );




function wpbootstrap_scripts_with_jquery() {

	// Register the script like this for a theme:
	//wp_register_script( 'custom-script', get_template_directory_uri() . '/bootstrap/bootstrap-3.2.0-dist/js/bootstrap.js', array( 'jquery' ) );
	// For either a plugin or a theme, you can then enqueue the script:
	wp_enqueue_script( 'custom-script' );

}

add_action( 'wp_enqueue_scripts', 'wpbootstrap_scripts_with_jquery' );




//http://www.smashingmagazine.com/2011/12/29/internationalizing-localizing-wordpress-theme/
function my_theme_setup(){

	load_theme_textdomain('novasotecma', get_template_directory() . '/languages');

}

add_action('after_setup_theme', 'my_theme_setup');




/**
 * Provides a standard format for the page title depending on the view. This is
 * filtered so that plugins can provide alternative title formats.
 *
 * @param       string    $title    Default title text for current view.
 * @param       string    $sep      Optional separator.
 * @return      string              The filtered title.
 * @package     mayer
 * @subpackage  includes
 * @version     1.0.0
 * @since       1.0.0
 */
function front_mayer_wp_title( $title, $sep ) {
	
	global $paged, $page;
 
	if ( is_feed() ) {
		return $title;
	} // end if
 
	// Add the site name.
	$title .= get_bloginfo( 'name' );

 	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		
		$title = "$title $sep $site_description";
	
	} // end if
 
	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = sprintf( __( 'Page %s', 'mayer' ), max( $paged, $page ) ) . " $sep $title";
	} // end if
 
	return $title;
 
} // end mayer_wp_title

add_filter( 'wp_title', 'front_mayer_wp_title', 10, 2 );


//Problem with get_permalink() + workaround [via https://wordpress.org/support/topic/problem-with-get_permalink-workaround]
//add_filter('post_type_link', 'qtrans_convertURL');




/* CUSTOMIZED WP ADMIN LOGO */
function admin_login_logo() { ?>

	<style type="text/css">

	    body.login div#login h1 a {
			background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/logo-admin-login.png);
	        background-size: contain;
	        padding-bottom: 30px;
	        width: 100%;

	    }

	</style>

<?php }

add_action( 'login_enqueue_scripts', 'admin_login_logo' );




/* CUSTOM CLASSES TO WP_NAV_MENU */
function front_nav_class($classes, $item){

     if( $item->type === 'taxonomy' ){ 

		$classes[] = "obj-type-" . $item->type;

		$classes[] = "obj-id-" . $item->object_id;
     
     }
     
     return $classes;
}

add_filter('nav_menu_css_class' , 'front_nav_class' , 10 , 2);




/* Add post/page slug to body */
function front_add_slug_body_class( $classes ) {
	
	global $post;

	if ( isset( $post ) ) {

		$classes[] = $post->post_type . '-' . $post->post_name;

	}

	return $classes;

}

add_filter( 'body_class', 'front_add_slug_body_class' );






function _fn( $fn, $args = NULL ) {

	if (is_null($args)) {

		return call_user_func( $fn );

	}

	return  call_user_func_array( $fn, $args );

}



function _has_fn( $fn ) {

	if ( function_exists( wp_get_theme()->TextDomain . '_' . $fn ) ) {

		return $true;

	}

	return false;

}



function _theme_name() {

	return wp_get_theme()->TextDomain;

}



/* 
	QTRANSLATE ON CUSTOM TYPE FIELDS 

	http://tonykwon.com/2010/07/09/how-to-enable-qtranslate-meta-on-custom-post-types/
*/

?>