@extends('layouts.masterLayout')

@section('title')
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="{{ route ('example.index.get') }}">Example</a></li>
</ol>

<h2>Example</h2>

@endsection

@section('content')

<div class="block-flat" style="border-width: 0px">
    <div class="content">
        <div class="row">
            <form id="form1">
                <table id="table1" class="table no-border table-hover">
                    <thead  class="no-border">
                        <tr>
                            <th>Field 1</th>                        
                            <th>Field 2</th>
                            <th>Field 3</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="no-border-y">
                        @include('example_no_edit.partials.rows', ['examples' => $examples])
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
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('/js/views/AjaxCrud.js') }}"></script>
<script type="text/javascript">

$('#form1').formValidation({
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

    var hooks = {
        addNewRowRequestDone: function(args) {
            $row = args.$row;
            //Show 'I'm done adding rows' button
            $row.closest('tfoot').find('.ads-save-new-rows').show();
        },
        deleteNewRowDone: function(args) {
            var table = args.table;

            if (table.find('tfoot tr').size() == 1) {
                //Override the hiding of the new/save buttons in the table footer
                table.find('tfoot tr:last').show();
                //Hide 'I'm done adding rows' button
                table.find('.ads-save-new-rows').hide();
            }
            
        },
        saveNewRowsRequestDone: function(args) {
            var table = args.table;
            //Override the hiding of the new/save buttons in the table footer
            table.find('tfoot tr:last').show();
            //Hide 'I'm done adding rows' button
            table.find('.ads-save-new-rows').hide();
            
        },
    }

    var urls = {
        urlAjaxRowGet: "",
        urlAjaxRowPut: "",
        urlAjaxEditableRowGet: "",
        urlAjaxNewRowGet: "{{ route('examples_no_edit.ajax.new_row.get') }}",
        urlAjaxRowDelete: "{{ route('examples_no_edit.ajax.row.delete', [id' => '_id_']) }}",
        urlAjaxRowsPost: "{{ route('examples_no_edit.ajax.rows.post') }}"
    };

    var config = [];
    config.urls = urls;
    config.hooks = hooks;
    config.form = $('form#primary_item_allocation');
    config.table = $('table#table-primary-allocation');
    config.datatableArgs = {
        pageLength: {{ Session::get('datatable.length', '10') }}
    };

    var ajaxCrud = new AjaxCrud(config);

</script>
@endsection

@stop
