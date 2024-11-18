@extends('layouts.main')
@section('content')
    <div class="nk-content">
        <div class="fluid-container">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head">
                        <div class="nk-block-head">
                            <div class="nk-block-head-between flex-wrap gap g-2 align-items-center">
                                <div class="nk-block-head-content">
                                    <div class="d-flex flex-column flex-md-row align-items-md-center">
                                        <div class="mt-3 mt-md-0 ms-md-3">
                                            <h3 class="title mb-1">ADD USER ROLE</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card card-gutter-md">
                            <div class="card-body">
                                <form action={{ route('editRole') }} method="post">
                                    @csrf
                                    <div class="bio-block">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="postalcode" class="form-label">Name</label>
                                                <div class="form-control-wrap"><input type="text" class="form-control"
                                                                                      required name="adduserrole"
                                                                                      id="adduserole" placeholder=""
                                                                                      value="{{ $role->name ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <h2>Access List</h2>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-6 ">
                                                    <strong class="small">Analytics</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('analytics-tutor-subject')) checked
                                                                    @endif name="permissions[]"
                                                                    value="analytics-tutor-subject"
                                                                    class="small" type="checkbox">&nbspTutor
                                                            Vs Subject</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('analytics-subject-subject')) checked
                                                                    @endif class="small" name="permissions[]"
                                                                    value="analytics-subject-subject"
                                                                    type="checkbox">&nbspStudent
                                                            vs Subject</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('analytics-overview')) checked
                                                                    @endif class="small" name="permissions[]"
                                                                    value="analytics-overview"
                                                                    type="checkbox">&nbspOverview</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('analytics-platform-usage')) checked
                                                                    @endif class="small" name="permissions[]"
                                                                    value="analytics-platform-usage"
                                                                    type="checkbox">&nbspPlarform
                                                            Usage</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('analytics-customer-subject')) checked
                                                                    @endif class="small" name="permissions[]"
                                                                    value="analytics-customer-subject"
                                                                    type="checkbox">&nbspCustomer vs
                                                            Subject</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('analytics-sale-performance')) checked
                                                                    @endif name="permissions[]"
                                                                    value="analytics-sale-performance"
                                                                    class="small" type="checkbox">&nbspPic
                                                            Sale Performance</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('analytics-student-invoice')) checked
                                                                    @endif class="small" name="permissions[]"
                                                                    value="analytics-student-invoice"
                                                                    type="checkbox">&nbspStudent
                                                            invoice</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('analytics-total-class')) checked
                                                                    @endif class="small" name="permissions[]"
                                                                    value="analytics-total-class"
                                                                    type="checkbox">&nbspTotal
                                                            Class Weekday/Weekened</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6 ">
                                                    <strong class="small">Tutor Report</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-report-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-report-list"
                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-report-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-report-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-report-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-report-detail"
                                                                    type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-report-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-report-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-report-payment-history')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-report-payment-history"
                                                                    type="checkbox">&nbspView Payment
                                                            History</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-report-schedule-calen')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-report-schedule-calen"
                                                                    type="checkbox">&nbspView Tutor
                                                            Schedule
                                                            Calen</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6 ">
                                                    <strong class="small">Tutor Payment</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-payment-journal')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-payment-journal"
                                                                    type="checkbox">&nbspTutor Payment
                                                            Journal</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-payment-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-payment-list"
                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-payment-make')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-payment-make"
                                                                    type="checkbox">&nbspMake
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-payment-cancel')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-payment-cancel"
                                                                    type="checkbox">&nbspCancel
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-payment-slip')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-payment-slip"
                                                                    type="checkbox">&nbspView Payment
                                                            Slip</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-payment-download-slip')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-payment-download-slip"
                                                                    type="checkbox">&nbspDownload
                                                            Payment
                                                            Slip</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-payment-slip-email')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-payment-slip-email"
                                                                    type="checkbox">&nbspSend Payment
                                                            Slip
                                                            Via
                                                            Email</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-payment-breakdown')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-payment-breakdown"
                                                                    type="checkbox">&nbspTutor Payment
                                                            Breakdown</label>
                                                    </div>

                                                </div>
                                                <div class="col-md-2 col-sm-6 ">
                                                    <strong class="small">Tutor Bonus</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-bonus-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-bonus-detail"
                                                                    type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-bonus-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-bonus-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-bonus-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-bonus-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-bonus-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-bonus-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-bonus-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-bonus-list"
                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6 ">
                                                    <strong class="small">Tutor</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-list" type="checkbox"
                                                            >&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-add" type="checkbox"
                                                            >&nbspAdd
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-detail"
                                                                    type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-edit')) checked
                                                                    @endif
                                                                    name="permissions[]"
                                                                    value="tutor-edit" type="checkbox">&nbspEdit
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-paymeny')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-paymeny"
                                                                    type="checkbox">&nbspView Payment
                                                            History</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-view-schedule')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-view-schedule"
                                                                    type="checkbox">&nbspView Tutor
                                                            Schedule
                                                            Calendar</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-assignment')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-assignment"
                                                                    type="checkbox">&nbspTutor
                                                            Assignments</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-finder')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-finder"
                                                                    type="checkbox">&nbspFinder</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('tutor-verify')) checked
                                                                    @endif name="permissions[]"
                                                                    value="tutor-verify"
                                                                    type="checkbox">&nbspVerify</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6 ">
                                                    <strong class="small">System Log</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('system-user-activities')) checked
                                                                    @endif name="permissions[]"
                                                                    value="system-user-activities"
                                                                    type="checkbox">&nbspUser
                                                            Activities</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('system-text-messages')) checked
                                                                    @endif name="permissions[]"
                                                                    value="system-text-messages"
                                                                    type="checkbox">&nbspText
                                                            Messages</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Student PIC Commision</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-pic-commission-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-pic-commission-detail"
                                                                    type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-pic-commission-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-pic-commission-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-pic-commission-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-pic-commission-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-pic-commission-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-pic-commission-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-pic-commission-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-pic-commission-list"
                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Student Pic Bonus</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-pic-bonus-details')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-pic-bonus-details"
                                                                    type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-pic-bonus-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-pic-bonus-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-pic-bonus-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-pic-bonus-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-pic-bonus-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-pic-bonus-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-pic-bonus-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-pic-bonus-list"
                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Student Invoice</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-invoice-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-invoice-list"
                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-invoice-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-invoice-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-invoice-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-invoice-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-invoice-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-invoice-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-invoice-receive-payment')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-invoice-receive-payment"
                                                                    type="checkbox">&nbspReceive
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-invoice-refund-payment')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-invoice-refund-payment"
                                                                    type="checkbox">&nbspRefund
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-invoice-cancel-payment')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-invoice-cancel-payment"
                                                                    type="checkbox">&nbspCancel
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-invoice-cancel-refund')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-invoice-cancel-refund"
                                                                    type="checkbox">&nbspCancel
                                                            Refund</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-invoice-view')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-invoice-view"
                                                                    type="checkbox">&nbspView
                                                            Invoice</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-invoice-view-schedule')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-invoice-view-schedule"
                                                                    type="checkbox">&nbspView Student
                                                            Schedule
                                                            Calender</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Student</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-list"
                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="sm"><input
                                                                    @if($role->hasPermissionTo('student-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-detail"
                                                                    type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-view-invoice')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-view-invoice"
                                                                    type="checkbox">&nbspView
                                                            Invoices</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('student-view-schedule')) checked
                                                                    @endif name="permissions[]"
                                                                    value="student-view-schedule"
                                                                    type="checkbox">&nbspView Student
                                                            Schedule
                                                            Calendar</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Staff Payment</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-payment-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="staff-payment-list"
                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-payment-make')) checked
                                                                    @endif name="permissions[]"
                                                                    value="staff-payment-make"
                                                                    type="checkbox">&nbspMake
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-payment-cancel')) checked
                                                                    @endif name="permissions[]"
                                                                    value="staff-payment-cancel"
                                                                    type="checkbox">&nbspCancel
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-payment-view-slip')) checked
                                                                    @endif name="permissions[]"
                                                                    value="staff-payment-view-slip"
                                                                    type="checkbox">&nbspView Payment
                                                            Slip</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-payment-download-slip')) checked
                                                                    @endif name="permissions[]"
                                                                    value="staff-payment-download-slip"
                                                                    type="checkbox">&nbspDownload
                                                            Payment
                                                            Slip</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-payment-email-slip')) checked
                                                                    @endif name="permissions[]"
                                                                    value="staff-payment-email-slip"
                                                                    type="checkbox">&nbspSend Payment
                                                            Slip
                                                            Via
                                                            Email</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-payment-pic-commission')) checked
                                                                    @endif name="permissions[]"
                                                                    value="staff-payment-pic-commission"
                                                                    type="checkbox">&nbspView Student
                                                            PIC
                                                            Commission</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Staff</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-view-list')) checked
                                                                    @endif  type="checkbox" name="permissions[]"
                                                                    value="staff-view-list">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-add')) checked
                                                                    @endif  type="checkbox" name="permissions[]"
                                                                    value="staff-add">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-view-detail')) checked
                                                                    @endif  type="checkbox" name="permissions[]"
                                                                    value="staff-view-detail">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-edit')) checked
                                                                    @endif  type="checkbox" name="permissions[]"
                                                                    value="staff-edit">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-delete')) checked
                                                                    @endif  type="checkbox" name="permissions[]"
                                                                    value="staff-delete">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('staff-view-payments')) checked
                                                                    @endif  type="checkbox" name="permissions[]"
                                                                    value="staff-view-payments">&nbspView
                                                            Payment
                                                            History</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Settings</strong>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('settings-view-detail')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="settings-view-detail"
                                                                                    type="checkbox">&nbspView Account Information</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('settings-edit')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="settings-edit"
                                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('settings-view-state-list')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="settings-view-state-list"
                                                                                    type="checkbox">&nbspView State List</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('settings-add-state')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="settings-add-state"
                                                                                    type="checkbox">&nbspAdd State</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('settings-edit-state')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="settings-edit-state"
                                                                                    type="checkbox">&nbspEdit State</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('settings-delete-state')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="settings-delete-state"
                                                                                    type="checkbox">&Delete State</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('settings-view-state-details')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="settings-view-state-details"
                                                                                    type="checkbox">&nbspView State Details</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('settings-view-message-template-list')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="settings-view-message-template-list"
                                                                                    type="checkbox">&nbspView Message Template List</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('settings-edit-message-details')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="settings-edit-message-details"
                                                                                    type="checkbox">&nbspEdit Message Template</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('settings-view-message-template-detail')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="settings-view-message-template-detail"
                                                                                    type="checkbox">&nbspView Message Template Details</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Sales Invoice</strong>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-details')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-details"
                                                                                    type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-add')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-add"
                                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('expenditure-list')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-edit"
                                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-delete')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-delete"
                                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-receive-payment')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-receive-payment"
                                                                                    type="checkbox"> Receive Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-refund-payment')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-refund-payment"
                                                                                    type="checkbox"> Refund Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-cancel-payment')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-cancel-payment"
                                                                                    type="checkbox">Cancel Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-cancel-refund')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-cancel-refund"
                                                                                    type="checkbox"> Cancel Refund</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-view-invoice')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-view-invoice"
                                                                                    type="checkbox"> View Invoice</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-view-payment-receipt')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-view-payment-receipt"
                                                                                    type="checkbox"> View Payment Receipt</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-download-invoice')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-download-invoice"
                                                                                    type="checkbox"> Download Invoice</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-download-payment-receipt')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-download-payment-receipt"
                                                                                    type="checkbox"> Download Payment Receipt</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-send-invoice-via-email')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-send-invoice-via-email"
                                                                                    type="checkbox"> Send Invoice Via Email</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-send-payment-receipt-via-email')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-send-payment-receipt-via-email"
                                                                                    type="checkbox"> Send Payment Receipt Via Email</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('sales-invoice-view-payment-list')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="sales-invoice-view-payment-list"
                                                                                    type="checkbox"> View Payment List</label>
                                                    </div>

                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Report</strong>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('report-trial-balance')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="report-trial-balance"
                                                                                    type="checkbox"> Trial Balance</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('report-income-statement')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="report-income-statement"
                                                                                    type="checkbox"> Income Statement</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('report-income-by-rpoduct')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="report-income-by-rpoduct"
                                                                                    type="checkbox"> Income by Product</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('report-balance-sheet')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="report-balance-sheet"
                                                                                    type="checkbox"> Balance Sheet</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('report-cash-flow')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="report-cash-flow"
                                                                                    type="checkbox"> Cash Flow</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('report-general-ledger')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="report-general-ledger"
                                                                                    type="checkbox"> General Ledger</label>
                                                    </div>

                                                </div>

                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Product</strong>
                                                    <div>
                                                        <label class="small"><input @if($role->hasPermissionTo('product-list')) checked
                                                                                    @endif  name="permissions[]"
                                                                                    value="product-list"
                                                                                    type="checkbox"> View List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('product-add')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="product-add"
                                                                                    type="checkbox"> Add</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('product-edit')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="product-edit"
                                                                                    type="checkbox"> Edit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('expenditure-list')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="product-view-details"
                                                                                    type="checkbox"> View Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('product-delete')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="product-delete"
                                                                                    type="checkbox"> Delete</label>
                                                    </div>

                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Journal Ledger</strong>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('journal-ledger-list')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="journal-ledger-list"
                                                                                    type="checkbox"> View List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('journal-ledger-add')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="journal-ledger-add"
                                                                                    type="checkbox"> Add</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('journal-ledger-edit')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="journal-ledger-edit"
                                                                                    type="checkbox"> Edit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('journal-ledger-view-details')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="journal-ledger-view-details"
                                                                                    type="checkbox"> View Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('journal-ledger-delete')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="journal-ledger-delete"
                                                                                    type="checkbox"> Delete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Follow Up</strong>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('follow-up-student-invoice')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="follow-up-student-invoice"
                                                                                    type="checkbox"> Student Invoice Ready For Confirmation</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('follow-up-tutor-not-update-class')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="follow-up-tutor-not-update-class"
                                                                                    type="checkbox"> Tutor Not Update Class Schedule</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input  @if($role->hasPermissionTo('follow-up-tutor-not-submit-report')) checked
                                                                                     @endif name="permissions[]"
                                                                                    value="follow-up-tutor-not-submit-report"
                                                                                    type="checkbox"> Tutor Not Submit Report</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Expenditure</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('expenditure-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="expenditure-list"
                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('expenditure-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="expenditure-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('expenditure-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="expenditure-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('expenditure-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="expenditure-detail"
                                                                    type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('expenditure-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="expenditure-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Dashboard</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('dashboard-income-expenses')) checked
                                                                    @endif name="permissions[]"
                                                                    value="dashboard-income-expenses"
                                                                    type="checkbox">&nbspIncome &
                                                            Expenses
                                                            Summary</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('dashboard-revenue-expenses')) checked
                                                                    @endif name="permissions[]"
                                                                    value="dashboard-revenue-expenses"
                                                                    type="checkbox">&nbspRevenue and
                                                            Expenses</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('dashboard-expenses-category')) checked
                                                                    @endif name="permissions[]"
                                                                    value="dashboard-expenses-category"
                                                                    type="checkbox">&nbspExpenses
                                                            Category</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('dashboard-active-chart')) checked
                                                                    @endif name="permissions[]"
                                                                    value="dashboard-active-chart"
                                                                    type="checkbox">&nbspActive Chart
                                                            Accounts</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('dashboard-cash-flow')) checked
                                                                    @endif name="permissions[]"
                                                                    value="dashboard-cash-flow"
                                                                    type="checkbox">&nbspCash
                                                            Flow</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('dashboard-unpaid-sale-invoice')) checked
                                                                    @endif name="permissions[]"
                                                                    value="dashboard-unpaid-sale-invoice"
                                                                    type="checkbox">&nbspUnpaid Sales
                                                            Invoice</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Customer</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('customer-view')) checked
                                                                    @endif name="permissions[]"
                                                                    value="customer-view" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('customer-add')) checked
                                                                    @endif  name="permissions[]"
                                                                    value="customer-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('customer-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="customer-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('customer-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="customer-detail" type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('customer-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="customer-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Creditor Payment</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('creditor-payment-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="creditor-payment-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('creditor-payment-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="creditor-payment-edit" type="checkbox">&nbspAdd
                                                            Edit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('creditor-payment-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="creditor-payment-detail" type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('creditor-payment-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="creditor-payment-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Credit Invoice</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('credit-invoice-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="credit-invoice-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('credit-invoice-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="credit-invoice-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('credit-invoice-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="credit-invoice-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('credit-invoice-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="credit-invoice-detail" type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('credit-invoice-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="credit-invoice-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Class Schedule</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('class-schedule-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="class-schedule-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('class-schedule-view-history')) checked
                                                                    @endif name="permissions[]"
                                                                    value="class-schedule-view-history" type="checkbox">&nbspView
                                                            Schedule
                                                            History</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('class-schedule-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="class-schedule-edit" type="checkbox">&nbspAdd
                                                            Edit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('class-schedule-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="class-schedule-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Chart of Account</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('char-account-list'))
                                                                        checked
                                                                    @endif name="permissions[]"
                                                                    value="char-account-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('char-account-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="char-account-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('char-account-edit'))
                                                                        checked
                                                                    @endif name="permissions[]"
                                                                    value="char-account-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('char-account-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="char-account-detail" type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('char-account-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="char-account-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Billing</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('billing-invoices-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="billing-invoices-list"
                                                                    type="checkbox">&nbspInvoices</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('billing-view-invoice')) checked
                                                                    @endif name="permissions[]"
                                                                    value="billing-view-invoice" type="checkbox">&nbspView
                                                            Invoice</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('billing-download-invoice')) checked
                                                                    @endif name="permissions[]"
                                                                    value="billing-download-invoice" type="checkbox">&nbspDownload
                                                            Invoice</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">User</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('user-list')) checked
                                                                    @endif
                                                                    name="permissions[]"
                                                                    value="user-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('user-add')) checked
                                                                    @endif
                                                                    name="permissions[]"
                                                                    value="user-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('user-edit')) checked
                                                                    @endif
                                                                    name="permissions[]"
                                                                    value="user-edit" type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('user-change-password')) checked
                                                                    @endif name="permissions[]"
                                                                    value="user-change-password" type="checkbox">&nbspChange
                                                            Password</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('user-detail')) checked
                                                                    @endif
                                                                    name="permissions[]"
                                                                    value="user-detail" type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('user-activity-log'))
                                                                        checked
                                                                    @endif name="permissions[]"
                                                                    value="user-activity-log" type="checkbox">&nbspView
                                                            Activity
                                                            Logs
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('user-delete')) checked
                                                                    @endif
                                                                    name="permissions[]"
                                                                    value="user-edit"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('user-edit-access')) checked
                                                                    @endif name="permissions[]"
                                                                    value="user-edit-access" type="checkbox">&nbspEdit
                                                            User
                                                            Access</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">User Role</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('user-role-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="user-role-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('user-role-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="user-role-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('user-edit-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="user-edit-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('user-edit-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="user-edit-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">My Staff Payment</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('my-staff-payment-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="my-staff-payment-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('my-staff-payment-slip')) checked
                                                                    @endif name="permissions[]"
                                                                    value="my-staff-payment-slip" type="checkbox">&nbspView
                                                            Payment
                                                            Slip</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('my-staff-payment-slip-download')) checked
                                                                    @endif name="permissions[]"
                                                                    value="my-staff-payment-slip-download"
                                                                    type="checkbox">&nbspDownload
                                                            Payment
                                                            Slip</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Apple Redemption Code</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('apply-redemption-code-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="apply-redemption-code-list" type="checkbox">&nbspView
                                                            Lsit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('apply-redemption-code-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="apply-redemption-code-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('apply-redemption-code-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="apply-redemption-code-detail"
                                                                    type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Job Ticket</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('job-ticket-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="job-ticket-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('job-ticket-detail'))
                                                                    checked
                                                                    @endif name="permissions[]"
                                                                    value="job-ticket-detail" type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('job-ticket-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="job-ticket-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('job-ticket-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="job-ticket-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('job-ticket-delete'))
                                                                    checked
                                                                    @endif name="permissions[]"
                                                                    value="job-ticket-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('job-ticket-complete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="job-ticket-complete" type="checkbox">&nbspComplete
                                                            Job
                                                            Ticket</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('job-ticket-tutor-application')) checked
                                                                    @endif name="permissions[]"
                                                                    value="job-ticket-tutor-application"
                                                                    type="checkbox">&nbspView Tutor
                                                            Application
                                                            Summary</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Job Ticket Application</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('job-ticket-application-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="job-ticket-application-edit" type="checkbox">&nbspEdit</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Mobile News</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-news-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-news-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-news-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-news-detail" type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-news-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-news-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-news-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-news-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-news-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-news-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Mobile Notification</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-notification-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-notification-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-notification-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-notification-add" type="checkbox">&nbspAdd
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-notification-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-notification-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Mobile Self Push Notification</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-push-notification-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-push-notification-list"
                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-push-notification-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-push-notification-add"
                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-push-notification-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-push-notification-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-push-notification-detail')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-push-notification-detail"
                                                                    type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-push-notification-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-push-notification-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Mobile Banner Advertisement</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-banner-advertise-list')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-banner-advertise-list"
                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-banner-advertise-add')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-banner-advertise-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-banner-advertise-edit')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-banner-advertise-edit"
                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-banner-advertise-details')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-banner-advertise-details"
                                                                    type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('mobile-banner-advertise-delete')) checked
                                                                    @endif name="permissions[]"
                                                                    value="mobile-banner-advertise-delete"
                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <strong class="small">Operation Report</strong>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('operation-report-daily-ticket')) checked
                                                                    @endif name="permissions[]"
                                                                    value="operation-report-daily-ticket"
                                                                    type="checkbox">&nbspDaily Ticket
                                                            Application</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('operation-report-invoice-status')) checked
                                                                    @endif name="permissions[]"
                                                                    value="operation-report-invoice-status"
                                                                    type="checkbox">&nbspMonthly
                                                            Invoice
                                                            Charge
                                                            Status</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('operation-report-monthly')) checked
                                                                    @endif name="permissions[]"
                                                                    value="operation-report-monthly" type="checkbox">&nbspMonthly</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input
                                                                    @if($role->hasPermissionTo('operation-report-nakngaji-product-commission')) checked
                                                                    @endif name="permissions[]"
                                                                value="operation-report-nakngaji-product-commission"
                                                                type="checkbox">&nbspMonthly
                                                            NakNgaji
                                                            Product
                                                            Vs Commision</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-6">
                                                    <button type="button" class="btn btn-light">Cancel</button>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <button type="submit" class="btn btn-info ml-2">Submit</button>
                                                    <!-- Added ml-2 for 10px gap -->
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
@endsection
