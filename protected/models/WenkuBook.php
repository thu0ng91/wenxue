<?php

/**
 * This is the model class for table "{{book}}".
 *
 * The followings are the available columns in table '{{book}}':
 * @property string $id
 * @property string $author
 * @property integer $dynasty
 * @property string $uid
 * @property string $title
 * @property string $content
 * @property string $classify
 * @property integer $status
 * @property string $cTime
 */
class WenkuBook extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{wenku_book}}';
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
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('title', 'required'),
            array('dynasty, status', 'numerical', 'integerOnly' => true),
            array('author, uid, cTime,attachid', 'length', 'max' => 10),
            array('title', 'length', 'max' => 50),
            array('classify', 'length', 'max' => 16),
            array('content', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'authorInfo' => array(self::BELONGS_TO, 'WenkuAuthor', 'author'),
            'dynastyInfo' => array(self::BELONGS_TO, 'WenkuColumns', 'dynasty'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'author' => '作者',
            'dynasty' => '朝代',
            'uid' => '创建人',
            'title' => '标题',
            'content' => '描述',
            'classify' => '分类',
            'status' => '状态',
            'cTime' => '创建时间',
            'attachid' => '封面图片',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Book the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function listAll($where = '') {
        $list = false;
        if (!$where) {
            $where = "WHERE status=" . Posts::STATUS_PASSED;
            $list = true;
        }
        $sql = "SELECT id,title FROM {{wenku_book}} " . $where;
        $items = Yii::app()->db->createCommand($sql)->queryAll();
        if ($list) {
            $return = CHtml::listData($items, 'id', 'title');
            return $return;
        } else {
            return $items;
        }
    }

}
