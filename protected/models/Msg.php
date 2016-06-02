<?php

/**
 * This is the model class for table "{{msg}}".
 *
 * The followings are the available columns in table '{{msg}}':
 * @property string $id
 * @property string $uid
 * @property string $phone
 * @property string $code
 * @property string $content
 * @property integer $expiredTime
 * @property string $type
 */
class Msg extends CActiveRecord {

    const ACTIVE_PASSED = 1; //验证通过
    const ACTIVE_ERROR = 0; //验证错误
    const ACTIVE_EXPIRED = -1; //已过期
    const TYPE_SMS = 1;
    const TYPE_VOICE = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{msg}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('phone, code, expiredTime, type', 'required'),
            array('expiredTime,cTime,status,sendType,voiceStatus', 'numerical', 'integerOnly' => true),
            array('uid, phone', 'length', 'max' => 11),
            array('voiceId,appInfo', 'length', 'max' => 32),
            array('code,appType', 'length', 'max' => 8),
            array('content', 'length', 'max' => 255),
            array('type,cTime', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('uid, phone', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => '短信ID',
            'uid' => '用户ID',
            'phone' => '电话号码',
            'code' => '验证码',
            'content' => '短信内容',
            'expiredTime' => '过期时间',
            'type' => '业务类型',
            'cTime' => '生成时间',
            'status' => '短信状态',
            'sendType' => '发送类别',
            'voiceStatus' => '语音接听状态',
            'voiceId' => '语言消息ID',
            'appInfo' => '软件版本',
            'appType' => '软件类型',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria;
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('phone', $this->phone, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Msg the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function returnTemplate($type) {
        switch ($type) {
            case 'reg':
                $content = 'SMS_7785619';
                break;
            case 'forget':
                $content = 'SMS_7785619';
                break;
            default :
                $content = 'SMS_7785619';
                break;
        }
        return $content;
    }

    public static function exTypes($type) {
        $arr = array(
            'reg' => '注册',
            'forget' => '找回密码',
            'exphone' => '更换手机',
            'checkPhone' => '验证手机',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

    /**
     * 用户发送短信验证码
     * @param type $userData
     * @param type $type
     * @return boolean
     */
    public static function initSend($userData, $type, $platform = '', $appinfo = '') {
        //时间有效期
        $_time = zmf::now();
        $time = $_time + 15 * 60;
        $code = zmf::simpleRand(4);
        $template = Msg::returnTemplate($type);
        $attr = array(
            'uid' => $userData['id'],
            'phone' => $userData['phone'],
            'type' => $type,
            'sendType' => Msg::TYPE_SMS,
            'code' => $code,
            'expiredTime' => $time,
            'cTime' => $_time,
            'content' => $template,
            'appInfo' => $appinfo,
            'appType' => $platform,
        );
        $model = new Msg();
        $model->attributes = $attr;
        if ($model->save()) {
            $params = array(
                'phone' => $userData['phone'],
                'code' => $code,
                'template' => $template,
            );
            $status = Msg::sendOne($params);
            return $status;
        }
        return false;
    }

    public static function sendOne($params) {
        $attr = array(
            'sign' => '重庆好婚礼',
            'phone' => $params['phone'],
            'attr' => array(
                'code' => $params['code'],
                'product' => '重庆好婚礼',
                'time' => '15',
            ),
            'template' => $params['template'],
        );
        return Msg::sendMsg($attr);
    }
    
    public static function sendMsg($attr){
        $res = new Taobao;        
        $info = $res->sendSms($attr);
        //大于0说明有错误
        if ($info->code > 0) {
            zmf::fp($attr, 1);
            zmf::fp($info, 1);
            return false;
        } else {
            return true;
        }
    }

    /**
     * 根据手机号判断当天已发送短信数量
     * @param type $phone
     * @return type
     */
    public static function statByPhone($phone) {
        $now = zmf::now();
        $time = strtotime(zmf::time($now, 'Y-m-d'), $now);
        $count = Msg::model()->count('phone=:p AND cTime>=:t AND cTime<=:n', array(':p' => $phone, ':t' => $time, ':n' => $now));
        return $count;
    }

    /**
     * 转换短信发送状态
     * @param type $type
     * @return string
     */
    public static function sendTypes($type) {
        $arr = array(
            self::TYPE_SMS => '短信',
            self::TYPE_VOICE => '语音'
        );
        return $arr[$type];
    }

    /**
     * 语音状态转换
     * @param type $type
     * @return string
     */
    public static function voiceStatus($type) {
        $arr = array(
            '1' => '已接听', //真实返回数据为0,1,2
            '2' => '未接通',
            '3' => '呼叫失败',
            '0' => '--',
            '-1' => '已挂断',
        );
        return $arr[$type];
    }

    public static function smsStatus($type) {
        $arr = array(
            self::ACTIVE_ERROR => '发送',
            self::ACTIVE_EXPIRED => '已过期',
            self::ACTIVE_PASSED => '已使用',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

}
