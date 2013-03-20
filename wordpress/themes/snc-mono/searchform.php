<div id="search">
	<form method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>">
		<input type="text" id="search_bar" class="search_bar_inactive" name="s" value="<?php echo esc_attr($_SESSION['sncmono_options']['search_box']); ?>" onfocus="search_focus(this, '<?php echo esc_attr($_SESSION['sncmono_options']['search_box']); ?>');" onblur="search_blur(this, '<?php echo esc_attr($_SESSION['sncmono_options']['search_box']); ?>');" />
		<input type="image" src="<?php echo get_template_directory_uri(); ?>/images/search_icon.png" onsubmit="submit-fom();" id="search_icon" />
	</form>
</div>