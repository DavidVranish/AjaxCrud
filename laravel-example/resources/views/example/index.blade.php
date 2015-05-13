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
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="{{ URL::Route ('setup.carriers.index.get') }}">Setup</a></li>
  <li><a href="{{ URL::Route ('setup.carriers.index.get') }}">Transportation Carriers</a></li>
</ol>
<button id="add-row-1" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Transportation Carriers</button>
<h2>Master List: Transportation Carriers</h2>
@endsection

@section('content')
<div class="block-flat" style="border-width: 0px">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <form id="carriers" method="post">
          	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <table id="table-carriers" class="table no-border table-hover">
            <thead class="no-border">
              <tr>
                <th style="width:30%;">Carrier</th>
                <th style="width:30%;">Carrier Email</th>
                <th style="text-align:center; width:20%;">Status</th>
                <th style="text-align:center; width:20%;">Actions</th>
            </tr>
            </thead>
            <tbody class="no-border-y">
              		@include('setup.carriers.partials.rows', $carriers)
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

$('#carriers').formValidation({
    framework: 'bootstrap',
    icon: {
        valid: 'fa fa-check',
        invalid: 'fa fa-exclamation-triangle',
        validating: 'fa fa-refresh'
    },
    fields: {
    	'name': {
            validators: {
                notEmpty: {
                    message: 'The carrier name is required'
                },
                remote: {
                    transformer: function($field, validatorName, validator) {
                        var name = $field.val();
                        var id = $field.closest('tr').attr('data-id');
                        return name + ":" + id;
                    },

                    url: "{{ route('setup.carriers.validate.unique.post') }}",
                    type: 'POST',
                    message: "Carrier name must be unique"
                }
            }
    	},
    	'email': {
            validators: {
                emailAddress: {
                    message: 'The email address must be valid'
                }
            }
    	},
    }
})

	var urls = {
		urlAjaxRowGet: "{{ route('setup.carriers.ajax.row.get', ['id' => '_id_']) }}",
		urlAjaxRowPut: "{{ route('setup.carriers.ajax.row.put', ['id' => '_id_']) }}",
		urlAjaxEditableRowGet: "{{ route('setup.carriers.ajax.editable_row.get', ['id' => '_id_']) }}",
		urlAjaxNewRowGet: "{{ route('setup.carriers.ajax.new_row.get') }}",
		urlAjaxRowDelete: "{{ route('setup.carriers.ajax.row.delete', ['id' => '_id_']) }}",
		urlAjaxRowsPost: "{{ route('setup.carriers.ajax.rows.post') }}"
	};

	var config = [];
	config.urls = urls;
	config.form = $('form#carriers');
	config.table = $('table#table-carriers');
	config.createInitButton = $('button#add-row-1');
  config.datatableArgs = {
      pageLength: {{ Session::get('datatable.length', '10') }}
  };

    var ajaxCrud = new AjaxCrud(config);

</script>
@endsection

@stop
