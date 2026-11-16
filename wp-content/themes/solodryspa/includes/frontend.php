<?php 
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