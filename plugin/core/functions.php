<?php


//allow attachments categories
function pwp_add_categories_to_attachments() {
    
	register_taxonomy_for_object_type( 'category', 'attachment' );

}




//translate taxonomies
function pwp_qtranslate_edit_taxonomies(){
  
	$args=array(
    	'public' => true ,
		'_builtin' => false
	);
  
	$output = 'object'; // or objects
	$operator = 'and'; // 'and' or 'or'

	$taxonomies = get_taxonomies($args, $output, $operator); 

	if  ($taxonomies) {

		foreach ($taxonomies  as $taxonomy ) {

			add_action( $taxonomy->name . '_add_form', 'qtrans_modifyTermFormFor');

			add_action( $taxonomy->name . '_edit_form', 'qtrans_modifyTermFormFor');        
	
		}
	
	}

}





function pwp_custom_ui_select ( $args ) {

	$html	= "";

	$type	= !isset($args['type']) ? "select" : $args['type'];

	if ($type === 'select') {

		if (isset($args['label'])) {

			$html .= "<label for=\"" . $args['name'] . "\">" . $args['label'] . "</legend>\n";

		}

		$html .= "<select name=\"" . $args['name'] . "\">\n";

		$html .= "<option/>";

	} else {

		$html .= "<fieldset>";		

		if (isset($args['label'])) {

			$html .= "<legend>" . $args['label'] . "</legend>\n";

		}

	}

	foreach ($args['items'] as $item) {


		$selected = "";

		if (isset($args['selected'])) {

			if ($type === 'checkbox' && is_array($args['selected']) && in_array($item['value'], $args['selected'])) {

				$selected = "checked";

			} elseif (preg_match("/(checkbox|radio)/", $type) && $item['value'] == $args['selected']) {

				$selected = "checked";

			} elseif ($item['value'] == $args['selected']) {

				$selected = "selected";

			}

		}


		if ($type === 'select' ) {

			$html .= "<option value=\"" . $item['value'] . "\" " . $selected . ">" . $item['name'] . "</option>\n";

		} else {

			$html .= "<label>" . $item['name'] . "</label>\n";

			$html .= "<input value=\"" . $item['value'] . "\" type=\"" . $type . "\" name=\"" . $args['name'];

			if ($type === 'checkbox') {

				$html .= "[]";

			}

			$html .= "\" " . $selected . "/>\n";

		}

	
	}


	if ($type === 'select') {

		$html .= "</select>\n";

	} else {

		$html .= "</fieldset>\n";

	}


	return $html;

}




?>