<!DOCTYPE html>
<html>
	<head>
		<?php wp_head() ?>
	</head>
    <body <?php body_class(); ?>>
		<header>
            <div class="flex-nav">
                <?php 
                    wp_nav_menu( [
                        'theme_location' => 'header-menu'
                    ]); 
                ?>
            </div>
        </header>