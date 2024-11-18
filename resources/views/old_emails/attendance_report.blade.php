<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Attendance Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; width: 95%; max-width: 650px; margin: 0 auto;">
<div style="margin: auto; background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h2 style="color: #333333">Attendance Confirmation</h2>

    <p>Dear Parents/Guardians, below are details of the recent class attendance and we appreciate your verification:</p>

    <p><strong>Subject Name:</strong> {{ $subjectName->name }}</p>
    <p><strong>Student Name:</strong> {{ $studentName->full_name }}</p>

    <p>Below are the details of the class attended:</p>

    <table style="border: 1px solid #ccc; border-collapse: collapse; width: 100%; table-layout: fixed;">
        <thead>
        <tr>
            <th style="background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;" scope="col">Date</th>
            <th style="background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;" scope="col">Start Time</th>
            <th style="background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;" scope="col">End Time</th>
            <th style="background-color: #233cb3; color: #fff; padding: .625em; text-align: center; font-size: .85em; letter-spacing: .1em; text-transform: uppercase;" scope="col">Total Time</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;" data-label="Date">{{ $attendedRecord->date }}</td>
            <td style="background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;" data-label="Start Time">{{ $attendedRecord->startTime }}</td>
            <td style="background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;" data-label="End Time">{{ $attendedRecord->endTime }}</td>
            <td style="background-color: #f8f8f8; border: 1px solid #ddd; padding: .60em; text-align: center;" data-label="Total Time">{{ $total_time_attended }}</td>
        </tr>
        </tbody>
    </table>

    <p>
        <a href="{{ $agreePath }}" style="display: inline-block; margin-top: 10px; padding: 10px 0px; background-color: #4caf50; color: #ffffff; text-decoration: none; border-radius: 0px; width: 49.1%; text-align: center; font-weight: bold;">Agree</a>
        
        <a href="mailto:info@sifu.qurangeek.com?subject=Class Attendance dispute" style="display: inline-block; margin-top: 10px; padding: 10px 0px; background-color: #e74c3c; color: #ffffff; text-decoration: none; border-radius: 0px; width: 49.1%; text-align: center; font-weight: bold;">Contact Support</a>
        <!--<a href="{{ $disagreePath }}" style="display: inline-block; margin-top: 10px; padding: 10px 0px; background-color: #e74c3c; color: #ffffff; text-decoration: none; border-radius: 0px; width: 49.1%; text-align: center; font-weight: bold;">Disagree</a>-->
    </p>

    <br> <br>Thank you for your cooperation!<br>Sifututor<br>
</div>
</body>
</html>
