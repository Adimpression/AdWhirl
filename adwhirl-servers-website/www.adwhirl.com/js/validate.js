function checkEmail (strng) {
	var error="";
	if (strng == "") {
	   error = "You didn't enter an email address.>";
	}
	
	    var emailFilter=/^.+@.+\..{2,4}$/;
	    if (!(emailFilter.test(strng))) { 
	       error = "Please enter a valid email address.";
	    }
	    else {
	//test email for illegal characters
	       var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/
	         if (strng.match(illegalChars)) {
	          error = "The email address contains illegal characters.";
	       }
	    }
	return error;    
}

// password - greater than 6 chars

function checkPassword (strng) {
var error = "";
if (strng == "") {
   error = "You didn't enter a password.\n";
}

    var illegalChars = /[\W_]/; // allow only letters and numbers
    
    if (strng.length < 6) {
       error = "The password needs to be at least 6 characters long.<br/>";
    }
    else if (illegalChars.test(strng)) {
      error = "The password contains illegal characters.<br/>";
    }
return error;    
}