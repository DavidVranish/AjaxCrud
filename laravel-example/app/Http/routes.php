<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'auth'], function()
{

  Route::get('/home', function()
  {
	return Redirect::to('/');
  });
	
  //dashboard route
  Route::get('/', ['as' => 'dashboard.index.get', 'uses' =>'DashboardController@getIndex']);

  Route::post('/data_table/update_length', ['as' => 'data_table.update_length.post', 'uses' => 'DataTableController@postDataTableUpdateLength']);

  //vendor purchase orders
  Route::get('/vendorpos', ['as' => 'vendorpos.index.get', 'uses' =>'VendorPOController@getIndex']);
  Route::get('/vendorpos/modify/{id?}', ['as' => 'vendorpos.modify.get', 'uses' =>'VendorPOController@getModify']);
  Route::get('/vendorpos/receive/{id?}', ['as' => 'vendorpos.receive.get', 'uses' =>'VendorPOController@getReceive']);
  Route::get('/vendorpos/unreceive/{id?}', ['as' => 'vendorpos.unreceive.get', 'uses' =>'VendorPOController@getUnReceive']);
  Route::get('/vendorpos/palletmodify/{id?}', ['as' => 'vendorpos.palletmodify.get', 'uses' =>'VendorPOController@getPalletModify']);
  Route::get('/vendorpos/uommodify/{id?}', ['as' => 'vendorpos.uommodify.get', 'uses' =>'VendorPOController@getUOMModify']);
  Route::get('/vendorpos/approve/{id}', ['as' => 'vendorpos.approve.get', 'uses' => 'VendorPOController@getApprove']);
  Route::get('/vendorpos/polines/{id}/{rm_receipt_id?}', ['as' => 'vendorpos.polines.get', 'uses' => 'VendorPOController@getPOLines']);

  //raw material receipts
  Route::get('/rmreceipts', ['as' => 'rmreceipts.index.get', 'uses' =>'RMReceiptController@getIndex']);
  //Route::get('/rmreceipts/calendar', ['as' => 'rmreceipts.calendar.get', 'uses' =>'RMReceiptController@getCalendar']);
  Route::get('/rmreceipts/modify/{type?}/{id?}', ['as' => 'rmreceipts.modify.get', 'uses' =>'RMReceiptController@getModify']);
  Route::get('/rmreceipts/selectpo/{id}', ['as' => 'rmreceipts.selectpo.get', 'uses' =>'RMReceiptController@getSelectPO']);
  Route::get('/rmreceipts/schedulepo/{rm_receipt_id}/{vendor_po_id}', ['as' => 'rmreceipts.schedulepo.get', 'uses' =>'RMReceiptController@getSchedulePO']);
  Route::get('/rmreceipts/updateTable/{display_date?}/{display_filter?}', ['as' => 'rmreceipts.update-table.get', 'uses' =>'RMReceiptController@getUpdateTable']);
  Route::get('/rmreceipts/removepo/{rm_receipt_id}/{vendor_po_id}', ['as' => 'rmreceipts.removepo.get', 'uses' =>'RMReceiptController@getRemovePO']);
    // Post
    Route::post('/rmreceipts/update/', ['as' => 'rmreceipts.update.post', 'uses' => 'RMReceiptController@postUpdate']);
    Route::post('/rmreceipts/schedulepo', ['as' => 'rmreceipts.schedulepo.post', 'uses' =>'RMReceiptController@postSchedulePO']);
  
  // AJAX - Forms
  Route::get('/rmreceipts/getCarriers', ['as' => 'rmreceipts.carriers.get', 'uses' => 'RMReceiptController@getCarriers']);
  Route::post('/rmreceipts/setLocation', ['as' => 'rmreceipts.location.post', 'uses' => 'RMReceiptController@postSetLocation']);
  
  // AJAX - Modal
  Route::get('/rmreceipts/createCarrier', ['as' => 'rmreceipts.createCarrier.get', 'uses' => 'RMReceiptController@getCreateCarrier']);
  
  //external calendar
  Route::get('/externalcalendar', ['as' => 'rmreceipts.calendar.get', 'uses' =>'RMReceiptController@getCalendar']);

  //inventory: locations
  Route::get('/inventorylocations', ['as' => 'inventorylocations.index.get', 'uses' =>'InventoryLocationController@getIndex']);
  Route::get('/inventorylocations/row', ['as' => 'inventorylocations.row.get', 'uses' =>'InventoryLocationController@getRow']);

  //inventory: transactions
  Route::get('/inventorytransactions', ['as' => 'inventorytransactions.index.get', 'uses' =>'InventoryTransactionController@getIndex']);
  Route::get('/inventorytransactions/moves/modify', ['as' => 'inventorytransactions.moves.modify.get', 'uses' =>'InventoryTransactionController@getMovesModify']);
  Route::get('/inventorytransactions/adjustments/modify', ['as' => 'inventorytransactions.adjustments.modify.get', 'uses' =>'InventoryTransactionController@getAdjustmentsModify']);
  Route::get('/inventorytransactions/cyclecounts', ['as' => 'inventorytransactions.cyclecounts.index.get', 'uses' =>'InventoryTransactionController@getCycleCountIndex']);
  Route::get('/inventorytransactions/cyclecounts/modify/{companylocation?}/{id?}', ['as' => 'inventorytransactions.cyclecounts.modify.get', 'uses' =>'InventoryTransactionController@getCycleCountModify']);

  //inventory: unit of measure
  Route::get('/inventoryuom', ['as' => 'inventoryuom.index.get', 'uses' => 'InventoryUOMController@getUnitsIndex']);
  Route::get('/inventoryuom/modify/{id}', ['as' => 'inventoryuom.modify.get', 'uses' => 'InventoryUOMController@getUnitsModify']);
  Route::post('/inventoryuom/modify/{id}', ['as' => 'inventoryuom.modify.post', 'uses' => 'InventoryUOMController@postUnitsModify']);
  Route::get('/inventoryuom/activate/{id}', ['as' => 'inventoryuom.activate.get', 'uses' => 'InventoryUOMController@getUnitsActivate']);


  //raw material transfers
  Route::get('/rmtransfers/requests/', ['as' => 'rmtransfers.requests.index.get', 'uses' =>'RMTransferController@getRequestIndex']);
  Route::get('/rmtransfers/requests/modify', ['as' => 'rmtransfers.requests.modify.get', 'uses' =>'RMTransferController@getRequestModify']);


  // setup routes
    //user routes
    Route::get('/setup/users', ['as' => 'setup.users.index.get', 'uses' =>'SetupController@getUserIndex']);
    Route::get('/setup/users/modify/{id?}', ['as' => 'setup.users.modify.get', 'uses' =>'SetupController@getUserModify']);
    Route::post('/setup/users/modify/{id?}', ['as' => 'setup.users.modify.post', 'uses' =>'SetupController@postUserModify']);
    Route::post('/setup/users/validate/unique/', ['as' => 'setup.users.validate.unique.post', 'uses' => 'Setup\UserController@postValidateUnique']);

    //company locations
    Route::get('/setup/locationscompany', ['as' => 'setup.locationscompany.index.get', 'uses' =>'Setup\CompanyLocationController@getCompanyLocationIndex']);

    Route::get('/setup/locationscompany/ajax/row/{id}/', ['as' => 'setup.locationscompany.ajax.row.get', 'uses' => 'Setup\CompanyLocationController@getCompanyLocationRow']);
    Route::put('/setup/locationscompany/ajax/row/{id}/', ['as' => 'setup.locationscompany.ajax.row.put', 'uses' => 'Setup\CompanyLocationController@putCompanyLocationRow']);
    Route::get('/setup/locationscompany/ajax/new_row/', ['as' => 'setup.locationscompany.ajax.new_row.get', 'uses' => 'Setup\CompanyLocationController@getCompanyLocationNewRow']);
    Route::delete('/setup/locationscompany/ajax/row/{id}/', ['as' => 'setup.locationscompany.ajax.row.delete', 'uses' => 'Setup\CompanyLocationController@deleteCompanyLocationRow']);
    Route::post('/setup/locationscompany/ajax/rows/', ['as' => 'setup.locationscompany.ajax.rows.post', 'uses' => 'Setup\CompanyLocationController@postCompanyLocationRows']);
    Route::get('/setup/locationscompany/ajax/editable_row/{id}/', ['as' => 'setup.locationscompany.ajax.editable_row.get', 'uses' => 'Setup\CompanyLocationController@getCompanyLocationEditRow']);

    //warehouse locations
    Route::get('/locationswarehouse', ['as' => 'setup.locationswarehouse.index.get', 'uses' =>'SetupController@getLocationWarehouseIndex']);

    Route::get('/setup/warehouselocations/ajax/row/{id}/', ['as' => 'setup.warehouselocations.ajax.row.get', 'uses' => 'Setup\WarehouseLocationController@getWarehouseLocationRow']);
    Route::put('/setup/warehouselocations/ajax/row/{id}/', ['as' => 'setup.warehouselocations.ajax.row.put', 'uses' => 'Setup\WarehouseLocationController@putWarehouseLocationRow']);
    Route::get('/setup/warehouselocations/ajax/new_row/', ['as' => 'setup.warehouselocations.ajax.new_row.get', 'uses' => 'Setup\WarehouseLocationController@getWarehouseLocationNewRow']);
    Route::delete('/setup/warehouselocations/ajax/row/{id}/', ['as' => 'setup.warehouselocations.ajax.row.delete', 'uses' => 'Setup\WarehouseLocationController@deleteWarehouseLocationRow']);
    Route::post('/setup/warehouselocations/ajax/rows/', ['as' => 'setup.warehouselocations.ajax.rows.post', 'uses' => 'Setup\WarehouseLocationController@postWarehouseLocationRows']);
    Route::get('/setup/warehouselocations/ajax/editable_row/{id}/', ['as' => 'setup.warehouselocations.ajax.editable_row.get', 'uses' => 'Setup\WarehouseLocationController@getWarehouseLocationEditRow']);
    Route::post('/setup/warehouselocations/validate/unique/', ['as' => 'setup.warehouselocations.validate.unique.post', 'uses' => 'Setup\WarehouseLocationController@postValidateUnique']);
    
    //inventory row locations
    Route::get('/inventoryrows/index/{warehouse_location_id?}', ['as' => 'setup.inventoryrows.index.get', 'uses' =>'SetupController@getInventoryRowIndex']);

    Route::get('/inventoryrows/removePrimaryItem/{inventory_row_id}/{item_id}', ['as' => 'setup.inventoryrows.remove_primary_item.get', 'uses' =>'SetupController@getRemovePrimaryInventoryRowItem']);
    Route::post('/inventoryrows/addPrimaryItem', ['as' => 'setup.inventoryrows.add_primary_item.post', 'uses' =>'SetupController@postPrimaryInventoryRowItem']);

    Route::get('/inventoryrows/removeOverflowItem/{inventory_row_id}/{item_id}', ['as' => 'setup.inventoryrows.remove_overflow_item.get', 'uses' =>'SetupController@getRemoveOverflowInventoryRowItem']);
    Route::post('/inventoryrows/addOverflowItem', ['as' => 'setup.inventoryrows.add_overflow_item.post', 'uses' =>'SetupController@postOverflowInventoryRowItem']);
    //Route::get('/inventoryrows/prep/{id?}', ['as' => 'setup.inventoryrows.prep.get', 'uses' =>'SetupController@getInventoryRowPrep']);

    Route::get('/inventoryrows/modify/{id?}', ['as' => 'setup.inventoryrows.modify.get', 'uses' =>'SetupController@getInventoryRowModify']);
    Route::post('/inventoryrows/modify/{id?}', ['as' => 'setup.inventoryrows.modify.post', 'uses' =>'SetupController@postInventoryRowModify']);

    	// AJAX Load
    	Route::get('/inventoryrows/rowsByLocation', ['as' => 'setup.inventoryrows.rowsByLocation.get', 'uses' => 'SetupController@getRowsByLocation']);
  
    Route::get('/setup/inventoryrows/primary_allocations/ajax/new_row/{inventory_row_id}', ['as' => 'setup.inventoryrows.primary_allocations.ajax.new_row.get', 'uses' => 'Setup\InventoryRowController@getPrimaryItemNewRow']);
    Route::delete('/setup/inventoryrows/primary_allocations/ajax/row/{inventory_row_id}/{id}/', ['as' => 'setup.inventoryrows.primary_allocations.ajax.row.delete', 'uses' => 'Setup\InventoryRowController@deletePrimaryItemRow']);
    Route::post('/setup/inventoryrows/primary_allocations/ajax/rows/{inventory_row_id}', ['as' => 'setup.inventoryrows.primary_allocations.ajax.rows.post', 'uses' => 'Setup\InventoryRowController@postPrimaryItemRows']);
    Route::get('/setup/inventoryrows/overflow_allocations/ajax/new_row/{inventory_row_id}', ['as' => 'setup.inventoryrows.overflow_allocations.ajax.new_row.get', 'uses' => 'Setup\InventoryRowController@getOverflowItemNewRow']);
    Route::delete('/setup/inventoryrows/overflow_allocations/ajax/row/{inventory_row_id}/{id}/', ['as' => 'setup.inventoryrows.overflow_allocations.ajax.row.delete', 'uses' => 'Setup\InventoryRowController@deleteOverflowItemRow']);
    Route::post('/setup/inventoryrows/overflow_allocations/ajax/rows/{inventory_row_id}', ['as' => 'setup.inventoryrows.overflow_allocations.ajax.rows.post', 'uses' => 'Setup\InventoryRowController@postOverflowItemRows']);

    Route::delete('/setup/inventoryrows/ajax/{id}/', ['as' => 'setup.inventoryrows.ajax.delete', 'uses' => 'Setup\InventoryRowController@deleteInventoryRow']);

    Route::post('/setup/inventoryrows/validate/unique/', ['as' => 'setup.inventoryrows.validate.unique.post', 'uses' => 'Setup\InventoryRowController@postValidateUnique']);

    Route::put('/setup/inventoryrows/ajax/name/{id}', ['as' => 'setup.inventoryrows.ajax.name.put', 'uses' => 'Setup\InventoryRowController@putName']);
    Route::put('/setup/inventoryrows/ajax/row_max/{id}', ['as' => 'setup.inventoryrows.ajax.row_max.put', 'uses' => 'Setup\InventoryRowController@putRowMax']);

    //transportation carriers
    Route::get('/carriers', ['as' => 'setup.carriers.index.get', 'uses' =>'SetupController@getCarrierIndex']);
    Route::get('/carriers/modify/{id?}', ['as' => 'setup.carriers.modify.get', 'uses' =>'SetupController@getCarrierModify']);
    Route::get('/carriers/row/{id?}', ['as' => 'setup.carriers.row.get', 'uses' =>'SetupController@getCarrierRow']);
    Route::get('/carriers/addAjax', ['as' => 'setup.carriers.addAjax.get', 'uses' =>'SetupController@getCarrierAddAjax']);
    Route::post('/carriers/modify', ['as' => 'setup.carriers.modify.post', 'uses' =>'SetupController@postCarrierModify']);
    Route::post('/carriers/modifyOne', ['as' => 'setup.carriers.modifyOne.post', 'uses' =>'SetupController@postCarrierModifyOne']);

    Route::get('/setup/carriers/ajax/row/{id}/', ['as' => 'setup.carriers.ajax.row.get', 'uses' => 'Setup\CarrierController@getCarrierRow']);
    Route::put('/setup/carriers/ajax/row/{id}/', ['as' => 'setup.carriers.ajax.row.put', 'uses' => 'Setup\CarrierController@putCarrierRow']);
    Route::get('/setup/carriers/ajax/new_row/', ['as' => 'setup.carriers.ajax.new_row.get', 'uses' => 'Setup\CarrierController@getCarrierNewRow']);
    Route::delete('/setup/carriers/ajax/row/{id}/', ['as' => 'setup.carriers.ajax.row.delete', 'uses' => 'Setup\CarrierController@deleteCarrierRow']);
    Route::post('/setup/carriers/ajax/rows/', ['as' => 'setup.carriers.ajax.rows.post', 'uses' => 'Setup\CarrierController@postCarrierRows']);
    Route::get('/setup/carriers/ajax/editable_row/{id}/', ['as' => 'setup.carriers.ajax.editable_row.get', 'uses' => 'Setup\CarrierController@getCarrierEditRow']);

    Route::post('/setup/carriers/validate/unique/', ['as' => 'setup.carriers.validate.unique.post', 'uses' => 'Setup\CarrierController@postValidateUnique']);

    //raw material transfer request template
    Route::get('/rmtrtemplates', ['as' => 'setup.rmtrtemplates.index.get', 'uses' =>'SetupController@getRMTRTemplateIndex']);
    Route::get('/rmtrtemplates/modify/{id?}', ['as' => 'setup.rmtrtemplates.modify.get', 'uses' =>'SetupController@getRMTRTemplateModify']);

    //units of measure routes
    Route::get('/units', ['as' => 'setup.units.index.get', 'uses' => 'SetupController@getUnitsIndex']);
    Route::get('/units/modify/{id}', ['as' => 'setup.units.modify.get', 'uses' => 'SetupController@getUnitsModify']);
    Route::post('/units/modify/{id}', ['as' => 'setup.units.modify.post', 'uses' => 'SetupController@postUnitsModify']);
    Route::get('/units/activate/{id}', ['as' => 'setup.units.activate.get', 'uses' => 'SetupController@getUnitsActivate']);

}); // End Auth Filter

Route::controllers([
	'auth' => 'Auth\AuthController',
]);
