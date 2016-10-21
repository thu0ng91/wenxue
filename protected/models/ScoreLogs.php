<?php

/**
 * This is the model class for table "{{score_logs}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:13
 * The followings are the available columns in table '{{score_logs}}':
 * @property string $id
 * @property string $uid
 * @property string $classify
 * @property string $logid
 * @property integer $score
 * @property string $cTime
 */
class ScoreLogs extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{score_logs}}';
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
            array('uid,classify,score', 'required'),
            array('score', 'numerical', 'integerOnly' => true),
            array('uid, logid, cTime,exp', 'length', 'max' => 10),
            array('classify', 'length', 'max' => 16),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, classify, logid, score, cTime', 'safe', 'on' => 'search'),
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
            'classify' => '分类',
            'logid' => '对象ID',
            'score' => '积分',
            'exp' => '经验',
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
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('logid', $this->logid, true);
        $criteria->compare('score', $this->score);
        $criteria->compare('cTime', $this->cTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ScoreLogs the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /**
     * 统计用户的总积分
     * @param int $uid
     * @return int
     */
    public static function statUserScore($uid){
        $sql="SELECT SUM(score) AS total FROM {{score_logs}} WHERE uid=:uid";
        $res=  Yii::app()->db->createCommand($sql);
        $res->bindValue(':uid', $uid);
        $info=$res->queryRow();
        return $info['total'];
    }

}
