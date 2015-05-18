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
    Route::get('/examples/', ['as' => 'example.index.get', 'uses' =>'ExampleController@getIndex']);

    Route::get('/examples/ajax/row/{id}/', ['as' => 'examples.ajax.row.get', 'uses' => 'ExampleController@getExampleRow']);
    Route::put('/examples/ajax/row/{id}/', ['as' => 'examples.ajax.row.put', 'uses' => 'ExampleController@putExampleRow']);
    Route::get('/examples/ajax/new_row/', ['as' => 'examples.ajax.new_row.get', 'uses' => 'ExampleController@getExampleNewRow']);
    Route::delete('/examples/ajax/row/{id}/', ['as' => 'examples.ajax.row.delete', 'uses' => 'ExampleController@deleteExampleRow']);
    Route::post('/examples/ajax/rows/', ['as' => 'examples.ajax.rows.post', 'uses' => 'ExampleController@postExampleRows']);
    Route::get('/examples/ajax/editable_row/{id}/', ['as' => 'examples.ajax.editable_row.get', 'uses' => 'ExampleController@getExampleEditRow']);

    Route::get('/examples_2_tables/', ['as' => 'examples_2_tables.index.get', 'uses' =>'Example2TableController@getIndex']);

    Route::get('/examples_2_tables/table_1/ajax/row/{id}/', ['as' => 'examples_2_tables.table_1.ajax.row.get', 'uses' => 'Example2TableController@getTable1Row']);
    Route::put('/examples_2_tables/table_1/ajax/row/{id}/', ['as' => 'examples_2_tables.table_1.ajax.row.put', 'uses' => 'Example2TableController@putTable1Row']);
    Route::get('/examples_2_tables/table_1/ajax/new_row/', ['as' => 'examples_2_tables.table_1.ajax.new_row.get', 'uses' => 'Example2TableController@getTable1NewRow']);
    Route::delete('/examples_2_tables/table_1/ajax/row/{id}/', ['as' => 'examples_2_tables.table_1.ajax.row.delete', 'uses' => 'Example2TableController@deleteTable1Row']);
    Route::post('/examples_2_tables/table_1/ajax/rows/', ['as' => 'examples_2_tables.table_1.ajax.rows.post', 'uses' => 'Example2TableController@postTable1Rows']);
    Route::get('/examples_2_tables/table_1/ajax/editable_row/{id}/', ['as' => 'examples_2_tables.table_1.ajax.editable_row.get', 'uses' => 'Example2TableController@getTable1EditRow']);

    Route::get('/examples_2_tables/table_2/ajax/row/{id}/', ['as' => 'examples_2_tables.table_2.ajax.row.get', 'uses' => 'Example2TableController@getTable2Row']);
    Route::put('/examples_2_tables/table_2/ajax/row/{id}/', ['as' => 'examples_2_tables.table_2.ajax.row.put', 'uses' => 'Example2TableController@putTable2Row']);
    Route::get('/examples_2_tables/table_2/ajax/new_row/', ['as' => 'examples_2_tables.table_2.ajax.new_row.get', 'uses' => 'Example2TableController@getTable2NewRow']);
    Route::delete('/examples_2_tables/table_2/ajax/row/{id}/', ['as' => 'examples_2_tables.table_2.ajax.row.delete', 'uses' => 'Example2TableController@deleteTable2Row']);
    Route::post('/examples_2_tables/table_2/ajax/rows/', ['as' => 'examples_2_tables.table_2.ajax.rows.post', 'uses' => 'Example2TableController@postTable2Rows']);
    Route::get('/examples_2_tables/table_2/ajax/editable_row/{id}/', ['as' => 'examples_2_tables.table_2.ajax.editable_row.get', 'uses' => 'Example2TableController@getTable2EditRow']);

    Route::get('/examples_no_edit/', ['as' => 'examples_no_edit.index.get', 'uses' =>'ExampleNoEditController@getIndex']);

    Route::get('/examples_no_edit/ajax/new_row/', ['as' => 'examples_no_edit.ajax.new_row.get', 'uses' => 'ExampleNoEditController@getExampleNoEditNewRow']);
    Route::delete('/examples_no_edit/ajax/row/{id}/', ['as' => 'examples_no_edit.ajax.row.delete', 'uses' => 'ExampleNoEditController@deleteExampleNoEditRow']);
    Route::post('/examples_no_edit/ajax/rows/', ['as' => 'examples_no_edit.ajax.rows.post', 'uses' => 'ExampleNoEditController@postExampleNoEditRows']);

}); // End Auth Filter

Route::controllers([
	'auth' => 'Auth\AuthController',
]);
