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