@extends('layouts.masterLayout')

@section('styles')
    <style type="text/css">
        table#table-carriers .form-group {
            width: 100%;
        }

        table#table-carriers .value-field {
            width: 100%;
        }

    </style>
@stop

@section('leftNavbarCollapseOpen')
<div class ="sb-collapsed fixed-menu" id="cl-wrapper">
@endsection

@section('submenu')
@include('partials.submenu')
@endsection

@section('title')
<button id="add-table-row" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Transportation Carriers</button>
<h2>Examples</h2>
@endsection

@section('content')
<div class="block-flat" style="border-width: 0px">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <form id="example-form" method="post">
          	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <table id="example" class="table no-border table-hover">
            <thead class="no-border">
              <tr>
                <th style="width:30%;">Field 1</th>
                <th style="width:30%;">Field 2</th>
                <th style="text-align:center; width:20%;">Field 3</th>
                <th style="text-align:center; width:20%;">Actions</th>
            </tr>
            </thead>
            <tbody class="no-border-y">
              		@include('examples.partials.rows', $examples)
            </tbody>
            <tfoot class="no-border-y">
            	<tr style="display: none">
            		<td colspan="4">
	            		<button class="btn btn-success btn-flat btn-xs ads-add-new-row" href="">New</button>
	         			  <button class="btn btn-default btn-flat btn-xs ads-save-new-rows" id="saverecords">I'm done adding rows</button>
         			  </td>
         		 </tr>
            </tfoot>
          </table>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('leftNavbarCollapseClose')
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('/js/views/AjaxCrud.js') }}"></script>
<script>

$('#example-form').formValidation({
    framework: 'bootstrap',
    icon: {
        valid: 'fa fa-check',
        invalid: 'fa fa-exclamation-triangle',
        validating: 'fa fa-refresh'
    },
    fields: {
    	'field1': {
            validators: {
                notEmpty: {
                    message: 'Field 1 is required'
                }
            }
    	},
    	'field2': {
            validators: {
                notEmpty: {
                    message: 'Field 2 is required'
                }
            }
      },
      'field3': {
            validators: {
                notEmpty: {
                    message: 'Field 3 is required'
                }
            }
      },
    }
});

	var urls = {
		urlAjaxRowGet: "{{ route('examples.ajax.row.get', ['id' => '_id_']) }}",
		urlAjaxRowPut: "{{ route('examples.ajax.row.put', ['id' => '_id_']) }}",
		urlAjaxEditableRowGet: "{{ route('examples.ajax.editable_row.get', ['id' => '_id_']) }}",
		urlAjaxNewRowGet: "{{ route('examples.ajax.new_row.get') }}",
		urlAjaxRowDelete: "{{ route('examples.ajax.row.delete', ['id' => '_id_']) }}",
		urlAjaxRowsPost: "{{ route('examples.ajax.rows.post') }}"
	};

	var config = [];
	config.urls = urls;
	config.form = $('form#example-form');
	config.table = $('table#example');
	config.createInitButton = $('button#add-table-row');
  config.datatableArgs = {
      pageLength: {{ Session::get('datatable.length', '10') }}
  };

  var ajaxCrud = new AjaxCrud(config);

</script>
@endsection

@stop
