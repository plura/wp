<?php
/*	*	*	*	*	*	*	*	*

 	PLURA_WORDPRESS FUNCTIONS	*
 
*	*	*	*	*	*	*	*	*/
include_once('wp-refresh.php');


//remove and use pwp_get_parents//isabelsantos
function pwp_is_blog(){
	global $post;

	$is_root = ereg($_SERVER['REQUEST_URI'], get_bloginfo('url') . "/index.php");
	
	if(isset($GLOBALS['blog_cats']))
		foreach($GLOBALS['blog_cats'] as $id)	if(in_category($id) && !$is_root) return true;
	
	if(isset($GLOBALS['blog_pages']))
		foreach($GLOBALS['blog_pages'] as $id)	if(is_page() && ($post->ID == $id || $post->post_parent == $id)) return true;
	
	return false;
}



//returns a link to a post, page or category[<a href="cat_link_href">cat_name</a>]
function pwp_link($id, $cat=false, $title=NULL){
	if($cat)	$a = "<a href=\"" . get_category_link($id). "\">" . (is_null($title)? get_the_category_by_ID($id) : $title) . "</a>";
	else		$a = "<a href=\"" . get_permalink($id) . "\">" . (is_null($title)? get_the_title($id) : $title)  . "</a>";
	return $a;
}



function pwp_use_permalinks(){ $a = get_option('permalink_structure'); return !empty($a); }



function pwp_get_slug () {
	
	global $wp_query;
	
	$value    = get_query_var($wp_query->query_vars['taxonomy']);
	
	//print_r(get_queried_object()->parent);
	
	//print_r(  get_term_by('slug', $value, $wp_query->query_vars['taxonomy']);
	
}



function pwp_get_parents($self=false, $post_parents_tree=false){
	
	global $wp_query;
	
	if(is_category() || is_tax())	{
		
		$parent		= get_queried_object();					// get_category($wp_query->get_queried_object());
		
		$tax		= $parent->taxonomy;
		
	} elseif(is_page())	{
		
		$parent = get_page($wp_query->get_queried_object());
		
	} elseif(is_single()) {
		
		return pwp_get_post_parents(NULL, $post_parents_tree);
		
	} else {
		
		return array();
		
	}
	
	$ids		= $self ? is_page() ? array($parent->ID) : array($parent->term_id) : array();
	
	
	$parent_id	= is_category() || is_tax() ? $parent->parent : $parent->post_parent;

	
	while($parent_id){
		
		$parent	=  is_page() ? get_page($parent_id)	:  get_term($parent->parent, $tax); //  get_category($parent->parent);
		
		if(is_page())	{
			
			$ids[]	= $parent->ID;
			
		} else {
			
			$ids[]	= $parent->term_id;
			
		}

		
		$parent_id = 	is_page() ? $parent->post_parent : $parent->parent;
	
	}
	
	return $ids;
}



/**
 * @param	id					string number identifying the post page
 * @param	post_parents_tree	includes all the categories tree [false] or unique categories [true]
 */
function pwp_get_post_parents($id=NULL, $post_parents_tree=false){
	
	global $wp_query;
	
	$ids	= array();
	
	$obj	= get_queried_object();
	
	$ID		= is_null($id) ? $wp_query->get_queried_object()->ID : $id;
	
	$taxs	= get_object_taxonomies( $obj, 'objects' );
	
	
	reset($taxs);
	
	$tax = key($taxs); //get taxonomy term name


	$cats	= wp_get_post_terms($ID, $tax);


	foreach($cats as $key=>$value){
		
		$ids[$key]	=  array($value->term_id);
		
		$parent		= get_term($value, $tax);
		
		$parent_id	= $value;
		
		while($parent_id){
			
			$parent			= get_term($parent->parent, $tax);
			
			$ids[$key][]	= $parent->term_id;
			
			$parent_id		= $parent->parent;
		
		}
	
	}
	

	
	
	if(!$post_parents_tree){
		
		$b = array();
		
		foreach($ids as $a) {
			
			$b = array_merge($b, $a);
			
		}
		
		return array_unique($b);
	
	}
	
	return $ids;
}



		//$parent	= 		is_page()? get_page($parent_id)	: get_category($parent->parent);
		//if(is_page())	$breadcrumbs[]	= '<a href="' . get_permalink($parent->ID) . '" title="' . get_the_title($parent->ID) . '">' . get_the_title($parent->ID) . '</a>';
		//else			$breadcrumbs[]	= '<a href="' . get_category_link($parent->term_id) . '" title="' . sprintf(__("View all posts in %s"), $parent->cat_name) . '">'.$parent->cat_name.'</a>';			
		//$parent_id = 	is_page()? $page->post_parent	: $parent->parent;

