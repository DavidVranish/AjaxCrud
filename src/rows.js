	function saveRow (event, recursive) {
		if(typeof(recursive) == "undefined") {
			recursive = false;
		}
		
		// FormValidation instance
	    var $row = $(event.target).closest('tr');

		if (recursive == false) {
			$.blockUI({ message: '' });
		}

		if (validationIsValid($row, recursive) == null) {
		    // Stop submission because of validation error.
		    setTimeout(function() {saveRow(event, true)}, 150);
		    return false;

		} else if (validationIsValid($row, recursive) == false) {
			return false;
			
		}
		
		var id = applyFilter('saveRowId', $row.attr('data-id'), {'$row': $row});
		
		var data = {};
		$row.find('.value-field').each(function (index, element) {
			if (!($(element).is(':checkbox')) || $(element).is(':checked')) {			
				data[$(element).attr('name')] = $(element).val();
			}
		});

		data = applyFilter('saveRowData', data, {'$row': $row, 'id': id});;

		var url = applyFilter('saveRowUrl', urls.urlAjaxRowPut.replace("_id_", id), {'$row': $row, 'id': id, 'data': data});

		var request = $.ajax({
			method: "PUT",
			url: url,
			cache: false,
			data: data
			});

		request.done(function( html ) {
			validationResetFields($row.find('.value-field'));
			var $rowIndex = dataTable.row($row).index();
	
			revertColspan($row);
	
			$html = $($.parseHTML($.trim(html)));
			$dRow = dataTable.row.add($html);

			dataTable.context[0].aoData[$rowIndex] = dataTable.context[0].aoData[$dRow.index()];

			$dRow.remove();
			dataTable.draw(false);

			$row = $(dataTable.row($rowIndex).node());

			applyHook('saveRowRequestDone', {"$row": $row});

			activateJs($row);
			$.unblockUI();

		});

		request.fail(function( jqXHR, textStatus ) {
			$.unblockUI();
			alert( "Request failed: " + textStatus );

		});

	}

	function editRow (event) {
		var $row = $(event.target).closest('tr');
		var id = $row.attr('data-id');
		var data = {};

		data = applyFilter('editRowDataFilter', data, {'$row': $row});
		$.blockUI({ message: '' });

		var request = $.ajax({
			method: "GET",
			url: urls.urlAjaxEditableRowGet.replace("_id_", id),
			data: data,
			cache: false
		});

		request.done(function( html ) {
			var $row = $(event.target).closest('tr');
			var $rowIndex = dataTable.row($row).index();
	
			validationResetFields($row.find('.value-field'));
	
			$html = $($.parseHTML($.trim(html)));
			$dRow = dataTable.row.add($html);

			dataTable.context[0].aoData[$rowIndex]._aData = dataTable.context[0].aoData[$dRow.index()]._aData;
			dataTable.context[0].aoData[$rowIndex].anCells = dataTable.context[0].aoData[$dRow.index()].anCells;
			dataTable.context[0].aoData[$rowIndex].nTr = dataTable.context[0].aoData[$dRow.index()].nTr;

			$dRow.remove();
			dataTable.draw(false);

			$row = $(dataTable.row($rowIndex).node());

			activateJs($row);

			validationAddFields($row.find('.value-field'));

			focusInput($row);

			applyHook('editRowRequestDone', {"$row": $row});

			$.unblockUI();
		});

		request.fail(function( jqXHR, textStatus ) {
			$.unblockUI();
			alert( "Request failed: " + textStatus );

		});

	}

	function cancelRow (event) {
		var $row = $(event.target).closest('tr');
		var id = $row.attr('data-id');
		var data = {};

		data = applyFilter('cancelRowDataFilter', data, {'$row': $row});

		$.blockUI({ message: '' });

		var request = $.ajax({
			method: "GET",
			url: urls.urlAjaxRowGet.replace("_id_", id),
			data: data,
			cache: false
			});

		request.done(function( html ) {
			var $rowIndex = dataTable.row($row).index();
			
			validationResetFields($row.find('.value-field'));

			revertColspan($row);
			$html = $($.parseHTML($.trim(html)));
			$dRow = dataTable.row.add($html);

			dataTable.context[0].aoData[$rowIndex]._aData = dataTable.context[0].aoData[$dRow.index()]._aData;
			dataTable.context[0].aoData[$rowIndex].anCells = dataTable.context[0].aoData[$dRow.index()].anCells;
			dataTable.context[0].aoData[$rowIndex].nTr = dataTable.context[0].aoData[$dRow.index()].nTr;

			$dRow.remove();
			dataTable.draw(false);

			$row = $(dataTable.row($rowIndex).node());

			activateJs($row);

			applyHook('cancelRowRequestDone', {"$row": $row});

			$.unblockUI();

		});

		request.fail(function( jqXHR, textStatus ) {
			$.unblockUI();
			alert( "Request failed: " + textStatus );

		});

	}

	function deleteRow (event) {
		var id = $(event.target).attr('data-id');

		if(typeof(id) == "undefined") {
			id = $(event.target).closest('a').attr('data-id');
		}
		
		var data = {};
		data = applyFilter('deleteRowDataFilter', data, {'id': id, event: event});

		$.blockUI({ message: '' });

		var request = $.ajax({
			method: "DELETE",
			url: urls.urlAjaxRowDelete.replace("_id_", id),
			data: data,
			cache: false
		});

		request.done(function( html ) {
			var $row = applyFilter('deleteRowRowFilter', table.find('tr[data-id="' + id + '"]'), {'id': id, event: event});
			dataTable.row($row)
        		.remove()
        		.draw(false);

			$.unblockUI();

		});

		request.fail(function( jqXHR, textStatus ) {
			$.unblockUI();
			alert( "Request failed: " + textStatus );

		});
	}

	function prepareDeleteRow (event) {
		var $row = $(event.target).closest('tr');
		var id = $row.attr('data-id');

		var headerText = $.trim($row.find('.delete-header').text());
		var bodyText = $.trim($row.find('.delete-body').text());
		$('#delete-modal .header-text').text('Remove: ' + headerText);
		$('#delete-modal .body-text').text(bodyText + ' will be removed. Ok to proceed?');

		$("#delete-modal .ads-delete").attr('data-id', id);

		//$( "body" ).off( "click", "#delete-modal .ads-delete");
		$("#delete-modal .ads-delete").off('click.delete');

		applyHook('prepareDeleteRowDone', {'$row': $row});

		$("#delete-modal .ads-delete").on('click.delete', function (event) { 
			deleteRow (event);
		});
	}

	function addNewRow (event) {
		var $row = table.find('tfoot tr:last');

		var data = {};
		data = applyFilter('addNewRowDataFilter', data, {'event': event});

		$.blockUI({ message: '' });

		var request = $.ajax({
			method: "GET",
			url: urls.urlAjaxNewRowGet,
			data: data,
			cache: false
		});

		request.done(function( html ) {
			$row.before(html);
			$row = $row.prev();

			activateJs($row);

			validationAddFields($row.find('.value-field'));

			focusInput($row);

			applyHook('addNewRowRequestDone', {"$row": $row,});

			//validationIsValid($row);

			$.unblockUI();
		});

		request.fail(function( jqXHR, textStatus ) {
			$.unblockUI();
			alert( "Request failed: " + textStatus );
		});

	}

	function saveNewRows (event, recursive) {
		if(typeof(recursive) == "undefined") {
			recursive = false;
		}

		var $tfoot = table.find('tfoot');
		
		if (recursive == false) {
			$.blockUI({ message: '' });
		}

		if (validationIsValid($tfoot, recursive) == null) {
		    // Stop submission because of validation error.
		    setTimeout(function() {saveRow(event, true)}, 150);
		    return false;

		} else if (validationIsValid($tfoot, recursive) == false) {
			return false;
			
		}

		$.blockUI({ message: '' });

		var newRowsData = {};
		table.find('tfoot tr.edit-row').each(function (index, element) {
			var newRowData = {};
			$(element).find('.value-field').each(function (index, element) {
				if (!$(element).is(':checkbox') || $(element).is(':checked')) {			
					newRowData[$(element).attr('name')] = $(element).val();
				}
			});
			newRowsData[index] = newRowData;

		});

		var request = $.ajax({
			method: "POST",
			url: urls.urlAjaxRowsPost,
			data: {
				newRows: newRowsData
			},
			cache: false
		});

		request.done(function( html ) {
			table.find('tfoot tr.edit-row').each( function (index, element) {
				validationResetFields($(element).find('.value-field'));
			});

			table.find('tfoot tr.edit-row').remove();

			table.find('tr:last').hide();
			if(typeof(createInitButton) != "undefined") {
				createInitButton.show();	
			}

			var newRows = $.parseHTML($.trim(html));

			$(newRows).each( function (index, element) {
				if($(element).context.nodeName != "#text") {
					var row = dataTable.row.add($(element)).draw(false).node();
					activateJs($(row));
				}
			});

			//Hook Call
			applyHook('saveNewRowsRequestDone', {"table": table});

			$.unblockUI();

		});

		request.fail(function( jqXHR, textStatus ) {
			$.unblockUI();
			alert( "Request failed: " + textStatus );

		});
	}

	function deleteNewRow (event) {
		var $row = $(event.target).closest('tr');
		var $rowIndex = dataTable.row($row).index();

		validationResetFields($row.find('.value-field'));

		// dataTable.row($rowIndex).remove().draw(false);
		$row.remove();

		//Checks to see if there are any added rows, if not hide the submit button
		if(table.find('tfoot tr').size() == 1) {
			table.find('tfoot tr:last').hide();
			
			if(typeof(createInitButton) != "undefined") {
				createInitButton.show();	
			}
		}

		applyHook('deleteNewRowDone', {"table": table});
	}