<?php
if (!defined('ABSPATH')) exit; 
get_header();
if ( post_password_required( $post )) {
    echo '<section class="container ">';
        echo get_the_password_form();
    echo '</section>';
} else {
    echo '<section class="container solo-section">';
        echo solo_page_title();
    echo '</section>';
    echo '<section class="mailing-list solo-section">';
        //mailing list
        $background = get_field('mailing_list_background');
        if($background != '') {
            echo '<div class="solo-img-bg">';
                echo wp_get_attachment_image($background, 'full', false, array( 'loading' => 'lazy' ) );
            echo '</div>';
        }
        echo '<div class="container">';
            echo '<h2 class="mailing-list-heading">'.get_field('mailing_list_heading').'</h2>';
            echo '<div class="mailing-list-content">'.get_field('mailing_list_content').'</div>';
            solo_mailchimp_signup(get_field('mailing_list_button_text'), get_field('mailing_list_confirmation'));
        echo '</div>';
    echo '</section>';
    
    $therapies = get_posts( array('post_type' => 'therapy', 'numberposts' => -1) );
    if(!empty($therapies)) {
        echo '<section class="container therapy-container solo-section">';
            //four therapies
            echo '<h2>'.get_field('therapy_heading').'</h2>';
            echo '<div>'.get_field('therapy_content').'</div>';
            echo '<div class="row therapies solo-section-content">';
                foreach ($therapies as $therapy) {
                    $description = get_field('description', $therapy);
                    $preview_image = get_field('preview_image', $therapy);
                    $title = get_the_title($therapy);
                    echo '<div class="col-6 col-lg-3 maintain-mobile-grid therapy-column">';
                        echo '<a href="'.get_permalink($therapy).'" class="therapy">';
                            echo '<div class="feature">';
                                echo wp_get_attachment_image( $preview_image, 'large', false, array( 'loading' => 'lazy' ) );
                                echo '<h3 class="h6 font-fahkwang">'.$title.'</h3>';
                            echo '</div>';
                            echo '<div class="description">'.$description.'</div>';
                            echo '<div class="learn-more">Learn more&nbsp;&raquo;</div>';
                        echo '</a>';
                    echo '</div>';
                }
            echo '</div>';
        echo '</section>';
    }
    
    echo '<section class="solo-section technologies">';
        //our tech
        echo '<div class="container">';
                echo '<h2>'.get_field('tech_heading').'</h2>';
                echo '<div>'.get_field('tech_content').'</div>';
            echo '</div>';
        echo '<div class="solo-section-content">';
            $background = get_field('tech_background');
            if($background != '') {
                echo '<div class="solo-img-bg">';
                    echo wp_get_attachment_image( $background, 'full', false, array( 'loading' => 'lazy' ) );
                echo '</div>';
            }
            if(have_rows('technologies')) {
                echo '<div class="container technologies-container">';
                    echo '<ul>';
                    while(have_rows('technologies')) {
                        the_row();
                        $link = get_sub_field('technology');
                        if( $link ): 
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            ?>
                            <li><a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a></li>
                        <?php endif;
                    }
                    echo '</ul>';
                echo '</div>';
            }
            
        echo '</div>';
    echo '</section>';
}
get_footer(); ?>
