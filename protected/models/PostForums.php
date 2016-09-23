<?php

/**
 * This is the model class for table "{{post_forums}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-04 20:55:18
 * The followings are the available columns in table '{{post_forums}}':
 * @property string $id
 * @property string $title
 * @property string $desc
 * @property string $faceImg
 * @property string $posts
 * @property string $favors
 */
class PostForums extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{post_forums}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required'),
            array('title', 'length', 'max' => 16),
            array('desc, faceImg', 'length', 'max' => 255),
            array('posts, favors', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, desc, faceImg, posts, favors', 'safe', 'on' => 'search'),
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
            'desc' => '描述',
            'faceImg' => '封面图',
            'posts' => '作品数',
            'favors' => '关注数',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('faceImg', $this->faceImg, true);
        $criteria->compare('posts', $this->posts, true);
        $criteria->compare('favors', $this->favors, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PostTypes the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getOne($id) {
        return PostForums::model()->findByPk($id);
    }
    
    public static function getUserFavorites($uid){
        if(!$uid){
            return array();
        }
        $items= Favorites::model()->findAll("uid=:uid AND classify='forum'", array(':uid'=>$uid));
        return CHtml::listData($items, 'id', 'logid');
    }

}
