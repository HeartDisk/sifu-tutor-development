<?php

namespace App\Http\Controllers;

use App\Models\SelfPushNotification;
use Illuminate\Http\Request;

class SelfPushNotificationController extends Controller
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
        $notifications = SelfPushNotification::all();
        return view('push_notification.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('push_notification.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        SelfPushNotification::create($data);
       return redirect()->to('/selfpushnotification');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = SelfPushNotification::find($id);
        return view('push_notification.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notification = SelfPushNotification::find($id);
        return view('push_notification.edit', compact('notification'));
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
        $notification = SelfPushNotification::find($id);
        $data = $request->all();
        $notification->update($data);
      return redirect()->to('/selfpushnotification');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SelfPushNotification::find($id)->delete();
     return redirect()->to('/selfpushnotification');

    }
}
