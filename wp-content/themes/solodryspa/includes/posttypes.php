<?php
add_action( 'init', 'solo_register_cpt_and_taxonomies' );
function solo_register_cpt_and_taxonomies() {

	/**
	 * Post Type: Therapy
	 */
    $therapy_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#a7aaad"><path d="M12 16.114c-3.998-5.951-8.574-7.043-8.78-7.09L2 8.75V10c0 7.29 3.925 12 10 12 5.981 0 10-4.822 10-12V8.75l-1.22.274c-.206.047-4.782 1.139-8.78 7.09z"/><path d="M11.274 3.767c-1.799 1.898-2.84 3.775-3.443 5.295 1.329.784 2.781 1.943 4.159 3.685 1.364-1.76 2.826-2.925 4.17-3.709-.605-1.515-1.646-3.383-3.435-5.271L12 3l-.726.767z"/></svg>';

    
	$labels = array(
		"name" => __( "Therapies", "" ),
		"singular_name" => __( "Therapy", "solo" ),
		'all_items' => __( "Therapies", "solo" ),
		'edit_item' => __( "Edit Therapy", "solo" ),
		'update_item' => __( "Update Therapy", "solo" ),
		'add_new' => __( "Add New Therapy", "solo" ),
		'add_new_item' => __( "Add New Therapy", "solo" ),
		'new_item_name' => __( "New Therapy", "solo" ),
		'menu_name' => __( "Therapies", "solo" ),
		'back_to_items' => __( "&laquo; All Therapies", "solo" ),
		'not_found' => __( "No therapies found.", "solo" ),
		'not_found_in_trash' => __( "No therapies found in trash.", "solo" ),
	);

	$args = array(
		"label" => __( "Therapies", "" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		'show_in_rest' => false,
		"exclude_from_search" => false,
		"capability_type" => "page",
		"map_meta_cap" => true,
		"rewrite" => array( "slug" => "therapy", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title", "custom-fields" ),
		"taxonomies" => array(),
		'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode($therapy_svg)
	);

    register_post_type( "therapy", $args );

}