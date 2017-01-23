<?php

/**
 * Add function to widgets_init that will load our widget.
 */
add_action( 'widgets_init', 'jardiwinery_widget_qrcode_load' );

/**
 * Register our widget.
 */
function jardiwinery_widget_qrcode_load() {
	register_widget('jardiwinery_widget_qrcode');
}

/**
 * QRCode Widget class.
 */
class jardiwinery_widget_qrcode extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array('classname' => 'widget_qrcode', 'description' => esc_html__('Generate QRCode with your personal data or with any text (links)', 'jardiwinery'));

		/* Widget control settings. */
		$control_ops = array('width' => 200, 'height' => 250, 'id_base' => 'jardiwinery_widget_qrcode');

		/* Create the widget. */
		parent::__construct( 'jardiwinery_widget_qrcode', esc_html__('JardiWinery - QRCode generator', 'jardiwinery'), $widget_ops, $control_ops );

		// Load required styles and scripts for Options Page
		add_action("admin_enqueue_scripts",	array($this, 'load_scripts'));
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget($args, $instance) {

		extract($args);

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title']);
		$ulname = $instance['ulname'];
		$ufname = $instance['ufname'];
		$ucompany = $instance['ucompany'];
		$uphone = $instance['uphone'];
		//$ufax = $instance['ufax'];
		$uaddr = $instance['uaddr'];
		$ucity = $instance['ucity'];
		$upostcode = $instance['upostcode'];
		$ucountry = $instance['ucountry'];
		$uemail = $instance['uemail'];
		$usite = $instance['usite'];
		//$unote = $instance['unote'];
		//$ucats = $instance['ucats'];
		$urev = $instance['urev'];
		$uid = $instance['uid'];
		$show_personal = $instance['show_personal'];
		$show_what = $instance['show_what'];
		$text = $instance['text'];
		$width = $instance['width'];
		$color = $instance['color'];
		$bg = $instance['bg'];
		$image = $instance['image'];
		
		$output = '';
		
		if ($title) 	$output .= ($before_title) . ($title) . ($after_title);
		
		$output .= '
				<div class="widget_inner' . ($show_personal ? ' with_personal_data' : '') . '">
					<div class="qrcode"><img src="' . ($image) . '" alt="" /></div>
					';
		if ($show_personal) 
			$output .= '
					<div class="personal_data">
					' . ($show_what==1 
						? '<p class="user_name odd first"><span class="theme_text">' . esc_html__('Name:', 'jardiwinery') . '</span> <span class="theme_info">' . ($ufname) . ' ' . ($ulname) . '<span></p>'
							. ($ucompany ? '<p class="user_company even"><span class="theme_text">' . esc_html__('Company:', 'jardiwinery') . '</span> <span class="theme_info">' . ($ucompany) . '<span></p>' : '')
							. ($uphone ? '<p class="user_phone odd"><span class="theme_text">' . esc_html__('Phone:', 'jardiwinery') . '</span> <span class="theme_info">' . ($uphone) . '<span></p>' : '')
							. ($uemail ? '<p class="user_email even"><span class="theme_text">' . esc_html__('E-mail:', 'jardiwinery') . '</span> <a href="' . esc_url('mailto:'.($uemail)) . '">' . ($uemail) . '</a></p>' : '')
							. ($usite ? '<p class="user_site odd"><span class="theme_text">' . esc_html__('Site:', 'jardiwinery') . '</span> <a href="' . esc_url($usite) . '" target="_blank">' . ($usite) . '</a></p>' : '')
						: $text)
						. '
					</div>
					';
		$output .= '
				</div>';

		/* Before widget (defined by themes). */
		echo trim($before_widget);
	
		echo trim($output);
			
		/* After widget (defined by themes). */
		echo trim($after_widget);
	}

	/**
	 * Update the widget settings.
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		/* Strip tags for title and comments count to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['ulname'] = strip_tags($new_instance['ulname']);
		$instance['ufname'] = strip_tags($new_instance['ufname']);
		$instance['utitle'] = strip_tags($new_instance['utitle']);
		$instance['ucompany'] = strip_tags($new_instance['ucompany']);
		$instance['uphone'] = strip_tags($new_instance['uphone']);
		$instance['uaddr'] = strip_tags($new_instance['uaddr']);
		$instance['ucity'] = strip_tags($new_instance['ucity']);
		$instance['upostcode'] = strip_tags($new_instance['upostcode']);
		$instance['ucountry'] = strip_tags($new_instance['ucountry']);
		$instance['uemail'] = strip_tags($new_instance['uemail']);
		$instance['usite'] = strip_tags($new_instance['usite']);
		$instance['uid'] = strip_tags($new_instance['uid']);
		$instance['urev'] = date('Y-m-d');
		$instance['show_personal'] = isset($new_instance['show_personal']) ? 1 : 0;
		$instance['show_what'] = $new_instance['show_what'];
		$instance['auto_draw'] = isset($new_instance['auto_draw']) ? 1 : 0;
		$instance['text'] = strip_tags($new_instance['text']);
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['color'] = strip_tags($new_instance['color']);
		$instance['bg'] = strip_tags($new_instance['bg']);
		$instance['image'] = strip_tags($new_instance['image']);
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form($instance) {
		
		/* Widget admin side css */
		jardiwinery_enqueue_style( 'widget-qrcode-style',  jardiwinery_get_file_url('widgets/qrcode/qrcode-admin.css'), array(), null );
		jardiwinery_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
		jardiwinery_enqueue_script('widget-qrcode-script', jardiwinery_get_file_url('widgets/qrcode/jquery.qrcode-0.6.0.min.js'), array(), null, false);

		/* Set up some default widget settings. */
		$address = explode(',', jardiwinery_get_theme_option('user_address'));
		$defaults = array(
			'title' => '', 
			'description' => esc_html__('QR Code Generator (for your vcard)', 'jardiwinery'),
			'ulname' => '', 
			'ufname' => '', 
			'ucompany' => '', 
			'uaddr' => '', 
			'ucity' => '', 
			'upostcode' => '', 
			'ucountry' => '', 
			'uemail' => '', 
			'usite' => '', 
			'uphone' => '', 
			'uid' => md5(microtime()), 
			'urev' => date('Y-m-d'),
			'image' => '', 
			'show_personal' => 0,
			'show_what' => 1,
			'auto_draw' => 0,
			'width' => 160,
			'text' => '',
			'color' => '#000000',
			'bg' => ''
		);
		$instance = wp_parse_args((array) $instance, $defaults); ?>

		<div class="widget_qrcode">
        	<div class="qrcode_tabs">
                <ul class="tabs">
                    <li class="first"><a href="#tab_settings"><?php esc_html_e('Settings', 'jardiwinery'); ?></a></li>
                    <li><a href="#tab_fields" onmousedown="jardiwinery_qrcode_init()"><?php esc_html_e('Personal Data', 'jardiwinery'); ?></a></li>
                    <li><a href="#tab_text" onmousedown="jardiwinery_qrcode_init()"><?php esc_html_e('Any Text', 'jardiwinery'); ?></a></li>
                </ul>
                <div id="tab_settings" class="tab_content tab_settings">
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'jardiwinery'); ?></label>
                        <input class="fld_title" onfocus="jardiwinery_qrcode_init()" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widgets_param_fullwidth" />
                    </p>
                    <p>
                        <label><?php esc_html_e('Show as QR Code:', 'jardiwinery'); ?></label><br />
                        <input class="fld_show_what" onfocus="jardiwinery_qrcode_init()" id="<?php echo esc_attr($this->get_field_id('show_what')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_what')); ?>" value="1" type="radio" <?php echo (1==$instance['show_what'] ? 'checked="checked"' : ''); ?> />
                        <label for="<?php echo esc_attr($this->get_field_id('show_what')); ?>_1"> <?php esc_html_e('Personal VCard', 'jardiwinery'); ?></label>
                        <input class="fld_show_what" onfocus="jardiwinery_qrcode_init()" id="<?php echo esc_attr($this->get_field_id('show_what')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_what')); ?>" value="0" type="radio" <?php echo (0==$instance['show_what'] ? 'checked="checked"' : ''); ?> />
                        <label for="<?php echo esc_attr($this->get_field_id('show_what')); ?>_0"> <?php esc_html_e('Any text', 'jardiwinery'); ?></label>
                    </p>
                    <p>
                        <input class="fld_show_personal" onfocus="jardiwinery_qrcode_init()" id="<?php echo esc_attr($this->get_field_id('show_personal')); ?>" name="<?php echo esc_attr($this->get_field_name('show_personal')); ?>" value="1" type="checkbox" <?php echo (1==$instance['show_personal'] ? 'checked="checked"' : ''); ?> />
                        <label for="<?php echo esc_attr($this->get_field_id('show_personal')); ?>"><?php esc_html_e('Show data under QR Code:', 'jardiwinery'); ?></label>
                    </p>
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('width')); ?>"><?php esc_html_e('Width:', 'jardiwinery'); ?></label>
                        <input onmousedown="jardiwinery_qrcode_init()" onfocus="jardiwinery_qrcode_init()" id="<?php echo esc_attr($this->get_field_id('width')); ?>" name="<?php echo esc_attr($this->get_field_name('width')); ?>" value="<?php echo esc_attr($instance['width']); ?>" class="widgets_param_fullwidth fld_width" />
                    </p>
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('color')); ?>"><?php esc_html_e('Color:', 'jardiwinery'); ?></label>
                        <input onmousedown="jardiwinery_qrcode_init()" onfocus="jardiwinery_qrcode_init()" id="<?php echo esc_attr($this->get_field_id('color')); ?>" name="<?php echo esc_attr($this->get_field_name('color')); ?>" value="<?php echo esc_attr($instance['color']); ?>" class="widgets_param_fullwidth iColorPicker fld_color" style="background-color:<?php echo esc_attr($instance['color']); ?>" />
                    </p>
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('bg')); ?>"><?php esc_html_e('Bg color:', 'jardiwinery'); ?></label>
                        <input onmousedown="jardiwinery_qrcode_init()" onfocus="jardiwinery_qrcode_init()" id="<?php echo esc_attr($this->get_field_id('bg')); ?>" name="<?php echo esc_attr($this->get_field_name('bg')); ?>" value="<?php echo esc_attr($instance['bg']); ?>" style="background-color:<?php echo esc_attr($instance['bg']); ?>" class="widgets_param_fullwidth iColorPicker fld_bg" />
                    </p>
                </div>
                <div id="tab_fields" class="tab_content tab_personal">
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('ulname')); ?>"><?php esc_html_e('Last name:', 'jardiwinery'); ?></label>
                        <input class="fld_ulname" id="<?php echo esc_attr($this->get_field_id('ulname')); ?>" name="<?php echo esc_attr($this->get_field_name('ulname')); ?>" value="<?php echo esc_attr($instance['ulname']); ?>" class="widgets_param_fullwidth" />
                    </p>
                    
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('ufname')); ?>"><?php esc_html_e('First name:', 'jardiwinery'); ?></label>
                        <input class="fld_ufname" id="<?php echo esc_attr($this->get_field_id('ufname')); ?>" name="<?php echo esc_attr($this->get_field_name('ufname')); ?>" value="<?php echo esc_attr($instance['ufname']); ?>" class="widgets_param_fullwidth" />
                    </p>
                    
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('ucompany')); ?>"><?php esc_html_e('Company:', 'jardiwinery'); ?></label>
                        <input class="fld_ucompany" id="<?php echo esc_attr($this->get_field_id('ucompany')); ?>" name="<?php echo esc_attr($this->get_field_name('ucompany')); ?>" value="<?php echo esc_attr($instance['ucompany']); ?>" class="widgets_param_fullwidth" />
                    </p>
                    
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('uphone')); ?>"><?php esc_html_e('Phone:', 'jardiwinery'); ?></label>
                        <input class="fld_uphone" id="<?php echo esc_attr($this->get_field_id('uphone')); ?>" name="<?php echo esc_attr($this->get_field_name('uphone')); ?>" value="<?php echo esc_attr($instance['uphone']); ?>" class="widgets_param_fullwidth" />
                    </p>
           
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('uaddr')); ?>"><?php esc_html_e('Address:', 'jardiwinery'); ?></label>
                        <input class="fld_uaddr" id="<?php echo esc_attr($this->get_field_id('uaddr')); ?>" name="<?php echo esc_attr($this->get_field_name('uaddr')); ?>" value="<?php echo esc_attr($instance['uaddr']); ?>" class="widgets_param_fullwidth" />
                    </p>
                    
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('ucity')); ?>"><?php esc_html_e('City:', 'jardiwinery'); ?></label>
                        <input class="fld_ucity" id="<?php echo esc_attr($this->get_field_id('ucity')); ?>" name="<?php echo esc_attr($this->get_field_name('ucity')); ?>" value="<?php echo esc_attr($instance['ucity']); ?>" class="widgets_param_fullwidth" />
                    </p>
                    
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('upostcode')); ?>"><?php esc_html_e('Post code:', 'jardiwinery'); ?></label>
                        <input class="fld_upostcode" id="<?php echo esc_attr($this->get_field_id('upostcode')); ?>" name="<?php echo esc_attr($this->get_field_name('upostcode')); ?>" value="<?php echo esc_attr($instance['upostcode']); ?>" class="widgets_param_fullwidth" />
                    </p>
                    
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('ucountry')); ?>"><?php esc_html_e('Country:', 'jardiwinery'); ?></label>
                        <input class="fld_ucountry" id="<?php echo esc_attr($this->get_field_id('ucountry')); ?>" name="<?php echo esc_attr($this->get_field_name('ucountry')); ?>" value="<?php echo esc_attr($instance['ucountry']); ?>" class="widgets_param_fullwidth" />
                    </p>
            
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('uemail')); ?>"><?php esc_html_e('E-mail:', 'jardiwinery'); ?></label>
                        <input class="fld_uemail" id="<?php echo esc_attr($this->get_field_id('uemail')); ?>" name="<?php echo esc_attr($this->get_field_name('uemail')); ?>" value="<?php echo esc_attr($instance['uemail']); ?>" class="widgets_param_fullwidth" />
                    </p>
            
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('usite')); ?>"><?php esc_html_e('Web Site URL:', 'jardiwinery'); ?></label>
                        <input class="fld_usite" id="<?php echo esc_attr($this->get_field_id('usite')); ?>" name="<?php echo esc_attr($this->get_field_name('usite')); ?>" value="<?php echo esc_attr($instance['usite']); ?>" class="widgets_param_fullwidth" />
                    </p>
				</div>
                <div id="tab_text" class="tab_content tab_text">
                    <p>
                        <label for="<?php echo esc_attr($this->get_field_id('fld_text')); ?>"><?php esc_html_e('Text to show as QR Code:', 'jardiwinery'); ?></label>
                        <textarea class="fld_text" id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php echo esc_attr($this->get_field_name('text')); ?>" class="widgets_param_fullwidth"><?php echo esc_html($instance['text']); ?></textarea>
                    </p>
				</div>
                    
            </div>            
            <input class="fld_uid" id="<?php echo esc_attr($this->get_field_id('uid')); ?>" name="<?php echo esc_attr($this->get_field_name('uid')); ?>" value="<?php echo esc_attr($instance['uid']); ?>" type="hidden" />
            <input class="fld_urev" id="<?php echo esc_attr($this->get_field_id('urev')); ?>" name="<?php echo esc_attr($this->get_field_name('urev')); ?>" value="<?php echo esc_attr($instance['urev']); ?>" type="hidden" />
    
            <p>
                <input class="fld_button_draw" id="<?php echo esc_attr($this->get_field_id('button_draw')); ?>" name="<?php echo esc_attr($this->get_field_name('button_draw')); ?>" value="<?php esc_attr_e('Update', 'jardiwinery'); ?>" type="button" />
                <input class="fld_auto_draw" id="<?php echo esc_attr($this->get_field_id('auto_draw')); ?>" name="<?php echo esc_attr($this->get_field_name('auto_draw')); ?>" value="1" type="checkbox" <?php echo (1==$instance['auto_draw'] ? 'checked="checked"' : ''); ?> />
                <label for="<?php echo esc_attr($this->get_field_id('auto_draw')); ?>"> <?php esc_html_e('Auto', 'jardiwinery'); ?></label>
            </p>
            <input class="fld_image" id="<?php echo esc_attr($this->get_field_id('image')); ?>" name="<?php echo esc_attr($this->get_field_name('image')); ?>" value="" type="hidden" />
            <div id="<?php echo esc_attr($this->get_field_id('qrcode_image')); ?>" class="qrcode_image"><img src="<?php echo trim($instance['image']); ?>" alt="" /></div>
            <div id="<?php echo esc_attr($this->get_field_id('qrcode_data')); ?>" class="qrcode_data">
