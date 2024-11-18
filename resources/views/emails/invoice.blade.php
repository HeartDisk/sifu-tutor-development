<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Template</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
</head>
<body style="margin: 0; padding: 0; background-color: #f2f2f2;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 0px; padding: 0px 30px;">
    <tr>
      <td align="center" style="background-color: #f2f2f2; padding: 0px 40px;">
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff;">
          <tr>
            <td align="left" valign="middle" background="https://democlient.top/email-template/email-1/Group-1294.png" style="background-size: cover; background-position: center; height: 300px;">
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <!-- Right side: Logo and Text -->
                  <td align="left" valign="top" style="padding: 40px 40px 40px 40px;">
                    <img src="https://democlient.top/email-template/email-1/logo.png" alt="SifuTutor Logo" style="display: block; width: 120px;">
                    <h1 style="font-family: Plus Jakarta Sans; color: #fff;  font-size: 23px; font-style: normal; font-weight: 600; line-height: 29px; margin: 40px 0 20px 0px;">Invoice for Recent<br> Tutoring Sessions</h1>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
           <table border="0" cellpadding="0" cellspacing="0" width="600" style="padding: 20px; background-color: #ffffff;">
            <tr>
              <td align="center" valign="middle" style="margin: 0px 0px; padding: 20px 20px 0px 20px;">
                <p style="color: #4E4E4E; text-align: center; font-family: Plus Jakarta Sans; font-size: 15px; font-style: normal; font-weight: 700; line-height: 150%; /* 21px */ letter-spacing: 0.2px;">Dear {{ $parentName }},</p>
                <p style="color: #4E4E4E; text-align: center; font-family: Plus Jakarta Sans; font-size: 15px; font-style: normal; font-weight: 500; line-height: 150%; /* 21px */ letter-spacing: 0.2px;">
                    Please find below the invoice for your child's recent tutoring sessions with SifuTutor. We appreciate your prompt attention.</p>
              </td>
            </tr>
           </table>
           <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; padding: 4%;">
             <tr>
               <td align="center" valign="middle" style="margin: 0px 0px; padding: 0px 20px 20px 20px;">
                 <h1 style="color: #000; text-align: center; font-family: Plus Jakarta Sans; font-size: 18px; font-style: normal; font-weight: 700; line-height: 150%; /* 21px */ letter-spacing: 0.2px;">Invoice Details</h1>
               </td>
             </tr>
            <tr>
            <td align="center" valign="middle" style="padding: 10px; border: 1px solid #E0E0E0; border-radius: 10px;  ">
              <table style="width: 100%; border-collapse: collapse;  border-radius: 10px;  ">
                <thead>
                    <tr>
                        <th style="background-color: #E1ECFF; padding: 20px; text-align: left; font-size: 12px; font-weight: 600; line-height: 18px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans; border-radius: 5px 0px 0px 5px;">Description</th>
                        <th style="background-color: #E1ECFF; padding: 20px; text-align: right; font-size: 12px; font-weight: 600; line-height: 18px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans; border-radius: 0px 5px 5px 0px;">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; font-size: 12px;  font-weight: 400; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans; ">Request Ticket ID</td>
                        <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; text-align: right; font-size: 12px;  font-weight: 700; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans;">{{ $ticketId }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; font-size: 12px;  font-weight: 400; line-height: 12px; letter-spacing: 0.2px;font-family: Plus Jakarta Sans;">Student Name</td>
                        <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; text-align: right; font-size: 12px;  font-weight: 700; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans;">
                            {{ $studentName }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; font-size: 12px;  font-weight: 400; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans; ">Subject</td>
                        <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; font-family: Plus Jakarta Sans; font-size: 12px;  font-weight: 700; line-height: 12px; letter-spacing: 0.2px; text-align: right;">{{ $subjectName }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; font-size: 12px;  font-weight: 400; line-height: 12px; letter-spacing: 0.2px;font-family: Plus Jakarta Sans;">Price Per Hour</td>
                        <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; text-align: right;font-size: 12px;  font-weight: 700; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans;">RM{{ $pricePerHour }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; font-size: 12px;  font-weight: 400; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans;">Total Hour</td>
                        <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; text-align: right; font-size: 12px;  font-weight: 700; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans;">{{ $totalHours }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; font-size: 12px;  font-weight: 400; line-height: 12px; letter-spacing: 0.2px;   font-family: Plus Jakarta Sans;">Date of Class</td>
                        <td style="padding: 20px; border-bottom: 0 solid #E0E0E0; text-align: right;font-size: 12px;  font-weight: 700; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans;">{{ $classDate }}</td>
                    </tr>
                    <tr>
                        <th style="background-color:  #1A2CB9;  color: #fff; padding: 20px; text-align: left; font-size: 12px; font-weight: 600; line-height: 18px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans; border-radius: 5px 0px 0px 5px;">Total Amount Due</th>
                        <th style="background-color: #1A2CB9; color: #fff; padding: 20px; text-align: right; font-size: 12px; font-weight: 600; line-height: 18px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans; border-radius: 0px 5px 5px 0px;">RM{{ $totalAmount }}</th>
                    </tr>
                </tbody>
            </table>
            </td>
            </tr>
           </table>
           <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; padding: 30px 0px 0px 0px;">
            <tr>
              <td align="center" valign="middle" width="50%" style="padding: 20px 40px 20px 20px;">
              <p style="font-family: Plus Jakarta Sans; color: #121A26; font-size: 14px; font-style: normal; font-weight: 500; line-height: 150%; letter-spacing: 0.2px color: #666666; margin: 0 0 20px;">You can make the payment using the link below or via the SifuTutor app.</p> <br>
              <a href="#"  style="background-color: #2969FF; color: white; margin: 20px 0px 0px 0px; padding: 15px 30px; font-size: 12px; font-family: Plus Jakarta Sans; font-weight: 700;  border: none; border-radius: 4px; cursor: pointer; line-height: 19.2px; font-style: normal  ;  text-decoration: none;">
                 Pay Now
             </a>
              </td>
            </tr>
           </table>
          <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff;">
            <tr>
              <td align="center" valign="middle" style="margin: 0px 0px; padding: 30px 20px;">
                <p style="color: #121A26; text-align: center; font-family: Plus Jakarta Sans; font-size: 12px; font-style: normal; font-weight: 500; line-height: 150%; /* 21px */ letter-spacing: 0.2px;">If you have any questions, feel free to contact our support team.</p>
                <h3 style="color: #121A26; text-align: center; font-family: Plus Jakarta Sans; font-size: 16px; font-style: normal; font-weight: 700; line-height: 150%; /* 21px */ letter-spacing: 0.2px;">Thank you for your prompt payment and for being a <br>valued member of the SifuTutor community.</h3>
              </td>
            </tr>
          </table>
        </table>   
      </td>
    </tr>
  </table>
</body>
</html>
