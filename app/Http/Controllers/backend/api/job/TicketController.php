<?php

namespace App\Http\Controllers\backend\api\job;

use App\Models\Student;
use App\Models\Customer;
use App\Models\JobTicket;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TicketController extends Controller
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
        $tickets = JobTicket::latest()->with('customer')
                               ->paginate(env('PAR_PAGE'));

        return response()->json(['status' => true, 'tickets' => $tickets]);
    }
    public function getStudentList(Request $request){
        $students = Student::latest()->get()->pluck('students')->all();
        $students= array_filter( $students);
        $students = collect($students)->collapse();
        return response()->json(['status' => true,'students' => $students]);
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
            'register_date'=>'required',
            'admin_charge'=>'required',
            'payment_attachment'=>'file|max:5048',
            'fee_payment_date'=>'required',
            'receiving_account'=>'required',
            'subscription_duration'=>'required',
            'customer.full_name'=>'required',
            'customer.email'=>'required',
            'customer.phone'=>'required',
            'customer.address'=>'required|array',
            'customer.age'=>'required|integer',
            'customer.dob'=>'required',
            'customer.gender'=>'required',
            'customer.nric' =>'required',
            'subjects'=>'array',
            'students' =>'array',
        ]);
        if ($request->hasfile('payment_attachment')) {
            $name=Str::random(20);
            // dd($name);
            $name = $name . "-" . time() . '.' . $request->payment_attachment->extension();
            $request->payment_attachment->move(public_path('/student/paymentattacments'), $name);
        }
        else
            $name = "";


        DB::beginTransaction();

try {



    $ticket = JobTicket::create([
        'register_date'=>$request->register_date,
        'admin_charge'=>$request->admin_charge,
        'slug' => Str::slug($request->admin_charge,'-'),
        'uid'=>Str::uuid(),
        'payment_attachment'=>$name ,
        'remarks'=>$request->remarks,
        'estimate_commission'=>$request->estimate_commission,
        'fee_payment_date'=>$request->fee_payment_date,
        'student_address'=>$request->student_address,
        'receiving_account'=>$request->receiving_account,
        'subscription_duration'=>$request->subscription_duration,
        'subjects'=>$request->subjects,
        'students'=>$this->mapStudents($request->students),
    ]);
            $ticket->slug = "JT-000" . $ticket->id;
            $ticket->save();
            $customer =(object) $request->customer;
            $new_custormer= $ticket->customer()->create(
                [
                    'full_name'=>$customer->full_name,
                    'slug' => $this->generateCustomerNumber(),
                    'uid'=>Str::uuid(),
                    'email'=>$customer->email,
                    'nric'=>$customer->nric,
                    'phone'=>$customer->phone,
                    'address'=>$customer->address,
                    'age'=>$customer->age,
                    'dob'=>$customer->dob,
                    'gender'=>$customer->gender,
                ]
            );


    DB::commit();
    if($new_custormer){
        return response()->json([
           'status' => true,
            'message' =>'job ticket has been created successfully']);
    }
    // all good
} catch (Exception $e) {
    DB::rollback();

    return response()->json(['message' => $e->getMessage(),'status' => false]);
    // something went wrong
}
        //


    }

    public function mapStudents($students){
        $index = 0;
        $students_list = [];
        foreach($students as $student){
            $index++;
            $students_list[]=[
                'age' => $student['age'],
                'dob' => $student['dob'],
                'nric' => $student['nric'],
                'gender' => $student['gender'],
                'fullname' =>  $student['fullname'],
                'student_id'=>"STD-00".rand(10,1000),
            ];
        }


        return $students_list;
    }
     public function generateStudentId($index){
        $student=Student::orderBy('id','desc')->first();

        if(!empty($student)){
            $last_student = collect($student->students)->last();

            if(!empty($last_student['student_id']))
            {
                    $id=str_replace('STD-00', '', $last_student['student_id']);
                    $student_id= (int)$id+$index+1;

            }
            else{
                $id=str_replace('STD-00', '', $last_student['student_id']);
                $student_id= (int)$id+$index+1;
            }

        }
        else{
            $student_id= 1;
        }

        $student_id=str_pad($student_id, 3, "0", STR_PAD_LEFT);

        $student_id = "STD". '-' . $student_id;
        return $student_id;
    }

     public function generateCustomerNumber(){
        $customer=Customer::orderBy('id','desc')->first();
        if(!empty($customer)){
           $customer_number= (int)$customer['id']+1;
        }
        else{
            $customer_number= 1;
        }
        $perfix_name = Str::slug('CM', '-');
        $customer_number=str_pad($customer_number, 4, "0", STR_PAD_LEFT);
        // '-'.date('Ymd');
        $customer_number = $perfix_name . '-' . $customer_number;
        return $customer_number;
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
        $ticket=JobTicket::where('uid',$id)->with('customer')->first();
        return response()->json(['status' => true,'ticket'=>$ticket]);
    }

    public function edit($id)
    {
        //
        $ticket=JobTicket::where('uid',$id)->with('customer')->first();
        return response()->json(['status' => true,'ticket'=>$ticket]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


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
            'register_date'=>'required',
            'admin_charge'=>'required',
            // 'payment_attachment'=>'file|max:5048',
            'fee_payment_date'=>'required',
            'receiving_account'=>'required',
            'subscription_duration'=>'required',
            'customer.full_name'=>'required',
            'customer.email'=>'required',
            'customer.phone'=>'required',
            'customer.address'=>'required|array',
            'customer.age'=>'required|integer',
            'customer.dob'=>'required',
            'customer.gender'=>'required',
            'customer.nric' =>'required',
        ]);
        $ticket = JobTicket::where('id', $id)->first();
        if ($request->hasfile('payment_attachment')) {
            $name=Str::random(20);
            // dd($name);
            $name = $name . "-" . time() . '.' . $request->payment_attachment->extension();
            $request->payment_attachment->move(public_path('/ticket/paymentattacments'), $name);
        }
        else
        {
            $name =  $ticket->payment_attachment;

        }


        DB::beginTransaction();

try {

    $update_student = JobTicket::where('id',$id)->update([
        'register_date'=>$request->register_date,
        'admin_charge'=>$request->admin_charge,
        'payment_attachment'=>$name ,
        'remarks'=>$request->remarks,
        'estimate_commission'=>$request->estimate_commission,
        'fee_payment_date'=>$request->fee_payment_date,
        'student_address'=>$request->student_address,
        'receiving_account'=>$request->receiving_account,
        'subscription_duration'=>$request->subscription_duration,
        'subjects'=>$request->subjects,
        'students'=>$this->mapStudents($request->students),
    ]);
            $customer =(object) $request->customer;

        $ticket->customer()->delete();
   $new_custormer=   $ticket->customer()->create([
        'full_name'=>$customer->full_name,
        'slug' => $this->generateCustomerNumber(),
        'uid'=>Str::uuid(),
        'email'=>$customer->email,
        'phone'=>$customer->phone,
        'address'=>$customer->address,
        'age'=>$customer->age,
        'dob'=>$customer->dob,
        'gender'=>$customer->gender,
    ]);

    DB::commit();
    if($new_custormer){
        return response()->json(['status' => true,'message' =>'student has been update successfully']);
    }
    // all good
} catch (Exception $e) {
    DB::rollback();

    return response()->json(['message' => $e->getMessage(),'status' => false]);
    // something went wrong
}


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
    }
}
