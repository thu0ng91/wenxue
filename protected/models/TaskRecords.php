<?php

/**
 * This is the model class for table "{{task_records}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:13
 * The followings are the available columns in table '{{task_records}}':
 * @property string $id
 * @property string $uid
 * @property string $tid
 * @property string $cTime
 */
class TaskRecords extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{task_records}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('uid, tid', 'required'),
            array('uid, tid, cTime', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, tid, cTime', 'safe', 'on' => 'search'),
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
            'uid' => '用户ID',
            'tid' => '任务ID',
            'cTime' => '参与时间',
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
        $criteria->compare('tid', $this->tid, true);
        $criteria->compare('cTime', $this->cTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TaskRecords the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function simpleAdd($attr){
        $model=new TaskRecords;
        $model->attributes=$attr;
        return $model->save();
    }

}
