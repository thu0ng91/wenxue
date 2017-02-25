<?php

/**
 * This is the model class for table "{{digest}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-25 10:46:25
 * The followings are the available columns in table '{{digest}}':
 * @property string $id
 * @property string $uid
 * @property string $title
 * @property string $url
 * @property string $faceImg
 * @property string $content
 * @property string $cTime
 * @property integer $status
 */
class Digest extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{digest}}';
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
            array('uid, title, url, faceImg, content', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('uid, cTime', 'length', 'max' => 10),
            array('title, url, faceImg, content', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, title, url, faceImg, content, cTime, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'userInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '创建人',
            'title' => '标题',
            'url' => '链接',
            'faceImg' => '封面',
            'content' => '内容',
            'cTime' => '创建时间',
            'status' => 'Status',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('faceImg', $this->faceImg, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Digest the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getOne($id) {
        return self::model()->findByPk($id);
    }

}
