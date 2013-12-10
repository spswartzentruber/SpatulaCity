// JavaScript Document
$(document).ready(function(){
	$('#login').submit(function( event ) {
		 // Stop form from submitting normally
		event.preventDefault();
		
		// Send the data using post
		var $form = $( this ),
		url = $form.attr( "action" );
		
		var posting = $.post( url , $form.serialize() );
		
		posting.done(function( data ) {
			$( "#login_div" ).empty().append( data );
//			alert(data);
		});
		
//		location.reload();
	});
	
	$('#logout').click(function( event ) {
		event.preventDefault();
//		alert('logout clicked');
		
		var $form = $( this ),
		url = $form.attr( "action" );
		
		var posting = $.post( url , { logout : 1 } );

		posting.done(function( data ) {
			$( "#login_div" ).empty().append( data );
//			alert(data);
		});
	});

});
