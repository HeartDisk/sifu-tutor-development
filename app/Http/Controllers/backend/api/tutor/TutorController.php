<?php

namespace App\Http\Controllers\backend\api\tutor;

use App\Models\Tutor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TutorController extends Controller
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
        $tutors = Tutor::latest()
            ->where('full_name', 'like', '%'.$q . '%')
            ->orWhere('email', 'like', '%'.$q . '%')
            ->paginate(env('PAR_PAGE'));

        return response()->json(['tutors' => $tutors]);
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
            'start_date'=>'required',
            'full_name'=>'required',
            'nick_name'=>'required',
            'gender'=>'required',
            'age'=>'required',
            'dob'=>'required',
            'nric'=>'required',
            'marital_status'=>'required',
            'training_date'=>'required',
            'shirt_size'=>'required|integer',
            'bank_name'=>'required',
            'back_account_no'=>'required',
            'email'=>'required|unique:tutors',
            'phone'=>'required|unique:tutors',
            'address'=>'array',
            'remark'=>'required|min:10',
            'fee_payment_date'=>'required',
            'receiving_account'=>'required',
            ]);
        $last_tutor = Tutor::latest()->first();
        $last_tutor = !empty($last_tutor) ? $last_tutor->id  : 1;
        $staff = Tutor::create([
            'uid'=>Str::uuid(),
            'slug'=>Str::slug($request->full_name,'-'),
            'tutor_id'=>"T00".$last_tutor+1,
            'start_date'=>$request->start_date,
            'training_date'=>$request->training_date,
            'shirt_size'=>$request->shirt_size,
            'bank_name'=>$request->bank_name,
            'bank_account'=>$request->bank_account,
            'full_name'=>$request->full_name,
            'nick_name'=>$request->nick_name,
            'gender'=>$request->gender,
            'age'=>$request->age,
            'dob'=>$request->dob,
            'nric'=>$request->nric,
            'marital_status'=>$request->marital_status,
            'fee_payment_date'=>$request->fee_payment_date,
            'receiving_account'=>$request->receiving_account,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'remark'=>$request->remark,

        ]);

        return response()->json(['message' => 'tutor has been created successfully']);

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
        $tutor = Tutor::where('uid',$id)->first();

        return response()->json(['tutor' => $tutor]);
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
            'full_name'=>'required',
            'nick_name'=>'required',
            'gender'=>'required',
            'age'=>'required',
            'dob'=>'required',
            'nric'=>'required',
            'marital_status'=>'required',
            'training_date'=>'required',
            'shirt_size'=>'required|integer',
            'bank_name'=>'required',
            'back_account_no'=>'required',
            'email'=>'required|unique:tutors,id,'.$id,
            'phone'=>'required|unique:tutors,id'.$id,
            'address'=>'array',
            'remark'=>'required|min:10',
            'fee_payment_date'=>'required',
            'receiving_account'=>'required',
            ]);
        $last_tutor = Tutor::latest()->first();
        $last_tutor = !empty($last_tutor) ? $last_tutor->id  : 1;
        $staff = Tutor::create([
            'uid'=>Str::uuid(),
            'slug'=>Str::slug($request->full_name,'-'),
            'tutor_id'=>"T00".$last_tutor+1,
            'start_date'=>$request->start_date,
            'training_date'=>$request->training_date,
            'shirt_size'=>$request->shirt_size,
            'bank_name'=>$request->bank_name,
            'bank_account'=>$request->bank_account,
            'full_name'=>$request->full_name,
            'nick_name'=>$request->nick_name,
            'gender'=>$request->gender,
            'age'=>$request->age,
            'dob'=>$request->dob,
            'nric'=>$request->nric,
            'marital_status'=>$request->marital_status,
            'fee_payment_date'=>$request->fee_payment_date,
            'receiving_account'=>$request->receiving_account,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'remark'=>$request->remark,

        ]);

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
        $tutor = Tutor::where('uid',$id)->first();
        if($tutor){
             $tutor->delete();
            return response()->json(['message' => 'tutor has been deleted']);
        }

        return response()->json(['message' => 'tutor not found'],404);
    }
}
