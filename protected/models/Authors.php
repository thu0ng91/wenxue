<?php

/**
 * This is the model class for table "{{authors}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 17:19:32
 * The followings are the available columns in table '{{authors}}':
 * @property integer $id
 * @property integer $uid
 * @property string $authorName
 * @property string $avatar
 * @property string $password
 * @property string $hashCode
 * @property string $content
 * @property integer $favors
 * @property integer $posts
 * @property integer $hits
 * @property integer $score
 * @property integer $cTime
 * @property integer $status
 */
class Authors extends CActiveRecord {

    public $newPassword;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{authors}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('hashCode', 'default', 'setOnEmpty' => true, 'value' => zmf::randMykeys(6)),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('uid, authorName, avatar, password', 'required'),
            array('avatar, skinUrl', 'checkUrl'),
            array('uid, favors, posts, hits, score, cTime, status,totalUrge,unUrge', 'numerical', 'integerOnly' => true),
            array('authorName', 'length', 'max' => 16),
            array('avatar,skinUrl', 'length', 'max' => 255),
            array('password', 'length', 'max' => 32),
            array('hashCode', 'length', 'max' => 6),
            array('content', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, authorName, avatar, password, hashCode, content, favors, posts, hits, score, cTime, status', 'safe', 'on' => 'search'),
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
            'uid' => '用户ID',
            'authorName' => '作者名',
            'avatar' => '作者头像',
            'skinUrl' => '皮肤图片地址',
            'password' => '密码',
            'newPassword' => '新密码',
            'hashCode' => '随机码',
            'content' => '作者简介',
            'favors' => '粉丝数',
            'posts' => '作品数',
            'hits' => '点击数',
            'score' => '积分',
            'cTime' => '创建时间',
            'status' => '状态',
            'totalUrge' => '总催更数',
            'unUrge' => '现催更数',
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
        $criteria->compare('authorName', $this->authorName, true);
        $criteria->compare('avatar', $this->avatar, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('hashCode', $this->hashCode, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('favors', $this->favors);
        $criteria->compare('posts', $this->posts);
        $criteria->compare('hits', $this->hits);
        $criteria->compare('score', $this->score);
        $criteria->compare('cTime', $this->cTime);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Authors the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function checkUrl($attribute, $params) {
        $url = $this->avatar;
        $urlb = $this->skinUrl;
        if (!Attachments::checkUrlDomain($url)) {
            $this->addError('avatar', '请使用站内图片哦~');
        } elseif (!Attachments::checkUrlDomain($urlb)) {
            $this->addError('skinUrl', '请使用站内图片哦~');
        }
    }

    public static function getOne($id, $imgSize = 'w120') {
        $info=Authors::model()->findByPk($id);
        $info['avatar']=  zmf::getThumbnailUrl($info['avatar'],$imgSize,'author');
        return $info;
    }

    public static function findByUid($uid) {
        return Authors::model()->find('uid=:uid', array(':uid' => $uid));
    }
    
    public static function findByName($truename){
        $info= Authors::model()->find('authorName=:authorName', array(
            ':authorName'=>$truename
        ));
        return $info;
    }

    public static function checkLogin($userInfo, $authorId) {
        if (!$userInfo) {
            return false;
        }
        $code = 'authorAuth-' . $userInfo['id'];
        $val = Yii::app()->session[$code];
        if ($authorId > 0 && $authorId == $userInfo['authorId'] && $val) {
            return true;
        }
        return false;
    }

    public static function otherTops($aid, $notInclude = 0, $limit = 10) {
        $arr=array(
            Books::STATUS_PUBLISHED,
            Books::STATUS_FINISHED
        );
        $items = Books::model()->findAll(array(
            'condition' => 'aid=:aid AND status='.Posts::STATUS_PASSED.' AND bookStatus IN('.join(',',$arr).')',
            'order' => 'hits DESC',
            'limit' => $limit,
            'params' => array(
                ':aid' => $aid
            )
        ));
        return $items;
    }
    
    public static function updateStatInfo($authorInfo){
        if(!$authorInfo || !$authorInfo['id']){
            return false;
        }
        $arr=array(
            Books::STATUS_PUBLISHED,
            Books::STATUS_FINISHED
        );
        $books=  Books::model()->findAll(array(
            'condition'=>'aid=:aid AND status='.Posts::STATUS_PASSED.' AND bookStatus IN('.join(',',$arr).')',
            'params'=>array(':aid'=>$authorInfo['id']),
            'select'=>'id,hits'
        ));
        $hits=$authorInfo['hits']+array_sum(CHtml::listData($books, 'id', 'hits'));
        $attr=array(
            'posts'=>count($books),
            'score'=>$hits,
        );
        return Authors::model()->updateByPk($authorInfo['id'], $attr);
    }

}
