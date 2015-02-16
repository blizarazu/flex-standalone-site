/*
 * jQuery Infinite Carousel
 * @author admin@catchmyfame.com - http://www.catchmyfame.com
 * @version 2.0.2
 * @date June 12, 2010
 * @category jQuery plugin
 * @copyright (c) 2009 admin@catchmyfame.com (www.catchmyfame.com)
 * @license CC Attribution-Share Alike 3.0 - http://creativecommons.org/licenses/by-sa/3.0/
 */

(function($){
	$.fn.extend({ 
		infiniteCarousel: function(options)
		{
			var defaults = 
			{
				transitionSpeed: 20,
				displayTime: 6000,
				textholderHeight: .25,
				displayProgressBar: true,
				displayThumbnails: true,
				displayThumbnailNumbers: true,
				displayThumbnailBackground: true,
				thumbnailWidth: '20px',
				thumbnailHeight: '20px',
				thumbnailFontSize: '.7em',
				easeLeft: 'linear',
				easeRight: 'linear',
				imagePath: '/js/infinitecarousel/images/',
				inView: 1,
				padding: '0px',
				advance: 1,
				showControls: true,
				autoHideControls: false,
				autoHideCaptions: false,
				autoStart: false,
				prevNextInternal: true,
				enableKeyboardNav: false,
				onSlideStart: function(){},
				onSlideEnd: function(){},
				onPauseClick: function(){}
			};
		var options = $.extend(defaults, options);
	
    		return this.each(function() {
    			var randID = Math.round(Math.random()*100000000);
			var o=options;
			var obj = $(this);
			var autopilot = o.autoStart;

			var numImages = $('div', obj).length; // Number of images
			//var imgHeight = $('div:first > img', obj).height() ? $('div:first > img', obj).height() : 720;
			//var imgWidth = $('div:first > img', obj).width() ? $('div:first > img', obj).width(): 576;
			var imgHeight = 480;
			var imgWidth = 640;
			$('div', obj).css({'width':imgWidth+'px','height':imgHeight*0.94+'px','text-align':'center', 'font-size':'4em', 'background':'white'});
			$('img', obj).width(imgWidth).height(imgHeight*0.94);
			if(o.inView > numImages-1) 
				o.inView=numImages-1; // check to make sure inview isnt greater than the number of images. inview should be at least two less than numimages (otherwise hinting wont work and animating left may catch a flash), but one less can work
			$('p', obj).hide(); // Hide any text paragraphs in the carousel
			$(obj).css({'border':'solid 1px black','position':'relative','overflow':'hidden', 'margin-left':'auto','margin-right':'auto'}).width((imgWidth*o.inView)+(o.inView*parseInt(o.padding)*2)).height(imgHeight+(parseInt(o.padding)*2)); //,'overflow':'hidden'
			$('ul', obj).css({'list-style':'none','margin':'0','padding':'0','position':'relative'}).width(imgWidth*numImages);
			$('li', obj).css({'display':'inline','float':'left','padding':o.padding});

			// Move rightmost image over to the left
			$('li:last', obj).prependTo($('ul', obj));
			$('ul', obj).css('left',-imgWidth-(parseInt(o.padding)*2)+'px').width(9999);
			
			$('li', obj).append('<label>Display time:<input type="text" name="Test" value="5" size="3" /></label>');
			$('li > label', obj).css({'background-color':'#3B3B3B', 'display':'block','font-size':'1em','margin-left':'auto','margin-right':'auto', 'padding-top':'4px', 'padding-bottom':'4px', 'text-align':'center', 'color':'white'});
			//$('li > input', obj).css({'display':'block','font-size':'1em','margin-left':'auto','margin-right':'auto'});

			
			function goToFirstSlide(auto){
				target_num = 1; // we want target_num[1]
				if(viewable[0] != target_num)
				{
					status='pause';
					//$('#progress'+randID).stop().fadeOut();
					clearTimeout(clearInt);
					$('#thumbs'+randID+' div').css({'cursor':'default'}).unbind('click'); // Unbind the thumbnail click event until the transition has ended
					autopilot = 0;
					setTimeout(function(){
						$('#play_pause_btn'+randID).css('background-position','0 -16px')
						},o.transitionSpeed);
					$('#play_pause_btn'+randID).unbind('click').bind('click',function(){forceStart();});
				}
				if(target_num > viewable[0])
				{
					diff = target_num - viewable[0];
					moveLeft(diff);
				}
				if(target_num < viewable[0])
				{
					diff = viewable[0]- target_num;
					moveRight(diff);
				}
				autopilot = auto;
			}
			
			
				function thumbclick(event)
				{
					target_num = this.id.split('_'); // we want target_num[1]
					if(viewable[0] != target_num[1])
					{
						status='pause';
						$('#progress'+randID).stop().fadeOut();
						clearTimeout(clearInt);
						$('#thumbs'+randID+' div').css({'cursor':'default'}).unbind('click'); // Unbind the thumbnail click event until the transition has ended
						autopilot = 0;
						setTimeout(function(){
										$('#play_pause_btn'+randID).css('background-position','0 -16px')
								   },o.transitionSpeed);
						$('#play_pause_btn'+randID).unbind('click').bind('click',function(){forceStart();});
					}
					if(target_num[1] > viewable[0])
					{
						diff = target_num[1] - viewable[0];
						moveLeft(diff);
					}
					if(target_num[1] < viewable[0])
					{
						diff = viewable[0]- target_num[1];
						moveRight(diff);
					}
				}

				var viewable = []; // track which images are being displayed
				var unviewable = []; // track which images are being displayed
				// Build thumbnail viewer and thumbnail divs
				$(obj).after('<div id="thumbs'+randID+'" style="position:relative;overflow:auto;clear:left;text-align:center;padding-top:5px;margin-left:auto;margin-right:auto;"></div>');
				for(i=0;i<=numImages-1;i++)
				{	
					thumb = '';
					thtext = '';
					if($('div:eq('+(i+1)+')', obj).has('img').length > 0){
						thumb = $('div:eq('+(i+1)+') > img', obj).attr('src').length ? $('div:eq('+(i+1)+') > img', obj).attr('src') : '';
					} else if($('div:eq('+(i+1)+')', obj).html() != null) {
						thtext = $('div:eq('+(i+1)+')', obj).html().length ? $('div:eq('+(i+1)+')', obj).html() : '';
					}
//					thumb = $('div:eq('+(i+1)+') > img', obj).attr('src') ? $('img:eq('+(i+1)+')', obj).attr('src') : '';
//					thtext = $('div:eq('+(i+1)+')', obj).html() ? $('div:eq('+(i+1)+')', obj).html(): '' ;
				//	console.log('thumb: ' +thumb);
				//	console.log('text: '+thtext);
					if(thumb.length > 0)
						$('#thumbs'+randID).append('<div class="thumb" id="thumb'+randID+'_'+(i+1)+'" style="cursor:pointer;background:white;display:inline;float:left;width:'+o.thumbnailWidth+';height:'+o.thumbnailHeight+';line-height:'+o.thumbnailHeight+';padding:0;overflow:hidden;text-align:center;border:2px solid #ccc;margin-right:4px;font-size:'+o.thumbnailFontSize+';font-family:Arial;color:#000;text-shadow:0 0 3px #fff"><img src="'+thumb+'" width="120" heigth="90"/></div>');
					else
						$('#thumbs'+randID).append('<div class="thumb" id="thumb'+randID+'_'+(i+1)+'" style="cursor:pointer;background:white;display:inline;float:left;width:'+o.thumbnailWidth+';height:'+o.thumbnailHeight+';line-height:'+o.thumbnailHeight+';padding:0;overflow:hidden;text-align:center;border:2px solid #ccc;margin-right:4px;font-size:'+o.thumbnailFontSize+';font-family:Arial;color:#000;text-shadow:0 0 3px #fff">'+thtext+'</div>');
					//Put red border to the thumbs whose images are displayed
					if(i<=o.inView) 
						$('#thumb'+randID+'_'+i).css({'border-color':'#ff0000'});
					unviewable.push(i+1);
				}
				// Initialize viewable/unviewable arrays
				for(i=1;i<=o.inView;i++) 
					viewable.push(unviewable.shift());

				// Next two lines are a special case to handle the first list element which was originally the last
				if($('div:first', obj).has('img').length > 0){
					thumb = $('div:first > img', obj).attr('src').length ? $('div:first > img', obj).attr('src') : '';
				} else {
					thtext = $('div:first', obj).html().length ? $('div:first', obj).html() : '';
				}
				
				
				//thumb = $('div:first > img', obj).attr('src') ? $('div:first > img', obj).attr('src') : '';
				//text = $('div:first', obj).html() ? $('div:first', obj).html() : '';
				if(thumb.length > 0)
					$('#thumb'+randID+'_'+numImages).empty().append('<img src="'+thumb+'" width="120" heigth="90"/>');//.css({'background-image':'url('+thumb+')'});
				else
					$('#thumb'+randID+'_'+numImages).empty().append(thtext);
				$('#thumbs'+randID+' div.thumb:not(:first)').css({opacity:.65}); // makes all thumbs 65% opaque except the first one

				$('#thumbs'+randID+' div.thumb').hover(function(){
					$(this).animate({'opacity':1},150)},
									function(){
										if(viewable[0]!=this.id.split('_')[1]) 
											$(this).animate({'opacity':.65},250)}
									); // add hover to thumbs
				// Assign click handler for the thumbnails. Normally the format $('.thumb') would work but since it's outside of our object (obj) it would get called multiple times
				$('#thumbs'+randID+' div').bind('click', thumbclick); // We use bind instead of just plain click so that we can repeatedly remove and reattach the handler
				
				if(!o.displayThumbnailNumbers) 
					$('#thumbs'+randID+' div').text('');
				if(!o.displayThumbnailBackground) 
					$('#thumbs'+randID+' div').css({'background-image':'none'});
			

			if(o.showControls)
			{
				// Pause/play button(img)
				html = '<div id="play_pause_btn'+randID+'" style="cursor:pointer;position:absolute;top:3px;right:3px;border:none;width:16px;height:16px;background:url('+o.imagePath+'playpause.gif) no-repeat 0 0"></div>';
				$(obj).append(html);
				var status = 'play';
				$('#play_pause_btn'+randID).css('opacity',.5).hover(function(){$(this).animate({opacity:'1'},250)},function(){$(this).animate({opacity:'.5'},250)});
				$('#play_pause_btn'+randID).click(function(){
					status = (status == 'play') ? 'pause':'play';
					(status=='play') ? forceStart():forcePause();
				});

				if(!o.prevNextInternal)
				{
					wrapID = $(obj).attr('id')+'Wrapper';
					$(obj).wrap('<div id="'+wrapID+'"></div>').css('margin','0 auto');
					$('#'+wrapID).css('position','relative').width(($(obj).width()+40+parseInt($(obj).css('padding-left'))+parseInt($(obj).css('padding-right'))));
				}

				// Prev/next button(img)
				arrowsTop = ((imgHeight/2)-15)+parseInt(o.padding);
				html = '<div id="btn_rt'+randID+'" style="position:absolute;right:2px;top:'+arrowsTop+'px;cursor:pointer;border:none;width:13px;height:30px;background:url('+o.imagePath+'leftright.gif) no-repeat 0 0"></div>';
				html += '<div id="btn_lt'+randID+'" style="position:absolute;left:2px;top:'+arrowsTop+'px;cursor:pointer;border:none;width:13px;height:30px;background:url('+o.imagePath+'leftright.gif) no-repeat -13px 0"></div>';
				(o.prevNextInternal) ? $(obj).append(html):$('#'+wrapID).append(html);

				$('#btn_rt'+randID).css('opacity',.5).click(function(){
					forcePrevNext('next');
				}).hover(function(){$(this).animate({opacity:'1'},250)},function(){$(this).animate({opacity:'.5'},250)});
				$('#btn_lt'+randID).css('opacity',.5).click(function(){
					forcePrevNext('prev');
				}).hover(function(){$(this).animate({opacity:'1'},250)},function(){$(this).animate({opacity:'.5'},250)});

				if(o.autoHideControls && o.prevNextInternal)
				{
					function showcontrols()
					{
						$('#play_pause_btn'+randID).stop().animate({top:'3px',right:'3px'},250);
						$('#btn_rt'+randID).stop().animate({top:arrowsTop+'px',right:'2px'},250);
						$('#btn_lt'+randID).stop().animate({top:arrowsTop+'px',left:'2px'},250);
					}
					function hidecontrols()
					{
						$('#play_pause_btn'+randID).stop().animate({top:-16-containerBorder+'px',right:-16-containerBorder+'px'},250);
						$('#btn_rt'+randID).stop().animate({right:'-16px'},250);
						$('#btn_lt'+randID).stop().animate({left:'-16px'},250);
					}
					$(obj).hover(showcontrols,hidecontrols);
					hidecontrols();
				}
				/*
				if(o.autoHideCaptions)
				{
					var isHover;
					function autoShowCap(){isHover=true;for(i=1;i<=o.inView;i++) showtext($('li:eq('+i+') p', obj).html(),i);}
					function autoHideCap(){isHover=false;hideCaption();}
					$(obj).hover(autoShowCap,autoHideCap);
					hideCaption();
				}*/
			}

			function keyBind(){
				if(o.enableKeyboardNav)
				{
					$(document).keydown(function(event){
						if(event.keyCode == 39)
						{
							forcePrevNext('next');
							$(document).unbind('keydown');
						}
						if(event.keyCode == 37)
						{
							forcePrevNext('prev');
							$(document).unbind('keydown');
						}
						if(event.keyCode == 80 || event.keyCode == 111) forcePause();
						if(event.keyCode == 83 || event.keyCode == 115)
						{
							forceStart();
							$(document).unbind('keydown');
						}
					});
				}
			}

			function forcePrevNext(dir)
			{
				o.onPauseClick.call(this);
				$('#btn_rt'+randID).unbind('click');
				$('#btn_lt'+randID).unbind('click');
				setTimeout(function(){$('#play_pause_btn'+randID).css('background-position','0 -16px')},o.transitionSpeed-1);
				autopilot = 0;
				$('#progress'+randID).stop().fadeOut();
				status='pause';
				clearTimeout(clearInt);
				(dir=='prev') ? moveRight():moveLeft();
				$('#play_pause_btn'+randID).unbind('click');
				setTimeout(function(){
						$('#play_pause_btn'+randID).bind('click',function(){forceStart();});
						$('#btn_rt'+randID).bind('click',function(){forcePrevNext('next')});
						$('#btn_lt'+randID).bind('click',function(){forcePrevNext('prev')});
					},o.transitionSpeed);
			}

			function forcePause()
			{
				$('#play_pause_btn'+randID).unbind('click'); // unbind the click, wait for transition, then reenable
				if(autopilot)
				{
					o.onPauseClick.call(this);
					$('#play_pause_btn'+randID).fadeTo(250,0,function(){$(this).css({'background-position':'0 -16px','opacity':'.5'});}).animate({opacity:.5},250);
					autopilot = 0;
					//showminmax();
					$('#progress'+randID).stop().fadeOut();
					clearTimeout(clearInt);
					setTimeout(function(){$('#play_pause_btn'+randID).bind('click',function(){forceStart();})},o.transitionSpeed);
				}
			}

			function forceStart()
			{
				//goToFirstSlide(1);
				$('#play_pause_btn'+randID).unbind('click'); // unbind the click, wait for transition, then reenable
				if(!autopilot)
				{
					setTimeout(function(){
									$('#play_pause_btn'+randID).css('background-position','0 0')
								},o.transitionSpeed-1);
					autopilot = 1;
					moveLeft();
					console.log('calling from forcestart');
					//alert($('li:first > input', obj).attr('value'));
//					clearInt=setInterval(function(){
//											moveLeft();
//										 },$('li:first > input', obj).attr('value')+o.transitionSpeed);
					setTimeout(function(){
						$('#play_pause_btn'+randID).bind('click',function(){
							forcePause();
							})
					},o.transitionSpeed);
				}
			}

			function preMove()
			{
				//hideCaption();
				// Fade out play/pause/left/right
				if(o.showControls && o.prevNextInternal)
				{
					$('#play_pause_btn'+randID).fadeOut(200);
					$('#btn_lt'+randID).fadeOut(200);
					$('#btn_rt'+randID).fadeOut(200);
				}
				if(o.displayThumbnails) 
					for(i=1;i<=numImages;i++) 
						$('#thumb'+randID+'_'+i).css({'border-color':'#ccc'}).animate({'opacity': .65},500);
			}

			function postMove()
			{
				if(o.showControls && o.prevNextInternal)
				{
					$('#play_pause_btn'+randID).fadeIn(200);
					$('#btn_lt'+randID).fadeIn(200);
					$('#btn_rt'+randID).fadeIn(200);
				}
				keyBind();
				//if(o.autoHideCaptions && isHover) autoShowCap();
				if(o.displayThumbnails) 
					for(i=0;i<viewable.length;i++) 
						$('#thumb'+randID+'_'+viewable[i]).css({'border-color':'#ff0000'}).animate({'opacity': 1},500);
				//if(!o.autoHideCaptions) for(i=1;i<=o.inView;i++) showtext($('li:eq('+i+') p', obj).html(),i);
				if(o.displayThumbnails) 
					$('#thumbs'+randID+' div').unbind('click').bind('click', thumbclick).css({'cursor':'pointer'});
				ary=[];
				for(x=1;x<=o.inView;x++){
					ary.push($('img:eq('+x+')',obj).attr('src'))
				}
				o.onSlideEnd.call(this,ary);
			}

			function moveLeft(dist)
			{
				if(dist==null) 
					dist=o.advance;
				preMove();
				if(o.displayThumbnails)
				{
					for(i=1;i<=dist;i++){
						viewable.push(unviewable.shift());
						unviewable.push(viewable.shift());
					}
				}
//				if(o.displayTime == 0){
//					clearInterval(clearInt);
//				} // If running a contonuous show with no display time, fist clear the interval. Then below, recursively call moveLeft
				$('li:lt('+dist+')', obj).clone(true).insertAfter($('li:last', obj)); // Copy the first image (offscreen to the left) to the end of the list (offscreen to the right)
				o.onSlideStart.call(this,viewable,'left');
				$('ul', obj).animate({left:-imgWidth*(dist+1)-(parseInt(o.padding)*(dist+1))*2},o.transitionSpeed,o.easeLeft,function(){ // Animate the entire list to the left
					$('li:lt('+dist+')', obj).remove(); // When the animation finishes, remove the first image (on the left). It has already been copied to the end of the list (right)
					$(this).css({'left':-imgWidth-parseInt(o.padding)*2});
//					if(o.displayProgressBar && autopilot) 
//						startProgressBar();
					postMove();
//					if(o.displayTime == 0){
//						moveLeft();
//					}
					if(autopilot){
						var delay = $('li:eq(1) > label > input', obj).attr('value').length ? $('li:eq(1) > label > input', obj).attr('value') : 5000;
						delay = parseInt(delay)*1000+o.transitionSpeed;
						console.log(delay);
						clearInterval(clearInt);
						clearInt=setInterval(function(){
												moveLeft();
											 },
											 delay);
					}
				});
			}
			function moveRight(dist)
			{
				if(dist==null) dist=o.advance;
				preMove();
				if(o.displayThumbnails)
				{
					for(i=1;i<=dist;i++){
						viewable.unshift(unviewable.pop());
						unviewable.unshift(viewable.pop());
					}
				}
				$('li:gt('+(numImages-(dist+1))+')', obj).clone(true).insertBefore($('li:first', obj)); // Copy rightmost (last) li and insert it after the first li
				o.onSlideStart.call(this,viewable,'right');
				$('ul', obj).css('left',-(imgWidth*(dist+1))-(parseInt(o.padding)*((dist+1)*2)))
					.animate({left:-imgWidth-(parseInt(o.padding)*2)},o.transitionSpeed,o.easeRight,function(){
						$('li:gt('+(numImages-1)+')', obj).remove();
						postMove();
					});
			}

			// Kickoff the show
			if(autopilot)
			{
				var clearInt = setInterval(function(){
					moveLeft();
					},o.displayTime+o.transitionSpeed);
				if(o.displayProgressBar) 
					startProgressBar(o.displayTime+o.transitionSpeed);
			} else {
				status='pause';
				$('#play_pause_btn'+randID).css({'background-position':'0 -16px'});
			}
			keyBind();
 		});
	}
	});
})(jQuery);