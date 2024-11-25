<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Event;
use DB;
class FullCalenderController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = DB::table('job_tickets')->select(['id', 'uid', 'register_date', 'student_id','tutor_id']);
                        //  $data = Event::whereDate('start', '>=', $request->created_at)
                        // ->get(['id', 'title', 'start', 'end']);
             return response()->json($data);
        }
        return view('fullcalender');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function ajax(Request $request)
    {
        switch ($request->type) {
           case 'add':
              $event = Event::create([
                  'title' => $request->title,
                  'start' => $request->start,
                  'end' => $request->end,
              ]);
              return response()->json($event);
             break;
           case 'update':
              $event = Event::find($request->id)->update([
                  'title' => $request->title,
                  'start' => $request->start,
                  'end' => $request->end,
              ]);
              return response()->json($event);
             break;
           case 'delete':
              $event = Event::find($request->id)->delete();
              return response()->json($event);
             break;
           default:
             # code...
             break;
        }
    }
}