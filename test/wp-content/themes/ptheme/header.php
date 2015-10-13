<!DOCTYPE html>
<html>

	<head>

	  <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <meta name="viewport" content="width=device-width, initial-scale=1">

		<title><?php wp_title( '|', true, 'right' ); ?></title>

		<?php wp_head(); ?>

		<!-- PLURA PWP -->
		<?php include_once('pwp/init.php'); ?>
			
		<!-- BOOTSTRAP [after init b/c it should come after jquery initialitzation] -->
		<link href="<?php bloginfo('template_url'); ?>/bootstrap/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
		<script src="<?php bloginfo('template_url'); ?>/bootstrap/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

		<!-- FONTAWESOME -->
	  <link href="<?php bloginfo('template_url'); ?>/fonts/font-awesome-4.4.0/font-awesome.min.css" rel="stylesheet">		

	  <!-- included after bootstrap.css to avoid override -->
	  <link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet">

	</head>


	<body <?php body_class(); ?>>


		<?php include_once('includes/nav.php'); ?>


		<div class="container" id="root">

			<div class="row">

  <?php if ( !_has_fn('has_sidebar') || _fn('has_sidebar') ) { ?>

				<div class="col-md-9" id="main">

  <?php } else { ?>

         <div class="col-md-12" id="main">

  <?php } ?>