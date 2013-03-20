<?php
/**
 * Plugin Name: WP Post Stats
 * Plugin URI: http://onlinewebapplication.com/2012/07/wp-post-stats.html
 * Description: Simple post stats plugin. Enables you to display how many times a post/page had been viewed.
 * Version:       2.0
 * Author:        Pankaj Jha
 * Author URI:    http://onlinewebapplication.com/
 * License:       GNU General Public License, v2 (or newer)
 * License URI:  http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
/*  Copyright 2012 Pankaj Jha (onlinewebapplication.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation using version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/



### Variables Variables Variables
$base_name = plugin_basename('wp-post-stats/post-stats-options.php');
$base_page = 'admin.php?page='.$base_name;
$id = (isset($_GET['id']) ? intval($_GET['id']) : 0);
$mode = (isset($_GET['mode']) ? trim($_GET['mode']) : '');
$views_settings = array('views_options', 'widget_views_most_viewed', 'widget_views');
$views_postmetas = array('views');


### Form Processing
// Update Options
if(!empty($_POST['Submit'])) {
	$views_options = array();
	$views_options['count'] = intval($_POST['views_count']);
	$views_options['exclude_bots'] = intval($_POST['views_exclude_bots']);
	$views_options['display_home'] = intval($_POST['views_display_home']);
	$views_options['display_single'] = intval($_POST['views_display_single']);
	$views_options['display_page'] = intval($_POST['views_display_page']);
	$views_options['display_archive'] = intval($_POST['views_display_archive']);
	$views_options['display_search'] = intval($_POST['views_display_search']);
	$views_options['display_other'] = intval($_POST['views_display_other']);
	$views_options['template'] =  trim($_POST['views_template_template']);
	$views_options['most_viewed_template'] =  trim($_POST['views_template_most_viewed']);
	$update_views_queries = array();
	$update_views_text = array();
	$update_views_queries[] = update_option('views_options', $views_options);
	$update_views_text[] = __('Post Stats Options', 'wp-post-stats');
	$i=0;
	$text = '';
	foreach($update_views_queries as $update_views_query) {
		if($update_views_query) {
			$text .= '<font color="green">'.$update_views_text[$i].' '.__('Updated', 'wp-post-stats').'</font><br />';
		}
		$i++;
	}
	if(empty($text)) {
		$text = '<font color="red">'.__('No Post Stats Option Updated', 'wp-post-stats').'</font>';
	}
}
// Decide What To Do
if(!empty($_POST['do'])) {
	//  Uninstall WP-Post-Stats
	switch($_POST['do']) {		
		case __('UNINSTALL WP-Post-Stats', 'wp-post-stats') :
			if(trim($_POST['uninstall_views_yes']) == 'yes') {
				echo '<div id="message" class="updated fade">';
				echo '<p>';
				foreach($views_settings as $setting) {
					$delete_setting = delete_option($setting);
					if($delete_setting) {
						echo '<font color="green">';
						printf(__('Setting Key \'%s\' has been deleted.', 'wp-post-stats'), "<strong><em>{$setting}</em></strong>");
						echo '</font><br />';
					} else {
						echo '<font color="red">';
						printf(__('Error deleting Setting Key \'%s\'.', 'wp-post-stats'), "<strong><em>{$setting}</em></strong>");
						echo '</font><br />';
					}
				}
				echo '</p>';
				echo '<p>';
				foreach($views_postmetas as $postmeta) {
					$remove_postmeta = $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '$postmeta'");
					if($remove_postmeta) {
						echo '<font color="green">';
						printf(__('Post Meta Key \'%s\' has been deleted.', 'wp-post-stats'), "<strong><em>{$postmeta}</em></strong>");
						echo '</font><br />';
					} else {
						echo '<font color="red">';
						printf(__('Error deleting Post Meta Key \'%s\'.', 'wp-post-stats'), "<strong><em>{$postmeta}</em></strong>");
						echo '</font><br />';
					}
				}
				echo '</p>';
				echo '</div>'; 
				$mode = 'end-UNINSTALL';
			}
			break;
	}
}


### Determines Which Mode It Is
switch($mode) {
		//  Deactivating WP-Post-Stats
		case 'end-UNINSTALL':
			$deactivate_url = 'plugins.php?action=deactivate&amp;plugin=wp-post-stats/wp-post-stats.php';
			if(function_exists('wp_nonce_url')) { 
				$deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_wp-post-stats/wp-post-stats.php');
			}
			echo '<div class="wrap">';
			echo '<h2>'.__('Uninstall WP-Post-Stats', 'wp-post-stats').'</h2>';
			echo '<p><strong>'.sprintf(__('<a href="%s">Click Here</a> To Finish The Uninstallation And WP-Post-Stats Will Be Deactivated Automatically.', 'wp-post-stats'), $deactivate_url).'</strong></p>';
			echo '</div>';
			break;
	// Main Page
	default:
		$views_options = get_option('views_options');
?>
<script type="text/javascript">
	/* <![CDATA[*/
	function views_default_templates(template) {
		var default_template;
		switch(template) {
			case 'template':
				default_template = "<?php _e('%VIEW_COUNT% views', 'wp-post-stats'); ?>";
				break;
			case 'most_viewed':
				default_template = "<li><a href=\"%POST_URL%\"  title=\"%POST_TITLE%\">%POST_TITLE%</a> - %VIEW_COUNT% <?php _e('views', 'wp-post-stats'); ?></li>";
				break;
		}
		jQuery("#views_template_" + template).val(default_template);
	}
	/* ]]> */
