<?php get_header();

/* Template name: Single Post */
?>

	<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
		<div class="wrapper">

			<p>post page</p>

		</div> <!-- /wrapper -->
	<?php endwhile; endif; ?>

<?php get_footer(); ?>