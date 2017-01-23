<?php
/**
 * JardiWinery Framework
 *
 * @package jardiwinery
 * @since jardiwinery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'JARDIWINERY_FW_DIR' ) )			define( 'JARDIWINERY_FW_DIR', 'fw' );

// Include theme variables storage
require_once trailingslashit( get_template_directory() ) . JARDIWINERY_FW_DIR . '/core/core.storage.php';

// Theme variables storage
jardiwinery_storage_set('options_prefix', 'jardiwinery');	//.'_'.str_replace(' ', '_', trim(strtolower(get_stylesheet()))));	// Prefix for the theme options in the postmeta and wp options
jardiwinery_storage_set('page_template', '');			// Storage for current page template name (used in the inheritance system)
jardiwinery_storage_set('widgets_args', array(			// Arguments to register widgets
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget_title">',
		'after_title'   => '</h4>',
	)
);
jardiwinery_storage_set('tag_open', 'script');
jardiwinery_storage_set('tag_close', '/script');
/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'jardiwinery_loader_theme_setup', 20 );
	function jardiwinery_loader_theme_setup() {

		// Before init theme
		do_action('jardiwinery_action_before_init_theme');

		// Load current values for main theme options
		jardiwinery_load_main_options();

		// Theme core init - only for admin side. In frontend it called from action 'wp'
		if ( is_admin() ) {
			jardiwinery_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */
// Manual load important libraries before load all rest files
// core.strings must be first - we use jardiwinery_str...() in the jardiwinery_get_file_dir()
require_once trailingslashit( get_template_directory() ) . JARDIWINERY_FW_DIR . '/core/core.strings.php';
// core.files must be first - we use jardiwinery_get_file_dir() to include all rest parts
require_once trailingslashit( get_template_directory() ) . JARDIWINERY_FW_DIR . '/core/core.files.php';

// Include debug utilities
require_once trailingslashit( get_template_directory() ) . JARDIWINERY_FW_DIR . '/core/core.debug.php';

// Include custom theme files
jardiwinery_autoload_folder( 'includes' );

// Include core files
jardiwinery_autoload_folder( 'core' );

// Include theme-specific plugins and post types
jardiwinery_autoload_folder( 'plugins' );

// Include theme templates
jardiwinery_autoload_folder( 'templates' );

// Include theme widgets
jardiwinery_autoload_folder( 'widgets' );
?>