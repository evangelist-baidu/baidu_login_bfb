轻应用登录和百度钱包支付
===============
## Demo说明

Demo中包括百度轻应用登录和百度钱包支付两个功能。
其中百度钱包支付部分仅作为演示[Blend.mbaas.pay](http://cloudaplus.duapp.com/blendapi/cloud/api_document#轻支付)使用方法，对于百度钱包如何查询订单，验证参数等，需要联系百度钱包的技术支持。

## 线上体验地址
<http://lightloginbfb.duapp.com>

## 本地环境：

由于测试应用的百度登录授权回调页设置为http://localhost/ordersys/login_redirect.php。所以要在本地服务器DocumentRoot目录内新建ordersys文件夹，将代码放置此文件夹内，即应用的首页地址为http://localhost/ordersys/index.php。否则登录后会显示回调地址未授权。