/**
* @param	separator		optional string separator
* @param	id				optional post/page id
* @param	first			include first ancestor
* @param	taxonomy		taxonomy used for posts. default 'categories'
* @param	extra			separator in the end
*/
function pwp_breadcrumbs($separator=NULL, $id=NULL, $first=true, $taxonomy='category', $extra=false){
	global $wp_query;

	$obj = is_null($id)? $wp_query->get_queried_object() : get_post($id);
	
	if(!is_page($obj->ID)){

		/*$cat		= wp_get_object_terms($obj->ID, $obj->post_type == 'post'? 'category' : get_query_var('taxonomy'));
		$tax		= $cat[0]->taxonomy;
		$parent_id	= $cat[0]->term_id;*/
		
		$tax		= $obj->taxonomy;
		$parent_id	= $obj->parent;

	} else {
		
		$parent_id	= $obj->post_parent;
	
	}
	
	$breadcrumbs = array();
	
	while($parent_id){
		
		$parent	= 	is_page()? get_page($parent_id)	: get_term($parent_id, $tax);

		if (is_page()) {
			
			$a	= array('permalink' => get_permalink($parent->ID), 				'title' => get_the_title($parent->ID));
			
		} else {
			
			$a	= array('permalink' => get_term_link((int)$parent_id, $tax),	'title' => $parent->name);
			
		}
		
		$parent_id = 	is_page()? $parent->post_parent	: $parent->parent;



		if(!is_null($separator)) {
			
			$breadcrumbs[] = '<a href="' . $a['permalink'] . '" title="' . $a['title'] . '">' . $a['title'] . '</a>';
			
		} else {
			
			$breadcrumbs[] = $a;
			
		}
	}
	
	$breadcrumbs = array_reverse($breadcrumbs);
	
	if (!$first) {
		
		array_shift($breadcrumbs);
		
	}
	
	if(is_null($separator)) {
		
		return $breadcrumbs;
		
	}

	return "<span class=\"breadcrumbs\">" . implode($separator, $breadcrumbs) . ($extra? $separator : "") . "</span>";
}




/**
* because query_posts()/get_posts() eliminate conditional tags 
* a customized query is required: http://wordpress.org/support/topic/157157
* @param	cat			categoryID
* @param	number		number posts
* @return	string		posts titles
*/
function pwp_list_posts($cat, $number=5, $excerpt=false){
	$q = new WP_Query("cat=$cat&showposts=$number");
	
	if($q->have_posts()) : while ($q->have_posts()) : $q->the_post(); ?>

	<li><?php if($excerpt){ ?><h2><?php } ?>
	
    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'kubrick'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a>
	
	<?php if($excerpt){ ?></h2><?php the_excerpt(); } ?></li>   

	<?php	endwhile; endif;
}





//to allow an excerpt one must include a more metatag in wp page editor. the preg_replace will erase everything in front of it
function pwp_list_page($pages_id, $excerpt=false, $excerpt_more=" [...]\n<p><small>clique para ver mais</small></p>"){
	$q 		= new WP_Query("page_id=$pages_id");
	
	$p 		= $q->get_queried_object();

	?><li><h2><a href="<?php print $p->guid; ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'kubrick'), $p->post_title); ?>"><?php print $p->post_title; ?></a></h2><?php
	if($excerpt){ print preg_replace(array('/\s\s+/','/<!--more-->.*$/'), array('', $excerpt_more), $p->post_content); } 
	else 		{ print  apply_filters('the_content', $p->post_content); } 
	?></li><?php
}



/**
 *	gets content from post/page
 */
function pwp_content($id, $page=true){
	$q = $page? new WP_Query("page_id=$id") : new WP_Query("p=$id");

	while ($q->have_posts()) : $q->the_post();
		the_content();
	endwhile;
}



/**
* returns page info [using function within query.php WP_QUERY class]
* @return	string		page info
*/
function pwp_info($key=NULL){
	global $wp_query;
	
	if(is_category()) 	$a = array("type" => "category", "id" => $wp_query->get_queried_object_id(), "name"=>$wp_query->get_queried_object()->page_name); 
	elseif(is_page()) 	$a = array("type" => "page", 	 "id" => $wp_query->get_queried_object_id(), "name"=>$wp_query->get_queried_object()->post_title, "permalink" => $wp_query->get_queried_object()->post_name); 
	elseif(is_single()) $a = array("type" => "post", 	 "id" => $wp_query->get_queried_object_id(), "name"=>'t');
	else				{

		$a = array(
			"id"		=> $wp_query->queried_object->term_id,
			"type"		=> $wp_query->queried_object->slug,
			"name"		=> $wp_query->queried_object->name,
			"permalink" => $wp_query->queried_object->slug
		);
	}
	
	if(is_null($key)){
		return $a;
	} else {
		if(!is_null($a[$key]))	return $a[$key];
	}
	
	return false;
}



