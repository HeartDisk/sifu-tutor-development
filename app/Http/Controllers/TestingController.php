<?php


namespace App\Http\Controllers;

use App\Models\ClassAttended;
use App\Models\ClassSchedules;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Student;
use Illuminate\Http\Request;
use DB;
use DateTime;
use DateInterval;
use Auth;
use Illuminate\Support\Str;
use App\Libraries\WhatsappApi;
use App\Libraries\SmsNiagaApi;
use App\Libraries\PushNotificationLibrary;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\SendInvoiceAttachmentEmail;
use Illuminate\Support\Facades\Mail;

class TestingController extends Controller
{

  public function sendInvoice()
  {
      
      Mail::to('binasift@gmail.com')->send(new SendInvoiceAttachmentEmail());
      
      
      dd("Done");
      
      
      
      
      
    $subjectTwo = 'Invoice';

    // Define boundary
    $boundary = md5(time());

    // Headers
    $headersTwo = "MIME-Version: 1.0" . "\r\n";
    $headersTwo .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
    $headersTwo .= 'From: <tutor@sifu.qurangeek.com>' . "\r\n";

    // Path to PDF file
    $pdfPath = "/home/sifuqurangeek/public_html/public/invoicePDF/Invoice-108.pdf";
    
    // dd($pdfPath);

    // Check if file exists before trying to attach
    if (file_exists($pdfPath)) {
        $pdfContent = file_get_contents($pdfPath);
        $base64Content = base64_encode($pdfContent);
    } else {
        $base64Content = '';
    }

    // Email body
    $emailBody = "--{$boundary}\r\n";
    $emailBody .= "Content-Type: text/html; charset=UTF-8\r\n";
    $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $emailBody .= '
    <html>
        <body>
            <p>Dear Parents/Guardians,</p>
            <p>We hope you are having a great day! Here are some details for your latest invoice:</p>
            <p>1) This invoice is computer-generated and no signature is required.</p>
            <p>2) Payment is due within 3 working days of issuance of this invoice.</p>
            <p>3) You can conveniently pay online via banking by clicking the "PAY NOW" button, or you can transfer to the account below:</p>
            <p><b>MAYBANK - 562115516678 <br> SIFU EDU & LEARNING SDN BHD</b></p>
            <p>Good news! If you pay in advance for 3 months of home or online tuition, you\'ll enjoy a 10% discount.</p>
            <p>... (Include your full HTML content here) ...</p>
        </body>
    </html>
    ';

    // Attachment
    if (!empty($base64Content)) {
        $emailBody .= "\r\n--{$boundary}\r\n";
        $emailBody .= "Content-Type: application/pdf; name=\"Invoice-108.pdf\"\r\n";
        $emailBody .= "Content-Transfer-Encoding: base64\r\n";
        $emailBody .= "Content-Disposition: attachment; filename=\"Invoice-108.pdf\"\r\n\r\n";
        $emailBody .= $base64Content . "\r\n";
    }

    // End boundary
    $emailBody .= "--{$boundary}--";

    // Recipients
    $to_email = "binasift@gmail.com";
    $to = "aasim.creative@gmail.com";

    // Send the email
    mail($to, $subjectTwo, $emailBody, $headersTwo);
    mail($to_email, $subjectTwo, $emailBody, $headersTwo);

    dd("Email sent successfully");
}
    
    public function markAttendance()
    {
        $customers = Customer::all()->unique('email');

        foreach ($customers as $customer) {
            $students = Student::where('customer_id', $customer->id)->get();
            foreach ($students as $student) {
                $classAttendeds = ClassAttended::where('studentID', $student->id)->get();

                $classAttendedsRecords = ClassAttended::where('studentID', $student->id)->first();

                if($classAttendedsRecords)
                {
                    $subject = Product::find($classAttendedsRecords->subjectID);
                $student = Student::find($classAttendedsRecords->studentID);
                $classAttendeds->subjectName=$subject->name;
                 $classAttendeds->studentName=$student->full_name;
                }



                if ($classAttendeds->isEmpty()) {
                    continue;
                }

                // Define email recipient
                // $to = $customer->email;
                $to = "binasift@gmail.com";
                $subject = "Attendance Report at: " . date('Y-m-d H:i:s');

                // Render the email view
                $emailBody = view('emails.attendance_email', compact('classAttendeds'))->render();

                // Set email headers
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: <tutor@sifu.qurangeek.com>' . "\r\n";

                // Send the email
                mail($to, $subject, $emailBody, $headers);
                 mail("mk.khanprogrammer@gmail.com", $subject, $emailBody, $headers);
            }
        }

        return response()->json(['message' => 'Attendance reminder emails have been sent.']);
    }

    public function submitTicket(Request $request)
    {


        $data = $request->all();
        // Generate a UUID

        if ($request->PaymentAttachment) {
            $imageName = time() . '.' . $request->PaymentAttachment->extension();
            $request->PaymentAttachment->move(public_path('PaymentAttachment'), $imageName);
        } else {
            $imageName = "";
        }

        if ($request->student_id == 'newStudent') {

            if ($request->parent_id == 'newParent') {
                $uuidForCustomer = rand(100, 99999);
                $customer_values = array(
                    'uid' => 'CUS-' . $uuidForCustomer,
                    'full_name' => $request->customerFullName,
                    'gender' => $request->customerGender,
                    'age' => $request->customerAge,
                    'email' => $request->customerEmail,
                    'dob' => $request->customerDateOfBirth,
                    'nric' => $request->customerCNIC,
                    'address1' => $request->address,
                    'city' => $request->customerCity,
                    'state' => $request->customerState,
                    'phone' => '+60' . $request->customerPhone,
                    'whatsapp' => '+60' . $request->customerWhatsapp,
                    'postal_code' => $request->customerPostalcode,
                    'latitude' => $request->customerLatitude,
                    'longitude' => $request->customerLongitude,
                    'customerable_type' => 0,
                    'customerable_id' => 0,
                    'remarks' => $request->remarks
                );

                $customerLastID = DB::table('customers')->insertGetId($customer_values);
                $uuidForStudent = rand(100, 99999);
                $studentValues = array(
                    'uid' => 'ST-' . $uuidForStudent,
                    'student_id' => 'ST-' . $uuidForStudent,
                    'full_name' => $request->mainStudentFullName,
                    'register_date' => $request->registration_date,
                    'customer_id' => $customerLastID,
                    'gender' => $request->mainStudentGender,
                    'age' => $request->mainAge,
                    'email' => $request->studentEmail,
                    'phone' => '+60' . $request->studentPhone,
                    'whatsapp' => '+60' . $request->studentWhatsapp,
                    'dob' => $request->mainStudentYearOfBirth,
                    'cnic' => $request->studentNRIC,
                    'address1' => $request->address,
                    'specialNeed' => $request->mainStudentSpecialNeed,
                    'city' => $request->studentCity,
                    'state' => $request->studentState,
                    'postal_code' => $request->studentPostalcode,
                    'latitude' => $request->studentLatitude,
                    'longitude' => $request->studentLongitude,
                    'receiving_account' => $request->receivingAccount,
                    'remarks' => $request->remarks
                );
                $studentLastID = DB::table('students')->insertGetId($studentValues);

                if (isset($data['studentFullName'])) {

                    for ($j = 0; $j < count($data['studentFullName']); $j++) {
                        if ($data['studentFullName'][$j] != NULL) {
                            if ($data['student_ids'][$j] == 'newStudent') {
                                $studentValuesTwo = array(
                                    'uid' => 'ST-' . $uuidForStudent,
                                    'student_id' => 'ST-' . $uuidForStudent,
                                    'full_name' => $data['studentFullName'][$j],
                                    'register_date' => $request->registration_date,
                                    'customer_id' => $customerLastID,
                                    'fee_payment_date' => $request->FeePaymentDate,
                                    'gender' => $data['studentGender'][$j],
                                    'age' => $data['age'][$j],
                                    'dob' => $data['studentDateOfBirth'][$j],
                                    'cnic' => $request->studentNRIC,
                                    'address1' => $request->address,
                                    'specialNeed' => $data['specialNeed'][$j]
                                );
                                $studentLastIDTwo = DB::table('students')->insertGetId($studentValuesTwo);
                                DB::table('customers')->where("id", $customerLastID)->update(["student_id" => $studentLastIDTwo]);

                            }


                        }
                    }
                }
                DB::table('customers')->where("id", $customerLastID)->update(["student_id" => $studentLastID]);

            } else {
                $uuidForStudent = rand(100, 99999);
                $studentValues = array(
                    'uid' => 'ST-' . $uuidForStudent,
                    'student_id' => 'ST-' . $uuidForStudent,
                    'full_name' => $request->mainStudentFullName,
                    'register_date' => $request->registration_date,
                    'customer_id' => $request->parent_id,
                    'gender' => $request->mainStudentGender,
                    'age' => $request->mainAge,
                    'email' => $request->studentEmail,
                    'phone' => '+60' . $request->studentPhone,
                    'whatsapp' => '+60' . $request->studentWhatsapp,
                    'dob' => $request->mainStudentYearOfBirth,
                    'cnic' => $request->studentNRIC,
                    'address1' => $request->address,
                    'specialNeed' => $request->mainStudentSpecialNeed,
                    'city' => $request->studentCity,
                    'state' => $request->studentState,
                    'postal_code' => $request->studentPostalcode,
                    'latitude' => $request->studentLatitude,
                    'longitude' => $request->studentLongitude,
                    'receiving_account' => $request->receivingAccount,
                    'remarks' => $request->remarks
                );
                $studentLastID = DB::table('students')->insertGetId($studentValues);

                if (isset($data['studentFullName'])) {
                    for ($j = 0; $j < count($data['studentFullName']); $j++) {
                        if ($data['studentFullName'][$j] != NULL) {
                            if ($data['student_ids'][$j] == 'newStudent') {
                                $uuidForAnotherStudent = rand(100, 99999);
                                $studentValuesTwo = array(
                                    'uid' => 'ST-' . $uuidForAnotherStudent,
                                    'student_id' => 'ST-' . $uuidForAnotherStudent,
                                    'full_name' => $data['studentFullName'][$j],
                                    'register_date' => $request->registration_date,
                                    'customer_id' => $request->parent_id,
                                    'gender' => $data['studentGender'][$j],
                                    'age' => $data['age'][$j],
                                    'dob' => $data['studentDateOfBirth'][$j],
                                    'specialNeed' => $data['specialNeed'][$j]
                                );
                                $studentLastIDTwo = DB::table('students')->insertGetId($studentValuesTwo);
                            }
                        }
                    }

                }
            }

        } else {
            $studentLastID = $request->student_id;
        }
        $data = $request->all();

        $latestTicketID = DB::table('job_tickets')->latest('created_at')->first();
        if ($latestTicketID) {
            $ticketIDs = $latestTicketID->id + 1;
        } else {
            $ticketIDs = 1;
        }
        $subject = $data['subject'];
        $addonStudents =count($data['studentFullName']);

        for ($i = 0; $i < count($subject); $i++) {

            $dayArray = array();

            foreach ($data['day'][$i + 1] as $selectedDay) {
                $dayArray[] = $selectedDay;
            }

            $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();

            $subjectFee = DB::table('products')->join("categories", "products.category", "=", "categories.id")
                ->select("products.*", "categories.price as category_price", "categories.mode as mode")
                ->where('products.id', '=', $data['subject'][$i])->first();



            if ($subjectFee->mode == "physical") {
                $extraStudentCharges = DB::table("extra_student_charges")->first();
                $extraStudentChargesDate = $extraStudentCharges->created_at;
                $extraStudentCharges = $extraStudentFeeCharges->physical_additional_charges;
                $extraChargesTutorComission = $addonStudents * $extraStudentFeeCharges->tutor_physical;


            } else {
                $extraStudentCharges = DB::table("extra_student_charges")->first();
                $extraStudentChargesDate = $extraStudentCharges->created_at;
                $extraStudentCharges = $extraStudentFeeCharges->online_additional_charges;
                $extraChargesTutorComission = $addonStudents * $extraStudentFeeCharges->tutor_online;


            }

            $uuidForTicket = rand(100, 99999);
            $ticektValues = array(
                'ticket_id' => $ticketIDs,
                'uid' => 'JT-' . $uuidForTicket,
                'student_id' => $studentLastID,
                'admin_charge' => $request->inCharge,
                'service' => $request->service,
                'payment_attachment' => $imageName,
                'fee_payment_date' => $request->PaymentDate,
                'fee_payment_amount' => $request->feeAmount / count($data['subject']),
                'receiving_account' => $request->ReceivingAccountId,
                'subjects' => $data['subject'][$i],
                'subject_fee' => $subjectFee->category_price,
                'quantity' => $data['quantity'][$i],
                'classFrequency' => $data['classFrequency'][$i],
                'remaining_classes' => $data['classFrequency'][$i],
                'tutorPereference' => $data['tutorPereference'][$i],
                'day' => json_encode(implode(",", $dayArray)),
                'time' => $data['time'][$i],
                'subscription' => $data['subscription'][$i],
                'specialRequest' => $data['specialRequest'][$i],
                'classAddress' => $request->classAddress,
                'classLatitude' => $request->classLatitude,
                'classLongitude' => $request->classLongitude,
                'classCity' => $request->classCity,
                'classState' => $request->classState,
                'classPostalCode' => $request->classPostalCode,
                'register_date' => $request->registration_date,
                'mode' => $request->classType,
                'estimate_commission' => $request->estimate_commission,
                'status' => 'pending'
            );
            $jobTicketLastID = DB::table('job_tickets')->insertGetId($ticektValues);

            $student_data = array(
                'student_id' => $studentLastID,
                'ticket_id' => $jobTicketLastID,
                'ticket_id2' => $ticketIDs,
                'subject' => $data['subject'][$i],
                'quantity' => $data['quantity'][$i],
                'classFrequency' => $data['classFrequency'][$i],
                'remaining_classes' => $data['classFrequency'][$i],
                'day' => json_encode(implode(",", $dayArray)),
                'time' => $data['time'][$i],
                'subscription' => $data['subscription'][$i],
                'specialRequest' => $data['specialRequest'][$i],
            );
            DB::table('student_subjects')->insertGetId($student_data);

            if (isset($data['studentFullName'])) {
                for ($j = 0; $j < count($data['studentFullName']); $j++) {
                    if ($data['studentFullName'][$j] != NULL) {

//                        dd($subjectFee->mode);
                        if ($subjectFee->mode == "physical") {
                            $extraStudentCharges = DB::table("extra_student_charges")->first();
                            $extraStudentChargesDate = $extraStudentCharges->created_at;
                            $extraStudentCharges = $extraStudentFeeCharges->physical_additional_charges;
                            $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_physical;


                        } else {
                            $extraStudentCharges = DB::table("extra_student_charges")->first();
                            $extraStudentChargesDate = $extraStudentCharges->created_at;
                            $extraStudentCharges = $extraStudentFeeCharges->online_additional_charges;
                            $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_online;


                        }


                        $extra_student_charges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();
                        $multipleStudent = array(
                            'student_id' => $studentLastID,
                            'student_name' => $data['studentFullName'][$j],
                            'student_gender' => $data['studentGender'][$j],
                            'student_age' => $data['age'][$j],
                            'year_of_birth' => $data['studentDateOfBirth'][$j],
                            'special_need' => $data['specialNeed'][$j],
                            'job_ticket_id' => $jobTicketLastID,
                            'subject_id' => $data['subject'][$i],
                            'extra_fee' => $extraStudentCharges,
                            'extra_fee_tutor' => $extraChargesTutorComission,
                            'extra_fee_date' => $extraStudentChargesDate,
                        );
                        DB::table('job_ticket_students')->insertGetId($multipleStudent);
                    }
                }
            }


            $studentDetail = DB::table('students')->where('id', '=', $studentLastID)->first();
            $customerDetail = DB::table('customers')->where('id', '=', $studentDetail->customer_id)->first();
            $subjectDetail = DB::table('products')->join("categories", "products.category", "=", "categories.id")
                ->select("products.*", "categories.price as category_price", "categories.mode as class_mode", "categories.category_name as category_name")->
                where('products.id', '=', $data['subject'][$i])->first();

            $tableName = 'job_ticket_students';
            $count = DB::table($tableName)
                ->select(DB::raw('count(*) as count'))
                ->where('job_ticket_id', '=', $jobTicketLastID)
                ->first()
                ->count;

            if ($subjectDetail->class_mode == "physical") {
                $extraStudentCharges = DB::table("extra_student_charges")->first();
                $extraStudentCharges = $extraStudentFeeCharges->physical_additional_charges;

            } else {
                $extraStudentCharges = DB::table("extra_student_charges")->first();
                $extraStudentCharges = $extraStudentFeeCharges->online_additional_charges;

            }




            $extraStudentFeeCharges = DB::table('extra_student_charges')->orderBy('id', 'DESC')->first();
            $jobTicketID = DB::table('job_tickets')->where('id', '=', $jobTicketLastID)->first();
            $ledgerValue = array(
                'payment_reference' => Auth::user()->id,
                'user_id' => Auth::user()->id,
                'bill_no' => $jobTicketID->uid,
                'sale_id' => $jobTicketID->uid,
                'account_id' => $customerDetail->id,
                'amount' => ($subjectDetail->price * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]) + ($count * $extraStudentCharges)),
                'type' => 'd',
                'debit' => ($subjectDetail->price * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]) + ($count * $extraStudentCharges)),
                'credit' => 0,
                'date' => date('Y-m-d'),
                'date_2' => date('Y-m-d')
            );

            $price = $subjectDetail->category_price;
            $classFrequency = floatval($data['classFrequency'][$i]);
            $quantity = floatval($data['quantity'][$i]);

            if ($subjectDetail->class_mode == "physical") {
                $extraCharges = $count * $extraStudentFeeCharges->physical_additional_charges;
                $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_physical;

            } else {
                $extraCharges = $count * $extraStudentFeeCharges->online_additional_charges;
                $extraChargesTutorComission = $count * $extraStudentFeeCharges->tutor_online;

            }


            $result = $price * $classFrequency * $quantity + $extraCharges;
            $ledgerID = DB::table('payments')->insertGetId($ledgerValue);

            if ($request->feeAmount != NULL) {
                $ledgerValueTwo = array(
                    'payment_reference' => Auth::user()->id,
                    'user_id' => Auth::user()->id,
                    'bill_no' => $jobTicketID->uid,
                    'sale_id' => $jobTicketID->uid,
                    'account_id' => $customerDetail->id,
                    'amount' => $request->feeAmount / count($data['subject']),
                    'type' => 'c',
                    'saleDescription' => 'Commitment Fee - ' . $request->receivingAccountId,
                    'sale_note' => $jobTicketID->uid,
                    'credit' => $request->feeAmount / count($data['subject']),
                    'debit' => null,
                    'date' => date('Y-m-d'),
                    'date_2' => date('Y-m-d')
                );
                $ledgerID = DB::table('payments')->insertGetId($ledgerValueTwo);
            }

            $tableName = 'job_ticket_students';
            $count = DB::table($tableName)
                ->select(DB::raw('count(*) as count'))
                ->where('job_ticket_id', '=', $ticketIDs)
                ->first()
                ->count;


            $class_term = $jobTicketID->subscription; //Checking the subsciption of the class (Long term/short term)
            $modeOfClass = $subjectDetail->class_mode;  //Checking the class mode(Physical or Online)
            $category_id_subject = $subjectDetail->category;
            $category_level_subject = $subjectDetail->category_name;  //Getting the category of the class(i.e PT3, IGCSE, Pre-school, Diploma)
            $estimate_commission = 0;
            $estimate_after_eight_hours = 0;

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

            if ($class_term == "LongTerm") {

                if ($modeOfClass == "online") {

                    switch ($category_level_subject) {

                        case "Pre-school":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_online_first_eight_hours["Pre-school"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["Pre-school"];

                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }


                            break;

                        case "UPSR":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_online_first_eight_hours["UPSR"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["UPSR"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "PT3":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_online_first_eight_hours["PT3"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["PT3"];

                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }

                            break;

                        case "SPM":
                            $numberOfSessions = $data['classFrequency'][$i];

                            $per_hour_charges = $long_term_online_first_eight_hours["SPM"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["SPM"];

                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {

                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {

                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "IGCSE":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_online_first_eight_hours["IGCSE"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["IGCSE"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "STPM":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_online_first_eight_hours["STPM"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["STPM"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "A-level/Pre-U":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_online_first_eight_hours["A-level/Pre-U"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["A-level/Pre-U"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "Diploma":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_online_first_eight_hours["Diploma"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["Diploma"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "Degree":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_online_first_eight_hours["Degree"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["Degree"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "ACCA":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_online_first_eight_hours["ACCA"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["ACCA"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));
                            }
                            break;

                        case "Master":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_online_first_eight_hours["Master"];
                            $per_hour_charges_addition = $long_term_online_after_eight_hours["Master"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));


                            }
                            break;

                    }
                } elseif ($modeOfClass == "physical") {

                    switch ($category_level_subject) {

                        case "Pre-school":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_physical_first_eight_hours["Pre-school"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["Pre-school"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }

                            break;

                        case "UPSR":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_physical_first_eight_hours["UPSR"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["UPSR"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "PT3":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_physical_first_eight_hours["PT3"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["PT3"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));


                            } else {
                                $estimate_commission += (floatval($per_hour_charges + $extraChargesTutorComission) * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition + $extraChargesTutorComission) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }


                            break;

                        case "SPM":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_physical_first_eight_hours["SPM"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["SPM"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "IGCSE":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_physical_first_eight_hours["IGCSE"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["IGCSE"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "STPM":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_physical_first_eight_hours["STPM"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["STPM"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "A-level/Pre-U":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_physical_first_eight_hours["A-level/Pre-U"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["A-level/Pre-U"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "Diploma":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_physical_first_eight_hours["Diploma"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["Diploma"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "Degree":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_physical_first_eight_hours["Degree"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["Degree"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));
                            }
                            break;

                        case "ACCA":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_physical_first_eight_hours["ACCA"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["ACCA"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) + $extraChargesTutorComission * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                        case "Master":
                            $numberOfSessions = $data['classFrequency'][$i];
                            $per_hour_charges = $long_term_physical_first_eight_hours["Master"];
                            $per_hour_charges_addition = $long_term_physical_after_eight_hours["Master"];
                            $estimate_after_eight_hours += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            if ($numberOfSessions <= 8) {
                                $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            } else {
                                $estimate_commission += (floatval($per_hour_charges) * 8 * floatval($data['quantity'][$i]));
                                $estimate_commission += (floatval($per_hour_charges_addition) * floatval($data['classFrequency'][$i] - 8) * floatval($data['quantity'][$i]));

                            }
                            break;

                    }
                }
            } else {
                if ($modeOfClass == "online") {


                    switch ($category_level_subject) {

                        case "Pre-school":

                            $per_hour_charges = $short_term_online["Pre-school"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));


                            break;


                        case "UPSR":
                            $per_hour_charges = $short_term_online["UPSR"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "PT3":
                            $per_hour_charges = $short_term_online["PT3"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;


                        case "SPM":

                            $per_hour_charges = $short_term_online["SPM"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;


                        case "IGCSE":
                            $per_hour_charges = $short_term_online["IGCSE"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "STPM":
                            $per_hour_charges = $short_term_online["STPM"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "A-level/Pre-U":
                            $per_hour_charges = $short_term_online["A-level/Pre-U"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "Diploma":
                            $per_hour_charges = $short_term_online["Diploma"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "Degree":
                            $per_hour_charges = $short_term_online["Degree"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "ACCA":
                            $per_hour_charges = $short_term_online["ACCA"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "Master":
                            $per_hour_charges = $short_term_online["Master"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                    }
                } elseif ($modeOfClass == "physical") {

                    switch ($category_level_subject) {

                        case "Pre-school":
                            $per_hour_charges = $short_term_physical["Pre-school"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;


                        case "UPSR":
                            $per_hour_charges = $short_term_physical["UPSR"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "PT3":
                            $per_hour_charges = $short_term_physical["PT3"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;


                        case "SPM":
                            $per_hour_charges = $short_term_physical["SPM"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;


                        case "IGCSE":
                            $per_hour_charges = $short_term_physical["IGCSE"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "STPM":
                            $per_hour_charges = $short_term_physical["STPM"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "A-level/Pre-U":
                            $per_hour_charges = $short_term_physical["A-level/Pre-U"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "Diploma":
                            $per_hour_charges = $short_term_physical["Diploma"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "Degree":
                            $per_hour_charges = $short_term_physical["Degree"];

                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "ACCA":
                            $per_hour_charges = $short_term_physical["ACCA"];
                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                        case "Master":
                            $per_hour_charges = $short_term_physical["Master"];
                            $estimate_commission += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));
                            $estimate_after_eight_hours += (floatval($per_hour_charges) * floatval($data['classFrequency'][$i]) * floatval($data['quantity'][$i]));

                            break;

                    }
                }
            }

//            if($subjectDetail->class_mode=="physical")
//            {
//                $extraStudentCharges=$extraStudentFeeCharges->tutor_physical;
//                $estimate_commission+=$extraStudentCharges*$count;
//                $estimate_after_eight_hours+=$extraStudentCharges*$count;
//            }else{
//                $extraStudentCharges=$extraStudentFeeCharges->tutor_online;
//                $estimate_commission+=$extraStudentCharges*$count;
//                $estimate_after_eight_hours+=$extraStudentCharges*$count;
//            }


            if ((isset($data["studentFullName"]))) {

                $jobTicketCalc = $subjectFee->category_price + $extraCharges;
                $jobTicketCalc = $jobTicketCalc * $data['classFrequency'][$i] * $data['quantity'][$i];
            } else {
                $jobTicketCalc = $subjectFee->category_price;
                $jobTicketCalc = $jobTicketCalc * $data['classFrequency'][$i] * $data['quantity'][$i];
            }

            //dd($estimate_commission);

            DB::table('job_tickets')
                ->where('id', $jobTicketLastID)
                ->update([
                    'extra_student_total' => $jobTicketCalc/$count,
                    'extra_student_tutor_commission' => $estimate_commission/$count,
                    'extra_estimate_commission_display_tutor' => $estimate_after_eight_hours/$count,
                    'estimate_commission' => $estimate_commission,
                    'estimate_commission_display_tutor' => $estimate_after_eight_hours,
                    'totalPrice' => $jobTicketCalc
                ]);

            if ((isset($data["studentFullName"]))) {

                $calcPrice = $subjectDetail->category_price + $extraCharges;
                $calcPrice = $calcPrice * $data['classFrequency'][$i];
                $calcPrice = $calcPrice * $data['quantity'][$i];
            } else {

                $calcPrice = $subjectDetail->category_price;
                $calcPrice = $calcPrice * $data['classFrequency'][$i];
                $calcPrice = $calcPrice * $data['quantity'][$i];
            }

            $invoiceValue = array(
                'studentID' => $studentLastID,
                'ticketID' => $jobTicketLastID,
                'subjectID' => $data['subject'][$i],
                'account_id' => $customerDetail->id,
                'invoiceDate' => date('Y-m-d'),
                'reference' => $jobTicketLastID,
                'payerName' => $customerDetail->full_name,
                'payerEmail' => $customerDetail->email,
                'payerPhone' => $customerDetail->phone,
                'quantity' => $data['quantity'][$i],
                'classFrequency' => $data['classFrequency'][$i],
                'day' => json_encode(implode(",", $dayArray)),
                'time' => $data['time'][$i],
                'type' => 'd',
                'debit' => ($subjectDetail->price * $data['classFrequency'][$i] * $data['quantity'][$i]) + ($extraCharges),
                'credit' => 0,
                'invoiceTotal' => $calcPrice,
                'brand' => $subjectDetail->brand);
            $invoiceID = DB::table('invoices')->insertGetId($invoiceValue);

            // Split the days string into an array
            $daysArray = explode(',', json_decode($invoiceValue['day']));

            // Get the initial date for the invoice
            $initialDate = new DateTime($invoiceValue['invoiceDate']);

            // Insert records for each day and each occurrence based on ClassFrequency
            for ($j = 0; $j < $data['classFrequency'][$i]; $j++) {
                // Iterate over each day
                $currentDay = $daysArray[$j % count($daysArray)];
                // Get the day for the current iteration using modulus

                // Calculate the date based on the current day
                $date = clone $initialDate;
                while ($date->format('D') !== $currentDay) {
                    $date->add(new DateInterval('P1D'));
                }

                // Update the initial date to the next occurrence of the current day
                $initialDate = clone $date;
                $initialDate->add(new DateInterval('P1D'));


                // Modify data as needed for each iteration
                $invoiceItemsData['quantity'] = $data['quantity'][$i];
                $invoiceItemsData['time'] = $data['time'][$i];
                $invoiceItemsData['day'] = $currentDay;
                $invoiceItemsData['isPaid'] = 'unPaid';
                $invoiceItemsData['studentID'] = $studentLastID;
                $invoiceItemsData['ticketID'] = $jobTicketLastID;
                $invoiceItemsData['subjectID'] = $data['subject'][$i];
                $invoiceItemsData['invoiceID'] = $invoiceID;
                $invoiceItemsData['invoiceDate'] = $date->format('Y-m-d');
                // Add other fields as needed

                // Insert into invoice_items table
                DB::table('invoice_items')->insert($invoiceItemsData);

            }


            // Sample data to pass to the view
            $invoice_detail = DB::table('invoices')->where('id', '=', $invoiceID)->orderBy('id', 'desc')->first();
            $invoice_items = DB::table('invoice_items')->where('invoiceID', '=', $invoiceID)->orderBy('id', 'desc')->get();
            $students = DB::table('students')->where('id', '=', $invoice_detail->studentID)->orderBy('id', 'DESC')->first();
            $customer = DB::table('customers')->where('id', '=', $students->customer_id)->first();

            $subjects = DB::table('products')->join("categories", "products.category", "=", "categories.id")
                ->select("products.*", "categories.price as category_price")
                ->where('products.id', '=', $invoice_detail->subjectID)->first();


            // $subjects = DB::table('products')->where('id','=',$invoice_detail->subjectID)->orderBy('id','DESC')->first();


            //dd($invoice_items);

            $tutorListings = DB::table('tutors')->where('status', '=', 'verified')->get();
            $jobTicketDeails = DB::table('job_tickets')->where('id', '=', $jobTicketLastID)->first();


            // $data = [
            //     'title' => 'Invoice',
            //     'content' => 'System Generated Invoice',
            // ];


            //dd($invoice_items);

            // Generate PDF from a view
            // $pdf = PDF::loadView('pdf.invoice', [
            //     'data' => $data,
            //     'invoice_items' => $invoice_items,
            //     'invoice_detail' => $invoice_detail,
            //     'students' => $students,
            //     'subjects' => $subjects,
            //     'customer' => $customer,
            //     'jobTicketDeails'=>$jobTicketDeails,
            // ]);

            // $pdf->save(public_path('invoicePDF/')."/"."Invoice-".$invoice_detail->id.".pdf");


            foreach ($tutorListings as $rowTutorListings) {
                // $sms_api = new SmsNiagaApi();
                // $whatsapp_api = new WhatsappApi();

                $phone_number = $rowTutorListings->whatsapp;
                $message = 'Dear Tutor: *' . $rowTutorListings->full_name . '*, A Class Ticket has been generated. Class Ticket # *' . $jobTicketDeails->uid . '*';
                // $whatsapp_api->send_message($phone_number, $message);
                // $sms_api->sendSms($phone_number, $message);
            }

            $tutorDevices = DB::table('tutor_device_tokens')->distinct()->get(['device_token', 'tutor_id']);
            //dd($tutorDevices);
            foreach ($tutorDevices as $rowDeviceToken) {
                $push_notification_api = new PushNotificationLibrary();
                $title = 'JOB-Ticket Create Successfully';
                $message = 'Message JOB Ticket ';
                $deviceToken = $rowDeviceToken->device_token;
                $push_notification_api->sendPushNotification($deviceToken, $title, $message);
            }

        }

        //dD("Done");

        return redirect('TicketList')->with('success', 'ticket has been added successfully!');
        //return view('job_tickets/addticket');
    }

}
