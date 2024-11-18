<?php

namespace App\Http\Controllers;

use App\Events\Parent\ParentDashbaord;
use Illuminate\Http\Request;
use DB;
use Lcobucci\JWT\Exception;
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

    public function selectCategoryPriceByAjax($id)
    {

        $categoryDetail = DB::table('categories')->where('id', '=', $id)->first();

        // $options = [];

        // foreach($cities as $rowsCity){
        //     $options[] .= "<option value='$rowsCity->id'>".$rowsCity->name."</option>";
        // }

        return Response::json(['categoryPrice' => $categoryDetail->price]);

    }

    public function deleteProduct($id)
    {

        DB::table('products')->where('id', $id)->delete();

        $staff_values = array('is_deleted' => 1);

        $var1 = DB::table('products')->where('id', $id)->update($staff_values);

        return redirect()->back();

    }

    public function deleteCategory($id)
    {


        DB::table('categories')->where('id', $id)->delete();

        $category_values = array('is_deleted' => 1);

        $var1 = DB::table('categories')->where('id', $id)->update($category_values);

        return redirect()->back();

    }


    public function addStudentGetAjaxCall($id)
    {

        $student = DB::table('students')->where('id', '=', $id)->first();
        $customer = DB::table('customers')->where('id', '=', $student->customer_id)->first();
        //$subjects = DB::table('student_subjects')->where('student_id','=',$student->id)->get();
        $subjects = DB::table('student_subjects')
            ->where('student_subjects.student_id', '=', $student->id)
            ->join('products', 'student_subjects.subject', '=', 'products.id')
            ->join('students', 'student_subjects.student_id', '=', 'students.id')
            ->select('student_subjects.*', 'products.name as subject', 'students.full_name as studentName', 'student_subjects.status as newstatus')->get();

        return Response::json(['student' => $student, 'customer' => $customer, 'subjects' => $subjects]);

    }


    public function ProductList(Request $request)
    {
        $query = DB::table('products')
            ->join('categories', 'products.category', '=', 'categories.id')
            ->select('products.*', 'categories.price as category_price', 'categories.category_name as category_name', 'categories.mode as mode')
            ->where("products.is_deleted", 0);

        // Apply filters based on user input
        if ($request->filled('searchQuery')) {
            $searchQuery = $request->input('searchQuery');
            $query->where(function ($query) use ($searchQuery) {
                $query->where('products.name', 'like', "%$searchQuery%")
                    ->orWhere('categories.category_name', 'like', "%$searchQuery%")
                    ->orWhere('categories.mode', 'like', "%$searchQuery%");
            });
        }

        $products = $query->get();

        return view('product.productList', compact('products'));
    }

    public function getProductsByAjax(Request $request)
    {
        return 'getProductByAjaxCall';
    }

    public function addProduct()
    {
        $categories = DB::table('categories')->where("is_deleted", 0)->get();
        return view('product/addProduct', Compact('categories'));
    }


    // public function submitProduct(Request $request)
    // {

    //     $values = array(
    //         'uid' => $request->productID,
    //         'name' => $request->product_name,
    //         'code' => $request->code,
    //         'brand' => $request->brand,
    //         'category' => $request->category,
    //         'cost' => 0,
    //         'price' => $request->price,
    //         'description' => $request->description,
    //         'remarks' => $request->remark,
    //         'tuition_service' => 0
    //     );

    //     $product = DB::table('products')->insertGetId($values);

    //     try {

    //         $data = [
    //             "ResponseCode" => "100",
    //             "message" => "Subject Created Successfully"
    //         ];
    //         event(new ParentDashbaord($data));
    //     } catch (Exception $e) {
    //         return response()->json($e->getMessage());
    //     }

    //     return redirect('subjectList')->with('success', 'Product has been Added successfully!');

    // }
    
    public function submitProduct(Request $request)
    {
        // Validate the image file
        $request->validate([
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // Handle image upload
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products'), $imageName); // Save the image in 'public/images/products' directory
        } else {
            $imageName = null; // Handle case if no image is uploaded (optional)
        }
        
        // Prepare data to insert into the database
        $values = array(
            'uid' => $request->productID,
            'name' => $request->product_name,
            'code' => $request->code,
            'brand' => $request->brand,
            'category' => $request->category,
            'cost' => 0,
            'price' => $request->price,
            'description' => $request->description,
            'remarks' => $request->remark,
            'tuition_service' => 0,
            'image' => $imageName // Save the image file name in the database
        );
    
        // Insert the product into the database
        $product = DB::table('products')->insertGetId($values);
    
        try {
            $data = [
                "ResponseCode" => "100",
                "message" => "Subject Created Successfully"
            ];
            event(new ParentDashbaord($data));
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    
        return redirect('subjectList')->with('success', 'Product has been added successfully!');
    }



    public function editProduct($id)
    {
        $product = DB::table('products')->where('id', '=', $id)->first();
        $categories = DB::table('categories')->get();
        return view('product/editProduct', Compact('categories', 'product'));
    }

    public function viewProduct($id)
    {
        $product = DB::table('products')->where('id', '=', $id)->first();
        $categories = DB::table('categories')->get();
        return view('product/viewProduct', Compact('categories', 'product'));
    }

    // public function submitEditProduct(Request $request)
    // {

    //     if ($request->is_tution_service == 'on') {
    //         $values = array(
    //             'uid' => $request->productID,
    //             'name' => $request->product_name,
    //             //'fee_payment_date' => $request->FeePaymentDate,
    //             'code' => $request->code,
    //             'brand' => $request->brand,
    //             'category' => $request->category,
    //             'cost' => 0,
    //             'price' => $request->price,
    //             'description' => $request->description,
    //             'remarks' => $request->remark,
    //             'tuition_service' => 1,
    //             'CommissionRateBeforeTraining' => $request->CommissionRateBeforeTraining,
    //             'IncentiveRateBeforeTraining' => $request->IncentiveRateBeforeTraining,
    //             'CommissionRateAfterTraining' => $request->CommissionRateAfterTraining,
    //             'IncentiveRateAfterTraining' => $request->IncentiveRateAfterTraining
    //         );
    //     } else {
    //         $values = array(
    //             'uid' => $request->productID,
    //             'name' => $request->product_name,
    //             //'fee_payment_date' => $request->FeePaymentDate,
    //             'code' => $request->code,
    //             'brand' => $request->brand,
    //             'category' => $request->category,
    //             'cost' => 0,
    //             'price' => $request->price,
    //             'description' => $request->description,
    //             'remarks' => $request->remark,
    //             'tuition_service' => 0
    //         );
    //     }


    //     $productUpdated = DB::table('products')
    //         ->where('id', $request->id)
    //         ->update($values);


    //     return redirect('subjectList')->with('success', 'Product has been Editted successfully!');


    //     //return view('staff/addStaff');
    // }
    
    public function submitEditProduct(Request $request)
    {
        // Handle image upload
        if ($request->hasFile('product_image')) {
            $request->validate([
                'product_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            // Delete old image if exists
            $oldImage = DB::table('products')->where('id', $request->id)->value('image');
            if ($oldImage) {
                $oldImagePath = public_path('images/products/' . $oldImage);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
    
            // Upload new image
            $image = $request->file('product_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products'), $imageName);
        } else {
            $imageName = DB::table('products')->where('id', $request->id)->value('image'); // Keep old image
        }
    
        // Prepare data for updating
        $values = array(
            'uid' => $request->productID,
            'name' => $request->product_name,
            'code' => $request->code,
            'brand' => $request->brand,
            'category' => $request->category,
            'cost' => 0,
            'price' => $request->price,
            'description' => $request->description,
            'remarks' => $request->remark,
            'tuition_service' => $request->is_tution_service == 'on' ? 1 : 0,
            'image' => $imageName // Save image name
        );
    
        // Update the product in the database
        $productUpdated = DB::table('products')
            ->where('id', $request->id)
            ->update($values);
    
        return redirect('subjectList')->with('success', 'Product has been edited successfully!');
    }


    public function CategoryList(Request $request)
    {
        $categories = DB::table('categories')
            ->join("services", "services.id", "=", "categories.service_id")
            ->select("categories.*", "services.service as service");

        // Apply filters
        if ($request->filled('mode')) {
            $categories->where('categories.mode', 'like', '%' . $request->input('mode') . '%');
        }

        if ($request->filled('level_name')) {
            $categories->where('categories.category_name', 'like', '%' . $request->input('level_name') . '%');
        }

        $categories = $categories->get();

        $services = DB::table('services')->get();

        return view('category.categoryList', compact('categories', 'services'));
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

    public function services()
    {
        $services = DB::table('services')->get();
        return view('category/services', Compact('services'));
    }


    public function submitService(Request $request)
    {

        $values = array('service' => $request->service);
        $tutorLastID = DB::table('services')->insertGetId($values);

        return redirect()->back()->with('success', 'Service has been Added successfully!');
    }

    public function deleteService($id)
    {

        DB::table('services')->where('id', $id)->delete();
        return redirect()->back()->with('danger', 'Service has been Deleted successfully!');

    }


    public function addCategory()
    {
        $services = DB::table('services')->get();
        return view('category/addCategory', compact('services'));
    }


    public function submitCategory(Request $request)
    {

        $values = array('category_name' => $request->category_name, 'service_id' => $request->service_id, 'mode' => $request->mode, 'type' => $request->type, 'price' => $request->price,
        'tutor_longterm_commission_before_eight_hours' => $request->tutor_longterm_commission_before_eight_hours,
        'tutor_longterm_commission_after_eight_hours' => $request->tutor_longterm_commission_after_eight_hours,
         'tutor_shorterm_commission' => $request->tutor_shorterm_commission,
            'additionalRM' => $request->additionalRM);
        $tutorLastID = DB::table('categories')->insertGetId($values);

        try {
            $data = [
                "ResponseCode" => "100",
                "message" => "Subject Created Successfully"
            ];
            event(new ParentDashbaord($data));
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

        return redirect('/CategoryList')->with('success', 'Level has been added successfully!');
    }

    public function editCategory($id)
    {
        $services = DB::table('services')->get();
        $category = DB::table('categories')->where('id', '=', $id)->first();
        return view('category/editCategory', Compact('category', 'services'));
    }

    public function submitEditCategory(Request $request)
    {

        $values = array('category_name' => $request->category_name);

        $affected = DB::table('categories')
            ->where('id', $request->category_id)
            ->update(['category_name' => $request->category_name, 'service_id' => $request->service_id, 'mode' => $request->mode, 'price' => $request->price, 
             'tutor_longterm_commission_before_eight_hours' => $request->tutor_longterm_commission_before_eight_hours,
             'tutor_longterm_commission_after_eight_hours' => $request->tutor_longterm_commission_after_eight_hours,
             'tutor_shorterm_commission' => $request->tutor_shorterm_commission,
            'additionalRM' => $request->additionalRM, 'is_deleted' => $request->status]);

        return redirect()->back()->with('success', 'Category has been Edited successfully!');
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
