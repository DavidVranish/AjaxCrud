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
            $example->field1 = $newRow['field1'];
            $example->field2 = $newRow['field2'];
            $example->field3 = $newRow['field3'];
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