</script>
<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>
<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e('Post Stats Options', 'wp-post-stats'); ?></h2>
	<table class="form-table">
		 <tr>
			<td valign="top" width="30%"><strong><?php _e('Count Views From:', 'wp-post-stats'); ?></strong></td>
			<td valign="top">
				<select name="views_count" size="1">
					<option value="0"<?php selected('0', $views_options['count']); ?>><?php _e('Everyone', 'wp-post-stats'); ?></option>
					<option value="1"<?php selected('1', $views_options['count']); ?>><?php _e('Guests Only', 'wp-post-stats'); ?></option>
					<option value="2"<?php selected('2', $views_options['count']); ?>><?php _e('Registered Users Only', 'wp-post-stats'); ?></option>
				</select>
			</td>
		</tr>
		 <tr>
			<td valign="top" width="30%"><strong><?php _e('Exclude Bot Views:', 'wp-post-stats'); ?></strong></td>
			<td valign="top">
				<select name="views_exclude_bots" size="1">
					<option value="0"<?php selected('0', $views_options['exclude_bots']); ?>><?php _e('No', 'wp-post-stats'); ?></option>
					<option value="1"<?php selected('1', $views_options['exclude_bots']); ?>><?php _e('Yes', 'wp-post-stats'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<strong><?php _e('Views Template:', 'wp-post-stats'); ?></strong><br /><br />
				<?php _e('Allowed Variables:', 'wp-post-stats'); ?><br />
				- %VIEW_COUNT%<br /><br />
				<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-post-stats'); ?>" onclick="views_default_templates('template');" class="button" />
			</td>
			<td valign="top">
				<input type="text" id="views_template_template" name="views_template_template" size="70" value="<?php echo htmlspecialchars(stripslashes($views_options['template'])); ?>" />
			</td>
		</tr>
		<tr>
			<td valign="top">
				<strong><?php _e('Most Viewed Template:', 'wp-post-stats'); ?></strong><br /><br />
				<?php _e('Allowed Variables:', 'wp-post-stats'); ?><br />
				- %VIEW_COUNT%<br />
				- %POST_TITLE%<br />
				- %POST_EXCERPT%<br />
				- %POST_CONTENT%<br />
				- %POST_URL%<br /><br />
				<input type="button" name="RestoreDefault" value="<?php _e('Restore Default Template', 'wp-post-stats'); ?>" onclick="views_default_templates('most_viewed');" class="button" />
			</td>
			<td valign="top">
				<textarea cols="80" rows="15"  id="views_template_most_viewed" name="views_template_most_viewed"><?php echo htmlspecialchars(stripslashes($views_options['most_viewed_template'])); ?></textarea>
			</td>
		</tr>
	</table>
	<h3><?php _e('Display Options', 'wp-post-stats'); ?></h3>
	<p><?php _e('These options specify where the view counts should be displayed and to whom. 	By default view counts will be displayed to all visitors. Note that the theme files must contain a call to <code>the_views()</code> in order for any view count to be displayed.', 'wp-post-stats'); ?></p>
	<table class="form-table">
		<tr>
			<td valign="top"><strong><?php _e('Home Page:', 'wp-post-stats'); ?></strong></td>
			<td>
				<select name="views_display_home" size="1">
					<option value="0"<?php selected('0', $views_options['display_home']); ?>><?php _e('Display to everyone', 'wp-post-stats'); ?></option>
					<option value="1"<?php selected('1', $views_options['display_home']); ?>><?php _e('Display to registered users only', 'wp-post-stats'); ?></option>
					<option value="2"<?php selected('2', $views_options['display_home']); ?>><?php _e('Don\'t display on home page', 'wp-post-stats'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top"><strong><?php _e('Singe Posts:', 'wp-post-stats'); ?></strong></td>
			<td>
				<select name="views_display_single" size="1">
					<option value="0"<?php selected('0', $views_options['display_single']); ?>><?php _e('Display to everyone', 'wp-post-stats'); ?></option>
					<option value="1"<?php selected('1', $views_options['display_single']); ?>><?php _e('Display to registered users only', 'wp-post-stats'); ?></option>
					<option value="2"<?php selected('2', $views_options['display_single']); ?>><?php _e('Don\'t display on single posts', 'wp-post-stats'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top"><strong><?php _e('Pages:', 'wp-post-stats'); ?></strong></td>
			<td>
				<select name="views_display_page" size="1">
					<option value="0"<?php selected('0', $views_options['display_page']); ?>><?php _e('Display to everyone', 'wp-post-stats'); ?></option>
					<option value="1"<?php selected('1', $views_options['display_page']); ?>><?php _e('Display to registered users only', 'wp-post-stats'); ?></option>
					<option value="2"<?php selected('2', $views_options['display_page']); ?>><?php _e('Don\'t display on pages', 'wp-post-stats'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top"><strong><?php _e('Archive Pages:', 'wp-post-stats'); ?></strong></td>
			<td>
				<select name="views_display_archive" size="1">
					<option value="0"<?php selected('0', $views_options['display_archive']); ?>><?php _e('Display to everyone', 'wp-post-stats'); ?></option>
					<option value="1"<?php selected('1', $views_options['display_archive']); ?>><?php _e('Display to registered users only', 'wp-post-stats'); ?></option>
					<option value="2"<?php selected('2', $views_options['display_archive']); ?>><?php _e('Don\'t display on archive pages', 'wp-post-stats'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top"><strong><?php _e('Search Pages:', 'wp-post-stats'); ?></strong></td>
			<td>
				<select name="views_display_search" size="1">
					<option value="0"<?php selected('0', $views_options['display_search']); ?>><?php _e('Display to everyone', 'wp-post-stats'); ?></option>
					<option value="1"<?php selected('1', $views_options['display_search']); ?>><?php _e('Display to registered users only', 'wp-post-stats'); ?></option>
					<option value="2"<?php selected('2', $views_options['display_search']); ?>><?php _e('Don\'t display on search pages', 'wp-post-stats'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top"><strong><?php _e('Other Pages:', 'wp-post-stats'); ?></strong></td>
			<td>
				<select name="views_display_other" size="1">
					<option value="0"<?php selected('0', $views_options['display_other']); ?>><?php _e('Display to everyone', 'wp-post-stats'); ?></option>
					<option value="1"<?php selected('1', $views_options['display_other']); ?>><?php _e('Display to registered users only', 'wp-post-stats'); ?></option>
					<option value="2"<?php selected('2', $views_options['display_other']); ?>><?php _e('Don\'t display on other pages', 'wp-post-stats'); ?></option>
				</select>
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'wp-post-stats'); ?>" />
	</p>
</div>
</form> 
<p>&nbsp;</p>

<!-- Uninstall WP-Post-Stats -->
<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
<div class="wrap"> 
	<h3><?php _e('Uninstall WP-Post-Stats', 'wp-post-stats'); ?></h3>
	<p>
		<?php _e('Deactivating WP-Post-Stats plugin does not remove any data that may have been created, such as the views data. To completely remove this plugin, you can uninstall it here.', 'wp-post-stats'); ?>
	</p>
	<p style="color: red">
		<strong><?php _e('WARNING:', 'wp-post-stats'); ?></strong><br />
		<?php _e('Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.', 'wp-post-stats'); ?>
	</p>
	<p style="color: red">
		<strong><?php _e('The following WordPress Options/PostMetas will be DELETED:', 'wp-post-stats'); ?></strong><br />
	</p>
	<table class="widefat">
		<thead>
			<tr>
				<th><?php _e('WordPress Options', 'wp-post-stats'); ?></th>
				<th><?php _e('WordPress PostMetas', 'wp-post-stats'); ?></th>
			</tr>
		</thead>
		<tr>
			<td valign="top">
				<ol>
				<?php
					foreach($views_settings as $settings) {
						echo '<li>'.$settings.'</li>'."\n";
					}
				?>
				</ol>
			</td>
			<td valign="top" class="alternate">
				<ol>
				<?php
					foreach($views_postmetas as $postmeta) {
						echo '<li>'.$postmeta.'</li>'."\n";
					}
				?>
				</ol>
			</td>
		</tr>
	</table>
	<p>&nbsp;</p>
	<p style="text-align: center;">
		<input type="checkbox" name="uninstall_views_yes" value="yes" />&nbsp;<?php _e('Yes', 'wp-post-stats'); ?><br /><br />
		<input type="submit" name="do" value="<?php _e('UNINSTALL WP-Post-Stats', 'wp-post-stats'); ?>" class="button" onclick="return confirm('<?php _e('You Are About To Uninstall WP-Post-Stats From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.', 'wp-post-stats'); ?>')" />
	</p>
</div> 
</form>
<?php
} // End switch($mode)
?>