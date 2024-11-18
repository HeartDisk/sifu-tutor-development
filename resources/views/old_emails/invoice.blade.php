<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Invoice</title>
</head>
<body>
<p>Dear Parents/Guardians,</p>

<p>We hope you are having a great day! Here are some details for your latest invoice:</p>

<p>
    1) This invoice is computer-generated and no signature is required.
    <br>2) Payment is due within 3 working days of issuance of this invoice.
    <br>3) You can conveniently pay online via banking by clicking the "PAY NOW" button, or you can transfer to the
    account below:
    <br><br>
    MAYBANK - 562115516678 <br> SIFU EDU & LEARNING SDN BHD
    <br><br>
    Good news! If you pay in advance for 3 months of home or online tuition, you'll enjoy a 10% discount.
</p>

<form method="post" action="https://payment.ipay88.com.my/ePayment/entry.asp" id="makePaymentForm"
      name="makePaymentForm" novalidate="novalidate">
    <input type="hidden" name="MerchantCode" value="M28937">
    <input type="hidden" name="RefNo" value="ST117226">
    <input type="hidden" name="Amount" id="Amount" value="1030.00">
    <input type="hidden" name="Currency" value="MYR">
    <input type="hidden" name="ProdDesc" value="December 2023 - ST117226">
    <input type="hidden" name="UserName" value="Wan Noriza">
    <input type="hidden" name="UserEmail" value="kashiza82@yahoo.com">
    <input type="hidden" name="UserContact" value="60195704303">
    <input type="hidden" name="Remark">
    <input type="hidden" name="Lang" value="UTF-8">
    <input type="hidden" name="SignatureType" value="SHA256">
    <input type="hidden" name="Signature" id="Signature"
           value="7dccc25ade1ff65c02724810a63c6155ca3f3b88753aea698bd6ec95c8145b5f">
    <input type="hidden" name="ResponseURL"
           value="https://portal.sifututor.my/Public/Invoices/RedirectPaymentStatus?invoiveReferenceNo=ST117226&amp;token=ad2y7S11Tl2">
    <input type="hidden" name="BackendURL"
           value="https://portal.sifututor.my/Public/Invoices/ConfirmPaymentStatus?invoiveReferenceNo=ST117226&amp;token=ad2y7S11Tl2">

    <label for="PaymentId">Choose Payment Method:</label>
    <select class="form-control valid" style="max-width:500px;" id="PaymentId" name="PaymentId" aria-required="true">
        <option value=""></option>
        <option value="2">Credit Card (MYR)</option>
        <option value="6">Maybank2U</option>
        <option value="8">Alliance Online</option>
        <option value="10">AmOnline</option>
        <option value="14">RHB Online</option>
        <option value="15">Hong Leong Online</option>
        <option value="20">CIMB Click</option>
        <option value="31">Public Bank Online</option>
        <option value="102">Bank Rakyat Internet Banking</option>
        <option value="103">Affin Online</option>
        <option value="124">BSN Online</option>
        <option value="134">Bank Islam</option>
        <option value="152">UOB</option>
        <option value="166">Bank Muamalat</option>
        <option value="167">OCBC</option>
        <option value="168">Standard Chartered Bank</option>
        <option value="198">HSBC Online Banking</option>
        <option value="199">Kuwait Finance House</option>
        <option value="210">Boost Wallet</option>
    </select>
    <button class="btn btn-primary btn-paynow waves-effect waves-light" id="Paynow" type="submit">Pay Now</button>
</form>

<p class="font-bold" style="font-size:16px">
    Pay in advance 3 months of home or online tuition and enjoy 10% discount
</p>
<a class="btn btn-pay-threemonth btn-primary waves-effect waves-light" id="GetDiscount">Pay 3 months</a>

<p>
    Copyright © 2024 All Rights Reserved.
</p>
</body>
</html>
