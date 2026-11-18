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
    
    therapies_list();
    
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
