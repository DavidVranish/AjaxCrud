var AjaxCrud = function (config) {

	var form = config.form; //$("form#create")
	var table = config.table; //$("table#customers")
	var createInitButton = config.createInitButton //$('button.js-create-init')
	var editAllButton = config.editAllButton //$('button.js-create-init')
	var saveAllButton = config.saveAllButton //$('button.js-create-init')
	var sortButton = config.sortButton;
	var cancelSortButton = config.cancelSortButton;
	var formValidation;
	var dataTable = table.DataTable(config.datatableArgs);
	var sortMode = 'view';
	var urls = {
		urlAjaxRowGet: config.urls.urlAjaxRowGet,
		urlAjaxRowPut: config.urls.urlAjaxRowPut,
		urlAjaxEditableRowGet: config.urls.urlAjaxEditableRowGet,
		urlAjaxRowsPut: config.urls.urlAjaxRowsPut,
		urlAjaxEditableRowsGet: config.urls.urlAjaxEditableRowsGet,
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