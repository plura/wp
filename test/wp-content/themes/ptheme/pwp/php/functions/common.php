<?php

function pwp_args( $defaults , $args = false ) {

	if ( is_array( $args ) ) {

		return array_merge( $defaults, $args );

	}


	return $defaults;

}

?>