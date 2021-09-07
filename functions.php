<?php
/**
 * Quartiere fuer Menschen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Quartiere fuer Menschen
 */
 
/**
 * Translations for theme meta
 */
esc_html__('Quartiere fuer Menschen','qfm');
esc_html__('WordPress theme for Quartiere fuer Menschen project by ADFC Hamburg.','qfm');
esc_html__('https://stadtkreation.de/en/wordpress-themes-and-plugins/','qfm');
esc_html__('https://stadtkreation.de/en/about-us/','qfm');

define('QFM_FILE', __FILE__ );
define('QFM_PATH', dirname( __FILE__ ) );

// only load admin if needed
if ( is_admin() ) {
	require_once( QFM_PATH . '/include/admin.php' );
}

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '0.7' );
}

if ( ! function_exists( 'qfm_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function qfm_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Quartiere fuer Menschen, use a find and replace
		 * to change 'qfm' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'qfm', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'qfm' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'qfm_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'qfm_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function qfm_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'qfm_content_width', 640 );
}
add_action( 'after_setup_theme', 'qfm_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function qfm_widgets_init() {
	$sidebars = array(
		'sidebar' => esc_html__( 'Bottom widgets section', 'qfm' ),
		'topbar' => esc_html__( 'Top bar', 'qfm' ),
		'footer-left' => esc_html__( 'Footer left', 'qfm' ),
		'footer-right' => esc_html__( 'Footer right', 'qfm' ),
	);
	foreach($sidebars as $id => $name) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'description'   => esc_html__( 'Add widgets here.', 'qfm' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'qfm_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function qfm_scripts() {
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'qfm-fonts', get_template_directory_uri() . '/fonts/fonts.css', array(), _S_VERSION );
	wp_enqueue_style( 'qfm-leaflet-css', get_template_directory_uri() . '/leaflet/leaflet.css', array(), _S_VERSION );
	wp_enqueue_style( 'qfm-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'qfm-style', 'rtl', 'replace' );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'qfm-leaflet', get_template_directory_uri() . '/leaflet/leaflet.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'qfm-leaflet-ajax', get_template_directory_uri() . '/leaflet/leaflet.ajax.min.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'qfm-leaflet-singleclick', get_template_directory_uri() . '/leaflet/leaflet-singleclick.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'qfm-functions', get_template_directory_uri() . '/js/functions.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'qfm-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'qfm_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function qfm_admin_scripts() {
	wp_enqueue_style( 'qfm-leaflet-css', get_template_directory_uri() . '/leaflet/leaflet.css', array(), _S_VERSION );
	wp_enqueue_style( 'qfm-admin-css', get_template_directory_uri() . '/admin-style.css', array(), _S_VERSION );

	wp_enqueue_script( 'qfm-leaflet', get_template_directory_uri() . '/leaflet/leaflet.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'qfm-leaflet-ajax', get_template_directory_uri() . '/leaflet/leaflet.ajax.min.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'qfm-leaflet-singleclick', get_template_directory_uri() . '/leaflet/leaflet-singleclick.js', array(), _S_VERSION, true );	
	wp_enqueue_script( 'qfm-functions', get_template_directory_uri() . '/js/functions.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'qfm-admin-js', get_template_directory_uri() . '/js/functions-admin.js', array(), _S_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'qfm_admin_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Theme admin notices
 */
function qfm_front_page_admin_notice(){
    if(!get_option('page_on_front')) {
         echo '
		<div class="notice notice-warning">
			<p>'.esc_html__('Warning: Quartiere fuer Menschen theme only works correctly when a front page has been selected in Settings > Reading.','qfm').'</p>
		</div>';
    }
}
add_action('admin_notices', 'qfm_front_page_admin_notice');

/**
 * Register custom post types
 */
function qfm_custom_post_types() {

	$labels = array(
		'name'                  => _x( 'Locations', 'Post Type General Name', 'qfm' ),
		'singular_name'         => _x( 'Location', 'Post Type Singular Name', 'qfm' ),
		'menu_name'             => esc_html__( 'Locations', 'qfm' ),
		'name_admin_bar'        => esc_html__( 'Location', 'qfm' ),
		'archives'              => esc_html__( 'Location Archives', 'qfm' ),
		'attributes'            => esc_html__( 'Location Attributes', 'qfm' ),
		'parent_item_colon'     => esc_html__( 'Parent Location:', 'qfm' ),
		'all_items'             => esc_html__( 'All Locations', 'qfm' ),
		'add_new_item'          => esc_html__( 'Add New Location', 'qfm' ),
		'add_new'               => esc_html__( 'Add New', 'qfm' ),
		'new_item'              => esc_html__( 'New Location', 'qfm' ),
		'edit_item'             => esc_html__( 'Edit Location', 'qfm' ),
		'update_item'           => esc_html__( 'Update Location', 'qfm' ),
		'view_item'             => esc_html__( 'View Location', 'qfm' ),
		'view_items'            => esc_html__( 'View Locations', 'qfm' ),
		'search_items'          => esc_html__( 'Search Location', 'qfm' ),
		'not_found'             => esc_html__( 'No Location found', 'qfm' ),
		'not_found_in_trash'    => esc_html__( 'No Location found in Trash', 'qfm' ),
		'featured_image'        => esc_html__( 'Featured Image', 'qfm' ),
		'set_featured_image'    => esc_html__( 'Set featured image', 'qfm' ),
		'remove_featured_image' => esc_html__( 'Remove featured image', 'qfm' ),
		'use_featured_image'    => esc_html__( 'Use as featured image', 'qfm' ),
		'insert_into_item'      => esc_html__( 'Insert into item', 'qfm' ),
		'uploaded_to_this_item' => esc_html__( 'Uploaded to this Location', 'qfm' ),
		'items_list'            => esc_html__( 'Locations list', 'qfm' ),
		'items_list_navigation' => esc_html__( 'Locations list navigation', 'qfm' ),
		'filter_items_list'     => esc_html__( 'Filter Locations list', 'qfm' ),
	);
	$args = array(
		'label'                 => esc_html__( 'Location', 'qfm' ),
		'description'           => esc_html__( 'Locations for Quartiere fuer Menschen WordPress theme.', 'qfm' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'revisions', 'thumbnail', 'comments' ),
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 7,
		'menu_icon'             => 'dashicons-location',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'location', $args );
	
	/*$labels = array(
		'name'                  => _x( 'Events', 'Post Type General Name', 'qfm' ),
		'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'qfm' ),
		'menu_name'             => esc_html__( 'Events', 'qfm' ),
		'name_admin_bar'        => esc_html__( 'Event', 'qfm' ),
		'archives'              => esc_html__( 'Event Archives', 'qfm' ),
		'attributes'            => esc_html__( 'Event Attributes', 'qfm' ),
		'parent_item_colon'     => esc_html__( 'Parent Event:', 'qfm' ),
		'all_items'             => esc_html__( 'All Events', 'qfm' ),
		'add_new_item'          => esc_html__( 'Add New Event', 'qfm' ),
		'add_new'               => esc_html__( 'Add New', 'qfm' ),
		'new_item'              => esc_html__( 'New Event', 'qfm' ),
		'edit_item'             => esc_html__( 'Edit Event', 'qfm' ),
		'update_item'           => esc_html__( 'Update Event', 'qfm' ),
		'view_item'             => esc_html__( 'View Event', 'qfm' ),
		'view_items'            => esc_html__( 'View Events', 'qfm' ),
		'search_items'          => esc_html__( 'Search Event', 'qfm' ),
		'not_found'             => esc_html__( 'No Event found', 'qfm' ),
		'not_found_in_trash'    => esc_html__( 'No Event found in Trash', 'qfm' ),
		'featured_image'        => esc_html__( 'Featured Image', 'qfm' ),
		'set_featured_image'    => esc_html__( 'Set featured image', 'qfm' ),
		'remove_featured_image' => esc_html__( 'Remove featured image', 'qfm' ),
		'use_featured_image'    => esc_html__( 'Use as featured image', 'qfm' ),
		'insert_into_item'      => esc_html__( 'Insert into item', 'qfm' ),
		'uploaded_to_this_item' => esc_html__( 'Uploaded to this Event', 'qfm' ),
		'items_list'            => esc_html__( 'Events list', 'qfm' ),
		'items_list_navigation' => esc_html__( 'Events list navigation', 'qfm' ),
		'filter_items_list'     => esc_html__( 'Filter Events list', 'qfm' ),
	);
	$args = array(
		'label'                 => esc_html__( 'Event', 'qfm' ),
		'description'           => esc_html__( 'Events for Quartiere fuer Menschen WordPress theme.', 'qfm' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'revisions', 'thumbnail' ),
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 8,
		'menu_icon'             => 'dashicons-calendar',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'event', $args );*/

}
add_action( 'init', 'qfm_custom_post_types', 0 );

/**
 * Register custom taxonomies
 */
function qfm_custom_taxonomies() {
	$labels = array(
		'name'              => esc_html_x( 'Location Types', 'taxonomy general name', 'qfm' ),
		'singular_name'     => esc_html_x( 'Location Type', 'taxonomy singular name', 'qfm' ),
		'search_items'      => esc_html__( 'Search Location Types', 'qfm' ),
		'all_items'         => esc_html__( 'All Location Types', 'qfm' ),
		'parent_item'       => esc_html__( 'Parent Location Type', 'qfm' ),
		'parent_item_colon' => esc_html__( 'Parent Location Type:', 'qfm' ),
		'edit_item'         => esc_html__( 'Edit Location Type', 'qfm' ),
		'update_item'       => esc_html__( 'Update Location Type', 'qfm' ),
		'add_new_item'      => esc_html__( 'Add New Location Type', 'qfm' ),
		'new_item_name'     => esc_html__( 'New Location Type Name', 'qfm' ),
		'menu_name'         => esc_html__( 'Location Types', 'qfm' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'location-type' ),
	);

	register_taxonomy( 'location-type', array( 'location' ), $args );
}
add_action( 'init', 'qfm_custom_taxonomies', 0 );

/**
 * Shortcode [qfm-map]
 */
function qfm_shortcode_qfm_map($atts) {
	$output = '';
	
	$a = shortcode_atts( array(
		'zoom' => 15,
		'lat' => 53.57744,
		'lon' => 9.94786,
		'maxzoom' => '',
		'minzoom' => '',
		'showentry-zoom' => '', 
		'bounds-ne' => '',
		'bounds-sw' => '',
		'minzoom-setmarker' => 13,
		'singlezoom' => 18,
	), $atts );
	
	// setmarker handling
	$setmarker = false;
	$minzoomSetmarker = $a['minzoom-setmarker'];
	if($atts && in_array('setmarker',$atts)) $setmarker = true;
	
	// single marker handling
	$single = false;
	if($atts && in_array('single',$atts)) $single = true;
	
	// prepare map bounds
	$bounds_ne = array();
	$bounds_se = array();
	if($a['bounds-ne'] && $a['bounds-sw']) {
		$bounds_ne = explode(',',$a['bounds-ne']);
		$bounds_sw = explode(',',$a['bounds-sw']);
	}
	
	$default_output = '""';
	$attachment = qfm_get_attachment_by_post_name( 'marker-00-default' );
	if($attachment) $default_output = '"'.wp_get_attachment_url($attachment->ID).'"';
	$output .= '
	<div class="qfm-map-wrapper">
		<div class="qfm-map'.($single ? ' single' : '').'" id="qfm-map"></div>	
		<script type="text/javascript">
			var defaultIconUrl = '.$default_output.';
			var locationTypes = [';
	$first = true;
	$terms = get_terms('location-type',array('hide_empty' => false,'orderby' => 'slug'));
	if(is_array($terms)) {
		foreach($terms as $term) {
			if(!$first) $output .= ',';
			$attachment = qfm_get_attachment_by_post_name( 'marker-'.$term->slug );
			if($attachment) $output .= '["'.$term->slug.'","'.$term->name.'","'.wp_get_attachment_url($attachment->ID).'","'.addslashes($term->description).'"]';
			$first = false;
		}
	}
	$output .= '];';
	$output .= '
			var mapAtts = {'.($single ? '
				"currentID" : '.get_the_ID().',' : '').($setmarker ? '
				"setMarker" : true,' : '').'
				"themeDir" : "'.get_template_directory_uri().'",
				"zoom" : '.$a['zoom'].',
				"singleZoom" : '.$a['singlezoom'].',
				"lat" : '.$a['lat'].',
				"lon" : '.$a['lon'].',
				'.($a['maxzoom'] ? '"maxZoom" : '.$a['maxzoom'].',' : '').'
				'.($a['minzoom'] ? '"minZoom" : '.$a['minzoom'].',' : '').'
				'.($a['showentry-zoom'] ? '"showentryZoom" : '.$a['showentry-zoom'].',' : '').($setmarker ? '
				"minZoomSetmarker" : '.$minzoomSetmarker.',' : '').'
				'.(sizeof($bounds_ne)==2 && sizeof($bounds_sw)==2 ? '"bounds" : ['.$bounds_sw[0].','.$bounds_sw[1].','.$bounds_ne[0].','.$bounds_ne[1].'],' : '').'
			}
			var geojsonData = {
				"projektGebietLayer" : "'.get_template_directory_uri().'/geojson/projektgebiet.geojson",
			};
			var mapTextData = {
				"viewDetails" : "'.esc_html__('View details','qfm').'",
				"editLocation" : "'.esc_html__('Edit location','qfm').'",
				"clickedOutside" : "'.esc_html__('Bitte wähle einen Punkt innerhalb des Projektgebeits.','qfm').'",
			}
			var showId = "";';
	if(isset($_GET['showentry']) && intval($_GET['showentry']) && get_post(intval($_GET['showentry']))) $output .= '
			var showId = '.intval($_GET['showentry']).';';
	$output .= '
		</script>
		<div class="qfm-map-buttons'.($single ? ' single' : '').'">';
	if(!$setmarker && !$single) {
		$adfc_icon = '';
		$community_icon = '';
		$attachment = qfm_get_attachment_by_post_name( 'marker-filter-adfc' );
		if($attachment) $adfc_icon = '<span class="icon adfc-icon"><img src="'.wp_get_attachment_url($attachment->ID).'" alt="ADFC icon" /></span>';
		$attachment = qfm_get_attachment_by_post_name( 'marker-filter-community' );
		echo $adfc_icon;
		if($attachment) $community_icon = '<span class="icon community-icon"><img src="'.wp_get_attachment_url($attachment->ID).'" alt="Community icon" /></span>';
		$output .= '
			<p class="filter-adfc-or-community">
				<a class="button small filter show-adfc" href="#">'.$adfc_icon.esc_html__('ADFC-Orte aus-/einblenden','qfm').'</a>
				<a class="button small filter show-community" href="#">'.$community_icon.esc_html__('Community-Orte aus-/einblenden','qfm').'</a>
			</p>';
	}
	$output .= '
			<p class="qfm-map-default-view"><a href="#">'.esc_html__('Back to default map view','qfm').'</a></p>';
	if(get_post_meta(get_option('page_on_front'),'new-location-form-page',true) && !$setmarker && !$single) {
		$output .= '
			<p class="qfm-map-new-location"><a class="button" href="'.get_permalink(get_post_meta(get_option('page_on_front'),'new-location-form-page',true)).'">'.esc_html__('Einen neuen Ort ergänzen','qfm').'</a></p>';
	}
	if(get_post_meta(get_option('page_on_front'),'map-page',true) && $single) {
		$output .= '<p class="map-page-button"><a href="'.get_permalink(get_post_meta(get_option('page_on_front'),'map-page',true)).'#qfm-map" class="button">'.esc_html__('zur Gesamtkarte wechseln','qfm').'</a></p>';
	}
	$output .= '
		</div>
	</div>';
	return $output;
}
add_shortcode('qfm-map','qfm_shortcode_qfm_map');
add_shortcode('qfm-karte','qfm_shortcode_qfm_map');

/**
 * Find attachment by post name
 * @SOURCE: https://wordpress.stackexchange.com/questions/117069/how-to-find-attachment-by-its-name
 */
function qfm_get_attachment_by_post_name( $post_name ) {
	$args           = array(
		'posts_per_page' => 1,
		'post_type'      => 'attachment',
		'name'           => trim( $post_name ),
	);

	$get_attachment = new WP_Query( $args );

	if ( ! $get_attachment || ! isset( $get_attachment->posts, $get_attachment->posts[0] ) ) {
		return false;
	}

	return $get_attachment->posts[0];
}

/**
 * ACF fields for start page
 */
if(function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group(array (
		'key' => 'general-settings',
		'title' => esc_html__('General settings','qfm'),
		'fields' => array (
			array (
				'key' => 'new-location-form-page',
				'label' => esc_html__('Seite mit Eintragsformular für neue Orte','qfm'),
				'name' => 'new-location-form-page',
				'type' => 'post_object',
				'post_type' => 'page',
				
			),
			array (
				'key' => 'map-page',
				'label' => esc_html__('Seite mit der Hauptkarte','qfm'),
				'name' => 'map-page',
				'type' => 'post_object',
				'post_type' => 'page',
				
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'page_type',
					'operator' => '==',
					'value' => 'front_page',
				),
			),
		),
	));
}

/**
 * ACF fields for location
 */
if(function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group(array (
		'key' => 'location-metadata',
		'title' => esc_html__('Metadaten des Ortes','qfm'),
		'fields' => array (
			array (
				'key' => 'location-longitude',
				'label' => esc_html__('Location longitude','qfm'),
				'name' => 'location-longitude',
				'type' => 'number',
				'required' => 1,
			),
			array (
				'key' => 'location-latitude',
				'label' => esc_html__('Location latitude','qfm'),
				'name' => 'location-latitude',
				'type' => 'number',
				'required' => 1,
			),
			array (
				'key' => 'location-use-alternative-icon',
				'instructions' => esc_html__('The alternative icon file must have the same name as the main icon file, but with "-2" in the end.','qfm'),
				'label' => esc_html__('Use alternative icon','qfm'),
				'name' => 'location-use-alternative-icon',
				'type' => 'true_false',
			),
			array (
				'key' => 'location-type',
				'label' => esc_html__('Typ deines Ortes','qfm'),
				'name' => 'location-type',
				'type' => 'taxonomy',
				'taxonomy' => 'location-type',
				'save_terms' => 1,
				'load_terms' => 1,
				'add_term' => 0,
				'return_format' => 'id',
				'field_type' => 'radio',
				'required' => 1,
			),
			array (
				'key' => 'location-nickname',
				'label' => sprintf(esc_html__('Dein Name oder Spitzname %s','qfm'),'<span style="font-weight:normal">'.esc_html__('(wird auf der Website angezeigt!)','qfm').'</span>'),
				'name' => 'location-nickname',
				'type' => 'text',
			),
			array (
				'key' => 'location-email-address',
				'label' => sprintf(esc_html__('Deine E-Mail-Adresse %s','qfm'),'<span style="font-weight:normal">'.esc_html__('(wird nicht veröffentlicht)','qfm').'</span>'),
				'name' => 'location-email-address',
				'type' => 'email',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'location',
				),
			),
		),
	));
}

/**
 * ACF fields for location (extra)
 */
if(function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group(array (
		'key' => 'location-metadata-extra',
		'title' => esc_html__('Metadaten des Ortes (extra)','qfm'),
		'fields' => array (
			array (
				'key' => 'kuula-id',
				'label' => esc_html__('ID der Kuula-360°-Ansicht','qfm'),
				'name' => 'kuula-id',
				'type' => 'text',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'location',
				),
			),
		),
	));
}

/**
 * Filters for ACF form page
 */
if(function_exists('acf_form_head')) {
	function qfm_acf_form_head_output(){
		if(is_page_template('page-acf-form.php')) {
			acf_form_head();
		}
	}
	add_action( 'template_redirect', 'qfm_acf_form_head_output', 100 );
	function qfm_form_output( $content){
		$updated = false;
		if(isset($_GET['updated']) && $_GET['updated'] == true) $updated = true;
		if(is_page_template('page-acf-form.php')) {
			if($updated) {
				$content .= '
				<div id="message"><p>'.esc_html__('Vielen Dank. Dein Vorschlag für einen neuen Ort wurde gespeichert. Er wird nun zeitnah durch die Redaktion geprüft und anschließend freigeschaltet.','qfm').'</p></div>';
			}
			else {
				$fields = array('location-type','location-longitude','location-latitude');
				if(!is_user_logged_in()) {
					$fields[] = 'location-nickname';
					$fields[] = 'location-email-address';
				}
				ob_start();
				acf_form(array(
					'post_id'       => 'new_post',
					'new_post'      => array(
						'post_type'     => 'location',
						'post_status'   => 'pending'
					),
					'submit_value'  => esc_html__('Vorschlag für neuen Ort absenden','qfm'),
					'post_title' => true,
					'post_content' => true,
					'fields' => $fields,
				));
				$form = ob_get_clean();
				$content .= $form;
				if(get_post_meta(get_option('page_on_front'),'map-page',true)) {
					$content .= '
					<p><a href="'.get_permalink(get_option('page_on_front'),'map-page',true).'">'.esc_html__('Zurück zur Gesamtkarte','qfm').'</a></p>';
				}
					
			}
		}
		if(is_single() && get_post_type() == 'location') {
			global $post;
			$fields = get_fields();
			foreach ($fields as $name => $value){
				$display_value = '';
				$data = get_field_object($name);
				if($data['type'] != 'email' && $data['parent'] == 'eintrag-extra-fields') {
					if(is_array($value)) {
						foreach($value as $single_value) {
							if(isset($data['choices'][$single_value])) $display_value .= ($display_value ? ', ' : '').$data['choices'][$single_value];
						}
						
					}
					elseif(isset($data['choices']) && isset($data['choices'][$value])) $display_value = $data['choices'][$value];
					else $display_value = $value;
					$content .= '
						<p class="field_label"><strong>'.$data['label'].'</strong></p>
						<p class="field_value">'.$display_value.'</p>';
				}
			}
		}
		return $content;
	}
	add_filter( 'the_content', 'qfm_form_output' );
}

// Change Post Content Type
if(has_filter('acf/get_valid_field')) {
	function change_post_content_type( $field ) {
		
		if($field['key'] == '_post_title') {
			$field['label'] = esc_html__('Bezeichnung des neuen Ortes','qfm');
		}
		if($field['type'] == 'wysiwyg' && $field['key'] == '_post_content') {
			$field['type'] = 'textarea';
			$field['rows'] = 8;
			$field['maxlength'] = 1500;
			$field['required'] = 1;
			$field['label'] = sprintf(esc_html__('Beschreibung deines Ortes %s','qfm'),'<span style="font-weight:normal">'.esc_html__('(maximal 1500 Zeichen)','qfm').'</span>');
		}
		if($field['key'] == 'location-latitude') {
			$field['label'] = esc_html__('Kartenposition deines Ortes','qfm');
			$field['instructions'] = sprintf(esc_html__('Klicke auf die Karte, um deinen Ort zu setzen. Um die Position zu verändern, klicke einfach an den neuen Ort. %s','qfm'),' <strong>'.esc_html__('Nach dem Freischalten deines Vorschlags werden auf der Gesamtkarte das zum gewählten Ortstyp passende Icon und deine Beschreibungen erscheinen.','qfm').'</strong>');
		}
		if(!is_user_logged_in()) {
			if($field['key'] == 'location-nickname') {
				$field['required'] = 1;
			}
			if($field['key'] == 'location-email-address') {
				$field['required'] = 1;
			}
		}
		if($field['key'] == 'eintrag-zustimmung-datenschutz') {
			$field['label'] = 'Ich habe die <a href="'.get_permalink( get_option( 'wp_page_for_privacy_policy' ) ).'" target="_blank">Datenschutzbestimmungen</a> gelesen und akzeptiere sie.';
		}
		if($field['key'] == 'eintrag-zustimmung-forumsregeln' && get_post_meta( get_option( 'page_on_front' ), 'kontaktboerse-forumsregeln-seite', true)) {
			$field['label'] = 'Ich habe die <a href="'.get_permalink(get_post_meta( get_option( 'page_on_front' ), 'kontaktboerse-forumsregeln-seite', true)).'" target="_blank">Forumsregeln</a> gelesen und akzeptiere sie.';
		}
			
		return $field;
	}
	add_filter( 'acf/get_valid_field', 'change_post_content_type');
	
	function change_field_rendering( $field ) {
		if(!is_admin()) {
			if($field['key'] == 'location-latitude') {
				echo do_shortcode('[qfm-map setmarker]');
			}
		}
	}

	add_action('acf/render_field', 'change_field_rendering');
}


/**
 * Shortcode [copyrightjahre]
 */
function qfm_shortcode_copyrightjahre($atts) {
	$a = shortcode_atts( array(
		'start' => 1970
	), $atts );
	$output = '';
	$current_year = date('Y');
	if($current_year > $a['start']) $output .= $a['start'].'-';
	$year_output = $current_year;
	if($a['start'] > $current_year) $year_output = $a['start'];
	$output .= $year_output;
	return $output;
}
add_shortcode('copyrightjahre','qfm_shortcode_copyrightjahre');

/**
 * Remove emoji scripts
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' ); 
remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/**
 * Allow excerpts for pages
 */
add_post_type_support( 'page', 'excerpt' );

/**
 * Open Graph meta tags
 */
 function qfm_og_meta_tags() {
    global $post;
    if ( !is_singular() && !is_front_page()) //if it is not a post or a page
        return;
        echo '<meta property="og:title" content="' . get_the_title() . '"/>
<meta property="og:type" content="article"/>
<meta property="og:description" content="' . get_the_excerpt() . '">
<meta property="og:url" content="' . get_permalink() . '"/>
<meta property="og:site_name" content="'. get_bloginfo('name'). '"/>';
    if(!has_post_thumbnail( $post->ID )) { //the post does not have featured image, get the website logo as default image
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$image_src = wp_get_attachment_image_src( $custom_logo_id , 'full' );
        echo '
<meta property="og:image" content="' . $image_src[0] . '"/>';
    }
    else{
        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
        echo '
<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
    }
    echo "
";
}
add_action( 'wp_head', 'qfm_og_meta_tags', 5 );

/**
 * Create domain output for automatically created e-mail addresses like noreply@...
 */
function qfm_maildomain() {
	$maildomain = str_replace('http://','',get_bloginfo('url'));
	$maildomain = str_replace('https://','',$maildomain);
	$maildomain = str_replace('www.','',$maildomain);
	$maildomain = explode('/',$maildomain);
	$maildomain = $maildomain[0];
	return $maildomain;
}

/**
 * Comment notification
 */
function show_message_function( $comment_ID, $comment_approved, $commentdata ) {
    $to = $commentdata['comment_author_email'];
	$headers = 'From: '.get_option('blogname').' '.esc_html__('Nachrichtenservice','qfm')." <noreply@".qfm_maildomain().">" . "\r\n";
	$headers .= 'Reply-To: '.get_option('admin_email'). "\r\n" .
	$subject = '['.get_option('blogname').'] '.esc_html__('Dein Kommentar','qfm');
	$message = 'Hallo '.$commentdata['comment_author'].','."\r\n\r\n";
	$message .= 'vielen Dank für deinen Kommentar zum Beitrag '.get_permalink($commentdata['comment_post_ID']).'. Du hast Folgendes geschrieben: '."\r\n\r\n";
	$message .= '----'."\r\n\r\n".$commentdata['comment_content']."\r\n\r\n".'----'."\r\n\r\n";
	$message .= 'Falls Du diesen Kommentar nicht selbst geschrieben hast, melde Dich bitte bei uns unter '.get_option('admin_email').'.'."\r\n\r\n";
	$message .= 'Wir werden deinen Kommentar schnellstmöglich prüfen und freischalten.'."\r\n\r\n";
	$message .= 'Viele Grüße vom'."\r\n";
	$message .= 'Team "Quartiere für Menschen"'."\r\n\r\n";
	$message .= '---'."\r\n";
	$message .= 'Du möchtest für den ADFC Hamburg spenden? Das geht hier: https://hamburg.adfc.de/spende.';
	$message_sent = wp_mail($to,$subject,$message,$headers);
    
}
add_action( 'comment_post', 'show_message_function', 10, 3 );

/**
 * Send mail to admin when a new location has been submitted
 */
function qfm_location_notification_email($post_id, $post, $update) {
  if($update) {
	  return;
  }
  
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  if (get_post_type($post_id) == 'location' && get_post_status($post_id) == 'pending') {
	  $to = get_option('admin_email');
	  $headers = 'From: '.get_bloginfo('name')." <noreply@".qfm_maildomain().">";
	  $subject = '['.get_bloginfo('name').']'.' Ein neuer Ort für die QfM-Karte wurde eingereicht';
	  $message = 'Bitte überprüfe den eingereichten Beitrag und veröffentliche ihn nach erfolgreicher Prüfung.'."\r\n\r\n";
	  $message .= 'Hier kannst du den Beitrag bearbeiten:'."\r\n";
	  $message .= get_admin_url().'post.php?post='.$post_id.'&action=edit'."\r\n\r\n";
	  if(!is_user_logged_in()) {
		$message .= 'Bei Rückfragen kannst du die Verfasserin/den Verfasser des Beitrags unter dieser E-Mail-Adresse kontaktieren (sofern sie/er die richtige E-Mail-Adresse eingetragen hat):'."\r\n";
		$message .= $_POST['acf']['location-email-address'];
	  }
	  $mail_sent = wp_mail($to,$subject,$message,$headers);
  }
  
  if (get_post_type($post_id) == 'location' && get_post_status($post_id) == 'pending' && !is_user_logged_in()) {
		$to = $_POST['acf']['location-email-address'];
		$headers = 'From: '.get_option('blogname').' '.esc_html__('Nachrichtenservice','qfm')." <noreply@".qfm_maildomain().">" . "\r\n";
		$headers .= 'Reply-To: '.get_option('admin_email'). "\r\n" .
		$subject = '['.get_bloginfo('name').']'.' Dein Beitrag';
		$message = 'Hallo '.$_POST['acf']['location-nickname'].','."\r\n\r\n";
		$message .= 'vielen Dank für deinen Beitrag auf '.get_bloginfo('url').'. Du hast Folgendes geschrieben: '."\r\n\r\n";
		$message .= '----'."\r\n\r\n".addslashes($post->post_title)."\r\n\r\n".addslashes($post->post_content)."\r\n\r\n".'----'."\r\n\r\n";
		$message .= 'Falls Du diesen Beitrag nicht selbst geschrieben hast, melde Dich bitte bei uns unter '.get_option('admin_email').'.'."\r\n\r\n";
		$message .= 'Wir werden deinen Beitrag schnellstmöglich prüfen und freischalten.'."\r\n\r\n";
		$message .= 'Viele Grüße vom'."\r\n";
		$message .= 'Team "Quartiere für Menschen"'."\r\n\r\n";
		$message .= '---'."\r\n";
		$message .= 'Du möchtest für den ADFC Hamburg spenden? Das geht hier: https://hamburg.adfc.de/spende.';
		$mail_sent = wp_mail($to,$subject,$message,$headers);
	}

}
add_action('save_post', 'qfm_location_notification_email', 1000, 3);