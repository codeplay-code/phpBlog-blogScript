(function($) { // Avoid conflicts with other libraries
'use strict';

$(function() {
	var settings = {
			min: 200,
			scrollSpeed: 400
		},
		toTop = $('.scroll-btn'),
		toTopHidden = true;

	$(window).scroll(function() {
		var pos = $(this).scrollTop();
		if (pos > settings.min && toTopHidden) {
			toTop.stop(true, true).fadeIn();
			toTopHidden = false;
		} else if(pos <= settings.min && !toTopHidden) {
			toTop.stop(true, true).fadeOut();
			toTopHidden = true;
		}
	});

	toTop.bind('click touchstart', function() {
		$('html, body').animate({
			scrollTop: 0
		}, settings.scrollSpeed);
	});
});

})(jQuery);