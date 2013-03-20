<?php
if (post_password_required()) {
	echo '<p class="nocomments">'._e('This post is password protected. Enter the password to view comments.', 'snc-mono').'</p>';
	return;
}
if (have_comments()): ?>
	<div id="comment_list">
		<span id="comment_title"><?php _e('Comments:', 'snc-mono'); ?></span>
		<?php wp_list_comments('callback=sncmono_comment'); ?></ul>
	</div>
	<div id="comment_paginate_links">
		<?php paginate_comments_links() ?>
	</div>
<?php
endif;
$comment_args = array('comment_notes_after' => '');
comment_form($comment_args);
?>