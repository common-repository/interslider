<?php
defined( 'ABSPATH' ) OR exit;
/*
 * Plugin Name: InterSlider.
 * Plugin URI: http://wordpress.org/plugins/InterSlider
 * Description: Responsive and customizable slider.
 * Version: 1.1
 * Author: Karol Polakowski Interkod
 * Author URI: http://interkod.pl
 * License: GPL2
 
	Copyright 2015  Karol Polakowski  (email : biuro@interkod.pl)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class InterSlider{
	function __construct(){
		register_activation_hook(__FILE__, array($this,'is_activate'));
	}

	function is_activate(){
		global $defaults;
		$defaults	= array(
		"show_nav" => true,
		"show_next_prev" => true,
		"show_description" => true,
		"show_progress" => true,
		"pause_on_hover" => false,
		"slider_size" => 0,
		"slider_height" => 450,
		"slider_width" => 1280,
		"transition_time" => 500,
		"delay" => 3000,
		"transition_type" => 0,
		"tile_number_x" => 15,
		"tile_number_y" => 5
		);
		$options = get_option('is_settings');
		if($options===false)
			update_option('is_settings_option',$defaults);
	}
	
	function is_set(){
		require_once('inter-slider-widget.php');
		require_once('inter-slider-post-type.php');
		require_once('inter-slider-settings.php');
		require_once('inter-slider-metabox.php');
		
		if(is_admin()){
			$my_settings_page = new ISSettingsPage();
			
		}
		
		$ispt = new ISPostType();	
		$ismb = new ISMetaBox();
		
		add_action('admin_print_scripts',array($this,'my_admin_scripts'));
		add_action('admin_print_styles',array($this,'my_admin_styles'));
		add_action('init',array($ispt,'register_slider_post_type'));
		
		add_action('widgets_init',array($this,'is_load_widget'));
		add_action('inter_slider',array($this,'slider'));
		
		if(is_admin()){
			add_action('admin_menu',array($my_settings_page,'add_plugin_page'));
			add_action('admin_init',array($my_settings_page,'page_init'));
		}
	}

	function my_admin_scripts(){
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('is_admin',WP_PLUGIN_URL.'/interslider/js/inter-slider-admin.js',array('jquery','media-upload','thickbox'));
		wp_enqueue_script('is_admin');
	}
	function slider(){
		ISWidget::set_container();
	}

	function my_admin_styles(){
		wp_enqueue_style('thickbox');
		wp_enqueue_style('is_admin_css',WP_PLUGIN_URL.'/interslider/inter-slider-admin.min.css');
	}
	
	function is_load_widget() {
		register_widget('ISWidget');
	}

}
$is = new InterSlider();
$is->is_set();
?>