<?php

function pwp_plugin_filter_list( $type, $taxonomies ) {
    
    $screen = get_current_screen();
    
    global $wp_query;
    
    if ( $screen->post_type == $type ) {

    	foreach ($taxonomies as $taxonomy) {

	    	$taxonomy_obj	= get_taxonomy( $taxonomy );
	        
	        wp_dropdown_categories( array(
	            'show_option_all'	=> __("Show All {$taxonomy_obj->label}"),
	            'taxonomy'			=> $taxonomy,
	            'name'				=> $taxonomy,
	            'orderby'			=> 'name',
	            'selected'			=> ( isset( $wp_query->query[$taxonomy] ) ? $wp_query->query[$taxonomy] : '' ),
	            'hierarchical'		=> true,
	            'show_count'		=> false
	        ) );

		}
	
	}

}



function pwp_plugin_filter_query($query, $type) {
    
    global $pagenow;
    global $typenow;

    //echo "<div style=\"margin: 0 0 0 300px;\">";

    if ($pagenow == 'edit.php' && $typenow === $type) {

        $filters = get_object_taxonomies( $typenow );
       
        foreach ($filters as $tax_slug) {
        
            $var = &$query->query_vars[$tax_slug];

            //echo "$var : $tax_slug<br/>";
        
            if ( isset($var) ) {

               //echo "var> " . $var . " | slug>" . $tax_slug . "<br/>";
        
                $term = get_term_by('id', $var, $tax_slug);
        
                $var = $term->slug;

                //echo $var . "<br/>";
        
            }
        
        }
    
    }

    //echo "</div>";

}

?>