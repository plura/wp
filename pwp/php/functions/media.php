<?php

include_once( dirname( __FILE__ ) . "/common.php" );


function pwp_image_path($data, $size = NULL) {
	
	$uploads = wp_upload_dir();
	
	if($size === 'large' && isset($data['sizes']['large'])){
			
		$f = $data['sizes']['large']['file'];
			
	} elseif(($size === 'medium' && isset($data['sizes']['medium'])) || ($size === 'large' && !isset($data['sizes']['large']))){
			
		$f = $data['sizes']['medium']['file'];
			
	} elseif($size === 'thumbnail' || ($size === 'medium' && !isset($data['sizes']['medium'])) || ($size === 'large' && !isset($data['sizes']['large']))){
			
		$f = $data['sizes']['thumbnail']['file'];
			
	}	
	
	if (isset($f)) {
		
		return $uploads['baseurl'] . '/' . preg_replace('/[^\/]+$/', '', $data['file']) . $f;		
		
	}
	
	return $uploads['baseurl'] . '/' . $data['file'];
	
}



/**
 * gets posts image
 * @param int $id the post ID
 * @param string $size image size
 */
function pwp_get_post_image( $id, $size = 'large' ) {

	if (has_post_thumbnail( $id )) {

		$imgID = get_post_thumbnail_id( $id );
				
	} else {

		$images = pwp_get_post_images( $id, 1 );

		if ($images) {

			$imgID = $images[0]->ID;

		} else {

			return false;

		}

	}

	return wp_get_attachment_image_src( $imgID, $size );

}


function pwp_get_post_image_obj( $id ) {

	if (has_post_thumbnail( $id )) {

		$imgID = get_post_thumbnail_id( $id );

		return get_post( $imgID );

	} else {

		$images = pwp_get_post_images( $id, 1 );

		if ($images) {

			return $images[0];

		}

	}

	return false;

}



/**
 * gets some or all images attached to a post
 * @param int $id the post ID
 * @param int $count indicates number of images to be returned. default -1 for all images
 */
function pwp_get_post_images( $id, $params = false ) {

	$query_params = array(
		'post_parent'		=> $id,
		'posts_per_page'	=> $params && is_int( $params) ? $params : -1,
	 	'post_status'		=> 'inherit',
		'post_type'			=> 'attachment',
		'post_mime_type'	=> array('image/gif','image/png','image/jpg','image/jpeg')

		/*'meta_key'		=> 'pwp_sort_rank',
		'orderby'			=> 'meta_value_num', 
		'order'				=> 'ASC',*/		
	);

    if ( $params && is_array( $params ) ) {

        $query_params = array_merge( $query_params, $params );

    }	

	$query = new WP_Query( $query_params );

	if ( count( $query->posts ) ) {

		return $query->posts;

	}

	return false;

}



/**
 * gets number of images attached to a post
 * @param int $id the post ID
 */
function pwp_get_number_of_images( $id ) {

	$query = new WP_Query(array(
		'post_parent'		=> $id,
	 	'post_status'		=> 'inherit',
		'post_type'			=> 'attachment',
		'post_mime_type'	=> array('image/gif','image/png','image/jpg','image/jpeg'),
		'posts_per_page'	=> -1
	) );

	return count( $query->posts );

}



/**
 * gets number of images attached to a post
 * @param int $term_id the post ID
 * @param string $taxonomy the taxonomy name
 * @param string $post_type the post type ID
 * @param array $args optional parameters for the query
 * @param string $size the image size to be returned
 */
function pwp_get_term_image( $term_id, $taxonomy, $post_type, $args = NULL, $size = 'large') {

	$params	= pwp_args( array(
	    
	    'post_type'			=> $post_type,
		'posts_per_page'	=> -1,
		'tax_query'			=> array(
			array(
				'field'			=> 'term_id',
				'taxonomy'		=> $taxonomy,
				'terms'			=> $term_id
			)
		)
	
	), $args );


	$query	= new WP_Query( $params );


	if ( count($query->posts) ) {

		foreach( $query->posts as $post ) {

			$image = pwp_get_post_image( $post->ID, $size );

			if ( $image ) {

				return $image;

			}

		}

	}

	return false;

}

?>