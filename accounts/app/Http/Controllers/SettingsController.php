<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


        public function giveAllPermissionsToUser()
    {
        $userId=1;
        // Retrieve the user
        $user = User::findOrFail($userId);

        // Create a role for the user
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Retrieve all permissions
        $permissions = Permission::all();

        // Assign all permissions to the role
        $role->syncPermissions($permissions);

        // Assign the role to the user
        $user->assignRole($role);

        return "All permissions assigned to the user successfully.";
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('tutor/index');
    }

    public function users()
    {
        //
        $roles = DB::table('roles')->get();
        $users = DB::table('users')->get();
        return view('settings/users', Compact('roles', 'users'));
    }

    public function deleteUser($id)
    {

        $user_values = array('is_deleted' => 1);

        $var1 = DB::table('users')->where('id', $id)->update($user_values);

        return redirect()->back();

    }


    public function submitUser(Request $request)
    {

        
        $values = array(
            'name' => $request->fullName,
            'phone' => $request->phone,
            'email' => $request->email,
            'remarks' => $request->remarks,
            'status' => $request->status,
            'role' => $request->role,
            'password' => bcrypt($request->password),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'));

        $user = User::create($values);

        $role = Role::find($request->role);
        $user->assignRole($role);

        return redirect()->back()->with('success', 'Users has been added successfully!');

    }

    public function editSubmitUser(Request $request)
    {
        $values = array(
            'name' => $request->fullName,
            'phone' => $request->phone,
            'email' => $request->email,
            'remarks' => $request->remarks,
            'status' => $request->status,
            'role' => $request->role,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'));
        DB::table('users')
            ->where('id', $request->userID)  // find your user by their email
            ->limit(1)  // optional - to ensure only one record is updated.
            ->update($userValue);  // update the record in the DB.
    }


    public function userRoles()
    {
        //
        $roles = DB::table('roles')->get();
        return view('settings/userRoles', Compact('roles'));
    }

    public function addUserRole(Request $request)
    {
        //

//        $values = array('name' => $request->roleName,'guard_name' => 'web','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'));
//        DB::table('roles')->insert($values);
//
//        return redirect()->back()->with('success','Role has been added successfully!');

        return view("userRole.create");

    }

    public function addRolesAndPermission(Request $request)
    {

        $input = $request->all();

        $role = Role::create(['name' => $input['adduserrole']]);

        if ($request->has('permissions')) {

            foreach ($input['permissions'] as $key => $permisison) {
                $permission = Permission::firstOrCreate(['name' => $permisison]);

                $role->givePermissionTo($permission);
                $permission->assignRole($role);
            }

        }

        return redirect()->route('userRoles')->with('Role Created Succesfully');

    }

    public function showRole(Request $request , $roleId)
    {

        $role = Role::where('id' , $roleId)->first();

        return view("userRole.edit" , compact('role'));

    }

    public function editRole(Request $request)
    {
        $affected = DB::table('roles')
            ->where('id', $request->rolesID)
            ->update(['name' => $request->roleName]);

        return redirect()->back()->with('update', 'Role has been updated successfully!');
    }

    public function deleteRole($id)
    {

        $deleted = DB::table('roles')->where('id', '=', $id)->delete();
        return redirect()->back()->with('delete', 'Role has been Deleted successfully!');

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

    public function StateCities()
    {

        $states = DB::table('states')->get();
        $cities = DB::table('cities')->get();
        return view('settings.stateCities', Compact('states', 'cities'));
    }

    public function editState($id)
    {

        $states = DB::table('states')->where('id', '=', $id)->first();

        return view('settings.editState', Compact('states'));
    }

    public function editCity($id)
    {

        $city = DB::table('cities')->where('id', '=', $id)->first();
        $getState = DB::table('states')->where('id', '=', $city->state_id)->first();
        $states = DB::table('states')->get();
        return view('settings.editCity', Compact('states', 'city', 'getState'));
    }


    public function submitState(Request $request)
    {
        $state_values = array(
            'name' => $request->state
        );
        DB::table('states')->insertGetId($state_values);
        return redirect('StateCities');
    }

    public function submitEditState(Request $request)
    {
        $state_values = array(
            'name' => $request->state
        );

        DB::table('states')->where('id', $request->state_id)->update($state_values);
        return redirect('StateCities');
    }

    public function submitEditCity(Request $request)
    {
        $city_values = array(
            'state_id' => $request->state_id,
            'name' => $request->city
        );

        DB::table('cities')->where('id', $request->city_id)->update($city_values);
        return redirect('StateCities');
    }


    public function submitCity(Request $request)
    {
        $state_values = array(
            'state_id' => $request->state_id,
            'name' => $request->city
        );
        DB::table('cities')->insertGetId($state_values);
        return redirect('StateCities');
    }


    public function appleRedemptionCode()
    {
        return view('settings.appleRedumptionCode');
    }

    public function system()
    {
        return view('settings.system');
    }

    public function extraStudentCharges()
    {
        $extraStudentCharges = DB::table('extra_student_charges')->get();
        return view('settings.extraStudentCharges', Compact('extraStudentCharges'));
    }

    public function submitExtraStudentCharges(Request $request)
    {
        $state_values = array(
            'online_additional_charges' => $request->online_additional_charges,
            'physical_additional_charges' => $request->physical_additional_charges,
            'tutor_online' => $request->tutor_online,
            'tutor_physical' => $request->tutor_physical
        );


        DB::table('extra_student_charges')->where("id", 1)->update($state_values);

        return redirect()->back()->with('success', 'Student Extra Charges has been added');

    }


    public function accountInformation()
    {


        return view('settings.accountInformation');
    }

    public function addRedemptionCode()
    {


        return view('settings.addRedemptionCode');
    }

    public function MessageTemplates()
    {

        return view('settings.MessageTemplates');
    }

    public function tutorBonus()
    {
        $tutorbonuses = DB::table('tutorbonuses')->orderBy('id', 'desc')->get();
        return view('settings.tutorBonus', Compact('tutorbonuses'));
    }

    public function viewTutorBonuses($id)
    {
        $tutorbonuses = DB::table('tutorbonuses')->where('id', '=', $id)->first();
        return view('settings.viewTutorBonus', Compact('tutorbonuses'));
    }

    public function editTutorBonuses($id)
    {
        $tutorbonuses = DB::table('tutorbonuses')->where('id', '=', $id)->first();
        return view('settings.editTutorBonus', Compact('tutorbonuses'));
    }

    public function addTutorBonus()
    {

        return view('settings.addTutorBonus');
    }

    public function submitTtuorBonus(Request $request)
    {

        $invoice_values = array(
            'fromDate' => $request->fromDate,
            'toDate' => $request->toDate,
            'rangeFrom' => $request->rangeFrom,
            'rangeTo' => $request->rangeTo,
            'bonusAmount' => $request->bonusAmount
        );
        $customerLastID = DB::table('tutorbonuses')->insertGetId($invoice_values);
        return redirect('tutorBonus');
    }


    public function submitEditTutorBonuses(Request $request)
    {

        $invoice_values = array(
            'fromDate' => $request->fromDate,
            'toDate' => $request->toDate,
            'rangeFrom' => $request->rangeFrom,
            'rangeTo' => $request->rangeTo,
            'bonusAmount' => $request->bonusAmount
        );

        DB::table('tutorbonuses')->where('id', $request->id)->update($invoice_values);
        return redirect('tutorBonus');


    }


    public function StudentPicBonuses()
    {
        $studentbonuses = DB::table('studentbonuses')->orderBy('id', 'desc')->get();
        return view('settings.StudentPicBonuses', Compact('studentbonuses'));
    }

    public function addStudentPicBonuses()
    {

        return view('settings.addStudentPicBonuses');
    }

    public function submitAddStudentPicBonuses(Request $request)
    {

        $invoice_values = array(
            'fromDate' => $request->fromDate,
            'toDate' => $request->toDate,
            'rangeFrom' => $request->rangeFrom,
            'rangeTo' => $request->rangeTo,
            'bonusAmount' => $request->bonusAmount
        );
        $customerLastID = DB::table('studentbonuses')->insertGetId($invoice_values);
        return redirect('StudentPicBonuses');

    }


    public function editStudentPicBonuses($id)
    {
        $studentbonuses = DB::table('studentbonuses')->where('id', '=', $id)->first();
        return view('settings.editStudentPicBonuses', Compact('studentbonuses'));
    }

    public function submitEditStudentPicBonuses(Request $request)
    {
        $invoice_values = array(
            'fromDate' => $request->fromDate,
            'toDate' => $request->toDate,
            'rangeFrom' => $request->rangeFrom,
            'rangeTo' => $request->rangeTo,
            'bonusAmount' => $request->bonusAmount
        );

        DB::table('studentbonuses')->where('id', $request->id)->update($invoice_values);
        return redirect('StudentPicBonuses');

    }

    public function viewStudentPicBonuses($id)
    {
        $studentbonuses = DB::table('studentbonuses')->where('id', '=', $id)->first();
        return view('settings.viewStudentPicBonuses', Compact('studentbonuses'));
    }


    public function StudentPicCommissions()
    {
        $studentcomissions = DB::table('studentcomissions')->orderBy('id', 'desc')->get();
        return view('settings.StudentPicCommission', Compact('studentcomissions'));
    }


    public function editComission($id)
    {
        $studentcomissions = DB::table('studentcomissions')->where('id', '=', $id)->first();
        return view('settings.editComission', Compact('studentcomissions'));
    }

    public function viewComission($id)
    {
        $studentcomissions = DB::table('studentcomissions')->where('id', '=', $id)->first();
        return view('settings.viewComission', Compact('studentcomissions'));
    }


    public function addComission()
    {

        return view('settings.addComission');
    }

    public function submitAddComission(Request $request)
    {


        $invoice_values = array(
            'fromDate' => $request->fromDate,
            'ToDate' => $request->toDate,
            'FirstInvoiceAmountPhysicalClass' => $request->FirstInvoiceAmountPhysicalClass,
            'RecurrenceInvoiceAmountPhysicalClass' => $request->RecurrenceInvoiceAmountPhysicalClass,
            'FirstInvoiceAmountOnlineClass' => $request->FirstInvoiceAmountOnlineClass,
            'RecurrenceInvoiceAmountOnlineClass' => $request->RecurrenceInvoiceAmountOnlineClass,
        );
        $customerLastID = DB::table('studentcomissions')->insertGetId($invoice_values);
        return redirect('StudentPicCommissions');
    }

    public function submitEditComission(Request $request)
    {

        $invoice_values = array(
            'fromDate' => $request->fromDate,
            'ToDate' => $request->toDate,
            'FirstInvoiceAmountPhysicalClass' => $request->FirstInvoiceAmountPhysicalClass,
            'RecurrenceInvoiceAmountPhysicalClass' => $request->RecurrenceInvoiceAmountPhysicalClass,
            'FirstInvoiceAmountOnlineClass' => $request->FirstInvoiceAmountOnlineClass,
            'RecurrenceInvoiceAmountOnlineClass' => $request->RecurrenceInvoiceAmountOnlineClass,
        );
        DB::table('studentcomissions')->where('id', $request->id)->update($invoice_values);

        return redirect('StudentPicCommissions');
    }

}
