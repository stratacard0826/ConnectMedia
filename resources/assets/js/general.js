(function($){


	/**
	*
	*	Prevent Browser from Scrolling
	*		- Prevents Scrolling elements from moving the browser when reaching the bottom of the scroll box
	*
	**/
	$('[data-scroll-box]').mousewheelStopPropagation();



	/**
	*
	*	Loads the Calendar
	*
	*
	**/
	$('[data-calendar]').fullCalendar({

	});



	/**
	*
	* 	CLICK: [data-menu-control]
	*		- Controls the Menu
	*
	*
	**/
	$('[data-menu-control]').click(function(e){

		e.stopPropagation();

		$('#sidebar').toggleClass('open');

		$('[data-menu-control]').toggleClass('fa-bars fa-times');

	});





	/**
	*
	*	CLICK: #sidebar li
	*		- Close the menu when clicking a link
	*
	*
	**/
	$('#sidebar li').click(function(e){
		if( $(this).find('ul').length == 0 ){

			$('[data-menu-control]:visible').click();

		}
	});





	/**
	*
	*	CLICK: body
	*		- When clicking the Body, if the target is not a part of the sidebar, close the sidebar
	*
	*
	**/
	$('body').click(function(e){
		if( $( e.target ).closest('#sidebar').length == 0 && $('#sidebar').hasClass('open') ){

			$('[data-menu-control]:visible').click();

		}
	});




	/**
	*
	* 	CLICK: [data-menu-control]
	*		- Controls the Menu
	*
	*
	**/
	$('#header section .showbtn').click(function(e){

		$(this).parent().toggleClass('show');
		$('.showbtn').not(this).parent().removeClass('show');

	});




	/**
	*
	* 	ON FOCUS: body :input
	*		- On Mobile Browsers, Hide the any error reporting while typing in an element
	*
	**/
	$('body').on('focus', ':input', function(){
		if( $(window).width() <= 500 ){
			$('.bs-callout-danger').addClass('hide');
		}
	});





	/**
	*
	* 	ON BLUR: body :input
	*		- On Mobile Browsers, Show any error reporting message that were previously hidden
	*
	**/
	$('body').on('blur', ':input', function(){
		$('.bs-callout-danger').removeClass('hide');
	});






})(jQuery);