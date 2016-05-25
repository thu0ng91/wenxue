<?php

/**
 * This is the model class for table "{{drafts}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-25 09:53:33
 * The followings are the available columns in table '{{drafts}}':
 * @property string $id
 * @property string $uid
 * @property string $aid
 * @property string $bid
 * @property string $uuid
 * @property string $title
 * @property string $content
 * @property string $cTime
 */
class Drafts extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{drafts}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('uid, aid, bid, uuid, title, content', 'required'),
            array('uid, aid, bid, cTime', 'length', 'max' => 10),
            array('uuid', 'length', 'max' => 8),
            array('title', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, aid, bid, uuid, title, content, cTime', 'safe', 'on' => 'search'),
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
            'uid' => '用户',
            'aid' => '作者ID',
            'bid' => '所属小说ID',
            'uuid' => '草稿随机ID',
            'title' => '标题',
            'content' => '正文',
            'cTime' => '保存时间',
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
        $criteria->compare('aid', $this->aid, true);
        $criteria->compare('bid', $this->bid, true);
        $criteria->compare('uuid', $this->uuid, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('cTime', $this->cTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Drafts the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
