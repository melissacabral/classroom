<?php
//Template Name: Assignments by Due Date
/**
 * List of all posts with the due date field. shows a countdown to the deadline
 *
 * @package Classroom Theme
 */

get_header(); ?>


<main id="content" class="column xs-100 md-67 lg-80">

	<?php if ( have_posts() ) : ?>

		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if(! wp_is_mobile()){ ?>
				<a class="fullscreen-button">View Fullscreen</a>
				<?php } //end if not mobile ?>

				<?php classroom_thumbnail_title(''); ?>

				<div class="row">
				<div class="entry-content column">
						<?php the_content();?>

						<?php 
						$today = current_time( 'Ymd' , 0);

						$args = array (
							'showposts' => 1000,
							'post_type' => array('post','page'),
							'ignore_sticky_posts' => 0,
							'order' => 'ASC',
							'orderby'   => 'meta_value_num',
							'meta_key'  => 'due_date',
							'meta_query' => array(
								array(
									'key'		=> 'due_date',
									'compare'	=> '>=',
									'value'		=> $today,
									),
								),
							);
						$q = new WP_Query($args);

				 //begin custom loop
						if( $q->have_posts() ): ?>
						<h2>Upcoming Assignments:</h2>
						<table class="assignments">
							<tr>
								<th>Date Due</th>
								<th>&nbsp;</th>
								<th>Assignment</th>


							</tr>
							<?php while( $q->have_posts() ): $q->the_post(); ?>
								<tr>
									<td>

										<?php echo classroom_show_duedate(''); ?>
									</td>
									<td>


										<?php echo  classroom_count_days( classroom_get_duedate(), $today ); ?> 
									</td>
									<td>
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>				
									</td>


								</tr>

							<?php endwhile; ?>
						</table>
					<?php else: ?>
						<h2>There are no upcoming Assignments</h2>
					<?php endif;  //end THE upcoming assignments LOOP 
					wp_reset_postdata(); ?>

					<?php 
					$args = array (
						'showposts' => 1000,
						'post_type' => array('post','page'),
						'ignore_sticky_posts' => 0,
						'order' => 'ASC',
						'orderby'   => 'meta_value_num',
						'meta_key'  => 'due_date',
						'meta_query' => array(
							array(
								'key'		=> 'due_date',
								'compare'	=> '<',
								'value'		=> $today,
								),
							),
						);
					$q = new WP_Query($args);

				 //begin custom loop
					if( $q->have_posts() ): ?>
					<h2>Past Assignments:</h2>
					<table class="assignments">
						<tr>
							<th>Date Due</th>
							<th>&nbsp;</th>
							<th>Assignment</th>


						</tr>
						<?php while( $q->have_posts() ): $q->the_post(); 
						if(get_post_meta( $post->ID, 'due_date', true )!= ''): ?>
						<tr>
							<td>

								<?php echo classroom_show_duedate(''); ?>
							</td>
							<td>


								<?php echo  classroom_count_days( classroom_get_duedate(), $today ); ?> past
							</td>
							<td>
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>				
							</td>


						</tr>

						<?php endif; //due_date is not blank
						endwhile; ?>
					</table>
				<?php else: ?>
					<h2>There are no upcoming Assignments</h2>
				<?php endif;  //end THE past assignments LOOP
				wp_reset_postdata(); ?>



			</div><!-- .entry-content -->

			<footer class="entry-footer">
				<?php classroom_theme_entry_footer(); ?>
			</footer>
		</div>		
	</article>
<?php endwhile; ?>



<?php else : ?>

	<?php get_template_part( 'content', 'none' ); ?>

<?php endif; ?>

</main>



<?php get_footer(); ?>
