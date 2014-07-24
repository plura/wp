<?php

define( PWP_TEMPLATE_DIR, dirname( __FILE__ ) . "/../" );

define( PWP_TEMPLATE_URL, get_bloginfo('template_directory') . "/" );

define( PWP_URL, get_bloginfo('template_directory') . "/" . basename( dirname( __FILE__ ) ) . "/" );



/* TO DISABLE THE BASE CATEGORY IN WORDPRESS, FUNCTIONS "get_category_link" IN category_template.php AND
"next_post" IN link_template.php [wp-includes folder] WERE COMMENTED OUT! IN THEIR PLACE, AT THE BOTTOM, 
WERE ADDED HACKED FUNCTION TO DISABLE "CATEGORY/" */

/* A RETURN TAG IS USED IN COMMENTS.PHP TO DISABLE COMMENTS
<!-- PLURA THEME --><?php return; ?><!-- /PLURA THEME -->*/

$URL_INDEX	= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], "/")+1);

include("php/functions/wp.php");
include("php/includes/js.php");

?>
<!--***********************************

	AUTHOR:		PLURA
    WEBSITE:	http://plura.pt
    INFO:		info@plura.pt

****************************************-->

<!-- Plura™: EXTERNAL JS -->
<script type="text/javascript" src="<?php echo PWP_URL; ?>js/external/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?php echo PWP_URL; ?>js/external/jquery-ui/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php echo PWP_URL; ?>js/external/jquery-ui/jquery-ui.min.css" type="text/css" />
<script type="text/javascript" src="<?php echo PWP_URL; ?>js/external/swfobject.js"></script>


<script type="text/javascript" src="<?php echo PWP_URL; ?>js/p.js"></script>
<script type="text/javascript" src="<?php echo PWP_URL; ?>js/p.others.js"></script>
<script type="text/javascript" src="<?php echo PWP_URL; ?>js/p.wp.js"></script>


<!-- Plura™ PLUGINS:colorbox [JQUERY LIGHTBOX] -->
<script type="text/javascript" src="<?php echo PWP_URL; ?>js/external/jquery.colorbox-min.js"></script>
<link rel="stylesheet" href="<?php echo PWP_URL; ?>js/external/jquery.colorbox/colorbox.css" type="text/css" media="screen" />


<!-- Plura™: CORE CSS -->
<link rel="stylesheet" href="<?php echo PWP_URL; ?>css/init.css" type="text/css" />


<!--[if lte IE 6]>
<?php if(file_exists( PWP_TEMPLATE_DIR . "/_content/ie/ie6.css")) { ?>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/_content/ie/ie6.css" type="text/css" />
<?php } ?>
<link rel="stylesheet" href="<?php echo PWP_URL; ?>css/ie6_pngfix.css" type="text/css" />
<style type="text/css"><!-- body { behavior:url("<?php echo PWP_URL; ?>css/ie6_hoverfix.htc"); } --></style>
<![endif]-->
<?php if(file_exists( PWP_TEMPLATE_DIR . "/_content/ie/ie.js") || file_exists( PWP_TEMPLATE_DIR . "/_content/ie/ie.css")) { ?>
<!--[if lte IE 7]><?php 
if(file_exists( PWP_TEMPLATE_DIR . "/_content/ie/ie.js")) 	{?><script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_content/ie/ie.js"></script><?php 			} 
if(file_exists( PWP_TEMPLATE_DIR . "/_content/ie/ie.css"))	{?><link rel="stylesheet" href="<?php echo PWP_URL; ?>css/ie6_pngfix.css" type="text/css" /><?php 	} 
?><![endif]-->
<?php }



//Plura™: CUSTOM 
if( file_exists( PWP_TEMPLATE_DIR . "/_content/scripts.js" ) ) { ?> 
	
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_content/scripts.js" charset="ISO-8859-1"></script>

<?php } ?>

<?php if ( file_exists( PWP_TEMPLATE_DIR . "/_content/header.dev.php" ) ) { 

	include(PWP_TEMPLATE_DIR . "/_content/header.dev.php"); 

} elseif (file_exists( PWP_TEMPLATE_DIR . "/_content/header.php" ) ) {

	include(PWP_TEMPLATE_DIR . "/_content/header.php"); 
	
}


//Plura™: FAVICON

if ( file_exists( PWP_TEMPLATE_DIR . "/_content/img/favicon.ico" ) )	{ ?>
    
	<link rel="SHORTCUT ICON" href="<?php bloginfo('template_directory'); ?>/_content/img/favicon.ico"/><?php 
	
} elseif (file_exists(PWP_TEMPLATE_DIR . "/_content/img/favicon.gif") ) { ?>

	<link rel="SHORTCUT ICON" href="<?php bloginfo('template_directory'); ?>/_content/img/favicon.gif"/><?php
	
}	elseif ( file_exists(PWP_TEMPLATE_DIR . "/_content/img/favicon.png") ) { ?>

	<link rel="SHORTCUT ICON" href="<?php bloginfo('template_directory'); ?>/_content/img/favicon.png"/>
	
<?php } ?>
