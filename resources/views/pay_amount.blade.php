<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<a href="{{url('/pay_amount')}}">支付0.1元</a><br>
<p>  </p>
<a href="{{url('/pay_amount_wep')}}">手机支付0.1元</a>
<a href="{{url('/wechat_scanning')}}">微信扫码支付</a>
<a href="{{url('/wechat_h5')}}">微信h5手机支付</a>
<p><a href="{{url('/union_pay')}}">银联支付</a></p>
<p><a href="{{url('/union_refund')}}">银联退款</a></p>
</body>
</html>