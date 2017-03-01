<?php

/**
 * This is the model class for table "{{links}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-12-06 15:32:31
 * The followings are the available columns in table '{{links}}':
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $logo
 * @property string $cTime
 * @property string $expritedTime
 * @property string $position
 * @property integer $typeid
 * @property integer $platform
 */
class Links extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{links}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('title, url,position', 'required'),
            array('typeid, platform', 'numerical', 'integerOnly' => true),
            array('title, url', 'length', 'max' => 255),
            array('logo', 'length', 'max' => 60),
            array('cTime, expritedTime,uid', 'length', 'max' => 10),
            array('position', 'length', 'max' => 16),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, url, logo, cTime, expritedTime, position, typeid, platform', 'safe', 'on' => 'search'),
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
            'title' => '标题',
            'url' => '链接',
            'logo' => '封面',
            'cTime' => '创建时间',
            'expritedTime' => '过期时间',
            'position' => '位置',
            'typeid' => '分类',
            'platform' => '平台限制',
            'status' => '显示',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('logo', $this->logo, true);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('expritedTime', $this->expritedTime, true);
        $criteria->compare('position', $this->position, true);
        $criteria->compare('typeid', $this->typeid);
        $criteria->compare('platform', $this->platform);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Links the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getOne($id) {
        return self::model()->findByPk($id);
    }

    /**
     * 友链位置展示
     * @param string $type
     * @return string
     */
    public static function exPositions($type) {
        $arr = array(
            'index' => '首页',
            'books' => '书城',
            'forums' => '论坛',
            'wenku' => '文库',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

    public static function getLinks($isMobile, $position, $typeid = 0) {
        if($isMobile){
            return array();
        }
        $_plat = 'web';
        $platform=1;
        if ($isMobile) {
            $_plat = 'mobile';
            $platform=2;
        }
        $key = "allLinks-{$_plat}-{$position}-{$typeid}";
        $links = zmf::getFCache($key);
        if ($links) {
            return $links;
        }
        $sql = "SELECT id,title,url FROM {{links}} WHERE (platform=0 OR platform='{$platform}') AND position='{$position}' AND typeid='{$typeid}' AND `status`=1 ORDER BY id ASC";
        $items = Yii::app()->db->createCommand($sql)->queryAll();
        zmf::setFCache($key, $items, 2592000);
        return $items;
    }

}
