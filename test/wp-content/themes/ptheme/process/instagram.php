<?php

/**
 * 1. use this to get userid: http://jelled.com/instagram/lookup-user-id
 * 2. use this to get token: https://instagram.com/oauth/authorize/?client_id=[CLIENTID]&redirect_uri=[SITE]&response_type=token
 * 3. Dont's forget to uncheck 'Disable implicit OAuth' in developer's client option
 */

// Supply a user id and an access token
$userid			= "2209132098";

$accessToken	= "2209132098.e681597.26ce8114f841433b9cead1766ce4801e";

$count			= 20;

$api_url		= "https://api.instagram.com/v1/users/{$userid}/media/recent/?access_token={$accessToken}&count={$count}";

if ( isset( $_GET['max_id'] ) ) {

	$api_url .= '&max_id=' . $_GET['max_id'];

}


/*$api_url .= '&max_id=1097265384041579153_1211182143';

echo $api_url;*/

// Gets our data
function fetchData( $url ) {
     
	$ch = curl_init();
     
    curl_setopt($ch, CURLOPT_URL, $url);
     
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //to allow localhost instagram api access   

	$result = curl_exec($ch);

	curl_close($ch); 
     
	return $result;

}


function has_graphic_diary_tags( $source ) {

	$tags = array('graphicdiary', 'guests', 'sketches', 'testimonial', 'testimonials');

	foreach( $tags as $tag ) {

		if (in_array($tag, $source)) {

			return true;

		}

	}

	return false;

}


// Pulls and parses data.
$result = fetchData( $api_url );


//json-it
$result = json_decode( $result );


//print_r($result); exit;

if ( isset( $result->data ) ) {


	foreach ($result->data as $image) {


		if( !has_graphic_diary_tags( $image->tags ) )	{

			continue;

		}


		$img = $image->images->low_resolution;

?>

<div class="plura-c-xs-1-2 plura-c-sm-1-3 grid-item" data-id="<?php echo $image->id; ?>">

	<div class="holder">

		<a href="<?php echo $image->link; ?>">
		
			<img src="<?php echo $img->url; ?>" width="<?php echo $img->width; ?>" height="<?php echo $img->height; ?>" />

		</a>

	</div>

</div>

<?php

	}


}

?>