<?php namespace App\Http\Controllers;

use App\Models\Example;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExampleNoEditController extends Controller
{
    public function getExampleIndex()
    {
        $examples = ExampleNoEdit::get();

        return view('examples_no_edit.index')
            ->with('examples', $examples);
    }

    public function getExampleNewRow()
    {
        return view('examples_no_edit.partials.new_row');

    }
    
    public function deleteExampleRow($id)
    {
        ExampleNoEdit::destroy($id);

    }
    
    public function postExampleRows(Request $request)
    {
        $newRows = $request->input('newRows');
        $examples = [];

        foreach ($newRows as $newRow) {
            $example = new ExampleNoEdit();
            $example->name = $newRow['name'];
            $example->email = $newRow['email'];
            $example->status = $newRow['status'];
            $example->save();

            $examples[] = $example;
        }

        return view('examples_no_edit.partials.rows', 
            [
                'examples' => $examples
            ]
        );
    }
}
