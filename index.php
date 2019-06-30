<?php get_header();

/* Template name: Home */
?>

	<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
		<div class="wrapper">

			<p>page content</p>

		</div> <!-- /wrapper -->
	<?php endwhile; endif; ?>

<?php get_footer(); ?>