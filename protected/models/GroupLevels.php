<?php

/**
 * This is the model class for table "{{group_levels}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-25 22:54:50
 * The followings are the available columns in table '{{group_levels}}':
 * @property string $id
 * @property string $gid
 * @property string $minExp
 * @property string $maxExp
 * @property string $title
 * @property string $desc
 * @property string $icon
 */
class GroupLevels extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{group_levels}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('gid, minExp, maxExp, title', 'required'),
            array('gid, minExp, maxExp', 'length', 'max' => 10),
            array('title, desc', 'length', 'max' => 16),
            array('icon', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, gid, minExp, maxExp, title, desc, icon', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'groupInfo' => array(self::BELONGS_TO, 'Group', 'gid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'gid' => '团队ID',
            'minExp' => '起始经验值',
            'maxExp' => '结束经验值',
            'title' => '等级名称',
            'desc' => '等级描述',
            'icon' => '图标',
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
        $criteria->compare('minExp', $this->minExp, true);
        $criteria->compare('maxExp', $this->maxExp, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('icon', $this->icon, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GroupLevels the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function getOne($id){
        return self::model()->findByPk($id);
    }
    
    public static function getByIds($idstr){
        if(!$idstr || $idstr==''){
            return array();
        }
        $items=  self::model()->findAll("id IN({$idstr})");
        $arr=array();
        foreach ($items as $val){
            $arr[$val['id']]=array(
                'id'=>$val['id'],
                'gid'=>$val['gid'],
                'title'=>$val['title'],
                'desc'=>$val['desc'],
                'icon'=>$val['icon'],
            );
        }
        return $arr;
    }

}