/** 
* gets images associated with a post/page
* @param	id		post/page id
*/
function pwp_images($post_id, $limit=0){
	
	global $wpdb;

	$q = "SELECT $wpdb->posts.ID FROM $wpdb->posts 
		  WHERE $wpdb->posts.post_type='attachment' 
		  AND $wpdb->posts.post_parent=$post_id";
	
	if($limit) $q .= " LIMIT $limit";

	$result = mysql_query($q);	
					  
	if(mysql_num_rows($result)){
		
		$images = array();
		
		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($images, pwp_image_info($row['ID']));
		
		return $images;
	}
	
	return false;
}


/**
* changes width/height parameters in flash embed/object tags
* @param	string		string containing flash embed/object tags
* @param	width		width parameter
* @param	height		height parameter
* @return	string		flash embed/object tag string
*/
function pwp_replace_flash_params($string, $width=NULL, $height=NULL){
	//pattern for object tag
	$p[]="/(<object width=\")([0-9]*)(\" height=\")([0-9]*)(\">)/";		$s[]="\${1}$width\${3}$height\${5}";
	//pattern for embed tag
	$p[]="/(<embed.*width=\")([0-9]*)(\" height=\")([0-9]*)(\">)/";		$s[]="\${1}$width\${3}$height\${5}";
	
	return preg_replace($p, $s, $string);
}



function pwp_backup($db_host, $db_user, $db_pass, $db_name, $path="backup.sql", $wp_prefix="wp_", $mysqldumppath="/usr/bin/mysqldump"){

	$conn		= mysql_connect($db_host, $db_user, $db_pass);
	$db			= mysql_select_db($db_name);
	
	$wp_tables	= mysql_query("SHOW TABLES LIKE '{$wp_prefix}%'");
	
	$tables 	= "";
	
	while($wp_tb = mysql_fetch_array($wp_tables)) $tables .= "$wp_tb[0] ";
	
	$query 		= sprintf( 
		"%s --add-drop-table -h %s -u %s -p%s %s %s > %s",
		$mysqldumppath,
		$db_host,
		$db_user,
		$db_pass,
		$db_name,
		$tables,
		getcwd() . "/" . $path
	);
		
	system($query, $log);
		
	return $log;
}



/**
* THERE WAS A BUG WITH wp_list_pages WP2.5 WHERE SUBPAGES WERE DISPLAYED EVEN IF PARENT WAS EXCLUDED
* UNTIL FURTHER NOTICE SUBSTITUTE IT WITH wp_list_pages FROM 2.3.3
* SEE: http://wordpress.org/support/topic/164745
* wp_list_pages has an error since WP2.5, threfore using this copy of the 2.3.3 version is advised.
* See: http://wordpress.org/support/topic/164745?replies=5#post-718595;
*/
function pwp_list_pages($args = '') {
	$defaults = array(
		'depth' => 0, 'show_date' => '',
		'date_format' => get_option('date_format'),
		'child_of' => 0, 'exclude' => '',
		'title_li' => __('Pages'), 'echo' => 1,
		'authors' => '', 'sort_column' => 'menu_order, post_title'
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$output = '';
	$current_page = 0;

	// sanitize, mostly to keep spaces out
	$r['exclude'] = preg_replace('[^0-9,]', '', $r['exclude']);

	// Allow plugins to filter an array of excluded pages
	$r['exclude'] = implode(',', apply_filters('wp_list_pages_excludes', explode(',', $r['exclude'])));

	// Query pages.
	$pages = get_pages($r);

	if ( !empty($pages) ) {
		if ( $r['title_li'] )
			$output .= '<li class="pagenav">' . $r['title_li'] . '<ul>';

		global $wp_query;
		if ( is_page() )
			$current_page = $wp_query->get_queried_object_id();
		$output .= walk_page_tree($pages, $r['depth'], $current_page, $r);

		if ( $r['title_li'] )
			$output .= '</ul></li>';
	}

	$output = apply_filters('wp_list_pages', $output);

	if ( $r['echo'] )
		echo $output;
	else
		return $output;
}
?>