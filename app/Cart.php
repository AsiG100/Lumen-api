<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Cart extends Model
{
  
    protected $fillable = [
        'cart','id','product', 'quantity', 'price', "currency"
    ];

    public function getProductAttribute($value)
    {
        return Product::find($value);
    }

    public function getQuantityAttribute($value)
    {
        return Product::find($value);
    }

    public static function get($arr = []){
        $cart = [];

        if(isset($_COOKIE['cart'])){
            $cart = $arr?: json_decode($_COOKIE['cart']);
            
            foreach($cart as $item){
                $item->product = Product::find($item->product);
            }
        }

        return $cart;
    }

    public static function add($req)
    {
        $cookie = [];
        if(isset($_COOKIE['cart'])){
            $cookie = json_decode($_COOKIE['cart']);
        }

        // $value = explode(',', $_COOKIE['cart']);
        //set ID
        $output["id"] = $cookie ? end($cookie)->id + 1 : 0;

        $output["product"] = $req->input("product"); 
        $output["quantity"] = $req->input("quantity");
        $output["currency"] = $req->input("currency"); 

        $product = Product::find($output["product"]);
        $output["priceInEUR"] = $output["currency"] === "EUR" ? $product->price : Cart::getCurrencyRate("USD")*$product->price; 

        array_push($cookie, $output);
        // $value = implode(',', $request->all());
        setcookie('cart', json_encode($cookie), '/');
        return response()->json($cookie, 201);
    }

    public static function deleteCookie()
    {
        setcookie('cart', json_encode([]), '/');
    }

    public static function updateItem($id, $quantity)
    {
        $cookie = json_decode($_COOKIE['cart']);

        if(isset($cookie)){
            foreach($cookie as $item){
                if($item->id == $id){
                    $item->quantity = $quantity;
                }
            }
        }

        return $cookie;
    }

    public static function remove($id){
        $cookie = json_decode($_COOKIE['cart']);
        $res = false;
        $arr = [];
        if(isset($cookie)){
            foreach($cookie as $key => $item){
                if($item->id != $id){
                    array_push($arr, $item);
                }else{
                    $res = true;
                }
            }
        }

        setcookie('cart', json_encode($arr), '/');

        return $res;
    }

    public static function sortBy($key)
    {
        $cookie = json_decode($_COOKIE['cart']);

        $GLOBALS["key"] = $key;
        if(array_key_exists($key, $cookie[0])){
            usort($cookie, function($a, $b)
            {
                $key = $GLOBALS["key"];
                $res;
                if($key === "product"){
                    $res = strcmp(Product::find($a->product)->name, Product::find($b->product)->name);
                }else{
                    $a = (array)$a;
                    $b = (array)$b;

                    if ($a[$key] == $b[$key]) {
                        return 0;
                    }
                    $res = ($a[$key] < $b[$key]) ? -1 : 1;
                }
        
                return $res;
            });
        }

        return Cart::get($cookie);
    }

    public static function getTotalPrice($currency)
    {
        $cookie = json_decode($_COOKIE['cart']);
        $total_price = 0;

        foreach($cookie as $item){
            $total_price += $item->priceInEUR*$item->quantity;
        }

        return $currency === 'USD' ? Cart::getCurrencyRate('USD')*$total_price : $total_price;
    }

    private static function getCurrencyRate($curr)
    {
        $res;
        if($curr === "USD"){
            $req = json_decode(file_get_contents('https://api.exchangeratesapi.io/latest?symbols=USD'));
            $res = $req->rates->USD;
        }else{
            $req = json_decode(file_get_contents('https://api.exchangeratesapi.io/latest?base=USD&symbols=EUR'));
            $res = $req->rates->EUR;
        }

        return $res;
    }

}
