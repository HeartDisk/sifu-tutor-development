<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FiuuService
{
    protected $merchantId;
    protected $verifyKey;
    protected $secretKey;
    protected $environment;
    protected $baseUrl;

    public function __construct()
    {
        //Live Data
//        $merchantId="sifututormy";
//        $verifyKey="0e8e1bcae9c1b7fb5ec2ec618516ee00";
//        $secretKey="ba4ec04c6848535238266ffd3876433f";

        //Sandbox Data
        $merchantId = "SB_sifututormy";
        $verifyKey = "d6c62333ce3635c6fe0bdecf2c14e6cb";
        $secretKey = "9384cbd5a5ca375d7df68f03534bc496";

        $this->merchantId = $merchantId;
        $this->verifyKey = $verifyKey;
        $this->secretKey = $secretKey;
//        $this->baseUrl = 'https://pay.fiuu.com/RMS/pay/'.$merchantId;
        $this->baseUrl = 'https://sandbox.merchant.razer.com/RMS/pay/' . $merchantId;
    }

    public function getPaymentUrl($orderid, $amount, $bill_name, $bill_email, $bill_mobile, $bill_desc = 'RMS PHP Library',
                                  $channel = null, $currency = "MYR", $returnUrl = null,
                                  $callbackurl = null, $cancelurl = null)
    {
        $amount = number_format($amount, 2, '.', '');

        $data = [
            'orderid' => $orderid,
            'amount' => $amount,
            'bill_name' => $bill_name,
            'bill_email' => $bill_email,
            'bill_mobile' => $bill_mobile,
            'bill_desc' => $bill_desc,
            'channel' => $channel,
            'currency' => $currency,
            'returnurl' => $returnUrl,
            'callbackurl' => $callbackurl,
            'cancelurl' => $cancelurl,
            'vcode' => md5($amount . $this->merchantId . $orderid . $this->verifyKey . $currency),
        ];

        return $this->baseUrl . '?' . http_build_query($data);
    }

    public function verifySignature($paydate, $domain, $key, $appcode, $skey)
    {
        $checkVcode = md5($paydate . $domain . $key . $appcode . $this->secretKey);
        return $checkVcode === $skey;
    }
}
