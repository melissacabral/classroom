<article <?php post_class(); ?>>
	
		<?php classroom_post_meta(); ?>	
<a href="<?php the_permalink(); ?>">
		<?php classroom_thumbnail_title() ?>
	</a>
		<div class="entry-content">
			<?php
			if(is_singular()){
				the_content();
				wp_link_pages( );
			}else{
				the_excerpt();
			} ?>
		</div>
	

</article>