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

                <li class="nk-menu-heading">
                    <h6 class="overline-title">Management</h6>
                </li>

                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                      <span class="nk-menu-icon">
                        <em class="icon ni ni-users"></em>
                      </span>
                        <span class="nk-menu-text">Tutors</span>
                    </a>
                    <ul class="nk-menu-sub">

                        @can("tutor-payment-list")
                            <li class="nk-menu-item">
                                <a href="{{route('TutorPayments')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Tutor Payments</span>
                                </a>
                            </li>
                        @endcan
                        
                         @can("tutor-list")
                            <li class="nk-menu-item">
                                <a href="{{route('TutorList')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Tutor List</span>
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
                      
                         @can("tutor-payment-journal")
                            <li class="nk-menu-item">
                                <a href="{{route('TutorPaymentJournal')}}" class="nk-menu-link">
                                    <span class="nk-menu-text">Tutor Payment Journal</span>
                                </a>
                            </li>
                        @endcan
                

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

                    </ul>
                </li>

                <!--- FINANCE PAGES --->
                <!--- CASH FLOW -->
                <li class="nk-menu-heading">
                    <h6 class="overline-title">Cash Flow</h6>
                </li>
                
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

                <!--<li class="nk-menu-item">-->
                <!--    <a href="{{url('financialReport/customer_statement')}}" class="nk-menu-link">-->
                <!--    <span class="nk-menu-icon">-->
                <!--      <em class="icon ni ni-pie"></em>-->
                <!--    </span>-->
                <!--        <span class="nk-menu-text">Customer Ledger</span>-->
                <!--    </a>-->
                <!--</li>-->

                <li class="nk-menu-item">
                    <a href="{{url('journalLedger')}}" class="nk-menu-link">
                    <span class="nk-menu-icon">
                      <em class="icon ni ni-pie"></em>
                    </span>
                        <span class="nk-menu-text">Journal Ledger</span>
                    </a>
                </li>

                <!--<li class="nk-menu-item has-sub">-->
                <!--    <a href="#" class="nk-menu-link nk-menu-toggle">-->
                <!--    <span class="nk-menu-icon">-->
                <!--      <em class="icon ni ni-users"></em>-->
                <!--    </span>-->
                <!--        <span class="nk-menu-text">Creditors</span>-->
                <!--    </a>-->
                <!--    <ul class="nk-menu-sub">-->
                <!--        <li class="nk-menu-item">-->
                <!--            <a href="{{route('CreditorInvoices')}}" class="nk-menu-link">-->
                <!--                <span class="nk-menu-text">Creditor Invoices</span>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--        <li class="nk-menu-item">-->
                <!--            <a href="{{route('CreditorpaymentList')}}" class="nk-menu-link">-->
                <!--                <span class="nk-menu-text">Creditor Payments</span>-->
                <!--            </a>-->
                <!--        </li>-->

                <!--    </ul>-->
                <!--</li>-->

            
                <li class="nk-menu-heading">
                    <h6 class="overline-title">Others</h6>
                </li>


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
            </ul>
        </div>
    </div>
</div>
