<?php

/**
 * This is the model class for table "{{posts}}".
 *
 * The followings are the available columns in table '{{posts}}':
 * @property string $id
 * @property string $uid
 * @property integer $dynasty
 * @property integer $colid
 * @property string $author
 * @property string $title
 * @property string $second_title
 * @property string $pinyin
 * @property string $content
 * @property string $hits
 * @property string $order
 * @property integer $status
 * @property string $updateTime
 * @property string $cTime
 */
class WenkuPosts extends CActiveRecord {

    public $authorName;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{wenku_posts}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('cTime,updateTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('title, content', 'required'),
            array('dynasty, colid, status,len', 'numerical', 'integerOnly' => true),
            array('uid, author, hits, order, updateTime, cTime', 'length', 'max' => 10),
            array('len', 'length', 'max' => 3),
            array('title, second_title, pinyin,faceImg,bgImg', 'length', 'max' => 255),
        );
    }

    public function beforeSave() {
        $this->pinyin = zmf::pinyin($this->title);
        $this->len = mb_strlen($this->title, 'GBK');
        return true;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'aboutInfos' => array(self::HAS_MANY, 'WenkuPostInfo', 'pid', 'order' => 'classify ASC'),
            'dynastyInfo' => array(self::BELONGS_TO, 'WenkuColumns', 'dynasty'),
            'authorInfo' => array(self::BELONGS_TO, 'WenkuAuthor', 'author'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '创建者',
            'dynasty' => '所属朝代',
            'colid' => '所属类型',
            'author' => '所属作者',
            'authorName' => '作者名字',
            'title' => '标题',
            'second_title' => '副标题',
            'pinyin' => '拼音',
            'content' => '内容',
            'hits' => '点击',
            'order' => '排序',
            'status' => '状态',
            'updateTime' => '更新时间',
            'cTime' => '创建时间',
            'len' => '长度',
            'faceImg' => '封面图',
            'bgImg' => '背景图',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Posts the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 获取热门作者
     * @param type $limit
     * @return type
     */
    public static function getTops($limit = 30) {
        $items = self::model()->findAll(array(
            'condition' => 'status=' . Posts::STATUS_PASSED,
            'order' => 'hits DESC',
            'limit' => $limit,
            'select' => 'id,title'
        ));
        return $items;
    }

    public static function getOne($id) {
        return self::model()->findByPk($id);
    }

}
