@extends('layouts.main')
@section('content')
<div class="nk-content sifu-view-page">
   <div class="fluid-container">
      <div class="nk-content-inner">
         <div class="nk-content-body">
            <div class="nk-block-head">
               <div class="nk-block-head-between flex-wrap gap g-2">
                  <div class="nk-block-head-content">
                     <h2 class="nk-block-title">
                        Add User Role
                     </h2>
                     <nav>
                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Setting</a></li>
                           <li class="breadcrumb-item active" aria-current="page"> Add User Role</li>
                        </ol>
                     </nav>
                  </div>
               </div>
            </div>
            <div class="nk-block">
               <div class="card card-gutter-md">
                  <div class="card-body">
                     <form action={{ route('addRolesPermission') }} method="post">
                     @csrf
                     <div class="bio-block cusers-roles">
                        <div class="col-lg-4">
                           <div class="form-group">
                              <label class="form-label">Name</label>
                              <div class="form-control-wrap">
                                <input type="text" class="form-control" required name="adduserrole" id="adduserole" placeholder="">
                              </div>
                           </div>
                        </div>
                        <h3>Access List</h3>
                        <div class="row g-4">
                           <div class="col-md-3 col-sm-6">
                              <h4>Analytics</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="analytics-tutor-subject" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Tutor Vs Subject</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="analytics-subject-subject" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Student vs Subject</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="analytics-overview" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Overview</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="analytics-platform-usage" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Platform Usage</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="analytics-customer-subject" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Customer vs Subject</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="analytics-sale-performance" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Pic Sale Performance</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="analytics-student-invoice" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Student invoice</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="analytics-total-class" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Total Class Weekday/Weekend</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Tutor Report</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-report-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-report-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-report-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Detail</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-report-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-report-payment-history" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Payment History</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-report-schedule-calen" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Tutor Schedule Calendar</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Tutor Payment</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-payment-journal" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Tutor Payment Journal</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-payment-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-payment-make" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Make Payment</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-payment-cancel" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Cancel Payment</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-payment-slip" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Payment Slip</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-payment-download-slip" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Download Payment Slip</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-payment-slip-email" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Send Payment Slip Via Email</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-payment-breakdown" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Tutor Payment Breakdown</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Tutor Bonus</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-bonus-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Detail</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-bonus-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-bonus-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-bonus-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-bonus-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Tutor</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Detail</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-payment" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Payment History</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-view-schedule" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Tutor Schedule Calendar</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-assignment" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Tutor Assignments</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-finder" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Finder</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="tutor-verify" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Verify</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>System Log</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="system-user-activities" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">User Activities</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="system-text-messages" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Text Messages</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Student PIC Commission</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-pic-commission-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Detail</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-pic-commission-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-pic-commission-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-pic-commission-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-pic-commission-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Student Pic Bonus</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-pic-bonus-details" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Details</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-pic-bonus-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-pic-bonus-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-pic-bonus-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-pic-bonus-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Student Invoice</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-invoice-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-invoice-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-invoice-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-invoice-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-invoice-receive-payment" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Receive Payment</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-invoice-refund-payment" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Refund Payment</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-invoice-cancel-payment" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Cancel Payment</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-invoice-cancel-refund" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Cancel Refund</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-invoice-view" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Invoice</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-invoice-view-schedule" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Student Schedule Calender</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Student</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Detail</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-view-invoice" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Invoices</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="student-view-schedule" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Student Schedule Calendar</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Staff Payment</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="staff-payment-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="staff-payment-make" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Make Payment</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="staff-payment-cancel" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Cancel Payment</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="staff-payment-view-slip" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Payment Slip</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="staff-payment-download-slip" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Download Payment Slip</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="staff-payment-email-slip" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Send Payment Slip Via Email</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="staff-payment-pic-commission" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Student PIC Commission</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Staff</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input type="checkbox" name="permissions[]" value="staff-view-list">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input type="checkbox" name="permissions[]" value="staff-add">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input type="checkbox" name="permissions[]" value="staff-view-detail">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Details</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input type="checkbox" name="permissions[]" value="staff-edit">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input type="checkbox" name="permissions[]" value="staff-delete">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input type="checkbox" name="permissions[]" value="staff-view-payments">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Payment History</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Settings</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="settings-view-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Account Information</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="settings-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="settings-view-state-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View State List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="settings-add-state" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add State</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="settings-edit-state" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit State</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="settings-delete-state" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">&Delete State</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="settings-view-state-details" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View State Details</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="settings-view-message-template-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Message Template List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="settings-edit-message-details" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit Message Template</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="settings-view-message-template-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Message Template Details</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Sales Invoice</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-details" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Details</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-receive-payment" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Receive Payment</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-refund-payment" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Refund Payment</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-cancel-payment" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Cancel Payment</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-cancel-refund" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Cancel Refund</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-view-invoice" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Invoice</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-view-payment-receipt" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Payment Receipt</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-download-invoice" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Download Invoice</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-download-payment-receipt" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Download Payment Receipt</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-send-invoice-via-email" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Send Invoice Via Email</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-send-payment-receipt-via-email" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Send Payment Receipt Via Email</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="sales-invoice-view-payment-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Payment List</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Report</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="report-trial-balance" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Trial Balance</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="report-income-statement" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Income Statement</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="report-income-by-rpoduct" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Income by Product</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="report-balance-sheet" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Balance Sheet</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="report-cash-flow" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Cash Flow</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="report-general-ledger" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">General Ledger</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Product</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="product-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="product-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="product-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="product-view-details" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Details</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="product-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Journal Ledger</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="journal-ledger-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="journal-ledger-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="journal-ledger-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="journal-ledger-view-details" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Details</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="journal-ledger-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Follow Up</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="follow-up-student-invoice" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Student Invoice Ready For Confirmation</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="follow-up-tutor-not-update-class" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Tutor Not Update Class Schedule</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="follow-up-tutor-not-submit-report" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Tutor Not Submit Report</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Expenditure</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="expenditure-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="expenditure-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="expenditure-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="expenditure-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Details</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="expenditure-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Dashboard</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="dashboard-income-expenses" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Income & Expenses Summary</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="dashboard-revenue-expenses" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Revenue and Expenses</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="dashboard-expenses-category" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Expenses Category</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="dashboard-active-chart" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Active Chart Accounts</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="dashboard-cash-flow" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Cash Flow</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="dashboard-unpaid-sale-invoice" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Unpaid Sales Invoice</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Customer</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="customer-view" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="customer-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="customer-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="customer-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Detail</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="customer-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Creditor Payment</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="creditor-payment-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="creditor-payment-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="creditor-payment-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Detail</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="creditor-payment-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Credit Invoice</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="credit-invoice-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="credit-invoice-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="credit-invoice-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="credit-invoice-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Detail</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="credit-invoice-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Class Schedule</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="class-schedule-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="class-schedule-view-history" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Schedule History</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="class-schedule-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="class-schedule-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Chart of Account</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="char-account-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="char-account-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="char-account-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="char-account-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Detail</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="char-account-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Billing</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="billing-invoices-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Invoices</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="billing-view-invoice" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Invoice</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="billing-download-invoice" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Download Invoice</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>User</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="user-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="user-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="user-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="user-change-password" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Change Passowrd</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="user-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Details</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="user-activity-log" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Activity Logs</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="user-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="user-edit-access" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit User Access</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>User Role</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="user-role-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="user-role-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="user-edit-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="user-edit-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>My Staff Payment</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="my-staff-payment-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="my-staff-payment-slip" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Payment Slip</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="my-staff-payment-slip-download" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Download Payment Slip</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Apple Redemption Code</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="apply-redemption-code-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Lsit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="apply-redemption-code-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="apply-redemption-code-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Details</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Job Ticket</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="job-ticket-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="job-ticket-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Details</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="job-ticket-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="job-ticket-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="job-ticket-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="job-ticket-complete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Complete Job Ticket</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="job-ticket-tutor-application" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Tutor Application Summary</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Job Ticket Application</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="job-ticket-application-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Mobile News</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-news-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-news-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Details</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-news-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-news-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-news-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Mobile Notification</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-notification-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-notification-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-notification-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Mobile Self Push Notification</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-push-notification-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-push-notification-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-push-notification-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-push-notification-detail" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Details</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-push-notification-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Mobile Banner Advertisement</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-banner-advertise-list" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View List</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-banner-advertise-add" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Add</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-banner-advertise-edit" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Edit</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-banner-advertise-details" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">View Details</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="mobile-banner-advertise-delete" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Delete</span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-3 col-sm-6">
                              <h4>Operation Report</h4>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="operation-report-daily-ticket" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Daily Ticket Application</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="operation-report-invoice-status" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Monthly Invoice Charge Status</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="operation-report-monthly" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Monthly</span>
                                 </label>
                              </div>
                              <div class="checkbox style-e">
                                 <label class="small">
                                 <input name="permissions[]" value="operation-report-nakngaji-product-commission" type="checkbox">
                                 <span class="checkbox__checkmark"></span>
                                 <span class="checkbox__body">Monthly NakNgaji Product Vs Commision</span>
                                 </label>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-2 col-sm-6">
                              <button type="submit" class="btn btn-primary">Submit</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection