<?php
class ISSettingsPage
{
	private $options;
	
	public function add_plugin_page(){
		add_submenu_page('edit.php?post_type=inter_slider','settings','Settings','manage_options','is_settings_page',array( $this,'create_admin_page'));
	}

	public function create_admin_page(){
		$this->options = get_option('is_settings_option');
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>InterSlider settings</h2>
			<p>Thank you for chosing InterSlider plugin.<br />Below you can find the settings groups affecting slider appearance and performance.<br />If you got any suggestions feel free to <a href="mailto:biuro@interkod.pl">contact me</a> or visit plugin website for more details.</p>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'is_settings_group' );   
				do_settings_sections('is_settings_page');
				submit_button(); 
			?>
			</form>
		</div>
<?php
	}
	public function page_init(){
		register_setting('is_settings_group','is_settings_option',array( $this,'sanitize'));
		
		add_settings_section('basic_settings_section','Basic Settings',array($this,'basic_section_info'),'is_settings_page');
		add_settings_section('transition_settings_section','Transition Settings',array($this,'transition_section_info'),'is_settings_page');
		add_settings_section('appearance_settings_section','Appearance Settings',array($this,'appearance_section_info'),'is_settings_page');

		add_settings_field('show_nav','Show navigation',array( $this, 'show_nav_callback' ),'is_settings_page','basic_settings_section');      
		add_settings_field('show_next_prev','Show next/prev buttons',array($this,'show_next_prev_callback'),'is_settings_page','basic_settings_section');
		add_settings_field('show_description','Show title & description',array( $this, 'show_description_callback' ),'is_settings_page','basic_settings_section'); 
		add_settings_field('show_progress','Show progress bar',array( $this, 'show_progress_callback' ),'is_settings_page','basic_settings_section');
		add_settings_field('pause_on_hover','Pause on hover',array( $this, 'pause_on_hover_callback' ),'is_settings_page','basic_settings_section');
		
		add_settings_field('slider_size','Slider size',array( $this, 'slider_size_callback' ),'is_settings_page','appearance_settings_section');
		add_settings_field('slider_height','Slider height',array( $this, 'slider_height_callback' ),'is_settings_page','appearance_settings_section');
		add_settings_field('slider_width','Slider width',array( $this, 'slider_width_callback' ),'is_settings_page','appearance_settings_section');
		
		add_settings_field('transition_time','Transition time',array( $this, 'transition_time_callback' ),'is_settings_page','transition_settings_section');
		add_settings_field('delay','Transition delay',array( $this, 'delay_callback' ),'is_settings_page','transition_settings_section');
		add_settings_field('transition_type','Transition type',array( $this, 'transition_type_callback' ),'is_settings_page','transition_settings_section');
		add_settings_field('tile_number_x','Number of tiles on X axis',array( $this, 'tile_number_x_callback' ),'is_settings_page','transition_settings_section');
		add_settings_field('tile_number_y','Number of tiles on Y axis',array( $this, 'tile_number_y_callback' ),'is_settings_page','transition_settings_section');

	}

	public function sanitize($input){
		$new_input = array();
		$new_input['show_nav'] = isset($input['show_nav']) ? 1 : 0;
		$new_input['show_next_prev'] = isset($input['show_next_prev']) ? 1 : 0;
		$new_input['pause_on_hover'] = isset($input['pause_on_hover']) ? 1 : 0;
		$new_input['show_description'] = isset($input['show_description']) ? 1 : 0;
		$new_input['show_progress'] = isset($input['show_progress']) ? 1 : 0;
		
		$new_input['tile_number_x'] = $input['transition_type']=="1" ? absint($input['tile_number_x']) : absint($input['tile_x']);
		$new_input['tile_number_y'] = $input['transition_type']=="1" ? absint($input['tile_number_y']) : absint($input['tile_y']);
		$new_input['delay'] = absint($input['delay']);
		$new_input['transition_time'] = absint($input['transition_time']);

		$new_input['slider_size'] = $input['slider_size'];
		$new_input['slider_height'] = absint($input['slider_height']);
		$new_input['slider_width'] = $input['slider_size']=="1" ? absint($input['slider_width']) : absint($input['width']);
		
		$new_input['transition_type'] = $input['transition_type'];
		
		return $new_input;
	}
	
	public function basic_section_info(){
		print 'Edit basic settings below.';
	}
	
	public function transition_section_info(){
		print 'Edit transition settings below. <br />Limitations in numbers of tiles allow to increase slider performance.';
	}
	
	public function appearance_section_info(){
		print 'Edit appearance settings below.<br />Responsive size will inherit width from slider container, and fixed will set it with provided value.';
	}

	public function show_nav_callback(){
		$v = $this->options['show_nav'] ? "checked" : "";
		echo '<input type="checkbox" id="show_nav" name="is_settings_option[show_nav]" '.$v.' />';
	}

	public function show_next_prev_callback(){
		$v = $this->options['show_next_prev'] ? "checked" : "";
		echo '<input type="checkbox" id="show_next_prev" name="is_settings_option[show_next_prev]" '.$v.' />';
	}
	
	public function pause_on_hover_callback(){
		$v = $this->options['pause_on_hover'] ? "checked" : "";
		echo '<input type="checkbox" id="pause_on_hover" name="is_settings_option[pause_on_hover]" '.$v.' />';
	}
	
	public function show_description_callback(){
		$v = $this->options['show_description'] ? "checked" : "";
		echo '<input type="checkbox" id="show_description" name="is_settings_option[show_description]" '.$v.' />';
	}

	public function show_progress_callback(){
		$v = $this->options['show_progress'] ? "checked" : "";
		echo '<input type="checkbox" id="show_progress" name="is_settings_option[show_progress]" '.$v.' />';
	}
	public function transition_time_callback(){
		$v = $this->options['transition_time'];
		echo '<input type="number" id="transition_time" name="is_settings_option[transition_time]" value='.$v.' required />ms';
	}
	
	
	
	public function tile_number_x_callback(){
		$s = $this->options['transition_type']=="1" ? "" : "disabled";
		$v = $this->options['tile_number_x'];
		echo '<input type="number" id="tile_number_x" name="is_settings_option[tile_number_x]" min="5" max="40" step="5" value="'.$v.'" '.$s.' required />
		<input type="hidden" name="is_settings_option[tile_x]" value="'.$v.'" />
		';
	}
	
	public function tile_number_y_callback(){
		$s = $this->options['transition_type']=="1" ? "" : "disabled";
		$v = $this->options['tile_number_y'];
		echo '<input type="number" id="tile_number_y" name="is_settings_option[tile_number_y]" min="5" max="15" step="5" value="'.$v.'" '.$s.' required />
		<input type="hidden" name="is_settings_option[tile_y]" value="'.$v.'" />
		';
	}
	
	
	
	public function slider_height_callback(){
		$v = $this->options['slider_height'];
		echo '<input type="number" id="slider_height" name="is_settings_option[slider_height]" min="0" step="50" value="'.$v.'" required />px<span> (if <strong>0</strong>, height will be inherited from slider container)</span>';
	}
	
	public function slider_width_callback(){
		$s = $this->options['slider_size']=="1" ? "" : "disabled";
		$v = $this->options['slider_width'];
		echo '<input type="number" id="slider_width" name="is_settings_option[slider_width]" step="50" max="2000" value="'.$v.'" '.$s.' required />
		<input type="hidden" name="is_settings_option[width]" value="'.$v.'" />px
		';
	}
	
	
	
	public function delay_callback(){
		$v = $this->options['delay'];
		echo '<input type="number" id="delay" name="is_settings_option[delay]" value='.$v.' required />ms';
	}
	
	public function slider_size_callback(){
		$v = $this->options['slider_size'];
		echo '<select id="slider_size" name="is_settings_option[slider_size]">
			<option value=0 ';
			if($v==0) echo "selected";
			echo '>Responsive</option>
			<option value=1 ';
			if($v==1) echo "selected";
			echo '>Fixed</option>
		</select>';
	}
	
	public function transition_type_callback(){
		$v = $this->options['transition_type'];
		echo '<select id="transition_type" name="is_settings_option[transition_type]">
			<option value=0 ';
			if($v==0) echo "selected";
			echo '>Fade</option>
			<option value=1 ';
			if($v==1) echo "selected";
			echo '>Squares</option>
		</select>';
	}
}


?>