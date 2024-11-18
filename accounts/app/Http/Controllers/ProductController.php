<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;

class ProductController extends Controller
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
    
    public function selectCategoryPriceByAjax($id){
        
        $categoryDetail = DB::table('categories')->where('id','=',$id)->first();
        
        // $options = [];
        
        // foreach($cities as $rowsCity){
        //     $options[] .= "<option value='$rowsCity->id'>".$rowsCity->name."</option>";    
        // }
        
        return Response::json(['categoryPrice'=>$categoryDetail->price]);
        
    }
    
    public function deleteProduct($id){
        
         $staff_values = array('is_deleted' => 1);
                    
        $var1 = DB::table('products')->where('id', $id)->update($staff_values);
        
        return redirect()->back();
        
    }
    
    public function deleteCategory($id){
        
         $category_values = array('is_deleted' => 1);
                    
        $var1 = DB::table('categories')->where('id', $id)->update($category_values);
        
        return redirect()->back();
        
    }
    
    
     public function addStudentGetAjaxCall($id){
        
        $student = DB::table('students')->where('id','=',$id)->first();
        $customer = DB::table('customers')->where('id','=',$student->customer_id)->first();
        //$subjects = DB::table('student_subjects')->where('student_id','=',$student->id)->get();
        $subjects = DB::table('student_subjects')
                        ->where('student_subjects.student_id','=',$student->id)
                        ->join('products', 'student_subjects.subject', '=', 'products.id')
                        ->join('students', 'student_subjects.student_id', '=', 'students.id')
                        ->select('student_subjects.*', 'products.name as subject', 'students.full_name as studentName','student_subjects.status as newstatus')->get();
                        
        return Response::json(['student'=>$student, 'customer'=>$customer, 'subjects'=>$subjects]);
        
    }
    
    
   public function ProductList(){
        $products = DB::table('products')
                    ->join('categories', 'products.category', '=', 'categories.id')
                    ->select('products.*', 'categories.price as category_price','categories.category_name as category_name','categories.mode as mode')->where("products.is_deleted",0)
                    ->get();
        
       // dd($products);            
        return view('product/productList', Compact('products'));
    }
    
    public function getProductsByAjax(Request $request){
        return 'getProductByAjaxCall';
    }
    
    public function addProduct(){
        $categories = DB::table('categories')->where("is_deleted",0)->get();
        return view('product/addProduct', Compact('categories'));
    }
    
    
     public function submitProduct(Request $request)
    {

        if($request->is_tution_service == 'on'){
        $values = array(
                    'uid' => $request->productID,
                    'name' => $request->product_name,
                    //'fee_payment_date' => $request->FeePaymentDate,
                    'code' => $request->code,
                    'brand' => $request->brand,
                    'category' => $request->category,
                    'cost' => 0,
                    'price' => $request->price,
                    'description' => $request->description,
                    'remarks' => $request->remark,
                    'tuition_service' => 1,
                    'CommissionRateBeforeTraining' => $request->CommissionRateBeforeTraining,
                    'IncentiveRateBeforeTraining' => $request->IncentiveRateBeforeTraining,
                    'CommissionRateAfterTraining' => $request->CommissionRateAfterTraining,
                    'IncentiveRateAfterTraining' => $request->IncentiveRateAfterTraining
                    );    
        }else{
            $values = array(
                    'uid' => $request->productID,
                    'name' => $request->product_name,
                    //'fee_payment_date' => $request->FeePaymentDate,
                    'code' => $request->code,
                    'brand' => $request->brand,
                    'category' => $request->category,
                    'cost' => 0,
                    'price' => $request->price,
                    'description' => $request->description,
                    'remarks' => $request->remark,
                    'tuition_service' => 0
                    );
        }
        
        $tutorLastID = DB::table('products')->insertGetId($values);
        
        
        // $customer_values = array(
        //             'student_id' => $studentLastID,
        //             'uid' => 'CUS-'.date('Hisdm'),
        //             'full_name' => $request->customerFullName,
        //             'gender' => $request->customerGender,
        //             'age' => $request->customerAge,
        //             'email' => $request->customerEmail,
        //             'dob' => $request->customerDateOfBirth,
        //             'nric' => $request->customerCNIC,
        //             'address1' => $request->customerStreetAddress1,
        //             'address2' => $request->customerStreetAddress2,
        //             'city' => $request->cicustomerCityty,
        //             'postal_code' => $request->customerPostalcode,
        //             'latitude' => $request->customerLatitude,
        //             'longitude' => $request->customerLongitude,
        //             'customerable_type' => 0,
        //             'customerable_id' => 0,
        //             'remarks' => $request->remarks
        //             );
        // $studentLastID = DB::table('customers')->insertGetId($customer_values);
        
        return redirect('subjectList')->with('success','Product has been Editted successfully!');
        
        
        return view('staff/addStaff');
    }
    
    
     public function editProduct($id){
        $product = DB::table('products')->where('id','=',$id)->first();
        $categories = DB::table('categories')->get();
        return view('product/editProduct', Compact('categories','product'));
    }
    
    public function viewProduct($id){
        $product = DB::table('products')->where('id','=',$id)->first();
        $categories = DB::table('categories')->get();
        return view('product/viewProduct', Compact('categories','product'));
    }
    
    public function submitEditProduct(Request $request)
        {

        if($request->is_tution_service == 'on'){
        $values = array(
                    'uid' => $request->productID,
                    'name' => $request->product_name,
                    //'fee_payment_date' => $request->FeePaymentDate,
                    'code' => $request->code,
                    'brand' => $request->brand,
                    'category' => $request->category,
                    'cost' => 0,
                    'price' => $request->price,
                    'description' => $request->description,
                    'remarks' => $request->remark,
                    'tuition_service' => 1,
                    'CommissionRateBeforeTraining' => $request->CommissionRateBeforeTraining,
                    'IncentiveRateBeforeTraining' => $request->IncentiveRateBeforeTraining,
                    'CommissionRateAfterTraining' => $request->CommissionRateAfterTraining,
                    'IncentiveRateAfterTraining' => $request->IncentiveRateAfterTraining
                    );    
        }else{
            $values = array(
                    'uid' => $request->productID,
                    'name' => $request->product_name,
                    //'fee_payment_date' => $request->FeePaymentDate,
                    'code' => $request->code,
                    'brand' => $request->brand,
                    'category' => $request->category,
                    'cost' => 0,
                    'price' => $request->price,
                    'description' => $request->description,
                    'remarks' => $request->remark,
                    'tuition_service' => 0
                    );
        }
        
        
        $productUpdated = DB::table('products')
              ->where('id', $request->id)
              ->update($values);
        
        
        return redirect('subjectList')->with('success','Product has been Editted successfully!');
        
        
        //return view('staff/addStaff');
    }
    
    public function CategoryList()
    {
        $categories = DB::table('categories')->join("services","services.id","=","categories.service_id")
        ->select("categories.*","services.service as service")
        // ->where("categories.is_deleted",0)
        ->get();
        $services = DB::table('services')->get();
        
        
        //  dd($services);
        
        return view('category/categoryList', Compact('categories','services'));
    }
    // public function addCategoryList()
    // {
    //     $categories = DB::table('categories')->join("services","services.id","=","categories.service_id")
    //     ->select("categories.*","services.service as service")
    //     ->get();
    //     $services = DB::table('services')->get();
        
        
    //     //  dd($services);
        
    //     return view('category/addcategoryList', Compact('categories','services'));
    // }
    
    public function services(){
        $services = DB::table('services')->get();
        return view('category/services', Compact('services'));
    }
    
    
    public function submitService(Request $request){
       
        $values = array('service' => $request->service);    
        $tutorLastID = DB::table('services')->insertGetId($values);
       
       return redirect()->back()->with('success','Service has been Added successfully!');
    }
    
    public function deleteService($id){
        
        DB::table('services')->where('id', $id)->delete();
        return redirect()->back()->with('danger','Service has been Deleted successfully!');
        
    }
    
    
    
    public function addCategory(){
        $services = DB::table('services')->get();
        return view('category/addCategory', compact('services'));
    }
    
    
    
     public function submitCategory(Request $request)
    {

        $values = array('category_name' => $request->category_name, 'service_id' => $request->service_id, 'mode' => $request->mode, 'type' => $request->type, 'price' => $request->price, 
        'additionalRM' => $request->additionalRM);    
        $tutorLastID = DB::table('categories')->insertGetId($values);
              
        return redirect('/CategoryList')->with('success','Level has been added successfully!');
    }
    
    public function editCategory($id){
        $services = DB::table('services')->get();
        $category = DB::table('categories')->where('id','=',$id)->first();
        return view('category/editCategory', Compact('category','services'));
    }
    
     public function submitEditCategory(Request $request)
    {

        $values = array('category_name' => $request->category_name);    

        $affected = DB::table('categories')
              ->where('id', $request->category_id)
              ->update(['category_name' => $request->category_name, 'service_id' => $request->service_id, 'mode' => $request->mode, 'price' => $request->price,'additionalRM' => $request->additionalRM,'is_deleted' => $request->status]);

        return redirect()->back()->with('success','Category has been Edited successfully!');
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
