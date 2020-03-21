<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
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

    public function get(Request $request){
        $res;

        if($request->input('type')){
            $res = Cart::sortBy($request->input('type'));
        }else if($request->input('total')){
            $res = response(Cart::getTotalPrice($request->input('total')), 200);
        }else{
            $res = Cart::get();
        }

        return $res;
    }


    public function add(Request $request){
        //validation
        $this->validate($request, [
            'product' => 'required',
            'quantity' => 'required',
            'currency' => 'required'
        ]);

        return Cart::add($request);;
    }

    public function delete(Request $request){
        if(!is_null($request->input('id'))){
            return Cart::remove($request->input('id')) ? response('Removed successfully', 200) : response("Item wasn't found", 200);
        }

        Cart::deleteCookie();
        return response('Deleted successfully', 200);
    }

    public function update($id, Request $request){
        //validation
        $this->validate($request, [
            'quantity' => 'required'
        ]);

        return Cart::updateItem($id, $request->input('quantity'));
    }
}