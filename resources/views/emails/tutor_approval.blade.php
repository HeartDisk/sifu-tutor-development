<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Email Template</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
</head>
<body style="margin: 0; padding: 0; font-family: Plus Jakarta Sans, sans-serif; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 5px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <div style="position: relative; width: 100%; overflow: hidden;">
            <img src="https://democlient.top/email-template/Tutor/Group-1334.png" alt="Banner Image" style="width: 100%; height: auto; display: block;">
            <img src="https://democlient.top/email-template/email-1/logo.png" alt="Logo" style="position: absolute; right: 45px; top: 30px; width: 100px; height: auto; border-radius: 5px;">
            <div style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: white; text-align: right; padding: 10px; max-width: 200px;">
                <p style="margin: -50px; margin-right: 0px; padding-top: 20px; align-content: right; padding-right: 10px; font-family: Plus Jakarta Sans; font-size: 23px; font-weight: 500; line-height: 29px;">Congratulations! Your Tutoring Job Application Was Successful!</p>
            </div>
        </div>
        <div style="padding: 20px;">
            <h3 style="text-align: center;">Hi {{ $tutorName }},</h3>
            <p style="text-align: center;">[Success/Update] Your application for the tutoring opportunity has been approved.</p>
        </div>
        <div style="width: 512px; height: 82px; margin: auto; display: flex; justify-content: space-between; padding: 10px; border: 1px solid #D4DDFF;">
            <div style="flex: 1; text-align: center; padding: 10px; display: flex; align-items: center;">
                <div style="display: flex; flex-direction: column; align-items: center;">
                    <p style="margin: 9px -30px 0px 0px; font-size: 10px;">Student Name</p>
                    <img src="https://democlient.top/email-template/Tutor/Ellipse-28.png" alt="Column Image" style="width: 27px; height: 27px; margin: 6px -1px 25px -10px;">
                </div>
                <p style="margin-left: -5px; font-size: 14px; padding-right: 30px; margin-bottom: 15px; font-weight: 700; color: #2969FF; white-space: nowrap;">{{ $studentName }}</p>
            </div>
            <div style="flex: 1; text-align: center; padding: 10px;">
                <p style="margin: 0; font-size: 10px; text-align: left;">Status</p>
                <div style="background-color: #D4DDFF; width: 101px; color: white; padding: 5px; border-radius: 27px; margin-top: 5px;">
                    <p style="margin: 0; font-size: 14px; font-weight: 700; color: #2969FF;">Approved</p>
                </div>
            </div>
            <div style="flex: 1; text-align: center; padding: 10px;">
                <p style="margin: 0; font-size: 10px; text-align: left;">Date</p>
                <p style="margin: 0; padding-top: 10px; font-size: 14px; font-weight: 700; color: #2969FF;">{{ $approvalDate }}</p>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; margin: 20px 20px;">
            <div style="flex: 1; padding: 20px; align-content: center; text-align: center; border-radius: 5px; margin-right: 10px;">
                <h4 style="margin: 0; color: black;">What’s Next?</h4>
                <p style="margin-top: 10px;font-size: 14px;font-weight: 500;font-family: Plus Jakarta Sans; color: #000;">You’re ready to begin! Make sure to review the guidelines to get started smoothly.</p>
                <!-- Button below the text -->
                <a href="#" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background-color: #2969FF; color: white; text-decoration: none; border-radius: 5px; font-size: 12px; font-weight: 700; font-family: Plus Jakarta Sans;">View Guidelines</a>
            </div>
            <div style="flex: 1; padding: 20px; background-color: #fff; border-radius: 5px; margin-left: 10px; text-align: center;">
                <img src="https://democlient.top/email-template/Tutor/Group-1322.png" style="max-width: 100%; height: auto; border-radius: 5px;">
            </div>
        </div>
        <div style="padding: 20px; text-align: center;">
            <p style="margin-top: 10px;font-size: 12px;font-weight: 400;font-family: Plus Jakarta Sans!important; color: #000;">Keep going, and we’re here to support you every step of the way!</p>
            <h4 style="margin: 0; font-size: 16px; font-family:Plus Jakarta Sans; font-weight:700; color: black;">Best,<br>The SifuTutor Team</h4>
        </div>
    </div>
</body>
</html>
