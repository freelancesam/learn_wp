<?php
global $rd_data;
$post=$wp_query->post;
$post_design = $rd_data['rd_blog_design_type'];
if(get_post_type() == 'portfolio'){
	include('single-portfolio.php');
}elseif(get_post_type() == 'partners'){
	include('single-staff.php');
}
elseif($post_design == 'business'){
	include('single-business.php');
}
else{
	include('single-default.php');
}

?>