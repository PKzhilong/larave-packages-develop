<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=j">
    <title>Document</title>
</head>
<body>
<form action="{{url('/refund')}}" method="post">
    {{csrf_field()}}
    <input type="text" name="refund_reason" id="" value="">
    <input type="text" name="refund_amount" id="" value="{{$refund_amount}}">
    <input type="text" name="out_trade_no" id="" value="{{$out_trade_no}}">
    <input type="text" name="trade_no" id="" value="{{$trade_no}}">
    <input type="submit" value="退款">
</form>
</body>
</html>