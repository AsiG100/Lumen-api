<?php

namespace App\Http\Controllers;

use App\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function showAllCatalogs(){
        return response()->json(Catalog::all());
    }

    public function showOneCatalog($id){
        return response()->json(Catalog::find($id));
    }

    public function showAllProductsInCatalog($id){
        return response()->json(Catalog::find($id)->items);
    }

    public function create(Request $request){
        //validation
        $this->validate($request, [
            'name' => 'required'
        ]);

        // insert record
        $catalog = Catalog::create($request->all());
        return response()->json($catalog, 201);
    }

    public function update($id, Request $request){
        $catalog = Catalog::findOrFail($id);
        $catalog->update($request->all());
        return response()->json($catalog, 200);
    }

    public function delete($id){
        Catalog::findOrFail($id)->delete();
        return response('Deleted successfully', 200);
    }
}
