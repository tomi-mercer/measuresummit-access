/* This is your custom Javascript */
jQuery(document).ready(($) => {
	
	function matchHeight() {
		let h = $('.player--inner').outerHeight(),
		  watchNow = $('.watching-now').outerHeight();
		
		if ( $(window).width() < 602 ) {

			$('.player--sidebar').css('height', h);
			$('.day--schedule-details').css('height', h - 103);
			$('.chatbox--inner').css('height', h - (269 + watchNow));
			$('.askaquestion .questions').css('height', h - 201);
		}else {
			$('.player--sidebar').css('height', h);
			$('.day--schedule-details').css('height', h - 182);
			$('.chatbox--inner').css('height', h - 269);
			$('.askaquestion .questions').css('height', h - 239);
		}
	}
	$(window).load(()=> {
		matchHeight();
	})

	if ($(window).width() < 1025) {
    	$('main.dashboard').addClass('active-sidebar')
	}
	
	var resizeTimeout = null;
	$(window).resize(() => {
		if ($(window).width() < 1025) {
	    	$('main.dashboard').addClass('active-sidebar')
		}
	    matchHeight();
	    clearTimeout(resizeTimeout);
	    resizeTimeout = setTimeout(matchHeight, 500);
	});
	

	$('.btn-close').click((e) => {
		e.preventDefault();
		$('main.dashboard').toggleClass('active-sidebar')
	});

	$('.player--sidebar-ctrl button').click(() => {
	 	matchHeight();
	    clearTimeout(resizeTimeout);
	    resizeTimeout = setTimeout(matchHeight, 500);
		if ( $('main.dashboard.active-sidebar').length ) {
			$('main.dashboard').toggleClass('active-sidebar')
		}
	});
});