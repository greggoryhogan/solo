<?php
if (!defined('ABSPATH')) exit;

register_nav_menu( 'main-menu',		__( 'Main Menu', 'solo' ) );

add_action('admin_init', 'remove_wp_block_menu', 100);
function remove_wp_block_menu() {
    remove_submenu_page( 'themes.php', 'site-editor.php?p=/pattern' );
    remove_submenu_page( 'themes.php', 'font-library.php' );
}

function solodryspa_disable_comment_support() {
    foreach (get_post_types(array(), 'names') as $post_type) {
        remove_post_type_support($post_type, 'comments');
        remove_post_type_support($post_type, 'trackbacks');
    }
}
add_action('init', 'solodryspa_disable_comment_support', 100);

add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

function solodryspa_hide_existing_comments($comments) {
    return array();
}
add_filter('comments_array', 'solodryspa_hide_existing_comments');

function solodryspa_remove_comment_admin_pages() {
    global $pagenow;

    if ($pagenow === 'edit-comments.php' || $pagenow === 'comment.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
}
add_action('admin_init', 'solodryspa_remove_comment_admin_pages');

function solodryspa_remove_post_admin_pages() {
    global $pagenow;

    $is_post_list = $pagenow === 'edit.php'
        && (!isset($_GET['post_type']) || $_GET['post_type'] === 'post');
    $is_post_creation = $pagenow === 'post-new.php'
        && (!isset($_GET['post_type']) || $_GET['post_type'] === 'post');
    $is_post_edit = $pagenow === 'post.php'
        && isset($_GET['post'])
        && get_post_type(absint($_GET['post'])) === 'post';

    if ($is_post_list || $is_post_creation || $is_post_edit) {
        wp_safe_redirect(admin_url());
        exit;
    }
}
add_action('admin_init', 'solodryspa_remove_post_admin_pages');

function solodryspa_remove_comment_and_post_admin_menus() {
    remove_menu_page('edit-comments.php');
    remove_menu_page('edit.php');
}
add_action('admin_menu', 'solodryspa_remove_comment_and_post_admin_menus', 999);

function solodryspa_remove_comment_and_post_dashboard_widgets() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'solodryspa_remove_comment_and_post_dashboard_widgets');

function solodryspa_remove_post_admin_bar_menu($admin_bar) {
    $admin_bar->remove_node('new-post');
}
add_action('admin_bar_menu', 'solodryspa_remove_post_admin_bar_menu', 999);