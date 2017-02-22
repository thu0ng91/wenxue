<?php

/**
 * This is the model class for table "{{author}}".
 *
 * The followings are the available columns in table '{{author}}':
 * @property string $id
 * @property string $uid
 * @property string $title
 * @property string $pinyin
 * @property string $dynasty
 * @property string $attachid
 * @property string $hits
 * @property string $cTime
 * @property integer $status
 */
class WenkuAuthor extends CActiveRecord {

    /**
     * @return string the associated database table pinyin
     */
    public function tableName() {
        return '{{wenku_author}}';
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
            array('status', 'numerical', 'integerOnly' => true),
            array('uid, dynasty, hits, cTime', 'length', 'max' => 11),
            array('firstChar', 'length', 'max' => 1),
            array('title, pinyin', 'length', 'max' => 100),
            array('attachid', 'length', 'max' => 10),
        );
    }

    public function beforeSave() {
        $this->pinyin = zmf::pinyin($this->title);
        $this->firstChar = substr($this->pinyin, 0, 1);
        return true;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation pinyin and the related
        // class pinyin for the relations automatically generated below.
        return array(
            'aboutInfos' => array(self::HAS_MANY, 'WenkuAuthorInfo', 'author', 'order' => 'classify ASC'),
            'aboutInfo' => array(self::HAS_ONE, 'WenkuAuthorInfo', 'author', 'order' => 'classify ASC'),
            'dynastyInfo' => array(self::BELONGS_TO, 'WenkuColumns', 'dynasty'),
        );
    }

    /**
     * @return array customized attribute labels (pinyin=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => 'Uid',
            'title' => '名称',
            'pinyin' => 'Name',
            'dynasty' => '所属朝代',
            'attachid' => 'Attachid',
            'hits' => 'Hits',
            'cTime' => 'C Time',
            'status' => '状态',
            'firstChar' => '首字母',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class pinyin.
     * @return Author the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 根据作者名字查询
     * @param type $name
     * @return type
     */
    public static function findByName($name) {
        $info = WenkuAuthor::model()->find('title=:title', array(':title' => $name));
        return $info;
    }

    /**
     * 新增作者
     * @param type $author 作者名称
     * @param type $chaodai 作者所在朝代
     * @return type
     */
    public static function add($author, $chaodai = '') {
        $attr = array(
            'title' => $author,
            'dynasty' => $chaodai,
        );
        $model = new WenkuAuthor;
        $model->attributes = $attr;
        return $model->save();
    }

    /**
     * 返回单条信息
     * @param type $keyid
     * @param type $return
     * @return boolean
     */
    public static function getOne($keyid, $return = '') {
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

}
