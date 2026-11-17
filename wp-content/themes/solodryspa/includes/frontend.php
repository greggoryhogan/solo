<?php 
//temp function to remove posts since we hid the posts section
add_action('init','solo_delete_posts');
function solo_delete_posts() {
	if(!isset($_GET['solo-delete-posts'])) {
		return;
	}
	$allposts= get_posts( array('post_type'=>'post','numberposts'=>-1) );
	foreach ($allposts as $eachpost) {
		wp_delete_post( $eachpost->ID, true );
	}
}
add_action( 'after_setup_theme', 'solo_theme_setup' );
function solo_theme_setup() {
    //add_theme_support( 'post-thumbnails' );
	add_theme_support('title-tag');
}

// Disable Gutenberg on the back end.
add_filter( 'use_block_editor_for_post', '__return_false' );

// Disable Gutenberg for widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

add_action( 'wp_enqueue_scripts', function() {
    // Remove CSS on the front end.
    wp_dequeue_style( 'wp-block-library' );

    // Remove Gutenberg theme.
    wp_dequeue_style( 'wp-block-library-theme' );

    // Remove inline global CSS on the front end.
    wp_dequeue_style( 'global-styles' );

    // Remove classic-themes CSS for backwards compatibility for button blocks.
    wp_dequeue_style( 'classic-theme-styles' );
}, 20 );

add_action('wp_enqueue_scripts','solo_scripts');
function solo_scripts() {
	$version = wp_get_theme()->get('Version');
	$dir = SOLO_URL.'/wp-content/themes/solodryspa/assets';
	wp_enqueue_style( 'solo-fonts', 'https://use.typekit.net/pzx0ris.css', false, $version, 'all' );
	wp_enqueue_style( 'solo', $dir.'/css/main.css', false, $version, 'all' );
}

add_action('wp_head', 'solo_head');
function solo_head() {
	$preconnect_domains = array(
		'https://use.typekit.net',
	);
	foreach($preconnect_domains as $domain) {
		echo '<link rel="preconnect" href="'.$domain.'" crossorigin>';
	}

	//fav
	echo '<link media="(prefers-color-scheme: light)" href="'.THEME_URI . 'assets/img/fav-light.png" rel="icon" type="image/x-icon" />';
  	echo '<link media="(prefers-color-scheme: dark)" href="'.THEME_URI . 'assets/img/fav-dark.png" rel="icon" type="image/x-icon" />';
}

function solo_wp_nav_menu( $location = 'main-menu', $args = array() ) {	
	$defaults = array( 
		'container' 		=> 'false',
		'container_id'		=> 'menu', 
		'menu_class'		=> 'menu container-lg mb-0', 
		'fallback_cb'		=> 'mfn_wp_page_menu', 
		'theme_location'	=> $location,
		'depth' 			=> 4,
		'echo' 				=> false,
	);
	$args = array_merge($defaults, $args);
	$menu = wp_nav_menu( $args ); 
		
	echo $menu;
}

function solo_page_title() {
	global $post;
	if(is_home()) {
		$h1 = 'h2';
		$h2 = 'h3';
	} else {
		$h1 = 'h1';
		$h2 = 'h2';
	}
	$images = get_field('images', $post) ?? array();
	$image_output = '';
	if(!empty($images)) {
		$image_output .= '<div class="image-container';
		if(count($images) > 1) {
			$image_output .= ' gallery';
		} else {
			$image_output .= ' single';
		}
		$image_output .= '">';
			foreach($images as $image) {
				$image_id = $image['image'];
				$image_output .=  wp_get_attachment_image($image_id, 'large');
			}
		$image_output .= '</div>';
	}
	$title = '<div class="solo-title-container';
	if(count($images) == 1) {
		$title .= ' no-gallery';
	}
	$title .= '">';
	
		$title .= '<div class="solo-title-content">';
			
			$title .= '<'.$h1.' class="h1">'.get_the_title().'</'.$h1.'>';
			if(function_exists('get_field')) {
				$subtitle = get_field('subtitle', $post);
				if($subtitle != '') {
					$title .= '<'.$h2.' class="h3 subtitle">'.$subtitle.'</'.$h1.'>';
				}
			}

			$title .= '<div class="mobile-gallery mobile-only">'.$image_output.'</div>';

			$benefits = get_field('benefits', $post);
			if($benefits != '') {
				$title .= '<div class="benefits">';
					$title .= '<div class="benefits-heading">Benefits:</div>';
					$title .= '<ul><li>'.implode('</li><li>', explode('<br />',$benefits)).'</li></ul>';
				$title .= '</div>';
			}
			$button_alignment = 'start';
			if(have_rows('faqs', $post)) {
				$button_alignment = 'center';
				$title .= '<div class="solo-accordion">';
				while(have_rows('faqs', $post)) {
					the_row();
					$title .= '<details name="faq">';
					$title .= '<summary>'.get_sub_field('question').'</summary>';
					$title .= '<div class="answer">'.get_sub_field('answer').'</div>';
					$title .= '</details>';
				}
				$title .= '</div>';
			}

			$booking_link = get_field('booking_link', $post);
			if($booking_link !== 'hide') {
				$title .= '<a href="'.SOLO_BOOKING_LINK.'" class="booking-link '.$button_alignment.'" target="_blank" title="Book your experience">Book Your Experience</a>';
			}
		$title .= '</div>';

		$title .= '<div class="desktop-gallery desktop-only">'.$image_output.'</div>';
	
	$title .= '</div>';
	return $title;
}