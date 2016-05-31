<?php

include_once( dirname( __FILE__ ) . "/common.php" );
include_once( dirname( __FILE__ ) . "/media.php");


function pwp_ref_rand( $args = NULL ) {

	if ( is_int( $args ) ) {

		$args = array('number_of_images' => $args);

	}

	$params = pwp_args( array(
		'category_filter'			=> "",
		'image_src_regex'			=> "",
 		'image_attributes'			=> "",
		'image_class_match'			=> "",
		'number_of_images'			=> 1,
		'lang'						=> NULL,		
		'post_type'					=> "post",
		'show_images_in_galleries'	=> true,
		'show_post_title'			=> true,
		'show_alt_caption'			=> true,
		'sort_randomly'				=> true,
		'thumb_type'				=> "thumbnail",

		/*'query'					=> array(
			'post_type'			=> 'post',
			'posts_per_page'	=> -1,
			'orderby'			=> "rand"
		)*/

	), $args);


	// get access to wordpress' database object
	global $wpdb;

  	// variable returned
	$posts = array();


  	// select the post_type sql for both post pages (post_status = 'static') 
  	// and posts (AND post_status = 'publish')
  	// or for just pages or for just posts (the default)
  	// by adding this where criteria, we also solve the problem
  	// of accidentally including images from draft posts.
	if ( is_string( $params['post_type'] ) ) {

		$sql_post_type = "post_type = '" . $params['post_type'] . "'";

	} else if( is_array( $params['post_type'] ) ) {

		$sql_post_type = array();

		foreach( $params['post_type'] as $type ) {

			$sql_post_type[] = "post_type = '" . $type . "'";

		}

		$sql_post_type = "AND " . implode(' OR ', $sql_post_type);

	}
  
	// assuming $category_filter is a comma separated list of category ids,
	// modify query to join with post2cat table to select from only the chosen categories
	$category_filter_join  = "";
	$category_filter_sql   = "";
	$category_filter_group = "";
	
	if( !empty( $params['category_filter'] ) ) {
		
		$category_filter_join  = "LEFT JOIN $wpdb->term_relationships ON $wpdb->posts.ID = $wpdb->term_relationships.object_id LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id";
		
		$category_filter_sql   = "AND $wpdb->term_taxonomy.term_id IN (" . $params['category_filter'] . ")";   
		
		$category_filter_group = "GROUP BY $wpdb->posts.ID";
	
	}
  
	// by default we sort images randomly,
	// but we can also sort them in descending date order
	if ( $params['sort_randomly'] ) {

		$sql_order_by = "rand()";

	} else {

		$sql_order_by = "$wpdb->posts.post_date DESC";

	}

	// query records that contain img tags, ordered randomly
	// do not select images from password protected posts
	$sql = "SELECT $wpdb->posts.ID, $wpdb->posts.post_title, $wpdb->posts.post_content, $wpdb->posts.post_excerpt 
			FROM $wpdb->posts 
			$category_filter_join 
			WHERE (post_content LIKE '%<img%' or post_content LIKE '%[gallery%') 														  
			AND post_password = '' 
			$sql_post_type 
			$category_filter_sql 
			$category_filter_group 
			ORDER BY $sql_order_by";


	$rows = $wpdb->get_results( $sql );


	// keep track of multiple images to prevent displaying duplicates
	$image_srcs = array();

	// loop through each applicable post from the database
	$image_count = 0;
	
	foreach( $rows as $row ) {

		$matches = array();

    	// find all img tags within each post's content
    	preg_match_all("/<img[^>]+>/i", $row->post_content, $matches);  //todo guid/src only
		
		if( $params['show_images_in_galleries'] ){
	
			// find any gallery attachments
			if(strpos($row->post_content, '[gallery') !== false){

				$images = pwp_get_post_images( $row->ID );
				
				if ( $images ) {

					foreach ($images as $image){
						
						$matches[0][] = "<img src=\"" . $image->guid . "\"/>";
					
					}

				}		
			
			}
    	
    	}

		// if there are none, try again,  
		if( !count($matches[0]) ) {

			continue;

		}

		// randomize the array of images in this post
		shuffle($matches[0]);

		// loop through each image candidate for this post and try to find a winner        
		foreach($matches[0] as $image_element) {
			
			preg_match("/src\s*=\s*(\"|')(.*?)\\1/i", $image_element, $image_src);
			
			$image_src = $image_src[2];      
	  
	  		// make sure we haven't displayed this image before
			if( empty( $image_src ) || in_array($image_src, $image_srcs) ) {

				continue;

			// if a regex is supplied and it doesn't match, try next post
			} elseif ( !empty( $params['image_src_regex'] ) && !preg_match("/" .  $params['image_src_regex'] . "/i", $image_src)) {

				continue;

			}

			if( !empty( $params['image_class_match'] ) ) {
				// grab the class attribute and see if it exists, if not try again
	        	preg_match("/class\s*=\s*(\"|')(.*?)\\1/i", $image_element, $image_classes);
    	    	
    	    	$image_classes = $image_classes[2];

				if($image_classes == '') {

					continue;

				}

				$image_classes = explode(" ", $image_classes);
				
				if(!in_array($params['image_class_match'], $image_classes)) {

					continue;

				}
			
			}

			// add img src to array to check for duplicates
			$image_srcs[] = $image_src;
         
			// grab the alt attribute and see if it exists, if not supply default
			preg_match("/alt\s*=\s*(\"|')(.*?)\\1/i", $image_element, $image_alt);
			
			$image_alt = $image_alt[2];

			if( empty( $image_alt ) ) {

				$image_alt = "random image";

			}

			
			$img = pwp_image_info( $image_src );
			
			$posts[] 	= array(
				'title' 	=> pwp_trans( $row->post_title, $params['lang'] ),  //pwp_strip_qtlang($row['post_title']),
				'permalink' => get_permalink($row->ID),
				'content'	=> pwp_trans( $row->post_content, $params['lang']),
				'excerpt'   => pwp_trans( $row->post_excerpt, $params['lang']),
				'img'		=> $img
			);
		
	      	$image_count++;
	  
	  		//returns posts
			if($image_count == $params['number_of_images'] )	{

				return $posts;

			}
	  
			//leave the foreach loop and look for images in other posts
			//TODO: if people wanted to display multiple images per post, we would selectively skip this break
			break;
		}
	}
}




