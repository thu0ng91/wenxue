<?php

class WenkuColumns extends CActiveRecord {

    const CLASSIFY_TYPES = 1;
    const CLASSIFY_DYNASTY = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{wenku_columns}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('title,classify', 'required'),
            array('status , system', 'numerical', 'integerOnly' => true),
            array('belongid, attachid, order, hits, cTime', 'length', 'max' => 10),
            array('name, title, second_title', 'length', 'max' => 100),
            array('classify, position', 'length', 'max' => 32),
            array('url', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, belongid, name, title, second_title, classify, position, url, attachid, order, hits, status, cTime,system', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        $this->name = zmf::pinyin($this->title);
        return true;
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
            'belongid' => '所属栏目',
            'name' => '栏目简写',
            'title' => '栏目标题',
            'second_title' => '简短描述',
            'classify' => '所属分类',
            'position' => '位置',
            'url' => '链接地址',
            'attachid' => '使用图标',
            'order' => '顺序',
            'hits' => 'Hits',
            'status' => '状态',
            'cTime' => '创建时间',
            'system' => '是否系统默认',
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('belongid', $this->belongid, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('second_title', $this->second_title, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('position', $this->position, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('attachid', $this->attachid, true);
        $criteria->compare('order', $this->order, true);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('system', $this->system, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getOne($keyid, $return = '') {
        if (!$keyid) {
            return false;
        }
        $item = self::model()->findByPk($keyid);
        if ($return != '') {
            return $item[$return];
        }
        return $item;
    }

    /**
     * 按照分类名查询栏目信息
     * @param type $name
     * @return type
     */
    public static function findByName($name) {
        $info = self::model()->find('title=:title', array(':title' => $name));
        return $info;
    }

    /**
     * 新增
     * @param type $attr
     * @return type
     */
    public static function add($attr) {
        $model = new WenkuColumns;
        $model->attributes = $attr;
        return $model->save();
    }

    public static function getAll($classify = WenkuColumns::CLASSIFY_DYNASTY) {
        $items = self::model()->findAll(array(
            'condition' => "classify='{$classify}' AND status=" . Posts::STATUS_PASSED,
            'select' => 'id,title'
        ));
        return CHtml::listData($items, 'id', 'title');
    }

    /**
     * 获取导航上朝代
     * @return type
     */
    public static function getNavItems() {
        $items = self::model()->findAll(array(
            'condition' => "classify='" . self::CLASSIFY_DYNASTY . "' AND position='topbar' AND status=" . Posts::STATUS_PASSED,
            'select' => 'id,title'
        ));
        return CHtml::listData($items, 'id', 'title');
    }

}
