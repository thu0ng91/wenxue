<?php

/**
 * This is the model class for table "{{orders}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-19 10:15:59
 * The followings are the available columns in table '{{orders}}':
 * @property string $id
 * @property string $orderId
 * @property string $uid
 * @property string $gid
 * @property string $title
 * @property string $desc
 * @property string $faceUrl
 * @property string $classify
 * @property string $content
 * @property string $scorePrice
 * @property string $goldPrice
 * @property string $num
 * @property string $payAction
 * @property integer $orderStatus
 * @property integer $status
 * @property string $cTime
 * @property string $paidTime
 * @property string $paidOrderId
 * @property string $paidType
 */
class Orders extends CActiveRecord {
    const PAID_NOTPAID = 0; //未支付
    const PAID_PAID = 1; //已支付，等待对方接受订单

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{orders}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            //array('orderId', 'default', 'setOnEmpty' => true, 'value' => Orders::genOrderid()),
            array('orderId, uid, gid, scorePrice, goldPrice, num, payAction', 'required'),
            array('orderStatus, status', 'numerical', 'integerOnly' => true),
            array('orderId', 'length', 'max' => 32),
            array('uid, gid, num, cTime, paidTime', 'length', 'max' => 10),
            array('title, desc, faceUrl, classify, paidOrderId', 'length', 'max' => 255),
            array('scorePrice, goldPrice, payAction, paidType', 'length', 'max' => 16),
            array('content', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, orderId, uid, gid, title, desc, faceUrl, classify, content, scorePrice, goldPrice, num, payAction, orderStatus, status, cTime, paidTime, paidOrderId, paidType', 'safe', 'on' => 'search'),
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
            'id' => '关系ID',
            'orderId' => '订单号',
            'uid' => '用户ID',
            'gid' => '商品ID',
            'title' => '标题',
            'desc' => '描述',
            'faceUrl' => '封面地址',
            'classify' => '分类信息',
            'content' => '商品介绍',
            'scorePrice' => '积分价格',
            'goldPrice' => '金币价格',
            'num' => '总量',
            'payAction' => '购买方式，积分还是金币',
            'orderStatus' => '订单状态',
            'status' => '状态',
            'cTime' => '创建时间',
            'paidTime' => '支付时间',
            'paidOrderId' => '支付订单号',
            'paidType' => '支付方式',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('orderId', $this->orderId, true);
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('gid', $this->gid, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('faceUrl', $this->faceUrl, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('scorePrice', $this->scorePrice, true);
        $criteria->compare('goldPrice', $this->goldPrice, true);
        $criteria->compare('num', $this->num, true);
        $criteria->compare('payAction', $this->payAction, true);
        $criteria->compare('orderStatus', $this->orderStatus);
        $criteria->compare('status', $this->status);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('paidTime', $this->paidTime, true);
        $criteria->compare('paidOrderId', $this->paidOrderId, true);
        $criteria->compare('paidType', $this->paidType, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Orders the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function genOrderid() {
        $now= zmf::now();
        $time=  zmf::time($now,'YmdHis');
        $code=$time.(number_format(abs(microtime(true)-$now)*1000,0,'.','')).mt_rand(100, 999);
        return $code;
    }

}
