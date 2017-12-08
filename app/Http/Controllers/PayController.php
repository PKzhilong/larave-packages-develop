<?php

namespace App\Http\Controllers;

use Core\Services\Payment\Payment;
use Illuminate\Http\Request;
use LeWaiJiao\Src\Methods\Web\Wechat\WeChatScanning;
use LeWaiJiao\Src\Payments;
use LeWaiJiao\Src\Methods\Web\Alipay\AlipayWeb;
use Log;
use PaymentMore;

class PayController extends Controller
{
    public $paymentObj;
    public $out_trade_no;
    public $trade_no;
    public $refund_amount;
    public $corePay;

    public function __construct(Payments $payments, Payment $corePay)
    {
        $this->paymentObj = $payments;
        $this->corePay = $corePay;
    }
    //
    public function index()
    {
        return view('pay_amount');
    }

    public function payAmount()
    {
        PaymentMore::payInstance('ALIPAY_WEB', [
            'total_amount' => 0.1,
            'subject' => '测试支付宝支付',
            'body'      => '大宅世家资金托管-2',
            'redirect_url' => route('pay_redirect_url'),
            'notify_url' => route('pay_notify_url')
        ])->pay();
//        $this->paymentObj->payInstance('ALIPAY_WEB', [
//            'total_amount' => 0.1,
//            'subject' => '测试支付宝支付',
//            'body'      => '大宅世家资金托管-2',
//            'redirect_url' => route('pay_redirect_url'),
//            'notify_url' => route('pay_notify_url')
//        ])->pay();
//        $this->paymentObj->payInstance($payment)->pay();
    }

    public function refundShow(Request $request)
    {
        $this->paymentObj->payInstance('ALIPAY_H5', [
            'out_trade_no' => $request->out_trade_no,
            'trade_no' => $request->trade_no,
            'refund_amount' => $request->refund_amount,
            'refund_reason' => $request->refund_reason,
            'redirect_url'  => route('pay_redirect_url'),
            'notify_url'    =>  route('pay_notify_url')
        ])->refund();
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
//        $payment = new AlipayWap([
//            'total_amount' => 0.1,
//            'subject' => '测试用例',
//            'body'      => '大宅世家资金托管-1',
//        ], route('pay_redirect_url'), route('pay_notify_url'));
        PaymentMore::payInstance('ALIPAY_H5', [
            'total_amount' => 0.1,
            'subject' => '测试用例',
            'body'      => '大宅世家资金托管-1',
            'redirect_url'  => route('pay_redirect_url'),
            'notify_url'    =>  route('pay_notify_url')
        ])->pay();
//        $result = $this->paymentObj->payInstance($payment)->pay();
    }

    public function orderCheck()
    {
        $pay = new AlipayWeb(['trade_no' => 2017113021001004900598603704, 'out_trade_no' => 'zBePQKccmp0GXHogNfWD7sMSnazCashY']);
        $info = $this->paymentObj->payInstance($pay)->orderCheck();
    }

    public function weChatPay()
    {
//        $pay = WeChatScanning::getInstall([
//            'total_fee'  => 1,
//            'body'       => '大宅世家',
//        ], '/redirect_url', '/notify_url');
//        $url = $this->paymentObj->payInstance($pay)->pay();
//        $codeUrl = $this->paymentObj->payInstance($pay)->pay();
//        $outTradeNo = $this->paymentObj->getOutTradeNo();

       $codeUrl = PaymentMore::payInstance('WECHAT_SCANNING', [
            'total_fee'  => 1,
            'body'       => '大宅世家',
            'redirect_url' => '/redirect_url',
            'notify_url'    => '/notify_url'
        ])->pay();
        $outTradeNo = PaymentMore::getOutTradeNo();
        return view('scanning')->with(compact('codeUrl', 'outTradeNo'));
    }

