<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receipt Payment</title>
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
                 <td align="left" valign="middle" background="https://democlient.top/email-template/email-1/Group-1295.png" style="background-size: cover; background-position: center; height: 300px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                       <tr>
                          <td align="left" valign="top" style="padding: 40px 40px 40px 40px;">
                             <img src="https://democlient.top/email-template/email-1/logo.png" alt="SifuTutor Logo" style="display: block; width: 120px;">
                             <h1 style="font-family: Plus Jakarta Sans; color: #fff;  font-size: 23px; font-style: normal; font-weight: 600; line-height: 29px; margin: 40px 0 20px 0px;">Payment <br>Received</h1>
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
                      We have received your payment for the following invoice. <br>Thank you for your prompt payment.</p>
                 </td>
              </tr>
           </table>
           <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; padding: 20px;">
              <tr>
                 <td align="center" valign="middle" style="margin: 0px 0px; padding: 0px 20px 20px 20px;">
                     <h1 style="color: #000; text-align: center; font-family: Plus Jakarta Sans; font-size: 18px; font-style: normal; font-weight: 700; line-height: 150%; /* 21px */ letter-spacing: 0.2px;">Receipt Details</h1>
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
                                <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; font-size: 12px;  font-weight: 400; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans; ">Invoice Number</td>
                                <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; text-align: right; font-size: 12px;  font-weight: 700; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans;">{{ $invoiceNumber }}</td>
                             </tr>
                             <tr>
                                <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; font-size: 12px;  font-weight: 400; line-height: 12px; letter-spacing: 0.2px;font-family: Plus Jakarta Sans;">Student Name</td>
                                <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; text-align: right; font-size: 12px;  font-weight: 700; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans;">
                                    {{ $studentName }}
                                </td>
                               </tr>
                               <tr>
                                <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; font-size: 12px;  font-weight: 400; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans; ">Date</td>
                               <td style="padding: 20px; border-bottom: 1px solid #E0E0E0; text-align: right; font-size: 12px;  font-weight: 700; line-height: 12px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans;">{{ $paymentDate }}</td>
                             </tr>
                             <tr>
                                <th style="background-color:  #1A2CB9;  color: #fff; padding: 20px; text-align: left; font-size: 12px; font-weight: 600; line-height: 18px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans; border-radius: 5px 0px 0px 5px;">Amount Paid</th>
                                <th style="background-color: #1A2CB9; color: #fff; padding: 20px; text-align: right; font-size: 12px; font-weight: 600; line-height: 18px; letter-spacing: 0.2px; font-family: Plus Jakarta Sans; border-radius: 0px 5px 5px 0px;">RM{{ $amountPaid }}</th>
                             </tr>
                        </tbody>
                     </table>
                 </td>
              </tr>
            </table>
           <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff;">
                <tr>
                    <td align="center" valign="middle" style="margin: 0px 0px; padding: 30px 20px;">
                       <h3 style="color: #121A26; text-align: center; font-family: Plus Jakarta Sans; font-size: 16px; font-style: normal; font-weight: 700; line-height: 150%; /* 21px */ letter-spacing: 0.2px;"> Your payment has been successfully processed, and<br> your account is up to date.</h3>
                       <p style="color: #121A26; text-align: center; font-family: Plus Jakarta Sans; font-size: 12px; font-style: normal; font-weight: 500; line-height: 150%; /* 21px */ letter-spacing: 0.2px;">Thank you for choosing SifuTutor. We’re committed to supporting your child’s<br> educational success.</p>
                    </td>
                </tr>
           </table>
           </table>
        </td>
      </tr>
    </table>
</body>
</html>
