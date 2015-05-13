<?php namespace App\Http\Controllers\Setup;


use App\Models\Carrier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarrierController extends Controller
{
    public function getCarrierIndex()
    {
        $carriers = Carrier::get();

        return view('setup.carriers.index')
            ->with('carriers', $carriers);
    }

    public function putCarrierRow(Request $request, $id)
    {
        $carrier = Carrier::find($id);
        $carrier->name = $request->input('name');
        $carrier->email = $request->input('email');
        $carrier->status = $request->input('status');
        $carrier->save();

        return view('setup.carriers.partials.rows', 
            [
                'carriers' => [$carrier]
            ]
        );
    }

    public function getCarrierNewRow()
    {
        return view('setup.carriers.partials.new_row');

    }
    
    public function deleteCarrierRow($id)
    {
        Carrier::destroy($id);

    }

    public function getCarrierRow($id)
    {
        $carrier = Carrier::find($id);
    
        return view('setup.carriers.partials.rows', 
            [
                'carriers' => [$carrier]
            ]
        );
    }
    
    public function postCarrierRows(Request $request)
    {
        $newRows = $request->input('newRows');
        $carriers = [];

        foreach ($newRows as $newRow) {
            $carrier = new Carrier();
            $carrier->name = $newRow['name'];
            $carrier->email = $newRow['email'];
            $carrier->status = $newRow['status'];
            $carrier->save();

            $carriers[] = $carrier;
        }

        return view('setup.carriers.partials.rows', 
            [
                'carriers' => $carriers
            ]
        );
    }

    public function getCarrierEditRow($id)
    {
        $carrier = Carrier::find($id);
        
        return view('setup.carriers.partials.edit_rows', 
            [
                'carriers' => [$carrier]
            ]
        );
    }

    public function postValidateUnique(Request $request)
    {   
        $value = explode(":", $request->input('name'));
        $name = $value[0];
        $id = $value[1];
        $carriers = Carrier::where('name', '=', $name)->where('id', '<>', $id)->get();
        
        if (count($carriers) > 0) {
            return \Response::json([ 'valid' => false ]);

        } else {
            return \Response::json([ 'valid' => true ]);

        }
    }
}
