<?php 
add_action( 'after_setup_theme', 'solo_theme_setup' );
function solo_theme_setup() {
    //add_theme_support( 'post-thumbnails' );
	add_theme_support('title-tag');
}

add_filter( 'image_editor_output_format', 'map_jpeg_to_webp' );
function map_jpeg_to_webp( $mappings ) {
	$mappings[ 'image/jpeg' ] = 'image/webp';
    $mappings[ 'image/png' ] = 'image/webp';
	return $mappings;
};

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
	//wp_enqueue_script( 'solo', $dir.'/js/site.min.js', array(), $version, true );
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
	//viewport
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';

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
	$images = get_field('images', $post);
	if(!is_array($images)) {
		$images = array();
	}
	$image_output = '';
	if(!empty($images)) {
		$image_output .= '<div class="image-container';
		if(count($images) > 1) {
			$image_output .= ' gallery';
		} else {
			$image_output .= ' single';
			if(!have_rows('faqs', $post)) {
				$image_output .= ' not-adaptive';
			}
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
					$benefits_heading = get_field('benefits_heading');
					if($benefits_heading != '') {
						$title .= '<div class="benefits-heading">'.$benefits_heading.'</div>';
					}
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
				if($booking_link == 'custom') {
					$link = get_field('button');
					$link_url = $link['url'];
					$link_title = $link['title'];
					$link_target = $link['target'] ? $link['target'] : '_self';
					$title .= '<a href="'.$link_url.'" class="solo-btn '.$button_alignment.'" target="'.$link_target.'" title="'.$link_title.'">'.$link_title.'</a>';
				} else {
					$title .= '<a href="'.SOLO_BOOKING_LINK.'" class="solo-btn '.$button_alignment.'" target="_blank" title="Book your experience">Book Your Experience</a>';
				}
			}
		$title .= '</div>';

		$title .= '<div class="desktop-gallery desktop-only">'.$image_output.'</div>';
	
	$title .= '</div>';
	return $title;
}

function solo_mailchimp_waitlist_submit() {
	if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
		wp_send_json_error( null, 405 );
	}

	if ( ! defined( 'SOLO_MAILCHIMP_API_KEY' ) || ! SOLO_MAILCHIMP_API_KEY ) {
		wp_send_json_error( null, 503 );
	}

	if ( ! empty( $_POST['b_3e5a9856682ea90ff75eeba0c_14aac10992'] ) ) {
		wp_send_json_success( array( 'pending' => true ) );
	}

	$email = isset( $_POST['EMAIL'] ) && is_string( $_POST['EMAIL'] )
		? trim( wp_unslash( $_POST['EMAIL'] ) )
		: '';
	if ( ! is_email( $email ) ) {
		wp_send_json_error( 'Enter a valid email address.', 400 );
	}

	$rate_key = 'solo_mc_' . hash_hmac( 'sha256', $_SERVER['REMOTE_ADDR'] ?? '', wp_salt() );
	$attempts = (int) get_transient( $rate_key );
	if ( $attempts >= 10 ) {
		wp_send_json_error( 'Too many signup attempts. Please try again in a minute.', 429 );
	}
	set_transient( $rate_key, $attempts + 1, MINUTE_IN_SECONDS );

	$member = md5( strtolower( $email ) );
	$result = wp_remote_request( "https://us20.api.mailchimp.com/3.0/lists/14aac10992/members/$member", array(
		'method'      => 'PUT',
		'timeout'     => 8,
		'redirection' => 0,
		'headers'     => array(
			'Authorization' => 'Bearer ' . SOLO_MAILCHIMP_API_KEY,
			'Content-Type'  => 'application/json',
		),
		'body'        => wp_json_encode( array(
			'email_address' => $email,
			'status_if_new' => 'subscribed',
		) ),
	) );

	if ( ! is_wp_error( $result ) && 200 === wp_remote_retrieve_response_code( $result ) ) {
		$data = json_decode( wp_remote_retrieve_body( $result ), true );
		$status = $data['status'] ?? '';
		if ( in_array( $status, array( 'pending', 'subscribed' ), true ) ) {
			wp_send_json_success( array( 'pending' => 'pending' === $status ) );
		}
	}

	wp_send_json_error( null, 502 );
}
add_action( 'wp_ajax_solo_mailchimp_waitlist_submit', 'solo_mailchimp_waitlist_submit' );
add_action( 'wp_ajax_nopriv_solo_mailchimp_waitlist_submit', 'solo_mailchimp_waitlist_submit' );

