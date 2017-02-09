<?php

/**
 * This is the model class for table "{{post_posts}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-05 04:08:38
 * The followings are the available columns in table '{{post_posts}}':
 * @property integer $id
 * @property integer $uid
 * @property string $aid
 * @property string $tid
 * @property string $content
 * @property integer $comments
 * @property string $favors
 * @property integer $cTime
 * @property integer $updateTime
 * @property integer $open
 * @property integer $status
 */
class PostPosts extends CActiveRecord {
    
    const OPEN_COMMENT=1;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{post_posts}}';
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
            array('open', 'default', 'setOnEmpty' => true, 'value' => self::OPEN_COMMENT),
            
            array('uid, tid, content', 'required'),
            array('uid, comments, cTime, updateTime, open, status,isFirst,platform,anonymous', 'numerical', 'integerOnly' => true),
            array('aid, tid,props', 'length', 'max' => 10),
            array('favors', 'length', 'max' => 11),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, aid, tid, content, comments, favors, cTime, updateTime, open, status', 'safe', 'on' => 'search'),
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
            'uid' => '作者ID',
            'aid' => '作者ID',
            'tid' => '帖子ID',
            'content' => '正文',
            'comments' => '评论数',
            'favors' => '点赞数',
            'cTime' => '创建时间',
            'updateTime' => '最近更新时间',
            'open' => '是否允许评论',
            'status' => '楼层状态',
            'isFirst' => '是否首贴',
            'platform' => '来源',
            'props' => '赞赏数',
            'anonymous' => '匿名',
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
        $criteria->compare('uid', $this->uid);
        $criteria->compare('aid', $this->aid, true);
        $criteria->compare('tid', $this->tid, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('comments', $this->comments);
        $criteria->compare('favors', $this->favors, true);
        $criteria->compare('cTime', $this->cTime);
        $criteria->compare('updateTime', $this->updateTime);
        $criteria->compare('open', $this->open);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PostPosts the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function getOne($pk){
        return self::model()->findByPk($pk);
    }

}
