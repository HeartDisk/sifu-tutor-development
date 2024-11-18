<?php

namespace App\Http\Controllers\backend\api\customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function index(Request $request)
    {
        $q = !empty(request('query')) ? request('query') : '';
        $customers = Customer::latest()->with('student')->where('full_name', 'LIKE', '%' . $q . '%')
            ->paginate(env('PAR_PAGE'));

        return response()->json(['status' => true, 'customers' => $customers]);
    }
    public function getCustomerList(){
        $customers = Customer::latest()->get();

        return response()->json(['status' => true, 'customers'=>$customers]);
    }
    public function edit($id)
    {
        //
        $customer=Customer::where('uid',$id)->with('student')->first();
        return response()->json(['status' => true,'customer'=>$customer]);
    }
    public function show($id)
    {
        //
        $customer=Customer::where('uid',$id)->with('student')->first();
        return response()->json(['status' => true,'customer'=>$customer]);
    }
    public function destroy($id)
    {
        //
        $customer = Customer::where('id', $id)->delete();
        if($customer){
            return response()->json(['status' => true,'message'=>'customer has been  deleted successfully']);
        }
    }
}


