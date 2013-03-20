<?php
$content_width = 520;

require_once (get_template_directory().'/theme-options.php');

//Source: http://bitacre.com/1935/php-function-to-check-current-wordpress-version
function sncmono_is_wp_version($is_ver) {
    $wp_ver = explode('.', get_bloginfo('version'));
    $is_ver = explode('.', $is_ver);
    for($i=0; $i<=count($is_ver); $i++)
        if(!isset($wp_ver[$i])) array_push($wp_ver, 0);
 
    foreach($is_ver as $i => $is_val)
        if($wp_ver[$i] < $is_val) return false;
    return true;
}

function sncmono_custom_header() {
	$options = get_option('sncmono_theme_options');
	echo '<style type="text/css">';
	if ($options['color_bg_content']) {
		echo '
			body { background-color: '.esc_attr($options['color_bg_content']).'; }
			#links .horizontal_menu div:hover, #links .current_page_item div { background-color: '.esc_attr($options['color_bg_content']).' !important; }
			';
	} else {
		echo '
			body { background-color: #fff; }
			#links .horizontal_menu div:hover, #links .current_page_item div { background-color: #fff !important; }
			';
	}
	if ($options['color_bg_header']) {
		echo '#top { background-color: '.esc_attr($options['color_bg_header']).'; }';
	} else {
		echo '#top { background-color: #505050; }';
	}
	if (get_header_textcolor() != null AND get_header_textcolor() != 'blank') {
		echo '#header, #header a { color: #'.get_header_textcolor().'; }';
	} else {
		echo '#header, #header a { color: #fff; }';
	}
	echo '</style>';
}
add_action('wp_head', 'sncmono_custom_header');

function sncmono_scripts() {
	wp_enqueue_script('sncmono_search_bar', get_template_directory_uri().'/functions.js');
}
add_action('wp_enqueue_scripts', 'sncmono_scripts');

function sncmono_sidebars() {
	register_sidebar();
}
add_action('widgets_init', 'sncmono_sidebars');

function sncmono_theme_setup() {
	add_theme_support('automatic-feed-links');
	add_theme_support('post-thumbnails');
	add_theme_support('menus');
	set_post_thumbnail_size(150, 150);
	
	$custom_header_args = array(
		'random-default' => false,
		'width' => 150,
		'height' => 100,
		'flex-height' => true,
		'flex-width' => true,
		'header-text' => true,
		'uploads' => true,
		);
	if (sncmono_is_wp_version('3.4')) {
		add_theme_support('custom-header', $custom_header_args);
	} else {
		add_custom_image_header($custom_header_args);
	}
 }
add_action('after_setup_theme', 'sncmono_theme_setup');

function sncmono_register_custom_menu() {
	register_nav_menu('horizontal_menu', __('Horizontal Menu', 'snc-mono'));
}
add_action('init', 'sncmono_register_custom_menu');

function sncmono_farbtastic() {
	wp_enqueue_style('farbtastic');
	wp_enqueue_script('farbtastic');
}
add_action('init', 'sncmono_farbtastic');

function sncmono_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	?>
	<li>
		<div id="comment_<?php comment_ID(); ?>" class="comment">
			<div id="comment_info"><?php echo get_avatar($comment->comment_author_email, 48); ?></div>
			<div id="comment_content">
				<div id="comment_link"><a href="#comment_<?php comment_ID() ?>">#<?php comment_ID() ?></a></div>
				<div id="comment_author"><?php echo get_comment_author_link() ?></div>
				<?php if ($comment->comment_approved == '0'): ?>
					<em><?php _e('Your comment is waiting for an approval.', 'snc-mono') ?></em>
				<?php endif; ?>
				<div id="comment_text"><?php comment_text() ?></div>
				<div id="comment_reply"><?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
				<div id="comment_date"><?php echo get_comment_time('F j, Y') ?>&nbsp;&mdash;&nbsp;<?php echo get_comment_time('G:i'); edit_comment_link(__('Edit', 'snc-mono'), ' | '); ?></div>
			</div>
			<div id="clear"></div>
		</div>
		<?php
}

function sncmono_default_options() {
	$options = get_option('sncmono_theme_options');
	if (!isset($options['search_box'])) $options['search_box'] = 'Search...';
	if (!isset($options['info_bar'])) $options['info_bar'] = 1;
	if (!isset($options['header_color'])) $options['header_color'] = '#505050';
	if (!isset($options['color_bg_content'])) $options['color_bg_content'] = '#ffffff';
	if (!isset($options['footer'])) $options['footer'] = 1;
	if (!isset($options['credit'])) $options['credit'] = 1;
	if (!isset($options['footer_text'])) $options['footer_text'] = '';
	return $options;
}

$_SESSION['sncmono_options'] = sncmono_default_options();

function sncmono_info_bar() {
	$options = get_option('sncmono_theme_options');
	return ($options['info_bar'] == 1) ? true : false;
}

function sncmono_footer() {
	$options = get_option('sncmono_theme_options');
	if ($options['footer'] == 1 OR !isset($options['footer'])) {
		return true;
	} else {
		return false;
	}
}

function sncmono_credit() {
	$options = get_option('sncmono_theme_options');
	if ($options['credit'] == 1 OR !isset($options['credit'])) {
		$e = 'Designed by <a href="http://threecircles.pl/" target="_blank" title="Three Circles - truly innovative approach. | Creating websites, web applications, scripts, tests and engines for Your own site.">Three Circles</a>.';
		if (!empty($options['footer_text'])) $e .= '&nbsp;';
		return $e;
	}
}
	
function sncmono_footer_text() {
}
?>