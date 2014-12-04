<?php
    require_once('./inc/lightapp_login_api.inc.php');
?>

<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>百度登录支付Demo</title>
    <link rel="stylesheet" type="text/css" href="./style/lib.css">
    <link rel="stylesheet" type="text/css" href="./style/shop.css">
    <script src="http://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

    //注意 应用首页代码,从该轻应用-》状态信息-》编辑中获取，用于提交轻应用时验证用。
    <script type="text/javascript" name="baidu-tc-cerfication" data-appid="2546793" src="http://apps.bdimg.com/cloudaapi/lightapp.js"></script>

    <style>
        #shopmenu_list {
            padding-bottom: 0;
        }

        .notice-container{
            position: absolute;
            z-index: 20;
            bottom : 50px;
            left: 0;
            width: 100%;
            padding: 5px;
        }

        .notice-container .notice{
            background-color: #26bf85;
            font-size: 18px;
            color:white;
            border-radius: 10px;
            padding: 10px;
        }

        .log-container{
            position: absolute;
            z-index: 20;
            top: 130px;
            left: 0;
            width: 100%;
            padding: 5px;
        }

        .log-container .log {
            background-color: #26bf85;
            font-size: 18px;
            color:white;
            border-radius: 10px;
            padding: 10px;
        }

        div#shopmenu_cart_bar{
            z-index: 21;
        }

        ul.list-group{
            background-color: #fff!important;
        }

        div#shopmenu_list{
            border-left:1px solid #CFCFCF;
        }

        /*div#goToConfirm{*/
            /*padding-top: 10px;*/
            /*font-size: 20px;*/
        /*}*/

        .list-group > li{
            border-bottom: #d4d4d4 solid 1px;
        }

        .list-group > li a{
            font-size: 14px;
        }

    </style>
</head>
<body>
<div id="wrapper">
    <div id="common_widget_nav" class="common-widget-nav -shadow-card -bg-normal -vcenter">
        <div jsaction="click_title" class="title -ft-large -ft-secondary">
            <span>百度登录支付Demo</span>
        </div>
    </div>
    <div class="row shopmenu">
        <div id="shopmenu_category" class="sticky-a" style="top: 40px; height:200px;">
            <ul class="list-group">
                <li data-index="0">
                    <a href="" class="active">精品盖饭</a>
                </li>
            </ul>
        </div>
        <div id="shopmenu_list" class="-bg-lighter">
            <ul class="listgroup" id="a_category_0">
                <li class="list-item item-img">
                    <div class="wrap">
                        <div class="list-content">
                            <div class="-ft-middle -ft-primary" id="goods_name">麻婆豆腐饭</div>
                            <p class="-ft-tertiary">
                                已售 <strong class="-ft-brand">29</strong>
                                份
                            </p>

                            <p class="ft-orange">¥<label id="unit_amount">10</label></p>
                        </div>
                    </div>
                    <div class="item-add -border-round show-all">
                        <button id="btn_minus" class="btn -small btn-minus">
                            <i class="icon -minus"></i>
                        </button>
                        <span class="item-count" id="unit_count">1</span>
                        <button id="btn_plus" class="btn -small btn-plus">
                            <i class="icon -plus"></i>
                        </button>
                    </div>
                    <div style="display: none" id="goods_desc">麻婆豆腐饭</div>
                    <div style="display: none" id="goods_url">http://xx.com</div>
                </li>
            </ul>
        </div>
    </div>
    <div id="shopmenu_cart" class="-ft-large hide-cart-list">
        <div style="display: none" id="transport_amount">0</div>
        <div id="shopmenu_cart_bar" class="container -ft-lighter row"><em class="cart-count"
                                                                          style="visibility: visible;">1</em>

            <div class="row-cart icon -cart -large -mr-large">共&nbsp;¥<label id="total_amount">10</label>&nbsp;元</div>
            <div class="row-status -col4" data-node="cartStatus" id="goToConfirm">选好了</div>
        </div>
    </div>
</div>
<div class="notice-container">
    <div class="notice">
        用户点击<label style="color:red">"选好了"</label>之后，首先将进行百度账号登录操作，登录成功后会将以上订单信息传递到订单确认页面。
    </div>
