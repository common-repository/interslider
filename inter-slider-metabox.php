<?php
class ISMetaBox{
	
	public function __construct(){
		add_action('add_meta_boxes', array($this,'is_meta_box_add'));
		add_action('save_post', array($this,'save_slide'));
	}
	
	function is_meta_box_add(){
		add_meta_box('slide','Image','is_get_slide','inter_slider','normal','high');
		function is_get_slide($post){
			$slide = get_post_meta($post->ID,'_upload_image',true);
			echo ' <div class="is_meta_box">';
			if($slide){
				echo '<img class="is_image" src="'.$slide.'"/>
				<label><strong>URL adress:</strong> '.sanitize_text_field($slide).'</label>';
			}
			echo '<input id="upload_image" type="text" size="32" name="upload_image" value="'.sanitize_text_field($slide).'" hidden />
							<input id="upload_image_button" type="button" value="Change/Upload Image" />
							<input id="remove_image_button" type="button" value="Remove Image" />
			</div>';
		}
	}

	function save_slide($post_id){
	
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;
			
		if (!current_user_can('edit_post',$post_id))
			return;
		
		if(isset($_POST['upload_image'])){
			$slide = sanitize_text_field($_POST['upload_image']);
			update_post_meta($post_id,'_upload_image', $slide );
		}

	}
}
?>