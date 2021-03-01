<?php
// include WP functions
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

$currentid = '';
if(isset($_GET['currentid']) && intval($_GET['currentid'])) {
	$currentid = intval($_GET['currentid']);
}

$locations = array();

$query_string = array(
	'post_type'      => 'location',
	'posts_per_page' => -1,
);

if($currentid) {
	$query_string['post__in'] = array($currentid);
}

$locations_query = new WP_Query($query_string);

if($locations_query->have_posts()) {
	while ($locations_query->have_posts()) {
		$locations_query->the_post();
		global $post;
		
		$locationObj = new stdClass();
		
		$community = '';
		if(trim(get_post_meta($post->ID,'location-nickname',true))) {
			$locationObj->isCommunity = true;
			$community = '-community';
		}
		
		$locationObj->id = get_the_ID();
		$locationObj->title = get_the_title();
		$locationObj->lat = get_post_meta($post->ID,'location-latitude',true);
		$locationObj->lon = get_post_meta($post->ID,'location-longitude',true);
		$src = wp_get_attachment_image_src(get_post_thumbnail_id(),'popup_image');
		if($src) $locationObj->imgurl = $src[0];
		$terms = get_the_terms($post->ID,'location-type');
		$locationObj->term = $terms[0]->slug;
		$attachment = false;
		$attachment2 = false;
		if(qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug ) && !get_post_meta($post->ID,'location-use-alternative-icon',true)==1) {
			$attachment = qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug.$community );
			if(!is_object($attachment)) {
				$attachment = qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug );
			}
			else {
				$attachment2 = qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug );
			}
		}
		elseif(qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug.'-2' ) && get_post_meta($post->ID,'location-use-alternative-icon',true)==1) {
			$attachment = qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug.$community.'-2' );
			if(!is_object($attachment)) {
				$attachment = qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug.'-2' );
			}
			else {
				$attachment2 = qfm_get_attachment_by_post_name( 'marker-'.$terms[0]->slug.'-2' );
			}
		}
		if(is_object($attachment)) $locationObj->markerUrl = wp_get_attachment_url($attachment->ID);
		if(is_object($attachment2)) $locationObj->markerUrl2 = wp_get_attachment_url($attachment2->ID);
		$locationObj->permalink = get_permalink();
		if(current_user_can('edit_posts')) $locationObj->editEntryLink = get_edit_post_link();
		$locations[] = $locationObj;
	}
}
$locationsJSON = json_encode($locations);
echo $locationsJSON;
?>