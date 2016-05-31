<?php 

//include_once( dirname( __FILE__ ) . "/../../fn/utils.php");

if (has_post_thumbnail()) {

	$featured_image_id	= get_post_thumbnail_id();

	$featured_image		= wp_get_attachment_image_src( $featured_image_id, 'large' );

	//$featured_image = novasotecma_get_featured_img();

?>

<div class="jumbotron" style="background-image: url('<?php echo $featured_image[0]; ?>');">

<?php 

} 

?>

<div class="entry-title">

<?php the_title( '<h1>', '</h1>' ); ?>

</div>



<?php if (has_post_thumbnail()) { ?>

</div>

<?php 

} 

?>