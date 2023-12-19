<?php get_header() ?>
    <main>
        <?php
            $args = [
                'post_type' => 'product'
            ];
            $list = new WP_Query($args);		
            $posts = $list->get_posts();
            foreach( $posts as $post ){
                $price = get_post_meta($post->ID, '_regular_price');
                $date = get_post_meta($post->ID, '_custom_product_date_field');
                $select = get_post_meta($post->ID, 'custom_product_select_field');
                $link = get_post_permalink();
                $symbol = $price[0] ? '$' : '';
            ?>
                <div class="product">
                    <?php echo get_the_post_thumbnail( $post );?>
                    <h4><?php the_title(); ?></h4>
                    <p><?php the_content()?></p>
                    <p><?php echo $price[0] . $symbol; ?></p>
                    <p><?php echo $date[0]; ?></p>
                    <p><?php echo $select[0]; ?></p>
                    <a class="link" href="<?php echo $link?>">OPEN PRODUCT</a>
                </div>
            <?php
            }
         ?>
    </main>
<?php get_footer() ?>
