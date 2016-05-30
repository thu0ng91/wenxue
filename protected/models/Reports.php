<?php

class Reports extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{reports}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid, logid, status, cTime', 'numerical', 'integerOnly' => true),
            array('classify, desc, url', 'length', 'max' => 255),
            array('ip', 'length', 'max' => 15),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, logid, classify, ip, desc, status , cTime, url', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'authorInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '举报者',
            'logid' => '举报内容',
            'classify' => '分类',
            'ip' => '举报者IP',
            'desc' => '举报描述',
            'status' => '状态',
            'cTime' => '举报时间',
            'url' => '举报链接',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('uid', $this->uid);
        $criteria->compare('logid', $this->logid);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('cTime', $this->cTime);
        $criteria->compare('url', $this->url, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
