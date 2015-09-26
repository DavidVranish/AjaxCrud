	function editModal(event) {
		var $row = $(event.target).closest('tr');
		var $modal = $('#edit-modal');

		applyHook('editModalStart', {"$modal": $modal, "$row": $row});

		var id = $row.attr('data-id');

		$.blockUI({ message: '' });

		var request = $.ajax({
			method: "GET",
			url: urls.urlAjaxEditableModalGet.replace("_id_", id),
			cache: false
		});

		request.done(function( html ) {
			$modal.html(html);
	
			validationResetFields($modal.find('.value-field'));

			activateJs($modal);

			$modal.trigger('create');

			validationAddFields($modal.find('.value-field'));

			focusInput($modal);

			applyHook('editModalRequestDone', {"$modal": $modal});

			$.unblockUI();
		});

		request.fail(function( jqXHR, textStatus ) {
			$.unblockUI();
			alert( "Request failed: " + textStatus );

		});
	}

	function saveModal(event, recursive) {
		if(typeof(recursive) == "undefined") {
			recursive = false;
		}
		
		// FormValidation instance
	    var $modal = $('#edit-modal');

	    applyHook('saveModalStart', {"$modal": $modal});

		if (recursive == false) {
			$.blockUI({ message: '' });
		}

		if (validationIsValid($modal, recursive) == null) {
		    // Stop submission because of validation error.
		    setTimeout(function() {saveModal(event, true)}, 150);
		    return false;

		} else if (validationIsValid($modal, recursive) == false) {
			return false;
			
		}

		var id = $(event.target).attr('data-id');
		var $row = table.find('tr[data-id="' + id + '"]');
		var putData = {};
		$modal.find('.value-field').each(function (index, element) {
			if (!($(element).is(':checkbox')) || $(element).is(':checked')) {			
				putData[$(element).attr('name')] = $(element).val();
			}
		});

		var request = $.ajax({
			method: "PUT",
			url: urls.urlAjaxModalPut.replace("_id_", id),
			cache: false,
			data: putData
			});

		request.done(function( html ) {
			validationResetFields($modal.find('.value-field'));
			
			var $rowIndex = dataTable.row($row).index();
	
			$html = $($.parseHTML($.trim(html)));
			$dRow = dataTable.row.add($html);

			dataTable.context[0].aoData[$rowIndex] = dataTable.context[0].aoData[$dRow.index()];

			$dRow.remove();
			dataTable.draw(false);

			$row = $(dataTable.row($rowIndex).node());

			$("#edit-modal").niftyModal("hide");

			applyHook('saveModalRequestDone', {"$modal": $modal});

			activateJs($row);
			$.unblockUI();

			applyHook('saveModalRequestLast', {"$modal": $modal});
		});

		request.fail(function( jqXHR, textStatus ) {
			$.unblockUI();
			alert( "Request failed: " + textStatus );

		});


	}

	function addNewModal(event) {
		var $modal = $('#new-modal');

		applyHook('addNewModalStart', {"$modal": $modal});

		$.blockUI({ message: '' });

		var request = $.ajax({
			method: "GET",
			url: urls.urlAjaxNewModalGet,
			cache: false
		});

		request.done(function( html ) {
			$modal.html(html);

			activateJs($modal);

			validationAddFields($modal.find('.value-field'));

			focusInput($modal);

			applyHook('addNewModalRequestDone', {"$modal": $modal});

			$.unblockUI();
		});

		request.fail(function( jqXHR, textStatus ) {
			$.unblockUI();
			alert( "Request failed: " + textStatus );
		});
	}

	function saveNewModal(event, recursive) {
		if(typeof(recursive) == "undefined") {
			recursive = false;
		}

		var $modal = $('#new-modal');
		
		applyHook('saveNewModalStart', {"$modal": $modal});

		if (recursive == false) {
			$.blockUI({ message: '' });
		}

		if (validationIsValid($modal, recursive) == null) {
		    // Stop submission because of validation error.
		    setTimeout(function() {saveNewModal(event, true)}, 150);
		    return false;

		} else if (validationIsValid($modal, recursive) == false) {
			return false;
			
		}

		$.blockUI({ message: '' });

		var putData = {};
		$modal.find('.value-field').each(function (index, element) {
			if (!($(element).is(':checkbox')) || $(element).is(':checked')) {			
				putData[$(element).attr('name')] = $(element).val();
			}
		});

		var request = $.ajax({
			method: "POST",
			url: urls.urlAjaxModalPost,
			data: putData,
			cache: false
		});

		request.done(function( html ) {
			$modal.each( function (index, element) {
				validationResetFields($(element).find('.value-field'));
			});

			var newRows = $.parseHTML($.trim(html));
			var rows = [];

			$(newRows).each( function (index, element) {
				if($(element).context.nodeName != "#text") {
					var row = dataTable.row.add($(element)).draw(false).node();
					rows[index] = $(row);
					activateJs($(row));
				}
			});

			$("#new-modal").niftyModal("hide");

			//Hook Call
			applyHook('saveNewModalRequestDone', {"$modal": $modal, "rows": rows});

			$.unblockUI();

			applyHook('saveNewModalRequestLast', {"$modal": $modal, "rows": rows});
		});

		request.fail(function( jqXHR, textStatus ) {
			$.unblockUI();
			alert( "Request failed: " + textStatus );

		});

	}