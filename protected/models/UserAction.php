<?php

/**
 * This is the model class for table "{{user_action}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-29 10:06:37
 * The followings are the available columns in table '{{user_action}}':
 * @property string $id
 * @property string $uid
 * @property string $logid
 * @property string $classify
 * @property string $data
 * @property string $cTime
 * @property string $ip
 */
class UserAction extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_action}}';
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
            array('ip', 'default', 'setOnEmpty' => true, 'value' => ip2long(Yii::app()->request->userHostAddress)),
            array('uid, logid, classify, data', 'required'),
            array('uid, logid', 'length', 'max' => 10),
            array('classify', 'length', 'max' => 32),
            array('cTime, ip', 'length', 'max' => 11),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, logid, classify, data, cTime, ip', 'safe', 'on' => 'search'),
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
            'logid' => '对象ID',
            'classify' => '对象分类',
            'data' => '对象内容',
            'cTime' => '创建时间',
            'ip' => '所在IP',
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
        $criteria->compare('data', $this->data, true);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('ip', $this->ip, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserAction the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function exClassify($type){
        $arr=array(
            'favoriteAuthor'=>'关注了作者',
            'favoriteBook'=>'收藏了小说',
            'chapterTip'=>'点评了',
            'post'=>'发布帖子',
        );
        return $arr[$type];
    }

    public static function recordAction($logid, $type, $jsonData, $uid = '') {
        if(!$logid || !$type || !$jsonData){
            return false;
        }
        $data = array(
            'uid' => ($uid != '') ? $uid : zmf::uid(),
            'logid' => $logid,
            'classify' => $type,
        );
        $info=  UserAction::model()->findByAttributes($data);
        if($info){
            $attr=array(
                'data'=>$jsonData,
                'cTime'=>  zmf::now()
            );
            return UserAction::model()->updateByPk($info['id'], $attr);
        }
        $data['data']=$jsonData;
        $model = new UserAction();        
        $model->attributes = $data;
        return $model->save();
    }

    public static function delAction($logid, $type) {
        if (Yii::app()->user->isGuest) {
            return false;
        }
        if (!is_numeric($logid)) {
            return false;
        }
        $attr = array(
            ':uid' => Yii::app()->user->id,
            ':logid' => $logid,
            ':classify' => $type,
        );
        if (UserAction::model()->deleteAll(':uid=uid AND :logid=logid AND :classify=classify', $attr)) {
            return true;
        } else {
            return false;
        }
    }

}
