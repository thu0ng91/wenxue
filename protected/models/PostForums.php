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
            array('title,faceImg,desc', 'required'),
            array('title', 'length', 'max' => 16),
            array('posterType', 'length', 'max' => 6),
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
            'posterType' => '谁可发帖',
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

    /**
     * 返回发帖者类别
     * @param string $type
     * @return string
     */
    public static function posterType($type) {
        $arr = array(
            'banzhu' => '版主',
            'reader' => '读者',
            'author' => '作者',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

    public static function getOne($id) {
        return PostForums::model()->findByPk($id);
    }

    public static function getUserFavorites($uid) {
        if (!$uid) {
            return array();
        }
        $sql = "SELECT pf.id,pf.title FROM {{post_forums}} pf,{{favorites}} f WHERE f.uid=:uid AND f.classify='forum' AND f.logid=pf.id ORDER BY f.cTime DESC";
        $res = Yii::app()->db->createCommand($sql);
        $res->bindValue(':uid', $uid);
        $items = $res->queryAll();
        return $items;
    }

    /**
     * 更新并统计每个版块的帖子数
     * @return bool
     */
    public static function updatePostsStat() {
        $sql = "UPDATE {{post_forums}} AS pf,(SELECT COUNT(id) AS total,fid FROM {{post_threads}} GROUP BY fid) AS tmp SET pf.posts=tmp.total WHERE pf.id=tmp.fid";
        $res = Yii::app()->db->createCommand($sql)->execute();
        return $res;
    }

    /**
     * 获取板块下近24小时最活跃用户
     * @param int $fid
     * @param int $limit
     * @return array
     */
    public static function getActivityUsers($fid, $limit = 12) {
        //过去24小时
        $now = zmf::now();
        $time = $now - 86400;
        $sql = "SELECT u.id,u.truename,u.avatar,count(pp.id) AS total FROM {{users}} u INNER JOIN {{post_posts}} pp ON pp.uid=u.id INNER JOIN {{post_threads}} pt ON pp.tid=pt.id WHERE pt.fid=:fid AND pp.cTime>=:time GROUP BY pp.uid ORDER BY total DESC LIMIT $limit";
        $res = Yii::app()->db->createCommand($sql);
        $res->bindValues(array(
            ':fid' => $fid,
            ':time' => $time
        ));
        $items = $res->queryAll();
        return $items;
    }

    public static function listAll() {
        $items = self::model()->findAll();
        return CHtml::listData($items, 'id', 'title');
    }

    /**
     * 判断用户是否可以在某版块发帖
     * @param array $forumInfo
     * @param array $userInfo
     * @return boolean
     */
    public static function addPostOrNot($forumInfo, $userInfo) {
        if (!$forumInfo || !$userInfo) {
            return false;
        }        
        if (!$forumInfo['posterType']) {
            return true;
        }
        if (($forumInfo['posterType'] == 'reader' && !$userInfo['authorId']) || ($forumInfo['posterType'] == 'author' && $userInfo['authorId'] > 0)) {
            return true;
        }
        //判断是否版主     
        if (ForumAdmins::checkForumPower($userInfo['id'], $forumInfo['id'], 'addPost')) {
            return true;
        }
        return false;
    }
    
    /**
     * 判断用户是否可以在某帖子回帖
     * @param array $threadInfo
     * @param array $userInfo
     * @return boolean
     */
    public static function replyPostOrNot($threadInfo, $userInfo) {
        if (!$threadInfo || !$userInfo) {
            return false;
        }        
        if (!$threadInfo['posterType']) {
            return true;
        }
        if (($threadInfo['posterType'] == 'reader' && !$userInfo['authorId']) || ($threadInfo['posterType'] == 'author' && $userInfo['authorId'] > 0)) {
            return true;
        }
        //判断是否版主     
        if (ForumAdmins::checkForumPower($userInfo['id'], $threadInfo['fid'], 'addPostReply')) {
            return true;
        }
        return false;
    }

}
