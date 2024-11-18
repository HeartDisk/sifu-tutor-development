<?php

namespace App\Http\Controllers\backend\api\student;

use Exception;
use App\Models\Product;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Customer;
use App\Models\StudentList;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StudentController extends Controller
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
        $students = Student::latest()->with('customer', 'students')
            ->paginate(env('PAR_PAGE'));

        return response()->json(['status' => true, 'students' => $students]);
    }
    public function getStudentList(Request $request)
    {
        $students = StudentList::latest()->get();
        return response()->json(['status' => true, 'students' => $students]);
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
            'register_date' => 'required',
            'admin_charge' => 'required',
            'payment_attachment' => 'file|max:5048',
            'fee_payment_date' => 'required',
            'receiving_account' => 'required|integer',
            'hour_per_subject' => 'required|integer',
            'subscription_duration' => 'required',
            'customer.full_name' => 'required',
            'customer.email' => 'required',
            'customer.phone' => 'required',
            'customer.address' => 'required|array',
            'customer.age' => 'required|integer',
            'customer.dob' => 'required',
            'customer.gender' => 'required',
            'customer.nric' => 'required',
            'subjects' => 'array',
            'students' => 'array',
        ]);
        if ($request->hasfile('payment_attachment')) {
            $name = Str::random(20);
            // dd($name);
            $name = $name . "-" . time() . '.' . $request->payment_attachment->extension();
            $request->payment_attachment->move(public_path('/student/paymentattacments'), $name);
        } else
            $name = "";


        // DB::beginTransaction();

        // try {



        $student = Student::create([
            'register_date' => $request->register_date,
            'admin_charge' => $request->admin_charge,
            'slug' => Str::slug($request->admin_charge, '-'),
            'uid' => Str::uuid(),
            'payment_attachment' => $name,
            'fee_payment_date' => $request->fee_payment_date,
            'receiving_account' => $request->receiving_account,
            'hour_per_subject' => $request->hour_per_subject,
            'subscription_duration' => $request->subscription_duration,
            'subjects' => $request->subjects,
        ]);
        foreach ($request->students as $item){
            $this->storeStudent($student,$item);
        }
        $customer = (object) $request->customer;
        $new_custormer = $student->customer()->create(
            [
                'full_name' => $customer->full_name,
                'slug' => $this->generateCustomerNumber(),
                'uid' => Str::uuid(),
                'email' => $customer->email,
                'nric' => $customer->nric,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'age' => $customer->age,
                'student_id' => $student->id,
                'dob' => $customer->dob,
                'gender' => $customer->gender,
            ]
        );

        // DB::commit();
        if ($new_custormer) {
            return response()->json([
                'status' => true,
                'message' => 'student has been created successfully'
            ]);
        }
        // all good
// } catch (Exception $e) {
//     DB::rollback();

        //     return response()->json(['message' => $e->getMessage(),'status' => false]);
