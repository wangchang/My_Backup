<?php
add_action('admin_init', 'theme_options_init');
add_action('admin_menu', 'theme_options_add_page');

function theme_options_init(){
	register_setting('sample_options', 'sncmono_theme_options', 'theme_options_validate');
}

function theme_options_add_page() {
	add_theme_page(__('Theme Options', 'snc-mono'), __('Theme Options', 'snc-mono'), 'edit_theme_options', 'theme_options', 'theme_options_do_page');
}

function theme_options_do_page() {
	global $select_options, $radio_options;

	if (!isset($_REQUEST['settings-updated'])) $_REQUEST['settings-updated'] = false;
	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . wp_get_theme() . __(' Theme Options', 'snc-mono') . "</h2>"; ?>

		<?php if (false !== $_REQUEST['settings-updated']) : ?>
		<div class="updated fade"><p><strong><?php _e('Options saved', 'snc-mono'); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
		
			<?php
			settings_fields('sample_options');
			$options = sncmono_default_options();
			
			if(empty($options['color_bg_header'])) $options['color_bg_header'] = ' ';
			if(empty($options['color_bg_content'])) $options['color_bg_content'] = ' ';
			?>
			
			<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#color_bg_header_picker').hide();
				jQuery('#color_bg_header_picker').farbtastic("#to_color_bg_header");
				jQuery("#to_color_bg_header").click(function(){jQuery('#color_bg_header_picker').slideToggle()});
				jQuery('#color_bg_content_picker').hide();
				jQuery('#color_bg_content_picker').farbtastic("#to_color_bg_content");
				jQuery("#to_color_bg_content").click(function(){jQuery('#color_bg_content_picker').slideToggle()});
			});
			</script>

			<table class="form-table">
				<tr>
					<td style="border-bottom:solid 1px #c0c0c0;" colspan="2"><h2><?php _e('Options', 'snc-mono'); ?></h2></td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e('Search box text', 'snc-mono'); ?></th>
					<td>
						<input id="to_search_box" class="regular-text" type="text" name="sncmono_theme_options[search_box]" value="<?php echo esc_attr($options['search_box']); ?>" />
						<label class="description" for="to_search_box"><small><?php _e('It\'s default text that shows inside search box.', 'snc-mono'); ?></small></label>
					</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e('Info bar', 'snc-mono'); ?></th>
					<td>
						<input id="to_info_bar" name="sncmono_theme_options[info_bar]" type="checkbox" value="1" <?php checked('1', $options['info_bar']); ?> />
						<label class="description" for="to_info_bar"><small><?php _e('Check to show info like author, category, etc. in your posts.', 'snc-mono'); ?></small></label>
					</td>
				</tr>
				<tr><td style="height:30px;" colspan="2"></td></tr>
				<tr>
					<td style="border-bottom:solid 1px #c0c0c0;" colspan="2"><h2><?php _e('Colors', 'snc-mono'); ?></h2></td>
				</tr>
				<tr><td style="height:20px;" colspan="2"></td></tr>
				<tr valign="top"><th scope="row"><?php _e('Header background color', 'snc-mono'); ?></th>
					<td>
						<input id="to_color_bg_header" class="regular-text" type="text" name="sncmono_theme_options[color_bg_header]" value="<?php echo esc_attr($options['color_bg_header']); ?>" />
						<label class="description" for="to_color_bg_header"><small><?php _e('Click here to show a color picker, click again to close.', 'snc-mono'); ?></small></label>
						<div id="color_bg_header_picker"></div>
					</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e('Content background color', 'snc-mono'); ?></th>
					<td>
						<input id="to_color_bg_content" class="regular-text" type="text" name="sncmono_theme_options[color_bg_content]" value="<?php echo esc_attr($options['color_bg_content']); ?>" />
						<label class="description" for="to_color_bg_content"><small><?php _e('Click here to show a color picker, click again to close.', 'snc-mono'); ?></small></label>
						<div id="color_bg_content_picker"></div>
					</td>
				</tr>
				<tr><td style="height:30px;" colspan="2"></td></tr>
				<tr>
					<td style="border-bottom:solid 1px #c0c0c0;" colspan="2"><h2><?php _e('Footer', 'snc-mono'); ?></h2></td>
				</tr>
				<tr><td style="height:20px;" colspan="2"></td></tr>
				<tr valign="top"><th scope="row"><?php _e('Footer', 'snc-mono'); ?></th>
					<td>
						<input id="to_footer" name="sncmono_theme_options[footer]" type="checkbox" value="1" <?php checked('1', $options['footer']); ?> />
						<label class="description" for="to_footer"><small><?php _e('Check to show footer.', 'snc-mono'); ?></small></label>
					</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e('Credit', 'snc-mono'); ?></th>
					<td>
						<input id="to_credit" name="sncmono_theme_options[credit]" type="checkbox" value="1" <?php checked('1', $options['credit']); ?> />
						<label class="description" for="to_credit"><small><?php _e('Check to show credit for this theme in footer.', 'snc-mono'); ?></small></label>
					</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e('Custom footer text', 'snc-mono'); ?></th>
					<td>
						<input id="to_footer_text" class="regular-text" type="text" name="sncmono_theme_options[footer_text]" value="<?php echo esc_attr($options['footer_text']); ?>" />
						<label class="description" for="to_footer_text"><small><?php _e('Set your own footer text.', 'snc-mono'); ?></small></label>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Options', 'snc-mono'); ?>" />
			</p>
		</form>
	</div>
	<?php
}

function theme_options_validate ($input) {
	global $select_options, $radio_options;
	$input['search_box'] = esc_attr(wp_filter_nohtml_kses($input['search_box']));
	$input['info_bar'] = ($input['info_bar'] == 1) ? 1 : 0 ;
	$input['color_bg_header'] = esc_attr(wp_filter_nohtml_kses($input['color_bg_header']));
	$input['color_bg_content'] = esc_attr(wp_filter_nohtml_kses($input['color_bg_content']));
	$input['footer'] = ($input['footer'] == 1) ? 1 : 0 ;
	$input['credit'] = ($input['credit'] == 1) ? 1 : 0 ;
	$input['footer_text'] = esc_attr(wp_filter_nohtml_kses($input['footer_text']));
	return $input;
}