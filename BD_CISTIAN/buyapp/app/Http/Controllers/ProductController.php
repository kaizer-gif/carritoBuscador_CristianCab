<?php

namespace App\Http\Controllers;

use App\Product;
use App\Provider;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index(Request $r){

        $products = Product::with(['provider'])->get();
        return view('products.index',['products'=>$products]);
    }
    public function create(){
        $providers = Provider::all();

        return view('products.create',['providers'=> $providers]);
    }

    public function store(Request $r)
    {
        $inputs = $r->all();
        $provider = Provider::find($inputs['provider']);

        $product = new Product(['name' => $inputs['name'],
            'cantidad' => $inputs['cantidad'],
            'stock' => $inputs['stock'],
            'type' => $inputs['tipo'],
            'unit_price' => $inputs['price']]);

        $provider->products()->save($product);
        return redirect('/products');

    }

    public function edit($id){
        $providers = Provider::all();
        $product = Product::find($id);
        return view('/products.edit',['product' => $product,'providers'=>$providers]);
    }

    public function update($id, Request $r){
        $inputs = $r->all();

        $product = Product::find($id);
        $provider = Provider::find($inputs['provider']);

        $product->provider()->associate($provider);
        $product->update(['name'=> $inputs['name'],
            'cantidad'=> $inputs['cantidad'],
            'stock'=> $inputs['stock'],
            'type'=> $inputs['tipo'],
            'unit_price'=> $inputs['price']]);
        return redirect('/products');
    }

    public function destroy($id){
        $product = Product::find($id);
        $product -> delete();
        return redirect('/products');
    }

    public function list(Request $r){
        $res = $r->all();

        $products = Product::when(isset($res["search"]),function ($q) use ($res){
            return $q->where('name','like','%'.$res['search'].'%')
                ->orWhere('city','like','%'.$res['search'].'%')
                ->orWhere('country','like','%'.$res['search'].'%');
        })->with('provider')->get();
        return view('products.list',['products'=>$products]);
    }

    public function showCar(Request $r)
    {
        $res = $r->all();
        $products = null;
        Log::info($res['products']);
        if(isset($res['products']))
            $products = Product::whereIn('id',$res['products']);

        return view('products.car_shop',['products'=>$products]);
    }
}
