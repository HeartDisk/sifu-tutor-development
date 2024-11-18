<?php

namespace App\Http\Controllers\backend\api\expenditure;

use App\Models\Expenditure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\JournalLedger;
use App\Http\Controllers\Controller;

class ExpenditureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = !empty(request('query')) ? request('query') : '';
        $expenditures = Expenditure::latest()
            ->where('description', 'like', '%'.$q . '%')
            ->paginate(env('PAR_PAGE'));

        return response()->json(['expenditures' => $expenditures]);
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
        $request->validate([
            'occurance_date'=>'required',
            'category'=>'required',
            'description'=>'required',
            'chart_of_account'=>'required',
            'quantity'=>'required',
            'cost_per_unit'=>'required',
            'paying_account'=>'required',
            'payment_date'=>'required',
            'remarks'=>'required',
            ]);

        $attachment = null;
        if($request->hasFile('attachment')){
            $name = singleImgUpload($request,"img/expenditures");
            $attachment = $name;
        }
        
        $expenditure = Expenditure::create([
            'uid'=>Str::uuid(),
            'slug'=>Str::slug($request->description,'-'),
            'occurance_date'=>$request->occurance_date,
            'category'=>$request->category,
            'description'=>$request->description,
            'chart_of_account'=>$request->chart_of_account,
            'quantity'=>$request->quantity,
            'cost_per_unit'=>$request->cost_per_unit,
            'paying_account'=>$request->paying_account,
            'payment_date'=>$request->payment_date,
            'attachment'=>$attachment,
            'remarks'=>$request->remarks,
        ]);

        return response()->json(['message' => 'Expenditure has been created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expenditure = Expenditure::where('uid',$id)->first();

        return response()->json(['expenditure' => $expenditure]);
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
        $request->validate([
            'occurance_date'=>'required',
            'category'=>'required',
            'description'=>'required',
            'chart_of_account'=>'required',
            'quantity'=>'required',
            'cost_per_unit'=>'required',
            'paying_account'=>'required',
            'payment_date'=>'required',
            'remarks'=>'required',
            ]);

        if($request->hasFile('attachment')){
            $name = singleImgUpload($request,"img/expenditures");
            Expenditure::where('id',$id)->update([
                'attachment'=>$name,
            ]);
        }
        
        $expenditure = Expenditure::where('id',$id)->update([
            'occurance_date'=>$request->occurance_date,
            'category'=>$request->category,
            'description'=>$request->description,
            'chart_of_account'=>$request->chart_of_account,
            'quantity'=>$request->quantity,
            'cost_per_unit'=>$request->cost_per_unit,
            'paying_account'=>$request->paying_account,
            'payment_date'=>$request->payment_date,
            'remarks'=>$request->remarks,
        ]);

        return response()->json(['message' => 'Expenditure has been updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expenditure = Expenditure::where('id',$id)->first();

        if ($expenditure) {
            $expenditure->delete();
            return response()->json(['message'=>'Expenditure has been deleted successfully']);
        }
        return response()->json(['message' => 'Expenditure has been removed successfully']);
    }
}
