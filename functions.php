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

add_action( 'pre_get_posts', 'wsu_news_top_stories' );
/**
 * Set the home page query to only include posts from the Top Stories category.
 *
 * @param WP_Query $query Current query object to be modified.
 */
function wsu_news_top_stories( $query ) {
	if ( is_home() && $query->is_main_query() ) {
		$query->set( 'category_name', 'top-stories' );
	}
}

add_action( 'init', 'wsu_news_redirect_publication_id', 10 );
/**
 * Redirect old PublicationID based detail pages for articles to the corresponding
 * article's new URL at news.wsu.edu.
 */
function wsu_news_redirect_publication_id() {
	/* @var WPDB $wpdb */
	global $wpdb;
	if ( ! isset( $_SERVER['HTTP_HOST'] ) ) {
		return;
	}
	//pattern:
	//http://news.wsu.edu/pages/publications.asp?Action=Detail&PublicationID=36331&TypeID=1
	if ( isset( $_GET['PublicationID'] ) && isset( $_GET['Action'] ) && 'Detail' === $_GET['Action'] && 0 !== absint( $_GET['PublicationID'] ) ) {
		$publication_id = absint( $_GET['PublicationID'] );
		$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM wp_postmeta WHERE meta_key = '_publication_id' AND meta_value = %s", $publication_id ) );
		if ( 0 !== absint( $post_id ) ) {
			wp_safe_redirect( get_permalink( $post_id ), 301 );
			exit;
		}
	}
	//pattern:
	//http://news.wsu.edu/articles/36828/1/New-cyber-security-firm-protects-Seattle-businesses
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	if ( strpos( $actual_link,'/articles/')>-1 ) {
		$urlparts = explode('/',$actual_link);
		$publication_id = absint( $urlparts[4] );
		$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM wp_postmeta WHERE meta_key = '_publication_id' AND meta_value = %s", $publication_id ) );
		if ( 0 !== absint( $post_id ) ) {
			wp_safe_redirect( get_permalink( $post_id ), 301 );
			exit;
		}
	}
	return;
}