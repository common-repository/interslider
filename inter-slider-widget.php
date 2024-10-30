<?php
// Creating the widget 
class ISWidget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
		// Base ID of your widget
		'is_widget', 
		// Widget name will appear in UI
		__('InterSlider widget', 'is_widget_domain'), 
		// Widget description
		array('description' => __('Responsive and customizable slider.','is_widget_domain')) 
		);
	}
	
	public function widget($args,$instance) {
		$title = apply_filters('widget_title',$instance['title']);
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if (!empty( $title ))
		echo $args['before_title'].$title.$args['after_title'];
		// This is where you run the code and display the output
		$this->set_container();
		echo $args['after_widget'];
	}
	
	public function is_scripts(){
		
		wp_enqueue_style('is_slider_css',WP_PLUGIN_URL.'/interslider/inter-slider.min.css');
		wp_register_script('is_slider', WP_PLUGIN_URL.'/interslider/js/inter-slider.js', array('jquery'));
		wp_enqueue_script('is_slider');
		wp_enqueue_style( 'jquery_UI_css', "//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css");
		wp_localize_script('is_slider','php_options',get_option('is_settings_option'));
		wp_localize_script('is_slider','slides',$this->get_slides());
	}
	
	public function set_container(){
		echo '<ul id="is_container"></ul>';
		$this->is_scripts();
	}
	
	public static function get_slides(){
		global $post;
		$arg = array('post_type'=>'inter_slider');
		$myposts = get_posts($arg);
		$result = array();
		foreach($myposts as $post) : setup_postdata( $post );
			$id = get_the_ID();
			$title = get_the_title();
			$content = get_the_content();
			$src = get_post_custom_values('_upload_image',$id)[0];
			$p['id'] = $id;
			$p['title'] = $title;
			$p['content'] = $content;
			$p['src'] = $src;
			array_push($result,$p);
		endforeach;
		wp_reset_postdata();
		return $result;
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = (!empty( $new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		return $instance;
	}
}
?>