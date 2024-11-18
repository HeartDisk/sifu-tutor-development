<?php

namespace App\Http\Controllers\backend\api\product;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $q=!empty(request('query')) ? request('query') : '';
        $products=Product::latest()->where('name', 'LIKE', '%' . $q . '%')
        ->paginate(env('PAR_PAGE'));

        return response()->json(['products'=>$products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name'=>'required',
            'code'=>'required',
            'brand'=>'required',
            'category'=>'required',
            'cost'=>'required|integer',
            'price'=>'required|integer',
            'description'=>'required',
            'remarks'=>'min:10',
            'tuition_service'=>'boolean',
        ]);

      $product=  Product::create([
        'name'=>$request->name,
        'code'=>$request->code,
        'slug'=>Str::slug($request->name,'-'),
        'uid'=>Str::uuid(),
        'brand'=>$request->brand,
        'category'=>$request->category,
        'cost'=>$request->cost,
        'price'=>$request->price,
        'description'=>$request->description,
        'remarks'=>$request->remarks,
        'tuition_service'=>$request->tuition_service,
        'services'=>$request->services,
      ]);

      return response()->json(['product'=>$product,'message'=>'product has been created successfully']);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = Product::where('uid', $id)->first();
        return response()->json(['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $product = Product::where('uid', $id)->first();
        return response()->json(['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name'=>'required',
            'code'=>'required',
            'brand'=>'required',
            'category'=>'required',
            'cost'=>'required|integer',
            'price'=>'required|integer',
            'description'=>'required',
            'remarks'=>'min:10',
            'tuition_service'=>'boolean',
        ]);

        $product=  Product::where('id',$id)
        ->update([
            'name'=>$request->name,
            'code'=>$request->code,
            'slug'=>Str::slug($request->name,'-'),
            'brand'=>$request->brand,
            'category'=>$request->category,
            'cost'=>$request->cost,
            'price'=>$request->price,
            'description'=>$request->description,
            'remarks'=>$request->remarks,
            'tuition_service'=>$request->tuition_service,
            'services'=>$request->services,
          ]);

        return response()->json(['product'=>$product,'message'=>'product has been created successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $product = Product::where(['id' => $id])->delete();
        return response()->json(['message' => 'product has been destroy successfuly']);
    }
}
