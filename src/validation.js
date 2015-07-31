	function validationIsValid($container, recursive) {
		if(typeof(recursive) == "undefined") {
			recursive = false;
		}

		if(typeof(formValidation) == "undefined") {
			return true;

		} else {
			// Validate the container
			if(recursive === false) {
				formValidation.validateContainer($container);	
			}
			var isValidContainer = formValidation.isValidContainer($container);
			if (isValidContainer === false || isValidContainer === null) {
			    if (isValidContainer === false)
			    	$.unblockUI();
			    
			    // Stop submission because of validation error.
			    return isValidContainer;

			} else {
				return true;
				
			}
		}
		
	}

	function validationAddFields($fields) {
		if(typeof(formValidation) != "undefined") {
			$fields.each( function (index, element) {
				formValidation.addField($(element));
			});
		}
	}

	function validationResetFields($fields) {
		if(typeof(formValidation) != "undefined") {
			$fields.each( function (index, element) {
				formValidation.resetField($(element));
			});
		}
	}