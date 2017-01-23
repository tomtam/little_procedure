<?php
namespace console\controllers;

use Yii;

class CrontabController extends \yii\console\Controller{

	public function actionIndex(){
		/**
		 * User: gaohl
		 * 定时任务处罚
		 * Date: 16-8-4
		 */
		date_default_timezone_set('PRC');
		define('DS', DIRECTORY_SEPARATOR);
		//require dirname(dirname(__FILE__)) . DS . 'vendor' . DS . 'autoload.php';
		require dirname(__FILE__).'/../..'. DS . 'vendor' . DS . 'autoload.php';

		error_reporting(E_ALL);

		$logFile = Yii::$app->getRuntimePath() . '/logs/appactivity.log';
		$missions = [
			//app抢钱游戏任务
			//[
			//'name' => 'appactivity',
			//'cmd' => "cd /home/www/ehome_pay_api; php yii appactivity/rechange ",
			//'out' => 'file://'.$logFile,
			//'err' => 'file://'.$logFile,
			//'time' => '0,30 14-23 * * *',
			//'time' => '*/1 * * * *',
			//'user' => 'nobody',
			//'group' => 'nobody'
			//],		    
			[
			'name' => 'spdcrond/checkout',
			'cmd' => "cd /home/www/ehome_pay_api; php yii spdcrond/checkout",
			'out' => 'file://'.Yii::$app->getRuntimePath() . '/logs/spdcrond-checkout.log',
			'err' => 'file://'.Yii::$app->getRuntimePath() . '/logs/spdcrond-checkout.log',
			'time' => '0 */2 * * *',//0 */2 * * *未激活2个小时执行一次
			'user' => 'nobody',
			'group' => 'nobody'
				],
			[
			'name' => 'spdcrond/not-active',
			'cmd' => "cd /home/www/ehome_pay_api; php yii spdcrond/not-active",
			'out' => 'file://'.Yii::$app->getRuntimePath() . '/logs/spdcrond-not-active.log',
			'err' => 'file://'.Yii::$app->getRuntimePath() . '/logs/spdcrond-not-active.log',
			'time' => '0 * * * *',//0 * * * *激活超时的1小时通知彭亮一次
			'user' => 'nobody',
			'group' => 'nobody'
				],
		];

		$daemon = new \Jenner\Crontab\Daemon($missions);
		$daemon->start();
	}

}
