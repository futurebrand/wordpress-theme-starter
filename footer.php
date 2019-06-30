
		<?php include(locate_template('components/footer.php')); ?>

	</main>

	<?php if($_SERVER['HTTP_HOST'] == 'localhost') { ?>
		<script src="http://localhost:35729/livereload.js"></script>
	<?php } ?>

	<?php wp_footer(); ?>

</body>
</html>