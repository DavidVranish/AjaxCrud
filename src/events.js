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

	if (typeof(editAllButton) != "undefined") {
		editAllButton.click(function (event) {
			event.preventDefault();
			editAllRows(event);
		});
	}

	if (typeof(saveAllButton) != "undefined") {
		saveAllButton.click(function (event) {
			event.preventDefault();
			saveAllRows(event);
		});
	}