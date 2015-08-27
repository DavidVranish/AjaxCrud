var AjaxCrud = function (config) {

	var form = config.form; //$("form#create")
	var table = config.table; //$("table#customers")
	var createInitButton = config.createInitButton //$('button.js-create-init')
	var sortButton = config.sortButton;
	var cancelSortButton = config.cancelSortButton;
	var formValidation;
	var dataTable = table.DataTable(config.datatableArgs);
	var sortMode = 'view';
	var urls = {
		urlAjaxRowGet: config.urls.urlAjaxRowGet,
		urlAjaxRowPut: config.urls.urlAjaxRowPut,
		urlAjaxEditableRowGet: config.urls.urlAjaxEditableRowGet,
		urlAjaxNewRowGet: config.urls.urlAjaxNewRowGet,
		urlAjaxRowDelete: config.urls.urlAjaxRowDelete,
		urlAjaxRowsPost: config.urls.urlAjaxRowsPost,
		urlAjaxModalPut: config.urls.urlAjaxModalPut,
		urlAjaxModalPost: config.urls.urlAjaxModalPost,
		urlAjaxNewModalGet: config.urls.urlAjaxNewModalGet,
		urlAjaxEditableModalGet: config.urls.urlAjaxEditableModalGet,
		urlAjaxSortPut: config.urls.urlAjaxSortPut
	};

	table.on( 'draw.dt', function () {
        table.find('tbody td[data-colspan]').each(function (index, element) {
            var colspan = $(element).attr('data-colspan');
            if($(element).attr('colspan') != colspan) {
            	for(i = 0; i < (colspan - 1); i++) {
	                $(element).next().remove();
	            }
	            $(element).attr('colspan', colspan);	
            }
            
        });

        if (typeof(dataTable) != "undefined" && typeof(sortButton) != "undefined") {
	        if($.trim(dataTable.search()) == "") {
	        	sortButton.show();
	        } else {
	        	sortButton.hide();
	        }
	    }
    });

	dataTable.draw();

	table.find('tbody td').each( function (index, element) {
		if (typeof($(element).attr('data-order')) == 'undefined' || $(element).attr('data-order').length < 1) {
			$(element).attr('data-order', $(element).text());
		}
	});

	if (typeof(form) != "undefined") {
		formValidation = form.data('formValidation');
		
		if ($('[id='+form.attr('id')+']').length > 1) {
 			console.warn('Form Validation will have problems, duplicate IDs detected!');
 		}
		
	}

	if (typeof(config.hooks) == "undefined") {
		config.hooks = {};
	}

	if (typeof(config.filters) == "undefined") {
		config.filters = {};
	}

	function revertColspan($row) {
		$row.find('td[data-colspan]').each(function (index, element) {
			var $elem = $(element);
			var colspan = $elem.attr('data-colspan');

			if($elem.attr('colspan') == colspan) {
				var elemIndex = dataTable.cell($elem).index().column;
				var rowData = dataTable.row($row).data();
				$elem.attr('colspan', '');

				for(i = 0; i < (colspan - 1); i++) {
					elemIndex++;
					$elem.after('<td>' + rowData[elemIndex] + '</td>');
					$elem = $elem.next();
				}
			}
		});
	}

	function applyHook(hook, args) {
		if(!(typeof(config.hooks[hook]) == "undefined")) {
			config.hooks[hook](args);
		}
	}

	function applyFilter(filter, def, args) {
		if(!(typeof(config.filters[filter]) == "undefined")) {
			return config.filters[filter](def, args);
		} else {
			return def;
		}
	}

	function focusInput($row) {
		$input = $row.find('.focus-field');
		if($input.hasClass('select2')) {
			$input.select2('open');
		} else {
			$input.focus();
 		}
 	}

	function activateJs($parent) {
		if(typeof(App) != "undefined" && typeof(App.activate) != "undefined") {
			App.activate($parent);
		}
	}
	if (typeof(form) != "undefined") {
		form.on('changeDate', '.value-field', function(event) {
			if(typeof(formValidation) != "undefined") {
				formValidation.revalidateField($(event.target));
			}
		});
	}

	table.on('click', '.ads-edit-row', function (event) {
		event.preventDefault();
		editRow(event);

	});

	table.on('click', '.ads-prep-del-row', function (event) {
		event.preventDefault();
		prepareDeleteRow(event);

	});

	table.on('click', '.ads-delete', function (event) {
		event.preventDefault();
		deleteRow(event);

	});

	table.on('click', '.ads-save-row', function (event) {
		event.preventDefault();
		saveRow(event);

	});

	table.on('keypress', 'input', function (event) {
		// Make the default action of hitting enter to attempt to save the current row
		if (event.which == '13') {
			var $row = $(event.target).closest('tr');
			event.preventDefault();
			if ($row.attr('data-id') > 0)
				saveRow(event);
			else
				addNewRow(event);
			return false;			
		}
	});

	table.on('click', '.ads-cancel-row', function (event) {
		event.preventDefault();
		cancelRow(event);

	});

	table.on('click', '.ads-del-new-row', function (event) {
		event.preventDefault();
		deleteNewRow(event);

	});

	table.on('click', '.ads-add-new-row', function (event) {
		event.preventDefault();
		addNewRow(event);

	});

	table.on('click', '.ads-save-new-rows', function (event) {
		event.preventDefault();
		saveNewRows(event);

	});

	table.on('click', '.ads-edit-modal', function (event) {
		event.preventDefault();
		editModal(event);
		$('#edit-modal').off();
		$('#edit-modal').on('click', '.ads-save-modal', function (event) {
			event.preventDefault();
			saveModal(event);

		});
		$('#edit-modal').on('click', '.md-close', function (event) {
			event.preventDefault();
			$("#edit-modal").niftyModal("hide");

		});
	});

	if (typeof(createInitButton) != "undefined") {
		createInitButton.click(function (event) {
			event.preventDefault();
			if($(event.target).hasClass('new-modal')) {			
				addNewModal(event);
				$('#new-modal').off();
				$('#new-modal').on('click', '.ads-save-new-modal', function (event) {
					event.preventDefault();
					saveNewModal(event);
				});
				$('#new-modal').on('click', '.md-close', function (event) {
					event.preventDefault();
					$('#new-modal').niftyModal("hide");
				});
			} else {
				createInitButton.hide();
				table.find('tfoot tr:last').show();
				addNewRow(event);	
			}
		});
	}

	if (typeof(sortButton) != "undefined") {
		sortButton.click(function (event) {
			event.preventDefault();
			sortRows(event);
		});
	}

	if (typeof(cancelSortButton) != "undefined") {
		cancelSortButton.click(function (event) {
			event.preventDefault();
			cancelSortRows(event);
		});
	}
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
	function editModal(event) {
		var $row = $(event.target).closest('tr');
		var $modal = $('#edit-modal');
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

		});

		request.fail(function( jqXHR, textStatus ) {
			$.unblockUI();
			alert( "Request failed: " + textStatus );

		});


	}

	function addNewModal(event) {
		var $modal = $('#new-modal');

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

		});

		request.fail(function( jqXHR, textStatus ) {
			$.unblockUI();
			alert( "Request failed: " + textStatus );

		});

	}
	function sortRows (event) {
		//If button is 'Change Sort Order'
		if(sortMode == "view") {
			table.prev().find('.dataTables_filter').hide();

			//Resets the DataTable sort to use the sort_order database field
			var columnIndex = applyFilter('sortRowsColumnIndex', 0, {});
			dataTable.order([columnIndex, 'asc']).draw();

			//Updates the wording of the 'Change Sort Order' button and updates table styles to give a visual indicator
			sortMode = "sort";
			sortButton.html('Save Sort Order');
			if(typeof(cancelSortButton) != "undefined") {
				cancelSortButton.show();
			}

			//Fix for Chrome styling error
			table.find('tbody').addClass('sort-body');
			table.find('tbody tr td:last-of-type').addClass('sort-column');

			//Sets the table body as sortable, disables selection of text and enables sorting
			table.find('tbody').sortable({
				helper: function(e, tr) {
				    var $originals = tr.children();
				    var $helper = tr.clone();
				    $helper.children().each(function (index, element)
				    {
				      // Set helper cell sizes to match the original sizes
				      $(element).width($originals.eq(index).width());
				    });
					$helper.find(".sort-column").removeClass('sort-column');

				    return $helper;
			  	}
			});
			table.find('tbody').sortable("enable");
			table.find('tbody').disableSelection();

			table.on('order.dt', disableSorting);

		//If button is 'Save Sort Order'
		} else {
			table.prev().find('.dataTables_filter').show();
			//Reverts the text of the 'Save Sort Order' button and the table styles
			sortMode = "view";
			sortButton.html('Change Sort Order');
			if(typeof(cancelSortButton) != "undefined") {
				cancelSortButton.hide();	
			}
			

			table.find('tbody').removeClass('sort-body');
			table.find('tbody tr td:last-of-type').removeClass('sort-column');


			//Disables sorting and enables text selection
			table.find('tbody').sortable("disable");
			table.find('tbody').enableSelection();

			//Gets an array of ids in the order the user sorted them
			//Gets an array of ids in the order the user sorted them
			var sortable = table.find('tbody').sortable( "instance" );
			var ids = sortable.toArray({attribute: "data-id"});
			var data = applyFilter('sortRowsDataFilter', {ids: ids}, {sortable: sortable});

			$.blockUI({ message: '' });

			var request = $.ajax({
				method: "PUT",
				url: urls.urlAjaxSortPut,
				cache: false,
				data: data
				});

			request.done(function( html ) {
				$.unblockUI();
				applyHook('sortRowsRequestDone', {});
				table.off('order.dt', disableSorting);
			});

			request.fail(function( jqXHR, textStatus ) {
				$.unblockUI();
				alert( "Request failed: " + textStatus );

			});

		}
	}

	function cancelSortRows (event) {
		sortMode = "view";
		sortButton.html('Change Sort Order');
		cancelSortButton.hide();
		table.prev().find('.dataTables_filter').show();

		table.find('tbody').sortable("destroy");
		table.find('tbody').enableSelection();
		table.find('tbody').removeClass('sort-body');
		table.find('tbody tr td:last-of-type').removeClass('sort-column');

		var columnIndex = applyFilter('sortRowsColumnIndex', 0, {});
		dataTable.order([columnIndex, 'asc']).draw();
		table.off('order.dt', disableSorting);
	}

	function disableSorting() {
		var columnIndex = applyFilter('sortRowsColumnIndex', 0, {});
		 
		var order = dataTable.order();
		if(order.length == 1) {
			if(order[0][0] != columnIndex || order[0][1] != 'asc') {
				dataTable.order([[columnIndex, "asc"]]).draw();
			}
		} else {
			dataTable.order([[columnIndex, "asc"]]).draw();
		}
	}
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
	return {
		form: form,
		table: table,
		createInitButton: createInitButton,
		formValidation: formValidation,
		dataTable: dataTable,
		urls: urls
	}
}