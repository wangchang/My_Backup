<div id="sidebar_column">
	<ul id="sidebar">
		<?php if (!dynamic_sidebar()): ?>
			<?php wp_list_pages('title_li=<li><h2>'.__('Pages', 'snc-mono').'</h2></li>'); ?>
			<?php wp_list_bookmarks('title_before=<li><h2>&title_after=</h2></li>'); ?>
			<?php wp_list_categories('title_li=<li><h2>'.__('Categories', 'snc-mono').'</h2></li>'); ?>
			<li id="archives"><h2><?php _e('Archives:', 'snc-mono'); ?></h2>
				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>
			<li id="meta"><h2><?php _e('Meta:', 'snc-mono'); ?></h2>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</li>
		<?php endif; ?>
	</ul>
</div>
<div id="clear"></div>