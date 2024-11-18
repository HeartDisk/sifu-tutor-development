<?php

namespace App\Helper;

class HelperComponent
{


    public static function SideBar()
    {
        $prefix = "portal";
        return [
            [
                "heading" => "Analytics",
                "heading_can" => "analytics-heading-view",
            ],

            [

                "title" => 'Dashboard',
                'can' => 'dashboard-dropdown',
                "icon" => "ti-smart-home",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Overview",
                        null,
                        "dashboard-overview-view",
                        "/dashboard/overview",
                    ),
                ],
            ],

            [
                "heading" => "Management",
                "heading_can" => "management-heading-view",

            ],
            [

                "title" => 'User Management',
                'can' => 'user-management-dropdown',
                "icon" => "ti-users",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Users",
                        null,
                        "users-list-view",
                        "/users",
                    ),
                    // setSubMenu(
                    //     "Clients",
                    //     null,
                    //     "clients-list-view",
                    //     "/clients",
                    // ),
                    setSubMenu(
                        "Roles",
                        null,
                        "roles-list-view",
                        "/roles",
                    ),
                    setSubMenu(
                        "Permissions",
                        null,
                        "permissions-list-view",
                        "/permissions",
                    ),

                ]

            ],
            setSingleLink("Class Schedules", "ti ti-calendar", "class-schedule-view", "/class-schedules"),
            [
                "title" => 'Students',
                'can' => 'student-dropdown',
                "icon" => "ti-school",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Customer List",
                        null,
                        "customer-list-view",
                        "/students/customer-list",
                    ),
                    setSubMenu(
                        "Student List",
                        null,
                        "student-list-view",
                        "/students/student-list",
                    ),
                    setSubMenu(
                        "Student Assignments",
                        null,
                        "student-assignments-view",
                        "/students/student-assignments",
                    ),
                    // setSubMenu(
                    //     "Subject",
                    //     null,
                    //     "subject-list-view",
                    //     "/subject/subject-list",
                    // ),
                ]

            ],
            [
                "title" => 'Student Invoices',
                'can' => 'students-invoices-dropdown',
                "icon" => "ti-file-invoice",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Invoice",
                        null,
                        "invocie-list-view",
                        "/student-invoices/invoice",
                    ),
                    setSubMenu(
                        "Payments",
                        null,
                        "payment-list-view",
                        "/student-invoices/payment",
                    ),
                ]

            ],
            [
                "title" => 'Tutors',
                'can' => 'tutor-dropdown',
                "icon" => "ti-user-plus",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Tutor List",
                        null,
                        "tutor-list-view",
                        "/tutor/tutor-list",
                    ),
                    // setSubMenu(
                    //     "Schedule Calander",
                    //     null,
                    //     "schedule-calander-view",
                    //     "/tutor/schedule-calander",
                    // ),
                    setSubMenu(
                        "Reports",
                        null,
                        "tutor-reports-list",
                        "/tutor/tutor-reports",
                    ),
                    setSubMenu(
                        "Reports V2",
                        null,
                        "reports-v2-view",
                        "/tutor/reports-v2",
                    ),
                    setSubMenu(
                        "Tutor Assigments",
                        null,
                        "tutor-assigment-view",
                        "/tutor/tutor-assigment",
                    ),
                    setSubMenu(
                        "Tutor Payments",
                        null,
                        "tutor-payment-view",
                        "/tutor/tutor-payment",
                    ),
                    setSubMenu(
                        "Tutor Payment Journal",
                        null,
                        "tutor-payment-journal-view",
                        "/tutor/tutor-payment-journal",
                    ),
                    setSubMenu(
                        "Tutor Finder",
                        null,
                        "tutor-finder-view",
                        "/tutor/tutor-finder",
                    ),
                ]

            ],
            [
                "title" => 'Staffs',
                'can' => 'staff-dropdown',
                "icon" => "ti-users",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Staff List",
                        null,
                        "staff-list-view",
                        "/staff/staff-list",
                    ),
                    setSubMenu(
                        "Staff Payments",
                        null,
                        "staff-paymentt-view",
                        "/staff/staff-payment-list",
                    ),
                    setSubMenu(
                        "PIC Commission",
                        null,
                        "pic-commission-view",
                        "/staff/pic-commission-list",
                    ),
                ]

            ],
            [
                "title" => 'Products',
                'can' => 'product-dropdown',
                "icon" => "ti-package",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Product List",
                        null,
                        "product-list-view",
                        "/products/product-list",
                    ),
                ]

            ],
            [
                "title" => 'Job Tickets',
                'can' => 'job-tickets-dropdown',
                "icon" => "ti-ticket",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Ticket List",
                        null,
                        "ticket-list-view",
                        "/job-ticket/ticket-list",
                    ),
                    setSubMenu(
                        "Tutor Application",
                        null,
                        "tutor-application-list-view",
                        "/job-ticket/tutor-application",
                    ),
                ]

            ],
            setSingleLink("Chart of Accounts", "ti-file-analytics", "chart-account-view", "/chart-account"),
            [
                "heading" => "Cash Flow",
                "heading_can" => "cash-flow-view",

            ],
            setSingleLink("Journal Ledger", "ti-books", "journal-ledger-view", "/cash-flow/journal-ledger"),
            setSingleLink("Expenditures", "ti-database", "expenditures-view", "/cash-flow/expenditures"),
            [
                "title" => 'Sales Invoices',
                'can' => 'sales-invoice-dropdown',
                "icon" => "ti-file-invoice",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Invoices",
                        null,
                        "sale-invoices-view",
                        "/sales-invoice/invoice",
                    ),
                    setSubMenu(
                        "Payments",
                        null,
                        "sales-payments-view",
                        "/sales-invoice/payments",
                    ),
                ]

            ],
            [
                "title" => 'Creditors',
                'can' => 'creditors-invoice-dropdown',
                "icon" => "ti-timeline",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Creditor Invoice",
                        null,
                        "creditor-invoices-view",
                        "/creditor/invoice",
                    ),
                    setSubMenu(
                        "Creditor Payment",
                        null,
                        "creditor-payments-view",
                        "/creditor/payments",
                    ),
                ]

            ],
            [
                "heading" => "Others",
                "heading_can" => "others-heading-view",
            ],
            [
                "title" => 'Operation Reports',
                'can' => 'operation-reports-dropdown',
                "icon" => "ti-vocabulary",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Daily Ticket Application",
                        null,
                        "daily-ticket-application-view",
                        "/operation-report/daily-ticket-application",
                    ),
                    setSubMenu(
                        "Monthly Invoice Charge Status",
                        null,
                        "monthly-invoice-charge-status-view",
                        "/operation-report/monthly-invoice-charge-status",
                    ),
                    setSubMenu(
                        "Monthly NakNgaji Product Vs Commission",
                        null,
                        "monthly-nakngji-product-vs-commission-view",
                        "/operation-report/monthly-nakngji-product-vs-commission",
                    ),
                ]

            ],
            [
                "title" => 'Analytics',
                'can' => 'analytics-dropdown',
                "icon" => "ti-vocabulary",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Overview",
                        null,
                        "overview-view",
                        "/analytics/overview",
                    ),
                    setSubMenu(
                        "Tutor Vs Subject",
                        null,
                        "tutor-vs-subject-view",
                        "/analytics/tutor-vs-subject",
                    ),
                    setSubMenu(
                        "Student Vs Subject",
                        null,
                        "student-vs-subject-view",
                        "/analytics/student-vs-subject",
                    ),
                    setSubMenu(
                        "Customer Vs Subject",
                        null,
                        "customer-vs-subject-view",
                        "/analytics/customer-vs-subject",
                    ),
                    setSubMenu(
                        "Classes By Weekday/Weekend",
                        null,
                        "classes-by-day-type-view",
                        "/analytics/classes-by-day-type",
                    ),
                    setSubMenu(
                        "Ticket Status",
                        null,
                        "ticket-status-view",
                        "/analytics/ticket-status",
                    ),
                    setSubMenu(
                        "Student Invoices",
                        null,
                        "student-invoices-view",
                        "/analytics/student-invoices",
                    ),
                    setSubMenu(
                        "Pic Sales Performance",
                        null,
                        "pic-sales-performance-view",
                        "/analytics/pic-sales-performance",
                    ),
                    setSubMenu(
                        "Platform Usage",
                        null,
                        "platform-usage-view",
                        "/analytics/platform-usage",
                    ),
                ]

            ],
            [
                "title" => 'Financial Reports',
                'can' => 'financial-reports-dropdown',
                "icon" => "ti-vocabulary",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Cash Flow",
                        null,
                        "cash-flow-view",
                        "/financial-reports/cash-flow",
                    ),
                    setSubMenu(
                        "Balance Sheet",
                        null,
                        "balance-sheet-view",
                        "/financial-reports/balance-sheet",
                    ),
                    setSubMenu(
                        "Trial Balance",
                        null,
                        "trial-balance-view",
                        "/financial-reports/trial-balance",
                    ),
                    setSubMenu(
                        "Income Statement",
                        null,
                        "income-statement-view",
                        "/financial-reports/income-statement",
                    ),
                    setSubMenu(
                        "Income By Product",
                        null,
                        "income-by-product-view",
                        "/financial-reports/income-by-product",
                    ),
                    setSubMenu(
                        "General Ledger",
                        null,
                        "general-ledger-view",
                        "/financial-reports/general-ledger",
                    ),
                ]

            ],
            [
                "title" => 'Billing',
                'can' => 'billing-dropdown',
                "icon" => "ti-receipt",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Invoices",
                        null,
                        "billing-invoice-view",
                        "/billing/invoice",
                    ),
                ]

            ],
            [
                "heading" => "Tools",
                "heading_can" => "tools-heading-view",
            ],
            [
                "title" => 'Settings',
                'can' => 'setting-dropdown',
                "icon" => "ti-settings",
                'type' => 'multi',
                "sub_menu" => [
                    setSubMenu(
                        "Profile",
                        null,
                        "profile-view",
                        "/settings",
                    ),
                    setSubMenu(
                        "Change Password",
                        null,
                        "changepassword-view",
                        "/settings/change-password",
                    ),
                ]

            ],
        ];
    }
}
