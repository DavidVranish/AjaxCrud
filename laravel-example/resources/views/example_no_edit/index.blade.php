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
                            <th>Data 1</th>                        
                            <th>Data 2</th>
                            <th>Data 3</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="no-border-y">
                        @include('example_no_edit.partials.rows', ['rows' => $rows])
                    </tbody>
                    <tfoot class="no-border-y">
                        <tr style="display: none">
                            <td colspan="4">
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
<script>

    var hooks = {
        addNewRowRequestDone: function(args) {
            $row = args.$row;

            $row.find('.allocation_item').select2({
                minimumInputLength: 5,
                data: items,
                width: '100%'
            });

            $row.find('input.allocation_item').select2('open');

            $row.closest('tfoot').find('.ads-save-new-rows').show();

        },
        deleteNewRowDone: function(args) {
            var table = args.table;

            if (table.find('tfoot tr').size() == 1) {
                table.find('tfoot tr').show();
                table.find('.ads-save-new-rows').hide();
            }
            
        },
        saveNewRowsRequestDone: function(args) {
            var table = args.table;
            table.find('tfoot tr').show();
            table.find('.ads-save-new-rows').hide();
            
        },
    }

    var urlsPrimary = {
        urlAjaxRowGet: "",
        urlAjaxRowPut: "",
        urlAjaxEditableRowGet: "",
        urlAjaxNewRowGet: "{{ route('setup.inventoryrows.primary_allocations.ajax.new_row.get', ['inventory_row_id' => $inventoryRow->id]) }}",
        urlAjaxRowDelete: "{{ route('setup.inventoryrows.primary_allocations.ajax.row.delete', ['inventory_row_id' => $inventoryRow->id, 'id' => '_id_']) }}",
        urlAjaxRowsPost: "{{ route('setup.inventoryrows.primary_allocations.ajax.rows.post', ['inventory_row_id' => $inventoryRow->id]) }}"
    };

    var configPrimary = [];
    configPrimary.urls = urlsPrimary;
    configPrimary.hooks = hooks;
    configPrimary.form = $('form#primary_item_allocation');
    configPrimary.table = $('table#table-primary-allocation');
    configPrimary.datatableArgs = {
        pageLength: {{ Session::get('datatable.length', '10') }}
    };

    var ajaxCrudPrimary = new AjaxCrud(configPrimary);

    var urlsOverflow = {
        urlAjaxRowGet: "",
        urlAjaxRowPut: "",
        urlAjaxEditableRowGet: "",
        urlAjaxNewRowGet: "{{ route('setup.inventoryrows.overflow_allocations.ajax.new_row.get', ['inventory_row_id' => $inventoryRow->id]) }}",
        urlAjaxRowDelete: "{{ route('setup.inventoryrows.overflow_allocations.ajax.row.delete', ['inventory_row_id' => $inventoryRow->id, 'id' => '_id_']) }}",
        urlAjaxRowsPost: "{{ route('setup.inventoryrows.overflow_allocations.ajax.rows.post', ['inventory_row_id' => $inventoryRow->id]) }}"
    };

    var configOverflow = [];
    configOverflow.urls = urlsOverflow;
    configOverflow.hooks = hooks;
    configOverflow.form = $('form#overflow_item_allocation');
    configOverflow.table = $('table#table-overflow-allocation');
    configOverflow.datatableArgs = {
        pageLength: {{ Session::get('datatable.length', '10') }}
    };

    var ajaxCrudOverflow = new AjaxCrud(configOverflow);

</script>
@endsection

@stop