    public function checkOrder(Request $request)
    {
//        $pay = WeChatScanning::getInstall([
//            'out_trade_no' => 'WHbLpiKQ7F'
//        ]);
//        dump($this->paymentObj->payInstance($pay)->orderCheck());
       $result =  $this->paymentObj->payInstance('WECHAT_SCANNING', [
            'out_trade_no' => 'feFAVFWzUq'
        ])->orderCheck();

       dump($result);

        return view('refund_wechat');
    }

    public function refundWechat(Request $request)
    {
//        $pay = WeChatScanning::getInstall([
//            'total_fee'  => 1,
//            'out_trade_no' => $request->refundOrder,
//            'refund_no'     => 'laksjdlkfjljalkjslkjdf',
//            'refund_fee'    => 1
//        ]);
        $result =  $this->paymentObj->payInstance('WECHAT_SCANNING', [
            'total_fee'  => 1,
            'out_trade_no' => $request->refundOrder,
            'refund_no'     => 'laksjdlkfjljalkjslkj23232332f',
            'refund_fee'    => 1
        ])->refund();
//        $result = $this->paymentObj->payInstance($pay)->refund();
        return $result;
    }



    public function weChatInfo()
    {
        $pay = WeChatScanning::getInstall([]);
        $response = $this->paymentObj->payInstance($pay)->handleWeChatPayNotify(function($notify, $successful){
            // 你的逻辑
            Log::info($notify);
            return true; // 或者错误消息
        });
        return $response;
    }

    public function wechatH5()
    {
        $result = $this->paymentObj->payInstance('WECHAT_H5', [
            'total_fee'  => 1,
            'body'       => '大宅世家',
            'redirect_url' => '/redirect_url',
            'notify_url'    => '/notify_url',
            'trade_type' => 'MWEB',
            'scene_info' => [
                'h5_info' => [
                    'wap_name' => 'dazhai',
                    'wap_url'   => 'http://47.93.10.113:8880',
                    'type'      => 'Wap'
                ]
            ]
        ])->pay();
    }

    public function wechatH5Notify(Request $request)
    {
        Log::info($request->all());
    }

    public function unionPay()
    {
        $this->paymentObj->payInstance('UNIONPAY_WEB', [
//            'merId' => '898110282990609',		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
            'orderId' => time(),	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
            'txnTime' => date('YmdHis'),	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
            'txnAmt' => 1,	//交易金额，单位分，此处默认取demo演示页面传递的参数
            // 订单超时时间。
            // 超过此时间后，除网银交易外，其他交易银联系统会拒绝受理，提示超时。 跳转银行网银交易如果超时后交易成功，会自动退款，大约5个工作日金额返还到持卡人账户。
            // 此时间建议取支付时的北京时间加15分钟。
            // 超过超时时间调查询接口应答origRespCode不是A6或者00的就可以判断为失败。
            'backUrl' => 'http://47.93.10.113:8088/union_pay_backUrl',
            'frontUrl'  => 'http://47.93.10.113:8088/union_pay_frontUrl',
        ])->pay();
    }

    public function unionRefund()
    {
       $result = $this->paymentObj->payInstance('UNIONPAY_WEB', [
            'orderId' => time(),	    //商户订单号，8-32位数字字母，不能含“-”或“_”，可以自行定制规则，重新产生，不同于原消费，此处默认取demo演示页面传递的参数
            'origQryId' => '681712081012272717068', //原消费的queryId，可以从查询接口或者通知接口中获取，此处默认取demo演示页面传递的参数
            'txnTime' => date('YmdHis'),	    //订单发送时间，格式为YYYYMMDDhhmmss，重新产生，不同于原消费，此处默认取demo演示页面传递的参数
            'txnAmt' => 1,
            'backUrl' => 'http://47.93.10.113:8088/union_refund_backUrl'
        ])->refund();
       dd($result);
    }

    public function unionRefundNotify(Request $request)
    {
        Log::info($request->all());
    }

    public function unionFront(Request $request)
    {
        dd($request->all());
        exit;
    }

    public function unionBackUrl(Request $request)
    {
        Log::info($request->all());
        exit;
    }
}

