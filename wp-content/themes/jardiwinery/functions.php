<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Prepare demo data
$jardiwinery_demo_data_url = esc_url('http://jardiwinery.ancorathemes.com/demo/');


// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'jardiwinery_theme_setup' ) ) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_theme_setup', 1 );
	function jardiwinery_theme_setup() {

		// Register theme menus
		add_filter( 'jardiwinery_filter_add_theme_menus',		'jardiwinery_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'jardiwinery_filter_add_theme_sidebars',	'jardiwinery_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'jardiwinery_filter_importer_options',		'jardiwinery_set_importer_options' );

		// Add theme required plugins
		add_filter( 'jardiwinery_filter_required_plugins',		'jardiwinery_add_required_plugins' );

		// Init theme after WP is created
		add_action( 'wp',									'jardiwinery_core_init_theme' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 							'jardiwinery_body_classes' );

		// Add data to the head and to the beginning of the body
		add_action('wp_head',								'jardiwinery_head_add_page_meta', 1);
		add_action('wp_head',								'jardiwinery_head_add_page_preloader_styles', 9);
		add_action('before',								'jardiwinery_body_add_gtm');
		add_action('before',								'jardiwinery_body_add_toc');
		add_action('before',								'jardiwinery_body_add_page_preloader');

		// Add data to the head and to the beginning of the body
		add_action('wp_footer',								'jardiwinery_footer_add_views_counter');
		add_action('wp_footer',								'jardiwinery_footer_add_login_register');
		add_action('wp_footer',								'jardiwinery_footer_add_theme_customizer');
		add_action('wp_footer',								'jardiwinery_footer_add_scroll_to_top');
		add_action('wp_footer',								'jardiwinery_footer_add_custom_html');
		add_action('wp_footer',								'jardiwinery_footer_add_gtm2');

		// Set list of the theme required plugins
		jardiwinery_storage_set('required_plugins', array(
			'essgrids',
			'revslider',
            'mailchimp',
			'trx_utils',
			'visual_composer',
			'woocommerce',
			)
		);
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'jardiwinery_add_theme_menus' ) ) {
	function jardiwinery_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'jardiwinery_add_theme_sidebars' ) ) {
	function jardiwinery_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'jardiwinery' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'jardiwinery' )
			);
			if (function_exists('jardiwinery_exists_woocommerce') && jardiwinery_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'jardiwinery' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'jardiwinery_add_required_plugins' ) ) {
	function jardiwinery_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> esc_html__( 'JardiWinery Utilities' , 'jardiwinery'),
			'version'	=> '2.8',					// Minimal required version
			'slug' 		=> 'trx_utils',
			'source'	=> jardiwinery_get_file_dir('plugins/install/trx_utils.zip'),
			'required' 	=> true
		);
		return $plugins;
	}
}


// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( !function_exists( 'jardiwinery_set_importer_options' ) ) {
	function jardiwinery_set_importer_options($options=array()) {
		if (is_array($options)) {
			$options['debug'] = jardiwinery_get_theme_option('debug_mode')=='yes';
			$options['menus'] = array(
				'menu-main'	  => esc_html__('Main menu', 'jardiwinery'),
				'menu-user'	  => esc_html__('User menu', 'jardiwinery'),
				'menu-footer' => esc_html__('Footer menu', 'jardiwinery'),
				'menu-outer'  => esc_html__('Main menu', 'jardiwinery')
			);

			// Main demo
			global $jardiwinery_demo_data_url;
			$options['files']['default'] = array(
				'title'				=> esc_html__('Basekit demo', 'jardiwinery'),
				'file_with_posts'	=> $jardiwinery_demo_data_url . 'default/posts.txt',
				'file_with_users'	=> $jardiwinery_demo_data_url . 'default/users.txt',
				'file_with_mods'	=> $jardiwinery_demo_data_url . 'default/theme_mods.txt',
				'file_with_options'	=> $jardiwinery_demo_data_url . 'default/theme_options.txt',
				'file_with_templates'=>$jardiwinery_demo_data_url . 'default/templates_options.txt',
				'file_with_widgets'	=> $jardiwinery_demo_data_url . 'default/widgets.txt',
				'file_with_revsliders' => array(
					$jardiwinery_demo_data_url . 'default/revsliders/jardiwinery-slider1.zip',
					$jardiwinery_demo_data_url . 'default/revsliders/jardiwinery-slider2.zip'
				),
				'file_with_attachments' => array(),
				'attachments_by_parts'	=> true,
				'domain_dev'	=> esc_url('jardi.dv.ancorathemes.com'),
				'domain_demo'	=> esc_url('jardiwinery.ancorathemes.com')
			);

            for ($i=1; $i<=15; $i++) {
                $options['files']['default']['file_with_attachments'][] = $jardiwinery_demo_data_url . 'default/uploads/uploads.' . sprintf('%03u', $i);
            }
		}
		return $options;
	}
}


// Add data to the head and to the beginning of the body
//------------------------------------------------------------------------

