<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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

    public function showAllProducts(){
        return response()->json(Product::all());
    }

    public function showOneProduct($item){
        $id = Product::find($item);
        $res;

        if($id){
            $res = response()->json($id);
        }else{

            $res = response()->json(Product::where('name', 'LIKE', '%'.$item.'%')->get());
        }

        return $res;
    }

    public function create(Request $request){
        //validation
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required'
        ]);

        // insert record
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    public function update($id, Request $request){
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return response()->json($product, 200);
    }

    public function delete($id){
        Product::findOrFail($id)->delete();
        return response('Deleted successfully', 200);
    }
}
