<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccountCategory;
use App\Models\ChartOfAccountSubCategory;
use Illuminate\Http\Request;

class ChartOfAccountSubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ChartOfAccountSubCategory::with('category')->get();
        return  view("chartofaccountsubcategory.index",compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories=ChartOfAccountCategory::all();
        return view("chartofaccountsubcategory.create",compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=new ChartOfAccountSubCategory();
        $data->name=$request->name;
        $data->category_id=$request->category_id;
        $data->save();
        return redirect("/chart-of-accounts-subcategory");
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=ChartOfAccountSubCategory::find($id);
        $categories=ChartOfAccountCategory::all();
        return view("chartofaccountsubcategory.edit",compact('data','categories'));
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
        $data=ChartOfAccountSubCategory::find($id);
        $data->name=$request->name;
        $data->category_id=$request->category_id;
        $data->save();
        return redirect("/chart-of-accounts-subcategory");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ChartOfAccountSubCategory::find($id)->delete();
        return redirect("/chart-of-accounts-subcategory");

    }

    public function getCategoriesById($id)
    {
        $data=ChartOfAccountSubCategory::where("category_id",$id)->get();
        return response()->json($data,200);

    }
}
