<?php

include_once( dirname(__FILE__) . "/../functions/wp.php");

//urlencoding some strings is necessary because explorer's incapacity [doh] of [sometimes] correctly reading json

//$pwp_crumbs			= pwp_breadcrumbs();
//$pwp_breadcrumbs	= !empty($pwp_crumbs)? parray2json($pwp_crumbs) : 'null';


//qtranslate adds (non default) lang code to the WP site address. To get a 'clean' WP root url without 
//the lang prefix, one was to clean it via regexp. pwp_home will maintain lang code in its url.
if ( function_exists('qtranxf_getLanguage') && preg_match("/\/" . qtranxf_getLanguage() . "$/", get_bloginfo('url')) ) {

	$pwp_root = preg_replace("/" . qtranxf_getLanguage() . "$/" , "", get_bloginfo('url'));

} else {

	$pwp_root = get_bloginfo('url') . "/";

}


/* taxonomies & categories */
if ( is_tax() || is_category() ) {

	$selected = array(
		'id'		=> $wp_query->queried_object->term_id,
		'name'		=> $wp_query->queried_object->name,
		'slug'		=> $wp_query->queried_object->slug,
		'taxonomy'	=> $wp_query->queried_object->taxonomy,
		'type'		=> 'term',
		'url'		=> get_term_link( $wp_query->queried_object->term_id, $wp_query->queried_object->taxonomy )
	);


} else {

	$selected = array(
		'id'		=> $wp_query->queried_object->ID,
		'name'		=> $wp_query->queried_object->post_title,
		'slug'		=> $wp_query->queried_object->post_name,
		'type' 		=> $wp_query->queried_object->post_type,
		'url'		=> get_permalink( $wp_query->queried_object->ID )
	);


	$taxonomies		= get_object_taxonomies( $wp_query->queried_object->post_type );


	if (!empty($taxonomies)) {

		$tax = array();

		foreach($taxonomies as $k => $taxonomy) {

			$terms = wp_get_object_terms(get_the_ID(), $taxonomy);

			if(!empty( $terms )) {

				$a = array(
					'taxonomy'	=> $taxonomy,
					'terms'		=> array()
				);

				foreach( $terms as $term ) {

					$a['terms'][] = array(
						'id' 	=> $term->term_id,
						'name'	=> $term->name,					
						'slug'	=> $term->slug
					);

				}

				$tax[] = $a;

			}

		}

		if (!empty($tax)) {

			$selected['taxonomies'] = $tax;

		}

	}

}


$PWP = array(
	'index' 		=> '',
	'dir'			=> get_bloginfo('template_directory') . '/pwp/',
	'home'			=> get_bloginfo('url') . '/',
	'front_page'	=> is_front_page() ? 1 : 0,
	'permalinks'	=> pwp_use_permalinks() ? 1 : 0,
	'root'			=> $pwp_root,
	'selected'		=> $selected,
	'template_dir'	=> get_bloginfo('template_directory') . '/'
);


$PWP_FLASHVARS = array(
	'wp_dir'			=> $PWP['dir'],
	'wp_front_page'		=> $PWP['front_page'] ? 'true' : 'false',
	'wp_permalinks'		=> $PWP['permalinks'] ? 'true' : 'false',
	'wp_root'			=> $PWP['root'],
	'wp_selected_id'	=> $PWP['selected']['id'],
	'wp_selected_name'	=> $PWP['selected']['name'],
	'wp_selected_type'	=> $PWP['selected']['type'],
	'wp_selected_url'	=> $PWP['selected']['url'],
	'wp_template_dir'	=> $PWP['template_dir']
);


if ( function_exists('qtranxf_getLanguage') ) {

	$PWP['lang'] 				= qtranxf_getLanguage();

	$PWP_FLASHVARS['wp_lang']	= $PWP['lang'];

}

?>
<script type="text/javascript">var PWP = <?php echo json_encode( $PWP ); ?>, PWP_FLASHVARS = <?php echo json_encode( $PWP_FLASHVARS ); ?>;</script>
