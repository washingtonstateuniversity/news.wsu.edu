<section class="row side-right gutter pad-ends">

	<div class="column one">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'articles/post', get_post_type() ) ?>

		<?php endwhile; ?>

	</div><!--/column-->

	<div class="column two">
		<?php
		// @global WSU_News_Announcements $wsu_news_announcements
		global $wsu_content_type_announcement;
		// Load a sidebar when we're dealing with the display of announcements.
		if ( isset( $wsu_content_type_announcement->post_type ) && ( ( is_singular( $wsu_content_type_announcement->post_type ) ) ) ) {
			dynamic_sidebar( 'announcements-side' );
		} else {
			get_sidebar();
		}
		?>
	</div><!--/column two-->

</section>