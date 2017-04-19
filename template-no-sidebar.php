<?php
/**
 *Template Name: No Sidebar
 *
 * @package Classroom Theme
 */

get_header(); ?>

<main id="content" class="column">

	<?php 
	//begin THE LOOP
	if( have_posts() ): ?>
	<?php while( have_posts() ): the_post(); ?>
		
		<article <?php post_class(); ?>>
		<?php if(! wp_is_mobile()){ ?>
			<a class="fullscreen-button">View Fullscreen</a>
		<?php } //end if not mobile ?>

			<?php classroom_thumbnail_title(''); ?>
			
			<div class="entry-content column xs-100 md-67 lg-67 ">
					<?php the_content(); ?>
				</div>				
				
		</article>

		<?php comments_template(); ?>

	<?php endwhile; ?>
	
<?php else: ?>
	<h2>Sorry, no posts found</h2>
	<p>Try a search instead</p>
	<?php get_search_form( ); ?>
<?php endif;  //end THE LOOP ?>

</main>

<?php get_footer(); ?>
