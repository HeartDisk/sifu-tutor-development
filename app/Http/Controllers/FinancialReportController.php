<?php

namespace App\Http\Controllers;

use App\Models\CustomerCommitmentFee;
use App\Models\Tutorpayment;
use App\Models\TutorCommitmentFee;
use App\Models\LedgerItem;
use App\Models\Ledger;
use App\Models\Account;
use App\Models\ChartOfAccount;
use App\Models\ChartOfAccountCategory;
use App\Models\CustomerVoucher;
use App\Models\CustomerVoucherItem;
use App\Models\ExpenseVoucher;
use App\Models\ExpenseVoucherItem;
use App\Models\Tutor;
use App\Models\TutorVoucher;
use App\Models\TutorVoucherItem;
use App\Models\BankVoucherItem;
use App\Models\BankVoucher;
use App\Models\Invoice;
use Illuminate\Http\Request;
use DB;
use Auth;
use DateTime;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


        public function getIncomeStatement()
    {
        $invoices_sum = Invoice::where("status", "paid")->sum("invoiceTotal");

        $customer_commitment_fee = CustomerCommitmentFee::sum("payment_amount");

        $tutor_commitment_fee = TutorCommitmentFee::sum("payment_amount");

        $tutor_payments = Tutorpayment::sum("payAmount");

        $operating_expense = LedgerItem::where("account_code", 2100)->sum("debit");

        $provisional_expense = LedgerItem::where("account_code", 2400)->sum("debit");

        $staff_cost = LedgerItem::where("account_code", 2200)->sum("debit");

        $finance_cost = LedgerItem::where("account_code", 2300)->sum("debit");

        $sales_sum = $invoices_sum + $customer_commitment_fee + $tutor_commitment_fee;

        $gross_profit = $sales_sum - $tutor_payments;

        $profit = $gross_profit - $operating_expense - $provisional_expense - $staff_cost - $finance_cost;

        return $profit;
    }

   public function cashFlow()
    {
        $profit_before_taxation = $this->getIncomeStatement();
        $depreciation_property_pant_equiment = LedgerItem::where("account_code", 2400)->sum("debit");
        $interest_expense = LedgerItem::where("account_code", 2300)->sum("debit");
        $operating_profit_before_working_capital = $depreciation_property_pant_equiment + $interest_expense;

        //changes in working capital
        $other_receivable_code = ChartOfAccount::where("name", "Other receivables and deposits")->first();
        $other_receivable = LedgerItem::where("account_code", $other_receivable_code->code)
            ->where("account_id", $other_receivable_code->id)
            ->sum("debit");

        $amount_due_director_code = ChartOfAccount::where("name", "Amount due from director")->first();
       $amount_due_director = LedgerItem::selectRaw(
    '(SUM(CASE WHEN YEAR(created_at) = ? THEN debit ELSE 0 END) - SUM(CASE WHEN YEAR(created_at) = ? THEN debit ELSE 0 END)) AS difference',
    [now()->year, now()->year - 1]
)->where("account_code", function($query) {
    $query->select('code')
        ->from('chart_accounts')
        ->where('name', 'Amount due from director')
        ->limit(1);
})
->where("account_id", function($query) {
    $query->select('id')
        ->from('chart_accounts')
        ->where('name', 'Amount due from director')
        ->limit(1);
})
->value('difference');

        $amount_due_company_code = ChartOfAccount::where("name", "Amount due from related company")->first();
       $amount_due_company = LedgerItem::selectRaw(
    '(SUM(CASE WHEN YEAR(created_at) = ? THEN debit ELSE 0 END) - SUM(CASE WHEN YEAR(created_at) = ? THEN debit ELSE 0 END)) AS difference',
    [now()->year, now()->year - 1]
)->where("account_code", function($query) {
    $query->select('code')
        ->from('chart_accounts')
        ->where('name', 'Amount due from company')
        ->limit(1);
})
->where("account_id", function($query) {
    $query->select('id')
        ->from('chart_accounts')
        ->where('name', 'Amount due from company')
        ->limit(1);
})
->value('difference');

        $other_payable_code = ChartOfAccount::where("name", "Other payables and accruals")->first();
       $other_payable = LedgerItem::selectRaw(
    '(SUM(CASE WHEN YEAR(created_at) = ? THEN debit ELSE 0 END) - SUM(CASE WHEN YEAR(created_at) = ? THEN debit ELSE 0 END)) AS difference',
    [now()->year, now()->year - 1]
)->where("account_code", function($query) {
    $query->select('code')
        ->from('chart_accounts')
        ->where('name', 'Other payable')
        ->limit(1);
})
->where("account_id", function($query) {
    $query->select('id')
        ->from('chart_accounts')
        ->where('name', 'Other payable')
        ->limit(1);
})
->value('difference');

        $amount_due_to_director_code = ChartOfAccount::where("name", "Amount due to directors")->first();
        $amount_due_to_director = LedgerItem::where("account_code", $amount_due_to_director_code->code)
            ->where("account_id", $amount_due_director_code->id)
            ->sum("debit");

        $amount_due_to_company_code = ChartOfAccount::where("name", "Amount due to related company")->first();
        $amount_due_to_company = LedgerItem::selectRaw(
    '(SUM(CASE WHEN YEAR(created_at) = ? THEN debit ELSE 0 END) - SUM(CASE WHEN YEAR(created_at) = ? THEN debit ELSE 0 END)) AS difference',
    [now()->year, now()->year - 1]
)->where("account_code", function($query) use ($amount_due_to_company_code) {
    $query->select('code')
        ->from('chart_accounts')
        ->where('name', 'Amount due to company')
        ->limit(1);
})
->where("account_id", function($query) use ($amount_due_to_company_code) {
    $query->select('id')
        ->from('chart_accounts')
        ->where('name', 'Amount due to company')
        ->limit(1);
})
->value('difference');

        //A
        $net_cash_used_in_operation_activities = $other_receivable + $amount_due_director +
            $other_payable + $amount_due_to_director + $amount_due_to_company +
            $amount_due_company;


        //cash flows from investing activities
        //B
        $property_plant_equipment_code = ChartOfAccount::where("name", "Property, plant and equipment")->first();
        $property_plant_equipment = LedgerItem::where("account_code", $property_plant_equipment_code->code)
            ->where("account_id", $property_plant_equipment_code->id)
            ->sum("debit");

        $net_cash_used_in_investing_activities=$property_plant_equipment;


        //CASH FLOW FROM FINANCING ACTIVITIES
        $share_capital_code = ChartOfAccount::where("name", "Property, plant and equipment")->first();
        $share_capital = LedgerItem::where("account_code", $share_capital_code->code)
            ->where("account_id", $share_capital_code->id)
            ->sum("debit");

        $bank_borrowings_code = ChartOfAccount::where("name", "Property, plant and equipment")->first();
        $bank_borrowings = LedgerItem::where("account_code", $bank_borrowings_code->code)
            ->where("account_id", $bank_borrowings_code->id)
            ->sum("debit");

        $finance_lease_code = ChartOfAccount::where("name", "Property, plant and equipment")->first();
        $finance_lease = LedgerItem::where("account_code", $finance_lease_code->code)
            ->where("account_id", $finance_lease_code->id)
            ->sum("debit");

        //C
        $net_cash_from_financing_activities=$share_capital+$bank_borrowings+$finance_lease;

        $net_increase_in_cash=($net_cash_used_in_operation_activities-$net_cash_used_in_investing_activities)+$net_cash_from_financing_activities;


       //Cash and cash equivalents brough forward
        $cash_and_cash_brough_forward_code = ChartOfAccount::where("name", "Cash and cash equivalents")->first();
        $cash_and_cash_brough_forward = LedgerItem::where("account_code", $cash_and_cash_brough_forward_code->code)
            ->where("account_id", $cash_and_cash_brough_forward_code->id)
            ->sum("debit");

       //Cash and cash equivalents carried forward
        $cash_and_cash_carried_forward_code = ChartOfAccount::where("name", "Cash and cash equivalents")->first();
        $cash_and_cash_carried_forward = LedgerItem::where("account_code", $cash_and_cash_carried_forward_code->code)
            ->where("account_id", $cash_and_cash_carried_forward_code->id)
            ->sum("debit");


        return view('financialReport/cashFlow',compact('profit_before_taxation','depreciation_property_pant_equiment','interest_expense','operating_profit_before_working_capital',
        
        'net_cash_from_financing_activities','net_increase_in_cash','cash_and_cash_brough_forward','cash_and_cash_carried_forward',
        
        'net_cash_used_in_operation_activities','property_plant_equipment','net_cash_used_in_investing_activities','share_capital','bank_borrowings','finance_lease',
        
        'amount_due_director','amount_due_company','other_payable','amount_due_to_director','amount_due_to_company'));
    }

    public function submitAccount(Request $request)
    {
        $data = $request->all();

        DB::table('chart_accounts')->insert([
            'name' => $data['name'],
            'type' => $data['type'],
            'code' => $data['account_no'],
            'category_id' => $data['category'],
            'sub_category_id' => $data['sub_category'],
            'description' => $data['note'],
            'initial_balance' => $data['initial_balance'],
        ]);


        return redirect('/financialReport/accounts')->with('message', 'Account created successfully');
    }

    public function edit_account($id)
    {
        $data = ChartOfAccount::find($id);
        $categories = ChartOfAccountCategory::all();

        return view("financialReport.edit_account", compact('data', 'categories'));
    }

    public function update_account(Request $request)
    {

        // dd($request->all());

        $data = $request->all();

        DB::table('chart_accounts')
            ->where('id', $request->account_id)
            ->update([
                'name' => $data['name'],
                'type' => $data['type'],
                'code' => $data['account_no'],
                'category_id' => $data['category'],
                'sub_category_id' => $data['sub_category'],
                'description' => $data['note'],
                'initial_balance' => $data['initial_balance'],
            ]);

        return redirect("/financialReport/accounts");
    }

    public function delete_account($id)
    {
        DB::table("chart_accounts")->where("id", $id)->delete();
        return redirect("/financialReport/accounts");

    }

    public function account_type($id)
    {
        $count_account_type = DB::table('accounts')->where('type', '=', $id)->count();
        echo json_encode($count_account_type + 1);
    }

    public function accounts()
    {
        $accountList = ChartOfAccount::with(['category', 'subCategory'])->get();
        $categories = ChartOfAccountCategory::all();

//        dd($accountList[0]->subCategory->name);
        return view('financialReport/accounts', compact('accountList', 'categories'));
    }

    public function balanceSheet(Request $request)
    {
        $year = $request->year;

        // Define the date range based on the selected year
        if ($year != 'all') {
            $startDate = Carbon::createFromDate($year)->startOfYear();
            $endDate = Carbon::createFromDate($year)->endOfYear();
        } else {
            $startDate = null; // Set a default value or handle accordingly
            $endDate = null; // Set a default value or handle accordingly
        }

        //non-current-assets
        $property_plant_equipment_code = ChartOfAccount::where("name", "Property, plant and equipment")->first();
        $property_plant_equipment = LedgerItem::where("account_code", $property_plant_equipment_code->code)
            ->where("account_id", $property_plant_equipment_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        //current-assets
        $other_receivable_code = ChartOfAccount::where("name", "Other receivables and deposits")->first();
        $other_receivable = LedgerItem::where("account_code", $other_receivable_code->code)
            ->where("account_id", $other_receivable_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        $mount_due_director_code = ChartOfAccount::where("name", "Amount due from director")->first();
        $mount_due_director = LedgerItem::where("account_code", $mount_due_director_code->code)
            ->where("account_id", $mount_due_director_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        $mount_due_company_code = ChartOfAccount::where("name", "Amount due from related company")->first();
        $mount_due_company = LedgerItem::where("account_code", $mount_due_company_code->code)
            ->where("account_id", $mount_due_company_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        $fixed_deposit_code = ChartOfAccount::where("name", "Fixed deposit")->first();
        $fixed_deposit = LedgerItem::where("account_code", $fixed_deposit_code->code)
            ->where("account_id", $fixed_deposit_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        $cash_equipments_code = ChartOfAccount::where("name", "Cash and cash equivalents")->first();
        $cash_equipments = LedgerItem::where("account_code", $cash_equipments_code->code)
            ->where("account_id", $cash_equipments_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        //EQUITY AND LIABILITIES
        $share_capital_code = ChartOfAccount::where("name", "Share capital")->first();
        $share_capital = LedgerItem::where("account_code", $share_capital_code->code)
            ->where("account_id", $share_capital_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        $retained_profits_code = ChartOfAccount::where("name", "Retained profits")->first();
        $retained_profits = LedgerItem::where("account_code", $retained_profits_code->code)
            ->where("account_id", $retained_profits_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        //non current liabilities
        $finance_lease_code = ChartOfAccount::where(["name" => "Finance lease", "code" => 5200])->first();
        $finance_lease = LedgerItem::where("account_code", $finance_lease_code->code)
            ->where("account_id", $finance_lease_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        $bank_borrowings_code = ChartOfAccount::where(["name" => "Bank borrowings", "code" => 5200])->first();
        $bank_borrowings = LedgerItem::where("account_code", $bank_borrowings_code->code)
            ->where("account_id", $bank_borrowings_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        $deferred_tax_liabilities_code = ChartOfAccount::where("name", "Deferred tax liabilities")->first();
        $deferred_tax_liabilities = LedgerItem::where("account_code", $deferred_tax_liabilities_code->code)
            ->where("account_id", $deferred_tax_liabilities_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        //CURRENT LIABILITIES
        $other_payables_and_accruals_code = ChartOfAccount::where("name", "Other payables and accruals")->first();
        $other_payables_and_accruals = LedgerItem::where("account_code", $other_payables_and_accruals_code->code)
            ->where("account_id", $other_payables_and_accruals_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        $financelease_current_libility_code = ChartOfAccount::where(["name" => "Finance lease", "code" => 5100])->first();
        $financelease_current_libility = LedgerItem::where("account_code", $financelease_current_libility_code->code)
            ->where("account_id", $financelease_current_libility_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        $bank_borrowings_current_libility_code = ChartOfAccount::where(["name" => "Bank borrowings", "code" => 5100])->first();
        $bank_borrowings_current_libility = LedgerItem::where("account_code", $bank_borrowings_current_libility_code->code)
            ->where("account_id", $bank_borrowings_current_libility_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        $mount_due_director_current_liability_code = ChartOfAccount::where("name", "Amount due of directors")->first();
        $mount_due_director_current_liabilitty = LedgerItem::where("account_code", $mount_due_director_current_liability_code->code)
            ->where("account_id", $mount_due_director_current_liability_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        $current_tax_liabilities_code = ChartOfAccount::where("name", "Current tax liabilities")->first();
        $current_tax_liabilities = LedgerItem::where("account_code", $current_tax_liabilities_code->code)
            ->where("account_id", $current_tax_liabilities_code->id)
            ->when($year != 'all', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->sum("debit");

        return view('financialReport/balanceSheet', compact(
            'property_plant_equipment', 'other_receivable', 'mount_due_director', 'mount_due_company',
            'fixed_deposit', 'cash_equipments', 'share_capital', 'retained_profits', 'finance_lease',
            'bank_borrowings', 'deferred_tax_liabilities', 'other_payables_and_accruals',
            'financelease_current_libility', 'bank_borrowings_current_libility',
            'mount_due_director_current_liabilitty', 'current_tax_liabilities'
        ));
    }

//    public function balanceSheet(Request $request)
//    {
//        $year = $request->year;
//
//        //non-current-assets
//        $property_plant_equipment_code = ChartOfAccount::where("name", "Property, plant and equipment")->first();
//        $property_plant_equipment = LedgerItem::where("account_code", $property_plant_equipment_code->code)->where("account_id", $property_plant_equipment_code->id)->sum("debit");
//        // dd($property_plant_equipment);
//
//        //current-assets
//        $other_receivable_code = ChartOfAccount::where("name", "Other receivables and deposits")->first();
//        $other_receivable = LedgerItem::where("account_code", $other_receivable_code->code)->where("account_id", $other_receivable_code->id)->sum("debit");
//
//        $mount_due_director_code = ChartOfAccount::where("name", "Amount due from director")->first();
//        $mount_due_director = LedgerItem::where("account_code", $mount_due_director_code->code)->where("account_id", $mount_due_director_code->id)->sum("debit");
//
//        $mount_due_company_code = ChartOfAccount::where("name", "Amount due from related company")->first();
//        $mount_due_company = LedgerItem::where("account_code", $mount_due_company_code->code)->where("account_id", $mount_due_company_code->id)->sum("debit");
//
//        $fixed_deposit_code = ChartOfAccount::where("name", "Fixed deposit")->first();
//        $fixed_deposit = LedgerItem::where("account_code", $fixed_deposit_code->code)->where("account_id", $fixed_deposit_code->id)->sum("debit");
//
//        $cash_equipments_code = ChartOfAccount::where("name", "Cash and cash equipments")->first();
//        $cash_equipments = LedgerItem::where("account_code", $cash_equipments_code->code)->where("account_id", $cash_equipments_code->id)->sum("debit");
//
//        //EQUITY AND LIABILITIES
//        $share_capital_code = ChartOfAccount::where("name", "Share capital")->first();
//        $share_capital = LedgerItem::where("account_code", $share_capital_code->code)->where("account_id", $share_capital_code->id)->sum("debit");
//
//
//        $retained_profits_code = ChartOfAccount::where("name", "Retained profits")->first();
//        $retained_profits = LedgerItem::where("account_code", $retained_profits_code->code)->where("account_id", $retained_profits_code->id)->sum("debit");
//
//        //non current libilities
//        $finance_lease_code = ChartOfAccount::where(["name" => "Finance lease", "code" => 5200])->first();
//        $finance_lease = LedgerItem::where("account_code", $finance_lease_code->code)->where("account_id", $finance_lease_code->id)->sum("debit");
//
//        $bank_borrowings_code = ChartOfAccount::where(["name" => "Bank borrowings", "code" => 5200])->first();
//        $bank_borrowings = LedgerItem::where("account_code", $bank_borrowings_code->code)->where("account_id", $bank_borrowings_code->id)->sum("debit");
//
//        $deferred_tax_liabilities_code = ChartOfAccount::where("name", "Deferred tax liabilities")->first();
//        $deferred_tax_liabilities = LedgerItem::where("account_code", $deferred_tax_liabilities_code->code)->where("account_id", $deferred_tax_liabilities_code->id)->sum("debit");
//
//        //CURRENT LIABILITIES
//        $other_payables_and_accruals_code = ChartOfAccount::where("name", "Other payables and accruals")->first();
//        $other_payables_and_accruals = LedgerItem::where("account_code", $other_payables_and_accruals_code->code)->where("account_id", $other_payables_and_accruals_code->id)->sum("debit");
//
//        $financelease_current_libility_code = ChartOfAccount::where(["name" => "Finance lease", "code" => 5100])->first();
//        $financelease_current_libility = LedgerItem::where("account_code", $financelease_current_libility_code->code)->where("account_id", $financelease_current_libility_code->id)->sum("debit");
//
//        $bank_borrowings_current_libility_code = ChartOfAccount::where(["name" => "Bank borrowings", "code" => 5100])->first();
//        $bank_borrowings_current_libility = LedgerItem::where("account_code", $bank_borrowings_current_libility_code->code)->where("account_id", $bank_borrowings_current_libility_code->id)->sum("debit");
//
//
//        $mount_due_director_current_liability_code = ChartOfAccount::where("name", "Amount due of directors")->first();
//        $mount_due_director_current_liabilitty = LedgerItem::where("account_code", $mount_due_director_current_liability_code->code)->where("account_id", $mount_due_director_current_liability_code->id)->sum("debit");
//
//
//        $current_tax_liabilities_code = ChartOfAccount::where("name", "Current tax liabilities")->first();
//        $current_tax_liabilities = LedgerItem::where("account_code", $current_tax_liabilities_code->code)->where("account_id", $current_tax_liabilities_code->id)->sum("debit");
//
//        return view('financialReport/balanceSheet',
//            compact('property_plant_equipment',
//                'other_receivable', 'mount_due_director',
//                'mount_due_company', 'fixed_deposit', 'cash_equipments',
//                'share_capital', 'retained_profits', 'finance_lease',
//                'bank_borrowings', 'deferred_tax_liabilities', 'other_payables_and_accruals',
//                'financelease_current_libility', 'bank_borrowings_current_libility',
//                'mount_due_director_current_liabilitty', 'current_tax_liabilities'
//            ));
//
//    }

    public function trialBalance(Request $request)
    {

        $year = $request->input('year', date('Y')); // Get the year from the request, default to current year if not present

    // Base query for LedgerItem
    $ledgerItemQuery = LedgerItem::query();

    // Apply year filter if not 'all'
    if ($year !== 'all') {
        $ledgerItemQuery->whereYear('created_at', $year);
    }

    // Calculate sums using cloned queries
    $assets_sum = (clone $ledgerItemQuery)->whereIn("account_code", [4100, 4200])->sum("debit");
    $expense_sum = (clone $ledgerItemQuery)->whereIn("account_code", [2100, 2200, 2300, 2400])->sum("debit");
    $capital_sum = (clone $ledgerItemQuery)->where("account_code", 3000)->sum("debit");
    $liability_sum = (clone $ledgerItemQuery)->whereIn("account_code", [5100, 5200])->sum("debit");

    // Separate queries for other models
    $invoiceQuery = Invoice::query();
    $customerCommitmentFeeQuery = CustomerCommitmentFee::query();
    $tutorCommitmentFeeQuery = TutorCommitmentFee::query();

    // Apply year filter to other models if not 'all'
    if ($year !== 'all') {
        $invoiceQuery->whereYear('created_at', $year);
        $customerCommitmentFeeQuery->whereYear('created_at', $year);
        $tutorCommitmentFeeQuery->whereYear('created_at', $year);
    }

    // Calculate sums for other models
    $invoices_sum = $invoiceQuery->where("status", "paid")->sum("invoiceTotal");
    $customer_commitment_fee = $customerCommitmentFeeQuery->sum("payment_amount");
    $tutor_commitment_fee = $tutorCommitmentFeeQuery->sum("payment_amount");

    $income_sum = $invoices_sum + $customer_commitment_fee + $tutor_commitment_fee;

    $debit_sum = $assets_sum + $expense_sum;
    $credit_sum = $capital_sum + $liability_sum + $income_sum;

    return view('financialReport/trialBalance', [
        "assets_sum" => $assets_sum,
        "expense_sum" => $expense_sum,
        "capital_sum" => $capital_sum,
        "liability_sum" => $liability_sum,
        "income_sum" => $income_sum,
        'debit_sum' => $debit_sum,
        'credit_sum' => $credit_sum
    ]);

    }

    public function incomeStatement(Request $request)
    {
        $month = $request->month;
        $year = $request->year;


        $invoicesQuery = Invoice::where("status", "paid");
        $customer_commitment_feeQuery = CustomerCommitmentFee::query();
        $tutor_commitment_feeQuery = TutorCommitmentFee::query();
        $tutor_paymentsQuery = Tutorpayment::query();
        $operating_expenseQuery = LedgerItem::where("account_code", 2100);
        $provisional_expenseQuery = LedgerItem::where("account_code", 2400);
        $staff_costQuery = LedgerItem::where("account_code", 2200);
        $finance_costQuery = LedgerItem::where("account_code", 2300);

        if ($month != "all") {
            //   dd("1");
            $invoicesQuery->whereMonth('created_at', $month);
            $customer_commitment_feeQuery->whereMonth('created_at', $month);
            $tutor_commitment_feeQuery->whereMonth('created_at', $month);
            $tutor_paymentsQuery->whereMonth('created_at', $month);
            $operating_expenseQuery->whereMonth('created_at', $month);
            $provisional_expenseQuery->whereMonth('created_at', $month);
            $staff_costQuery->whereMonth('created_at', $month);
            $finance_costQuery->whereMonth('created_at', $month);
        }

        if ($year != "all") {
            // dd("2");
            $invoicesQuery->whereYear('created_at', $year);
            $customer_commitment_feeQuery->whereYear('created_at', $year);
            $tutor_commitment_feeQuery->whereYear('created_at', $year);
            $tutor_paymentsQuery->whereYear('created_at', $year);
            $operating_expenseQuery->whereYear('created_at', $year);
            $provisional_expenseQuery->whereYear('created_at', $year);
            $staff_costQuery->whereYear('created_at', $year);
            $finance_costQuery->whereYear('created_at', $year);
        }

        $invoices_sum = $invoicesQuery->sum("invoiceTotal");
        $customer_commitment_fee = $customer_commitment_feeQuery->sum("payment_amount");
        $tutor_commitment_fee = $tutor_commitment_feeQuery->sum("payment_amount");
        $tutor_payments = $tutor_paymentsQuery->sum("payAmount");
        $operating_expense = $operating_expenseQuery->sum("debit");
        $provisional_expense = $provisional_expenseQuery->sum("debit");
        $staff_cost = $staff_costQuery->sum("debit");
        $finance_cost = $finance_costQuery->sum("debit");

        // $invoices_sum=Invoice::where("status","paid")->sum("invoiceTotal");
        // $customer_commitment_fee=CustomerCommitmentFee::sum("payment_amount");
        // $tutor_commitment_fee=TutorCommitmentFee::sum("payment_amount");

        $sales_sum = $invoices_sum + $customer_commitment_fee + $tutor_commitment_fee;

        // $tutor_payments=Tutorpayment::sum("payAmount");

        $gross_profit = $sales_sum - $tutor_payments;

        // $operating_expense = LedgerItem::where("account_code",2100)->sum("debit");
        // $provisional_expense = LedgerItem::where("account_code",2400)->sum("debit");
        // $staff_cost = LedgerItem::where("account_code",2200)->sum("debit");
        // $finance_cost = LedgerItem::where("account_code",2300)->sum("debit");

        $profit = $gross_profit - $operating_expense - $provisional_expense - $staff_cost - $finance_cost;

        return view('financialReport/incomeStatement', compact('invoices_sum',
            'customer_commitment_fee', 'tutor_commitment_fee', 'tutor_payments', 'operating_expense', 'provisional_expense', 'staff_cost', 'finance_cost',
            'gross_profit', 'sales_sum', 'profit'
        ));

    }

public function incomeByProduct(Request $request)
{
    $selectedMonth = $request->input('SelectedMonth');
    $selectedYear = $request->input('SelectedYear');

    // Set default values if month and year are not selected
    if (empty($selectedMonth)) {
        $selectedMonth = date('m'); // Current month in 'm' format (01-12)
    }
    
    if (empty($selectedYear)) {
        $selectedYear = date('Y'); // Current year in 'Y' format (e.g., 2024)
    }

    // Start building the query
    $query = DB::table('products')
        ->join('categories', 'products.category', '=', 'categories.id')
        ->join('job_tickets', 'job_tickets.subjects', '=', 'products.id')
        ->select(
            'products.id',
            'products.name as name',
            DB::raw('SUM(job_tickets.totalPrice) as total_price'),
            'categories.mode as mode',
            'categories.category_name as category_name'
        )
        ->groupBy('products.id', 'products.name', 'categories.mode', 'categories.category_name');

    // Apply filters based on selected month and year if they are provided
    if ($selectedMonth != '13' && $selectedYear != '13') {
        if ($selectedMonth != 'all' && $selectedYear != 'all') {
            $query->whereMonth('job_tickets.created_at', $selectedMonth)
                  ->whereYear('job_tickets.created_at', $selectedYear);
        }
    }

    // Fetch the products based on the query
    $products = $query->get();

    // Return view with products data, selectedMonth, and selectedYear
    return view('financialReport.incomeByProduct', compact('products', 'selectedMonth', 'selectedYear'));
}

    public function customer_statement(Request $req)
    {

        if ($req->has('account_id')) {
            $account_id = $req->account_id;

            // Opening balance
            $opening_balance = DB::table("customer_commitment_fees")
                ->where("customer_id", $account_id)
                ->value("payment_amount");

            // Credit data (receiving vouchers)
            $credit_query = CustomerVoucher::where(["customer_id" => $account_id, "type" => "receiving"]);

            // Debit data (payment vouchers)
            $debit_query = CustomerVoucher::where(["customer_id" => $account_id, "type" => "payment"]);

            // Invoices data
            $invoices_query = DB::table("invoices")->where("account_id", $account_id);

            // Payments data
            $payments_query = DB::table("payments")->where("account_id", $account_id);

            // Applying date filters if provided
            if ($req->filled('from_date') && $req->filled('to_date')) {
                $from_date = $req->from_date;
                $to_date = $req->to_date;

                $credit_query->whereBetween('created_at', [$from_date, $to_date]);
                $debit_query->whereBetween('created_at', [$from_date, $to_date]);
                $invoices_query->whereBetween('created_at', [$from_date, $to_date]);
                $payments_query->whereBetween('date_2', [$from_date, $to_date]);
            }

            // Calculate sums
            $credit_data_sum = $credit_query->sum("amount");
            $debit_data_sum = $debit_query->sum("amount");
            $invoices_debit_data_sum = $invoices_query->sum("invoiceTotal");
            $payments_credit_data_sum = $payments_query->sum("amount");

            // Retrieve data sets
            $credit_data = $credit_query->get();
            $debit_data = $debit_query->get();
            $invoices_debit_data = $invoices_query->get();
            $payments_credit_data = $payments_query->get();

            // Merge data sets
            $merged_data = $credit_data->merge($debit_data);

            // Append invoices data
            foreach ($invoices_debit_data as $ddata) {
                if ($ddata->invoiceTotal > 0) {
                    $merged_data[] = (object)[
                        "id" => rand(1, 99),
                        "customer_id" => $ddata->account_id,
                        "type" => "payment",
                        "note" => "",
                        "reference_no" => "INV-" . $ddata->reference,
                        "amount" => $ddata->invoiceTotal,
                        "created_at" => $ddata->created_at,
                        "updated_at" => $ddata->updated_at,
                    ];
                }
            }

            // Append payments data
            foreach ($payments_credit_data as $cdata) {
                if ($cdata->amount > 0) {
                    $merged_data[] = (object)[
                        "id" => rand(1, 99),
                        "customer_id" => $cdata->account_id,
                        "type" => "receiving",
                        "note" => "",
                        "reference_no" => $cdata->bill_no,
                        "amount" => $cdata->amount,
                        "created_at" => $cdata->date_2,
                        "updated_at" => $cdata->date_2,
                    ];
                }
            }

            // Sort merged data by created_at in descending order
            $sorted_data = $merged_data->sortByDesc('created_at');

            // Calculate totals
            $totalCredit = $credit_data_sum + $payments_credit_data_sum;
            $totalDebit = $debit_data_sum + $invoices_debit_data_sum;

            // Pass data to view
            return view('financialReport.customer_statement', compact('opening_balance', 'totalDebit', 'totalCredit', 'sorted_data'));
        }


        return view('financialReport.customer_statement');
    }

    public function class_statement()
    {
        return view('financialReport.class_statement');
    }


    public function delete_cash_in_hand($id)
    {

        DB::table('cash_registers')->where('id', '=', $id)->delete();
        return redirect()->back();
    }

    public function add_cash_in_hand(Request $request)
    {

        //dd($request);
        //die();

        DB::table('cash_registers')->insert([
            'created_at' => $request['date'],
            'cash_in_hand' => $request['cashInHand'],
            'user_id' => 1
        ]);

        return redirect()->back();

    }

    public function customer_payment_voucher_list()
    {

        return view('financialReport.voucher.customer_payment_voucher_list');
    }


    public function expense_voucher_list()
    {

        $expense_vouchers = ExpenseVoucher::
        join("accounts", "expense_vouchers.expense_id", "=", "accounts.id")->
        select("expense_vouchers.*", "accounts.name as expense_name")->
        orderBy("id", "desc")->
        get();


        return view('financialReport.voucher.expense_voucher_list', Compact('expense_vouchers'));
    }

    public function bank_voucher_list()
    {
        $bank_vouchers = BankVoucher::
        join("accounts", "bank_vouchers.expense_id", "=", "accounts.id")->
        select("bank_vouchers.*", "accounts.name as expense_name")->
        orderBy("id", "desc")->
        get();
        return view('financialReport.voucher.bank_voucher_list', Compact('bank_vouchers'));
    }


    public function supplier_receiving_voucher_edit_post(Request $request)
    {
        //dd($request);
        //die();
        DB::table('payment_vouchers')->where('id', $request->id)->update(
            array(
                'receive_voucher_date' => $request->date,
                'amount' => $request->amount,
                'action' => $request->action,
                'supplier_id' => $request->account_id,
            )
        );

        $account = DB::table('accounts')
            ->where('supplier_id', '=', $request->account_id)
            ->get();


        if ($request->action == 'credit') {

            DB::table('payments')->where('payment_voucher_id', $request->id)->update(
                array(
                    'date' => $request->date,
                    'date_2' => $request->date,
                    'amount' => $request->amount,
                    'credit' => $request->amount,
                    'debit' => NULL,
                    'type' => 'c',
                    'account_id' => $request->account_id,
                )
            );

        } elseif ($request->action == 'debit') {
            DB::table('payments')->where('payment_voucher_id', $request->id)->update(
                array(
                    'date' => $request->date,
                    'date_2' => $request->date,
                    'amount' => $request->amount,
                    'debit' => $request->amount,
                    'credit' => NULL,
                    'type' => 'd',
                    'account_id' => $request->account_id,
                )
            );
        }


        return redirect()->back()->with('success', 'Voucher has been updated successfully');
    }

    public function customer_receiving_voucher_edit_post(Request $request)
    {
        //dd($request);
        //die();
        DB::table('receive_vouchers')->where('id', $request->id)->update(
            array(
                'receive_voucher_date' => $request->date,
                'amount' => $request->amount,
                'action' => $request->action,
                'customer_id' => $request->account_id,
            )
        );

        $account = DB::table('accounts')
            ->where('customer_id', '=', $request->account_id)
            ->get();


        if ($request->action == 'credit') {

            DB::table('payments')->where('receive_voucher_id', $request->id)->update(
                array(
                    'date' => $request->date,
                    'date_2' => $request->date,
                    'amount' => $request->amount,
                    'credit' => $request->amount,
                    'debit' => NULL,
                    'type' => 'c',
                    'account_id' => $account[0]->customer_id,
                )
            );

        } elseif ($request->action == 'debit') {
            DB::table('payments')->where('receive_voucher_id', $request->id)->update(
                array(
                    'date' => $request->date,
                    'date_2' => $request->date,
                    'amount' => $request->amount,
                    'debit' => $request->amount,
                    'credit' => NULL,
                    'type' => 'd',
                    'account_id' => $account[0]->customer_id,
                )
            );
        }


        return redirect()->back()->with('success', 'Voucher has been updated successfully');
    }


    public function customer_voucher_list()
    {

        $customer_receive_voucher = CustomerVoucher::
        join("customers", "customers.id", "=", "customer_vouchers.customer_id")->
        select("customer_vouchers.*", "customers.full_name as customer_name")->
        orderBy("customer_vouchers.id", "desc")->

        get();


        return view('financialReport.voucher.customer_voucher_list', compact('customer_receive_voucher'));
    }

    public function view_customer_voucher($id)
    {
        $customer_receive_voucher = CustomerVoucher::
        join("customers", "customers.id", "=", "customer_vouchers.customer_id")->
        select("customer_vouchers.*", "customers.full_name as customer_name")
            ->where("customer_vouchers.id", $id)
            ->first();

        $voucher_items = CustomerVoucherItem::where("customer_receive_voucher_id", $id)->get();

        return view('financialReport.voucher.view_customer_voucher', compact('customer_receive_voucher', 'voucher_items'));
    }

    public function view_tutor_voucher($id)
    {
        $customer_receive_voucher = TutorVoucher::
        join("tutors", "tutors.id", "=", "tutor_vouchers.tutor_id")->
        select("tutor_vouchers.*", "tutors.full_name as tutor_name")
            ->where("tutor_vouchers.id", $id)
            ->first();

        $voucher_items = TutorVoucherItem::where("tutor_voucher_id", $id)->get();

        return view('financialReport.voucher.view_tutor_voucher', compact('customer_receive_voucher', 'voucher_items'));
    }

    public function view_expense_voucher($id)
    {
        $customer_receive_voucher = ExpenseVoucher::
        join("accounts", "accounts.id", "=", "expense_vouchers.expense_id")
            ->select("expense_vouchers.*", "accounts.name as expense_name")
            ->where("expense_vouchers.id", $id)
            ->first();

        $voucher_items = ExpenseVoucherItem::where("expense_voucher_id", $id)->get();

        return view('financialReport.voucher.view_expense_voucher', compact('customer_receive_voucher', 'voucher_items'));
    }


    public function view_bank_voucher($id)
    {
        $customer_receive_voucher = BankVoucher::
        join("accounts", "accounts.id", "=", "bank_vouchers.expense_id")
            ->select("bank_vouchers.*", "accounts.name as expense_name")
            ->where("bank_vouchers.id", $id)
            ->first();

        $voucher_items = BankVoucherItem::where("bank_voucher_id", $id)->get();

        return view('financialReport.voucher.view_bank_voucher', compact('customer_receive_voucher', 'voucher_items'));
    }


    public function delete_tutor_voucher($id)
    {
        TutorVoucher::find($id)->delete();
        $items = TutorVoucherItem::where("tutor_voucher_id", $id)->get();
        foreach ($items as $item) {
            TutorVoucherItem::find($item->id)->delete();
        }
        return redirect("/tutor-voucher-list");
    }

    public function delete_bank_voucher($id)
    {
        BankVoucher::find($id)->delete();
        $items = BankVoucherItem::where("bank_voucher_id", $id)->get();
        foreach ($items as $item) {
            BankVoucherItem::find($item->id)->delete();
        }
        return redirect("/bank-voucher-list");
    }


    public function delete_expense_voucher($id)
    {
        ExpenseVoucher::find($id)->delete();
        $items = ExpenseVoucherItem::where("expense_voucher_id", $id)->get();
        foreach ($items as $item) {
            ExpenseVoucherItem::find($item->id)->delete();
        }
        return redirect("/expense-voucher-list");
    }

    public function delete_customer_voucher($id)
    {
        CustomerVoucher::find($id)->delete();
        $items = CustomerVoucherItem::where("customer_receive_voucher_id", $id)->get();
        foreach ($items as $item) {
            CustomerVoucherItem::find($item->id)->delete();
        }
        return redirect("/customer-voucher-list");
    }

    public function customer_receiving_voucher_edit($id)
    {

        $customer_receive_voucher = DB::table('receive_vouchers')->where('id', '=', $id)->get();


        return view('financialReport.voucher.customer_receiving_voucher_edit', compact('customer_receive_voucher'));
    }

    public function tutor_receiving_voucher_edit($id)
    {

        $supplier_receive_voucher = DB::table('payment_vouchers')->where('id', '=', $id)->get();

        return view('financialReport.voucher.supplier_receiving_voucher_edit', compact('supplier_receive_voucher'));
    }


    public function tutor_receiving_voucher_list()
    {

        $tutors = TutorVoucher::
        join("tutors", "tutors.id", "=", "tutor_vouchers.tutor_id")->
        select("tutor_vouchers.*", "tutors.full_name as tutor_name")->
        orderBy("id", "desc")->
        get();

        return view('financialReport.voucher.tutor_voucher_list', compact('tutors'));
    }




//    public function expense_voucher_list()
//    {
//
//        $tutors =ExpenseVoucher::
//        join("accounts","accounts.id","=","expense_vouchers.expense_id")->
//        select("expense_vouchers.*","accounts.name as expense_name")->
//        orderBy("id","desc")->
//        get();
//
//        return view('financialReport.voucher.tutor_voucher_list', compact('tutors'));
//    }

    public function tutor_voucher()
    {

        $tutors = DB::table('tutors')->
        where("is_deleted", "!=", 1)->
        orderBy("id", "desc")->get();

        return view('financialReport.voucher.tutor_voucher', compact('tutors'));


    }

    public function general_journal_edit(Request $request)
    {

        //dd($request);
        //die();
        $user = User::find(Auth::id());

        DB::table('receive_vouchers')
            ->where('id', $request['id'])
            ->update([
                'receive_voucher_date' => $request['date'],
                'customer_id' => $request['customer_id'],
                'amount' => $request['amount'],
                'note' => $request['note'],
                'action' => $request['action']
            ]);

        if ($request['action'] == 'credit') {

            DB::table('payments')
                ->where('receive_voucher_id', $request['id'])
                ->update([
                    'sale_id' => 'Receiving',
                    'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'type' => 'c',
                    'receive_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'credit' => $request['amount'],
                    'debit' => NULL,
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        if ($request['action'] == 'debit') {

            DB::table('payments')
                ->where('receive_voucher_id', $request['id'])
                ->update([
                    'sale_id' => 'Receiving',
                    'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'type' => 'd',
                    'receive_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'debit' => $request['amount'],
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        $date = $request['date'];
        $message = "Receive Voucher has been updated successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);


    }

    public function general_journal_edit_two($date)
    {


        //$data = $request->all();
        //$date = $data['sale_date'];

        $payment_vouchers = DB::table('payment_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $payment_vouchers_debit = DB::table('payment_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'debit')
            ->orderBy('id', 'ASC')
            ->sum('amount');


        $receive_vouchers = DB::table('receive_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'asc')
            ->sum('amount');

        $receive_vouchers_debit = DB::table('receive_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'debit')
            ->orderBy('id', 'asc')
            ->sum('amount');


        $expense_vouchers = DB::table('expense_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $expense_vouchers_debit = DB::table('expense_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'debit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $bank_vouchers = DB::table('bank_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $bank_vouchers_debit = DB::table('bank_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'debit')
            ->orderBy('id', 'ASC')
            ->sum('amount');


        $payment_vouchers_list = DB::table('payment_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->orderBy('id', 'ASC')
            ->get();


        //$receive_vouchers_list = [];
        $receive_vouchers_list = DB::table('receive_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->orderBy('id', 'ASC')
            ->get();
        //if($receive_vouchers_list[0]->customer_id = "25371"){
        //  echo $receive_vouchers_list[0]->customer_id;
        //$customer_name = DB::table('customers')->where('id', $receive_vouchers_list[0]->customer_id)->get();
        //echo $customer_name[0]->name.' ('.$customer_name[0]->city.') ';
        //}
        //die();
        $expense_vouchers_list = DB::table('expense_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->orderBy('id', 'ASC')
            ->get();

        $bank_vouchers_list = DB::table('bank_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->orderBy('id', 'ASC')
            ->get();

        $cash_in_hand = DB::table('cash_registers')
            ->where('created_at', '=', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($cash_in_hand->isEmpty()) {
            $cashInHand = 0;
        } else {
            $cashInHand = $cash_in_hand[0]->cash_in_hand;
        }
        $newDate = '';
        return view('financialReport.voucher.general_journal_two', compact('cashInHand', 'newDate', 'payment_vouchers', 'receive_vouchers', 'expense_vouchers', 'bank_vouchers', 'payment_vouchers_debit', 'receive_vouchers_debit', 'expense_vouchers_debit', 'bank_vouchers_debit', 'payment_vouchers_list', 'receive_vouchers_list', 'bank_vouchers_list', 'date', 'expense_vouchers_list'));


    }

    public function general_journal_edit_bank_voucher(Request $request)
    {

        //dd($request);
        //die();
        $user = User::find(Auth::id());

        DB::table('bank_vouchers')
            ->where('id', $request['id'])
            ->update([
                'receive_voucher_date' => date("Y-m-d", strtotime($request['date'])),
                'bank_id' => $request['customer_id'],
                'amount' => $request['amount'],
                'note' => $request['note'],
                'action' => $request['action']
            ]);

        if ($request['action'] == 'credit') {

            DB::table('payments')
                ->where('bank_voucher_id', $request['id'])
                ->update([
                    'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'bank_id' => $request['customer_id'],
                    'type' => 'c',
                    'bank_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'credit' => $request['amount'],
                    'debit' => NULL,
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        if ($request['action'] == 'debit') {

            DB::table('payments')
                ->where('bank_voucher_id', $request['id'])
                ->update([
                    'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'bank_id' => $request['customer_id'],
                    'type' => 'd',
                    'bank_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'debit' => $request['amount'],
                    'credit' => NULL,
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }


        $date = $request['date'];
        $message = "Bank Voucher has been updated successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);


        //return redirect('general-journal')->with('success', 'Record has been Updated Successfully!');

    }

    public function general_journal_edit_expense_voucher(Request $request)
    {
        //dd($request);
        //die();
        $user = User::find(Auth::id());

        DB::table('expense_vouchers')
            ->where('id', $request['id'])
            ->update([
                'receive_voucher_date' => date("Y-m-d", strtotime($request['date'])),
                'expense_id' => $request['customer_id'],
                'amount' => $request['amount'],
                'note' => $request['note'],
                'action' => $request['action']
            ]);

        if ($request['action'] == 'credit') {

            DB::table('payments')
                ->where('expense_voucher_id', $request['id'])
                ->update([
                    'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'expense_id' => $request['customer_id'],
                    'type' => 'c',
                    'expense_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'credit' => $request['amount'],
                    'debit' => NULL,
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        if ($request['action'] == 'debit') {

            DB::table('payments')
                ->where('expense_voucher_id', $request['id'])
                ->update([
                    'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'expense_id' => $request['customer_id'],
                    'type' => 'd',
                    'expense_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'debit' => $request['amount'],
                    'credit' => NULL,
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }


        $date = $request['date'];
        $message = "Expense Voucher has been updated successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);


        //return redirect('general-journal')->with('success', 'Record has been Updated Successfully!');
    }

    public function general_journal_edit_payment_voucher(Request $request)
    {

        // dd($request);
        // die();

        $user = User::find(Auth::id());

        DB::table('payment_vouchers')
            ->where('id', $request['id'])
            ->update([
                'receive_voucher_date' => date("Y-m-d", strtotime($request['date'])),
                'supplier_id' => $request['customer_id'],
                'amount' => $request['amount'],
                'note' => $request['note'],
                'action' => $request['action']
            ]);

        if ($request['action'] == 'credit') {

            DB::table('payments')
                ->where('payment_voucher_id', $request['id'])
                ->update([
                    'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'purchase_id' => $request['customer_id'],
                    'type' => 'c',
                    'payment_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'credit' => $request['amount'],
                    'debit' => NULL,
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        if ($request['action'] == 'debit') {

            DB::table('payments')
                ->where('payment_voucher_id', $request['id'])
                ->update([
                    'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $request['customer_id'],
                    'purchase_id' => $request['customer_id'],
                    'type' => 'd',
                    'payment_voucher_id' => $request['id'],
                    'date' => date('Y-m-d', strtotime($request['date'])),
                    'date_2' => date('Y-m-d', strtotime($request['date'])),
                    'debit' => $request['amount'],
                    'credit' => NULL,
                    'amount' => $request['amount'],
                    'payment_note' => $request['note'],
                    'user_id' => Auth::id(),
                    'paying_method' => 0,
                    'change' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }

        $date = $request['date'];
        $message = "Payment Voucher has been updated successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);
    }

    public function receive_voucher_delete($id)
    {

        $receive_vouchers = DB::table('receive_vouchers')->where('id', $id)->get();
        $payment = DB::table('payments')->where('receive_voucher_id', $receive_vouchers[0]->id)->delete();
        DB::table('receive_vouchers')->where('id', '=', $id)->delete();

        $date = date("Y-m-d", strtotime($receive_vouchers[0]->receive_voucher_date));
        $message = "Receive Voucher has been Deleted successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);

        //return redirect('general-journal')->with('success', 'Record has been deleted Successfully!');
    }

    public function payment_voucher_delete($id)
    {

        $receive_vouchers = DB::table('payment_vouchers')->where('id', $id)->get();
        $payment = DB::table('payments')->where('payment_voucher_id', $receive_vouchers[0]->id)->delete();
        DB::table('payment_vouchers')->where('id', '=', $id)->delete();

        $date = date("Y-m-d", strtotime($receive_vouchers[0]->receive_voucher_date));
        $message = "Receive Voucher has been Deleted successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);

        //return redirect('general-journal')->with('success', 'Record has been deleted Successfully!');

    }

    public function expense_voucher_delete($id)
    {

        $receive_vouchers = DB::table('expense_vouchers')->where('id', $id)->get();
        $payment = DB::table('payments')->where('expense_voucher_id', $receive_vouchers[0]->id)->delete();
        DB::table('expense_vouchers')->where('id', '=', $id)->delete();

        $date = date("Y-m-d", strtotime($receive_vouchers[0]->receive_voucher_date));
        $message = "Expense Voucher has been Deleted successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);

        //return redirect('general-journal')->with('success', 'Record has been deleted Successfully!');

    }

    public function bank_voucher_delete($id)
    {

        $receive_vouchers = DB::table('bank_vouchers')->where('id', $id)->get();
        $payment = DB::table('payments')->where('bank_voucher_id', $receive_vouchers[0]->id)->delete();
        DB::table('bank_vouchers')->where('id', '=', $id)->delete();

        $date = date("Y-m-d", strtotime($receive_vouchers[0]->receive_voucher_date));
        $message = "Bank Voucher has been Deleted successfully";
        return redirect('receive-voucher/general_journal_edit_two/' . $date)->with('message', $message);

        //return redirect('general-journal')->with('success', 'Record has been deleted Successfully!');

    }

    public function general_journal23_two($date)
    {

        $receive_vouchers_debit = "";
        $receive_vouchers = DB::table('receive_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'asc')
            ->sum('amount');
        $payment_vouchers_debit = "";
        $payment_vouchers = "";
        $expense_vouchers_debit = "";
        $expense_vouchers = "";
        $bank_vouchers_debit = "";
        $bank_vouchers = "";
        $receive_vouchers_list = [];
        $payment_vouchers_list = [];
        $expense_vouchers_list = [];
        $bank_vouchers_list = [];
        $cash_in_hand = DB::table('cash_registers')
            ->where('created_at', '=', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($cash_in_hand->isEmpty()) {
            $cashInHand = 0;
        } else {
            $cashInHand = $cash_in_hand[0]->cash_in_hand;
        }
        $newDate = '';
        return view('financialReport.voucher.general_journal_two', compact('cashInHand', 'newDate', 'payment_vouchers', 'receive_vouchers', 'expense_vouchers', 'bank_vouchers', 'payment_vouchers_debit', 'receive_vouchers_debit', 'expense_vouchers_debit', 'bank_vouchers_debit', 'payment_vouchers_list', 'receive_vouchers_list', 'bank_vouchers_list', 'date', 'expense_vouchers_list'));
    }

    public function print_general_journal23($date)
    {

        $payment_vouchers = DB::table('payment_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $payment_vouchers_debit = DB::table('payment_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'debit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $receive_vouchers = DB::table('receive_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'asc')
            ->sum('amount');

        $receive_vouchers_debit = DB::table('receive_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'debit')
            ->orderBy('id', 'asc')
            ->sum('amount');


        $expense_vouchers = DB::table('expense_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $expense_vouchers_debit = DB::table('expense_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'debit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $bank_vouchers = DB::table('bank_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $bank_vouchers_debit = DB::table('bank_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'debit')
            ->orderBy('id', 'ASC')
            ->sum('amount');


        $payment_vouchers_list = DB::table('payment_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->orderBy('id', 'ASC')
            ->get();


        //$receive_vouchers_list = [];
        $receive_vouchers_list = DB::table('receive_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->orderBy('id', 'ASC')
            ->get();
        //if($receive_vouchers_list[0]->customer_id = "25371"){
        //  echo $receive_vouchers_list[0]->customer_id;
        //$customer_name = DB::table('customers')->where('id', $receive_vouchers_list[0]->customer_id)->get();
        //echo $customer_name[0]->name.' ('.$customer_name[0]->city.') ';
        //}
        //die();
        $expense_vouchers_list = DB::table('expense_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->orderBy('id', 'ASC')
            ->get();

        $bank_vouchers_list = DB::table('bank_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->orderBy('id', 'ASC')
            ->get();

        $cash_in_hand = DB::table('cash_registers')
            ->where('created_at', '=', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($cash_in_hand->isEmpty()) {
            $cashInHand = 0;
            $cashInHand_date = 'No Date';
        } else {
            $cashInHand = $cash_in_hand[0]->cash_in_hand;
            $cashInHand_date = $cash_in_hand[0]->created_at;
        }
        $newDate = '';

        return view('financialReport.voucher.print_general_journal', compact('cashInHand', 'cashInHand_date', 'newDate', 'payment_vouchers', 'receive_vouchers', 'expense_vouchers', 'bank_vouchers', 'payment_vouchers_debit', 'receive_vouchers_debit', 'expense_vouchers_debit', 'bank_vouchers_debit', 'payment_vouchers_list', 'receive_vouchers_list', 'bank_vouchers_list', 'date', 'expense_vouchers_list'));
    }


    public function general_journal23(Request $request)
    {
        $data = $request->all();
        $date = $data['sale_date'];

        $payment_vouchers = DB::table('payment_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $payment_vouchers_debit = DB::table('payment_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'debit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $receive_vouchers = DB::table('receive_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'asc')
            ->sum('amount');

        $receive_vouchers_debit = DB::table('receive_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'debit')
            ->orderBy('id', 'asc')
            ->sum('amount');


        $expense_vouchers = DB::table('expense_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $expense_vouchers_debit = DB::table('expense_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'debit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $bank_vouchers = DB::table('bank_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'credit')
            ->orderBy('id', 'ASC')
            ->sum('amount');

        $bank_vouchers_debit = DB::table('bank_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->where('action', '=', 'debit')
            ->orderBy('id', 'ASC')
            ->sum('amount');


        $payment_vouchers_list = DB::table('payment_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->orderBy('id', 'ASC')
            ->get();


        //$receive_vouchers_list = [];
        $receive_vouchers_list = DB::table('receive_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->orderBy('id', 'ASC')
            ->get();
        //if($receive_vouchers_list[0]->customer_id = "25371"){
        //  echo $receive_vouchers_list[0]->customer_id;
        //$customer_name = DB::table('customers')->where('id', $receive_vouchers_list[0]->customer_id)->get();
        //echo $customer_name[0]->name.' ('.$customer_name[0]->city.') ';
        //}
        //die();
        $expense_vouchers_list = DB::table('expense_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->orderBy('id', 'ASC')
            ->get();

        $bank_vouchers_list = DB::table('bank_vouchers')
            ->where('receive_voucher_date', '=', $date)
            ->orderBy('id', 'ASC')
            ->get();

        $cash_in_hand = DB::table('cash_registers')
            ->where('created_at', '=', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($cash_in_hand->isEmpty()) {
            $cashInHand = 0;
            $cashInHand_date = 'No Date';
        } else {
            $cashInHand = $cash_in_hand[0]->cash_in_hand;
            $cashInHand_date = $cash_in_hand[0]->created_at;
        }
        $newDate = '';
        return view('financialReport.voucher.general_journal_two', compact('cashInHand', 'cashInHand_date', 'newDate', 'payment_vouchers', 'receive_vouchers', 'expense_vouchers', 'bank_vouchers', 'payment_vouchers_debit', 'receive_vouchers_debit', 'expense_vouchers_debit', 'bank_vouchers_debit', 'payment_vouchers_list', 'receive_vouchers_list', 'bank_vouchers_list', 'date', 'expense_vouchers_list'));
    }

    public function delete_all_selected($id)
    {
        echo json_encode($id);
    }

    public function general_journal()
    {

        //$payment_vouchers = DB::table('payment_vouchers')
        //      ->where('receive_voucher_date', '=', '2021-03-22 00:00:00')
        //    ->orderBy('id', 'ASC')
        //  ->sum('amount');
        //dd($payment_vouchers);
        //die();
        //$receive_vouchers = DB::table('receive_vouchers')
        //      ->orderBy('id', 'ASC')
        //    ->get();
        //$expense_vouchers = DB::table('expense_vouchers')
        ///     ->orderBy('id', 'ASC')
        //  ->get();
        $balance = 0;

        //$date = date('Y-m-d H:i:s');
        //$newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y ');
        //dd($newDate);

        $date = '';
        $cash_in_hand = '';
        $payment_vouchers = '';
        $receive_vouchers = '';
        $expense_vouchers = '';
        $bank_vouchers = '';
        $payment_vouchers_debit = '';
        $receive_vouchers_debit = '';
        $expense_vouchers_debit = '';
        $bank_vouchers_debit = '';
        $payment_vouchers_list = [];
        $receive_vouchers_list = [];
        $expense_vouchers_list = [];
        $bank_vouchers_list = [];
        return view('financialReport.voucher.general_journal', compact('cash_in_hand', 'payment_vouchers', 'bank_vouchers_list', 'payment_vouchers_list', 'date', 'receive_vouchers', 'receive_vouchers_debit', 'payment_vouchers_debit', 'bank_vouchers', 'expense_vouchers_debit', 'bank_vouchers_debit', 'expense_vouchers', 'receive_vouchers_list', 'expense_vouchers_list'));
    }

    public function expense_voucher()
    {
        $expenses = ChartOfAccount::where("type", "EXPENSES")->orWhere("type", "Expense")->get();

        return view('financialReport.voucher.expense_voucher', compact('expenses'));

    }


    public function recieve_vouchers()
    {

        $receive_vouchers = DB::table('payment_vouchers')->where('receive_voucher_date', '=', '2021-10-06 00:00:00')->where('status', '=', 0)->get();
        return view('financialReport.voucher.rv', compact('receive_vouchers'));
    }

    public function vouchereditForLedger($id)
    {

        $rv = DB::table('payment_vouchers')->where('id', '=', $id)->where('status', '=', 0)->get();
        $receive_voucher_id = $rv[0]->id;
        $receive_voucher_date = $rv[0]->receive_voucher_date;
        $customer_id = $rv[0]->supplier_id;
        $amount = $rv[0]->amount;
        $action = $rv[0]->action;


        if ($action == 'credit') {

            $user = User::find(Auth::id());
            //echo $user->name;
            //die();
            DB::table('payments')->insert([
                'credit' => $amount,
                'debit' => NULL,
                'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                'account_id' => $customer_id,
                'change' => 0,
                'payment_voucher_id' => $receive_voucher_id,
                'amount' => $amount,
                'purchase_id' => 'Payments',
                'date' => date('Y-m-d', strtotime($receive_voucher_date)),
                'date_2' => date('Y-m-d', strtotime($receive_voucher_date)),
                'type' => "c",
                'paying_method' => 0,
                'payment_note' => '',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $receive_vouchers_updated = DB::table('payment_vouchers')->where('id', $id)->update(['status' => 1]);
            return redirect()->back()->with('message', 'Record has been Updated Successfully! ');

        } elseif ($action == 'debit') {

            $user = User::find(Auth::id());
            //dd($user);
            //die();

            DB::table('payments')->insert([
                'debit' => $amount,
                'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                'account_id' => $customer_id,
                'change' => 0,
                'payment_voucher_id' => $receive_voucher_id,
                'amount' => $amount,
                'purchase_id' => 'Payments',
                'date' => date('Y-m-d', strtotime($receive_voucher_date)),
                'date_2' => date('Y-m-d', strtotime($receive_voucher_date)),
                'type' => "d",
                'paying_method' => 0,
                'payment_note' => '',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $receive_vouchers_updated = DB::table('payment_vouchers')->where('id', $id)->update(['status' => 1]);
            return redirect()->back()->with('message', 'Record has been Updated Successfully!');
        }
    }

    public function customer_transfer_edit($id, $date)
    {
        $payment_id = $id;
        return view('financialReport.voucher.customer_transfer_edit', compact('payment_id', 'date'));
    }

    public function supplier_transfer_edit($id, $date)
    {
        $payment_id = $id;
        return view('financialReport.voucher.supplier_transfer_edit', compact('payment_id', 'date'));
    }

    public function customer_transfer_edit_post(Request $request)
    {
        $data = $request->all();

        if ($request->action == 'c') {
            DB::table('payments')->where('id', $request->id)->update(
                array(
                    'credit' => $request->amount,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'account_id' => $request->account_id,
                    'debit' => NULL,
                    'date_2' => $request->date,
                    'type' => $request->action,
                    'payment_note' => $request->payment_note,
                )
            );
        } else {
            DB::table('payments')->where('id', $request->id)->update(
                array(
                    'debit' => $request->amount,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'account_id' => $request->account_id,
                    'credit' => NULL,
                    'date_2' => $request->date,
                    'type' => $request->action,
                    'payment_note' => $request->payment_note,
                )
            );
        }
        return redirect()->back()->with('message', 'Record has been Updated Successfully! ');
    }


    public function supplier_transfer_edit_post(Request $request)
    {

        if ($request->action == 'c') {
            DB::table('payments')->where('id', $request->id)->update(
                array(
                    'credit' => $request->amount,
                    'debit' => NULL,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'account_id' => $request->account_id,
                    'date_2' => $request->date,
                    'type' => $request->action,
                    'payment_note' => $request->payment_note,
                )
            );
        } else {
            DB::table('payments')->where('id', $request->id)->update(
                array(
                    'debit' => $request->amount,
                    'credit' => NULL,
                    'account_id' => $request->account_id,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'date_2' => $request->date,
                    'type' => $request->action,
                    'payment_note' => $request->payment_note,
                )
            );
        }
        return redirect()->back()->with('message', 'Record has been Updated Successfully! ');
    }


    public function cash_in_hand()
    {

        $cash_in_hand = DB::table('cash_registers')->orderBy('id', 'DESC')->get();
        return view('financialReport.voucher.cashInHand', compact('cash_in_hand'));
    }

    public function customer_ledger_delete($id)
    {
        //dd($id);
        //die();
        $payments = DB::table('payments')
            ->where('id', '=', $id)
            ->get();

        if ($payments[0]->receive_voucher_id != NULL) {
            DB::table('receive_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
        } elseif ($payments[0]->payment_voucher_id != NULL) {
            DB::table('payment_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
        } elseif ($payments[0]->expense_voucher_id != NULL) {
            DB::table('expense_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
        } elseif ($payments[0]->bank_voucher_id != NULL) {
            DB::table('bank_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
        }

        DB::table('payments')->where('id', '=', $id)->delete();
        return redirect()->back();
    }

    public function supplier_ledger_delete($id)
    {
        //dd($id);
        //die();
        $payments = DB::table('payments')
            ->where('id', '=', $id)
            ->get();

        if ($payments[0]->receive_voucher_id != NULL) {
            DB::table('receive_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
        } elseif ($payments[0]->payment_voucher_id != NULL) {
            DB::table('payment_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
        } elseif ($payments[0]->expense_voucher_id != NULL) {
            DB::table('expense_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
        } elseif ($payments[0]->bank_voucher_id != NULL) {
            DB::table('bank_vouchers')->where('id', '=', $payments[0]->receive_voucher_id)->delete();
        }

        DB::table('payments')->where('id', '=', $id)->delete();
        return redirect()->back();
    }

    public function voucher_deleted($id)
    {
        echo $id;


    }

    public function add_recieve_voucher()
    {

        $balance = 0;
        $lims_products_list = Customer::where('is_active', true)->orderBy('name', 'ASC')->get();
        $list_payment = DB::table('payments')->orderBy('created_at', 'desc')->first();

        //$product_id =  $request->input('product_id');

        $today = date('23-03-2021');
        //$sale_date = $request->input('sale_date');
        $newDate = date("d-m-Y", strtotime($today));

        //$today = $newDate;

        $receive_vouchers = DB::table('receive_vouchers')
            ->orderBy('id', 'ASC')
            ->get();

        //$testing = DB::table('receive_vouchers')->orderBy('id', 'ASC')->first();

        return view('financialReport.voucher.add_receive_voucher', compact('receive_vouchers', 'lims_products_list', 'list_payment', 'balance'));

    }

    public function add_payment_voucher()
    {

        $balance = 0;
        $lims_products_list = Customer::where('is_active', true)->orderBy('name', 'ASC')->get();
        $list_payment = DB::table('payments')->orderBy('created_at', 'desc')->first();

        $today = date('23-03-2021');
        //$sale_date = $request->input('sale_date');
        $newDate = date("d-m-Y", strtotime($today));

        //$today = $newDate;

        $receive_vouchers = DB::table('receive_vouchers')
            ->orderBy('id', 'ASC')
            ->get();

        //$testing = DB::table('receive_vouchers')->orderBy('id', 'ASC')->first();

        return view('financialReport.voucher.add_purchase_voucher', compact('receive_vouchers', 'lims_products_list', 'list_payment', 'balance'));

    }

    public function customer_voucher()
    {
        $customers = DB::table('customers')->where("is_deleted", "!=", 1)->get();
        return view('financialReport.voucher.customer_voucher', compact('customers'));
    }

    public function customer_payment_voucher()
    {
        $lims_products_list = DB::table('customers')->get();

        return view('financialReport.voucher.customer_payment_voucher', compact('lims_products_list'));

    }

    public function submitCustomerVoucher(Request $request)
    {
//        dd($request->all());
        $total = 0;
        $ref = "";
        if ($request->type == "payment") {
            $ref = "CPV-" . date('dis');
        } else {
            $ref = "CRV-" . date('dis');
        }
        $receive_voucher = CustomerVoucher::create([
            "customer_id" => $request->customer_id,
            "note" => $request->note,
            "type" => $request->type,
            "reference_no" => $ref,
            "amount" => 100,
        ]);

        $description = $request->description;
        $quantity = $request->quantity;
        $price = $request->price;

        foreach ($description as $key => $value) {
            CustomerVoucherItem::create([
                "customer_receive_voucher_id" => $receive_voucher->id,
                "quantity" => $quantity[$key],
                "description" => $description[$key],
                "price" => $price[$key],
                "total" => $price[$key] * $quantity[$key]
            ]);
            $total += $price[$key] * $quantity[$key];

        }
        $receive_voucher->update(["amount" => $total,]);
        return redirect("/customer-voucher-list");
    }


    public function submitTutorVoucher(Request $request)
    {

        $total = 0;
        $ref = "";
        if ($request->type == "payment") {
            $ref = "TPV-" . date('dis');
        } else {
            $ref = "TRV-" . date('dis');
        }
        $receive_voucher = TutorVoucher::create([
            "tutor_id" => $request->tutor_id,
            "note" => $request->note,
            "type" => $request->type,
            "reference_no" => $ref,
            "amount" => 100,
        ]);

        $description = $request->description;
        $quantity = $request->quantity;
        $price = $request->price;

        foreach ($description as $key => $value) {
            TutorVoucherItem::create([
                "tutor_voucher_id" => $receive_voucher->id,
                "quantity" => $quantity[$key],
                "description" => $description[$key],
                "price" => $price[$key],
                "total" => $price[$key] * $quantity[$key]
            ]);
            $total += $price[$key] * $quantity[$key];

        }
        $receive_voucher->update(["amount" => $total,]);
        return redirect("/tutor-voucher-list");
    }


    public function submitExpenseVoucher(Request $request)
    {

        $total = 0;
        $ref = "";
        if ($request->type == "payment") {
            $ref = "EPV-" . date('dis');
        } else {
            $ref = "ERV-" . date('dis');
        }
        $receive_voucher = ExpenseVoucher::create([
            "expense_id" => $request->expense_id,
            "note" => $request->note,
            "type" => $request->type,
            "reference_no" => $ref,
            "amount" => 100,
        ]);

        $description = $request->description;
        $quantity = $request->quantity;
        $price = $request->price;

        foreach ($description as $key => $value) {
            ExpenseVoucherItem::create([
                "expense_voucher_id" => $receive_voucher->id,
                "quantity" => $quantity[$key],
                "description" => $description[$key],
                "price" => $price[$key],
                "total" => $price[$key] * $quantity[$key]
            ]);
            $total += $price[$key] * $quantity[$key];

        }
        $receive_voucher->update(["amount" => $total,]);
        return redirect("/expense-voucher-list");
    }


    public function tutor_payment_voucher()
    {


        $lims_products_list = DB::table('tutors')->get();

        return view('financialReport.voucher.supplier_payment_voucher', compact('lims_products_list'));

    }

    public function expense_receiving_voucher()
    {


        $lims_products_list = DB::table('accounts')->where('type', 'Expense')->orderBy('id', 'ASC')->get();

        $expense_vouchers = DB::table('expense_vouchers')->orderBy('id', 'ASC')->get();
        $balance = 0;
        return view('financialReport.voucher.expense_receiving_voucher', compact('expense_vouchers', 'lims_products_list', 'balance'));

    }

    public function expense_payment_voucher()
    {

        $lims_products_list = DB::table('accounts')->where('type', 'Expense')->orderBy('id', 'ASC')->get();
        $expense_vouchers = DB::table('expense_vouchers')
            ->orderBy('id', 'ASC')
            ->get();
        $balance = 0;
        return view('financialReport.voucher.expense_payment_voucher', compact('expense_vouchers', 'lims_products_list', 'balance'));

    }

    public function bank_voucher()
    {
        $expenses = Account::where("type", "bank")->orWhere("type", "bank")->get();

        return view('financialReport.voucher.bank_voucher', compact('expenses'));
    }

    public function bank_payment_voucher()
    {

        $lims_products_list = DB::table('accounts')->where('type', 'Bank')->orderBy('id', 'ASC')->get();
        $balance = 0;
        return view('financialReport.voucher.bank_payment_voucher', compact('lims_products_list', 'balance'));

    }

    public function customer_balance($id)
    {

        $date = '2023-06-01';
        $previous_day = date('Y-m-d', (strtotime('0 day', strtotime($date))));
        $previous_day_two = date('Y-m-d', (strtotime('+1 day', strtotime($date))));

        $payment = str_replace($id, 'payment_', $id);

        $payment_id = str_replace('payment_', '', $id);

        $customer_name = DB::table('customers')
            ->where('id', '=', $payment_id)
            ->get();

        // $customer_closings = DB::table('customer_closings')
        //     ->where('account_id', '=', $payment_id)
        //     ->first();

        $accounts_t_balance_debit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('sale_id', '!=', NULL)
            ->where('date_2', '>=', $previous_day_two)
            ->where('type', '=', 'd')
            ->sum('debit');

        $accounts_t_balance_credit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('sale_id', '!=', NULL)
            ->where('date_2', '>=', $previous_day_two)
            ->where('type', '=', 'c')
            ->sum('credit');

        $total = $accounts_t_balance_debit - $accounts_t_balance_credit;

        //echo json_encode($total);

        $last_date = DB::table('payments')->orderBy('id', 'desc')->where('account_id', '=', $payment_id)->where('credit', '!=', NULL)->first();

        if (isset($last_date->date)) {
            $first_date = new DateTime(date('d-m-Y', strtotime($last_date->date)));
            $second_date = new DateTime(date('d-m-Y'));
            $interval = $first_date->diff($second_date);
            $last = $interval->format('%a');
            return $array = array($total, $customer_name[0]->full_name, $last, $accounts_t_balance_credit, $customer_name[0]->phone);
        } else {
            $last = 0;
            return $array = array($total, $customer_name[0]->full_name, $last, $accounts_t_balance_credit, $customer_name[0]->phone);
        }

    }


    public function tutor_balance($id)
    {

        $payment = str_replace($id, 'payment_', $id);
        $payment_id = str_replace('payment_', '', $id);

        $customer_name = DB::table('tutors')
            ->where('id', '=', $payment_id)
            ->get();

        $accounts_t_balance_debit = DB::table('payments')
            ->where('payments.account_id', '=', $payment_id)
            ->where('payments.purchase_id', '!=', NULL)
            ->where('payments.type', '=', 'd')
            ->sum('payments.debit');

        $accounts_t_balance_credit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('purchase_id', '!=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');

        $total = $accounts_t_balance_credit - $accounts_t_balance_debit;

        $accounts_t_balance_debit_latest = DB::table('payments')
            ->where('payments.account_id', '=', $payment_id)
            ->where('payments.purchase_id', '!=', NULL)
            ->where('payments.type', '=', 'd')
            ->sum('payments.debit');

        $accounts_t_balance_credit_latest = DB::table('payments')
            ->where('payments.account_id', '=', $payment_id)
            ->where('payments.purchase_id', '!=', NULL)
            ->where('payments.type', '=', 'c')
            ->sum('payments.credit');

        $total_latest = $accounts_t_balance_credit_latest - $accounts_t_balance_debit_latest;


        //echo json_encode($total);

        $last_date = DB::table('payments')->orderBy('id', 'desc')->where('account_id', '=', $payment_id)->where('credit', '!=', NULL)->first();

        if (isset($last_date->date)) {
            $first_date = new DateTime(date('d-m-Y', strtotime($last_date->date)));
            $second_date = new DateTime(date('d-m-Y'));
            $interval = $first_date->diff($second_date);
            $last = $interval->format('%a');
            return $array = array($total, $customer_name[0]->full_name, $last, $accounts_t_balance_credit, $total_latest);
        } else {
            $last = 0;
            return $array = array($total, $customer_name[0]->name, $last, $accounts_t_balance_credit, $total_latest);
        }


    }


    public function getRecords($id)
    {
        // dd($id);

        $payment = str_replace($id, 'payment_', $id);
        $payment_id = str_replace('payment_', '', $id);

        $debit_list_two = DB::table('payments as pay')
            ->where('pay.account_id', $payment_id)
            ->where('sale_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->orderBy('date_2', 'ASC')
            ->select("pay.*", DB::raw('DATE_FORMAT(pay.date_2, "%d-%m-%Y") as formatted_date'))
            ->get();


        $credit_sum_by_date = DB::table('payments as pay')
            ->where('pay.account_id', $payment_id)
            ->where('sale_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');

        $debit_sum_by_date = DB::table('payments as pay')
            ->where('pay.account_id', $payment_id)
            ->where('sale_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'd')
            ->sum('debit');


        $total_receiving = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('sale_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');

        $current_balance_credit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('sale_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');

        $current_balance_debit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('sale_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'd')
            ->sum('debit');
        $userData['current_balance'] = $current_balance_debit - $current_balance_credit;

        $userData['total_debit'] = $current_balance_debit;
        $userData['total_credit'] = $current_balance_credit;


        // Fetch all records
        $userData['data'] = $debit_list_two;
        $userData['total_receiving'] = $total_receiving;
        $userData['total_debit_by_date'] = $debit_sum_by_date;
        $userData['total_credit_by_date'] = $credit_sum_by_date;

        echo json_encode($userData);
        exit;

    }

    public function getTutorRecords(Request $request)
    {

        $credit_records = TutorVoucher::where("type", "receiving")->get();

        return view("financialReport.tutor_statement");

    }


    public function getBankRecords($id)
    {

        $payment = str_replace($id, 'payment_', $id);
        $payment_id = $id;

        $debit_list_two = DB::table('payments as pay')
            ->where('pay.account_id', $payment_id)
            ->where('bank_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->orderBy('date_2', 'ASC')
            ->select("pay.*", DB::raw('DATE_FORMAT(pay.date_2, "%d-%m-%Y") as formatted_date'))
            ->get();


        $credit_sum_by_date = DB::table('payments as pay')
            ->where('pay.account_id', $payment_id)
            ->where('bank_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');

        $debit_sum_by_date = DB::table('payments as pay')
            ->where('pay.account_id', $payment_id)
            ->where('bank_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'd')
            ->sum('debit');


        $total_receiving = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('bank_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');

        $current_balance_credit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('bank_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');

        $current_balance_debit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('bank_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'd')
            ->sum('debit');
        $userData['current_balance'] = $current_balance_debit - $current_balance_credit;

        $userData['total_debit'] = $current_balance_debit;
        $userData['total_credit'] = $current_balance_credit;


        // Fetch all records
        $userData['data'] = $debit_list_two;
        $userData['total_receiving'] = $total_receiving;
        $userData['total_debit_by_date'] = $debit_sum_by_date;
        $userData['total_credit_by_date'] = $credit_sum_by_date;

        return response()->json($userData, 200);
        echo json_encode($userData);
        exit;

    }

    public function getExpenseRecords($id)
    {

        $payment = str_replace($id, 'payment_', $id);
        $payment_id = $id;

        $debit_list_two = DB::table('payments as pay')
            ->where('pay.account_id', $payment_id)
            ->where('expense_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->orderBy('date_2', 'ASC')
            ->select("pay.*", DB::raw('DATE_FORMAT(pay.date_2, "%d-%m-%Y") as formatted_date'))
            ->get();


        $credit_sum_by_date = DB::table('payments as pay')
            ->where('pay.account_id', $payment_id)
            ->where('expense_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');

        $debit_sum_by_date = DB::table('payments as pay')
            ->where('pay.account_id', $payment_id)
            ->where('expense_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'd')
            ->sum('debit');


        $total_receiving = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('expense_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');

        $current_balance_credit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('expense_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'c')
            ->sum('credit');

        $current_balance_debit = DB::table('payments')
            ->where('account_id', '=', $payment_id)
            ->where('expense_id', '!=', NULL)
            ->where('payment_voucher_id', '=', NULL)
            ->where('type', '=', 'd')
            ->sum('debit');
        $userData['current_balance'] = $current_balance_debit - $current_balance_credit;

        $userData['total_debit'] = $current_balance_debit;
        $userData['total_credit'] = $current_balance_credit;


        // Fetch all records
        $userData['data'] = $debit_list_two;
        $userData['total_receiving'] = $total_receiving;
        $userData['total_debit_by_date'] = $debit_sum_by_date;
        $userData['total_credit_by_date'] = $credit_sum_by_date;

        echo json_encode($userData);
        exit;

    }



    public function tutor_statement(Request $req)
    {


        // Initialize default values or get them from the request
        $from_date = $req->input('from_date', null);  // Assuming 'from_date' is sent via request
        $to_date = $req->input('to_date', null);      // Assuming 'to_date' is sent via request

        $tutors = Tutor::join("tutor_commitment_fees", "tutors.id", "=", "tutor_commitment_fees.tutor_id")
            ->select("tutors.*")
            ->where("tutors.is_deleted", "0")
            ->get();

        if (isset($req->account_id)) {
            // Get opening balance
            $opening_balance = DB::table("tutor_commitment_fees")
                ->where("tutor_id", $req->account_id)
                ->value("payment_amount");

            // Initialize empty collections
            $credit_data_sum = 0;
            $debit_data_sum = 0;
            $credit_data = collect();
            $debit_data = collect();
            $tutor_payments_debit_data = collect();
            $tutor_payments_debit_data_sum = 0;

            // Apply date filters if both from_date and to_date are not null
            if (!is_null($from_date) && !is_null($to_date)) {
                $credit_data_sum = TutorVoucher::where(["tutor_id" => $req->account_id, "type" => "receiving"])
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->sum("amount");

                $debit_data_sum = TutorVoucher::where(["tutor_id" => $req->account_id, "type" => "payment"])
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->sum("amount");

                $credit_data = TutorVoucher::where(["tutor_id" => $req->account_id, "type" => "receiving"])
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->get();

                $debit_data = TutorVoucher::where(["tutor_id" => $req->account_id, "type" => "payment"])
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->get();

                $tutor_payments_debit_data = DB::table("tutorpayments")
                    ->where("tutorID", $req->account_id)
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->get();

                $tutor_payments_debit_data_sum = DB::table("tutorpayments")
                    ->where("tutorID", $req->account_id)
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->sum("payAmount");
            } else {
                // If from_date or to_date is null, fetch all records without date filters
                $credit_data_sum = TutorVoucher::where(["tutor_id" => $req->account_id, "type" => "receiving"])
                    ->sum("amount");

                $debit_data_sum = TutorVoucher::where(["tutor_id" => $req->account_id, "type" => "payment"])
                    ->sum("amount");

                $credit_data = TutorVoucher::where(["tutor_id" => $req->account_id, "type" => "receiving"])
                    ->get();

                $debit_data = TutorVoucher::where(["tutor_id" => $req->account_id, "type" => "payment"])
                    ->get();

                $tutor_payments_debit_data = DB::table("tutorpayments")
                    ->where("tutorID", $req->account_id)
                    ->get();

                $tutor_payments_debit_data_sum = DB::table("tutorpayments")
                    ->where("tutorID", $req->account_id)
                    ->sum("payAmount");
            }

            // Merge all collections
            $merged_data = $credit_data->merge($debit_data);

            foreach ($tutor_payments_debit_data as $data) {
                if ($data->payAmount > 0) {
                    $merged_data[] = (object)[
                        "id" => $data->id,
                        "customer_id" => $data->tutorID,
                        "type" => "payment",
                        "note" => "",
                        "reference_no" => "TP-" . $data->id,
                        "amount" => $data->payAmount,
                        "created_at" => $data->created_at,
                        "updated_at" => $data->created_at,
                    ];
                }
            }

            // Sort the merged collection by date in descending order
            $sorted_data = $merged_data->sortByDesc('created_at');

            // Calculate total credit and debit
            $totalCredit = $credit_data_sum;
            $totalDebit = $debit_data_sum + $tutor_payments_debit_data_sum;

            // Pass data to view
            return view('financialReport.tutor_statement', compact('opening_balance',
                'totalDebit', 'totalCredit', 'tutors',
                'sorted_data'));
        }
        return view('financialReport.tutor_statement', compact('tutors'));
    }

    public function expense_statement(Request $req)
    {
        // Initialize default values or get them from the request
        $from_date = $req->input('from_date', null);  // Assuming 'from_date' is sent via request
        $to_date = $req->input('to_date', null);      // Assuming 'to_date' is sent via request

        // Fetch all expenses
        $expenses = ChartOfAccount::where("type", "EXPENSES")->get();

        if (isset($req->account_id)) {
            // Calculate opening balance
            $opening_balance = ChartOfAccount::where("id", $req->account_id)->first();
            $opening_balance = $opening_balance->initial_balance;

            // Initialize sums and collections
            $credit_data_sum = 0;
            $debit_data_sum = 0;
            $credit_data = collect();
            $debit_data = collect();
            $tutor_payments_debit_data = collect();
            $tutor_payments_debit_data_sum = 0;

            // Apply date filters if both from_date and to_date are not null
            if (!is_null($from_date) && !is_null($to_date)) {
                $credit_data_sum = ExpenseVoucher::where(["expense_id" => $req->account_id, "type" => "receiving"])
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->sum("amount");

                $debit_data_sum = ExpenseVoucher::where(["expense_id" => $req->account_id, "type" => "payment"])
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->sum("amount");

                $credit_data = ExpenseVoucher::where(["expense_id" => $req->account_id, "type" => "receiving"])
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->get();

                $debit_data = ExpenseVoucher::where(["expense_id" => $req->account_id, "type" => "payment"])
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->get();

                $tutor_payments_debit_data = DB::table("expenditures")
                    ->where("accountId", $req->account_id)
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->get();

                $tutor_payments_debit_data_sum = DB::table("expenditures")
                    ->where("accountId", $req->account_id)
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->sum("total");
            } else {
                // Fetch all records without date filters if from_date or to_date is null
                $credit_data_sum = ExpenseVoucher::where(["expense_id" => $req->account_id, "type" => "receiving"])
                    ->sum("amount");

                $debit_data_sum = ExpenseVoucher::where(["expense_id" => $req->account_id, "type" => "payment"])
                    ->sum("amount");

                $credit_data = ExpenseVoucher::where(["expense_id" => $req->account_id, "type" => "receiving"])
                    ->get();

                $debit_data = ExpenseVoucher::where(["expense_id" => $req->account_id, "type" => "payment"])
                    ->get();

                $tutor_payments_debit_data = DB::table("expenditures")
                    ->where("accountId", $req->account_id)
                    ->get();

                $tutor_payments_debit_data_sum = DB::table("expenditures")
                    ->where("accountId", $req->account_id)
                    ->sum("total");
            }

            // Merge all collections
            $merged_data = $credit_data->merge($debit_data);

            foreach ($tutor_payments_debit_data as $data) {
                if ($data->total > 0) {
                    $merged_data[] = (object)[
                        "id" => $data->id,
                        "customer_id" => $data->accountId,
                        "type" => "payment",
                        "note" => "",
                        "reference_no" => "EXP-" . $data->id,
                        "amount" => $data->total,
                        "created_at" => $data->created_at,
                        "updated_at" => $data->created_at,
                    ];
                }
            }

            // Sort the merged collection by date in descending order
            $sorted_data = $merged_data->sortByDesc('created_at');

            // Calculate total credit and debit
            $totalCredit = $credit_data_sum;
            $totalDebit = $debit_data_sum + $tutor_payments_debit_data_sum;

            // Pass data to view
            return view('financialReport.expense_statement', compact('opening_balance',
                'totalDebit', 'totalCredit', 'expenses', 'sorted_data'));
        }else{
            return view('financialReport.expense_statement',compact('expenses'));
        }


    }

    public function bank_statement(Request $req)
    {
        // Initialize default values or get them from the request
        $from_date = $req->input('from_date', null);  // Assuming 'from_date' is sent via request
        $to_date = $req->input('to_date', null);      // Assuming 'to_date' is sent via request

        // Fetch all bank accounts (assuming 'type' is 'bank' for bank accounts)
        $expenses = Account::where("type", "bank")->get();

        if (isset($req->account_id)) {
            // Calculate opening balance
            $opening_balance = Account::find($req->account_id);
            $opening_balance = $opening_balance->initial_balance;

            // Initialize sums and collections
            $credit_data_sum = 0;
            $debit_data_sum = 0;
            $credit_data = collect();
            $debit_data = collect();

            // Apply date filters if both from_date and to_date are not null
            if (!is_null($from_date) && !is_null($to_date)) {
                $credit_data_sum = BankVoucher::where(["expense_id" => $req->account_id, "type" => "receiving"])
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->sum("amount");

                $debit_data_sum = BankVoucher::where(["expense_id" => $req->account_id, "type" => "payment"])
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->sum("amount");

                $credit_data = BankVoucher::where(["expense_id" => $req->account_id, "type" => "receiving"])
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->get();

                $debit_data = BankVoucher::where(["expense_id" => $req->account_id, "type" => "payment"])
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->get();
            } else {
                // Fetch all records without date filters if from_date or to_date is null
                $credit_data_sum = BankVoucher::where(["expense_id" => $req->account_id, "type" => "receiving"])
                    ->sum("amount");

                $debit_data_sum = BankVoucher::where(["expense_id" => $req->account_id, "type" => "payment"])
                    ->sum("amount");

                $credit_data = BankVoucher::where(["expense_id" => $req->account_id, "type" => "receiving"])
                    ->get();

                $debit_data = BankVoucher::where(["expense_id" => $req->account_id, "type" => "payment"])
                    ->get();
            }

            // Merge all collections
            $merged_data = $credit_data->merge($debit_data);

            // Sort the merged collection by date in descending order
            $sorted_data = $merged_data->sortByDesc('created_at');

            // Calculate total credit and debit
            $totalCredit = $credit_data_sum;
            $totalDebit = $debit_data_sum;

            // Pass data to view
            return view('financialReport.bank_statement', compact('opening_balance',
                'totalDebit', 'totalCredit', 'expenses', 'sorted_data'));
        }
        return view('financialReport.bank_statement', compact('expenses'));

    }

    public function submit_expense_voucher_debit(Request $request)
    {


        $data = $request->all();
        $receive_voucher_date = $request->input('payment_voucher_date');
        $customer_id = $request->input('customer_id');
        $amount = $request->input('qty');
        $note = $request->input('note');
        $action = 'debit';

        for ($i = 0; $i < count($customer_id); $i++) {


            if ($data['qty'][$i] != NULL) {

                $expense_voucher_id = DB::table('expense_vouchers')->insertGetId([
                    'receive_voucher_date' => $receive_voucher_date,
                    'expense_id' => $data['customer_id'][$i],
                    'amount' => $data['qty'][$i],
                    'note' => $data['note'][$i],
                    'years' => '2023',
                    'action' => 'debit'
                ]);

                //$user = User::find(Auth::id());
                $user = DB::table('users')->where('id', '=', Auth::id())->first();

                DB::table('payments')->insert([
                    'debit' => $data['qty'][$i],
                    'credit' => null,
                    'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $customer_id[$i],
                    'expense_id' => $customer_id[$i],
                    'change' => 0,
                    'expense_voucher_id' => $expense_voucher_id,
                    'amount' => $data['qty'][$i],
                    'date' => date('Y-m-d', strtotime($receive_voucher_date)),
                    'date_2' => date('Y-m-d', strtotime($receive_voucher_date)),
                    'type' => "d",
                    'paying_method' => 0,
                    'years' => '2023',
                    'payment_note' => $data['note'][$i],
                    'created_at' => date('Y-m-d H:i:s')
                ]);

            }

        }

        return redirect()->back()->with('message', 'Expense Voucher has been Submitted Successfully! ');


    }


    public function submit_expense_voucher_credit(Request $request)
    {

        $data = $request->all();

        $receive_voucher_date = $request->input('payment_voucher_date');
        $customer_id = $request->input('customer_id');
        $amount = $request->input('qty');
        $note = $request->input('note');
        $action = 'credit';

        for ($i = 0; $i < count($customer_id); $i++) {


            if ($data['qty'][$i] != NULL) {

                $expense_voucher_id = DB::table('expense_vouchers')->insertGetId([
                    'receive_voucher_date' => $receive_voucher_date,
                    'expense_id' => $data['customer_id'][$i],
                    'amount' => $data['qty'][$i],
                    'note' => $data['note'][$i],
                    'years' => '2022',
                    'action' => $action
                ]);

                //$user = User::find(Auth::id());
                $user = DB::table('users')->where('id', '=', Auth::id())->first();

                DB::table('payments')->insert([
                    'credit' => $data['qty'][$i],
                    'debit' => null,
                    'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $customer_id[$i],
                    'expense_id' => $customer_id[$i],
                    'expense_voucher_id' => $expense_voucher_id,
                    'change' => 0,
                    'amount' => $data['qty'][$i],
                    'date' => date('Y-m-d', strtotime($receive_voucher_date)),
                    'date_2' => date('Y-m-d', strtotime($receive_voucher_date)),
                    'type' => "c",
                    'paying_method' => 0,
                    'years' => '2022',
                    'payment_note' => $data['note'][$i],
                    'created_at' => date('Y-m-d H:i:s')
                ]);

            }


        }

        return redirect()->back()->with('message', 'Receive Voucher has been Submitted Successfully! ');

    }


    public function expense_balance($id)
    {

        //$customers = DB::table('suppliers')->where('id', '=', $id)->get();

        $payment_id = str_replace(' ', 'payment_', $id);

        $accounts_balance = DB::table('accounts')->where('type', '=', 'Expense')->where('id', '=', $id)->get();


        $array = array(
            'name' => $accounts_balance[0]->name,
            'balance' => $accounts_balance[0]->initial_balance,
            'city' => $accounts_balance[0]->account_no);
        echo json_encode($array);

    }


    public function bank_balance($id)
    {
        $payment_id = str_replace(' ', 'payment_', $id);
        //$customers = DB::table('suppliers')->where('id', '=', $id)->get();
        $accounts_balance = DB::table('accounts')->where('type', '=', 'Bank')->where('id', '=', $id)->get();

        $array = array(
            'name' => $accounts_balance[0]->name,
            'balance' => $accounts_balance[0]->initial_balance,
            'city' => $accounts_balance[0]->account_no);

        return response()->json($array, 200);


    }


    public function submit_bank_voucher_debit(Request $request)
    {


        $total = 0;
        $ref = "";
        if ($request->type == "payment") {
            $ref = "BPV-" . date('dis');
        } else {
            $ref = "BRV-" . date('dis');
        }
        $receive_voucher = BankVoucher::create([
            "expense_id" => $request->expense_id,
            "note" => $request->note,
            "type" => $request->type,
            "reference_no" => $ref,
            "amount" => 100,
        ]);

        $description = $request->description;
        $quantity = $request->quantity;
        $price = $request->price;

        foreach ($description as $key => $value) {
            BankVoucherItem::create([
                "bank_voucher_id" => $receive_voucher->id,
                "quantity" => $quantity[$key],
                "description" => $description[$key],
                "price" => $price[$key],
                "total" => $price[$key] * $quantity[$key]
            ]);
            $total += $price[$key] * $quantity[$key];

        }

        $receive_voucher->update(["amount" => $total,]);

        return redirect("/bank-voucher-list");

    }


    public function submit_bank_voucher_credit(Request $request)
    {

        $data = $request->all();
        //dd($data);
        //die();

        $receive_voucher_date = $request->input('payment_voucher_date');
        $customer_id = $request->input('customer_id');
        $amount = $request->input('qty');
        $note = $request->input('note');
        $action = 'credit';


        for ($i = 0; $i < count($customer_id); $i++) {

            if ($data['qty'][$i] != NULL) {


                $id = DB::table('bank_vouchers')->insertGetId([
                    'receive_voucher_date' => $receive_voucher_date,
                    'bank_id' => $data['customer_id'][$i],
                    'amount' => $data['qty'][$i],
                    'note' => $data['note'][$i],
                    'years' => '2023',
                    'action' => $action
                ]);

                //$user = User::find(Auth::id());
                $user = DB::table('users')->where('id', '=', Auth::id())->first();

                DB::table('payments')->insert([
                    'credit' => $data['qty'][$i],
                    'debit' => null,
                    'payment_reference' => $user->name . '-' . date("d-m-Y - H:i:a"),
                    'account_id' => $customer_id[$i],
                    'bank_voucher_id' => $id,
                    'change' => 0,
                    'amount' => $data['qty'][$i],
                    'bank_id' => $customer_id[$i],
                    'date' => date('Y-m-d', strtotime($receive_voucher_date)),
                    'date_2' => date('Y-m-d', strtotime($receive_voucher_date)),
                    'type' => "c",
                    'years' => '2023',
                    'paying_method' => 0,
                    'payment_note' => $data['note'][$i],
                    'created_at' => date('Y-m-d H:i:s')
                ]);

            }


        }

        return redirect()->back()->with('message', 'Receive Voucher has been Submitted Successfully! ');


    }


}
