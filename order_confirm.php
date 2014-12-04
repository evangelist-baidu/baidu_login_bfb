<?php

require_once('./bfbsdk/bfb_sdk.php');
require_once('./bfbsdk/bfb_pay.cfg.php');
require_once('./inc/lightapp_login_api.inc.php');

if (!defined("SERVER_ROOT")) {
    define("SERVER_ROOT", str_replace('order_confirm.php','',"http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']));
}

//接收并配置支付所必须的请求参数
$order_create_time = date("YmdHis");
$expire_time = date('YmdHis', strtotime('+2 day'));
$order_no = $order_create_time . sprintf ( '%06d', rand(0, 999999));
$good_name_utf8 = $_COOKIE['goods_name'];
$good_desc_utf8 = $_COOKIE['goods_desc'];
$goods_url = $_COOKIE['goods_url'];


//参数中 商品单价，总金额，运费 以分为单位，实际使用中需要换算成“分”
//$unit_amount = intval($_COOKIE['unit_amount'])*100;
//$transport_amount = intval($_COOKIE['transport_amount'])*100;
//$total_amount = intval($_COOKIE['total_amount'])*100;
//$unit_count = $_COOKIE['unit_count'];

//演示demo统一支付1分钱
$unit_count="1";
$unit_amount="1";
$transport_amount="0";
$total_amount= intval($unit_count)*intval($unit_amount);

$buyer_sp_username_utf8 = $_COOKIE['bd_username'];
$page_url = SERVER_ROOT."/pay_redirect_uri.php";
//$pay_type = $_POST['pay_type'];
$pay_type = "2";
$bank_no = "";
$extra = "";

$return_url = SERVER_ROOT."/pay_return_url.php";

//接口中针对“包含中文的字段：goods_name、goods_desc、buyer_sp_username”，需要进行GBK编码
$good_name = iconv("UTF-8", "GBK", urldecode($good_name_utf8));
$buyer_sp_username = iconv("UTF-8", "GBK", urldecode($buyer_sp_username_utf8));
$good_desc = iconv("UTF-8","GBK",urldecode($good_desc_utf8));

$params = array (
		'service_code' =>
sp_conf::BFB_PAY_INTERFACE_SERVICE_ID,
		'sp_no' => sp_conf::SP_NO,
		'order_create_time' => $order_create_time,
		'order_no' => $order_no,
		'goods_name' => $good_name,
		'goods_desc' => $good_desc,
		'goods_url' => $goods_url,
		'unit_amount' => $unit_amount,
		'unit_count' => $unit_count,
		'transport_amount' => $transport_amount,
		'total_amount' => $total_amount,
		'currency' => sp_conf::BFB_INTERFACE_CURRENTCY,
		'buyer_sp_username' => $buyer_sp_username,
		'return_url' => $return_url,
		'page_url' => $page_url,
		'pay_type' => $pay_type,
		'bank_no' => $bank_no,
		'expire_time' => $expire_time,
		'input_charset' => sp_conf::BFB_INTERFACE_ENCODING,
		'version' => sp_conf::BFB_INTERFACE_VERSION,
		'sign_method' => sp_conf::SIGN_METHOD_MD5,
		'extra' =>$extra
);


//利用百付宝sdk生成请求链接，其中有对参数进行排序和签名。
$bfb_sdk = new bfb_sdk();
$order_url = $bfb_sdk->create_baifubao_pay_order_url($params, sp_conf::BFB_PAY_WAP_DIRECT_URL);

// $order_url = $bfb_sdk->create_baifubao_pay_order_url($params, sp_conf::BFB_PAY_DIRECT_LOGIN_URL);

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
	<title>订单确认</title>

	<link rel="stylesheet" type="text/css" href="./style/lib.css">
	<link rel="stylesheet" type="text/css" href="./style/order.css">
	<script src="http://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
	<script src="http://cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

    //注意 应用首页代码,从该轻应用-》状态信息-》编辑中获取，用于提交轻应用时验证用。
    <script type="text/javascript" name="baidu-tc-cerfication" data-appid="2546793" src="http://apps.bdimg.com/cloudaapi/lightapp.js"></script>

	<script type="text/javascript">
            //初始化，使用任何Clouda API前必须调用
            clouda.lightInit({
                ak: "<?=$lightapp_api_key?>",
            	module:["account","pay"]
            });

            function payInit(){
            	var options = {};
                //只有在百度app环境下，且轻应用已经上线，onsuccess和onfail方法有效。
                options.onsuccess = function(data){alert("ok")};
            	options.onfail = function(data){alert("false")};

                //初始化支付接口，传入商户号码SP_NO
            	clouda.mbaas.pay.init("<?php echo sp_conf::SP_NO ?>", options);
            }

			function dopay(orderInfo){

			        payInit();
                    //支付成功回调
					var successCallback = function(resultText) {
						alert(resultText);
					};
                    //支付失败回调
					var errorCallback  = function(code){
						statcode = code.split(";")[0].split("{")[1].split("}")[0];

						if(statcode == 2){
							alert("您已取消支付");
						}
						alert("error dopay, errcode = " + code);
					};
					var options = {};
					options.orderInfo = orderInfo;
					options.onsuccess = successCallback;
					options.onfail = errorCallback;

                    //传入支付配置，调起支付
					clouda.mbaas.pay.doPay(options);

			}
		</script>

	<style>
		#orderconfirm .cart-info{
		    margin-bottom: 0px !important;
		}

		div#wrapper{
		    padding-bottom:60px;
		    overflow:scroll;
		}
		.totalamt-container{
		    padding-top:5px;
		    padding-bottom:5px;
		}
		.order-title{
		    padding-right:40px;
		}

		.notice {
            background-color: rgb(255, 0, 61) !important;
            color: white;
        }

        .params {
            background-color: rgb(0, 71, 255) !important;
            color: white;
        }

        .btn-primary{
            background-color: #26bf85;
            color:white;
            border-radius:10px;
            height:35px;
            width:50%;
            margin-left:0 !important;
        }

        .btn-cancel{
            background-color: rgb(255, 0, 61);
            color:white;
            border-radius:10px;
            height:35px;
            width:50%;
            margin-left:0 !important;
        }

        .confirm-alert-button .btn{
            width:49%;
        }

        .alert-close{
            display:none;
        }

        .confirm-alert-title{
            font-size:18px;
            padding-bottom:10px;
            padding-top:10px;

        }

        .confirm-alert{
            position:absolute;
            left:0;
            top:250px;
            border: 1px solid grey;
            border-radius:10px;
            background-color:white !important;
        }

        .btn-alert{
            padding-bottom:10px;
            padding-top:10px;
            margin-bottom:10px;
        }

        #btn_ok{
            background-color: rgb(59, 214, 20) !important;
            color:white;
        }

        #btn_cancel{
            background-color: rgb(214, 28, 20) !important;
            color:white;
        }

	</style>

