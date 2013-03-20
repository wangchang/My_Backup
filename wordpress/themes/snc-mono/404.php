<?php get_header(); ?>

<div id="main">
	<div id="content">
		<div id="foofo">
			<?php _e('Sorry but what you\'re looking for has been moved or even deleted.', 'snc-mono'); ?><br />
			<?php _e('Try using search form to look for it and find out what happened.', 'snc-mono'); ?>
		</div>
	</div>
	
	<?php
	get_sidebar();
	get_footer();
	?>