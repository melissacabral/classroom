<?php
/**
* Custom template tags for this theme.
*
* Eventually, some of the functionality here could be replaced by core features.
*
* @package Classroom Theme
*/


/**
* Helper function to handle pagination for any template
*/
function classroom_pagination(){
	echo '<div class="pagination">';

	if( is_singular() ){
		?>
		<div class="nav-links">	
			<?php
//single post, page, etc.
			previous_post_link( '%link', '&larr; %title' );
			next_post_link(  '%link', '%title &rarr;' );
			?>
		</div>
		<?php
	}
	elseif( function_exists( 'the_posts_pagination' ) AND ! wp_is_mobile() ){
		the_posts_pagination();
	}
	else{
//archive, blog, search, etc.		
		previous_posts_link( '&larr; Newer Posts' );
		next_posts_link( 'Older Posts &rarr;' );
	}

	echo '</div>';
}
/**
* Display the due date custom field from the post 
* @return [type] [description]
*/
function classroom_show_duedate($text = 'Due '){
	global $post;

//get due date custom field 
	$date = get_post_meta( $post->ID, 'due_date', true);
	if($date == '')
		return false;
// $date = 19881123 (23/11/1988)
	if($date == date('Ymd')):
		echo '<span class="due-date due-today">' . $text .  'Today</span>';
	elseif($date < date('Ymd')):
		echo '<span class="due-date due-past">' . $text .  classroom_get_duedate() . '</span>';
	elseif($date):
		echo '<span class="due-date due-future">' . $text .  classroom_get_duedate() . '</span>';
	endif;
}
function classroom_get_duedate( $unix = 0 ){
	global $post;

//get due date custom field 
	$date = get_post_meta( $post->ID, 'due_date', true);

// $date = 19881123 (23/11/1988)
	if($date):
// extract Y,M,D
		$y = substr($date, 0, 4);
	$m = substr($date, 4, 2);
	$d = substr($date, 6, 2);

// create UNIX
	$time = strtotime("{$d}-{$m}-{$y}");		

// format date (November 11th 1988)
	if($unix):
		return $time;
	else:
		return  date('F j Y', $time);
	endif;
	else:
		return false;
	endif;
}
/**
* Counts the number of days between 2  timestamps
* @return [type] [description]
*/
function classroom_count_days($from, $to){

	$from_date = new DateTime($from);
	$to_date = new DateTime($to);
	$output =  $from_date->diff($to_date)->days;

	if($output == 1) :
		$output .= ' day';
	elseif($output == 0):
		$output = '<span class="today-due">Today!</span>';
	else:
		$output .= ' days';
	endif;
	return $output;
}

/**
* Add "today" class to post_class if the post was published today.
*/
add_filter('post_class', 'classroom_theme_post_class' );
function classroom_theme_post_class($classes){

	if( date('Yz') == get_the_time('Yz') ) {
		$classes[] = 'posted-today';
	}	
	return $classes;
}

/**
* Prints HTML with meta information for the date, categories and comments.
*/
function classroom_theme_entry_meta() {
// Show the date posted
	if( date('Yz') == get_the_time('Yz') ) {		
		$date = 'Posted today';
	}else{	
		$date = 'Posted ' . human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; 
	}	



// Show the date of the post
	if ( 'post' == get_post_type() ) :
		echo '<header class="entry-header" >';

	echo '<a class="post-date" href="'. get_permalink() . '"><time datetime="'.get_the_time('F jS, Y').'">';
	echo $date;
	echo '</time></a>';



	/* translators: used between list items, there is a space after the comma */
	$categories_list = get_the_category_list( __( ', ', 'classroom' ) );
	if ( $categories_list && classroom_theme_categorized_blog() ) {
		printf( '<span class="cat-links">' . __( '%1$s', 'classroom' ) . '</span>', $categories_list );
	} 

	classroom_show_duedate();


	echo '</header>';
	endif;	
}


