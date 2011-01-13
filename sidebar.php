<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 */
?>

  </div><!-- #content -->
		<div id="sidebar" class="widget-area" role="complementary">
			<ul>

<?php
	/* When we call the dynamic_sidebar() function, it'll spit out
	 * the widgets for that widget area. If it instead returns false,
	 * then the sidebar simply doesn't exist, so we'll hard-code in
	 * some default sidebar stuff just in case.
	 */
	if (!function_exists('dynamic_sidebar') || !dynamic_sidebar()): ?>
			<li id="search" class="widget-container widget_search">
				<?php get_search_form(); ?>
			</li>

			<li id="meta" class="widget-container">
				<h3 class="widget-title">Meta</h3>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</li>

		<?php endif; // end primary widget area ?>
		  <li><a href="<?php bloginfo('template_directory'); ?>/calendar.html" target="_blank">View All Events &gt;&gt;</a></li>
			</ul>
		</div><!-- #sidebar -->
