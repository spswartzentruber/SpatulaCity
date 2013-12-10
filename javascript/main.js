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
			$( "#login_div" ).empty().append( content );
		});
		
//		location.reload();
	});
	
	$('#logout').click(function( event ) {
//		alert('logout clicked');
		$.post( 'login.php' , { logout : 1 } );
//		location.reload();

		posting.done(function( data ) {
			$( "#login_div" ).empty().append( content );
		});
	});

});
