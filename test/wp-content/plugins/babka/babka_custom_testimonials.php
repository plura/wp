<?php

//CUSTOM POST TYPE name limit is 20 charachters !!!! 

add_action( 'init', 											'custom_type_babka_testimonials_register' );

add_action( 'admin_init',										'custom_type_babka_testimonials_admin' );

//add_action( 'save_post',										'custom_save_babka_testimonials_details' );


//custom type creation
function custom_type_babka_testimonials_register(){

	//item
	register_post_type('babka_testimonials', array(
		'labels'				=> array(
		
			'name'					=> _x('Testemunhos', 'post type general name'),
			'singular_name' 		=> _x('Testemunho', 'post type singular name'),
			'add_new'				=> _x('Adicionar Novo', 'partner item'),
			'add_new_item'			=> __('Adicionar Nova Testemunho'),
			'edit'					=> __('Editar'),			
			'edit_item'				=> __('Editar Testemunho'),
			'new_item'				=> __('Nova Testemunho'),
			'view_item'				=> __('Ver Testemunho'),
			'search_items'			=> __('Procurar Testemunhos'),
			'not_found'				=> __('Nada Foi Encontrado'),
			'not_found_in_trash'	=> __('Nada Foi Encontrado no Trash'),
			'parent_item_colon'		=> ''
		
		),
		'menu_position'			=> null,
		'public'				=> true,
		'publicly_queryable'	=> true,
		'query_var'				=> true,
		'show_in_nav_menus'		=> true,		
		'show_ui'				=> true,
		'menu_icon'				=> 'dashicons-groups',			
		'rewrite'				=> array("slug" => "testimonial"),
		'hierarchical'			=> false,
		'supports'				=> array('title','editor','thumbnail')
	 ));	
	
}



//custom type custom input ui
function custom_type_babka_testimonials_admin(){

	wp_enqueue_style('my_meta_css',  '/wp-content/plugins/babka/styles.css');	
	
	//add_meta_box("custom_fields_main_meta", "Marcas", "custom_ui_babka_testimonials_info", "babka_testimonials", "side", "low");

}



//custom fields UI
function custom_ui_babka_testimonials_info(){

	global $post;
	
	$post_brands	= get_post_meta( $post->ID, 'area_brands', TRUE ); //used to get array info from meta value

	$brands			= get_objects( 'babka_brands' );

	//$terms				= get_terms('babka_products_brands');

	wp_nonce_field( plugin_basename( __FILE__ ), 'babka_testimonials_info_content_nonce' );	


	foreach($brands as $brand) {

?>

<fieldset>

	<label for="<?php echo $brand->post_name ?>">

	<input type="checkbox" id="<?php echo  $brand->post_name ?>" name="area_brands[]" value="<?php echo $brand->ID ?>"<?php

		if ($post_brands && in_array( $brand->ID, $post_brands) ) {

			echo " checked";

		}

	?>><?php echo $brand->post_title ?></label>

</fieldset>

<?php

	}

}



//custom save
/*function custom_save_babka_testimonials_details($post_id){

	// authentication checks
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		
		return;
 
    // make sure data came from our meta box
	if ( !wp_verify_nonce( $_POST['babka_testimonials_info_content_nonce'], plugin_basename( __FILE__ ) ) )
	
		return $post_id;
		
 	// check user permissions
    if ($_POST['post_type'] == 'page')	{

       	if (!current_user_can('edit_page', $post_id))
		
			return $post_id;
    
	} else {
        
		if (!current_user_can('edit_post', $post_id))
		
			return $post_id;
    
	}


 	update_post_meta($post_id, "area_brands", $_POST["area_brands"] );


}*/


?>