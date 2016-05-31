<?php

//$query = 'tag=top&numberposts=3orderby=modified';
//$query = 'category=101&numberposts=3&orderby=modified';
//$query = "posts_per_page=6&post_type=models";

if ( isset( $_REQUEST['query'] )  && !empty( $_REQUEST['query'] ) ) {

	$return = pwp_ref( $_REQUEST['query'] );

}

/*if(function_exists('json_encode'))	{
	
	echo json_encode( pwp_ref( $_REQUEST['query'] ) );
	
} else {
	
	echo parray2json( pwp_ref( $_REQUEST['query'] ) );
	
}*/

?>