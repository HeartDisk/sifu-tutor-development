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
                                <form action={{ route('addRolesPermission') }} method="post">
                                    @csrf
                                    <div class="bio-block">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="postalcode" class="form-label">Name</label>
                                                <div class="form-control-wrap"><input type="text" class="form-control"
                                                                                      required name="adduserrole"
                                                                                      id="adduserole" placeholder="">
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
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="analytics-tutor-subject"
                                                                                    class="small" type="checkbox">&nbspTutor
                                                            Vs Subject</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input class="small" name="permissions[]"
                                                                                    value="analytics-subject-subject"
                                                                                    type="checkbox">&nbspStudent
                                                            vs Subject</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input class="small" name="permissions[]"
                                                                                    value="analytics-overview"
                                                                                    type="checkbox">&nbspOverview</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input class="small" name="permissions[]"
                                                                                    value="analytics-platform-usage"
                                                                                    type="checkbox">&nbspPlarform
                                                            Usage</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input class="small" name="permissions[]"
                                                                                    value="analytics-customer-subject"
                                                                                    type="checkbox">&nbspCustomer vs
                                                            Subject</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="analytics-sale-performance"
                                                                                    class="small" type="checkbox">&nbspPic
                                                            Sale Performance</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input class="small" name="permissions[]"
                                                                                    value="analytics-student-invoice"
                                                                                    type="checkbox">&nbspStudent
                                                            invoice</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input class="small" name="permissions[]"
                                                                                    value="analytics-total-class"
                                                                                    type="checkbox">&nbspTotal
                                                            Class Weekday/Weekened</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6 ">
                                                    <strong class="small">Tutor Report</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-report-list"
                                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-report-add"
                                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-report-detail"
                                                                                    type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-report-delete"
                                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-report-payment-history"
                                                                                    type="checkbox">&nbspView Payment
                                                            History</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-report-schedule-calen"
                                                                                    type="checkbox">&nbspView Tutor
                                                            Schedule
                                                            Calen</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6 ">
                                                    <strong class="small">Tutor Payment</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-payment-journal"
                                                                                    type="checkbox">&nbspTutor Payment
                                                            Journal</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-payment-list"
                                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-payment-make"
                                                                                    type="checkbox">&nbspMake
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-payment-cancel"
                                                                                    type="checkbox">&nbspCancel
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-payment-slip"
                                                                                    type="checkbox">&nbspView Payment
                                                            Slip</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-payment-download-slip"
                                                                                    type="checkbox">&nbspDownload
                                                            Payment
                                                            Slip</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-payment-slip-email"
                                                                                    type="checkbox">&nbspSend Payment
                                                            Slip
                                                            Via
                                                            Email</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-payment-breakdown"
                                                                                    type="checkbox">&nbspTutor Payment
                                                            Breakdown</label>
                                                    </div>

                                                </div>
                                                <div class="col-md-2 col-sm-6 ">
                                                    <strong class="small">Tutor Bonus</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-bonus-detail"
                                                                                    type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-bonus-add"
                                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-bonus-edit"
                                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-bonus-delete"
                                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-bonus-list"
                                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6 ">
                                                    <strong class="small">Tutor</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-add" type="checkbox">&nbspAdd
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-detail"
                                                                                    type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-edit" type="checkbox">&nbspEdit
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-delete"
                                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-paymeny"
                                                                                    type="checkbox">&nbspView Payment
                                                            History</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-view-schedule"
                                                                                    type="checkbox">&nbspView Tutor
                                                            Schedule
                                                            Calendar</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-assignment"
                                                                                    type="checkbox">&nbspTutor
                                                            Assignments</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-finder"
                                                                                    type="checkbox">&nbspFinder</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="tutor-verify"
                                                                                    type="checkbox">&nbspVerify</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6 ">
                                                    <strong class="small">System Log</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="system-user-activities"
                                                                                    type="checkbox">&nbspUser
                                                            Activities</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
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
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-pic-commission-detail"
                                                                                    type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-pic-commission-add"
                                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-pic-commission-edit"
                                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-pic-commission-delete"
                                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-pic-commission-list"
                                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Student Pic Bonus</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-pic-bonus-details"
                                                                                    type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-pic-bonus-add"
                                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-pic-bonus-edit"
                                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-pic-bonus-delete"
                                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-pic-bonus-list"
                                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Student Invoice</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-invoice-list"
                                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-invoice-add"
                                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-invoice-edit"
                                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-invoice-delete"
                                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-invoice-receive-payment"
                                                                                    type="checkbox">&nbspReceive
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-invoice-refund-payment"
                                                                                    type="checkbox">&nbspRefund
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-invoice-cancel-payment"
                                                                                    type="checkbox">&nbspCancel
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-invoice-cancel-refund"
                                                                                    type="checkbox">&nbspCancel
                                                            Refund</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-invoice-view"
                                                                                    type="checkbox">&nbspView
                                                            Invoice</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-invoice-view-schedule"
                                                                                    type="checkbox">&nbspView Student
                                                            Schedule
                                                            Calender</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Student</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-list"
                                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-detail"
                                                                                    type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-edit"
                                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-delete"
                                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-view-invoice"
                                                                                    type="checkbox">&nbspView
                                                            Invoices</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="student-view-schedule"
                                                                                    type="checkbox">&nbspView Student
                                                            Schedule
                                                            Calendar</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Staff Payment</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="staff-payment-list"
                                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="staff-payment-make"
                                                                                    type="checkbox">&nbspMake
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="staff-payment-cancel"
                                                                                    type="checkbox">&nbspCancel
                                                            Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="staff-payment-view-slip"
                                                                                    type="checkbox">&nbspView Payment
                                                            Slip</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="staff-payment-download-slip"
                                                                                    type="checkbox">&nbspDownload
                                                            Payment
                                                            Slip</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="staff-payment-email-slip"
                                                                                    type="checkbox">&nbspSend Payment
                                                            Slip
                                                            Via
                                                            Email</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="staff-payment-pic-commission"
                                                                                    type="checkbox">&nbspView Student
                                                            PIC
                                                            Commission</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Staff</strong>
                                                    <div>
                                                        <label class="small"><input type="checkbox" name="permissions[]"
                                                                                    value="staff-view-list">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input type="checkbox" name="permissions[]"
                                                                                    value="staff-add">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input type="checkbox" name="permissions[]"
                                                                                    value="staff-view-detail">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input type="checkbox" name="permissions[]"
                                                                                    value="staff-edit">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input type="checkbox" name="permissions[]"
                                                                                    value="staff-delete">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input type="checkbox" name="permissions[]"
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
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="settings-view-detail"
                                                                                    type="checkbox">&nbspView Account Information</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="settings-edit"
                                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="settings-view-state-list"
                                                                                    type="checkbox">&nbspView State List</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="settings-add-state"
                                                                                    type="checkbox">&nbspAdd State</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="settings-edit-state"
                                                                                    type="checkbox">&nbspEdit State</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="settings-delete-state"
                                                                                    type="checkbox">&Delete State</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="settings-view-state-details"
                                                                                    type="checkbox">&nbspView State Details</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="settings-view-message-template-list"
                                                                                    type="checkbox">&nbspView Message Template List</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="settings-edit-message-details"
                                                                                    type="checkbox">&nbspEdit Message Template</label>
                                                    </div>

                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="settings-view-message-template-detail"
                                                                                    type="checkbox">&nbspView Message Template Details</label>
                                                    </div>

                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Sales Invoice</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-details"
                                                                                    type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-add"
                                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-edit"
                                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-delete"
                                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-receive-payment"
                                                                                    type="checkbox"> Receive Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-refund-payment"
                                                                                    type="checkbox"> Refund Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-cancel-payment"
                                                                                    type="checkbox"> Cancel Payment</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-cancel-refund"
                                                                                    type="checkbox"> Cancel Refund</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-view-invoice"
                                                                                    type="checkbox"> View Invoice</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-view-payment-receipt"
                                                                                    type="checkbox"> View Payment Receipt</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-download-invoice"
                                                                                    type="checkbox"> Download Invoice</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-download-payment-receipt"
                                                                                    type="checkbox"> Download Payment Receipt</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-send-invoice-via-email"
                                                                                    type="checkbox"> Send Invoice Via Email</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-send-payment-receipt-via-email"
                                                                                    type="checkbox"> Send Payment Receipt Via Email</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="sales-invoice-view-payment-list"
                                                                                    type="checkbox"> View Payment List</label>
                                                    </div>

                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Report</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="report-trial-balance"
                                                                                    type="checkbox"> Trial Balance</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="report-income-statement"
                                                                                    type="checkbox"> Income Statement</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="report-income-by-rpoduct"
                                                                                    type="checkbox"> Income by Product</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="report-balance-sheet"
                                                                                    type="checkbox"> Balance Sheet</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="report-cash-flow"
                                                                                    type="checkbox"> Cash Flow</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="report-general-ledger"
                                                                                    type="checkbox"> General Ledger</label>
                                                    </div>

                                                </div>

                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Product</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="product-list"
                                                                                    type="checkbox"> View List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="product-add"
                                                                                    type="checkbox"> Add</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="product-edit"
                                                                                    type="checkbox"> Edit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="product-view-details"
                                                                                    type="checkbox"> View Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="product-delete"
                                                                                    type="checkbox"> Delete</label>
                                                    </div>

                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Journal Ledger</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="journal-ledger-list"
                                                                                    type="checkbox"> View List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="journal-ledger-add"
                                                                                    type="checkbox"> Add</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="journal-ledger-edit"
                                                                                    type="checkbox"> Edit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="journal-ledger-view-details"
                                                                                    type="checkbox"> View Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="journal-ledger-delete"
                                                                                    type="checkbox"> Delete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Follow Up</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="follow-up-student-invoice"
                                                                                    type="checkbox"> Student Invoice Ready For Confirmation</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="follow-up-tutor-not-update-class"
                                                                                    type="checkbox"> Tutor Not Update Class Schedule</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
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
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="expenditure-list"
                                                                                    type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="expenditure-add"
                                                                                    type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="expenditure-edit"
                                                                                    type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="expenditure-detail"
                                                                                    type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="expenditure-delete"
                                                                                    type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Dashboard</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="dashboard-income-expenses"
                                                                                    type="checkbox">&nbspIncome &
                                                            Expenses
                                                            Summary</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="dashboard-revenue-expenses"
                                                                                    type="checkbox">&nbspRevenue and
                                                            Expenses</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="dashboard-expenses-category"
                                                                                    type="checkbox">&nbspExpenses
                                                            Category</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="dashboard-active-chart"
                                                                                    type="checkbox">&nbspActive Chart
                                                            Accounts</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="dashboard-cash-flow"
                                                                                    type="checkbox">&nbspCash
                                                            Flow</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="dashboard-unpaid-sale-invoice"
                                                                                    type="checkbox">&nbspUnpaid Sales
                                                            Invoice</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Customer</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="customer-view" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="customer-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="customer-edit" type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="customer-detail" type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="customer-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Creditor Payment</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="creditor-payment-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="creditor-payment-edit" type="checkbox">&nbspAdd
                                                            Edit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="creditor-payment-detail" type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="creditor-payment-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Credit Invoice</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="credit-invoice-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="credit-invoice-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="credit-invoice-edit" type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="credit-invoice-detail" type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="credit-invoice-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Class Schedule</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="class-schedule-list"  type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="class-schedule-view-history"  type="checkbox">&nbspView Schedule
                                                            History</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="class-schedule-edit"  type="checkbox">&nbspAdd
                                                            Edit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="class-schedule-delete"  type="checkbox">&nbspDelete</label>
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
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="char-account-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="char-account-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="char-account-edit" type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="char-account-detail" type="checkbox">&nbspView
                                                            Detail</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="char-account-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Billing</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="billing-invoices-list"
                                                                type="checkbox">&nbspInvoices</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="billing-view-invoice" type="checkbox">&nbspView
                                                            Invoice</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="billing-download-invoice" type="checkbox">&nbspDownload
                                                            Invoice</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">User</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="user-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="user-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="user-edit" type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="user-change-password" type="checkbox">&nbspChange
                                                            Passowrd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="user-detail" type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="user-activity-log" type="checkbox">&nbspView Activity
                                                            Logs
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="user-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="user-edit-access" type="checkbox">&nbspEdit User
                                                            Access</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">User Role</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="user-role-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="user-role-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="user-edit-edit" type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="user-edit-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">My Staff Payment</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="my-staff-payment-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="my-staff-payment-slip" type="checkbox">&nbspView Payment
                                                            Slip</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="my-staff-payment-slip-download" type="checkbox">&nbspDownload
                                                            Payment
                                                            Slip</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Apple Redemption Code</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="apply-redemption-code-list" type="checkbox">&nbspView
                                                            Lsit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="apply-redemption-code-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="apply-redemption-code-detail" type="checkbox">&nbspView
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
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="job-ticket-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="job-ticket-detail" type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="job-ticket-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="job-ticket-edit" type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="job-ticket-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="job-ticket-complete" type="checkbox">&nbspComplete Job
                                                            Ticket</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="job-ticket-tutor-application" type="checkbox">&nbspView Tutor
                                                            Application
                                                            Summary</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Job Ticket Application</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="job-ticket-application-edit" type="checkbox">&nbspEdit</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Mobile News</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-news-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-news-detail" type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-news-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-news-edit" type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-news-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Mobile Notification</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-notification-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-notification-add" type="checkbox">&nbspAdd </label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-notification-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Mobile Self Push Notification</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-push-notification-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-push-notification-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-push-notification-edit" type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-push-notification-detail" type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-push-notification-delete" type="checkbox">&nbspDelete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6">
                                                    <strong class="small">Mobile Banner Advertisement</strong>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-banner-advertise-list" type="checkbox">&nbspView
                                                            List</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-banner-advertise-add" type="checkbox">&nbspAdd</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-banner-advertise-edit" type="checkbox">&nbspEdit</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-banner-advertise-details" type="checkbox">&nbspView
                                                            Details</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="mobile-banner-advertise-delete" type="checkbox">&nbspDelete</label>
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
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="operation-report-daily-ticket" type="checkbox">&nbspDaily Ticket
                                                            Application</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="operation-report-invoice-status" type="checkbox">&nbspMonthly Invoice
                                                            Charge
                                                            Status</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="operation-report-monthly" type="checkbox">&nbspMonthly</label>
                                                    </div>
                                                    <div>
                                                        <label class="small"><input name="permissions[]"
                                                                                    value="operation-report-nakngaji-product-commission" type="checkbox">&nbspMonthly
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
