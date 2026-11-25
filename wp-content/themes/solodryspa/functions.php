<?php 
define('SOLO_URL',get_bloginfo('url'));
define( 'THEME_DIR', trailingslashit(get_template_directory()) );
define( 'THEME_URI', trailingslashit(get_template_directory_uri()) );
define( 'LIBS_DIR', THEME_DIR. 'includes/' );
define( 'SOLO_BOOKING_LINK', 'https://book.squareup.com/appointments/xsahyv5nwb2ygf/location/LTRPBSTF8SF7A/services' );
require( LIBS_DIR .'admin.php' );
require( LIBS_DIR .'posttypes.php' );
require( LIBS_DIR .'acf-config.php' );
require( LIBS_DIR .'frontend.php' );
require( LIBS_DIR .'square.php' );