$(function(){	
	var navSelector = "ul#menu li";/** define the main navigation selector **/

	/** set up rounded corners for the selected elements **/
	$('.box-container').corners("5px bottom");
	 $('.box h4').corners("5px top");
	 $('ul.tab-menu li a').corners("5px top");
	 $('textarea#wysiwyg').wysiwyg();
	 $("div#sys-messages-container a, div#to-do-list ul li a").colorbox({fixedWidth:"50%", transitionSpeed:"100", inline:true, href:"#sample-modal"}); /** jquery colorbox modal boxes for system
	 messages and to-do list - see colorbox help docs for help: http://colorpowered.com/colorbox/ **/

	$('#to-do').tabs();		 
	$("#calendar").datepicker();/** jquery ui calendar/date picker - see jquery ui docs for help: http://jqueryui.com/demos/ **/
	$("ul.list-links").accordion();/** side menu accordion - see jquery ui docs for help:  http://jqueryui.com/demos/  **/

 
	jQuery(navSelector).find('a').css( {backgroundPosition: "0 0"} );
	
	jQuery(navSelector).hover(function(){/** build animated dropdown navigation **/
		jQuery(this).find('ul:first:hidden').css({visibility: "visible",display: "none"}).show("fast");
		jQuery(this).find('a').stop().animate({backgroundPosition:"(0 -40px)"},{duration:150});
 	   jQuery(this).find('a.top-level').addClass("blue");
		},function(){
		jQuery(this).find('ul:first').css({visibility: "hidden", display:"none"});
		jQuery(this).find('a').stop().animate({backgroundPosition:"(0 0)"}, {duration:75});
		jQuery(this).find('a.top-level').removeClass("blue");
		});
	});