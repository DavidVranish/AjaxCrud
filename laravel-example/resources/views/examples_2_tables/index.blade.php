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

<h2>2 Tables example</h2>
@endsection

@section('content')
<button id="add-table-1-row" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Table 1 Row</button>
<div class="block-flat" style="border-width: 0px">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <form id="table1-form" method="post">
          	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <table id="table1" class="table no-border table-hover">
            <thead class="no-border">
              <tr>
                <th style="width:30%;">Field 1</th>
                <th style="width:30%;">Field 2</th>
                <th style="text-align:center; width:20%;">Field 3</th>
                <th style="text-align:center; width:20%;">Actions</th>
            </tr>
            </thead>
            <tbody class="no-border-y">
              		@include('examples_2_tables.partials.table_1.rows', $examplesTable1)
            </tbody>
            <tfoot class="no-border-y">
            	<tr style="display: none">
            		<td colspan="4">
	            		<button class="btn btn-success btn-flat btn-xs ads-add-new-row" href="">New</button>
	         			  <button class="btn btn-default btn-flat btn-xs ads-save-new-rows" id="saverecords">I'm done adding transportation carriers</button>
         			</td>
         		</tr>
            </tfoot>
          </table>
          </form>

          <button id="add-table-2-row" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Table 2 Row</button>
          <form id="table2-form" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <table id="table2" class="table no-border table-hover">
            <thead class="no-border">
              <tr>
                <th style="width:30%;">Field 1</th>
                <th style="width:30%;">Field 2</th>
                <th style="text-align:center; width:20%;">Field 3</th>
                <th style="text-align:center; width:20%;">Actions</th>
            </tr>
            </thead>
            <tbody class="no-border-y">
                  @include('examples_2_tables.partials.table_2.rows', $examplesTable2)
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

$('#table1-form').formValidation({
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

	var table1Urls = {
		urlAjaxRowGet: "{{ route('examples_2_tables.table_1.ajax.row.get', ['id' => '_id_']) }}",
		urlAjaxRowPut: "{{ route('examples_2_tables.table_1.ajax.row.put', ['id' => '_id_']) }}",
		urlAjaxEditableRowGet: "{{ route('examples_2_tables.table_1.ajax.editable_row.get', ['id' => '_id_']) }}",
		urlAjaxNewRowGet: "{{ route('examples_2_tables.table_1.ajax.new_row.get') }}",
		urlAjaxRowDelete: "{{ route('examples_2_tables.table_1.ajax.row.delete', ['id' => '_id_']) }}",
		urlAjaxRowsPost: "{{ route('examples_2_tables.table_1.ajax.rows.post') }}"
	};

	var table1Config = [];
	config.urls = table2Urls;
	config.form = $('form#table1-form');
	config.table = $('table#table1');
	config.createInitButton = $('button#add-table-1-row');
  config.datatableArgs = {
      pageLength: {{ Session::get('datatable.length', '10') }}
  };

  var table1AjaxCrud = new AjaxCrud(table1Config);

  var table2Urls = {
    urlAjaxRowGet: "{{ route('examples_2_tables.table_2.ajax.row.get', ['id' => '_id_']) }}",
    urlAjaxRowPut: "{{ route('examples_2_tables.table_2.ajax.row.put', ['id' => '_id_']) }}",
    urlAjaxEditableRowGet: "{{ route('examples_2_tables.table_2.ajax.editable_row.get', ['id' => '_id_']) }}",
    urlAjaxNewRowGet: "{{ route('examples_2_tables.table_2.ajax.new_row.get') }}",
    urlAjaxRowDelete: "{{ route('examples_2_tables.table_2.ajax.row.delete', ['id' => '_id_']) }}",
    urlAjaxRowsPost: "{{ route('examples_2_tables.table_2.ajax.rows.post') }}"
  };

  var table2Config = [];
  config.urls = table2Urls;
  config.form = $('form#table2-form');
  config.table = $('table#table2');
  config.createInitButton = $('button#add-table-2-row');
  config.datatableArgs = {
      pageLength: {{ Session::get('datatable.length', '10') }}
  };

  var table2AjaxCrud = new AjaxCrud(table2Config);

</script>
@endsection

@stop
