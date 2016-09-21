<?php

/**
 * This is the model class for table "{{prop_relation}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-21 21:36:31
 * The followings are the available columns in table '{{prop_relation}}':
 * @property string $id
 * @property string $uid
 * @property string $touid
 * @property string $pid
 * @property string $classify
 * @property string $logid
 * @property string $num
 * @property string $updateTime
 */
class PropRelation extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{prop_relation}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('updateTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('uid, touid, pid, classify, logid', 'required'),
            array('uid, touid, pid, logid, num, updateTime', 'length', 'max' => 10),
            array('classify', 'length', 'max' => 16),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, touid, pid, classify, logid, num, updateTime', 'safe', 'on' => 'search'),
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
            'uid' => '用户',
            'touid' => '接收者',
            'pid' => '道具ID',
            'classify' => '分类，如帖子',
            'logid' => '对象ID',
            'num' => '使用数量',
            'updateTime' => '最近更新时间',
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
        $criteria->compare('touid', $this->touid, true);
        $criteria->compare('pid', $this->pid, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('logid', $this->logid, true);
        $criteria->compare('num', $this->num, true);
        $criteria->compare('updateTime', $this->updateTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PropRelation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