// Add theme specified classes to the body tag
if ( !function_exists('jardiwinery_body_classes') ) {
	function jardiwinery_body_classes( $classes ) {

		$classes[] = 'jardiwinery_body';
		$classes[] = 'body_style_' . trim(jardiwinery_get_custom_option('body_style'));
		$classes[] = 'body_' . (jardiwinery_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'article_style_' . trim(jardiwinery_get_custom_option('article_style'));
		
		$blog_style = jardiwinery_get_custom_option(is_singular() && !jardiwinery_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(jardiwinery_get_template_name($blog_style));
		
		$body_scheme = jardiwinery_get_custom_option('body_scheme');
		if (empty($body_scheme)  || jardiwinery_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = jardiwinery_get_custom_option('top_panel_position');
		if (!jardiwinery_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = jardiwinery_get_sidebar_class();

		if (jardiwinery_get_custom_option('show_video_bg')=='yes' && (jardiwinery_get_custom_option('video_bg_youtube_code')!='' || jardiwinery_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (jardiwinery_get_theme_option('page_preloader')!='')
			$classes[] = 'preloader';

		return $classes;
	}
}


// Add page meta to the head
if (!function_exists('jardiwinery_head_add_page_meta')) {
	function jardiwinery_head_add_page_meta() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1<?php if (jardiwinery_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
		<meta name="format-detection" content="telephone=no">
	
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
	}
}

// Add page preloader styles to the head
if (!function_exists('jardiwinery_head_add_page_preloader_styles')) {
	function jardiwinery_head_add_page_preloader_styles() {
		if (($preloader=jardiwinery_get_theme_option('page_preloader'))!='') {
			$clr = jardiwinery_get_scheme_color('bg_color');
			?>
			<style type="text/css">
			<!--
				#page_preloader { background-color: <?php echo esc_attr($clr); ?>; background-image:url(<?php echo esc_url($preloader); ?>); background-position:center; background-repeat:no-repeat; position:fixed; z-index:1000000; left:0; top:0; right:0; bottom:0; opacity: 0.8; }
			-->
			</style>
			<?php
		}
	}
}

// Add gtm code to the beginning of the body 
if (!function_exists('jardiwinery_body_add_gtm')) {
	function jardiwinery_body_add_gtm() {
		echo force_balance_tags(jardiwinery_get_custom_option('gtm_code'));
	}
}

// Add TOC anchors to the beginning of the body 
if (!function_exists('jardiwinery_body_add_toc')) {
	function jardiwinery_body_add_toc() {
		// Add TOC items 'Home' and "To top"
		if (jardiwinery_get_custom_option('menu_toc_home')=='yes')
			echo trim(jardiwinery_sc_anchor(array(
				'id' => "toc_home",
				'title' => esc_html__('Home', 'jardiwinery'),
				'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'jardiwinery'),
				'icon' => "icon-home",
				'separator' => "yes",
				'url' => esc_url(home_url('/'))
				)
			)); 
		if (jardiwinery_get_custom_option('menu_toc_top')=='yes')
			echo trim(jardiwinery_sc_anchor(array(
				'id' => "toc_top",
				'title' => esc_html__('To Top', 'jardiwinery'),
				'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'jardiwinery'),
				'icon' => "icon-double-up",
				'separator' => "yes")
				)); 
	}
}

// Add page preloader to the beginning of the body
if (!function_exists('jardiwinery_body_add_page_preloader')) {
	//add_action('before', 'jardiwinery_body_add_page_preloader');
	function jardiwinery_body_add_page_preloader() {
		if (($preloader=jardiwinery_get_theme_option('page_preloader'))!='') {
			?><div id="page_preloader"></div><?php
		}
	}
}


// Add data to the footer
//------------------------------------------------------------------------

// Add post/page views counter
if (!function_exists('jardiwinery_footer_add_views_counter')) {
	//add_action('wp_footer', 'jardiwinery_footer_add_views_counter');
	function jardiwinery_footer_add_views_counter() {
		// Post/Page views counter
		get_template_part(jardiwinery_get_file_slug('templates/_parts/views-counter.php'));
	}
}

// Add Login/Register popups
if (!function_exists('jardiwinery_footer_add_login_register')) {
	//add_action('wp_footer', 'jardiwinery_footer_add_login_register');
	function jardiwinery_footer_add_login_register() {
		if (jardiwinery_get_theme_option('show_login')=='yes') {
			jardiwinery_enqueue_popup();
			// Anyone can register ?
			if ( (int) get_option('users_can_register') > 0) {
				get_template_part(jardiwinery_get_file_slug('templates/_parts/popup-register.php'));
			}
			get_template_part(jardiwinery_get_file_slug('templates/_parts/popup-login.php'));
		}
	}
}

// Add theme customizer
if (!function_exists('jardiwinery_footer_add_theme_customizer')) {
	//add_action('wp_footer', 'jardiwinery_footer_add_theme_customizer');
	function jardiwinery_footer_add_theme_customizer() {
		// Front customizer
		if (jardiwinery_get_custom_option('show_theme_customizer')=='yes') {
            require_once trailingslashit( get_template_directory() ) . 'fw/core/core.customizer/front.customizer.php';
		}
	}
}

// Add scroll to top button
if (!function_exists('jardiwinery_footer_add_scroll_to_top')) {
	//add_action('wp_footer', 'jardiwinery_footer_add_scroll_to_top');
	function jardiwinery_footer_add_scroll_to_top() {
		?><a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'jardiwinery'); ?>"></a><?php
	}
}

// Add custom html
if (!function_exists('jardiwinery_footer_add_custom_html')) {
	//add_action('wp_footer', 'jardiwinery_footer_add_custom_html');
	function jardiwinery_footer_add_custom_html() {
		?><div class="custom_html_section"><?php
			echo force_balance_tags(jardiwinery_get_custom_option('custom_code'));
		?></div><?php
	}
}

// Add gtm code
if (!function_exists('jardiwinery_footer_add_gtm2')) {
	//add_action('wp_footer', 'jardiwinery_footer_add_gtm2');
	function jardiwinery_footer_add_gtm2() {
		echo force_balance_tags(jardiwinery_get_custom_option('gtm_code2'));
	}
}

function wpb_move_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}

add_filter( 'comment_form_fields', 'wpb_move_comment_field_to_bottom' );


// Include framework core files
//-------------------------------------------------------------------
require_once trailingslashit( get_template_directory() ) . 'fw/loader.php';
?>