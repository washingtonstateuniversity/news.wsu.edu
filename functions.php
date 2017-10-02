<?php

// Disable the Select2 interface used with WSU University Taxonomy.
add_filter( 'wsu_taxonomy_select2_interface', '__return_false' );

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

add_shortcode( 'wsu_news_valentine', 'wsu_news_display_valentine_shortcode' );
/**
 * Display a specific WSU Valentine gallery from Facebook.
 *
 * @param $args
 *
 * @return string
 */
function wsu_news_display_valentine_shortcode( $args ) {
	ob_start();
	?><div id="fb-root"></div><script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";  fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script><div class="fb-post" data-href="https://www.facebook.com/media/set/?set=a.1108671689165435.1073741950.133018306730783&amp;type=3" data-width="500"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/media/set/?set=a.1108671689165435.1073741950.133018306730783&amp;type=3"><p>Here are #WSU Love Stories submitted from our Facebook fans. #WSUValentines #GoCougs</p>Posted by <a href="https://www.facebook.com/WSUPullman/">Washington State University</a> on&nbsp;<a href="https://www.facebook.com/media/set/?set=a.1108671689165435.1073741950.133018306730783&amp;type=3">Wednesday, February 10, 2016</a></blockquote></div></div><?php
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
