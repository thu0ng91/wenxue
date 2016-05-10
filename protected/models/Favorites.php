<?php

/**
 * @filename Favrites.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-11-20  17:11:53 
 */
class Favorites extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{favorites}}';
    }

    public function rules() {
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('uid, logid, cTime', 'length', 'max' => 11),
            array('ip', 'length', 'max' => 16),
            array('classify', 'length', 'max' => 32),
            array('ipInfo', 'length', 'max' => 255),
            array('uid, logid, classify', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '用户',
            'logid' => '收藏对象',
            'classify' => '分类',
            'cTime' => '收藏时间',
            'ip' => 'ip',
            'ipInfo' => 'ip信息',
        );
    }

    public function beforeSave() {
        $ip = Yii::app()->request->userHostAddress;
        $url = 'http://apis.baidu.com/apistore/iplookupservice/iplookup?ip=' . $ip;
        // 执行HTTP请求
        $header = array(
            'apikey:e5882e7ac4b03c5d6f332b6de4469e81',
        );
        $ch = curl_init();
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $res = curl_exec($ch);
        $res = CJSON::decode($res, true);
        $retData = array();
        if ($res['errNum'] == 0) {
            $retData = $res['retData'];
        }
        $_info = json_encode($retData);
        $this->ip = ip2long($ip);
        $this->ipInfo = $_info;
        return true;
    }

    public static function checkFavored($logid, $type, $uid = '') {
        if (!$uid) {
            $uid = zmf::uid();
        }
        if (!$uid) {
            if (zmf::actionLimit('favorite-' . $type, $logid, 1, 86400, true, true)) {
                return true;
            }
            return false;
        }
        if (!is_numeric($logid)) {
            return false;
        }
        if (!isset($type) OR ! in_array($type, array('post'))) {
            return false;
        }
        $attr = array(
            'uid' => $uid,
            'logid' => $logid,
            'classify' => $type
        );
        if (Favorites::model()->findByAttributes($attr)) {
            return true;
        } else {
            return false;
        }
    }

    public static function simpleAdd($logid, $type) {
        $attr = array(
            'logid' => $logid,
            'classify' => $type
        );
        $model = new Favorites();
        $model->attributes = $attr;
        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }

}
