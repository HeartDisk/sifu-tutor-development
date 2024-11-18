<!DOCTYPE html>
<html>
<head>
    <title>Invoice Paid</title>
</head>
<body>
<div class="card">
    <div class="nk-invoice">
        <div class="nk-invoice-head flex-column flex-sm-row">
            <div class="nk-invoice-head-item mb-3 mb-sm-0">
                <div class="nk-invoice-brand mb-1">
                    <h1>SifuTutor</h1>
                </div>
            </div>
            <div class="nk-invoice-head-item text-sm-end">
                <div class="h3">Invoices No: {{ $invoiceDetail->reference }}</div>
                <div class="h3">Invoices Date: {{ $invoiceDetail->invoiceDate }}</div>
            </div>
        </div>
        <div class="nk-invoice-head flex-column flex-sm-row">
            <table class="table table-responsive no-border">
                <tbody>
                <tr>
                    <td><strong>Payer Name: </strong> {{ $invoiceDetail->payerName }}</td>
                </tr>
                <tr>
                    <td><strong>Payer Email: </strong> {{ $invoiceDetail->payerEmail }}</td>
                </tr>
                <tr>
                    <td><strong>Payer Phone Number: </strong> {{ $invoiceDetail->payerPhone }}</td>
                </tr>
                <tr>
                    <td><strong>Paid Amount: </strong> {{ $invoiceDetail->invoiceTotal }} RM</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
