<?php

if(!isset($id)) $id = 2;

$q 		= new WP_Query("page_id=$id");

$p 		= $q->get_queried_object();

echo '{"content":'. json_encode(wpautop($p->post_content)) . '}';

?>