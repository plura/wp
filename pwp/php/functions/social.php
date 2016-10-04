<?php

//include_once( dirname( __FILE__ ) . "/../functions/utils.php" );

function pwp_social( $args=NULL ) {

	$args = array_merge( array(
		'classes'	=> '',
		'comments'	=> false,
		'filter'	=> false,
		'id'		=> '',
		'labels'	=> array(
			"comments"	=> 'Comments',
			"facebook"	=> 'Facebook',
			"google"	=> 'Google+',
			"link"		=> 'Link',
			"linkedin"	=> 'Linkedin',
			"mail"		=> 'Email',
			"pinterest"	=> 'Pinterest',
			"twitter"	=> 'Twitter'
		),
		'mail'		=> '',
		'source'	=> false,
		'title'		=> false,
		'url'		=> pwp_currentURL(),
		'social'	=> array(
			"comments"	=>	'[URL]#',
			"facebook"	=> 	'http://www.facebook.com/sharer/sharer.php?u=[URL]&title=[TITLE]',
			"google"	=> 	'https://plus.google.com/share?url=[URL]',
			"link"		=>	'[URL]',
			"linkedin"	=> 	'http://www.linkedin.com/shareArticle?mini=true&url=[URL]&title=[TITLE]&source=[SOURCE]',
			"mail"		=>	'mailto:[MAIL]?subject=[TITLE]&body=[URL]',
			"pinterest"	=> 	'http://pinterest.com/pin/create/bookmarklet/?media=[MEDIA]&url=[URL]&is_video=false&description=[TITLE]',
			"twitter"	=> 	'http://twitter.com/intent/tweet?status=[TITLE]+[URL]'
		),
		'wrapper-icons'	=> false,
		'wrapper-icon'	=> false
	), is_null( $args ) ? array() : $args);

	$classes	= !empty( $args['classes'] ) ? " " . $args['classes'] : "";

	$id 		= !empty( $args['id'] ) ? " id=\"" . $args['id'] . "\"" : "";

?>

	<div class="social-icons<?php echo $classes; ?>"<?php echo $id; ?>>

<?php

	if ($args['wrapper-icons']) {

?>
		<div class="social-icons-inner">

<?php
	
	}
	
	$social = !$args['filter'] ? $args['social'] : $args['filter'];

	foreach( $social as $v ) {

		$url = $args['social'][$v];

		if ( $v === 'comments' ) {

			$url .= ( is_bool( $args['comments' ] ) ? 'comments' : $args['comments' ] );

		}

		$href 	= preg_replace(
			
			array('/\[MAIL\]/', '/\[MEDIA\]/', '/\[SOURCE\]/', '/\[TITLE\]/', '/\[URL\]/'),
			
			array( $args['mail'], urlencode( $args['media'] ), urlencode( $args['source'] ), urlencode( $args['title'] ), /*urlencode(*/ $args['url'] /*)*/ ),
			
			$url
		
		);

		$title	= $args['labels'][$v];			

		if ($args['wrapper-icon']) {
?>

	<div class="icon-wrapper">

<?php
		}
?>
		<a class="icon icon-round <?php echo $v; ?>" href="<?php echo $href; ?>" title="<?php echo $title; ?>"<?php if (!preg_match('/(comments|mail)/', $v)) { ?> target="_blank"<?php } ?>></a>
<?php
		if ($args['wrapper-icon']) {
?>

	</div>

<?php
		}


	}

	if ($args['wrapper-icons']) {

?>

		</div>

<?php

	}

?>

	</div>

<?php

}

?>