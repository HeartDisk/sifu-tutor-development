<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Confirmation</title>
</head>
<body
    style="font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; width: 95%; max-width: 650px; margin: 0 auto;">
<div
    style="margin: auto; background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h2 style="color: #333333">Attendance Confirmation</h2>

    <p>Dear Parents/Guardians, below are details of the recent class attendance and we appreciate your verification:</p>

    @foreach ($classAttendeds as $classAttended)
        @php
            $subject = \App\Models\Product::find($classAttended->subjectID);
            $student = \App\Models\Student::find($classAttended->studentID);
        @endphp
        <p><strong>Subject Name:</strong> {{ $subject->name }}<br>
            <strong>Student Name:</strong> {{ $student->full_name }}</p>

        <p>Below are the details of the class attended:</p>

        <table style="border: 1px solid #ccc; border-collapse: collapse; width: 100%; table-layout: fixed;">
            <thead>
            <tr>
                <th style="background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;"
                    scope="col">Date
                </th>
                <th style="background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;"
                    scope="col">Start Time
                </th>
                <th style="background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;"
                    scope="col">End Time
                </th>
                <th style="background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;"
                    scope="col">Total Time
                </th>
                <th style="background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;"
                    scope="col">Check In Proof
                </th>
                <th style="background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;"
                    scope="col">Checkout Proof
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;"
                    data-label="Date">{{ $classAttended->date }}</td>
                <td style="background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;"
                    data-label="Start Time">{{ $classAttended->startTime }}</td>
                <td style="background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;"
                    data-label="End Time">{{ $classAttended->endTime }}</td>
                <td style="background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;"
                    data-label="Total Time">{{ $classAttended->totalTime }}</td>
                @if($classAttended->startTimeProofImage!=null)
                    <td style="background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;"
                        data-label="Total Time"><img
                            src="{{url("/public/signInProof"."/".$classAttended->startTimeProofImage)}}"
                            style="height: 80px"></td>
                @endif
                @if($classAttended->endTimeProofImage!=null)
                    <td style="background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;"
                        data-label="Total Time"><img
                            src="{{url("/public/signOutProof"."/".$classAttended->endTimeProofImage)}}"
                            style="height: 80px"></td>
                @endif
            </tr>
            </tbody>
        </table>

        <p>
            <a href="{{ url('/agreeAttendance/' . $classAttended->id) }}"
               style="display: inline-block; margin-top: 10px; padding: 10px 0px; background-color: #4caf50; color: #ffffff; text-decoration: none; border-radius: 0px; width: 49.1%; text-align: center; font-weight: bold;">Agree</a>
            <a href="{{ url('/disputeAttendance/' . $classAttended->id) }}"
               style="display: inline-block; margin-top: 10px; padding: 10px 0px; background-color: #e74c3c; color: #ffffff; text-decoration: none; border-radius: 0px; width: 49.1%; text-align: center; font-weight: bold;">Disagree</a>
        </p>
    @endforeach

    <p>Thank you for your cooperation!<br>Sifututor<br></p>

    <p>Thank you</p>
</div>
</body>
</html>