function classroom_theme_entry_footer() {
	/* translators: used between list items, there is a space after the comma */
	$tags_list = get_the_tag_list( '', __( ', ', 'classroom' ) );
	if ( $tags_list ) {
		printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'classroom' ) . '</span>', $tags_list );
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'classroom' ), __( '1 Comment', 'classroom' ), __( '% Comments', 'classroom' ) );
		echo '</span>';
	}


}
/**
* Generates a list of posts "due today" based on Due Date custom field
* @return HTML list output
*/
function classroom_due_today(){
// $today = date('Ymd'); 
	$today = current_time( 'Ymd' , 0);

	$args = array (
		'post_type' => array('post','page'),
		'ignore_sticky_posts' => 1,
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
	if($q->have_posts()){
		?>
		<section class="widget">
			<div class="widget-content">
				<h2 class="widget-title">Upcoming Deadlines:</h2>

				<ul>
					<?php
					while($q->have_posts()){
						$q->the_post();
						?>
						<li>
							<a href="<?php the_permalink(); ?>">
								<?php classroom_show_duedate(''); ?>:
								<?php the_title(); ?>
							</a>
						</li>
						<?php
					}
					?></ul>
				</div>
			</section>
			<?php
		}
	}

/**
* Displays the content from the 'important' custom field. use within the loop
* @return string  HTML output
*/
function classroom_important_text(){
	global $post;
	$content  = get_post_meta($post->ID, 'important', true );
	if($content){
		?> 
		<section class="important-content">
			<?php echo $content; ?>
		</section>
		<?php 
	}else{
		return false;
	}
}
/**
* Display file attachments for this post. use within the loop
* @return string HTML link to file
*/
function classroom_file_attachments(){

	global $post;

//list of recognized dashicons => mime types. 
	$mimes = array(
		'application/x-photoshop'	=>	'media-format-image' ,
		'application/zip'			=> 'media-archive',
		'application/vnd.ms-excel'	=> 'media-spreadsheet',
		'application/vnd.ms-powerpoint'	=> 'media-interactive'	,
		'application/msword'			=> 'media-document',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document'			=> 'media-document',	
		'application/pdf'				=> 'media-document' ,					
		'text/plain'				=> 'media-text' ,
		'application/x-javascript' 	=> 'media-code',
		'audio/mpeg'				=>'media-audio',
		);

	$attachment_ids = array();


//add any attachments from the media uploader
	foreach($mimes as $mime => $icon){
		if($attachments = get_children(array(   
			'post_parent' => $post->ID,
			'post_type' => 'attachment',
			'numberposts' => -1,
'post_mime_type' => $mime,  //MIME Type condition
))){
			foreach( $attachments as $attachment ){
				$attachment_ids[] = $attachment->ID;

			}
		}
	}


//get the attachment from the custom field
	$manual_att   = get_post_meta($post->ID, 'file_attachment', true );
	if($manual_att)
		$attachment_ids[] = $manual_att;

//then, check for non blank array
	if(!empty($attachment_ids) ):
		?>
	<section class="post-attachments">
		<h2>Files to Download:</h2>
		<ul>
			<?php
			foreach($attachment_ids as $file){
				$url = wp_get_attachment_url( $file );
				$title = get_the_title( $file );
				$filetype = wp_check_filetype($url);
				$extension =  $filetype['ext']; 
				$mime = get_post_mime_type( $file );
				$icon = $mimes[$mime];


				?>
				<li><a href="<?php echo $url; ?>"><span class="dashicons dashicons-<?php echo $icon; ?>"></span><?php echo $title; ?> (<?php echo $extension; ?>)</a></li>

				<?php
			}
			?>
		</ul>
	</section>
	<?php 
	endif;

}
function classroom_related_reading(){
	global $post;
	$related = get_post_meta($post->ID, 'related_reading', true );
	if($related){
		?>
		<section class="related-reading">
			<h2>Related Reading:</h2>

			<?php
			echo wpautop($related);
		} ?>

	</section>
	<?php
}

if ( ! function_exists( 'the_archive_title' ) ) :
/**
* Shim for `the_archive_title()`.
*
* Display the archive title based on the queried object.
*
* @todo Remove this function when WordPress 4.3 is released.
*
* @param string $before Optional. Content to prepend to the title. Default empty.
* @param string $after  Optional. Content to append to the title. Default empty.
*/
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( __( 'Category: %s', 'classroom' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s', 'classroom' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'classroom' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'classroom' ), get_the_date( _x( 'Y', 'yearly archives date format', 'classroom' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'classroom' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'classroom' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'classroom' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'classroom' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title', 'classroom' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title', 'classroom' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title', 'classroom' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title', 'classroom' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title', 'classroom' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title', 'classroom' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title', 'classroom' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title', 'classroom' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title', 'classroom' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'classroom' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'classroom' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'classroom' );
	}

	/**
	* Filter the archive title.
	*
	* @param string $title Archive title to be displayed.
	*/
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;



/**
* Returns true if a blog has more than 1 category.
*
* @return bool
*/
function classroom_theme_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'classroom_theme_categories' ) ) ) {
	// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

	// We only need to know if there is more than one category.
			'number'     => 2,
			) );

	// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'classroom_theme_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
	// This blog has more than 1 category so classroom_theme_categorized_blog should return true.
		return true;
	} else {
	// This blog has only 1 category so classroom_theme_categorized_blog should return false.
		return false;
	}
}

