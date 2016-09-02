<?php

/**
 * This is the model class for table "{{task}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:13
 * The followings are the available columns in table '{{task}}':
 * @property string $id
 * @property string $title
 * @property string $faceImg
 * @property string $desc
 * @property string $action
 * @property integer $type
 * @property integer $continuous
 * @property integer $num
 * @property string $score
 * @property string $startTime
 * @property string $endTime
 * @property string $times
 * @property string $cTime
 */
class Task extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{task}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, faceImg, desc, action, type, continuous, num, score, startTime, endTime, times, cTime', 'required'),
            array('type, continuous, num', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 50),
            array('faceImg, desc', 'length', 'max' => 255),
            array('action', 'length', 'max' => 16),
            array('score, startTime, endTime, times, cTime', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, faceImg, desc, action, type, continuous, num, score, startTime, endTime, times, cTime', 'safe', 'on' => 'search'),
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
            'title' => '标题',
            'faceImg' => '封面图',
            'desc' => '描述',
            'action' => '操作标识',
            'type' => '类型，一次性还是重复性任务',
            'continuous' => '连续的，不可中断',
            'num' => '每天次数',
            'score' => '奖励积分',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'times' => '参与人数',
            'cTime' => '创建时间',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('faceImg', $this->faceImg, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('action', $this->action, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('continuous', $this->continuous);
        $criteria->compare('num', $this->num);
        $criteria->compare('score', $this->score, true);
        $criteria->compare('startTime', $this->startTime, true);
        $criteria->compare('endTime', $this->endTime, true);
        $criteria->compare('times', $this->times, true);
        $criteria->compare('cTime', $this->cTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Task the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
