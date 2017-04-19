<?php get_header(); ?>

<main id="content" class="column xs-100 md-67 lg-80">

	<?php 
	//begin THE LOOP
	if( have_posts() ): ?>
	<?php while( have_posts() ): the_post(); 
	//does this post have meta to show in the sidebar?
	if (has_classroom_meta()) {
		$classes = 'xs-100  lg-67';
	}else{
		$classes = 'xs-100  lg-100';
	}
	?>
		
		<article <?php post_class(); ?>>
			<?php if(! wp_is_mobile()){ ?>
			<a class="fullscreen-button">View Fullscreen</a>
			<?php } //end if not mobile ?>

			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php classroom_important_info_box(); ?>
			<div class="row">

				<div class="entry-content column <?php echo $classes; ?> ">

					<?php the_content(); ?>
				</div>

				<?php get_sidebar('single'); ?>	
			</div>		
		</article>

		<?php comments_template(); ?>

	<?php endwhile; ?>

	<?php classroom_pagination(); ?>
	
<?php else: ?>
	<h2>Sorry, no posts found</h2>
	<p>Try a search instead</p>
	<?php get_search_form( ); ?>
<?php endif;  //end THE LOOP ?>

</main>

<?php get_footer(); ?>
