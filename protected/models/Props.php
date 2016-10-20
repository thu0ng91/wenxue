<?php

/**
 * This is the model class for table "{{props}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-20 04:45:45
 * The followings are the available columns in table '{{props}}':
 * @property string $id
 * @property string $uid
 * @property string $gid
 * @property string $classify
 * @property string $action
 * @property string $from
 * @property string $to
 * @property string $num
 * @property string $updateTime
 */
class Props extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{props}}';
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
            array('uid, gid, classify, action, num', 'required'),
            array('uid, gid, num, updateTime', 'length', 'max' => 10),
            array('classify, action, from, to', 'length', 'max' => 16),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, gid, classify, action, from, to, num, updateTime', 'safe', 'on' => 'search'),
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
            'gid' => '商品ID',
            'classify' => '分类，如道具',
            'action' => '操作，如转换卡',
            'from' => '起始值',
            'to' => '结束值',
            'num' => '数量',
            'updateTime' => '更新时间',
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
        $criteria->compare('gid', $this->gid, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('action', $this->action, true);
        $criteria->compare('from', $this->from, true);
        $criteria->compare('to', $this->to, true);
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
     * @return Props the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function getOne($id){
        return self::model()->findByPk($id);
    }

    /**
     * 为用户保存道具数据
     * @param object $userInfo
     * @param object $goodsInfo
     * @param object $orderInfo
     * @return boolean
     */
    public static function saveUserProps($userInfo, $goodsInfo, $orderInfo) {
        if (empty($userInfo) || empty($goodsInfo) || empty($orderInfo)) {
            return false;
        } elseif (empty($goodsInfo['actionId'])) {
            return false;
        }        
        $now = zmf::now();
        foreach ($goodsInfo['actionId'] as $_action) {
            //只有道具才写到道具表
            if (in_array($_action['classify'],array('goods'))) {
                continue;
            }
            $_propAttr = array(
                'uid' => $userInfo['id'],
                'gid' => $goodsInfo['id'],
                'classify' => $_action['classify'],
                'action' => $_action['action']
            );
            $_propInfo = Props::model()->findByAttributes($_propAttr);
            if ($_propInfo) {//存在则说明之前购买过，再之前的基础上增加数量即可
                Props::model()->updateByPk($_propInfo['id'], array(
                    'num'=>($_propInfo['num']+$orderInfo['num']),
                    'updateTime'=>$now
                ));
            } else {
                $_propAttr = array(
                    'uid' => $userInfo['id'],
                    'gid' => $goodsInfo['id'],
                    'classify' => $_action['classify'],
                    'action' => $_action['action'],
                    'from' => $_action['from'],
                    'to' => $_action['to'],
                    'num' => $orderInfo['num'],
                );
                $_propModel= new Props;
                $_propModel->attributes=$_propAttr;
                if(!$_propModel->save()){
                    zmf::fp('props model--155');
                    zmf::fp($_propAttr,1);
                    zmf::fp($_propModel->getErrors(),1);
                    zmf::fp('props model--155');
                }
            }
        }
        return true;
    }
    
    public static function getClassifyProps($classify,$logid){
        if($classify=='book'){
            $chapters=  Chapters::getByBook($logid);
            if(empty($chapters)){
                return array();
            }
            $cids=  join(',',  array_keys(CHtml::listData($chapters, 'id', 'title')));
            if(!$cids){
                return array();
            }
            $sql="SELECT g.title,g.faceUrl,pr.uid,u.truename,u.avatar,pr.num,pr.updateTime FROM {{prop_relation}} pr INNER JOIN {{props}} p ON pr.pid=p.id INNER JOIN {{goods}} g ON p.gid=g.id INNER JOIN {{users}} u ON pr.uid=u.id WHERE pr.classify='chapter' AND pr.logid IN({$cids}) ORDER BY pr.num DESC,pr.updateTime ASC";
            $res=Yii::app()->db->createCommand($sql);
            $items=$res->queryAll();
        }else{
            $sql="SELECT g.title,g.faceUrl,pr.uid,u.truename,u.avatar,pr.num,pr.updateTime FROM {{prop_relation}} pr INNER JOIN {{props}} p ON pr.pid=p.id INNER JOIN {{goods}} g ON p.gid=g.id INNER JOIN {{users}} u ON pr.uid=u.id WHERE pr.classify=:classify AND pr.logid=:logid ORDER BY pr.num DESC,pr.updateTime ASC";
            $res=Yii::app()->db->createCommand($sql);
            $res->bindValues(array(
                ':classify'=>$classify,
                ':logid'=>$logid
            ));
            $items=$res->queryAll();
        }
        return $items;                
    }

}
