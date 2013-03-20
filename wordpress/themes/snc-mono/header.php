<!doctype html>
<html>
<head <?php language_attributes(); ?>>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<title><?php bloginfo('name'); ?></title>
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
	<?php if (is_singular()) wp_enqueue_script('comment-reply'); ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="top">
	<div id="top_content">
		<div id="header">
			<?php if (!empty(get_custom_header()->url)) { ?>
			<div class="image">
				<a href="<?php echo home_url(); ?>"><img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="<?php bloginfo('name'); ?>" /></a>
			</div>
			<?php } ?>
			<?php if (get_header_textcolor() != 'blank') { ?>
			<div class="text">
				<a href="<?php echo home_url(); ?>"><span class="title"><?php bloginfo('name'); ?></span></a><br />
				<span class="description"><?php bloginfo('description'); ?></span>
			</div>
			<?php } ?>
		</div>
		<div id="links">
			<?php wp_nav_menu(array('theme_location' => 'horizontal_menu', 'menu_class' => 'horizontal_menu', 'link_before' => '<div>', 'link_after' => '</div>', 'depth' => 0, 'show_home' => 1)); ?>
		</div>
	</div>
</div>