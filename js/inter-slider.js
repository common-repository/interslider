(function($){
	$.fn.interSlider = function(){
		
		var settings = {
			duration : parseInt(php_options.delay),
			fadeTime : parseInt(php_options.transition_time),
			pauseOnHover : php_options.pause_on_hover==1 ? true : false,
			transitionType : php_options.transition_type,
			showNavigation : php_options.show_nav==1 ? true : false,
			showTimeBar : php_options.show_progress==1 ? true : false,
			showNextPrev : php_options.show_next_prev ==1 ? true : false,
			showDescription : php_options.show_description ==1 ? true : false,
			tx : parseInt(php_options.tile_number_x),
			ty : parseInt(php_options.tile_number_y),
			sliderSize: php_options.slider_size,
			sliderWidth: php_options.slider_width,
			sliderHeight: php_options.slider_height
		};
		
		var time,diff,tiles;
		var current = 0;
		var completed = true;
		var pause = false;
		
		var slider = this.addClass('interSlider').addClass('loading');
		
		var tx = settings.tx;
		var ty = settings.ty;
		
		if(settings.sliderSize==1){
			slider.css('width',settings.sliderWidth+'px');
		}
		
		if(settings.sliderHeight>0)
				slider.parent().css('height',settings.sliderHeight+'px');
			else slider.parent().css('height',slider.parent().outerHeight()+'px');

		var w = Math.ceil(slider.width() / tx);
		var h = Math.ceil(slider.height() / ty);

		var lp = new Array();
		var tp = new Array();
		
		for(var y=0;y<ty;y++)
			for(var x=0;x<tx;x++){
				lp.push(x*w);
				tp.push(y*h);
			}
			
		var el,imgs,description,nel,images = new Array();
		var tiles;
		
		var navigation = $('<ul></ul>').addClass('navigation');

		var preload_images = function(){
			
			$.each(slides,function(key,value){
				title = $('<h1>'+value['title']+'</h1>');
				content = $('<p>'+value['content']+'</p>');
				img = $('<img />').attr('src',value['src']);
				var tdescription = $('<div></div>').addClass('description').append(title).append(content);
				container = $('<li id="'+value['id']+'"></li>').append(img).append(tdescription);;
				slider.prepend(container);
				navigation.append($('<li></li>')).find('li').eq(key).append($('<a></a>'));
			});
			
			el = slider.children('li');
			el.hide(0);
			imgs = el.children('img');
			description = el.find('.description');
			nel = navigation.find('li');
			
			el.each(function(key,value){
				$(this).attr('data-src',imgs.eq(key).attr('src'));
			});
			imgs.remove();
			
			loadImages(el,0);
		}
		
		var loadImages = function(el,key){
			if(key<el.length){
				$('<img />').attr('src',el.eq(key).data("src")).bind('onreadystatechange load', function(){
					if (this.complete){
						var image = $('<div></div>').addClass('image');
						el.eq(key).append(image);
						el.eq(key).append($('<div></div>').addClass('progressWrap'));
						
						if(settings.transitionType==1) 
							tileLoad(image,el.eq(key).data('src'),key);
						images.push(image);
						loadImages(el,++key)
					}
				}); 
			} else {
				startSlider(el);
			}
			
			
		}
		
		var startSlider = function(el){

			//all images loaded
			tiles = el.find('.tile');
			nel = navigation.children('li');
			nel.first().find('a').addClass('active');
			
			slider.show(0);
			slider.append(navigation.fadeOut(0));
			slider.find('ul.navigation');
			navigation.append($('<li></li>').addClass('control').append($('<a></a>').addClass('pause')));
			slider.append($('<a title="Skip to next slide"></a>').addClass('next'));
			slider.append($('<a title="Go back to previous slide"></a>').addClass('prev'));
			
			slider.removeClass('loading').promise().done(function(){
				if(settings.showNavigation) navigation.slideDown(settings.fadeTime);
				if(!settings.showNextPrev) slider.find('.next,.prev').hide(0);
				showSlide(0);
			});

		}
		
		var tileLoad = function(target,src,index){
			for(var y=0;y<ty;y++)
				for(var x=0;x<tx;x++){
					var tile = $('<div></div>').addClass('tile').css({
						'left':lp[x+y*tx]+'px',
						'top':tp[x+y*tx]+'px',
						'width':w+'px',
						'height':h+'px',
						"transform": "rotateX(30deg) rotateY(30deg) scale(0.95)"
					}).attr('id',x+y*tx);
					tile.append($('<div></div>').addClass('rtile').css({
						'width':slider.css('width'),
						'height':slider.css('height'),
						'background-image':'url('+src+')',
						'transform':'translateX('+(-lp[x+y*tx])+'px) translateY('+(-tp[x+y*tx])+'px)'						
					}));
					$(target).append(tile);
				}
		}
		
		
		var showSlide = function(index){
			if(completed){
				switch(settings.transitionType){
					case "0":
						fadeTransition(index);
						break;
					case "1":
						squaresTransition(index);
						break;
				}
			}
		}
		
		var fadeTransition = function(index){
			completed=false;
			$(images[index]).css('background-image','url('+el.eq(index).data('src')+')');
			slider.children('li:visible').fadeOut(settings.fadeTime,function(){
				nextItem(index);
				description.removeAttr('style');
			}).promise().always(function(){
				el.eq(index).fadeIn(settings.fadeTime,function(){
					if(settings.showDescription)
						showDescription(index);
					addProgressBar(index);
					time = new Date().getTime();
					current=0;
					completed=true;
					animation(index);
				});
			});
		}
		
		var squaresTransition = function(index){
			completed=false;
			el.fadeOut(0,function(){
				description.removeAttr('style');
				nextItem(index);
			}).promise().always(function(){
				el.eq(index).fadeIn(0,function(){
					var src= el.eq(index).data('src');
					addProgressBar(index);
					tiles.css({'transform':'none','opacity':'0'});
					tileAnim(index,el.eq(index).data('src'));
				});
			})
		}
		
		var showDescription = function(index){
			description.eq(index).animate(
			{
			opacity:1,
			},
			{
				duration:100,
				complete: function(obj, num, remain) {
					$(this).css({
						"transform": "translateY(-50%)"
					});
				},
				easing:"linear"
			});
		}
		
		var nextItem = function(index){
			$(nel).find('a').removeClass('active');
			$(nel[index]).find('a').addClass('active');
		}
	
		var tileAnim = function(index,src){
		
			$.each(el.eq(index).find('.tile'),function(key,value){
				$(this).delay(Math.round(settings.fadeTime/(tx*ty))*(key%tx)*(key/ty)).animate(
				{
				opacity:1
				},
				{
				duration:settings.fadeTime, 
				progress: function(obj, num, remain) {
					$(this).css({
						"transform": "rotateX("+(30-(num*30))+"deg) rotateY("+(30-(num*30))+"deg) scale("+(num)+")"
					});
				},
				complete:function(){
					if(key==(tx*ty-1)){
						if(settings.showDescription)
							showDescription(index);
						completed=true;
						current=0;
						time = new Date().getTime();
						$(images).eq(index).css('background-image','url('+src+')');
						animation(index);
					}
				}
				});
			})
		}
				
		
		
		var addProgressBar = function(index){
			slider.find('.progressBar').remove();
			slider.find('.progressWrap').eq(index).append($('<div></div>').addClass('progressBar'));
			if(!settings.showTimeBar) slider.find('.progressWrap').hide(0);
		}
		
		var animFin = function(index){
			slider.find('.progressBar').animate(
			{
				width: "100%"
			},
			{
				duration : settings.duration-current,
				start: function(){
				var next = (index+1) % images.length;
				$(images[next]).css('background-image','url('+el.eq(index).data('src')+')');
				},
				always: function(){
					if(!pause){
						$(this).fadeOut(settings.fadeTime);
					}
				},
				complete: function(){
					if(completed){
						description.animate(
						{
						opacity:0
						},
						{
							duration:settings.fadeTime,
							complete: function(obj, num, remain) {
								description.removeAttr('style');
								showSlide((++index)%el.length);
							},
							easing:"linear"
						});
					}	
				},
				easing: "linear"
			}
			)
		}
		
		var animation = function(index){
			slider.find('.progressBar').stop(true,false);
			if(completed && !pause)
				animFin(index);
		}
		
		$(document).on("click",".interSlider .navigation > li:not('.control') a:not('.active')",function(){
			if(completed ){
				slider.find('.progressBar').stop(true,false);
				showSlide($(this).parent().index());
			}
		});
		
		if(settings.showNextPrev){
			$(document).on("click",".interSlider a.next",function(){
				if(completed){
					slider.find('.progressBar').stop(true,false);
					showSlide((slider.children('li:visible').index()+1)%images.length);
				}
				return false;
			});
			
			$(document).on("click",".interSlider a.prev",function(){
				if(completed){
					slider.find('.progressBar').stop(true,false);
					showSlide((slider.children('li:visible').index()-1)%images.length);
				}
			});
		}
		
		$(document).on("click",".control > a",function(){
			if($(this).hasClass("pause")){
				$(this).removeClass('pause');
				$(this).addClass('play');
				pause=true;
				current+=diff;
				slider.find('.progressBar').stop(true,false);
			} else {
				pause=false;
				$(this).removeClass('play');
				$(this).addClass('pause');
				time = new Date().getTime();
				if(completed && slider.find('.progressBar').length>0)
					animation(slider.children('li:visible').index());		
			}
		})

		if(settings.pauseOnHover){
		
			$(document).on({
				"mouseenter":function(){
					pause=true;
					current+=diff;
					slider.find('.progressBar').stop(true,false);
				},
				"mouseleave":function(){
					pause=false;
					time = new Date().getTime();
					if(completed && slider.find('.progressBar').length>0)
						animation(slider.children('li:visible').index());
				}
			},".interSlider > li");
		
		}

		var checktime = setInterval(function(){		
			var curr = new Date().getTime();
			diff = curr - time;
		},1);
		
		$(window).load(function(){
			preload_images();
		});
		
	}
		
		$(document).ready(function(){
			$('#is_container').interSlider();			
		});
	
	

})(jQuery);