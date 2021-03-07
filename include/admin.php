<?php

/**
 *	Handle WP-Admin Hooks like script enqueing, options page and so on.
 */
 
// add options
add_option('qfm-theme-settings');


// register settings
function qfm_register_settings() {
	register_setting('qfm-theme-settings-group','qfm-theme-settings');
}


// add setting sub menu link
function qfm_create_menu() {

	// create new top-level menu
	$page_hook = add_submenu_page('themes.php', __('QFM settings','qfm'),__('QFM settings','qfm'), 'manage_options', 'qfm-theme-settings', 'qfm_settings_page') ;

	//call register settings function
	add_action( 'admin_init', 'qfm_register_settings' );
}
add_action('admin_menu', 'qfm_create_menu');


// options page
function qfm_settings_page() {
	global $wpdb;
	$plugin_options = get_option('qfm-settings');

	?>
	<div class="wrap qfm-dashboard">
		<h2><?php esc_html_e('Quartiere fuer Menschen theme settings','qfm'); ?></h2>

		<p><em><?php esc_html_e('Settings will come in a future version.','qfm'); ?></em></p>	
	</div>
	<?php
}

// additions to single locations edit page
function qfm_add_location_post_edit_content() {
    echo do_shortcode('[qfm-map setmarker]');
}

function qfm_add_map_metabox() {
	add_meta_box(
		'qfm_post_location',
		esc_html__('Map position','qfm'),
		'qfm_add_location_post_edit_content',
		'location'
	);
}
add_action( 'add_meta_boxes', 'qfm_add_map_metabox' );
