$('.updown ul li').click(function(e){ 
	e.preventDefault(); //Prevents browser from loading href in window 
 
	//Switch with menu item is active 
	$('.updown ul li').removeClass('current'); 
	$(this).addClass('current'); 
 
	//Switch to appropiate index 
	var index = $(this).children('a').attr('href'); 
	$('".updownbody ul li').removeClass('current'); 
	$(".updownbody ul li:nth-child("+index+")").addClass('current'); 
}); 