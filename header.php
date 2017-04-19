<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<a class="jump-to-content screen-reader-text" href="#content">Jump to Content</a>
	
	<header class="site-header container-fluid fill">
		<div class="row">
			<div class="site-logo-container column xs-20 md-25 lg-20">
			<?php the_custom_logo(); ?>
				
			</div>
			<div class="site-info column xs-80 md-75 lg-80">
				<h1 class="site-name">
					<a href="<?php echo home_url('/'); ?>">
						<?php bloginfo( 'name' ); ?>				
					</a>
				</h1>

				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div>
		</div>
	</header>

	<div class="container-fluid fill">
		<div class="row">
			<nav class="main-navigation column xs-100 md-25 lg-20">
				<?php 
				wp_nav_menu(array(
					'theme_location' => 'main_menu',
					'container' => false,
					'fallback_cb' => '',
					)); 
					?>
				</nav>


