@extends('layouts.masterLayout')

@section('styles')
    <style type="text/css">
        .width-13em {
            width: 13em;
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
    <li><a href="{{ route ('setup.inventoryrows.index.get') }}">Setup</a></li>
    <li><a href="{{ route ('setup.inventoryrows.index.get') }}">Inventory Rows</a></li>
    @if (empty($inventoryRow->id))
        <li><a href="{{ route('setup.inventoryrows.modify.get') }}">Add Inventory Row</a></li>
    @else
        <li><a href="{{ route('setup.inventoryrows.modify.get') }}/{{ $inventoryRow->id }}">Edit Inventory Row</a></li>
    @endif
</ol>
@if (empty($inventoryRow->id))
    <h2>Add Inventory Row</h2><br>
@else
    <h2>Edit Inventory Row {{ $inventoryRow->name }}</h2><br>
@endif

<?php
if(!empty($inventoryRow->id)){
    foreach ($warehouseLocations AS $warehouseLocation){
        if($inventoryRow->warehouse_location_id == $warehouseLocation->id){
            $companyId = $warehouseLocation->company_location_id;
            break;
        }
    }
}

?>

<form class="form-horizontal">
        <div class="form-group" style="margin-top:-5px">
            <div class="col-md-6 input-group">
                <span class="input-group-addon width-13em"><i class="fa fa-map-marker"></i><font size="2">&nbsp;&nbsp;Company Location</font></span>
                @include('partials.company_locations', ['disabled' => true])
            </div>
        </div>
        <div class="form-group" style="margin-top:-25px;margin-bottom:-20px;">
                <div class="col-md-6 input-group">
                        <span class="input-group-addon width-13em"><font size="2">&nbsp;&nbsp;Warehouse Location</font></span>
                        <select disabled class="select2" id="warehouseLocation" name="">
                        @foreach ($warehouseLocations AS $warehouseLocation)
                        <option cid="{{ $warehouseLocation->company_location_id }}" {{($warehouseLocation->id==Session::get('warehouse_location_id', 0))?'SELECTED':''}} value="{{ $warehouseLocation->id }}">
                            {{ $warehouseLocation->name }} ({{ $warehouseLocation->short }})
                        </option>
                        @endforeach
                </select>
                </div>
        </div>
</form>

@endsection

@section('content')

<div class="block-flat" style="border-width: 0px">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                    <div class="content">
                        <form class="form-horizontal" id="form-inventory-row" action="{{ route('setup.inventoryrows.modify.post') }}"role="form" method="POST" style="margin-top:-40px">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="id" value="{{$inventoryRow->id}}" />
                            <input type="hidden" name="company_location_id" value="{{$company_location_id}}" />
                            <input type="hidden" name="warehouse_location_id" value="{{Session::get('warehouse_location_id', 0)}}" />
                            <div class="form-group">
                                <label for="inventoryRowName" class="col-md-3" style="margin-top:8px">Inventory Row Name</label>
                                <div class="col-md-5">
                                    @if (empty($inventoryRow->id))
                                        <input type="text" class="form-control" placeholder="row name" id="inventoryRow" name="inventoryRow" />
                                    @else
                                        <div id="inventoryRowContainer">
                                            <a href="#" id="inventoryRow" class="x-editable">{{$inventoryRow->name}}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inventoryRowMax" class="col-md-3" style="margin-top:8px">Row Max</label>
                                <div class="col-md-5">
                                    @if (empty($inventoryRow->id))
                                        <input type="text" class="form-control" placeholder="maximum row pallets" id="inventoryRowMax" name="inventoryRowMax" />
                                    @else
                                        <div id="inventoryRowMaxContainer">
                                            <a href="#" id="inventoryRowMax" class="x-editable">{{$inventoryRow->row_max}}</a>
                                        </div>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group">

                                @if(!empty($warehouseId) and !empty($companyId))
                                    <input type="hidden" class="form-control" placeholder="maximum row pallets" id="warehouseId" name="warehouseId" value="{{$warehouseId}}" />
                                    <input type="hidden" class="form-control" placeholder="maximum row pallets" id="companyId" name="companyId" value="{{$companyId}}" />
                                @elseif(!empty($inventoryRow->id))
                                    <input type="hidden" class="form-control" placeholder="maximum row pallets" id="id" name="id" value="{{$inventoryRow->id}}" />
                                @endif

                            </div>
                            <?php //@include('partials.submitCancel')
                            ?>
                            @if (empty($inventoryRow->id))
                                <input class="btn btn-primary" type="submit" value="Submit"/>
                                <a class="btn btn-default" href="{{ route('setup.inventoryrows.index.get') }}">Cancel</a>
                            @else
                                <a class="btn btn-default" href="{{ route('setup.inventoryrows.index.get') }}">Return to Inventory Rows</a>
                                @if($inventoryRow->deletable())
                                    <button type="button" class="btn btn-danger pull-right js-delete-inventory-row md-trigger" data-modal="delete-modal">Delete</button>
                                @endif
                            @endif
                            

                    </form>

                    <br/>

                    @if (!empty($inventoryRow->id))

                    <form id="primary_item_allocation">
                    <input type="hidden" name="inventory_row_id" value="{{$inventoryRow->id}}" />
                        <table id="table-primary-allocation" class="table no-border table-hover">
                            <thead class="no-border">
                                    <tr>
                                        <th style="width:80%;"><strong>Primary Inventory Allocation</strong></th>
                                        <th class="text-center" style="width:20%;">Actions</th>
                                    </tr>
                            </thead>
                            <tbody class="no-border-y">
                                @include('setup.inventoryrows.partials.modify.primary_allocations.rows', ['primaryInventoryItems' => $inventoryRow->primaryInventoryItems])
                            </tbody>
                            <tfoot class="no-border-y">
                                <tr>
                                    <td colspan="2">
                                        <button type="button" class="btn btn-success btn-flat btn-xs ads-add-new-row" href="#">New Primary</button>
                                        <button type="button" class="btn btn-default btn-flat btn-xs ads-save-new-rows" style="display:none;">I'm done adding primary allocations</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </form>

                    <br/><br/><br/>
                    <form id ="overflow_item_allocation">
                    <input type="hidden" name="inventory_row_id" value="{{$inventoryRow->id}}" />
                        <table id="table-overflow-allocation" class="table no-border table-hover">
                            <thead class="no-border">
                                <tr>
                                    <th style="width:80%;"><strong>Overflow Inventory Allocation</strong></th>
                                    <th class="text-center" style="width:20%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="no-border-y">
                                @include('setup.inventoryrows.partials.modify.overflow_allocations.rows', ['overflowInventoryItems' => $inventoryRow->overflowInventoryItems])
                            </tbody>
                            <tfoot class="no-border-y">
                                <tr>
                                    <td colspan="2">
                                        <button type="button" class="btn btn-success btn-flat btn-xs ads-add-new-row" href="#">New Overflow</button>
                                        <button type="button" class="btn btn-default btn-flat btn-xs ads-save-new-rows" style="display:none;">I'm done adding primary allocations</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                    @endif
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
    $(window).ready(function() {
        $('#inventoryRow').focus();
    });

    $('#form-inventory-row').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'fa fa-check',
            invalid: 'fa fa-exclamation-triangle',
            validating: 'fa fa-refresh'
        },
        fields: {
            'inventoryRow': {
                validators: {
                    notEmpty: {
                        message: 'Inventory row is required'
                    },
                    remote: {
                        transformer: function($field, validatorName, validator) {
                            var name = $field.val();
                            @if (!empty($inventoryRow->id))
                                var id = {{ $inventoryRow->id }};
                            @else
                                var id = 0;
                            @endif
                            var warehouseLocationId = {{ Session::get('warehouse_location_id', 0) }};
                            return name + ":" + id + ":" + warehouseLocationId;
                        },
                        data: {
                            type: 'name'
                        },
                        url: "{{ route('setup.inventoryrows.validate.unique.post') }}",
                        type: 'POST',
                        message: "Inventory row must be unique"
                    }
                }
            },
            'inventoryRowMax': {
                validators: {
                    notEmpty: {
                        message: 'Inventory row max is required'
                    },
                    digits: {
                        message: 'Inventory row max must be a whole number'
                    }
                }
            }
        }
    });

