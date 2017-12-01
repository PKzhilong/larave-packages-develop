<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LeWaiJiao\Src\Methods\H5\Alipay\AlipayWap;
use LeWaiJiao\Src\Methods\Web\Wechat\WeChatScanning;
use LeWaiJiao\Src\Payments;
use LeWaiJiao\Src\Methods\Web\Alipay\AlipayWeb;
use Log;

class PayController extends Controller
{
    public $paymentObj;
    public $out_trade_no;
    public $trade_no;
    public $refund_amount;

    public function __construct(Payments $payments)
    {
        $this->paymentObj = $payments;
    }
    //
    public function index()
    {
        return view('pay_amount');
    }

    public function payAmount()
    {
        $payment = new AlipayWeb([
            'total_amount' => 0.1,
            'subject' => '测试用例',
            'body'      => '大宅世家资金托管-1',
        ], route('pay_redirect_url'), route('pay_notify_url'));
        $this->paymentObj->payInstance($payment)->pay();
    }

    public function refundShow(Request $request)
    {
        $payment = new AlipayWap([
            'out_trade_no' => $request->out_trade_no,
            'trade_no' => $request->trade_no,
            'refund_amount' => $request->refund_amount,
            'refund_reason' => $request->refund_reason
        ], route('pay_redirect_url'), route('pay_notify_url'));
       $result = $this->paymentObj->payInstance($payment)->refund();
       dd($result);
    }

    public function redirectShow(Request $request)
    {
        $out_trade_no = $request->out_trade_no;
        $refund_amount = $request->total_amount;
        $trade_no = $request->trade_no;
        return view('refund')->with(compact('out_trade_no', 'refund_amount', 'trade_no'));
    }


    public function notifyLog(Request $request)
    {
        Log::info($request->all());
    }


    public function mobliePay()
    {
        $payment = new AlipayWap([
            'total_amount' => 0.1,
            'subject' => '测试用例',
            'body'      => '大宅世家资金托管-1',
        ], route('pay_redirect_url'), route('pay_notify_url'));

        $result = $this->paymentObj->payInstance($payment)->pay();
    }

    public function orderCheck()
    {
        $pay = new AlipayWeb(['trade_no' => 2017113021001004900598603704, 'out_trade_no' => 'zBePQKccmp0GXHogNfWD7sMSnazCashY']);
        $info = $this->paymentObj->payInstance($pay)->orderCheck();
        dd($info);
    }

    public function weChatPay()
    {
        $pay = new WeChatScanning([
            'total_fee'  => 1,
            'body'       => '大宅世家',
        ], '/redirect_url', '/notify_url');
        $url = $this->paymentObj->payInstance($pay)->pay();
        dd($url);
    }

    public function weChatInfo(Request $request)
    {
        Log::info($request->all());
    }
}

