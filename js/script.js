jQuery(document).ready(function() {

  if( (xconnect.userLoggedIn === "" ) ) {

   jQuery('.lightbox').show();
  
  } else {
  
   jQuery('.lightbox').hide();
 }

 jQuery( "#join-email-btn" ).click(function() {
  			//alert( "Handler for .click() called." );
  			jQuery('.login-popup').hide().fadeOut();
  			jQuery('.registration-popup').fadeIn();

  		});

 jQuery( ".signup-link" ).click(function() {
  		//alert( "Handler for .click() called." );
  		jQuery('.signin').hide().fadeOut();
  		jQuery('.login-popup').fadeIn();

  	});

 jQuery( ".signin-link" ).click(function() {
  		//alert( "Handler for .click() called." );
  		jQuery('.login-popup').hide().fadeOut();
  		jQuery('.signin').fadeIn();

  	});


 jQuery( ".registration-close" ).click(function() {
  		//alert( "Handler for .click() called." );
  		jQuery('.login-popup').fadeIn();
  		jQuery('.registration-popup').hide();

  	});

 jQuery( ".signin-close" ).click(function() {
  		//alert( "Handler for .click() called." );
  		jQuery('.login-popup').fadeIn();
  		jQuery('.signin').hide();

  	});

});