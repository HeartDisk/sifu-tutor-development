<div class="nk-sidebar-element nk-sidebar-body">
    <div class="nk-sidebar-content">
        <div class="nk-sidebar-menu" data-simplebar>
            <ul class="nk-menu">
                <li class="nk-menu-heading">
                    <h6 class="overline-title">General</h6>
                </li>
                <li class="nk-menu-item">
                    <a href="{{url('/home')}}" class="nk-menu-link">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-dashboard"></em>
                      </span>
                        <span class="nk-menu-text">Dashboard</span>
                    </a>
                </li>

                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-arrow-up"></em>
                      </span>
                        <span class="nk-menu-text">Followup</span>
                    </a>
                    <ul class="nk-menu-sub">

                        <li class="nk-menu-item">
                            <a href="{{route('StudentInvoiceReadyConfirmation')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Student Invoice Ready for Confirm</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{route('TutorNotUpdateClassSchedule')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Tutor Not Update Class Schedule</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{route('TutorNotSubmitReport')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Tutor Not Submit Report</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{route('TutorNeverLogIn')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Tutor Never Logged-in</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{route('TutorNeverScheduleClass')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Tutor Never Schedule Classes</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nk-menu-heading">
                    <h6 class="overline-title">Management</h6>
                </li>
                @can("class-schedule-list")
                    <li class="nk-menu-item">
                        <a href="{{route('ClassSchedules')}}" class="nk-menu-link">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-calendar-booking"></em>
                      </span>
                            <span class="nk-menu-text">Class Schedules</span>
                        </a>
                    </li>
                @endcan

                <li class="nk-menu-item has-sub">

                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-users"></em>
                      </span>
                        <span class="nk-menu-text">Students</span>
                    </a>


                    <ul class="nk-menu-sub">
                        @can("customer-view")
                            <li class="nk-menu-item">
                                <a href="{{route('Customers')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Customer List</span>
                                </a>
                            </li>
                        @endcan

                        @can("student-list")
                            <li class="nk-menu-item">
                                <a href="{{route('Students')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Student List</span>
                                </a>
                            </li>
                        @endcan

                        @can("student-view-schedule")
                            <li class="nk-menu-item">
                                <a href="{{route('studentSchedule')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Schedule Calender</span>
                                </a>
                            </li>
                        @endcan

                        <li class="nk-menu-item">
                            <a href="{{route('studentAssignments')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Student Assignments</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-calendar-booking"></em>
                      </span>
                        <span class="nk-menu-text">Student Invoices</span>
                    </a>
                    <ul class="nk-menu-sub">
                        @can("student-invoice-list")
                            <li class="nk-menu-item">
                                <a href="{{route('StudentInvoices')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Invoices</span>
                                </a>
                            </li>
                        @endcan
                        <li class="nk-menu-item">
                            <a href="{{route('StudentPaymentLists')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Payments</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-users"></em>
                      </span>
                        <span class="nk-menu-text">Tutors</span>
                    </a>
                    <ul class="nk-menu-sub">
                        @can("tutor-list")
                            <li class="nk-menu-item">
                                <a href="{{route('TutorList')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Tutor List</span>
                                </a>
                            </li>
                        @endcan

                        @can("tutor-view-schedule")
                            <!--<li class="nk-menu-item">-->
                            <!--    <a href="{{route('TutorScheduleCalendar')}}" class="nk-menu-link">-->
                            <!--        <span class="nk-menu-text">Schedule Calendar</span>-->
                            <!--    </a>-->
                            <!--</li>-->
                        @endcan


                        <!--<li class="nk-menu-item">-->
                        <!--  <a href="{{route('TutorReports')}}" class="nk-menu-link">-->
                        <!--    <span class="nk-menu-text">Reports</span>-->
                        <!--  </a>-->
                        <!--</li>-->

                        <li class="nk-menu-item">
                            <a href="{{route('TutorReportsV2')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Student Evaluation Report</span>
                            </a>
                        </li>

                            <li class="nk-menu-item">
                                <a href="{{route('progressReports')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Student Progress Report</span>
                                </a>
                            </li>

                        @can("tutor-assignment")
                            <!--<li class="nk-menu-item">-->
                            <!--    <a href="{{route('TutorAssignments')}}" class="nk-menu-link">-->
                            <!--        <span class="nk-menu-text">Tutor Assignments</span>-->
                            <!--    </a>-->
                            <!--</li>-->
                        @endcan

                        @can("tutor-payment-list")
                            <li class="nk-menu-item">
                                <a href="{{route('TutorPayments')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Tutor Payments</span>
                                </a>
                            </li>
                        @endcan




                        @can("tutor-finder")
                            <li class="nk-menu-item">
                                <a href="{{route('TutorFinder')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Tutor Finder</span>
                                </a>
                            </li>
                        @endcan


                    </ul>
                </li>


                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-users"></em>
                      </span>
                        <span class="nk-menu-text">Staff</span>
                    </a>
                    <ul class="nk-menu-sub">
                        @can("staff-view-list")
                            <li class="nk-menu-item">
                                <a href="{{route('StaffList')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Staff List</span>
                                </a>
                            </li>
                        @endcan
                        @can("staff-payment-list")
                            <li class="nk-menu-item">
                                <a href="{{route('StaffPayments')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Staff Payments</span>
                                </a>
                            </li>
                        @endcan


                        @can("staff-payment-pic-commission")
                            @can("student-pic-commission-list")
                                <li class="nk-menu-item">
                                    <a href="{{route('staffPaymentsViewCommissions')}}" class="nk-menu-link">
                                        <span class="nk-menu-text">Student PIC Commission</span>
                                    </a>
                                </li>
                            @endcan
                        @endcan


                    </ul>
                </li>


                @can("product-list")
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-bag"></em>
                      </span>
                            <span class="nk-menu-text">Subjects</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <!--  <li class="nk-menu-item">-->
                            <!--  <a href="{{route('services')}}" class="nk-menu-link">-->
                            <!--    <span class="nk-menu-text">Services</span>-->
                            <!--  </a>-->
                            <!--</li>-->
                            <li class="nk-menu-item">
                                <a href="{{route('CategoryList')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Levels List</span>
                                </a>
                            </li>

                            <li class="nk-menu-item">
                                <a href="{{route('subjectList')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Subjects List</span>
                                </a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{route('extraStudentCharges')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Extra Student Charges</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan


                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-list-index"></em>
                      </span>
                        <span class="nk-menu-text">Job Tickets</span>
                    </a>


                    <ul class="nk-menu-sub">
                        @can("job-ticket-list")
                            <li class="nk-menu-item">
                                <a href="{{route('TicketList')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Ticket List</span>
                                </a>
                            </li>
                        @endcan

                        @can("job-ticket-tutor-application")
                            <li class="nk-menu-item">
                                <a href="{{route('TutorApplicationSummary')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Tutor Application Summary</span>
                                </a>
                            </li>
                        @endcan

                    </ul>
                </li>

{{--                <li class="nk-menu-item">--}}
{{--                    <a href="{{route('ChartOfAccounts')}}" class="nk-menu-link">--}}
{{--                    <span class="nk-menu-icon">--}}
{{--                      <em class="icon ni ni-list-index-fill"></em>--}}
{{--                    </span>--}}
{{--                        <span class="nk-menu-text">Chart of Accounts</span>--}}
{{--                    </a>--}}
{{--                </li>--}}

                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-grid-alt"></em>
                      </span>
                        <span class="nk-menu-text">Mobile APP</span>
                    </a>
                    <ul class="nk-menu-sub">
                        @can("mobile-news-list")
                            <li class="nk-menu-item">
                                <a href="{{route('MobileAppNews')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Add News</span>
                                </a>
                            </li>
                        @endcan


                        <li class="nk-menu-item">
                            <a href="{{url('faq')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Add FAQ</span>
                            </a>
                        </li>

                        @can("mobile-notification-list")
                            <li class="nk-menu-item">
                                <a href="{{route('notification')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Notification</span>
                                </a>
                            </li>
                        @endcan


                        @can("mobile-push-notification-list")
                            <li class="nk-menu-item">
                                <a href="{{url('selfpushnotification')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Add Push Notification</span>
                                </a>
                            </li>
                        @endcan

                        @can("mobile-banner-advertise-list")
                            <li class="nk-menu-item">
                                <a href="{{route('bannerAds')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Banner Ads</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                <!--- FINANCE PAGES --->

                <li class="nk-menu-heading">
                    <h6 class="overline-title">Accounts Section</h6>
                </li>
                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-signin"></em>
                      </span>
                        <span class="nk-menu-text">Finance Section</span>
                    </a>
                    <ul class="nk-menu-sub">

                        <li class="nk-menu-item">
                            <a href="{{url('chart-of-accounts-category')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Accounts Category</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{url('chart-of-accounts-subcategory')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Accounts Sub Category</span>
                            </a>
                        </li>



                        <li class="nk-menu-item">
                            <a href="{{url('financialReport/accounts')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Chart of Accounts</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{url('financialReport/customer_statement')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Customer Ledger</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{url('financialReport/tutor_statement')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Tutor Ledger</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{url('financialReport/expense_statement')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Expense Ledger</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{url('financialReport/bank_statement')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Bank Ledger</span>
                            </a>
                        </li>
                        <!--<li class="nk-menu-item">-->
                        <!--    <a href="{{url('financialReport/class_statement')}}" class="nk-menu-link" target="_blank">-->
                        <!--        <span class="nk-menu-text">Class Ledger</span>-->
                        <!--    </a>-->
                        <!--</li>-->
                         @can("tutor-payment-journal")
                            <li class="nk-menu-item">
                                <a href="{{route('TutorPaymentJournal')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Tutor Payment Journal</span>
                                </a>
                            </li>
                        @endcan
                        <!--<li class="nk-menu-item">
                            <a href="{{url('/financialReport/general_journal')}}" class="nk-menu-link">
                                <span class="nk-menu-text">General Journal </span>
                            </a>
                        </li>-->
                        <!--<li class="nk-menu-item">-->
                        <!--    <a href="{{url('financialReport/cash_in_hand')}}" class="nk-menu-link">-->
                        <!--        <span class="nk-menu-text">Cash in Hand</span>-->
                        <!--    </a>-->
                        <!--</li>-->

                        <li class="nk-menu-item">
                            <a href="{{url('customer-voucher-list')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Customer Vouchers </span>
                            </a>
                        </li>

                           <li class="nk-menu-item">
                            <a href="{{url('tutor-voucher-list')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Tutor Vouchers </span>
                            </a>
                        </li>

{{--                        <li class="nk-menu-item has-sub">--}}
{{--                            <a href="#" class="nk-menu-link nk-menu-toggle">--}}
{{--                                <span class="nk-menu-text">Customer Vouchers </span>--}}
{{--                            </a>--}}
{{--                            <ul class="nk-menu-sub">--}}
{{--                                <li class="nk-menu-item">--}}
{{--                                    <a href="{{url('customer-voucher-list')}}" class="nk-menu-link">--}}
{{--                                        <span class="nk-menu-text">Customer Voucher List</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="nk-menu-item">--}}
{{--                                    <a href="{{url('customer-voucher')}}" class="nk-menu-link">--}}
{{--                                        <span class="nk-menu-text">Create Voucher</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                {{--                          <li class="nk-menu-item">--}}
                                {{--                            <a href="{{url('customer-payment-voucher')}}" class="nk-menu-link">--}}
                                {{--                              <span class="nk-menu-text">Payment Voucher</span>--}}
                                {{--                            </a>--}}
                                {{--                          </li>--}}
{{--                            </ul>--}}
{{--                        </li>--}}
                        <!--<li class="nk-menu-item has-sub">-->
                        <!--    <a href="#" class="nk-menu-link nk-menu-toggle">-->
                        <!--        <span class="nk-menu-text">Tutor Vouchers </span>-->
                        <!--    </a>-->
                        <!--    <ul class="nk-menu-sub">-->
                        <!--        <li class="nk-menu-item">-->
                        <!--            <a href="{{url('tutor-voucher-list')}}" class="nk-menu-link">-->
                        <!--                <span class="nk-menu-text">Tutor Voucher List</span>-->
                        <!--            </a>-->
                        <!--        </li>-->
                        <!--        <li class="nk-menu-item">-->
                        <!--            <a href="{{url('tutor-receive-voucher')}}" class="nk-menu-link">-->
                        <!--                <span class="nk-menu-text">Receive Voucher</span>-->
                        <!--            </a>-->
                        <!--        </li>-->
                        <!--        <li class="nk-menu-item">-->
                        <!--            <a href="{{url('tutor-payment-voucher')}}" class="nk-menu-link">-->
                        <!--                <span class="nk-menu-text">Payment Voucher</span>-->
                        <!--            </a>-->
                        <!--        </li>-->
                        <!--    </ul>-->
                        <!--</li>-->


                        <li class="nk-menu-item">
                            <a href="{{url('expense-voucher-list')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Expense Vouchers </span>
                            </a>
                        </li>

                         <li class="nk-menu-item">
                            <a href="{{url('bank-voucher-list')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Bank Vouchers </span>
                            </a>
                        </li>

{{--                        <li class="nk-menu-item has-sub">--}}
{{--                            <a href="#" class="nk-menu-link nk-menu-toggle">--}}
{{--                                <span class="nk-menu-text">Expense Vouchers </span>--}}
{{--                            </a>--}}
{{--                            <ul class="nk-menu-sub">--}}
{{--                                <li class="nk-menu-item">--}}
{{--                                    <a href="{{url('expense-voucher-list')}}" class="nk-menu-link">--}}
{{--                                        <span class="nk-menu-text">Expense Voucher List</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="nk-menu-item">--}}
{{--                                    <a href="{{url('expense-receive-voucher')}}" class="nk-menu-link">--}}
{{--                                        <span class="nk-menu-text">Expense Receive Voucher</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="nk-menu-item">--}}
{{--                                    <a href="{{url('expense-payment-voucher')}}" class="nk-menu-link">--}}
{{--                                        <span class="nk-menu-text">Expense Payment Voucher</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </li>--}}

                        <!--<li class="nk-menu-item has-sub">-->
                        <!--    <a href="#" class="nk-menu-link nk-menu-toggle">-->
                        <!--        <span class="nk-menu-text">Bank Vouchers </span>-->
                        <!--    </a>-->
                        <!--    <ul class="nk-menu-sub">-->
                        <!--        <li class="nk-menu-item">-->
                        <!--            <a href="{{url('bank-voucher-list')}}" class="nk-menu-link">-->
                        <!--                <span class="nk-menu-text">Bank Voucher List</span>-->
                        <!--            </a>-->
                        <!--        </li>-->
                        <!--        <li class="nk-menu-item">-->
                        <!--            <a href="{{url('bank-receive-voucher')}}" class="nk-menu-link">-->
                        <!--                <span class="nk-menu-text">Bank Receive Voucher</span>-->
                        <!--            </a>-->
                        <!--        </li>-->
                        <!--        <li class="nk-menu-item">-->
                        <!--            <a href="{{url('bank-payment-voucher')}}" class="nk-menu-link">-->
                        <!--                <span class="nk-menu-text">Bank Payment Voucher</span>-->
                        <!--            </a>-->
                        <!--        </li>-->
                        <!--    </ul>-->
                        <!--</li>-->


                    </ul>
                </li>

                <!--- FINANCE PAGES --->
                <!--- CASH FLOW -->
                <li class="nk-menu-heading">
                    <h6 class="overline-title">Cash Flow</h6>
                </li>

                <li class="nk-menu-item">
                    <a href="{{url('financialReport/customer_statement')}}" class="nk-menu-link">
                    <span class="nk-menu-icon">
                      <em class="icon ni ni-pie"></em>
                    </span>
                        <span class="nk-menu-text">Customer Ledger</span>
                    </a>
                </li>

                <li class="nk-menu-item">
                    <a href="{{url('journalLedger')}}" class="nk-menu-link">
                    <span class="nk-menu-icon">
                      <em class="icon ni ni-pie"></em>
                    </span>
                        <span class="nk-menu-text">Journal Ledger</span>
                    </a>
                </li>
{{--                <li class="nk-menu-item">--}}
{{--                    <a href="{{route('expenditures')}}" class="nk-menu-link">--}}
{{--                    <span class="nk-menu-icon">--}}
{{--                      <em class="icon ni ni-pie"></em>--}}
{{--                    </span>--}}
{{--                        <span class="nk-menu-text">Expenditures</span>--}}
{{--                    </a>--}}
{{--                </li>--}}

                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon">
                      <em class="icon ni ni-users"></em>
                    </span>
                        <span class="nk-menu-text">Sale Invoices</span>
                    </a>
                    <ul class="nk-menu-sub">
                        <li class="nk-menu-item">
                            <a href="{{route('saleInvoice')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Invoices</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{route('paymentList')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Payments</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon">
                      <em class="icon ni ni-users"></em>
                    </span>
                        <span class="nk-menu-text">Creditors</span>
                    </a>
                    <ul class="nk-menu-sub">
                        <li class="nk-menu-item">
                            <a href="{{route('CreditorInvoices')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Creditor Invoices</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{route('CreditorpaymentList')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Creditor Payments</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <!--- CASH FLOW -->

                <!--- My Payment -->
                <li class="nk-menu-heading">
                    <h6 class="overline-title">My Payment</h6>
                </li>
                <li class="nk-menu-item">
                    <a href="{{route('paymentHistory')}}" class="nk-menu-link">
                        <span class="nk-menu-text">Payment History</span>
                    </a>
                </li>
                <!--- My Payment -->

                <!--- Others -->
                <li class="nk-menu-heading">
                    <h6 class="overline-title">Others</h6>
                </li>

                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon">
                      <em class="icon ni ni-users"></em>
                    </span>
                        <span class="nk-menu-text">Operation Report</span>
                    </a>
                    <ul class="nk-menu-sub">
                        <li class="nk-menu-item">
                            <a href="{{route('dailyTicketApplication')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Daily Ticket Application</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{route('monthlyInvoiceChargeStatus')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Monthly Invoice Charge Status</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{route('monthlyProductVsComission')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Monthly Product Vs Commission</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- Analytics -->
                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-users"></em>
                      </span>
                        <span class="nk-menu-text">Analytics</span>
                    </a>
                    <ul class="nk-menu-sub">
                        <li class="nk-menu-item">
                            <a href="{{route('analytics/overview')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Overview</span>
                            </a>
                        </li>
                        @can("analytics-tutor-subject")
                            <li class="nk-menu-item">
                                <a href="{{route('analytics/tutorVsSubject')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Tutor Vs Subject</span>
                                </a>
                            </li>
                        @endcan
                        
                        <li class="nk-menu-item">
                                <a href="{{route('getTutorDropOutRate')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Tutor Dropout Rate</span>
                                </a>
                            </li>


                        @can("analytics-subject-subject")
                            <li class="nk-menu-item">
                                <a href="{{route('analytics/studentVsSubject')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Student Vs Subject</span>
                                </a>
                            </li>
                        @endcan


                        @can("analytics-customer-subject")
                            <li class="nk-menu-item">
                                <a href="{{route('analytics/customerVsSubject')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Customer Vs Subject</span>
                                </a>
                            </li>
                        @endcan



                        @can("analytics-total-class")
                            <li class="nk-menu-item">
                                <a href="{{route('analytics/classesByWeekday')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Classes By Weekday / Weekend</span>
                                </a>
                            </li>
                        @endcan


                        <li class="nk-menu-item">
                            <a href="{{route('analytics/ticketStatus')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Ticket Status</span>
                            </a>
                        </li>


                        @can("analytics-student-invoice")
                            <li class="nk-menu-item">
                                <a href="{{route('analytics/studentInvoices')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Student Invoices</span>
                                </a>
                            </li>
                        @endcan



                        @can("analytics-sale-performance")
                            <li class="nk-menu-item">
                                <a href="{{route('analytics/picSalesPerformance')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">PIC Sales Performance</span>
                                </a>
                            </li>
                        @endcan



                        @can("analytics-platform-usage")
                            <li class="nk-menu-item">
                                <a href="{{route('analytics/platformUsage')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Platform Usage</span>
                                </a>
                            </li>
                        @endcan

                        <li class="nk-menu-item">
                            <a href="{{url('analytics/tutorsuccessreport')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Tutor Success Report</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <!--Analytics -->

                <!-- Financial Report start -->


                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon">
                      <em class="icon ni ni-users"></em>
                    </span>
                        <span class="nk-menu-text">Financial Report</span>
                    </a>
                    <ul class="nk-menu-sub">
                        <li class="nk-menu-item">
                            <a href="{{route('financialReport/cashFlow')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Cash Flow</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{route('financialReport/balanceSheet')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Balance Sheet</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{route('financialReport/trialBalance')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Trial Balance</span>
                            </a>
                        </li>


                        <li class="nk-menu-item">
                            <a href="{{route('financialReport/incomeStatement')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Income Statement</span>
                            </a>
                        </li>


                        <li class="nk-menu-item">
                            <a href="{{route('financialReport/incomeByProduct')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Income By Product</span>
                            </a>
                        </li>


                        <li class="nk-menu-item">
                            <a href="{{route('reportGeneralLedger')}}" class="nk-menu-link">
                                <span class="nk-menu-text">General Ledger</span>
                            </a>
                        </li>

                    </ul>
                </li>


                <!-- Financial Report Ends -->


                <!-- System Log -->

                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-users"></em>
                      </span>
                        <span class="nk-menu-text">System Logs</span>
                    </a>
                    <ul class="nk-menu-sub">
                        @can("system-user-activities")
                            <li class="nk-menu-item">
                                <a href="{{route('userActivities')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">User Activities</span>
                                </a>
                            </li>
                        @endcan

                        @can("system-text-messages")
                            <li class="nk-menu-item">
                                <a href="{{route('textMessages')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Text Messages</span>
                                </a>
                            </li>
                        @endcan


                    </ul>
                </li>

                <!-- System Log end -->

                <!-- Setting  -->

                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-users"></em>
                      </span>
                        <span class="nk-menu-text">Settings</span>
                    </a>
                    <ul class="nk-menu-sub">
                        @can("user-list")
                            <li class="nk-menu-item">
                                <a href="{{route('users')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Users</span>
                                </a>
                            </li>
                        @endcan

                        @can("user-role-list")
                            <li class="nk-menu-item">
                                <a href="{{route('userRoles')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">User Roles</span>
                                </a>
                            </li>
                        @endcan


                        <li class="nk-menu-item">
                            <a href="{{route('StudentPicCommissions')}}" class="nk-menu-link">
                                <span class="nk-menu-text">PIC Commissions</span>
                            </a>


                        @can("student-pic-bonus-list")
                            <li class="nk-menu-item">
                                <a href="{{route('StudentPicBonuses')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">PIC Bonus</span>
                                </a>
                            </li>
                        @endcan


                        @can("tutor-bonus-list")
                            <li class="nk-menu-item">
                                <a href="{{route('tutorBonus')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Tutor Bonus</span>
                                </a>
                            </li>
                        @endcan


                        <!--<li class="nk-menu-item">-->
                        <!--    <a href="{{route('appleRedemptionCode')}}" class="nk-menu-link">-->
                        <!--        <span class="nk-menu-text">Apple Redemption Code</span>-->
                        <!--    </a>-->
                        <!--</li>-->

                        <li class="nk-menu-item">
                            <a href="{{route('system')}}" class="nk-menu-link">
                                <span class="nk-menu-text">System</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{route('StateCities')}}" class="nk-menu-link">
                                <span class="nk-menu-text">State City</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{route('accountInformation')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Account Information</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="{{route('MessageTemplates')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Message Templates</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- Setting End  -->

                <!-- Billin Start  -->

                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-users"></em>
                      </span>
                        <span class="nk-menu-text">Billing</span>
                    </a>
                    <ul class="nk-menu-sub">
                        <li class="nk-menu-item">
                            <a href="{{url('billing/invoices')}}" class="nk-menu-link">
                                <span class="nk-menu-text">Invoices</span>
                            </a>
                        </li>


                    </ul>
                </li>


            </ul>
        </div>
    </div>
</div>
