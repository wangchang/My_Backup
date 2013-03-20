<?php
/**
 * The Sidebar containing the main widget areas.
 */
?>
		<div id="sidebar" class="widget-area col300" role="complementary">
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
				<div class="widget-wrap">
				<aside id="archives" class="widget">
					<h2 class="widget-title"><?php _e( 'Archives', 'media-maven' ); ?></h2>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>
                </div>

				<div class="widget-wrap">
                <aside id="meta" class="widget">
					<h2 class="widget-title"><?php _e( 'Meta', 'media-maven' ); ?></h2>
					<ul>
						<?php wp_register(); ?>
						<aside><?php wp_loginout(); ?></aside>
						<?php wp_meta(); ?>
					</ul>
				</aside>
                </div>
			<?php endif; // end sidebar widget area ?>
		</div><!-- #secondary .widget-area -->
