/**
 * 
 */
(function($) {

	$.fn.iconPicker = function( options ) {
		var options = ['dashicons','dashicons']; // default font set
		var icons = [
					"blank",	// there is no "blank" but we need the option
					"menu",
					"admin-site",
					"dashboard",
					"admin-media",
					"admin-page",
					"admin-comments",
					"admin-appearance",
					"admin-plugins",
					"admin-users",
					"admin-tools",
					"admin-settings",
					"admin-network",
					"admin-generic",
					"admin-home",
					"admin-collapse",
					"admin-links",
					"format-links",
					"admin-post",
					"format-standard",
					"format-image",
					"format-gallery",
					"format-audio",
					"format-video",
					"format-chat",
					"format-status",
					"format-aside",
					"format-quote",
					"welcome-write-blog",
					"welcome-edit-page",
					"welcome-add-page",
					"welcome-view-site",
					"welcome-widgets-menus",
					"welcome-comments",
					"welcome-learn-more",
					"image-crop",
					"image-rotate-left",
					"image-rotate-right",
					"image-flip-vertical",
					"image-flip-horizontal",
					"undo",
					"redo",
					"editor-bold",
					"editor-italic",
					"editor-ul",
					"editor-ol",
					"editor-quote",
					"editor-alignleft",
					"editor-aligncenter",
					"editor-alignright",
					"editor-insertmore",
					"editor-spellcheck",
					"editor-distractionfree",
					"editor-kitchensink",
					"editor-underline",
					"editor-justify",
					"editor-textcolor",
					"editor-paste-word",
					"editor-paste-text",
					"editor-removeformatting",
					"editor-video",
					"editor-customchar",
					"editor-outdent",
					"editor-indent",
					"editor-help",
					"editor-strikethrough",
					"editor-unlink",
					"editor-rtl",
					"align-left",
					"align-right",
					"align-center",
					"align-none",
					"lock",
					"calendar",
					"visibility",
					"post-status",
					"post-trash",
					"edit",
					"trash",
					"arrow-up",
					"arrow-down",
					"arrow-left",
					"arrow-right",
					"arrow-up-alt",
					"arrow-down-alt",
					"arrow-left-alt",
					"arrow-right-alt",
					"arrow-up-alt2",
					"arrow-down-alt2",
					"arrow-left-alt2",
					"arrow-right-alt2",
					"leftright",
					"sort",
					"list-view",
					"exerpt-view",
					"share",
					"share1",
					"share-alt",
					"share-alt2",
					"twitter",
					"rss",
					"facebook",
					"facebook-alt",
					"networking",
					"googleplus",
					"hammer",
					"art",
					"migrate",
					"performance",
					"wordpress",
					"wordpress-alt",
					"pressthis",
					"update",
					"screenoptions",
					"info",
					"cart",
					"feedback",
					"cloud",
					"translation",
					"tag",
					"category",
					"yes",
					"no",
					"no-alt",
					"plus",
					"minus",
					"dismiss",
					"marker",
					"star-filled",
					"star-half",
					"star-empty",
					"flag",
					"location",
					"location-alt",
					"camera",
					"images-alt",
					"images-alt2",
					"video-alt",
					"video-alt2",
					"video-alt3",
					"vault",
					"shield",
					"shield-alt",
					"search",
					"slides",
					"analytics",
					"chart-pie",
					"chart-bar",
					"chart-line",
					"chart-area",
					"groups",
					"businessman",
					"id",
					"id-alt",
					"products",
					"awards",
					"forms",
					"portfolio",
					"book",
					"book-alt",
					"download",
					"upload",
					"backup",
					"lightbulb",
					"smiley"
				];
                
		$list = $('');
		$button = $('.icon-picker');

		$button.each( function() {
			$(this).on('click.iconPicker', function() {
				createPopup($(this));
			});
		});

		function makeList($popup, $button, clear) {
			$list = $popup.find('.icon-picker-list');

			if (clear == 1) { 
				$list.empty(); 
			}

			for (var i in icons) {
				$list.append('<li data-icon="' + icons[i] + '"><a href="#" title="' + icons[i] + '"><span class="dashicons dashicons-' + icons[i] + '"></span></a></li>');
			};

			$('a', $list).click(function(e) {
				e.preventDefault();
				var title = $(this).attr("title");
				$target.val("dashicons|dashicons-" + title);
				$button.removeClass().addClass("button icon-picker dashicons dashicons-" + title);
				removePopup();
			});
		}
	
		function removePopup(){
			$(".icon-picker-container").remove();
		}

		function createPopup($button) {
			$target = $($button.data('target'));
			$popup = $('<div class="icon-picker-container"><div class="icon-picker-control"></div><ul class="icon-picker-list"></ul></div>')
				.css({
					'top': $button.offset().top,
					'left': $button.offset().left
				});

			makeList($popup, $button, 0);
			var $control = $popup.find('.icon-picker-control');
			$control.html(/*'<p>Select Font: <select><option value="dashicons">Dashicons</option><option value="fa">Font Awesome</option><option value="genericon">Genericons</option></select></p>'+ */
			'<a data-direction="back" href="#"><span class="dashicons dashicons-arrow-left-alt2"></span></a> '+
			'<input type="text" class="" placeholder="Search" />'+
			'<a data-direction="forward" href="#"><span class="dashicons dashicons-arrow-right-alt2"></span></a>'+
			'');

			$('select', $control).on('change', function(e) {
				e.preventDefault();
				if (this.value != "dashicons") {
					makeList($popup, $button, 1);
				};
			});

			$('a', $control).click(function(e) {
				e.preventDefault();
				if ($(this).data('direction') === 'back') {
					//move last 25 elements to front
					$('li:gt(' + (icons.length - 26) + ')', $list).each(function() {
						$(this).prependTo($list);
					});
				} else {
					//move first 25 elements to the end
					$('li:lt(25)', $list).each(function() {
						$(this).appendTo($list);
					});
				}
			});

			$popup.appendTo('body').show();

			$('input', $control).on('keyup', function(e) {
				var search = $(this).val();
				if (search === '') {
					//show all again
					$('li:lt(25)', $list).show();
				} else {
					$('li', $list).each(function() {
						if ($(this).data('icon').toString().toLowerCase().indexOf(search.toLowerCase()) !== -1) {
							$(this).show();
						} else {
							$(this).hide();
						}
					});
				}
			});

			$(document).mouseup(function (e){
				if (!$popup.is(e.target) && $popup.has(e.target).length === 0) {
					removePopup();
				}
			});
		}
	}


	$(function() {
		$('.icon-picker').iconPicker();
	});

}(jQuery));