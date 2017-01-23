<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jardiwinery_mailchimp_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_mailchimp_theme_setup', 1 );
	function jardiwinery_mailchimp_theme_setup() {
		if (jardiwinery_exists_mailchimp()) {
			if (is_admin()) {
				add_filter( 'jardiwinery_filter_importer_options',				'jardiwinery_mailchimp_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'jardiwinery_filter_importer_required_plugins',		'jardiwinery_mailchimp_importer_required_plugins', 10, 2 );
			add_filter( 'jardiwinery_filter_required_plugins',					'jardiwinery_mailchimp_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'jardiwinery_exists_mailchimp' ) ) {
	function jardiwinery_exists_mailchimp() {
		return function_exists('mc4wp_load_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_mailchimp_required_plugins' ) ) {
	//add_filter('jardiwinery_filter_required_plugins',	'jardiwinery_mailchimp_required_plugins');
	function jardiwinery_mailchimp_required_plugins($list=array()) {
		if (in_array('mailchimp', jardiwinery_storage_get('required_plugins')))
			$list[] = array(
				'name' 		=> 'MailChimp for WP',
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Mail Chimp in the required plugins
if ( !function_exists( 'jardiwinery_mailchimp_importer_required_plugins' ) ) {
	//add_filter( 'jardiwinery_filter_importer_required_plugins',	'jardiwinery_mailchimp_importer_required_plugins', 10, 2 );
	function jardiwinery_mailchimp_importer_required_plugins($not_installed='', $list='') {
		if (jardiwinery_strpos($list, 'mailchimp')!==false && !jardiwinery_exists_mailchimp() )
			$not_installed .= '<br>Mail Chimp';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'jardiwinery_mailchimp_importer_set_options' ) ) {
	//add_filter( 'jardiwinery_filter_importer_options',	'jardiwinery_mailchimp_importer_set_options' );
	function jardiwinery_mailchimp_importer_set_options($options=array()) {
		if ( in_array('mailchimp', jardiwinery_storage_get('required_plugins')) && jardiwinery_exists_mailchimp() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'mc4wp_lite_checkbox';
			$options['additional_options'][] = 'mc4wp_lite_form';
		}
		return $options;
	}
}
?>