function solo_mailchimp_signup($button_text = 'Join the Waitlist', $confirmation = 'Thank you for subscribing to our waitlist!') {
	if ( ! is_string( $confirmation ) || '' === trim( $confirmation ) ) {
		$confirmation = 'Thank you for subscribing to our waitlist!';
	}

	?>
	<div id="mc_embed_signup" class="mc-signup">
		<form id="mc-embedded-subscribe-form" class="mc_container" method="post"
			action="https://solodryspa.us20.list-manage.com/subscribe/post?u=3e5a9856682ea90ff75eeba0c&amp;id=14aac10992&amp;f_id=00ce79e0f0">
		
			<input
				type="email"
				name="EMAIL"
				id="mce-EMAIL"
				placeholder="Your email"
				required
				class=""
			>
			<!-- Mailchimp honeypot - leave this -->
			<div style="position:absolute;left:-5000px;" aria-hidden="true">
				<input
					type="text"
					name="b_3e5a9856682ea90ff75eeba0c_14aac10992"
					tabindex="-1"
					value=""
				>
			</div>

			<button type="submit" id="mc-embedded-subscribe"><?php echo esc_html( $button_text ); ?></button>

			<div id="mce-response" role="status"></div>
		</form>
	</div>
	<?php if ( defined( 'SOLO_MAILCHIMP_API_KEY' ) && SOLO_MAILCHIMP_API_KEY ) : ?>
	<script>
		document.getElementById('mc-embedded-subscribe-form').addEventListener('submit', async function(e) {
			const form = this;
			if (form.dataset.useMailchimpForm) {
				return;
			}

			e.preventDefault();

			const response = form.querySelector('#mce-response');
			const button = form.querySelector('#mc-embedded-subscribe');
			const body = new URLSearchParams(new FormData(form));
			body.set('action', 'solo_mailchimp_waitlist_submit');
			response.textContent = '';
			button.disabled = true;

			try {
				const request = await fetch(<?php echo wp_json_encode( wp_make_link_relative( admin_url( 'admin-ajax.php' ) ), JSON_HEX_TAG ); ?>, {
					method: 'POST',
					body
				});
				const result = await request.json();

				if (result.success) {
					response.textContent = result.data.pending
						? 'Check your email to confirm your waitlist signup.'
						: <?php echo wp_json_encode( $confirmation, JSON_HEX_TAG ); ?>;
					form.reset();
				} else if (request.status === 400 || request.status === 429) {
					response.textContent = result.data;
				} else {
					throw new Error('Signup unavailable');
				}
			} catch {
				form.dataset.useMailchimpForm = 'true';
				response.textContent = 'You can continue on Mailchimp with the email you entered.';
				button.textContent = 'Continue on Mailchimp';
			} finally {
				button.disabled = false;
			}
		});
	</script>
	<?php endif; ?>
	<?php
}

function solo_page_content() {
	if(have_rows('page_content')) {
		while(have_rows('page_content')) {
			the_row();
			$heading = get_sub_field('heading');
			$content = get_sub_field('content');
			$link = get_sub_field('cta');
			$image = get_sub_field('image');
			$image_position = get_sub_field('position');
			if($image != '' && $image_position == 'background') {
				echo '<section class="solo-section margin-sm has-bg-img">';
					echo '<div class="solo-img-bg">';
						echo wp_get_attachment_image($image, 'full', false, array( 'loading' => 'lazy' ) );
					echo '</div>';
					echo '<div class="container">';
						if($heading != '') {
							echo '<h2 class="">'.$heading.'</h2>';
						}
						if($content != '') {
							echo '<div class="">'.$content.'</div>';
						}
						if($link) {
							$link_url = $link['url'];
							$link_title = $link['title'];
							$link_target = $link['target'] ? $link['target'] : '_self';
							echo '<a href="'.$link_url.'" class="solo-btn center" target="'.$link_target.'" title="'.$link_title.'">'.$link_title.'</a>';
						}
					echo '</div>';
				echo '</section>';
			} else {
				echo '<section class="solo-section margin-sm">';
					echo '<div class="container page-container">';
						if($heading != '') {
							echo '<h2 class="">'.$heading.'</h2>';
						}
						echo '<div class="solo-section-content image-'.$image_position.'">';
							echo '<div class="content">';
								if($content != '') {
									echo '<div>'.$content.'</div>';
								}
								if($link) {
									$link_url = $link['url'];
									$link_title = $link['title'];
									$link_target = $link['target'] ? $link['target'] : '_self';
									echo '<a href="'.$link_url.'" class="solo-btn start" target="'.$link_target.'" title="'.$link_title.'">'.$link_title.'</a>';
								}
							echo '</div>';
							if($image != '') {
								echo '<div class="solo-image">';
									echo wp_get_attachment_image($image, 'full', false, array( 'loading' => 'lazy' ) );
								echo '</div>';
							}
						echo '</div>';
					echo '</div>';
				echo '</section>';
			}
		}
	}
	if(get_post_type() == 'therapy') {
		echo '<section class="solo-section therapies-gap"></section>';
		therapies_list();
	}
}

function therapies_list() {
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
}
