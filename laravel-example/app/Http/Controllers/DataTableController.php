<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataTableController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
            $this->middleware('auth');
    }

    public function postDataTableUpdateLength(Request $request)
    {
            \Session::put($request->input('key'), $request->input('length'));
    }
}