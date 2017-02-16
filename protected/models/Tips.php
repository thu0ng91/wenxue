<?php

/**
 * This is the model class for table "{{tips}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-14 20:42:08
 * The followings are the available columns in table '{{tips}}':
 * @property string $id
 * @property string $uid
 * @property string $logid
 * @property string $classify
 * @property string $tocommentid
 * @property string $content
 * @property string $platform
 * @property string $score
 * @property integer $status
 * @property string $cTime
 * @property string $ip
 * @property string $ipInfo
 */
class Tips extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{tips}}';
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
            array('ip', 'default', 'setOnEmpty' => true, 'value' => ip2long(Yii::app()->request->userHostAddress)),            
            array('uid, logid, classify,content,score', 'required'),
            array('status,favors', 'numerical', 'integerOnly' => true),
            array('uid, logid, tocommentid, score, cTime,bid,comments,props', 'length', 'max' => 10),
            array('classify, platform, ip', 'length', 'max' => 16),
            array('ipInfo', 'length', 'max' => 255),
            array('content', 'length', 'max' => 1024),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, logid, classify, tocommentid, content, platform, score, status, cTime, ip, ipInfo', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'bookInfo' => array(self::BELONGS_TO, 'Books', 'bid'),
            'userInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '用户ID',
            'bid' => '所属小说',
            'logid' => '对象ID',
            'classify' => '对象分类',
            'tocommentid' => '评论ID',
            'content' => '内容',
            'platform' => '来源',
            'score' => '评分',
            'favors' => '点赞',
            'status' => '状态',
            'cTime' => '创建时间',
            'ip' => 'IP',
            'ipInfo' => 'IP信息',
            'comments' => '评论数',
            'props' => '赞赏数',
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
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('logid', $this->logid, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('tocommentid', $this->tocommentid, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('platform', $this->platform, true);
        $criteria->compare('score', $this->score, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('ipInfo', $this->ipInfo, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Tips the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function getOne($id){
        return Tips::model()->findByPk($id);
    }
    
    public static function exScore($type){
        $arr=array(
            '1'=>'很差',
            '2'=>'较差',
            '3'=>'还行',
            '4'=>'推荐',
            '5'=>'力荐',
        );
        if($type=='admin'){
            return $arr;
        }
        return $arr[$type];
    }
    
    public static function exStatusForUser($type) {
        $arr = array(
            Posts::STATUS_DELED => '不再显示',
            Posts::STATUS_PASSED => '立即发布'
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

}
