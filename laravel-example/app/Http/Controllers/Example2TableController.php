<?php namespace App\Http\Controllers;


use App\Models\Example;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Example2TableController extends Controller
{
    public function getIndex()
    {
        $examples = ExampleTable1::get();

        return view('examples_2_tables.index')
            ->with('examples', $examples);
    }

    public function putTable1Row(Request $request, $id)
    {
        $example = ExampleTable1::find($id);
        $example->name = $request->input('name');
        $example->email = $request->input('email');
        $example->status = $request->input('status');
        $example->save();

        return view('examples_2_tables.partials.table_1.rows', 
            [
                'examples' => [$example]
            ]
        );
    }

    public function getTable1NewRow()
    {
        return view('examples_2_tables.partials.table_1.new_row');

    }
    
    public function deleteTable1Row($id)
    {
        ExampleTable1::destroy($id);

    }

    public function getTable1Row($id)
    {
        $example = ExampleTable1::find($id);
    
        return view('examples_2_tables.partials.table_1.rows', 
            [
                'examples' => [$example]
            ]
        );
    }
    
    public function postTable1Rows(Request $request)
    {
        $newRows = $request->input('newRows');
        $examples = [];

        foreach ($newRows as $newRow) {
            $example = new ExampleTable1();
            $example->name = $newRow['name'];
            $example->email = $newRow['email'];
            $example->status = $newRow['status'];
            $example->save();

            $examples[] = $example;
        }

        return view('examples_2_tables.partials.table_1.rows', 
            [
                'examples' => $examples
            ]
        );
    }

    public function getTable1EditRow($id)
    {
        $example = ExampleTable1::find($id);
        
        return view('examples_2_tables.partials.table_1.edit_rows', 
            [
                'examples' => [$example]
            ]
        );
    }

    public function putTable2Row(Request $request, $id)
    {
        $example = ExampleTable2::find($id);
        $example->name = $request->input('name');
        $example->email = $request->input('email');
        $example->status = $request->input('status');
        $example->save();

        return view('examples_2_tables.partials.table_2.rows', 
            [
                'examples' => [$example]
            ]
        );
    }

    public function getTable2NewRow()
    {
        return view('examples_2_tables.partials.table_2.new_row');

    }
    
    public function deleteTable2Row($id)
    {
        ExampleTable2::destroy($id);

    }

    public function getTable2Row($id)
    {
        $example = ExampleTable2::find($id);
    
        return view('examples_2_tables.partials.table_2.rows', 
            [
                'examples' => [$example]
            ]
        );
    }
    
    public function postTable2Rows(Request $request)
    {
        $newRows = $request->input('newRows');
        $examples = [];

        foreach ($newRows as $newRow) {
            $example = new ExampleTable2();
            $example->name = $newRow['name'];
            $example->email = $newRow['email'];
            $example->status = $newRow['status'];
            $example->save();

            $examples[] = $example;
        }

        return view('examples_2_tables.partials.table_2.rows', 
            [
                'examples' => $examples
            ]
        );
    }

    public function getTable2EditRow($id)
    {
        $example = ExampleTable2::find($id);
        
        return view('examples_2_tables.partials.table_2.edit_rows', 
            [
                'examples' => [$example]
            ]
        );
    }
}