/**
* gets image info. the regex is fundamental to correctly retrieve the image metadata. '(.*\:")?' is used
* because the file name and its path maybe separated. The '\:"' follows WP syntax pattern regarding image 
* metadata entries in the db
* @param	id		image id or file string
* @param	type	image types: 'thumbnail', 'medium', 'large'
*/
function pwp_image_info( $id ){
	
	global $wpdb;

	if(!is_numeric( $id ) ) {
		
		$reg	= preg_replace('/^.*\/([0-9]{4}\/[0-9]{2}\/)(.*)$/', '$1(.*\:")?$2', $id);
		
		$rows	= $wpdb->get_results("SELECT $wpdb->postmeta.post_id FROM $wpdb->postmeta 
							   		  WHERE $wpdb->postmeta.meta_value REGEXP '$reg' LIMIT 1");
		$id		= $rows[0]->post_id;
	
	}

	$a	= wp_get_attachment_metadata( $id );

	$u	= wp_upload_dir();
	
	$a['dir']	= $u['baseurl'] . preg_replace('/^(.*[0-9]{2}\/).*$/', '/$1', $a['file']);

	$a['file']	= preg_replace('/^.*[0-9]{2}\/(.*)$/', '$1', $a['file']);

	return $a;

}




/**
* @param	catID		categoryID
* @param	limit		number of posts
* @param	thumbdim	size limit of thumb
* @param	width		limits thumbdim by width if true. othewise limits by height
* @param	radimg		if true and multiple images are retrieved, return a random one. 
*						otherwise only the first is returned
* @param	suffix		wordpress suffix
*
*/
function pwp_ref( $args = NULL ){	

	if ( is_int( $args ) ) {

		$prms = array( 'posts_per_page' => $args );

	} else if ( is_string( $args ) ) {

		parse_str( $args, $prms );

	} else {

		$prms = $args;

	}

	$params = pwp_args( array(
		'post_type' 		=> 'post',
		'posts_per_page'	=> 5
	), $prms );

	$query 	= new WP_Query( $params );
	
	$p 		= array();
	
	if( count( $query->posts ) ) {

		foreach( $query->posts as $post ) {
	
			$row = array(
				'excerpt'	=> get_the_excerpt( $post->ID ), //pwp_excerpt($row['content']);
				'url'		=> get_permalink( $post->ID ),
				'title'		=> get_the_title( $post->ID ),
				'content'	=> get_the_content( $post->ID ),
				'date'		=> get_the_date( $post->ID )
			);

			$row['html'] = "<a href=\"" . $row['url'] . "\" title=\"" . $row['title'] . "\" class=\"ref title\">" . $row['title'] . "</a>\n";


			$image = pwp_get_post_image( $post->ID, 'thumbnail');


			if ( !$image ) {

				preg_match_all('/(img|src)=("|\')[^"\'>]+/i', get_the_content( $post->ID ), $matches);	//matches all img tags
	
				if(!empty($matches) && !empty($matches[0])) {

					$src = preg_replace('/(img|src)("|\'|="|=\')(.*)/i',"$3", $matches[0]);	//retrieves src tags
		
					if(is_array($src)) {

						$src = $src[0]; 										//in case of multiple urls
				
					}

					$img = pwp_image_info( $src );

					if($img){
					
						$row['img']	= $img['sizes'];
					
						if(!empty($img['sizes'])){
						
							foreach($row['img'] as &$r){ 
						
								$r['src'] = $img['dir'] . $r['file']; 
						
							}
					
						}				
			
						$image = array( isset($row['img']['thumbnail']) ? $row['img']['thumbnail']['src'] : $img['dir'] . $img['file'] );

					}								

				}
		
			}
				
			if ( $image ) {

				$row['html']   .= "<a href=\"$row[url]\" title=\"$row[title]\" class=\"ref img\"><img src=\"" . $image[0] . "\"/></a>\n";

			}			
	
			$row['html']   .= "<a href=\"$row[url]\" title=\"$row[title]\" class=\"ref excerpt\">$row[excerpt]</a>\n";

			array_push($p, $row);
	
		}

		return $p;
	
	}
	
	return false;

}



function pwp_excerpt($text, $n=55, $more='[...]'){
	
	$words = explode(' ', strip_tags($text), 55+1);
	
	if(count($words)>$n){
		
		array_pop($words);
		
		array_push($words,'[...]');
	
	}
	
	return implode(' ', $words);

}
?>
