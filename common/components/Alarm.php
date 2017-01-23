<?php
namespace common\components;

use Yii;

/**
 * 
 * @author louzhiqiang
 * 创建的时候，传入的构造参数是一个数组，数组中包含收件人地址email
 */
class Alarm{
    
    private $subject;
    private $body;
    private $email;
    
    function setToEmail($email){
        $this->email = $email;
    }
    public function setSubject($subject){
        $this->subject = $subject;
    }
    public function setBody($body){
        $this->body = $body;
    }
    public function mail(){
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($this->email);
        $mail->setSubject($this->subject);
        $mail->setHtmlBody($this->body);    //发布可以带html标签的文本
        if($mail->send()){
            $message = printf("line %d >>> email %s >>> body: %s >>> subject: %s >>> msg is : %s", __LINE__, $this->email, $this->body, $this->subject, "send success!");
            Yii::info($message);
            return true;
        }else{
            $message = printf("line %d >>> email %s >>> body: %s >>> subject: %s >>> msg is : %s", __LINE__, $this->email, $this->body, $this->subject, "send failed!");
            Yii::error($message);
            return false;
        }
    }
    
}