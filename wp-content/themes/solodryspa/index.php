<?php
if (!defined('ABSPATH')) exit; 
get_header();
?>
<section class="container">
    <?php echo solo_page_title(); ?>
	<?php 
    if ( ! post_password_required( $post )) {
        while ( have_posts() ){
            the_post();							// Post Loop	
            the_content();	// Content Builder & WordPress Editor Content
        }
    } else {
        echo get_the_password_form();
    }
    ?>
</section>
<?php get_footer(); ?>