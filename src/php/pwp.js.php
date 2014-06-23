<?php
include_once('fn.php');

//urlencoding some strings is necessary because explorer's incapacity [doh] of [sometimes] correctly reading json

//$pwp_crumbs			= pwp_breadcrumbs();
//$pwp_breadcrumbs	= !empty($pwp_crumbs)? parray2json($pwp_crumbs) : 'null';

if(defined('WPLANG')){ ?>

<!-- Plura™: CORE JS -->
<script type="text/javascript">
var pwp_index 			= "<?php //print $URL_INDEX; ?>",//"http://" . $_SERVER['HTTP_HOST'] .
	//pwp_root			= "<?php print preg_replace('/\\\/','/', realpath('.')); ?>",
	pwp_root 			= "<?php print get_bloginfo('url') . "/"; ?>",
	pwp_dir 			= "<?php print PWP_URL; ?>",
	pwp_front_page		= <?php	 print (is_front_page()? 'true' : 'false'); ?>,
	pwp_lang			= <?php  print function_exists('qtrans_getLanguage')? "'".qtrans_getLanguage() . "'" : 'null'; ?>,
	pwp_permalinks		= <?php print pwp_use_permalinks()? "true" : "false";		?>,
	pwp_template_dir	= "<?php print get_bloginfo('template_directory') . "/";	?>",
	pwp_selected		= {
		id:			 <?php	print pwp_info('id') ?			pwp_info('id')	 		: 'null'; ?>,
		type:	  	'<?php	print pwp_info('type') ?		pwp_info('type') 		: 'null'; ?>',
		name:	  	'<?php	print pwp_info('name') ?		pwp_info('name') 		: 'null'; ?>',
		permalink:	'<?php	print pwp_info('permalink') ?	pwp_info('permalink')	: 'null'; ?>'	
	};

var pwp_flashvars		= {
	wp_breadcrumbs:			'<?php //print urlencode($pwp_breadcrumbs); ?>',
	wp_dir:					pwp_dir,
	wp_front_page:			<?php print (is_front_page()? "'true'" : "''"); ?>,
	wp_index:				pwp_index,
	wp_permalinks:			pwp_permalinks,
	wp_root:				pwp_root,
	wp_selected_id:			pwp_selected.id,
	wp_selected_type:		pwp_selected.type,
	wp_selected_name:		pwp_selected.name,
	wp_selected_permalink:	pwp_selected.permalink,
	wp_template_dir:		pwp_template_dir
};
</script>

<?php } ?>