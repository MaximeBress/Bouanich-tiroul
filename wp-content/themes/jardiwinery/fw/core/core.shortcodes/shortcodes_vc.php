<?php
if (is_admin() 
		|| (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
		|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
	) {
	require_once trailingslashit( get_template_directory() ) . JARDIWINERY_FW_DIR . '/core/core.shortcodes/shortcodes_vc_classes.php';
}

// Width and height params
if ( !function_exists( 'jardiwinery_vc_width' ) ) {
	function jardiwinery_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => esc_html__("Width", 'jardiwinery'),
			"description" => wp_kses_data( __("Width of the element", 'jardiwinery') ),
			"group" => esc_html__('Size &amp; Margins', 'jardiwinery'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'jardiwinery_vc_height' ) ) {
	function jardiwinery_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => esc_html__("Height", 'jardiwinery'),
			"description" => wp_kses_data( __("Height of the element", 'jardiwinery') ),
			"group" => esc_html__('Size &amp; Margins', 'jardiwinery'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'jardiwinery_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'jardiwinery_shortcodes_vc_scripts_admin' );
	function jardiwinery_shortcodes_vc_scripts_admin() {
		// Include CSS 
		jardiwinery_enqueue_style ( 'shortcodes_vc_admin-style', jardiwinery_get_file_url('shortcodes/theme.shortcodes_vc_admin.css'), array(), null );
		// Include JS
		jardiwinery_enqueue_script( 'shortcodes_vc_admin-script', jardiwinery_get_file_url('core/core.shortcodes/shortcodes_vc_admin.js'), array('jquery'), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'jardiwinery_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'jardiwinery_shortcodes_vc_scripts_front' );
	function jardiwinery_shortcodes_vc_scripts_front() {
		if (jardiwinery_vc_is_frontend()) {
			// Include CSS 
			jardiwinery_enqueue_style ( 'shortcodes_vc_front-style', jardiwinery_get_file_url('shortcodes/theme.shortcodes_vc_front.css'), array(), null );
			// Include JS
			jardiwinery_enqueue_script( 'shortcodes_vc_front-script', jardiwinery_get_file_url('core/core.shortcodes/shortcodes_vc_front.js'), array('jquery'), null, true );
			jardiwinery_enqueue_script( 'shortcodes_vc_theme-script', jardiwinery_get_file_url('shortcodes/theme.shortcodes_vc_front.js'), array('jquery'), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'jardiwinery_shortcodes_vc_add_init_script' ) ) {
	//add_filter('jardiwinery_shortcode_output', 'jardiwinery_shortcodes_vc_add_init_script', 10, 4);
	function jardiwinery_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (jardiwinery_strpos($output, 'jardiwinery_vc_init_shortcodes')===false) {
				$id = "jardiwinery_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<'.'script id="'.esc_attr($id).'">
						try {
							jardiwinery_init_post_formats();
							jardiwinery_init_shortcodes(jQuery("body").eq(0));
							jardiwinery_scroll_actions();
						} catch (e) { };
					</'.'script'.'>
				';
			}
		}
		return $output;
	}
}

// Return vc_param value
if ( !function_exists( 'jardiwinery_get_vc_param' ) ) {
	function jardiwinery_get_vc_param($prm) {
		return jardiwinery_storage_get_array('vc_params', $prm);
	}
}

// Set vc_param value
if ( !function_exists( 'jardiwinery_set_vc_param' ) ) {
	function jardiwinery_set_vc_param($prm, $val) {
		jardiwinery_storage_set_array('vc_params', $prm, $val);
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jardiwinery_shortcodes_vc_theme_setup' ) ) {
	//if ( jardiwinery_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'jardiwinery_action_after_init_theme', 'jardiwinery_shortcodes_vc_theme_setup' );
	function jardiwinery_shortcodes_vc_theme_setup() {


		// Set dir with theme specific VC shortcodes
		if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
			vc_set_shortcodes_templates_dir( jardiwinery_get_folder_dir('shortcodes/vc' ) );
		}
		
		// Add/Remove params in the standard VC shortcodes
		vc_add_param("vc_row", array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'jardiwinery'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'jardiwinery') ),
					"group" => esc_html__('Color scheme', 'jardiwinery'),
					"class" => "",
					"value" => array_flip(jardiwinery_get_list_color_schemes(true)),
					"type" => "dropdown"
		));
		vc_add_param("vc_row", array(
					"param_name" => "inverse",
					"heading" => esc_html__("Inverse colors", 'jardiwinery'),
					"description" => wp_kses_data( __("Inverse all colors of this block", 'jardiwinery') ),
					"group" => esc_html__('Color scheme', 'jardiwinery'),
					"class" => "",
					"std" => "no",
					"value" => array(esc_html__('Inverse colors', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
		));

		if (jardiwinery_shortcodes_is_used() && class_exists('JARDIWINERY_VC_ShortCodeSingle')) {

			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'jardiwinery_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'jardiwinery_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('jardiwinery_shortcode_output', 'jardiwinery_shortcodes_vc_add_init_script', 10, 4);

			jardiwinery_storage_set('vc_params', array(
				
				// Common arrays and strings
				'category' => esc_html__("JardiWinery shortcodes", 'jardiwinery'),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => esc_html__("Element ID", 'jardiwinery'),
					"description" => wp_kses_data( __("ID for the element", 'jardiwinery') ),
					"group" => esc_html__('ID &amp; Class', 'jardiwinery'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => esc_html__("Element CSS class", 'jardiwinery'),
					"description" => wp_kses_data( __("CSS class for the element", 'jardiwinery') ),
					"group" => esc_html__('ID &amp; Class', 'jardiwinery'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => esc_html__("Animation", 'jardiwinery'),
					"description" => wp_kses_data( __("Select animation while object enter in the visible area of page", 'jardiwinery') ),
					"group" => esc_html__('ID &amp; Class', 'jardiwinery'),
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('animations')),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => esc_html__("CSS styles", 'jardiwinery'),
					"description" => wp_kses_data( __("Any additional CSS rules (if need)", 'jardiwinery') ),
					"group" => esc_html__('ID &amp; Class', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => esc_html__("Top margin", 'jardiwinery'),
					"description" => wp_kses_data( __("Margin above this shortcode", 'jardiwinery') ),
					"group" => esc_html__('Size &amp; Margins', 'jardiwinery'),
					"std" => "inherit",
					"value" => array_flip(jardiwinery_get_sc_param('margins')),
					"type" => "dropdown"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => esc_html__("Bottom margin", 'jardiwinery'),
					"description" => wp_kses_data( __("Margin below this shortcode", 'jardiwinery') ),
					"group" => esc_html__('Size &amp; Margins', 'jardiwinery'),
					"std" => "inherit",
					"value" => array_flip(jardiwinery_get_sc_param('margins')),
					"type" => "dropdown"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => esc_html__("Left margin", 'jardiwinery'),
					"description" => wp_kses_data( __("Margin on the left side of this shortcode", 'jardiwinery') ),
					"group" => esc_html__('Size &amp; Margins', 'jardiwinery'),
					"std" => "inherit",
					"value" => array_flip(jardiwinery_get_sc_param('margins')),
					"type" => "dropdown"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => esc_html__("Right margin", 'jardiwinery'),
					"description" => wp_kses_data( __("Margin on the right side of this shortcode", 'jardiwinery') ),
					"group" => esc_html__('Size &amp; Margins', 'jardiwinery'),
					"std" => "inherit",
					"value" => array_flip(jardiwinery_get_sc_param('margins')),
					"type" => "dropdown"
				)
			) );
			
			// Add theme-specific shortcodes
			do_action('jardiwinery_action_shortcodes_list_vc');

		}
	}
}
?>