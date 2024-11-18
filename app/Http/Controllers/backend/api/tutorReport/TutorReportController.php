<?php

namespace App\Http\Controllers\backend\api\tutorReport;

use App\Models\TutorReport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TutorReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = !empty(request('query')) ? request('query') : '';
        $tutor_reports = TutorReport::latest()->with('tutor','student')
            // ->where('description', 'like', '%'.$q . '%')
            ->paginate(env('PAR_PAGE'));

        return response()->json(['tutor_reports' => $tutor_reports]);
    }
    public function getTutorReportV2()
    {
        $q = !empty(request('query')) ? request('query') : '';
        $tutor_reports = TutorReport::latest()->with('tutor','student')
            // ->where('description', 'like', '%'.$q . '%')
            ->paginate(env('PAR_PAGE'));

        return response()->json(['tutor_reports' => $tutor_reports]);
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
            'tutor_id'=>'required',
            'report_type'=>'required',
            'student_id'=>'required',
            'chart_of_account'=>'required',
            'first_class_date'=>'required',
            'remarks'=>'required',
            ]);

        $attachment = null;
        if($request->hasFile('attachment')){
            $name = singleImgUpload($request,"img/tutor_report");
            $attachment = $name;
        }
        
        $tutor_reports = TutorReport::create([
            'uid'=>Str::uuid(),
            'slug'=>Str::slug(Str::random(),'-'),
            'tutor_id'=>$request->tutor_id,
            'report_type'=>$request->report_type,
            'student_id'=>$request->student_id,
            'first_class_date'=>$request->first_class_date,
            'attachment'=>$attachment,
            'remarks'=>$request->remarks,
        ]);

        return response()->json(['message' => 'Tutor report has been created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tutor_report = TutorReport::where('uid',$id)->with('tutor','student')->first();

        return response()->json(['tutor_report' => $tutor_report]);
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
        $request->validate([
            'tutor_id'=>'required',
            'report_type'=>'required',
            'student_id'=>'required',
            'chart_of_account'=>'required',
            'first_class_date'=>'required',
            'remarks'=>'required',
            ]);
            if($request->hasFile('attachment')){
                $name = singleImgUpload($request,"img/tutor_report");
                TutorReport::where('id',$id)->update([
                    'attachment'=>$name,
                ]);
            }
            
            $tutor_report = TutorReport::where('id',$id)->update([
                'tutor_id'=>$request->tutor_id,
                'report_type'=>$request->report_type,
                'student_id'=>$request->student_id,
                'first_class_date'=>$request->first_class_date,
                'remarks'=>$request->remarks,
            ]);
    
            return response()->json(['message' => 'Tutor Report has been updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tutor_report = TutorReport::where('id',$id)->first();

        if ($tutor_report) {
            $tutor_report->delete();
            return response()->json(['message'=>'Tutor Report has been deleted successfully']);
        }
        return response()->json(['message' => 'Tutor Report has been removed successfully']);
    }
}
