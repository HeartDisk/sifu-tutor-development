@extends('layouts.main')

@section('content')

<div class="nk-content">
                  <div class="fluid-container">
                     <div class="nk-content-inner">
                        <div class="nk-content-body">
                           <div class="nk-block-head nk-page-head">
                              <div class="nk-block-head-content">
                                 <h1 class="nk-block-title">Class Schedules</h1>
                              </div>
                           </div>
                           <div class="nk-block">
                              <div class="card">
                                 <div class="card-body">
                                    <table class="datatable-init table" data-nk-container="table-responsive table-border">
                                       <thead class="table-dark">
                                          <tr>
                                             <th><span class="overline-title">#</span></th>
                                             <th><span class="overline-title">Student ID</span></th>
                                             <th><span class="overline-title">Full Name</span></th>
                                             <th><span class="overline-title">Subject</span></th>
                                             <th><span class="overline-title">Subject</span></th>
                                             <th><span class="overline-title">Subscribed Duration hr(s)</span></th>
                                             <th><span class="overline-title">Assigned Duration Hr(s)</span></th>
                                             <th><span class="overline-title">Status</span></th>
                                             <th><span class="overline-title">Action</span></th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <tr>
                                             <td>1</td>
                                             <td><i class="fa fa-user"></i> S180771</td>
                                             <td>Nur Alya Syafiqah Binti Roslin</td>
                                             <td>Mathematics (PT3) - PHYSICAL</td>
                                             <td>0</td>
                                             <td>0</td>
                                             <td>Pending</td>
                                             <td><i class="fa fa-eye"></i></td>
                                          </tr>
                                          
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
