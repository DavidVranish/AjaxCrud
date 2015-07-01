var AjaxCrud = function (config) {

	var form = config.form; //$("form#create")
	var table = config.table; //$("table#customers")
	var createInitButton = config.createInitButton //$('button.js-create-init')
	var formValidation;
	if (typeof(form) != "undefined") {
		formValidation = form.data('formValidation');
		
		if ($('[id='+form.attr('id')+']').length > 1) {
 			console.warn('Form Validation will have problems, duplicate IDs detected!');
 		}
		
	}
	var dataTable = table.DataTable(config.datatableArgs);

	var urls = {
		urlAjaxRowGet: config.urls.urlAjaxRowGet,
		urlAjaxRowPut: config.urls.urlAjaxRowPut,
		urlAjaxEditableRowGet: config.urls.urlAjaxEditableRowGet,
		urlAjaxNewRowGet: config.urls.urlAjaxNewRowGet,
		urlAjaxRowDelete: config.urls.urlAjaxRowDelete,
		urlAjaxRowsPost: config.urls.urlAjaxRowsPost,
		urlAjaxSortPut: ""
	};	

	table.find('tbody td').each( function() {
		if (typeof($(this).attr('data-order')) == 'undefined' || $(this).attr('data-order').length < 1) {
			$(this).attr('data-order', $(this).text());
		}
	});

	form.on('changeDate', '.value-field', function(event) {
		if(typeof(formValidation) != "undefined") {
			formValidation.revalidateField($(this));
		}
	});

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

	if (typeof(createInitButton) != "undefined") {
		createInitButton.click(function (event) {
			event.preventDefault();
			$(event.target).hide();
			table.find('tfoot tr:last').show();
			addNewRow();

		});
	}

	if (typeof(config.hooks) == "undefined") {
		config.hooks = {};
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

		var id = $row.attr('data-id');
		
		var putData = {};
		$row.find('.value-field').each( function() {
			if (!($(this).is(':checkbox')) || $(this).is(':checked')) {			
				putData[$(this).attr('name')] = $(this).val();
			}
		});

		var request = $.ajax({
			method: "PUT",
			url: urls.urlAjaxRowPut.replace("_id_", id),
			cache: false,
			data: putData
			});

		request.done(function( html ) {
			validationResetFields($row.find('.value-field'));
			var $rowIndex = dataTable.row($row).index();
	
			validationResetFields($row.find('.value-field'));
	
			$html = $($.parseHTML($.trim(html)));
			
			$dRow = dataTable.row.add($html);
			$data = $dRow.data();
			$dRow.remove();
			
			dataTable.row($rowIndex).data($data).draw(false);

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

		$.blockUI({ message: '' });

		var request = $.ajax({
			method: "GET",
			url: urls.urlAjaxEditableRowGet.replace("_id_", id),
			cache: false
		});

		request.done(function( html ) {
			var $row = $(event.target).closest('tr');
			var $rowIndex = dataTable.row($row).index();
	
			validationResetFields($row.find('.value-field'));
	
			$html = $($.parseHTML($.trim(html)));
			
			$dRow = dataTable.row.add($html);
			$data = $dRow.data();
			$dRow.remove();
			
			dataTable.row($rowIndex).data($data).draw(false);

			activateJs($row);

			validationAddFields($row.find('.value-field'));

			$row.find( "input.focus-field, select.focus-field, textarea.focus-field" ).focus();

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

		$.blockUI({ message: '' });

		var request = $.ajax({
			method: "GET",
			url: urls.urlAjaxRowGet.replace("_id_", id),
			cache: false
			});

		request.done(function( html ) {
			var $rowIndex = dataTable.row($row).index();
			
			validationResetFields($row.find('.value-field'));

			$html = $($.parseHTML($.trim(html)));
			
			$dRow = dataTable.row.add($html);
			$data = $dRow.data();
			$dRow.remove();
			
			dataTable.row($rowIndex).data($data).draw(false);

			// $row = $(html).replaceAll($row);

			activateJs($row);

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
		
		$.blockUI({ message: '' });

		var request = $.ajax({
			method: "DELETE",
			url: urls.urlAjaxRowDelete.replace("_id_", id),
			cache: false
		});

		request.done(function( html ) {
			dataTable.row(table.find('tr[data-id="' + id + '"]'))
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
		$("#delete-modal .ads-delete").on('click.delete', function (event) { 
			deleteRow (event);
		});
	}

	function addNewRow (event) {
		var $row = table.find('tfoot tr:last');

		$.blockUI({ message: '' });

		var request = $.ajax({
			method: "GET",
			url: urls.urlAjaxNewRowGet,
			cache: false
		});

		request.done(function( html ) {
			$row.before(html);
			$row = $row.prev();

			activateJs($row);

			validationAddFields($row.find('.value-field'));

			$row.find( "input.focus-field, select.focus-field, textarea.focus-field" ).focus();

			applyHook('addNewRowRequestDone', {"$row": $row});

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
		table.find('tfoot tr.edit-row').each(function(index, element) {
			var newRowData = {};
			$(this).find('.value-field').each(function (index, element) {
				if (!$(this).is(':checkbox') || $(this).is(':checked')) {			
					newRowData[$(this).attr('name')] = $(this).val();
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
				validationResetFields($(this).find('.value-field'));
			});

			table.find('tfoot tr.edit-row').remove();

			table.find('tr:last').hide();
			if(typeof(createInitButton) != "undefined") {
				createInitButton.show();	
			}

			var newRows = $.parseHTML($.trim(html));

			$(newRows).each( function (index, element) {
				if($(this).context.nodeName != "#text") {
					var row = dataTable.row.add($(this)).draw(false).node();
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

		console.log('a');

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

	function sortRows (event) {
		//If button is 'Change Sort Order'
		if( $(event.target).attr('data-mode') == "sort" ) {

			//Resets the DataTable sort to use the sort_order database field
			$('table.packaging_sizes').DataTable().order([0, 'asc']).draw();

			//Updates the wording of the 'Change Sort Order' button and updates table styles to give a visual indicator
			$(event.target).attr('data-mode', 'save');
			$(event.target).html('Save Sort Order');
			$('table.packaging_sizes tbody tr').addClass('sort-row');
			//Fix for Chrome styling error
			$('table.packaging_sizes tbody').addClass('sort-body');
			$('table.packaging_sizes tbody tr td:last-of-type').addClass('sort-column');



			//Sets the table body as sortable, disables selection of text and enables sorting
			$('table.packaging_sizes tbody').sortable({
				helper: function(e, tr) {
				    var $originals = tr.children();
				    var $helper = tr.clone();
				    $helper.children().each(function(index)
				    {
				      // Set helper cell sizes to match the original sizes
				      $(this).width($originals.eq(index).width());
				    });
					$helper.find(".sort-column").removeClass('sort-column');

				    return $helper;
			  	}
			});
			$("table.packaging_sizes tbody").sortable("enable");
			$('table.packaging_sizes tbody').disableSelection();

		//If button is 'Save Sort Order'
		} else {

			//Reverts the text of the 'Save Sort Order' button and the table styles
			$(event.target).attr('data-mode', 'sort');
			$(event.target).html('Change Sort Order');
			$('table.packaging_sizes tbody tr').removeClass('sort-row');
			//Fix for Chrome styling error
			$('table.packaging_sizes tbody').removeClass('sort-body');
			$('table.packaging_sizes tbody tr td:last-of-type').removeClass('sort-column');


			//Disables sorting and enables text selection
			$("table.packaging_sizes tbody").sortable("disable");
			$('table.packaging_sizes tbody').enableSelection();

			//Gets an array of ids in the order the user sorted them
			var ids = $('table.packaging_sizes tbody').sortable("toArray", { attribute: "data-id" });

			$.blockUI({ message: '' });

			var request = $.ajax({
				method: "PUT",
				url: urls.urlAjaxSortPut,
				cache: false,
				data: { ids: ids}
				});

			request.done(function( html ) {
				$.unblockUI();

			});

			request.fail(function( jqXHR, textStatus ) {
				$.unblockUI();
				alert( "Request failed: " + textStatus );

			});

		}
	}

	function applyHook(hook, args) {
		if(!(typeof(config.hooks[hook]) == "undefined")) {
			config.hooks[hook](args);
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
				formValidation.addField($(this));
			});
		}
	}

	function validationResetFields($fields) {
		if(typeof(formValidation) != "undefined") {
			$fields.each( function (index, element) {
				formValidation.resetField($(this));
			});
		}
	}

	function activateJs($parent) {
		if(typeof(App) != "undefined" && typeof(App.activate) != "undefined") {
			App.activate($parent);
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