/**
* Flush out the transients used in classroom_theme_categorized_blog.
*/
function classroom_theme_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'classroom_theme_categories' );
}
add_action( 'edit_category', 'classroom_theme_category_transient_flusher' );
add_action( 'save_post',     'classroom_theme_category_transient_flusher' );

/**
* Check to see if the current post has due date or related reading or attached files
* @return boolean whether or not the post has files, related reading, or a due date
*/
function has_classroom_meta(){
	global $post;
	$id = $post->ID;
	if(get_post_meta( $id, 'due_date', true ) OR get_post_meta( $id, 'file_attachment', true ) OR get_post_meta( $id, 'related_reading', true )){
		return true;
	}else{
		return false;
	}

}
/**
* Template Tags
*/
function classroom_thumbnail_title(){
	global $post;
	$tag = 'h2';
	if(is_singular()){
		$tag = 'h1';
	}
	if(has_post_thumbnail( )){ 
		$img = get_the_post_thumbnail_url($post->ID, 'medium'); ?>

		<div class="poster-image" style="background-image:url(<?php echo $img; ?>)">
			<<?php echo $tag ?> class="entry-title"><?php the_title() ?></<?php echo $tag ?>>
		</div>
		<?php 
	} else{ 
		?>
		<<?php echo $tag ?> class="entry-title"><?php the_title() ?></<?php echo $tag ?>>
		<?php 
	}
}
function classroom_post_meta(){
	global $post;
	?>
	<div class="entry-meta">
		<span class="date-posted">Posted: <?php the_time( 'F j' ); ?></span>
		<span class="category"><?php the_terms( $post->ID, 'category' ); ?></span>			
		<?php classroom_show_duedate() ?>
	</div>
	<?php
}

function classroom_due_date_box(){
	global $post;
	$date = classroom_get_duedate();
	if($date){ 
		?>
		<section class="widget widget-due-date">
			<h2 class="widgettitle">Due Date:</h2>
			<p><?php classroom_show_duedate(); ?></p>
		</section>
		<?php 
	}
}
function classroom_important_info_box(){
	global $post;
	$important = get_post_meta( $post->ID, 'important', true );
	if($important){ 
		?>
		<section class="widget widget-important">
			<h2 class="widgettitle">Important:</h2>
			<p><?php echo $important; ?></p>
		</section>
		<?php 
	}
}
function classroom_file_attachment_box(){
	global $post;
	$file_attachments = get_post_meta( $post->ID, 'file_attachment', false );

	if(!empty($file_attachments[0])){ 		
		?>
		<section class="widget widget-file-attachment">
			<h2 class="widgettitle">File Downloads:</h2>
			<ul>
				<?php 
				foreach($file_attachments as $id){  
					$att = classroom_get_attachment($id);
					?>
					<li>
						<a href="<?php echo wp_get_attachment_url($id) ?>">
							<?php  echo $att['title']; ?>
						</a>
					</li>
					<?php 
				} //end foreach 
				?>
			</ul>
		</section>
		<?php 
	}
}
function classroom_related_reading_box(){
	global $post;
	$related_reading = get_post_meta( $post->ID, 'related_reading', true );
	if($related_reading){ 
		?>
		<section class="widget widget-related_reading">
			<h2 class="widgettitle">Related Reading:</h2>
			<p><?php echo $related_reading; ?></p>
		</section>
		<?php 
	}
}


/*Attachment helper function*/
function classroom_get_attachment( $attachment_id ) {
	$attachment = get_post( $attachment_id );
	return array(
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => get_permalink( $attachment->ID ),
		'src' => $attachment->guid,
		'title' => $attachment->post_title
		);
}