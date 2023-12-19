<?php get_header() ?>
    <div>
		<?php while (have_posts()) : the_post(); ?>
			<?php wc_get_template_part('content', 'single-product'); ?>	
			<p>Date created: <?php echo get_post_meta($post->ID, '_custom_product_date_field', true); ?></p>
			<p>Product type: <?php echo get_post_meta($post->ID, 'custom_product_select_field', true); ?></p>
		<?php endwhile; // end of the loop. ?>
	</div>
<?php get_footer() ?>