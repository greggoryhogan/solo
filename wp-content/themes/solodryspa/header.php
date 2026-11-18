<!DOCTYPE html>
<?php if (!defined('ABSPATH')) exit; ?>
<html <?php language_attributes(); ?>>
<head>
<?php wp_head();?>
</head>
<body <?php body_class(); ?>>
<header id="solo-header">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <a href="<?php echo get_bloginfo('url'); ?>" title="Home" class="logo">
                    <h1>
                    <span>SOLO Dry Spa</span>
                    <img src="<?php echo THEME_URI; ?>assets/img/logo.png" alt="SOLO Dry Spa Logo Image" width="273" height="83" />
                    </h1>
                </a>
            </div>
            <div class="col-md-8 font-fahkwang solo-nav-container">
                <a href="<?php echo SOLO_BOOKING_LINK; ?>" class="solo-btn nav-item" target="_blank" title="Book your experience">Book Your Experience</a>
                <button type="button" class="solo-menu-toggle" aria-label="Open menu" aria-haspopup="dialog" aria-controls="solo-menu-drawer" aria-expanded="false" hidden>
                    <span class="solo-menu-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
    <div class="bg"></div>
</header>
<nav id="solo-primary-nav" class="solo-primary-nav font-fahkwang" aria-label="Primary navigation">
    <?php solo_wp_nav_menu( 'main-menu', array( 'container' => false, 'menu_class' => 'menu', 'fallback_cb' => 'wp_page_menu' ) ); ?>
</nav>
<dialog id="solo-menu-drawer" class="solo-menu-drawer font-fahkwang" aria-labelledby="solo-menu-title">
    <div class="solo-menu-heading">
        <a href="<?php echo get_bloginfo('url'); ?>" title="Home" class="logo">
            <img src="<?php echo THEME_URI; ?>assets/img/logo.png" alt="SOLO Dry Spa Logo Image" width="273" height="83" />
        </a>
        <form method="dialog">
            <button type="submit" class="solo-menu-toggle" aria-label="Close menu" autofocus>
                <span class="solo-menu-close-icon" aria-hidden="true"></span>
            </button>
        </form>
    </div>
</dialog>
<main role="main" id="Main">
