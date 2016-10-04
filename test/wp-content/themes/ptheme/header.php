<!DOCTYPE html>
<html>

	<head>

	  <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <meta name="viewport" content="width=device-width, initial-scale=1">

		<title><?php wp_title( '|', true, 'right' ); ?></title>

		<?php wp_head(); ?>

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