<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<header class="entry-header">

		<?php

		switch ($wp_query->queried_object->post_type) {

		//include specific post cases here

		default:

		?>

		
		<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) /*&& twentyfourteen_categorized_blog() */) : ?>
		
		<div class="entry-meta">
			
			<span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', _theme_name() ) ); ?></span>
		
		</div>
		
		<?php
			
			endif;

			if ( is_single() ) :

				the_title( '<h1 class="entry-title">', '</h1>' );
			
			else :
				
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			
			endif;
		
		?>

		<div class="entry-meta">
			
			<?php
				
				if ( 'post' == get_post_type() )
					
					twentyfourteen_posted_on();

				if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
			
			?>
			
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', _theme_name() ), __( '1 Comment', _theme_name() ), __( '% Comments', _theme_name() ) ); ?></span>
			
			<?php
				
				endif;
				
				edit_post_link( __( 'Edit', _theme_name() ), '<span class="edit-link">', '</span>' );
			
			?>
		
		</div><!-- .entry-meta -->

	
	<?php } ?> <!-- end switch condition -->


	</header><!-- .entry-header -->

	<?php if ( is_search() ) : ?>
	
	<div class="entry-summary">
	
		<?php the_excerpt(); ?>
	
	</div><!-- .entry-summary -->
	
	<?php else : ?>
	
	<div class="entry-content">
		
		<?php
			
			include('includes/content-post.php');
			
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', _theme_name() ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
		
		?>
	
	</div><!-- .entry-content -->
	
	<?php endif; ?>

	
	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>

</article><!-- #post-## -->
