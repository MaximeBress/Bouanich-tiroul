<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jardiwinery_template_form_1_theme_setup' ) ) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_template_form_1_theme_setup', 1 );
	function jardiwinery_template_form_1_theme_setup() {
		jardiwinery_add_template(array(
			'layout' => 'form_1',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 1', 'jardiwinery')
			));
	}
}

// Template output
if ( !function_exists( 'jardiwinery_template_form_1_output' ) ) {
	function jardiwinery_template_form_1_output($post_options, $post_data) {
		?>
		<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
			<?php jardiwinery_sc_form_show_fields($post_options['fields']); ?>
			<div class="sc_form_info">
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'jardiwinery'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name *', 'jardiwinery'); ?>"></div>
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'jardiwinery'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail *', 'jardiwinery'); ?>"></div>
			</div>
			<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'jardiwinery'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'jardiwinery'); ?>"></textarea></div>
			<div class="sc_form_item sc_form_button"><button class="sc_button_size_large"><?php esc_html_e('Send Message', 'jardiwinery'); ?></button></div>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>