</head>
<body>
	<div id="wrapper">
		<div id="common_widget_nav" class="common-widget-nav -shadow-card -bg-normal -vcenter">
			<a jsaction="back" class="btn -ft-secondary" href="index.php"> <i class="icon -back-arrow"></i>
			</a>
			<div jsaction="click_title" class="title -ft-large -ft-secondary order-title">
				<span>
					用户名:
					<?php  echo  $buyer_sp_username_utf8;  ?></span>
			</div>
		</div>
		<div id="orderconfirm" class="container -layout">

			<div class="card -bg-lighter -layout">
				<ul class="cart-list">
					<li class="container -large-v -border cl-item row">
						<div class="cl-r1">
							<div id="good_name"></div>
							<p class="ft-orange">
								¥
								<label id="unit_amount"></label>
							</p>
						</div>
						<div class="cl-r2 -col4">
							<div>
								<span>
									<label id="unit_count"></label>
									份
								</span>
							</div>
						</div>
					</li>
				</ul>
			</div>

			<div class="notice card -layout -base-v -bg-lighter recevie-info">此处为订单结算页面，在用户点下'确认下单'后，将会跳转到支付页面。</div>
			<div class="confirm-alert alert-close container -layout -bg-light">
			     <div class="confirm-alert-title">注意：即将向百付宝测试商户号支付1分钱，钱款不能退还，是否继续支付？</div>
			     <div class="confirm-alert-button">
			          <button id="btn_ok" class="btn btn-alert">我要支付</button>
                      <button id="btn_cancel" class="btn btn-alert">残忍拒绝</button>
			     </div>
			</div>
			<div class="params card -layout -base-v -bg-lighter recevie-info">
				支付接口需要传递的参数如下：
				<br>
				<br>
				<p>
					service_code(服务编号):
					<?php echo $params['service_code'] ?></p>
				<p>
					sp_no(百度钱包商户号):
					<?php echo $params['sp_no'] ?></p>
				<p>
					order_create_time(创建订单的时间):
					<?php echo $params['order_create_time'] ?></p>
				<p>
					order_no(订单号):
					<?php echo $params['order_no'] ?></p>
				<p>
					goods_name(商品的名称):
					<?php echo $good_name_utf8 ?></p>
				<p>
					goods_desc(商品的描述信息):
					<?php echo $good_desc_utf8 ?></p>
				<p>
					total_amount(总金额，以分为单位):
					<?php echo $params['total_amount'] ?></p>
				<p>
					currency(币种:人民币):
					<?php echo $params['currency'] ?>
					<br>
					<p>
						return_url(通知商户支付结果的URL):
						<?php echo $params['return_url'] ?></p>
					<p>
						pay_type(支付方式:默认2网上支付):
						<?php echo $params['pay_type'] ?></p>
					<p>
						input_charset(字符编码:GBK):
						<?php echo $params['input_charset'] ?></p>
					<p>
						version(接口的版本号):
						<?php echo $params['version'] ?></p>
					<p>sign(签名结果): 参数排序后计算MD5</p>
					<p>
						sign_method(签名方法):
						<?php echo $params['sign_method'] ?></p>
				</div>

				<div class="cart-confirm container -layout -bg-light">
					<div class="container totalamt-container">
						<span class="totalamt">
							共￥
							<label id="total_amount"></label>
							元
						</span>
						<span
                    class="-ft-secondary">&nbsp;(含配送费/餐盒费)</span>
					</div>
					<div class="row -ft-lighter -ft-large">
						<button data-node="submitBtn" class="submit-btn btn row-status -border-round" id="goToPay">确认下单</button>
					</div>
				</div>
				<div></div>
			</div>
		</div>
	<script>
	$(function(){
	    $('#goToPay').click(function(){
            $('.confirm-alert').removeClass('alert-close');
	    });

	    $('#btn_ok').click(function(){
            dopay('<?php echo $order_url; ?>');
	    });

	    $('#btn_cancel').click(function(){
            $('.confirm-alert').addClass('alert-close');
        });

    	$('#good_name').html($.cookie('goods_name'));
    	$('#unit_amount').html($.cookie('unit_amount'));
    	$('#unit_count').html($.cookie('unit_count'));
    	var totalPrice = parseInt($('#unit_amount').html()) * parseInt($('#unit_count').html());
    	$('#total_amount').html(totalPrice);
	});
	</script>
</body>

</html>