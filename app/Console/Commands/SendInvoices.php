<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\InvoiceItems;



class SendInvoices extends Command
{
    protected $signature = 'send:invoices';
    protected $description = 'Send invoices to customers every thirty days';


    public function handle()
      {


        $thirtyDaysAgo = \Carbon\Carbon::now()->subDays(30)->toDateString();

        // dd($thirtyDaysAgo);

        $invoices = DB::table("invoices")
        ->whereDate('invoiceDate', '<=', $thirtyDaysAgo)
        ->get();

       // dd($invoices);

        // $invoices = DB::table("invoices")
        //     ->limit(10)->get();


        foreach ($invoices as $invoice) {

            $invoice=Invoice::find($invoice->id);
            $newInvoice = $invoice->replicate();
            $newInvoice->invoiceDate = \Carbon\Carbon::now()->format("Y-m-d");

            $ticketData=DB::table("job_tickets")->where("id",$newInvoice->ticketID)->first();

            $subjectData=DB::table("products")->where("id",$newInvoice->subjectID)->first();

            $class_term = $ticketData->subscription; //Checking the subsciption of the class (Long term/short term)
            $modeOfClass = $ticketData->mode;  //Checking the class mode(Physical or Online)
            $category_id_subject = $subjectData->category;
            $category_level_subject = DB::table("categories")->where("id", $category_id_subject)->first();
            $category_level_subject = $category_level_subject->category_name;  //Getting the category of the class(i.e PT3, IGCSE, Pre-school, Diploma)
            $estimate_commission = 0;
            $estimate_after_eight_hours = 0;

            $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();


            //Long Term Classes prices according to hours
            $long_term_online_first_eight_hours = [
                'Pre-school' => 12,
                'UPSR' => 12,
                'PT3' => 12,
                'SPM' => 14,
                'IGCSE' => 18,
                'STPM' => 18,
                'A-level/Pre-U' => 19,
                'Diploma' => 19,
                'Degree' => 21,
                'ACCA' => 25,
                'Master' => 25,
            ];

            $long_term_online_after_eight_hours = [
                'Pre-school' => 24.5,
                'UPSR' => 24.5,
                'PT3' => 24.5,
                'SPM' => 28,
                'IGCSE' => 35,
                'STPM' => 35,
                'A-level/Pre-U' => 38.5,
                'Diploma' => 38.5,
                'Degree' => 42,
                'ACCA' => 49,
                'Master' => 49,
            ];

            $long_term_physical_first_eight_hours = [
                'Pre-school' => 25,
                'UPSR' => 25,
                'PT3' => 25,
                'SPM' => 29,
                'IGCSE' => 38,
                'STPM' => 38,
                'A-level/Pre-U' => 42,
                'Diploma' => 42,
                'Degree' => 46,
                'ACCA' => 55,
                'Master' => 55,
            ];

            $long_term_physical_after_eight_hours = [
                'Pre-school' => 42,
                'UPSR' => 42,
                'PT3' => 42,
                'SPM' => 49,
                'IGCSE' => 63,
                'STPM' => 63,
                'A-level/Pre-U' => 70,
                'Diploma' => 70,
                'Degree' => 77,
                'ACCA' => 91,
                'Master' => 91,
            ];

            //Short Term Classes prices according to hours
            $short_term_online = [
                'Pre-school' => 21,
                'UPSR' => 21,
                'PT3' => 21,
                'SPM' => 24,
                'IGCSE' => 30,
                'STPM' => 30,
                'A-level/Pre-U' => 33,
                'Diploma' => 33,
                'Degree' => 36,
                'ACCA' => 42,
                'Master' => 42,
            ];

            $short_term_physical = [
                'Pre-school' => 36,
                'UPSR' => 36,
                'PT3' => 36,
                'SPM' => 42,
                'IGCSE' => 54,
                'STPM' => 54,
                'A-level/Pre-U' => 60,
                'Diploma' => 60,
                'Degree' => 66,
                'ACCA' => 78,
                'Master' => 78,
            ];

            $classFrequency=$newInvoice->classFrequency+$newInvoice->classFrequency;
            // $newInvoice->classFrequency=$classFrequency;

            $tableName = 'job_ticket_students';
            $count = DB::table($tableName)
                ->select(DB::raw('count(*) as count'))
                ->where('job_ticket_id', '=', $newInvoice->ticketID)
                ->first()
                ->count;

            if ($class_term == "LongTerm") {

                if ($modeOfClass == "online") {

                    switch ($category_level_subject) {

                        case "Pre-School":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_online_first_eight_hours["Pre-School"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["Pre-School"];

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }

                            break;

                        case "UPSR":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_online_first_eight_hours["UPSR"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["UPSR"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "SPM":
                            $numberOfSessions = $classFrequency;

                            $per_hour_charges = $long_term_online_first_eight_hours["SPM"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["SPM"];



                            if ($numberOfSessions <= 8) {

                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                            } else {

                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));




                            }
                            break;

                        case "IGCSE":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_online_first_eight_hours["IGCSE"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["IGCSE"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "STPM":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_online_first_eight_hours["STPM"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["STPM"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "A-level/Pre-U":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_online_first_eight_hours["A-level/Pre-U"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["A-level/Pre-U"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));



                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "Diploma":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_online_first_eight_hours["Diploma"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["Diploma"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));



                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "Degree":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_online_first_eight_hours["Degree"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["Degree"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));



                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "ACCA":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_online_first_eight_hours["ACCA"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["ACCA"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "Master":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_online_first_eight_hours["Master"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["Master"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));



                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                    }
                } elseif ($modeOfClass == "physical") {

                    switch ($category_level_subject) {

                        case "Pre-School":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_physical_first_eight_hours["Pre-School"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["Pre-School"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }

                            break;

                        case "UPSR":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_physical_first_eight_hours["UPSR"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["UPSR"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));



                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "SPM":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_physical_first_eight_hours["SPM"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["SPM"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "IGCSE":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_physical_first_eight_hours["IGCSE"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["IGCSE"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "STPM":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_physical_first_eight_hours["STPM"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["STPM"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "A-level/Pre-U":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_physical_first_eight_hours["A-level/Pre-U"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["A-level/Pre-U"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "Diploma":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_physical_first_eight_hours["Diploma"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["Diploma"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "Degree":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_physical_first_eight_hours["Degree"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["Degree"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "ACCA":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_physical_first_eight_hours["ACCA"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["ACCA"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                        case "Master":
                            $numberOfSessions = $classFrequency;
                            $per_hour_charges = $long_term_physical_first_eight_hours["Master"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["Master"];
                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($classFrequency - 8) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));


                                $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            }
                            break;

                    }
                }
            }
            else {
                if ($modeOfClass == "online") {

                    switch ($category_level_subject) {

                        case "Pre-School":
                            $per_hour_charges = $short_term_online["Pre-School"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                            break;

                        case "UPSR":
                            $per_hour_charges = $short_term_online["UPSR"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                            break;

                        case "SPM":

                            $per_hour_charges = $short_term_online["SPM"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            break;

                        case "IGCSE":
                            $per_hour_charges = $short_term_online["IGCSE"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                            break;

                        case "STPM":
                            $per_hour_charges = $short_term_online["STPM"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                        case "A-level/Pre-U":
                            $per_hour_charges = $short_term_online["A-level/Pre-U"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                        case "Diploma":
                            $per_hour_charges = $short_term_online["Diploma"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                        case "Degree":
                            $per_hour_charges = $short_term_online["Degree"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                        case "ACCA":
                            $per_hour_charges = $short_term_online["ACCA"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                        case "Master":
                            $per_hour_charges = $short_term_online["Master"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                            break;

                    }
                }
                elseif ($modeOfClass == "physical") {
                    switch ($category_level_subject) {

                        case "Pre-School":
                            $per_hour_charges = $short_term_physical["Pre-School"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            break;

                        case "UPSR":
                            $per_hour_charges = $short_term_physical["UPSR"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));
                            break;

                        case "SPM":
                            $per_hour_charges = $short_term_physical["SPM"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            break;

                        case "IGCSE":
                            $per_hour_charges = $short_term_physical["IGCSE"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            break;

                        case "STPM":
                            $per_hour_charges = $short_term_physical["STPM"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            break;

                        case "A-level/Pre-U":
                            $per_hour_charges = $short_term_physical["A-level/Pre-U"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            break;

                        case "Diploma":
                            $per_hour_charges = $short_term_physical["Diploma"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            break;

                        case "Degree":
                            $per_hour_charges = $short_term_physical["Degree"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            break;

                        case "ACCA":
                            $per_hour_charges = $short_term_physical["ACCA"];
                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            break;

                        case "Master":
                            $per_hour_charges = $short_term_physical["Master"];
                            $estimate_commission += (floatval($per_hour_charges) * floatval($classFrequency) * floatval($newInvoice->quantity) + (intval($count) * intval($extraStudentFeeCharges->charges)));

                            break;

                    }
                }
            }

            $newInvoice->save();

            $ticketUpdateValues=
            [
                'estimate_commission'=>$estimate_commission,
                'estimate_commission_display_tutor'=>$estimate_after_eight_hours
                ];

            DB::table("job_tickets")->where("id",$newInvoice->ticketID)->update($ticketUpdateValues);


            $invoiceItems=InvoiceItems::where("invoiceID",$newInvoice->id)->get();
            foreach($invoiceItems as $invoiceItem)
            {
                // dd($invoiceItem);
                $item=InvoiceItems::find($invoiceItem->id);
                $item = $item->replicate();
                $item->invoiceDate = \Carbon\Carbon::now()->format("Y-m-d");
                $item->save();

            }

        }
    }
//     public function handle()
//     {


//         $invoices = DB::table("invoices")->limit(2)->get();

//         foreach ($invoices as $invoice) {

//             $invoice=Invoice::find($invoice->id);
//             $newInvoice = $invoice->replicate();
//             $newInvoice->invoiceDate = \Carbon\Carbon::now()->format("Y-m-d"); // the new project_id
//             $newInvoice->save();

//             // $invoiceItems=InvoiceItems::where("invoiceID",$invoice->id)->get();
//             // foreach($invoiceItems as $invoiceItem)
//             // {
//             //     // dd($invoiceItem);
//             //     $item=InvoiceItems::find($invoiceItem->id);
//             //     $item = $item->replicate();
//             //     $item->invoiceDate = \Carbon\Carbon::now()->format("Y-m-d"); // the new project_id
//             //     $item->save();

//             // }

//         }


//         $tutorPayment = array(
//             'name' => "Ahsan Khan",
//         );

//         DB::table('automations')->insertGetId($tutorPayment);
//         $this->info('Invoices sent successfully!');


//         // Get all invoices that haven't been sent in the last thirty days
// //        $invoices = Invoice::where('last_sent_at', '<=', Carbon::now()->subDays(30))->get();
// //
// //        foreach ($invoices as $invoice) {
// //            // Send the invoice email
// //            Mail::to($invoice->customer->email)->send(new InvoiceMail($invoice));
// //
// //            // Update the last sent timestamp
// //            $invoice->update(['last_sent_at' => now()]);
// //        }
// //
// //        $this->info('Invoices sent successfully!');
//     }
}
