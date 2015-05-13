function blockUI() {
	$.blockUI({ message: '' });
}

$(document).on('hidden.bs.modal', function (e) {
    $(e.target).removeData('bs.modal');
});

// Fades alert out
window.setTimeout(function() {
    $(".alert-removable").fadeTo(1500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 2500);

function updateDataTableLength(key, length) {
	blockUI();

	var request = $.ajax({
		method: "POST",
		url: urlDataTableUpdateLengthPost,
		cache: false,
		data: { key: key, length: length}
		});

	request.done(function( msg ) {
		$.unblockUI();

	});

	request.fail(function( jqXHR, textStatus ) {
		$.unblockUI();
		alert( "Request failed: " + textStatus );
	});

}

function limit_decimals(object, precision)
{
	var num = object.val();
	if (isNaN( parseFloat(num) ))
		return;
	
	num = parseFloat(num);
    object.val(num.toFixed(precision));
}

$(document).on('blur', '.limit_0_decimals', function() {
	limit_decimals($(this), 0);

});

$(document).on('blur', '.limit_2_decimals', function() {
	limit_decimals($(this), 2);

});

$(document).on('blur', '.limit_4_decimals', function() {
	limit_decimals($(this), 4);

});

$(document).on('blur', '.limit_decimals', function() {
	var decimals = $(this).attr('data-decimals');
	if (typeof decimals === 'undefined') {
		decimals = 4;
	}
	
	limit_decimals($(this), decimals);

});

$(document).on('change', '.dataTables_length select', function() {
	updateDataTableLength('datatable.length', $(this).val());

});

$('.dataTables_filter input').addClass('form-control').attr('placeholder','Search');
$('.dataTables_length select').select2({minimumResultsForSearch: 6});