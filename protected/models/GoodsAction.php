<?php

/**
 * This is the model class for table "{{goods_action}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-18 04:01:31
 * The followings are the available columns in table '{{goods_action}}':
 * @property string $id
 * @property string $gid
 * @property string $classify
 * @property string $action
 * @property string $from
 * @property string $to
 */
class GoodsAction extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{goods_action}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('gid, classify, action', 'required'),
            array('gid', 'length', 'max' => 10),
            array('classify, action, from, to', 'length', 'max' => 16),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, gid, classify, action, from, to', 'safe', 'on' => 'search'),
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
            'gid' => '商品ID',
            'classify' => '分类，如道具',
            'action' => '操作，如转换卡',
            'from' => '起始值',
            'to' => '结束值',
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
        $criteria->compare('gid', $this->gid, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('action', $this->action, true);
        $criteria->compare('from', $this->from, true);
        $criteria->compare('to', $this->to, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GoodsAction the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function exClassify($level='admin'){
        $arr=array(
            'tools'=>array(
                'desc'=>'道具',
                'key'=>'tools',
                'seconds'=>array(
                    'transfer'=>array(
                        'desc'=>'转让卡',
                        'key'=>'transfer',
                        'fromto'=>TRUE
                    ),
                    'reward'=>array(
                        'desc'=>'打赏、奖励',
                        'key'=>'reward',
                    )
                )
            ),
            'goods'=>array(
                'desc'=>'商品',
                'key'=>'goods',
            )
        );
        $tmp=array();
        if($level=='admin'){
            foreach ($arr as $k=>$val){
                $tmp[$k]=$val['desc'];
            }
        }elseif($level=='all'){
            $tmp=$arr;
        }else{
            $tmp=$arr[$level];
        }
        return $tmp;
    }

}
