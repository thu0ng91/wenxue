<?php

/**
 * This is the model class for table "{{activity_vote}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-11-03 03:41:11
 * The followings are the available columns in table '{{activity_vote}}':
 * @property string $id
 * @property string $uid
 * @property string $activity
 * @property string $logid
 * @property string $classify
 * @property string $cTime
 * @property integer $ip
 */
class ActivityVote extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{activity_vote}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid, activity, logid, classify', 'required'),
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('ip', 'numerical', 'integerOnly' => true),
            array('uid, activity, cTime', 'length', 'max' => 10),
            array('logid', 'length', 'max' => 11),
            array('classify', 'length', 'max' => 8),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, activity, logid, classify, cTime, ip', 'safe', 'on' => 'search'),
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
            'uid' => 'Uid',
            'activity' => 'Activity',
            'logid' => 'Logid',
            'classify' => 'Classify',
            'cTime' => 'C Time',
            'ip' => 'IP',
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
        $criteria->compare('activity', $this->activity, true);
        $criteria->compare('logid', $this->logid, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('ip', $this->ip);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ActivityVote the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
