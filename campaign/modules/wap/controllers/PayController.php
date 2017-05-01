<?php
namespace campaign\modules\wap\controllers;

use Yii;
use campaign\components\alipay\alipayapi;
use campaign\models\Order;

class PayController extends BaseController{
    public $modelClass = '';
    
    public function beforeAction($action){
        parent::beforeAction($action);
        return true;
    }

    /**
     * 中间跳转临时页面
     */
    public function actionTiaozhuan()
    {
	$orderId = Yii::$app->request->get("orderId");
	$orderInfo = Order::findOne($orderId);
	
        $html = '<!DOCTYPE html>
                    <html>
                        <head>
                        <title>支付宝手机网站支付接口接口</title>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <style>
                        *{
                            margin:0;
                            padding:0;
                        }
                        ul,ol{
                            list-style:none;
                        }
                        body{
                            font-family: "Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;
                        }
                        .hidden{
                            display:none;
                        }
                        .new-btn-login-sp{
                            padding: 1px;
                            display: inline-block;
                            width: 75%;
                        }
                        .new-btn-login {
                            background-color: #02aaf1;
                            color: #FFFFFF;
                            font-weight: bold;
                            border: none;
                            width: 100%;
                            height: 30px;
                            border-radius: 5px;
                            font-size: 16px;
                        }
                        #main{
                            width:100%;
                            margin:0 auto;
                            font-size:14px;
                        }
                        .red-star{
                            color:#f00;
                            width:10px;
                            display:inline-block;
                        }
                        .null-star{
                            color:#fff;
                        }
                        .content{
                            margin-top:5px;
                        }
                        .content dt{
                            width:100px;
                            display:inline-block;
                            float: left;
                            margin-left: 20px;
                            color: #666;
                            font-size: 13px;
                            margin-top: 8px;
                        }
                        .content dd{
                            margin-left:120px;
                            margin-bottom:5px;
                        }
                        .content dd input {
                            width: 85%;
                            height: 28px;
                            border: 0;
                            -webkit-border-radius: 0;
                            -webkit-appearance: none;
                        }
                        #foot{
                            margin-top:10px;
                            position: absolute;
                            bottom: 15px;
                            width: 100%;
                        }
                        .foot-ul{
                            width: 100%;
                        }
                        .foot-ul li {
                            width: 100%;
                            text-align:center;
                            color: #666;
                        }
                        .note-help {
                            color: #999999;
                            font-size: 12px;
                            line-height: 130%;
                            margin-top: 5px;
                            width: 100%;
                            display: block;
                        }
                        #btn-dd{
                            margin: 20px;
                            text-align: center;
                        }
                        .foot-ul{
                            width: 100%;
                        }
                        .one_line{
                            display: block;
                            height: 1px;
                            border: 0;
                            border-top: 1px solid #eeeeee;
                            width: 100%;
                            margin-left: 20px;
                        }
                        .am-header {
                            display: -webkit-box;
                            display: -ms-flexbox;
                            display: box;
                            width: 100%;
                            position: relative;
                            padding: 7px 0;
                            -webkit-box-sizing: border-box;
                            -ms-box-sizing: border-box;
                            box-sizing: border-box;
                            background: #1D222D;
                            height: 50px;
                            text-align: center;
                            -webkit-box-pack: center;
                            -ms-flex-pack: center;
                            box-pack: center;
                            -webkit-box-align: center;
                            -ms-flex-align: center;
                            box-align: center;
                        }
                        .am-header h1 {
                            -webkit-box-flex: 1;
                            -ms-flex: 1;
                            box-flex: 1;
                            line-height: 18px;
                            text-align: center;
                            font-size: 18px;
                            font-weight: 300;
                            color: #fff;
                        }
                    </style>
                    </head>
                    <body text=#000000 bgColor="#ffffff" leftMargin=0 topMargin=4>
                    <header class="am-header">
                            <h1>支付宝手机网站支付接口快速通道</h1>
                    </header>
                    <div id="main">
                            <form name=alipayment action="/wap/pay/submit" method=post target="_blank">
                                <div id="body" style="clear:left">
                                    <dl class="content">
                                        <dt>商户订单号
                    ：</dt>
                                        <dd>
                                            <input id="WIDout_trade_no" value="'.$orderId.'" name="WIDout_trade_no" />
                                        </dd>
                                        <hr class="one_line">
                                        <dt>订单名称
                    ：</dt>
                                        <dd>
                                            <input id="WIDsubject" value="'.$orderInfo['campTitle'].'" name="WIDsubject" />
                                        </dd>
                                        <hr class="one_line">
                                        <dt>付款金额
                    ：</dt>
                                        <dd>
                                            <input id="WIDtotal_fee" value="'.$orderInfo['amount'].'"name="WIDtotal_fee" />
                                        </dd>
                                        <hr class="one_line">
                                        <dt>商品展示网址
                    ：</dt>
                                        <dd>
                                            <input id="WIDshow_url" value="http://www.ioutdoor.org/m/detail.html?id='.$orderInfo['campId'].'" name="WIDshow_url" />
                                        </dd>
                                        <hr class="one_line">
                                        <dt>商品描述：</dt>
                                        <dd>
                                            <input id="WIDbody" name="WIDbody" value="暂无"/>
                                        </dd>
                                        <hr class="one_line">
                                        <dt></dt>
                                        <dd id="btn-dd">
                                            <span class="new-btn-login-sp">
                                                <button class="new-btn-login" type="submit" style="text-align:center;">确 认</button>
                                            </span>
                                            <span class="note-help">如果您点击“确认”按钮，即表示您同意该次的执行操作。</span>
                                        </dd>
                                    </dl>
                                </div>
                            </form>
                            <div id="foot">
                                <ul class="foot-ul">
                                    <li>
                                        支付宝版权所有 2015-2018 ALIPAY.COM
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </body>
                    <script language="javascript">
                        function GetDateNow() {
                            var vNow = new Date();
                            var sNow = "";
                            sNow += String(vNow.getFullYear());
                            sNow += String(vNow.getMonth() + 1);
                            sNow += String(vNow.getDate());
                            sNow += String(vNow.getHours());
                            sNow += String(vNow.getMinutes());
                            sNow += String(vNow.getSeconds());
                            sNow += String(vNow.getMilliseconds());
                            document.getElementById("WIDout_trade_no").value =  "'.$orderId.'";
                            document.getElementById("WIDsubject").value = "'.$orderInfo['campTitle'].'";
                            document.getElementById("WIDtotal_fee").value = "0.1";
                        }
                        GetDateNow();
                    </script>
                    </html>';
        return $html;
    }

    public function actionSubmit()
    {
	$WIDout_trade_no = Yii::$app->request->post('WIDout_trade_no');
	$WIDsubject = Yii::$app->request->post('WIDsubject');
	$WIDtotal_fee = Yii::$app->request->post('WIDtotal_fee');
	$WIDshow_url = Yii::$app->request->post('WIDshow_url');
	$WIDbody = Yii::$app->request->post('WIDbody');
        $html ='<!DOCTYPE html>
                <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <title>支付宝手机网站支付接口接口</title>
                </head>';
        $obj =  new alipayapi($WIDout_trade_no, $WIDsubject, $WIDtotal_fee, $WIDshow_url, $WIDbody);
        $html .= $obj->getPage();
        $html .= '</body>
</html>';
        return $html;

    }

    public function actionNotify()
    {
	Yii::info("notify 回调信息".json_encode(Yii::$app->request->post()), 'order');
	Yii::info("notify 回调信息".json_encode(Yii::$app->request->get()), 'order');
    }

    public function actionReturn()
    {
	Yii::info("return 信息".json_encode(Yii::$app->request->post()), 'order');
	Yii::info("return 信息".json_encode(Yii::$app->request->post()), 'order');
	
    }

    public function afterAction($action, $result)
    {
        exit($result);
    }
}