<?php if ($instance['show_personal']==1) { ?>
                <ul>
				<?php if ($instance['show_what']==1) { ?>
                    <li class="user_name odd first"><?php echo  esc_html__('Name:', 'jardiwinery') . ' ' . ($instance['ufname']) . ' ' . ($instance['ulname']); ?></li>
                    <?php 
						echo  ($instance['ucompany'] ? '<li class="user_company even">' . esc_html__('Company:', 'jardiwinery') . ' ' . ($instance['ucompany']) . '</li>' : '')
							. ($instance['uphone'] ? '<li class="user_phone odd">' . esc_html__('Phone:', 'jardiwinery') . ' ' . ($instance['uphone']) . '</li>' : '')
							. ($instance['uemail'] ? '<li class="user_email even">' . esc_html__('E-mail:', 'jardiwinery') . ' ' . '<a href="' . esc_url('mailto:'.($instance['uemail'])) . '">' . ($instance['uemail']) . '</a></li>' : '')
							. ($instance['usite'] ? '<li class="user_site odd">' . esc_html__('Site:', 'jardiwinery') . ' ' . '<a href="' . esc_url($instance['usite']) . '" target="_blank">' . ($instance['usite']) . '</a></li>' : '');
					?>
				<?php } else { ?>
                    <li class="text odd first"><?php echo trim($instance['text']); ?></li>
				<?php } ?>
                </ul>
<?php } ?>
            </div>
		</div>

        <<?php echo esc_attr(jardiwinery_storage_get('tag_open'));?>>
            jQuery(document).ready(function(){
				jardiwinery_qrcode_init();
				jardiwinery_color_picker();
            });
			function jardiwinery_qrcode_init() {
				jQuery('#widgets-right .widget_qrcode:not(.inited)').each(function() {
					var widget = jQuery(this).addClass('inited');
					widget.find('input.iColorPicker:not(.colored)').each(function() {
						var obj = jQuery(this);
						if (obj.attr('id').indexOf('__i__') < 0) {
							obj.addClass('colored');
							jardiwinery_set_color_picker(obj.attr('id'));
						}
					});
					widget.find('div.qrcode_tabs').tabs();
					widget.on('click', '.fld_button_draw', function() {
						jardiwinery_qrcode_update(widget);
					});
					widget.parents('form').on('click', '.widget-control-save', function() {
						jardiwinery_qrcode_update(widget);
					});
					widget.find('.tab_personal input,.tab_text textarea,.fld_auto_draw,.iColorPicker').on('change', function () {
						if (widget.find('.fld_auto_draw').attr('checked')=='checked') {
							widget.find('.fld_button_draw').hide();
							jardiwinery_qrcode_update(widget);
						} else 
							widget.find('.fld_button_draw').show();
					});
					if (widget && widget.find('.fld_auto_draw').attr('checked')=='checked')
						widget.find('.fld_button_draw').hide();
				});
			}
            function jardiwinery_qrcode_update(widget) {
				jardiwinery_qrcode_show(widget, {
                        ufname:		widget.find('.fld_ufname').val(),
                        ulname:		widget.find('.fld_ulname').val(),
                        ucompany:	widget.find('.fld_ucompany').val(),
                        usite:		widget.find('.fld_usite').val(),
                        uemail:		widget.find('.fld_uemail').val(),
                        uphone:		widget.find('.fld_uphone').val(),
                        uaddr:		widget.find('.fld_uaddr').val(),
                        ucity:		widget.find('.fld_ucity').val(),
                        upostcode:	widget.find('.fld_upostcode').val(),
                        ucountry:	widget.find('.fld_ucountry').val(),
                        uid:		widget.find('.fld_uid').val(),
                        urev:		widget.find('.fld_urev').val(),
                        text: 		widget.find('.fld_text').val()
                    }, 
                    {
                        qrcode: widget.find('.qrcode_image').eq(0),
                        personal: widget.find('.qrcode_data'),
                        show_personal: widget.find('.fld_show_personal').attr('checked')=='checked',
                        show_what: widget.find('.fld_show_what').attr('checked')=='checked' ? 1 : 0,
                        width: widget.find('.fld_width').val(),
                        color: widget.find('.fld_color').val(),
                        bg: widget.find('.fld_bg').val()
                    }
                );
				var image = widget.find('.qrcode_image canvas').get(0).toDataURL('image/png');
				widget.find('.fld_image').val(image);
				widget.find('.qrcode_image img').attr('src', image);
            }
			function jardiwinery_qrcode_show(widget, vc, opt) {
				if (opt.show_what==1) {
					var text = 'BEGIN:VCARD\n'
						+ 'VERSION:3.0\n'
						+ 'FN:' + vc.ufname + ' ' + vc.ulname + '\n'
						+ 'N:' + vc.ulname + ';' + vc.ufname + '\n'
						+ (vc.ucompany ? 'ORG:' + vc.ucompany + '\n' : '')
						+ (vc.uphone ? 'TEL;TYPE=cell, pref:' + vc.uphone + '\n' : '')
						+ (vc.ufax ? 'TEL;TYPE=fax, pref:' + vc.ufax + '\n' : '')
						+ (vc.uaddr || vc.ucity || vc.ucountry ? 'ADR;TYPE=dom, home, postal, parcel:;;' + vc.uaddr + ';' + vc.ucity + ';;' + vc.upostcode + ';' + vc.ucountry + '\n' : '')
						+ (vc.usite ? 'URL:' + vc.usite + '\n' : '')
						+ (vc.uemail ? 'EMAIL;TYPE=INTERNET:' + vc.uemail + '\n' : '')
						+ (vc.ucats ? 'CATEGORIES:' + vc.ucats + '\n' : '')
						+ (vc.unote ? 'NOTE:' + vc.unote + '\n' : '')
						+ (vc.urev ? 'NOTE:' + vc.urev + '\n' : '')
						+ (vc.uid ? 'UID:' + vc.uid + '\n' : '')
						+ 'END:VCARD';
				} else {
					var text = vc.text;
				}
				opt.qrcode
					.empty()
					.qrcode({
						'text': text,
						'color': opt.color,
						'bgColor': opt.bg!='' ? opt.bg : null,
						'width': opt.width,
						'height': opt.width,
						'size': opt.width
					});
				if (opt.show_personal == 0)
					opt.personal.empty().hide(); 
				else
					opt.personal.html(
						'<ul>'
							+ (opt.show_what==1 
								? '<li class="user_name odd first">' + vc.ufname + ' ' + vc.ulname + '</li>'
									+ (vc.ucompany ? '<li class="user_company even">' + vc.ucompany + '</li>' : '')
									+ (vc.uphone ? '<li class="user_phone odd">' + vc.uphone + '</li>' : '')
									+ (vc.uemail ? '<li class="user_email even"><a href="mailto:' + vc.uemail + '">' + vc.uemail + '</a></li>' : '')
									+ (vc.usite ? '<li class="user_site odd"><a href="' + vc.usite + '" target="_blank">' + vc.usite + '</a></li>' : '')
								: '<li class="text odd first">' + vc.text + '</li>')
						+ '</ul>'
					).show();
			}
			
			if (!window.jardiwinery_set_color_picker) {
				function jardiwinery_set_color_picker(id_picker) {
					jQuery('#'+id_picker).on('click', function (e) {
						"use strict";
						jardiwinery_color_picker_show(null, jQuery(this), function(fld, clr) {
							"use strict";
							fld.css('backgroundColor', clr).val(clr);
						});
					});
				}
			}
        <<?php echo esc_attr(jardiwinery_storage_get('tag_close'));?>>
	<?php
	}
	
	// Load scripts
	function load_scripts() {
		jardiwinery_enqueue_script( 'jardiwinery-core-utils-script',	jardiwinery_get_file_url('js/core.utils.js'), array('jquery'), null, true );
		jardiwinery_enqueue_script('widget-qrcode-script', 		jardiwinery_get_file_url('widgets/qrcode/jquery.qrcode-0.6.0.min.js'), array('jquery'), null, true);
	}
}
?>