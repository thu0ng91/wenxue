<?php

/**
 * This is the model class for table "{{showcase_link}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-17 08:45:33
 * The followings are the available columns in table '{{showcase_link}}':
 * @property string $id
 * @property string $sid
 * @property string $logid
 * @property string $classify
 * @property string $title
 * @property string $faceimg
 * @property string $content
 * @property string $url
 * @property integer $status
 * @property string $cTime
 * @property string $startTime
 * @property string $endTime
 */
class ShowcaseLink extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{showcase_link}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),            
            array('sid,classify', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('sid, logid, cTime, startTime, endTime', 'length', 'max' => 10),
            array('classify', 'length', 'max' => 16),
            array('title, faceimg, content, url', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, sid, logid, classify, title, faceimg, content, url, status, cTime, startTime, endTime', 'safe', 'on' => 'search'),
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
            'id' => 'ID',
            'sid' => '板块ID',
            'logid' => '所属对象ID',
            'classify' => '分类',
            'title' => '标题',
            'faceimg' => '图片地址',
            'content' => '简介',
            'url' => '链接',
            'status' => '状态',
            'cTime' => '创建时间',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
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
        $criteria->compare('sid', $this->sid, true);
        $criteria->compare('logid', $this->logid, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('faceimg', $this->faceimg, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('startTime', $this->startTime, true);
        $criteria->compare('endTime', $this->endTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ShowcaseLink the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
