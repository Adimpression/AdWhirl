var errorObj = function() {
	var hasError = false;
	var decRegExp  =  /^\d\d?\d?$/;
	var me = {
		hasError: function() {
		  return hasError;
		},
		reset: function() {
		  hasError=false;
		  $('.error').remove();
		},
		attacheError: function (elem, msg) {
		  $(elem).parent().append($("<span class='error'>"+msg+"</span>"));
		  hasError|=true;
		},
		testPercent: function(val, elem) {
			if (val=="") {
				me.attacheError(elem, "<br/>This field is required.");
			} else if (val.indexOf(".")>=0) {
				me.attacheError(elem, "<br/>Only integers are allowed.");				
			} else if (val.indexOf("-")>=0) {
				me.attacheError(elem, "<br/>Negative numbers are not allowed.");
			} else if (!decRegExp.test(val)) {
				errorObj.attacheError(elem, "<br/>This is not a valid number.");
			} else if (val>100) {
				errorObj.attacheError(elem, "<br/>Can't be greater than 100%.");
			}			
		}
	};
	return me;
}();
// function attacheError(elem, msg) {
// 	$(elem).parent().append($("<span class='error'>"+msg+"</span>"))	
// }
