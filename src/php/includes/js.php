<?php

//urlencoding some strings is necessary because explorer's incapacity [doh] of [sometimes] correctly reading json

//$pwp_crumbs			= pwp_breadcrumbs();
//$pwp_breadcrumbs	= !empty($pwp_crumbs)? parray2json($pwp_crumbs) : 'null';



//qtranslate adds (non default) lang code to the WP site address. To get a 'clean' WP root url without 
//the lang prefix, one was to clean it via regexp. pwp_home will maintain lang code in its url.
if ( function_exists('qtrans_getLanguage') && preg_match("/\/" . qtrans_getLanguage() . "$/", get_bloginfo('url')) ) {

	$pwp_root = preg_replace("/" . qtrans_getLanguage() . "$/" , "", get_bloginfo('url'));

} else {

	$pwp_root = get_bloginfo('url') . "/";

}


if(defined('WPLANG')){ ?>

<!-- Plura™: CORE JS -->
<script type="text/javascript">

var PWP = {
		index:			"<?php //print $URL_INDEX; ?>",//"http://" . $_SERVER['HTTP_HOST'] .
		root:			"<?php print $pwp_root; ?>",
		home:			"<?php print get_bloginfo('url') . "/"; ?>",
		dir:			"<?php print PWP_URL; ?>",
		front_page:		<?php print (is_front_page() ? 'true' : 'false'); ?>,
		lang:			<?php print function_exists('qtrans_getLanguage')? "'". qtrans_getLanguage() . "'" : 'null'; ?>,
		permalinks:		<?php print pwp_use_permalinks()? "true" : "false";		?>,
		template_dir:	"<?php print get_bloginfo('template_directory') . "/";	?>",
		selected:		{
			id:			<?php	print pwp_info('id') ?			pwp_info('id')	 		: 'null'; ?>,
			type:		'<?php	print pwp_info('type') ?		pwp_info('type') 		: 'null'; ?>',
			name:		'<?php	print pwp_info('name') ?		pwp_info('name') 		: 'null'; ?>',
			permalink:	'<?php	print pwp_info('permalink') ?	pwp_info('permalink')	: 'null'; ?>'	
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

<?php } ?>