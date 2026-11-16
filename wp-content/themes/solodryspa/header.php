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
            <div class="col-md-6">
                <a href="https://solodryspa.com/" title="Home" class="logo">
                    <h1>
                    <span>SOLO Dry Spa</span>
                    <img src="<?php echo THEME_URI; ?>assets/img/logo.png" alt="SOLO Dry Spa Logo Image" width="273" height="83" class="solo-animate slide-in-bottom is-inactive" />
                    </h1>
                </a>
            </div>
            <div class="col-md-6 font-fahkwang">
                <nav>
                    <?php solo_wp_nav_menu(); ?>
                </nav>
            </div>
        </div>
    </div>
    <div class="bg"></div>
</header>
<main role="main" id="Main">