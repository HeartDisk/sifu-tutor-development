@extends('layouts.main')
@section('content')
<div class="nk-content">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2 align-items-center">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">Push Notification Details</h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Mobile App</a></li>
                           <li class="breadcrumb-item"><a href="{{ route('selfpushnotification.index') }}">Push Notification List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Details</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card card-gutter-md">
                  <div class="card-body">
                     <div class="bio-block">
                        <div class="row g-3">
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="page" class="form-label">Page</label>
                                 <div class="form-control-wrap">
                                    {{ $notification->page }}
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="subject" class="form-label">Subject</label>
                                 <div class="form-control-wrap">
                                    {{ $notification->subject }}
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="message" class="form-label">Message</label>
                                 <div class="form-control-wrap">
                                    {{ $notification->message }}
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="time" class="form-label">Push Time</label>
                                 <div class="form-control-wrap">
                                    {{ $notification->time }}
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="type" class="form-label">Push Type</label>
                                 <div class="form-control-wrap">
                                    @if($notification->type === 'one_time')
                                       One Time
                                    @elseif(!empty($notification->days))
                                       Weekly Recurrence
                                    @elseif($notification->monthly_date)
                                       Monthly Recurrence
                                    @else
                                       Daily Recurrence
                                    @endif
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="push_on" class="form-label">Push On</label>
                                 <div class="form-control-wrap">
                                    @if($notification->type === 'one_time')
                                        {{ \Carbon\Carbon::parse($notification->date)->format('d/m/Y') }}
                                    @elseif(!empty($notification->days))
                                        @php
                                            // No need to decode as it is already an array
                                            $dayNames = implode(', ', array_map('ucfirst', $notification->days));
                                        @endphp
                                        {{ $dayNames }}
                                    @elseif($notification->monthly_date)
                                        Every month on {{ $notification->monthly_date }}
                                    @else
                                        Everyday
                                    @endif
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                 <label for="status" class="form-label">Status</label>
                                 <div class="form-control-wrap">
                                    @if($notification->sent == 0)
                                        <span class="badge bg-warning text-dark">Processing</span>
                                    @else
                                        <span class="badge bg-success">Success</span>
                                    @endif
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-8">
                              <div class="form-group">
                                 <label for="remark" class="form-label">Remark</label>
                                 <div class="form-control-wrap">
                                    {{ $notification->remark }}
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="mt-4">
                           <a href="{{ route('selfpushnotification.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
