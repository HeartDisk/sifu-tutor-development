<?php

namespace App\Http\Controllers\backend\api\staff;

use App\Models\Staff;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaffController extends Controller
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
        $staffs = Staff::latest()
            ->where('full_name', 'like', '%'.$q . '%')
            
            ->paginate(env('PAR_PAGE'));

        return response()->json(['staffs' => $staffs]);
    }
    public function getStaffs(){
        $staffs = Staff::latest()->get();
        return response()->json(['staffs' =>$staffs ]);
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
            'start_date'=>'required',
            'designation'=>'required',
            'basic_salary'=>'required|integer',
            'type'=>'required',
            'full_name'=>'required',
            'nick_name'=>'required',
            'gender'=>'required',
            'age'=>'required',
            'dob'=>'required',
            'nric'=>'required',
            'marital_status'=>'required',
            'no_children'=>'required|integer',
            'spouse_working'=>'boolean',
            'email'=>'required|unique:staffs',
            'phone'=>'required|unique:staffs',
            'address'=>'array',
            'remark'=>'required|min:10',
            'epf_no'=>'required',
            'tax_no'=>'required',
            ]);

        $staff = Staff::create([
            'uid'=>Str::uuid(),
            'slug'=>Str::slug($request->full_name,'-'),
            'start_date'=>$request->start_date,
            'designation'=>$request->designation,
            'basic_salary'=>$request->basic_salary,
            'type'=>$request->type,
            'full_name'=>$request->full_name,
            'nick_name'=>$request->nick_name,
            'gender'=>$request->gender,
            'age'=>$request->age,
            'dob'=>$request->dob,
            'nric'=>$request->nric,
            'marital_status'=>$request->marital_status,
            'no_children'=>$request->no_children,
            'spouse_working'=>$request->spouse_working,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'remark'=>$request->remark,
            'epf_no'=>$request->epf_no,
            'tax_no'=>$request->tax_no,
        ]);

        return response()->json(['message' => 'staff has been created successfully']);
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
        $staff = Staff::where('uid',$id)->first();

        return response()->json(['staff' => $staff]);
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
            'start_date'=>'required',
            'designation'=>'required',
            'basic_salary'=>'required|integer',
            'type'=>'required',
            'full_name'=>'required',
            'nick_name'=>'required',
            'gender'=>'required',
            'age'=>'required',
            'dob'=>'required',
            'nric'=>'required',
            'marital_status'=>'required',
            'no_children'=>'required|integer',
            'spouse_working'=>'boolean',
            'email'=>'required|unique:staffs,id,'.$id,
            'phone'=>'required|unique:staffs,id,'.$id,
            'address'=>'array',
            'remark'=>'required|min:10',
            'epf_no'=>'required',
            'tax_no'=>'required',
            ]);

        $staff = Staff::where('id',$id)->update([
            'slug'=>Str::slug($request->full_name,'-'),
            'start_date'=>$request->start_date,
            'designation'=>$request->designation,
            'basic_salary'=>$request->basic_salary,
            'type'=>$request->type,
            'full_name'=>$request->full_name,
            'nick_name'=>$request->nick_name,
            'gender'=>$request->gender,
            'age'=>$request->age,
            'dob'=>$request->dob,
            'nric'=>$request->nric,
            'marital_status'=>$request->marital_status,
            'no_children'=>$request->no_children,
            'spouse_working'=>$request->spouse_working,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'remark'=>$request->remark,
            'epf_no'=>$request->epf_no,
            'tax_no'=>$request->tax_no,
        ]);

        return response()->json(['message'=>'staff has been updated successfully']);
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
        $staff = Staff::where('id',$id)->first();

        if ($staff) {
            $staff->delete();
            return response()->json(['message'=>'staff has been deleted successfully']);
        }
        return response()->json(['message' => 'staff has been removed successfully']);
    }
}
