<?php

namespace App\Http\Controllers\backend\api\subject;

use App\Models\Product;
use App\Models\Subject;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    //
    public function index(Request $request)
    {
        $q=!empty(request('query')) ? request('query') : '';
        $subjects = Subject::latest()->where('name', 'LIKE', '%' . $q . '%')
                               ->paginate(env('PAR_PAGE'));

        return response()->json(['status' => true, 'subjects' => $subjects]);
    }

    public function getSubjectList(Request $request){
        $subjects = Product::latest()->orderBy('category', 'asc')->get();
        $subjects=collect($subjects)->groupBy('category');
        $subjects = collect($subjects)->map(function ($item) {
            return ['category' => $item->first()->category, 'subjects' => $item->all()];
        })->values();
        return response()->json(['status' => true,'subjects' => $subjects]);
    }
    public function store(Request $request){
        $request->validate([
            'name' =>'required|string|min:2|max:255|unique:subjects',
            'category'=>'required|string|min:2|max:255',
            'description' =>'max:255',
        ]);

      $subject=  Subject::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name,'-'),
            'uid'=>Str::uuid(),
            'category' => $request->category,
            'price' =>$request->price,
            'description' => $request->description,
        ]);
        return response()->json(['status' => true,'subject'=>$subject]);
    }

    public function show($id)
    {
        //
        $subject=Subject::where('uid',$id)->first();
        return response()->json(['status' => true,'subject'=>$subject]);
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
        $subject=Subject::where('uid',$id)->first();
        return response()->json(['status' => true,'subject'=>$subject]);
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
            'name' =>'required|string|min:2|max:255|unique:subjects,name,'.$id,
            'category'=>'required|string|min:2|max:255',
            'description' =>'min:10|max:255',
        ]);

      $subject=  Subject::where('id',$id)->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name,'-'),
            'uid'=>Str::uuid(),
            'price' =>$request->price,
            'category' => $request->category,
            'description' => $request->description,
        ]);
        return response()->json(['status' => true,'subject'=>$subject]);
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
        $subject = Subject::where('id', $id)->delete();
        if($subject){
            return response()->json(['status' => true,'message']);
        }
    }


}
