<?php

/**
 * @filename Taobao.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-4-15  10:33:57 
 */
require_once(Yii::app()->basePath . '/../taobao/TopSdk.php');

class Taobao extends TopClient {

    public function __construct() {
        $this->appkey = '23381563';
        $this->secretKey = '3796df193d33cac6562d5b9b34ac649d';
        $this->format='json';
        $this->simplify=true;
    }

    public function sendSms($params=array()) {
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($params['sign']);        
        $req->setRecNum($params['phone']);        
        $attr=$params['attr'];
        if(!empty($attr)){
            $req->setSmsParam(json_encode($attr));
        }        
        $req->setSmsTemplateCode($params['template']);
        $resp = $this->execute($req);
        return $resp;
    }

}
