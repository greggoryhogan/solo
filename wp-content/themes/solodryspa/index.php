<?php
if (!defined('ABSPATH')) exit; 
get_header();
if ( post_password_required( $post )) {
    echo '<section class="container">';
        echo get_the_password_form();
    echo '</section>';
} else {
    echo '<section class="container">';
        echo solo_page_title();
    echo '</section>';
    echo '<section class="container">';
    if ( ! post_password_required( $post )) {
        while ( have_posts() ){
            the_post();							// Post Loop	
            the_content();	// Content Builder & WordPress Editor Content
        }
    } 
    echo '</section>';
}
get_footer(); ?>