</div>

<div class="log-container">
    <div class="log">

    </div>
</div>
<script type="text/javascript">
    //初始化，使用任何Clouda API前必须调用
    clouda.lightInit({
        ak: "<?=$lightapp_api_key?>",
        module: ["account", "pay"]
    });



    //登陆成功后的处理函数
    function onSuccess() {
        //登录成功后关闭登录的浮层
        clouda.mbaas.account.closeLoginDialog();

        //将菜品信息写入cooki
        writeCookie();
        window.location.href = "order_confirm.php";
    }

    //登陆页面点击“回退”后的处理函数
    function onFail() {
        alert('没有登录成功！');
    }

    function login() {
        //使用轻应用登陆接口登陆
        clouda.mbaas.account.login({
            //配置授权后的回跳地址，通常在此地址页中处理账号信息
            redirect_uri: location.href.replace(/(.*\/).*/, "$1")+"login_redirect.php",
            scope: 'basic',
            display: 'mobile',
            login_mode: 1,
            state: 'state',
            onsuccess: onSuccess,
            onfail: onFail
        });
    }

    $(function () {

        var isShoppingReady = true;

        initInfoFromCookie();
        $('.log').html(getParameters());
        $('#goToConfirm').click(function () {
            if(isShoppingReady){
                login();
            }else{
                alert("购物车内没有商品！");
            }

        });

        $('#btn_plus').click(function () {
            var currentCount = $('#unit_count').html();
            $('#unit_count').html(parseInt(currentCount) + 1);
            updateShoppingCart();
            $('.log').html(getParameters());
        });

        $('#btn_minus').click(function () {
            var currentCount = $('#unit_count').html();
            if (parseInt(currentCount) !== 0) {
                $('#unit_count').html(parseInt(currentCount) - 1);
                updateShoppingCart();
                $('.log').html(getParameters());
            }
        });

        function updateShoppingCart() {
            var currentCount = $("#unit_count").html();
            if(parseInt(currentCount) > 0){
                $('#goToConfirm').removeClass('cb-disable');
                isShoppingReady = true;
            }else{
                $('#goToConfirm').addClass('cb-disable');
                isShoppingReady = false;
            }
            var unitPrice = $("#unit_amount").html();
            $(".cart-count").html($("#unit_count").html());
            var totalPrice = parseInt(currentCount) * parseInt(unitPrice);
            $("#total_amount").html(totalPrice);
        }

        function initInfoFromCookie() {
            $(".cart-count").html($.cookie('unit_count'));
            $('#unit_count').html($.cookie('unit_count'));
            $('#total_amount').html($.cookie('total_amount'));
        }
    });


    function writeCookie() {
        $.removeCookie('goods_name');
        $.removeCookie('unit_count');
        $.removeCookie('unit_amount');
        $.removeCookie('goods_desc');
        $.removeCookie('goods_url');
        $.removeCookie('transport_amount');
        $.removeCookie('total_amount');
        $.cookie('goods_name', $('#goods_name').html(), { expires: 1 });
        $.cookie('unit_count', $('#unit_count').html(), { expires: 1 });
        $.cookie('unit_amount', $('#unit_amount').html(), { expires: 1 });
        $.cookie('goods_desc', $('#goods_desc').html(), { expires: 1 });
        $.cookie('goods_url', $('#goods_url').html(), { expires: 1 });
        $.cookie('transport_amount', $('#transport_amount').html(), { expires: 1 });
        $.cookie('total_amount', $('#total_amount').html(), { expires: 1 });
    }

    function getParameters() {
        var log = "页面传递的参数key-value如下:"+'<br>';
        log += 'goods_name: ' + $('#goods_name').html() + '<br>';
        log += 'unit_count: ' + $('#unit_count').html() + '<br>';
        log += 'unit_amount: ' + $('#unit_amount').html() + '<br>';
        log += 'goods_desc: ' + $('#goods_desc').html() + '<br>';
        log += 'goods_url: ' + $('#goods_url').html() + '<br>';
        log += 'transport_amount: ' + $('#transport_amount').html() + '<br>';
        log += 'total_amount: ' + $('#total_amount').html() + '<br>';
        return log;
    }
</script>
</body>
</html>