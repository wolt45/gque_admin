/* COMPOSE */

function init_compose() {

	if( typeof ($.fn.slideToggle) === 'undefined'){ return; }
	console.log('init_compose');

	$('#compose, .compose-close').click(function(){
		$('.compose').slideToggle();
	});

};

$(document).ready(function() {
			
	init_compose();
			
});	