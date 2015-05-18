<?php namespace App\Http\Controllers;


use App\Models\Example;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function getExampleIndex()
    {
        $examples = Example::get();

        return view('setup.examples.index')
            ->with('examples', $examples);
    }

    public function putExampleRow(Request $request, $id)
    {
        $example = Example::find($id);
        $example->field1 = $request->input('field1');
        $example->field2 = $request->input('field2');
        $example->field3 = $request->input('field3');
        $example->save();

        return view('setup.examples.partials.rows', 
            [
                'examples' => [$example]
            ]
        );
    }

    public function getExampleNewRow()
    {
        return view('setup.examples.partials.new_row');

    }
    
    public function deleteExampleRow($id)
    {
        Example::destroy($id);

    }

    public function getExampleRow($id)
    {
        $example = Example::find($id);
    
        return view('setup.examples.partials.rows', 
            [
                'examples' => [$example]
            ]
        );
    }
    
    public function postExampleRows(Request $request)
    {
        $newRows = $request->input('newRows');
        $examples = [];

        foreach ($newRows as $newRow) {
            $example = new Example();
            $example->field1 = $newRow['field1'];
            $example->field2 = $newRow['field2'];
            $example->field3 = $newRow['field3'];
            $example->save();

            $examples[] = $example;
        }

        return view('setup.examples.partials.rows', 
            [
                'examples' => $examples
            ]
        );
    }

    public function getExampleEditRow($id)
    {
        $example = Example::find($id);
        
        return view('setup.examples.partials.edit_rows', 
            [
                'examples' => [$example]
            ]
        );
    }

    public function postValidateUnique(Request $request)
    {   
        $value = explode(":", $request->input('name'));
        $name = $value[0];
        $id = $value[1];
        $examples = Example::where('name', '=', $name)->where('id', '<>', $id)->get();
        
        if (count($examples) > 0) {
            return \Response::json([ 'valid' => false ]);

        } else {
            return \Response::json([ 'valid' => true ]);

        }
    }
}