@if (!empty($inventoryRow->id))

    $('.js-delete-inventory-row').click(function (event) {
        $('#delete-modal .header-text').text('Remove: ' + "{{ $inventoryRow->name }}");
        $('#delete-modal .body-text').text("{{ $inventoryRow->name }}" + ' will be removed. Ok to proceed?');

        $("#delete-modal .ads-delete").attr('data-id', id);

        $("#delete-modal .ads-delete").off('click.delete');
        $("#delete-modal .ads-delete").on('click.delete', function (event) { 
            blockUI();

            var request = $.ajax({
                method: "DELETE",
                url: "{{ route('setup.inventoryrows.ajax.delete', ['id' => $inventoryRow->id]) }}",
                cache: false
            });

            request.done(function( html ) {
                window.location = "{{ route('setup.inventoryrows.index.get') }}"
                $.unblockUI();

            });

            request.fail(function( jqXHR, textStatus ) {
                $.unblockUI();
                alert( "Request failed: " + textStatus );

            });
        });
    });

    $('form#primary_item_allocation').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'fa fa-check',
            invalid: 'fa fa-exclamation-triangle',
            validating: 'fa fa-refresh'
        }
    });
    $('form#overflow_item_allocation').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'fa fa-check',
            invalid: 'fa fa-exclamation-triangle',
            validating: 'fa fa-refresh'
        }
    });

    $('#inventoryRow').editable({
        ajaxOptions: {
            type: 'PUT'
        },
        pk: {{$inventoryRow->id}},
        type: 'text',
        url: '{{ route("setup.inventoryrows.ajax.name.put", ["id" => $inventoryRow->id]) }}',
        title: 'Enter specific gravity',
        inputclass: 'limit_0_decimals',
        clear: false,
        validate: function(value) {
            var $container = $('div#inventoryRowContainer');

            $("form#form-inventory-row").formValidation('validateContainer', $container);

            var isValidContainer = $("form#form-inventory-row").formValidation('isValidContainer', $container);
            if (isValidContainer === false || isValidContainer === null) {
                // Stop submission because of validation error.
                return "validation must be successful";
            }
        }
    });



    $('#inventoryRowMax').editable({
        ajaxOptions: {
            type: 'PUT'
        },
        pk: {{$inventoryRow->id}},
        type: 'text',
        url: '{{ route("setup.inventoryrows.ajax.row_max.put", ["id" => $inventoryRow->id]) }}',
        title: 'Enter specific gravity',
        inputclass: 'limit_0_decimals',
        clear: false,
        validate: function(value) {
            var $container = $('div#inventoryRowMaxContainer');

            $("form#form-inventory-row").formValidation('validateContainer', $container);

            var isValidContainer = $("form#form-inventory-row").formValidation('isValidContainer', $container);
            if (isValidContainer === false || isValidContainer === null) {
                // Stop submission because of validation error.
                return "validation must be successful";
            }
        }
    });

    $('#inventoryRow').on('shown', function(e, editable) {
        $('form#form-inventory-row div#inventoryRowContainer input').attr('name', 'inventoryRow');
        $("form#form-inventory-row").formValidation('addField', $('form#form-inventory-row div#inventoryRowContainer input')); 
    });
    $('#inventoryRow').on('hidden', function(e, editable) {
        $("form#form-inventory-row").formValidation('resetField', $('form#form-inventory-row div#inventoryRowContainer input')); 
    });

    $('#inventoryRowMax').on('shown', function(e, editable) {
        $('form#form-inventory-row div#inventoryRowMaxContainer input').attr('name', 'inventoryRowMax');
        $("form#form-inventory-row").formValidation('addField', $('form#form-inventory-row div#inventoryRowMaxContainer input')); 
    });
    $('#inventoryRowMax').on('hidden', function(e, editable) {
        $("form#form-inventory-row").formValidation('resetField', $('form#form-inventory-row div#inventoryRowMaxContainer input')); 
    });

    var items = [
         @foreach ($items as $item)
            {
                id: {{$item->id}}, 
                text:'{{$item->stockid}} ({{addslashes($item->description)}})'
            },
         @endforeach
    ];

    var urlsPrimary = {
        urlAjaxRowGet: "",
        urlAjaxRowPut: "",
        urlAjaxEditableRowGet: "",
        urlAjaxNewRowGet: "{{ route('setup.inventoryrows.primary_allocations.ajax.new_row.get', ['inventory_row_id' => $inventoryRow->id]) }}",
        urlAjaxRowDelete: "{{ route('setup.inventoryrows.primary_allocations.ajax.row.delete', ['inventory_row_id' => $inventoryRow->id, 'id' => '_id_']) }}",
        urlAjaxRowsPost: "{{ route('setup.inventoryrows.primary_allocations.ajax.rows.post', ['inventory_row_id' => $inventoryRow->id]) }}"
    };

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

@endif

</script>
@endsection

@stop
