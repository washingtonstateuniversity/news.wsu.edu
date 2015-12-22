<?php

add_action( 'widgets_init', 'wsu_news_register_sidebars' );
/**
 * Register the sidebars and custom widgets used by the theme.
 */
function wsu_news_register_sidebars() {
	$sidebar_args = array(
		'name' => 'Announcements Sidebar',
		'id' => 'announcements-side',
		'description' => 'Displays the sidebar on announcements views.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>\n",
	);
	register_sidebar( $sidebar_args );
}