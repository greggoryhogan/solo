<?php
if (!defined('ABSPATH')) exit; 
get_header();
if ( post_password_required( $post )) {
    echo '<section class="container">';
        echo get_the_password_form();
    echo '</section>';
} else {
    echo '<section class="container solo-section">';
        echo solo_page_title();
    echo '</section>';
    if ( ! post_password_required( $post )) {
        while ( have_posts() ){
            the_post();
            //the_content();
            solo_page_content();
        }
    } 
}
get_footer(); ?>