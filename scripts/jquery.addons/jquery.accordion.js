$(function() {
	// Hide all the content except the first
	$('.accordion li:odd:gt(0)').hide();
	
	// Add a padding to the first link
	$('.accordion li:first').animate( {
		paddingLeft:"15px"
	} );
	
	// Add the dimension class to all the content
	$('.accordion li:odd').addClass('dimension');
	
	// Set the even links with an 'even' class
	$('.accordion li:even:even').addClass('even');
	
	// Set the odd links with a 'odd' class
	$('.accordion li:even:odd').addClass('odd');
	
	// Show the correct cursor for the links
	$('.accordion li:even').css('cursor', 'pointer');
	
	// Handle the click event
	$('.accordion li:even').click( function() {
		// Get the content that needs to be shown
		var cur = $(this).next();
		
		// Get the content that needs to be hidden
		var old = $('.accordion li:odd:visible');
		
		// Make sure the content that needs to be shown 
		// isn't already visible
		if ( cur.is(':visible') )
			return false;
		
		// Hide the old content
		old.slideToggle(500);
		
		// Show the new content
		cur.stop().slideToggle(500);
		
		// Animate (add) the padding in the new link
		$(this).stop().animate( {
			paddingLeft:"15px"
		} );
		
		// Animate (remove) the padding in the old link
		old.prev().stop().animate( {
			paddingLeft:"10px"
		} );
	} );
});