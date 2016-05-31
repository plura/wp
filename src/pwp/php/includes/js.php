<?php

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

if (is_tax()) {

	$js_obj_taxonomies	= $js_obj_categories = "false";

	$js_obj_type		= "taxonomy";


} else {

	$taxonomies			= get_object_taxonomies( $wp_query->queried_object->post_type );


	$categories			= wp_get_object_terms(get_the_ID(), $taxonomies[0]);


	$js_obj_taxonomies	= !$taxonomies ? "false" : "[\"" . implode('", "', $taxonomies) . "\"]";


	if (!empty( $categories) ) {

		$js_obj_categories	= array();

		foreach($categories as $category) {

			$js_obj_categories[] = "{id: " . $category->term_id . ", slug: \"" . $category->slug . "\", name: \"" . $category->name . "\"}";

		}

	}

	$js_obj_categories	= !empty( $categories ) ? "[" . implode(' ,', $js_obj_categories ) . "]" : "false";

	$js_obj_type		= $wp_query->queried_object->post_type;

}



?>

<!-- Plura™: CORE JS -->
<script type="text/javascript">

var PWP = {
		index:			"<?php //print $URL_INDEX; ?>",//"http://" . $_SERVER['HTTP_HOST'] .
		root:			"<?php print $pwp_root; ?>",
		home:			"<?php print get_bloginfo('url') . "/"; ?>",
		dir:			"<?php print PWP_URL; ?>",
		front_page:		<?php print (is_front_page() ? 'true' : 'false'); ?>,
		lang:			<?php print function_exists('qtranxf_getLanguage')? "'". qtranxf_getLanguage() . "'" : 'null'; ?>,
		permalinks:		<?php print pwp_use_permalinks()? "true" : "false";		?>,
		template_dir:	"<?php print get_bloginfo('template_directory') . "/";	?>",
		selected:		{
			taxonomies:	<?php echo $js_obj_taxonomies; ?>,
			categories:	<?php echo $js_obj_categories; ?>,
			id:			<?php	print pwp_info('id') ?			pwp_info('id')	 		: 'null'; ?>,
			type:		"<?php	echo $js_obj_type; ?>",
			name:		"<?php	print pwp_info('name') ?		pwp_info('type') 		: 'null'; ?>",
			permalink:	"<?php	print pwp_info('permalink') ?	pwp_info('permalink')	: 'null'; ?>"	
		}
	},

	pwp_flashvars		= {
		wp_breadcrumbs:			'<?php //print urlencode($pwp_breadcrumbs); ?>',
		wp_dir:					PWP.dir,
		wp_front_page:			<?php print (is_front_page()? "'true'" : "''"); ?>,
		wp_index:				PWP.index,
		wp_permalinks:			PWP.permalinks,
		wp_root:				PWP.root,
		wp_selected_id:			PWP.selected.id,
		wp_selected_type:		PWP.selected.type,
		wp_selected_name:		PWP.selected.name,
		wp_selected_permalink:	PWP.selected.permalink,
		wp_template_dir:		PWP.template_dir
	};

</script>