//     // something went wrong
// }
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function storeStudent($student,$item){
        $last_student = StudentList::orderBy('id', 'desc')->first();
        $last_id = !empty($last_student) ? $last_student->id + 1 : 1;
        $student->students()->create([
            'age' => $item['age'],
            'dob' => $item['dob'],
            'slug'=>Str::slug($item['fullname'],'-'),
            'uid'=>Str::uuid(),
            'nric' => $item['nric'],
            'gender' => $item['gender'],
            'fullname' => $item['fullname'],
            'student_id' => "STD-00" .  $last_id,
        ]);
     }


    public function mapStudents($students)
    {
        $index = 0;
        $students_list = [];
        foreach ($students as $student) {
            $index++;
            $students_list[] = [
                'age' => $student['age'],
                'dob' => $student['dob'],
                'nric' => $student['nric'],
                'gender' => $student['gender'],
                'fullname' => $student['fullname'],
                'student_id' => "STD-00" . rand(10, 1000),
            ];
        }


        return $students_list;
    }
    public function generateStudentId($index)
    {
        $student = Student::orderBy('id', 'desc')->first();

        if (!empty($student)) {
            $last_student = collect($student->students)->last();

            if (!empty($last_student['student_id'])) {
                $id = str_replace('STD-00', '', $last_student['student_id']);
                $student_id = (int) $id + $index + 1;

            } else {
                $id = str_replace('STD-00', '', $last_student['student_id']);
                $student_id = (int) $id + $index + 1;
            }

        } else {
            $student_id = 1;
        }

        $student_id = str_pad($student_id, 3, "0", STR_PAD_LEFT);

        $student_id = "STD" . '-' . $student_id;
        return $student_id;
    }

    public function generateCustomerNumber()
    {
        $customer = Customer::orderBy('id', 'desc')->first();
        if (!empty($customer)) {
            $customer_number = (int) $customer['id'] + 1;
        } else {
            $customer_number = 1;
        }
        $perfix_name = Str::slug('CM', '-');
        $customer_number = str_pad($customer_number, 4, "0", STR_PAD_LEFT);
        // '-'.date('Ymd');
        $customer_number = $perfix_name . '-' . $customer_number;
        return $customer_number;
    }
    public function show($id)
    {
        //
        $student = Student::where('uid', $id)->with('customer','students')->first();
        return response()->json(['status' => true, 'student' => $student]);
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
        $student = Student::where('uid', $id)->with('customer','students')->first();
        return response()->json(['status' => true, 'student' => $student]);
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
            'register_date' => 'required',
            'admin_charge' => 'required',
            'payment_attachment' => 'file|max:5048',
            'fee_payment_date' => 'required',
            'receiving_account' => 'required|integer',
            'hour_per_subject' => 'required|integer',
            'subscription_duration' => 'required',
            'customer.full_name' => 'required',
            'customer.email' => 'required',
            'customer.phone' => 'required',
            'customer.address' => 'required|array',
            'customer.age' => 'required|integer',
            'customer.dob' => 'required',
            'customer.gender' => 'required',
            'customer.nric' => 'required',
            'subjects' => 'array',
        ]);
        $student = Student::where('id', $id)->first();
        if ($request->hasfile('payment_attachment')) {
            $name = Str::random(20);
            // dd($name);
            $name = $name . "-" . time() . '.' . $request->payment_attachment->extension();
            $request->payment_attachment->move(public_path('/student/paymentattacments'), $name);
        } else {
            $name = $student->payment_attachment;

        }


        DB::beginTransaction();

        try {

            $update_student = Student::where('id', $id)->update([
                'register_date' => $request->register_date,
                'admin_charge' => $request->admin_charge,
                'slug' => Str::slug($request->admin_charge, '-'),
                'uid' => Str::uuid(),
                'payment_attachment' => $name,
                'fee_payment_date' => $request->fee_payment_date,
                'receiving_account' => $request->receiving_account,
                'hour_per_subject' => $request->hour_per_subject,
                'subscription_duration' => $request->subscription_duration,
                'subjects' => $request->subjects,
            ]);
            $customer = (object) $request->customer;

            Customer::where('student_id', $id)->delete();
            $new_custormer = Customer::create([
                'full_name' => $customer->full_name,
                'slug' => Str::slug($customer->full_name, '-'),
                'student_id' => $student->id,
                'uid' => Str::uuid(),
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'age' => $customer->age,
                'dob' => $customer->dob,
                'gender' => $customer->gender,
            ]);

            DB::commit();
            if ($new_custormer) {
                return response()->json(['status' => true, 'message' => 'student has been update successfully']);
            }
            // all good
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['message' => $e->getMessage(), 'status' => false]);
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
        $student = Student::where('id', $id)->first();
        $new_custormer = Customer::where('student_id', $id)->delete();
        $student->delete();
        return response()->json(['status' => true, 'message' => 'student destroy has been successfully']);
    }
}
