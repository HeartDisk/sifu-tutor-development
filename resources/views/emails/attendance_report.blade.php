<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Confirmation</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

</head>
<body style="margin: 0; padding: 0; background-color: #f2f2f2;">
  
  <!-- Wrapper Table -->
  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 0px; padding: 0px 30px;">
    <tr>
      <td align="center" style="background-color: #f2f2f2; padding: 0px 40px;">

        <!-- Main Container Table -->
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff;">
          <!-- Header with Background Image -->
          <tr>
            <td align="right" valign="middle" background="https://democlient.top/email-template/email-1/Group-1288.png" style="background-size: cover; background-position: center; height: 300px;">

              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <!-- Right side: Logo and Text -->
                  <td align="right" valign="top" style="padding: 40px 40px 40px 40px;">
                    <img src="https://democlient.top/email-template/email-1/logo.png" alt="SifuTutor Logo" style="display: block; width: 120px;">
                    <h1 style="font-family: Plus Jakarta Sans; color: #fff;  font-size: 23px; font-style: normal; font-weight: 600; line-height: 29px; margin: 40px 0 20px 0px;">Attendence<br> Verification<br> Details</h1>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <!-- End Header -->
           <table border="0" cellpadding="0" cellspacing="0" width="600" style="padding: 40px; background-color: #ffffff;">
            <tr>
              <td align="center" valign="middle" style="margin: 0px 0px; padding: 30px 40px 0px 40px;">
                <p style="color: #4E4E4E; text-align: center; font-family: Plus Jakarta Sans; font-size: 15px; font-style: normal; font-weight: 700; line-height: 150%; /* 21px */ letter-spacing: 0.2px;">Dear Parents/Guardians,</p>
                <p style="color: #4E4E4E; text-align: center; font-family: Plus Jakarta Sans; font-size: 15px; font-style: normal; font-weight: 500; line-height: 150%; /* 21px */ letter-spacing: 0.2px;">
                  We hope your child had a productive session with {{ $tutors->full_name }}. Please help us ensure accurate records by verifying the attendance for the session.</p>
              </td>
            </tr>
           </table>
           <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; padding: 0px;">
          <!-- Insert Attendance Details Table Here -->
          <tr>
            <td align="center" valign="middle" style="padding: 0px 40px 20px; ">
              <!-- Attendance Details Table -->
              <table style="width: 100%; max-width: 600px; border-collapse: collapse; border: 1px solid #e0e0e0; margin-top: 20px; border-radius: 10px; ">
                <tr style="background-color: #ffffff;">
                  <th style="padding: 15px; font-family: Plus Jakarta Sans; text-align: left; font-size: 14px; color: #4e4e4e; border-bottom: 1px solid #fff;">Student Name</th>
                  <th style="padding: 15px; font-family: Plus Jakarta Sans; text-align: left; font-size: 14px; color: #4e4e4e; border-bottom: 1px solid #fff;">Subject</th>
                  <th style="padding: 15px; font-family: Plus Jakarta Sans; text-align: left; font-size: 14px; color: #4e4e4e; border-bottom: 1px solid #fff;">Date</th>
                  <th style="padding: 15px; font-family: Plus Jakarta Sans; text-align: left; font-size: 14px; color: #4e4e4e; border-bottom: 1px solid #fff;">Clock in</th>
                  <th style="padding: 15px; font-family: Plus Jakarta Sans; text-align: left; font-size: 14px; color: #4e4e4e; border-bottom: 1px solid #fff;">Clock out</th>
                  <th style="padding: 15px; font-family: Plus Jakarta Sans; text-align: left; font-size: 14px; color: #4e4e4e; border-bottom: 1px solid #fff;">Total Time</th>
                </tr>
                <tr>
                  <td style="padding: 15px; font-family: Plus Jakarta Sans; font-size: 14px; color: #4e4e4e; display: flex; align-items: center;">
                    <a href="#" style="font-weight: bold; font-family: Plus Jakarta Sans; font-size: 16px; color: #2b73d6; text-decoration: none;">{{ $studentName->full_name }}</a>
                  </td>
                  <td style="padding: 15px; font-size: 14px; color: #2b73d6;">
                    <span style="background-color: #e5f0ff; color: #2b73d6; padding: 5px 10px; font-family: Plus Jakarta Sans; border-radius: 15px; font-weight: bold;">{{ $subjectName->name }}</span>
                  </td>
                  <td style="padding: 15px; font-family: Plus Jakarta Sans; font-size: 14px; color: #2b73d6; font-weight: bold;">
                    {{ $attendedRecord->date }}
                  </td>
                  <td style="padding: 15px; font-family: Plus Jakarta Sans; font-size: 14px; color: #2b73d6; font-weight: bold;">
                    {{ $attendedRecord->startTime }}
                  </td>
                  <td style="padding: 15px; font-family: Plus Jakarta Sans; font-size: 14px; color: #2b73d6; font-weight: bold;">
                    {{ $attendedRecord->endTime }}
                  </td>
                  <td style="padding: 15px; font-family: Plus Jakarta Sans; font-size: 14px; color: #2b73d6; font-weight: bold;">
                    {{ $total_time_attended }}
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        
         <!-- Main Container Table -->
         <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; padding: 0px;">
          <tr>

             <!-- Left Side: Mobile App Image -->
             <td align="center" valign="middle" width=335 height=246 style="padding: 0px;">
              <img src="http://democlient.top/email-template/email-1/Group-1163.png" alt="Mobile App Interface" width="335" style="display: block; border-radius: 50px;">
            </td>

            <!-- Right Side: Download Text and Buttons -->
            <td align="center" valign="middle" width="50%" style="padding: 20px 40px 20px 20px;">
              <h2 style="font-family: Plus Jakarta Sans; font-size: 18px; color:#000; font-style: normal; font-weight: 700; line-height: 150%; letter-spacing: 0.2px  ; #000; margin: 0 0 10px;">Confirm Attendance</h2>
              <p style="font-family: Plus Jakarta Sans; color: #121A26; font-size: 14px; font-style: normal; font-weight: 500; line-height: 150%; letter-spacing: 0.2px color: #666666; margin: 0 0 20px;">Please verify by clicking the <br>button below or using the <br>SifuTutor app.</p><br>
              <a href="{{ $agreePath }}"  style="background-color: #2969FF; color: white; margin: 20px 0px 0px 0px; padding: 15px 30px; font-size: 12px; font-family: Plus Jakarta Sans; font-weight: 700;  border: none; border-radius: 4px; cursor: pointer; line-height: 19.2px; font-style: normal  ;  text-decoration: none;">
                  Verify Attendance
           </a>
            </td>
          </tr>
        </table>
        <!-- End Main Container -->
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff;">
          <tr>
            <td align="center" valign="middle" style="margin: 0px 0px; padding: 30px 20px;">
              <h3 style="color: #121A26; text-align: center; font-family: Plus Jakarta Sans; font-size: 16px; font-style: normal; font-weight: 700; line-height: 150%; /* 21px */ letter-spacing: 0.2px;"></h3style>Your prompt action helps keep our records accurate and <br>our services running smoothly.</h3>
              <p style="color: #121A26; text-align: center; font-family: Plus Jakarta Sans; font-size: 12px; font-style: normal; font-weight: 500; line-height: 150%; /* 21px */ letter-spacing: 0.2px;"></pstyle>
                Thank you for choosing SifuTutor! </p>
            </td>
          </tr>
        </table>
        </table>

        
      </td>
    </tr>
  </table>

</body>
</html>
