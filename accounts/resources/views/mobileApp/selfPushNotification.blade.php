@extends('layouts.main')

@section('content')

<div class="nk-content">
                  <div class="fluid-container">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head nk-page-head">
                              <div class="nk-block-head-content">
                                 <table style="width:100%">
                                    <tr>
                                       <td><h1 class="nk-block-title">Add Push Notification List</h1></td>
                                       
                                    </tr>
                                 </table>

                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                    <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                       <thead class="table-dark">
                                          <tr>
                                              
                                             <th><span class="overline-title">#</span></th>
                                             <th><span class="overline-title">Subject</span></th>
                                             <th><span class="overline-title">Push Type</span></th>
                                             <th><span class="overline-title">Push On</span></th>
                                             <th><span class="overline-title">Push Time</span></th>
                                             <th><span class="overline-title">Status</span></th>
                                             <th><span class="overline-title">Action</span></th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                           @php
                                            $numbers = 1;
                                           @endphp
                                           @foreach($selfPushNotifications as $rows)
                                              <tr>
                                                 <td>{{$numbers++}}</td>
                                                 <td>{{$rows->subject}}</td>
                                                 <td>{{$rows->pushType}}</td>
                                                 <td>{{$rows->recurrancePattern}}</td>
                                                 <td>{{$rows->pushTime}}</td>
                                                 <td>{{$rows->status}}</td>
                                                 <td>
                                                    <i title="Edit" style="border:1px solid #000; border-radius:5px; padding:5px;" class="fa fa-edit"></i>
                                                    <i title="Delete" style="border:1px solid #000; border-radius:5px; padding:5px;" class="fa fa-trash"></i>
                                                </td>
                                              </tr> 
                                           @endforeach
                                          
                                       </tbody>
                                    </table>
                                    
                                 </div>
                              </div>
                           </div>
                           
                        </div>
                     </div>
                  </div>
               </div>


@endsection
