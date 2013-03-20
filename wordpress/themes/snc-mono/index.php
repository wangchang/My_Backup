<?php get_header(); ?>

<div id="main">
	<div id="content">
		<?php
		if (is_search()) { echo '<div id="show_message">' . __('Search results for phrase', 'snc-mono') . ' "<b>' . get_search_query() . '</b>".</div>'; }
		if (have_posts()) : while (have_posts()) : the_post();
		?>
			<div id="post" <?php post_class(); ?>>
				<div id="post_title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></div>
				<div id="post_date"><?php the_time('F j, Y');?>&nbsp;&mdash;&nbsp;<?php the_time('G:i'); edit_post_link(__('Edit', 'snc-mono') , ' | '); ?></div>
				<div id="clear"></div>
				<hr />
				<?php if (sncmono_info_bar()) { ?>
				<div id="post_info"><b><?php _e('Author:', 'snc-mono'); ?></b> <?php the_author() ?> | <b><?php _e('Category:', 'snc-mono'); ?></b> <?php the_category(' '); the_tags(' | <b>'.__('Tags:', 'snc-mono').'</b> ', ', ', ''); ?> | <?php _e('<b>Comments: </b>', 'snc-mono'); comments_popup_link('0',  '1', '%', '', __('Off', 'snc-mono')); ?></div>
				<?php } ?>
				<div id="post_content">
					<?php
					if ( has_post_thumbnail() ) { the_post_thumbnail(); }
					the_content('<div id="post_pages">'.__('more...', 'snc-mono').'</div>');
					wp_link_pages(array('before' => '<div id="post_pages">'.__('Pages:', 'snc-mono'), 'after' => '</div><div id="clear"></div>', 'link_before' => '<span class="link">', 'link_after' => '</span>'));
					?>
				</div>
				<?php comments_template(); ?>
			</div>
		<?php endwhile; else: ?>
		<p><?php _e('Sorry, no posts have matched your criteria.', 'snc-mono'); ?></p>
		<?php endif; ?>
		<div id="post_nav"><?php posts_nav_link(); ?></div>
	</div>
	
	<?php
	get_sidebar();
	get_footer();
	?>