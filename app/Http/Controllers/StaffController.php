<?php

namespace App\Http\Controllers;

use App\Mail\NewStaffRegistration;
use App\Mail\StaffPaymentSlip;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Spatie\Permission\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Swift_TransportException;

class StaffController extends Controller
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
        //
    }


    public function downloadStaffPaymentSlip($id)
    {


        $payment_slip = DB::table('staff_payments')->join("staffs", "staff_payments.staff_id", "=", "staffs.id")
            ->select("staff_payments.*", "staffs.full_name as name")
            ->where('staff_payments.id', '=', $id)->orderBy('id', 'desc')->first();
        $file = public_path() . "/staffPaymentSlipPDF" . "/" . "staff-Payment-Slip-" . $payment_slip->id . ".pdf";

        if (file_exists($file)) {
            $headers = array('Content-Type: application/pdf',);
            return Response::download($file, "staff-Payment-Slip-" . $payment_slip->id, $headers);
        } else {
            $pdf = PDF::loadView('staff.staffPaymentSlipPDF', [
                'payment_slip' => $payment_slip,

            ]);
            $pdf->save(public_path('staffPaymentSlipPDF') . "/" . "staff-Payment-Slip-" . $payment_slip->id . ".pdf");
            $file = public_path() . "/staffPaymentSlipPDF" . "/" . "staff-Payment-Slip-" . $payment_slip->id . ".pdf";
            $headers = array('Content-Type: application/pdf',);
            return Response::download($file, "staff-Payment-Slip-" . $payment_slip->id, $headers);
        }
    }

    public function sendStaffPaymentSlip($id)
    {
        $payment_slip = DB::table('staff_payments')->join("staffs", "staff_payments.staff_id", "=", "staffs.id")
            ->select("staff_payments.*", "staffs.full_name as name", "staffs.email as staff_email")
            ->where('staff_payments.id', '=', $id)->orderBy('id', 'desc')->first();

        $emailBody = "Please find attached Invoice.";

        $pdfPath = public_path('staffPaymentSlipPDF/staff-Payment-Slip-' . $payment_slip->id . '.pdf');

        // Send the email
        Mail::to('binasift@gmail.com')->send(new StaffPaymentSlip($emailBody, $pdfPath));
        Mail::to($payment_slip->staff_email)->send(new StaffPaymentSlip($emailBody, $pdfPath));


        return redirect()->back()->with("success", "Email sent successfully");

    }


    public function checkStaffDuplicateEmail(Request $request)
    {

        $tables = ['customers', 'staffs', 'tutors', 'users'];
        $results = [];

        foreach ($tables as $table) {
            $result = DB::table($table)
                ->where('email', $request->customerEmail)
                ->first();

            if ($result) {
                $results[$table] = $result;
            }
        }


        if (!empty($results)) {
            return response()->json(["recordFound" => true], 200);
        } else {
            return response()->json(["recordFound" => false], 200);
        }
    }

    public function checkStaffDuplicatePhone(Request $request)
    {
        $tables = ['customers', 'staffs', 'tutors', 'users'];

        $found = false; // Flag to indicate if a record is found

        if (isset($request->customerWhatsapp)) {
            foreach ($tables as $table) {
                // Search for a record based on WhatsApp number
                $existingRecord = DB::table($table)
                    ->where('whatsapp', $request->customerWhatsapp)
                    ->first();
                if ($existingRecord) {
                    $found = true;
                    break; // Stop the loop if a record is found
                }
            }
        } else {
            foreach ($tables as $table) {
                if ($table == "tutors") {
                    $existingRecord = DB::table($table)
                        ->where('phoneNumber', $request->customerMobile)
                        ->first();
                } else {
                    $existingRecord = DB::table($table)
                        ->where('phone', $request->customerMobile)
                        ->first();
                }

                if ($existingRecord) {
                    $found = true;
                    break;
                }
            }
        }

        if ($found) {
            return response()->json(["recordFound" => true], 200);
        } else {
            return response()->json(["recordFound" => false], 200);
        }

    }


    public function deleteStaff($id)
    {
        //
        $staff_values = array('is_deleted' => 1);

        $var1 = DB::table('staffs')->where('id', $id)->update($staff_values);

        return redirect()->back();


    }

    public function getStaffCommissionById($id)
    {
        $staff = DB::table('staffs')->where("id", $id)->first();
        $data = [];
        $data["salary"] = $staff->basic_salary;

        $monthlyData = DB::table('job_tickets')
            ->leftJoin('staffs', 'job_tickets.admin_charge', '=', 'staffs.id')
            ->leftjoin('invoices', 'job_tickets.id', '=', 'invoices.ticketID')
            ->select(
                'staffs.full_name as tutor_name',
                DB::raw('MONTHNAME(job_tickets.created_at) as month_name'),
                DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                DB::raw('SUM(job_tickets.totalPrice) as total_amount')
            )
            ->groupBy('tutor_name', 'month_name')
            ->orderBy('tutor_name')
            ->orderBy('month_name')
            ->where('staffs.id', $id)
            ->whereMonth('job_tickets.created_at', '=', now()->subMonth()->month)  // Filtering records for the last month
            ->where('invoices.status', 'paid')  // Ensuring only paid invoices are considered
            ->first();

        if ($monthlyData != null) {
            if ($monthlyData->total_amount >= 11000) {
                $data["commission"] = round($monthlyData->total_amount * 0.02);
            } else {
                $data["commission"] = round($monthlyData->total_amount * 0.01);
            }


            $registeredStudents = DB::table("students")->where("staff_id", $id)->count();
            $registeredParents = DB::table("customers")->where("staff_id", $id)->count();

            $data["invoice_count"] = $registeredStudents + $registeredParents;

            // Initialize bonus to 0 before the loop
            $data["bonus"] = 0;

            $studentPICBonuses = DB::table("studentbonuses")->get();


            foreach ($studentPICBonuses as $studentPICBonus) {
                if ($data["invoice_count"] > $studentPICBonus->rangeFrom && $data["invoice_count"] < $studentPICBonus->rangeTo) {
                    $data["bonus"] = $studentPICBonus->bonusAmount;
                    break;
                }

            }
        } else {
            $data["bonus"] = 0;
            $data["commission"] = 0;
            $data["salary"] = $staff->basic_salary;
        }


        return response()->json(['message' => $data]);

    }

    public function staffPaymentsViewCommissions()
    {

        $monthlyData = DB::table('job_tickets')
        ->leftJoin('staffs', 'job_tickets.admin_charge', '=', 'staffs.id')
        ->join('invoices', 'invoices.id', '=', 'job_tickets.ticket_id')
        ->select(
            'staffs.full_name as staff_name',
            'staffs.id as staff_id',
            DB::raw('MONTHNAME(job_tickets.created_at) as month_name'),
            DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
            DB::raw('SUM(invoices.invoiceTotal) as total_amount')
        )
        ->groupBy('staff_name', 'month_name', 'staff_id')
        ->orderBy('month_name')
        ->where('job_tickets.admin_charge', '!=', NULL)
        ->get();

    foreach ($monthlyData as $data) {
        if ($data->total_amount >= 11000) {
            $data->bonus = round($data->total_amount * 0.02);
        } else {
            $data->bonus = round($data->total_amount * 0.01);
        }
    }

        // dd($monthlyData);

        return view("staff.staffCommission", ["data" => $monthlyData]);

    }

    public function staffPaymentsViewCommissionsByMonth(Request $request)
    {
        $selectedMonth = $request->month;
//        dD($selectedMonth);
//        $selectedMonth = 7;

//        dd($selectedMonth);

        if ($selectedMonth != null) {
            $monthlyData = DB::table('job_tickets')
                ->leftJoin('staffs', 'job_tickets.admin_charge', '=', 'staffs.id')
                ->select(
                    'staffs.full_name as staff_name',
                    DB::raw('MONTHNAME(job_tickets.created_at) as month_name'),
                    DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                    DB::raw('SUM(job_tickets.totalPrice) as total_amount')
                )
                ->whereRaw("MONTH(job_tickets.created_at) = ?", [$selectedMonth])
                ->groupBy('staff_name', 'month_name')
                ->orderBy('month_name')
                ->get();

//            dd($monthlyData);


            foreach ($monthlyData as $data) {
                if ($data->total_amount >= 11000) {
                    $data->bonus = round($data->total_amount * 0.02);
                } else {
                    $data->bonus = round($data->total_amount * 0.01);
                }
            }
        } else {
            $monthlyData = DB::table('job_tickets')
                ->leftJoin('staffs', 'job_tickets.admin_charge', '=', 'staffs.id')
                ->select(
                    'staffs.full_name as staff_name',
                    DB::raw('MONTHNAME(job_tickets.created_at) as month_name'),
                    DB::raw('COUNT(job_tickets.id) as total_job_tickets'),
                    DB::raw('SUM(job_tickets.totalPrice) as total_amount')
                )
                ->groupBy('staff_name', 'month_name')
                ->orderBy('month_name')
                ->get();

            foreach ($monthlyData as $data) {
                if ($data->total_amount >= 11000) {
                    $data->bonus = round($data->total_amount * 0.02);
                } else {
                    $data->bonus = round($data->total_amount * 0.01);
                }
            }
        }

//        dd($monthlyData);

        return view("staff.staffCommission", ["data" => $monthlyData, 'selectedMonth' => $selectedMonth]);

    }

    public function ViewCommissionsBreakDown($staff_id)
    {
        $data = DB::table("job_tickets")->
        join("invoices", "invoices.id", "=", "job_tickets.ticket_id")->
        join("students", "students.id", "=", "job_tickets.student_id")->
        join("customers", "customers.id", "=", "students.customer_id")->

        join("products", "job_tickets.subjects", "=", "products.id")->
        join("categories", "products.category", "=", "categories.id")->
        leftjoin("invoicePayments", "invoicePayments.invoiceID", "=", "invoices.id")->

        select("job_tickets.*", "customers.full_name as customer_name", "customers.full_name as customer_name",
            "invoices.id as invoice_id", "categories.mode as mode", "invoices.invoiceTotal as invoiceTotal", "invoicePayments.paymentDate as paymentDate")->
        where("job_tickets.admin_charge", $staff_id)->get();
        $staff = DB::table("staffs")->where("id", $staff_id)->first();

        return view("staff.staffCommissionBreakDown", ["data" => $data, 'staff' => $staff]);
    }


    public function StaffList(Request $request)
    {
        $staffs = DB::table('staffs')
            ->join("users", "staffs.user_id", "users.id")
            ->join("roles", "users.role", "roles.id")
            ->select("staffs.*", "roles.name as role");

        if ($request->filled('searchQuery')) {
            $searchQuery = $request->searchQuery;
            $staffs->where(function ($query) use ($searchQuery) {
                $query->where('staffs.uid', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('staffs.full_name', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('staffs.phone', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('staffs.email', 'LIKE', "%{$searchQuery}%");
            });
        }

        $staffs = $staffs->orderBy("staffs.id", "desc")->get();
    // dd("Asd");
        return view('staff.staffList', compact('staffs'));
    }


    public function addStaff()
    {
        $roles = DB::table('roles')->get();
        return view('staff/addStaff', Compact('roles'));
    }

    public function viewStaff($id)
    {
        $staff = DB::table('staffs')->where('id', '=', $id)->first();
        if ($staff->city != null) {
            $city = DB::table("cities")->where("id", $staff->city)->pluck("name")->first();
        } else {
            $city = "";
        }


        $user_id = DB::table("staffs")->where("id", $id)->first();
        $staff_role = DB::table("users")->where("id", $user_id->user_id)->first();

        $role_name = DB::table("roles")->where("id", $staff_role->role)->first();

        return view('staff/viewStaff', Compact('staff', 'city', 'role_name'));
    }

    public function editStaff($id)
    {
        $staff = DB::table('staffs')->where('id', '=', $id)->first();
        $states = DB::table('states')->get();

        $roles = DB::table('roles')->get();
        $cities = DB::table("cities")->get();

        return view('staff/editStaff', Compact('staff', 'states', 'roles', 'cities'));
    }

    public function staffPayment($id)
    {
        $staff = DB::table('staffs')->where('id', '=', $id)->first();
        $staffPayments = DB::table('staff_payments')->where('staff_id', '=', $id)->get();
        return view('staff/staffPayment', Compact('staff', 'staffPayments'));
    }


    public function ViewPaymentSlip($id)
    {

        // dd($id);
        $payment_slip = DB::table('staff_payments')->join("staffs", "staff_payments.staff_id", "=", "staffs.id")
            ->select("staff_payments.*", "staffs.full_name as name")
            ->where('staff_payments.id', '=', $id)->orderBy('id', 'desc')->first();

        return view('staff.viewPayment', Compact('payment_slip'));
    }


    public function staffPayments()
    {

        $staffPayments = DB::table('staff_payments')->join("staffs", "staff_payments.staff_id", "=", "staffs.id")
            ->select("staff_payments.*", "staffs.full_name as name")
            ->get();


        // dd($staffPayments);
        return view('staff/staffPayment', Compact('staffPayments'));

    }


    public function StaffMakePayment()
    {
        $staffs = DB::table('staffs')->get();
        return view("staff.submitStaffPayment", ["staffs" => $staffs]);
    }

    public function submitPaymentStaff(Request $request)
    {


        //dd($request->all());

        $basicSalary = $request->basicSalry;
        $overTime = $request->overTimeAmount * $request->overTimeHour;
        $deduction = $request->deduction;
        $bonusAmount = $request->bonusAmount;
        $claim = $request->claim;
        $food = $request->food;


        $calc = $basicSalary + $bonusAmount + $overTime;
        $afterDeductionGovtFund = $calc - ($calc * 0.11);
        $afterDeductionGovtFund = $afterDeductionGovtFund + $claim + $food - $deduction;

        $nett_total = $afterDeductionGovtFund;
        $total = $basicSalary + $bonusAmount + $overTime + $claim + $food - $deduction;


        // dd($request->all());
        $values = array(

            'staff_id' => $request->staffID,
            'payment_date' => $request->paymentDate,
            'salary_month' => $request->salaryMonth,
            'salary_year' => $request->salaryYear,
            'basic_salary_description' => $request->basicSalaryDescription,
            'basic_salary' => $request->basicSalry,
            'overtime_amount_perhour' => $request->overTimeAmount,
            'overtime_hour' => $request->overTimeHour,
            'claim' => $request->claim,
            'food' => $request->food,
            'no_unpaid_leave' => $request->numberOfUnpaidLeave,
            'deduction' => $request->deduction,
            'has_services' => $request->has_services,
            'paying_account' => $request->PayingAccount,
            'comission' => $request->comission,

            'remark' => $request->remarks,
            'bonus_amount' => $request->bonusAmount,
            'total' => $total,
            'nett_amount' => $afterDeductionGovtFund


        );
        $tutorLastID = DB::table('staff_payments')->insertGetId($values);

        return redirect("/StaffPayments");

    }

    public function submitStaff(Request $request)
    {
        //
        //echo date('siHdm');
        //dd($request);
        //die();


        $values = array(
            'name' => $request->full_name,
            'phone' => $request->phoneNumber,
            'email' => $request->email,
            'remarks' => $request->remarks,
            'status' => $request->status,
            'role' => $request->role,
            'password' => bcrypt($request->password),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'));

        $user = User::create($values);

        $values = array(
            'uid' => $request->staffID,
            'full_name' => $request->full_name,
            'user_id' => $user->id,
            'gender' => $request->gender,
            'designation' => $request->desgnation,
            'start_date' => $request->registration_date,
            'age' => $request->age,
            'basic_salary' => $request->basic_salary,
            'email' => $request->email,
            'phone' => $request->phoneNumber,
            'attended_training_date' => $request->attended_training_date,
            'dob' => $request->dob,
            'nric' => $request->nric,
            'address' => $request->street_address1,
            'street_address2' => $request->street_address2,
            'city' => $request->city,
            'state' => $request->state,
            'bank_name' => $request->bankName,
            'bank_account_number' => $request->bankAccountNumber,
            'marital_status' => $request->maritalStatus,
            'no_of_children' => $request->number_of_children,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'remark' => $request->remarks,
            'epf_no' => $request->epf_no,
             'shirt_size' => $request->shirt_size,
            'tax_no' => $request->tax_no,
            'type' => $request->type,
            'status' => $request->status,
             'attended_training_date' => $request->attended_training_date
        );
        $tutorLastID = DB::table('staffs')->insertGetId($values);


        $role = Role::find($request->role);
        $user->assignRole($role);


        if ($request->email != null) {
            $to = $request->email;
            $message = "Welcome to the team! Your registration was successful.";
            
            try {
                // Attempt to send the email
                Mail::to($to)->send(new NewStaffRegistration($message));
            } catch (Swift_TransportException $e) {
                // Log the error for debugging purposes
                Log::error("Email sending failed: " . $e->getMessage());
        
                // Optionally, you could inform the user in a friendly way
                return redirect("/StaffList")->with(['error' => 'Staff has been added successfully but Unable to send email. Please verify the recipient\'s email address and try again.']);
            } catch (\Exception $e) {
                // Catch any other general exceptions that might occur
                Log::error("An unexpected error occurred: " . $e->getMessage());
        
                return redirect("/StaffList")->with(['error' => 'An unexpected error occurred while sending the email.']);
            }

            // Send the email
            // Mail::to($to)->send(new NewStaffRegistration($message));
        }

        return redirect("/StaffList")->with('success', 'Staff has been added successfully!');

        return redirect()->back()->with('success', 'Staff has been added successfully!');

        return view('staff/addStaff');
    }

    public function submitEditStaff(Request $request)
    {
        //
        //echo date('siHdm');
        //die();
        $staffValues = array(
            'uid' => $request->staffID,
            'full_name' => $request->full_name,
            //'fee_payment_date' => $request->FeePaymentDate,
            'gender' => $request->gender,
            'designation' => $request->desgnation,
            'start_date' => $request->registration_date,
            'age' => $request->age,
            'basic_salary' => $request->basic_salary,
            'email' => $request->email,
            'type' => $request->type,
            'phone' => $request->phoneNumber,
            'attended_training_date' => $request->attended_training_date,
            'dob' => $request->dob,
            'nric' => $request->nric,
            'address' => $request->street_address1,
            'street_address2' => $request->street_address2,
            'city' => $request->city,
            'state' => $request->state,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'marital_status' => $request->maritalStatus,
            'no_of_children' => $request->number_of_children,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'remark' => $request->remark,
            'shirt_size' => $request->shirt_size,
            'epf_no' => $request->epf_no,
            'tax_no' => $request->tax_no,
            'status' => $request->status,
            'attended_training_date' => $request->attended_training_date
        );
        $var1 = DB::table('staffs')->where('id', $request->id)->update($staffValues);
        // dd($request->password);
        if ($request->password == NULL) {
            // dd("1");
            $var2 = DB::table('users')->where('id', $request->user_id)->update(["role" => $request->role]);


        } else {
            // dd("2");
            $var2 = DB::table('users')->where('id', $request->user_id)->update(["role" => $request->role, "password" => bcrypt($request->password)]);

        }


        return redirect()->back()->with('success', 'Staff has been edited successfully!');

        return view('staff/addStaff');
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
