<?php
class ISPostType{
	function register_slider_post_type() {
		$labels = array(
			'name' => _x('InterSlider','inter_slider' ),
			'singular_name' => _x('InterSlider','inter_slider'),
			'add_new' => _x('Add new slide','inter_slider'),
			'add_new_item' => _x('Add new slide','inter_slider'),
			'edit_item' => _x('Edit slide','inter_slider'),
			'new_item' => _x('New slide','inter_slider'),
			'view_item' => _x('View slide','inter_slider'),
			'search_items' => _x('Search slides','inter_slider'),
			'not_found' => _x('No slides found','inter_slider'),
			'not_found_in_trash' => _x('No slides found in Trash','inter_slider'),
			'parent_item_colon' => _x('Parent slide:','inter_slider'),
			'menu_name' => _x('InterSlider','inter_slider'),
		);
		$args = array(
			'labels' => $labels,
			'hierarchical' => true,
			'description' => 'Music reviews filterable by genre',
			'supports' => array( 'title','editor'),
			'taxonomies' => array( 'genres' ),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 10,
			'menu_icon' => 'dashicons-format-image',
			'show_in_nav_menus' => false,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post'
		);
		register_post_type('inter_slider', $args);
	}
	
}
?>