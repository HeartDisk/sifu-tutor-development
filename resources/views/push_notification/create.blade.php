@extends('layouts.main')
@section('content')
<div class="nk-content">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">
                        Add Push Notification
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Push Notification List</a></li>
                           <li class="breadcrumb-item active" aria-current="page">Add Push Notification</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card card-gutter-md">
                  <div class="card-body">
                     @if (\Session::has('success'))
                     <div class="alert alert-success">
                        <ul>
                           <li>{!! \Session::get('success') !!}</li>
                        </ul>
                     </div>
                     @endif
                     @if (\Session::has('update'))
                     <div class="alert alert-primary">
                        <ul>
                           <li>{!! \Session::get('update') !!}</li>
                        </ul>
                     </div>
                     @endif
                     <div class="bio-block">
                        <form method="POST" action="{{url('selfpushnotification')}}" id="notifyForm">
                           @csrf
                           <div class="row g-3">
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="page" class="form-label">Page To Open</label>
                                    <div class="form-control-wrap">
                                       <select required class="form-control" name="page">
                                           <option value="">--- Select ---</option>
                                           <option value="Home">Dashboard</option>
                                           <option value="Profile">Profile</option>
                                           <option value="Home">Cumulative Commission</option>
                                           <option value="PaymentHistory">Payment History</option>
                                           <option value="Inbox">Inbox</option>
                                           <option value="JobTicket">Job Ticket List</option>
                                           <option value="Schedule">Class Schedule List</option>
                                           <option value="ReportSubmissionHistory">Submission History</option>
                                           <option value="Students">Student List</option>
                                           <option value="Schedule">Pending Actions</option>
                                           <option value="FAQs">Faq</option>
                                        </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="subject" class="form-label">Subject</label>
                                    <div class="form-control-wrap">
                                       <input type="text" required class="form-control" name="subject" value="{{$notification->subject ?? ''}}">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="message" class="form-label">Message</label>
                                    <div class="form-control-wrap">
                                       <input type="text" required class="form-control" name="message" value="{{$notification->message ?? ''}}">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="time" class="form-label">Push Time <span>(24hrs format)</span></label>
                                    <div class="form-control-wrap">
                                       <input type="time" required class="form-control" name="time" value="{{$notification->time ?? ''}}">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4">
                                 <div class="form-group">
                                    <label for="type" class="form-label">Push Type</label>
                                    <div class="form-control-wrap">
                                       <select required class="form-control" id="pushType" name="type">
                                          <option value="">--- Select ---</option>
                                          <option value="one_time">One Time</option>
                                          <option value="recurring">Recurring</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4 push-date">
                                 <div class="form-group">
                                    <label for="date" class="form-label">Push Date</label>
                                    <div class="form-control-wrap">
                                       <input type="date" class="form-control" name="date" value="{{$notification->date ?? ''}}">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4 recurrence-pattern d-none">
                                 <div class="form-group">
                                    <label for="recurrence_pattern" class="form-label">Recurrence Pattern</label>
                                    <div class="form-control-wrap">
                                       <select class="form-control" id="recurrencePattern" name="recurrence_pattern">
                                          <option value="">--- Select ---</option>
                                          <option value="daily">Daily</option>
                                          <option value="weekly">Weekly</option>
                                          <option value="monthly">Monthly</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12 weekly-days d-none">
                                 <div class="form-group">
                                    <label for="days" class="form-label">Days of the Week</label>
                                    <div class="form-control-wrap">
                                       <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" name="days[]" value="monday" id="monday">
                                          <label class="form-check-label" for="monday">Monday</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" name="days[]" value="tuesday" id="tuesday">
                                          <label class="form-check-label" for="tuesday">Tuesday</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" name="days[]" value="wednesday" id="wednesday">
                                          <label class="form-check-label" for="wednesday">Wednesday</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" name="days[]" value="thursday" id="thursday">
                                          <label class="form-check-label" for="thursday">Thursday</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" name="days[]" value="friday" id="friday">
                                          <label class="form-check-label" for="friday">Friday</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" name="days[]" value="saturday" id="saturday">
                                          <label class="form-check-label" for="saturday">Saturday</label>
                                       </div>
                                       <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="checkbox" name="days[]" value="sunday" id="sunday">
                                          <label class="form-check-label" for="sunday">Sunday</label>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-4 monthly-date d-none">
                                 <div class="form-group">
                                    <label for="monthly_date" class="form-label">Date of the Month</label>
                                    <div class="form-control-wrap">
                                       <input type="number" class="form-control" name="monthly_date" min="1" max="31" placeholder="Enter date (1-31)">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <label for="remark" class="form-label">Remark</label>
                                    <div class="form-control-wrap">
                                       <textarea required class="form-control" cols="90" rows="10" name="remark">{{$notification->remark ?? ''}}</textarea>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <button class="btn btn-primary" type="submit">Submit</button>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function () {
      const pushTypeSelect = document.getElementById('pushType');
      const recurrencePatternSelect = document.getElementById('recurrencePattern');
      const pushDateField = document.querySelector('.push-date');
      const recurrencePatternField = document.querySelector('.recurrence-pattern');
      const weeklyDaysField = document.querySelector('.weekly-days');
      const monthlyDateField = document.querySelector('.monthly-date');

      function toggleFields() {
         const pushType = pushTypeSelect.value;
         const recurrencePattern = recurrencePatternSelect.value;

         // Reset fields
         pushDateField.classList.add('d-none');
         recurrencePatternField.classList.add('d-none');
         weeklyDaysField.classList.add('d-none');
         monthlyDateField.classList.add('d-none');

         // Show fields based on push type
         if (pushType === 'one_time') {
            pushDateField.classList.remove('d-none');
         } else if (pushType === 'recurring') {
            recurrencePatternField.classList.remove('d-none');
         }

         // Show fields based on recurrence pattern
         if (recurrencePattern === 'weekly') {
            weeklyDaysField.classList.remove('d-none');
         } else if (recurrencePattern === 'monthly') {
            monthlyDateField.classList.remove('d-none');
         }
      }

      // Initial load
      toggleFields();

      // Event listeners
      pushTypeSelect.addEventListener('change', toggleFields);
      recurrencePatternSelect.addEventListener('change', toggleFields);
   });
</script>
@endsection
