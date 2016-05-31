<?php
/*	*	*	*	*	*	*	*	*

 	PLURA_WORDPRESS FUNCTIONS	*
 
*	*	*	*	*	*	*	*	*/

include_once( dirname( __FILE__ ) . "/common.php");


/**
 * Returns posts parents [ post algoritm inspiration: http://codex.wordpress.org/Function_Reference/get_the_terms ]
 * @param	int $id post categoryID
 * @return	array post parents
 */
function pwp_get_post_parents( $id ) {

	$post = get_post( $id );

	if ( $post->post_type === 'page' ){


		if ( $post->post_parent ) {

			$parents = array();

			while( $post->post_parent ) {

				$post 		= get_post( $post->post_parent );

				$parents[]	= $post;

			}

			return $parents;

		}

	} else {

		$parents	= array();

		$taxonomies = get_object_taxonomies( $post->post_type, 'objects' );

 		foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {

 			$terms = get_the_terms( $post->ID, $taxonomy_slug );

 			if ($terms) {

 				$parents[ $taxonomy_slug ] = array('name' => $taxonomy->label, 'terms' => array());

 				foreach( $terms as $term ) {

 					$parents[ $taxonomy_slug ]['terms'][] = pwp_get_term_parents( $term->term_id, $taxonomy_slug, true );

 				}

 			}

 		}

 		return $parents;
		
	}

	return false;

}


function pwp_get_post_parents_ids( $id ) {

	$parents = pwp_get_post_parents( $id );

	if ($parents) {

		$a = array();

		foreach( $parents as $parent ) {

			$a[] = $parent->ID;

		}

		return $a;

	}

	return false;

}



/**
 * @param	int $id term ID
 * @param	strimng $taxonomy the string identiying the term's taxonomy
 * @return	array post parents
 */
function pwp_get_term_parents( $id = false, $taxonomy = false, $self = false ) {

	if ( !$id && !$taxonomy ) {

		$term = get_queried_object();

	} else {

		$term = get_term( $id, $taxonomy );

	}

	if ( $term->parent || $self ) {

		$parents = array();

		if ( $self ) {

			$parents[] = $term;

		}

		while( $term->parent ) {

			$term 		= get_term( $term->parent, $taxonomy );

			$parents[]	= $term;

		}

		return $parents;

	}

	return false;

}



/**
 * @param	int $id the post or page ID
 * @param	booleam $group indicates if breadcrumbs should be grouped by taxonomy (post only)
 * @param	booleam $taxonomy indicates if breadcrumbs should be grouped by taxonomy (post only)
 * @return	html
 */
function pwp_post_breadcrumbs( $args ) {

	if ( is_int( $args ) ) {

		$args = array('id' => $args);

	}

	$args = pwp_args( array(
		'classes'	=> '',
		'tree'		=> true
	), $args );

	$post		= get_post( $args['id'] );

	$parents	= pwp_get_post_parents( $args['id'] );

	if ( $parents ) {

		$classes = array('breadcrumbs');

		if (!empty( $args['classes']) ) {

			if (is_array( $args['classes'] )) {

				$classes = array_merge( $classes, $args['classes'] );

			} elseif( is_string( $args['classes'] ) ) {

				$classes = array_merge( $classes, explode(' ', $args['classes']) );

			}

		}

		$html = "<div class=\"" . implode(' ', $classes ) . "\">";		

		if ( $post->post_type === 'page' ) {

			$html 		.= "<ul class=\"parents\">\n";

			$parents	= array_reverse( $parents );

			foreach( $parents as $parent ) {

				$html .= "<li class=\"parent parent-id-" . $parent->ID . "\">\n";

				$html .= "<a href=\"" . get_permalink( $parent->ID ) . "\" title=\"" . get_the_title( $parent->ID) . "\">\n";

				$html .= get_the_title( $parent->ID);

				$html .= "</a>\n</li>\n";

			}

			$html .= "</ul>\n";		

		} else {

			if ( $args['tree'] ) {

				$html .= "<ul class=\"taxonomies\">\n";

			}

			foreach( $parents as $taxonomy_slug => $taxonomy ) {

				if ($args['tree']) {

					$html .= "<li class=\"taxonomy " . $taxonomy_slug . "\">\n";

					$html .= "<span class=\"taxonomy-label\">" . $taxonomy['name'] . "</span>\n<ul>\n";

				}

				foreach( $taxonomy['terms'] as $g => $terms_group ) {

					if ($args['tree']) {

						$html .= "<li><span class=\"term-group-label\">" . $g . "</span>\n";

					}

					$html .= "<ul class=\"parents\">\n";

					$terms	= array_reverse( $terms_group );

					foreach( $terms as $term ) {

						$html .= "<li class=\"parent parent-id-" . $term->term_id . " " . $term->slug . "\" data-id=\"" . $term->term_id . "\">";

						$html .= "<a href=\"" . get_term_link( $term ) . "\" title=\"" . $term->name . "\">\n";

						$html .= $term->name;

						$html .= "</a>\n</li>\n";

					}

					$html .= "</ul>\n";

					if ($args['tree']) {

						$html .= "</li>\n";

					}

				}

				if ($args['tree']) {
					
					$html .= "</ul>\n</li>\n";

				}

			}

			if ($args['tree']) {			

				$html .= "</ul>\n";

			}

		}

		$html .= "</div>";

		return $html;

	}

	return false;

}




function pwp_term_breadcrumbs( $term ) {

	$parents 	= pwp_get_term_parents( $term->term_id, $term->taxonomy );

	if ( $parents ) {

		$html = "<div class=\"breadcrumbs\"><ul class=\"parents\">\n";

		foreach( $parents as $parent ) {

			$html .= "<li class=\"parent parent-id-" . $parent->term_id . "\">\n";

			$html .= "<a href=\"" . get_term_link( $parent ) . "\">" . $parent->name;

			$html .= "</a>\n</li>\n";

		}

		$html .= "</div>";

		return $html;

	}

	return false;

}


?>