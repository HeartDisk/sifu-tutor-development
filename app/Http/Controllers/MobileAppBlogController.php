<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product;
use App\Models\Blog;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\Parent\ParentDashbaord;


class MobileAppBlogController extends Controller
{
    public function mobileAppBlog()
    {
        $news = Blog::orderBy('id', 'desc')->where("status","Published")->get();
        return view('mobileApp.blogs.index', compact('news'));
    }

    public function addBlog()
    {
        $subjects=DB::table("products")->
        leftjoin("categories","products.category","=","categories.id")
            ->select("products.*","categories.category_name as level")->get();

        return view('mobileApp.blogs.create',compact('subjects'));
    }

    public function editBlog($id)
    {
        $singleNews = Blog::findOrFail($id); // Fetch the news using the Blog model
        $subjects=DB::table("products")->
        leftjoin("categories","products.category","=","categories.id")
            ->select("products.*","categories.category_name as level")->get();
        return view('mobileApp.blogs.edit', compact('singleNews','subjects'));
    }

    public function deleteBlog($id)
    {
        $news = Blog::findOrFail($id); // Fetch the news using the Blog model
        $news->is_deleted = 1; // Soft delete the news
        $news->save();

        return redirect()->back();
    }

    public function submitBlog(Request $request)
    {

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('MobileBlogImages'), $imageName);

        $blog = new Blog();
        $blog->subject = $request->subject;
        $blog->headerimage = $imageName;
        $blog->preheader = $request->preheader;
        $blog->content = $request->editor1;
        $blog->status = $request->status;
        $blog->subject = $request->subject;
        $blog->save();
        
        try {
            //parent tokent
            $data = [
                "ResponseCode" => "100",
                "message" => "Blog Data Fetched Successfully"
            ];
            event(new ParentDashbaord($data));

        } catch (Exception $e) {
            return response()->json(["ResponseCode" => "103",
                "error" => "Unable to fetch blog data"]);
        }
        
        return redirect('MobileAppBlog');
    }

    public function submitEditBlog(Request $request)
    {
        $blog = Blog::findOrFail($request->news_id);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('MobileBlogImages'), $imageName);
            $blog->headerimage = $imageName;
        }

        $blog->subject = $request->subject;
        $blog->preheader = $request->preheader;
        $blog->content = $request->editor1;
        $blog->status = $request->status;
        $blog->save();

        return redirect('MobileAppBlog');
    }

    public function singleMobileAppBlog($id)
    {
        $singleNews = Blog::findOrFail($id); // Fetch the news using the Blog model
        return view('mobileApp.blogs.view', compact('singleNews'));
    }

}
