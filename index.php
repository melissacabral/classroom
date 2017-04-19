<?php get_header(); ?>
<main id="content" class="column xs-100 md-75 lg-60">
	<?php 
	//begin THE LOOP
	if( have_posts() ): ?>
		<?php while( have_posts() ): the_post();
		
			get_template_part( 'content' );
		
		
		endwhile; ?>

	<?php classroom_pagination(); ?>
	
	<?php else: ?>
		<h2>Sorry, no posts found</h2>
		<p>Try a search instead</p>
		<?php get_search_form( ); ?>
	<?php endif;  //end THE LOOP ?>
	
</main>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
