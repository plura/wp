<?php


function pwp_ref_rand($number_of_images			= 1, 
					  $post_type				= "both",
					  $category_filter			= "",
					  $thumb_type				= "thumbnail",
					  $image_src_regex			= "",
					  $sort_randomly			= true,
					  $image_class_match		= "",
					  $show_images_in_galleries	= true,
 					  $image_attributes			= "",
					  $show_post_title			= true,
					  $show_alt_caption			= true){
	
	// get access to wordpress' database object
	global $wpdb;

  	// variable returned
	$posts = array();

  	// select the post_type sql for both post pages (post_status = 'static') 
  	// and posts (AND post_status = 'publish')
  	// or for just pages or for just posts (the default)
  	// by adding this where criteria, we also solve the problem
  	// of accidentally including images from draft posts.
	if ($post_type == 'both') {

		$post_type_sql = "AND post_status = 'publish' AND post_type in ('post', 'page')";

	} elseif ($post_type == 'pages') {

		$post_type_sql = "AND post_status = 'publish' AND post_type = 'page'";

	} else {

		$post_type_sql = "AND post_status = 'publish' AND post_type = 'post'";

	}							
  
	// assuming $category_filter is a comma separated list of category ids,
	// modify query to join with post2cat table to select from only the chosen categories
	$category_filter_join  = "";
	$category_filter_sql   = "";
	$category_filter_group = "";
	
	if($category_filter != ""){
		
		$category_filter_join  = "LEFT JOIN $wpdb->term_relationships ON $wpdb->posts.ID = $wpdb->term_relationships.object_id LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id";
		
		$category_filter_sql   = "AND $wpdb->term_taxonomy.term_id IN ($category_filter)";   
		
		$category_filter_group = "GROUP BY $wpdb->posts.ID";
	
	}
  
	// by default we sort images randomly,
	// but we can also sort them in descending date order
	if ($sort_randomly)	{

		$order_by_sql = "rand()";

	} else {

		$order_by_sql = "$wpdb->posts.post_date DESC";

	}

	// query records that contain img tags, ordered randomly
	// do not select images from password protected posts
	$sql = "SELECT $wpdb->posts.ID, $wpdb->posts.post_title, $wpdb->posts.post_content, $wpdb->posts.post_excerpt
			FROM $wpdb->posts
			$category_filter_join
			WHERE (post_content LIKE '%<img%' or post_content LIKE '%[gallery%')
			/*AND ID=341	*/															  
			AND post_password = ''
			$post_type_sql
			$category_filter_sql
			$category_filter_group
			ORDER BY $order_by_sql";
	
	$resultset = @mysql_query($sql, $wpdb->dbh);
  
	// keep track of multiple images to prevent displaying duplicates
	$image_srcs = array();

	// loop through each applicable post from the database
	$image_count = 0;
	
	while($row = mysql_fetch_array($resultset)) {

		$matches 		= array();

    	// find all img tags within each post's content
    	preg_match_all("/<img[^>]+>/i", $row['post_content'], $matches);  //todo guid/src only
		
		if($show_images_in_galleries){
	
			// find any gallery attachments
			if(strpos($row['post_content'], '[gallery') !== false){
				
				$sql = "SELECT $wpdb->posts.guid, $wpdb->posts.post_excerpt
            			FROM $wpdb->posts
						WHERE post_parent = '" . $row['ID'] . "'
						AND post_type = 'attachment'
						AND post_password = ''
						AND post_mime_type in ('image/jpeg', 'image/gif', 'image/png')";
				
				$resultset2 = @mysql_query($sql, $wpdb->dbh);
				
				while ($row2 = mysql_fetch_array($resultset2)){
					
					$matches[0][] = "<img src=\"" . $row2['guid'] . "\"/>";
				
				}
			
			}
    	
    	}

		// if there are none, try again,  
		if(count($matches[0]) == 0)	continue;

		// randomize the array of images in this post
		shuffle($matches[0]);

		// loop through each image candidate for this post and try to find a winner        
		foreach($matches[0] as $image_element) {
			
			preg_match("/src\s*=\s*(\"|')(.*?)\\1/i", $image_element, $image_src);
			
			$image_src = $image_src[2];      
	  
			// make sure we haven't displayed this image before
			if($image_src == "" || in_array($image_src, $image_srcs)) continue;

			// if a regex is supplied and it doesn't match, try next post
			if($image_src_regex != "" && !preg_match("/" . $image_src_regex . "/i", $image_src)) continue;

			if($image_class_match != "") {
				// grab the class attribute and see if it exists, if not try again
	        	preg_match("/class\s*=\s*(\"|')(.*?)\\1/i", $image_element, $image_classes);
    	    	
    	    	$image_classes = $image_classes[2];

				if($image_classes == '')	continue;

				$image_classes = explode(" ", $image_classes);
				
				if(!in_array($image_class_match, $image_classes))	continue;
			
			}

			// add img src to array to check for dups
			$image_srcs[] = $image_src;
         
			// grab the alt attribute and see if it exists, if not supply default
			preg_match("/alt\s*=\s*(\"|')(.*?)\\1/i", $image_element, $image_alt);
			
			$image_alt = $image_alt[2];

			if($image_alt == "") $image_alt = "random image";

			$img 		= pwp_image_info(preg_replace('/.*?uploads.*\/(.*)/', '$1', $image_src));
			
			$posts[] 	= array(
				'title' 	=> pwp_strip_qtlang($row['post_title']),
				'permalink' => get_permalink($row['ID']),
				'content'	=> $row['post_content'],
				'excerpt'   => $row['post_excerpt'],
				'img'		=> $img
			);
		
	      	$image_count++;
	  
	  		//returns posts
			if($image_count == $number_of_images)	return $posts;
	  
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
function pwp_image_info($id){
	
	global $wpdb;

	if(!is_numeric($id)){
		
		$reg	= preg_replace('/^.*\/([0-9]{4}\/[0-9]{2}\/)(.*)$/', '$1(.*\:")?$2', $id);
		
		$query	= mysql_query("SELECT $wpdb->postmeta.post_id FROM $wpdb->postmeta 
							   WHERE $wpdb->postmeta.meta_value REGEXP '$reg'");
		
		$r		= mysql_fetch_row($query);

		$id		= $r[0];
	
	}
	
	$a	= wp_get_attachment_metadata($id);
	
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
//function pwp_ref_cat($catID, $limit=9, $thumbdim=NULL, $width=true, $randimg=true){
function pwp_ref($args){	

	//$arg = array('numberposts'=>5, 'offset'=>0, 'category_name'=>, 'tag'=>, 'category'=>, 'orderby'=>
	//'tag=top&numberposts=3orderby=modified'
	
	if(is_array($args)){
		
		$a = $args; 
	
	} else {
		
		parse_str($args, $a);
	
	}
	
	$q = new WP_Query($a);
	
	$p = array();
	
	if($q->have_posts()){
	
		while($q->have_posts()) : $q->the_post();
	
			$row 			= array();
			$row['excerpt']	= get_the_excerpt(); //pwp_excerpt($row['content']);
			$row['url']		= get_permalink();
			$row['title']	= get_the_title();
			$row['content']	= get_the_content();
			$row['html']	= "<a href=\"$row[url]\" title=\"$row[title]\" class=\"ref title\">$row[title]</a>\n";
			$row['date']	= get_the_date();
	
				
			if(has_post_thumbnail(get_the_ID())) {	//if there's an official post thu

				preg_match_all('/(img|src)=("|\')[^"\'>]+/i', get_the_post_thumbnail(get_the_ID()), $matches);
			
			} else {								//if there's img tags in content [thumb fetch]
		
				preg_match_all('/(img|src)=("|\')[^"\'>]+/i', get_the_content(), $matches);	//matches all img tags
	
			}
		
		
		
			if(!empty($matches) && !empty($matches[0])) {

				$src = preg_replace('/(img|src)("|\'|="|=\')(.*)/i',"$3", $matches[0]);	//retrieves src tags
		
				if(is_array($src)) $src = $src[0]; 										//in case of multiple urls
				
				$img = pwp_image_info($src);
		
			} elseif($images = pwp_images(get_the_ID(), 1)) {			//if there's imgs linked to post ID [thumb fetch]
				
				$img = $images[0];
				
			} else { 
				
				$img = NULL;
			
			}
		
			if($img){
				
				$row['img']	= $img['sizes'];
				
				if(!empty($img['sizes'])){
					
					foreach($row['img'] as &$r){ 
					
						$r['src'] = $img['dir'] . $r['file']; 
					
					}
				
				}
				
		

				
				$row['thumb'] = isset($row['img']['thumbnail']) ? $row['img']['thumbnail'] : array('src' => $img['dir'] . $img['file']);
			
				$row['html']   .= "<a href=\"$row[url]\" title=\"$row[title]\" class=\"ref img\"><img src=\"" . $row['thumb']['src'] . "\"/></a>\n";
			}
	
			$row['html']   .= "<a href=\"$row[url]\" title=\"$row[title]\" class=\"ref excerpt\">$row[excerpt]</a>\n";

			array_push($p, $row);
	
		endwhile;

		return $p;
	}
	
	return false;
}




/** strips undesired lang conditional comments and content  
 * @param	s		string
 * @param	lang	if not given the function will search for the current [qtranslate language]
 * @param	empty	forces [if false] for any lang content to be returned if none is found 
 *					using he function parameters
 */
function pwp_strip_qtlang($s, $lang=NULL, $empty=false){
	
	if(function_exists('qtrans_getLanguage')){
		
		$l = is_null($lang)? qtrans_getLanguage() : $lang;
		
		preg_match("/<!--:" . $l . "-->(.*?)<!--:-->/", $s, $matches);

		if(empty($matches)){
			
			if(!$empty){
				
				preg_match("/<!--:[a-z]{2}-->(.*?)<!--:-->/", $s, $matches2);
				
				if(!empty($matches2)) {

					return $matches2[1];

				}
				
			}
				
			return $s;
		
		}

		return $matches[1];
		
	}
	
	return $s;

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
