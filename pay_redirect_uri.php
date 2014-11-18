<?php
//省略 验证订单支付，修改订单状态，

//支付回调字段
//bank_no:
//bfb_order_create_time:20140723175911
//bfb_order_no:2014072334000000011110151583036
//buyer_sp_username:(unable to decode value)
//currency:1
//extra:
//fee_amount:0
//input_charset:1
//order_no:20140723115904905955
//pay_result:1
//pay_time:20140723175916
//pay_type:1
//sign_method:1
//sp_no:3400000001
//total_amount:1
//transport_amount:0
//unit_amount:1
//unit_count:1
//version:2
//sign:aedba31a103b37cec5c0877e4b7856c6
$bfb_order_no = $_GET['bfb_order_no'];
$buyer_sp_username = $_GET['buyer_sp_username'];
$pay_result = $_GET['pay_result'];
$total_amount = $_GET['total_amount'];
$sp_no = $_GET['sp_no'];
$sign = $_GET['sign'];


?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>支付结果</title>

    <link rel="stylesheet" type="text/css" href="./style/lib.css">
    <link rel="stylesheet" type="text/css" href="./style/order.css"></head>
    <script src="http://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

<style>
    #orderconfirm .cart-info{
        margin-bottom: 0px !important;
    }

    div#wrapper{
        padding-bottom:80px;
        overflow:scroll;
    }
</style>

</head>
<body>
<div id="wrapper">
    <div id="common_widget_nav" class="common-widget-nav -shadow-card -bg-normal -vcenter">
        <div jsaction="click_title" class="title -ft-large -ft-secondary">
				<span>支付结果</span>
        </div>
    </div>
    <div id="orderconfirm" class="container -layout">

        <div class="card -layout -base-v -bg-lighter recevie-info">支付结果信息
            <p>商户号: <?php echo $sp_no ?></p>
            <p>订单号: <?php echo $bfb_order_no ?></p>
            <p>支付结果(1:支付成功,2:等待支付): <?php echo $pay_result ?></p>
            <p>总价格(单位:分): <?php echo $total_amount ?></p>
            <p>支付账户: <?php echo iconv("GBK", "UTF-8", urldecode($buyer_sp_username)); ?></p>
            <p>验证签名: <?php echo $sign ?></p>
        </div>

        <div class="cart-confirm container -layout -bg-light">
            <div class="row -ft-lighter -ft-large">
                <button data-node="submitBtn" class="submit-btn btn row-status -border-round" id="backToMain">返回主页</button>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    $(function(){
        $('#backToMain').click(function(){
            window.location.href = "index.php";
        });
    });

</script>

</html>