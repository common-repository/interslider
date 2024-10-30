jQuery.noConflict();
(function($){
	
	$(document).ready(function(){
		
		var restore_send_to_editor = window.send_to_editor;
		
		$('#upload_image_button').click(function() {
			formfield = $('#upload_image').attr('name');
			tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
			
			
			window.send_to_editor = function(html) {
				imgurl = $('img',html).attr('src');
				$('#upload_image').val(imgurl);
				var img = $('.is_meta_box').find('img');
				if(img.length>0) img.attr('src',imgurl)
					else $('.is_meta_box').prepend($('<label><strong>URL adress:</strong> '+imgurl+'</label>')).prepend($('<img/>').attr('src',imgurl));
				tb_remove();
				window.send_to_editor = restore_send_to_editor;
			}
			
			
			return false;
		});
		
		$('#remove_image_button').click(function() {
			if(confirm("Confirm delete of image")){
				$('#upload_image').val(null);
				$('.is_meta_box').find('img,label').remove();
			}
			return false;
		});
		
		
				
		$(document).on('change','select#transition_type',function(){
			if($(this).val()==0){
				$('input#tile_number_x, input#tile_number_y').prop('disabled', true);
			} else{
				$('input#tile_number_x, input#tile_number_y').prop('disabled', false);
			}
		});
		
		$(document).on('change','select#slider_size',function(){
			if($(this).val()==0){
				$('input#slider_width').prop('disabled', true);
			} else{
				$('input#slider_width').prop('disabled', false);
			}
		})
		
	});

})(jQuery);