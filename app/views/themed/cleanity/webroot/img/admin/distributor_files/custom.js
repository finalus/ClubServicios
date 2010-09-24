$(document).ready(function() {
	$('a[rel*=facebox]').facebox() 
	
	$('a.toggle').click(function() {
		$('#producto_'+$(this).attr('rel')).toggle();
		return false;
	})
})
