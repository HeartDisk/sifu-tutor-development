<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\ChartOfAccountCategory;
class ChartOfAccountCategoryController extends Controller
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
        $data=ChartOfAccountCategory::all();
        return  view("chartofaccountcategory.index",compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view("chartofaccountcategory.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $data=new ChartOfAccountCategory();
       $data->name=$request->name;
       $data->save();
       return redirect("/chart-of-accounts-category");
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
        $data=ChartOfAccountCategory::find($id);
        return view("chartofaccountcategory.edit",compact('data'));
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
        $data=ChartOfAccountCategory::find($id);
        $data->name=$request->name;
        $data->save();
        return redirect("/chart-of-accounts-category");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ChartOfAccountCategory::find($id)->delete();
        return redirect("/chart-of-accounts-category");

    }
}
