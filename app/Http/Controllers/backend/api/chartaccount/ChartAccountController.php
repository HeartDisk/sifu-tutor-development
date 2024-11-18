<?php

namespace App\Http\Controllers\backend\api\chartaccount;

use Illuminate\Support\Str;
use App\Models\ChartAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChartAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $q = !empty(request('query')) ? request('query') : '';
        $chart_accounts = ChartAccount::latest()->where('code', 'LIKE', '%' . $q . '%')
            ->paginate(env('PAR_PAGE'));

        return response()->json(['chart_accounts' => $chart_accounts]);

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
            'code'=>'required',
            'name'=>'required',
            'type'=>'required',
            'description'=>'required',
            'is_cash_source'=>'boolean',
        ]);

        $chart_account = ChartAccount::create([
            'code'=>$request->code,
            'name'=>$request->name,
            'uid'=>Str::uuid(),
            'description'=>$request->description,
            'slug'=>Str::slug($request->code,'-'),
            'type'=>$request->type,
            'is_cash_source'=>$request->is_cash_source,
        ]);

        return response()->json(['message' => 'chart account created successfully']);
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
        $chart_account=ChartAccount::where('uid', $id)->first();

        return response()->json(['chart_account' => $chart_account]);

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
        //

        $request->validate([
            'code'=>'required',
            'name'=>'required',
            'type'=>'required',
            'description'=>'required',
            'is_cash_source'=>'boolean',
        ]);

        $chart_account = ChartAccount::where('id',$id)->update([
            'code'=>$request->code,
            'name'=>$request->name,
            'slug'=>Str::slug($request->code,'-'),
            'description'=>$request->description,
            'type'=>$request->type,
            'is_cash_source'=>$request->is_cash_source,
        ]);

        return response()->json(['message' => 'chart account updated successfully']);

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
        $del = ChartAccount::where('id', $id)->first();
        if($del){
          $del->delete();
            return response()->json(['message' => 'chart account deleted successfully']);
        }
    }
}
