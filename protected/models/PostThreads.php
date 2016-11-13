<?php

/**
 * This is the model class for table "{{post_threads}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-04 22:17:10
 * The followings are the available columns in table '{{post_threads}}':
 * @property string $id
 * @property string $fid
 * @property string $type
 * @property string $uid
 * @property string $title
 * @property string $faceImg
 * @property string $hits
 * @property string $posts
 * @property string $comments
 * @property string $favorites
 * @property integer $styleStatus
 * @property integer $digest
 * @property integer $top
 * @property integer $open
 * @property integer $display
 * @property string $lastpost
 * @property string $lastposter
 * @property string $cTime
 */
class PostThreads extends CActiveRecord {

    public $content; //帖子第一楼正文

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return '{{post_threads}}';
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
            array('fid,uid,title', 'required'),
            array('styleStatus, digest, top, open, display,status', 'numerical', 'integerOnly' => true),
            array('fid, type, uid,aid, hits, posts, comments, favorites, lastpost, lastposter, cTime,props,postExpiredTime,voteExpiredTime', 'length', 'max' => 10),
            array('title', 'length', 'max' => 80),
            array('posterType', 'length', 'max' => 6),
            array('faceImg', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, fid, type, uid, title, faceImg, hits, posts, comments, favorites, styleStatus, digest, top, open, display, lastpost, lastposter, cTime', 'safe', 'on' => 'search'),
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
            'fid' => '所属版块',
            'type' => '文章分类',
            'uid' => '用户ID',
            'aid' => '作者ID',
            'title' => '标题',
            'faceImg' => '封面图',
            'hits' => '点击',
            'posts' => '楼层数',
            'comments' => '评论数',
            'favorites' => '收藏数',
            'styleStatus' => '显示状态',
            'digest' => '是否加精',
            'top' => '是否置顶',
            'open' => '是否开放回复',
            'display' => '是否倒序',
            'lastpost' => '最后回帖时间',
            'lastposter' => '最后回帖人',
            'cTime' => '发布时间',
            'status' => '帖子状态',
            'props' => '赞赏数',
            'posterType' => '那些可以回帖',
            'postExpiredTime' => '投稿结束时间',
            'voteExpiredTime' => '投票结束时间',
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
        $criteria->compare('fid', $this->fid, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('faceImg', $this->faceImg, true);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('posts', $this->posts, true);
        $criteria->compare('comments', $this->comments, true);
        $criteria->compare('favorites', $this->favorites, true);
        $criteria->compare('styleStatus', $this->styleStatus);
        $criteria->compare('digest', $this->digest);
        $criteria->compare('top', $this->top);
        $criteria->compare('open', $this->open);
        $criteria->compare('display', $this->display);
        $criteria->compare('lastpost', $this->lastpost, true);
        $criteria->compare('lastposter', $this->lastposter, true);
        $criteria->compare('cTime', $this->cTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PostThreads the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getOne($id) {
        return self::model()->findByPk($id);
    }

    /**
     * 更新帖子的统计
     * @param int $id
     * @return boolean
     */
    public static function updateStat($id) {
        if (!$id) {
            return false;
        }
        $posts = PostPosts::model()->count('tid=:tid AND isFirst=0', array(
            ':tid' => $id
        ));
        $attr = array(
            'posts' => $posts
        );
        return self::model()->updateByPk($id, $attr);
    }

    /**
     * 返回用户的某板块的其他帖子
     * @param int $uid
     * @param int $id
     * @param int $fid
     * @return array
     */
    public static function getUserOtherPosts($uid, $id, $fid, $limit = 10, $isAuthor = false) {
        if ($isAuthor) {
            $posts = PostThreads::model()->findAll(array(
                'condition' => 'aid=:uid AND fid=:fid AND id!=:id',
                'select' => 'id,title',
                'order' => 'cTime DESC',
                'limit' => $limit,
                'params' => array(
                    ':uid' => $uid,
                    ':fid' => $fid,
                    ':id' => $id,
                )
            ));
        } else {
            $posts = PostThreads::model()->findAll(array(
                'condition' => 'uid=:uid AND fid=:fid AND id!=:id',
                'select' => 'id,title',
                'order' => 'cTime DESC',
                'limit' => $limit,
                'params' => array(
                    ':uid' => $uid,
                    ':fid' => $fid,
                    ':id' => $id,
                )
            ));
        }

        return $posts;
    }

    public static function getForumTops($fid, $id, $limit = 10) {
        $posts = PostThreads::model()->findAll(array(
            'condition' => 'fid=:fid AND id!=:id',
            'select' => 'id,title',
            'order' => 'hits DESC',
            'limit' => $limit,
            'params' => array(
                ':fid' => $fid,
                ':id' => $id,
            )
        ));

        return $posts;
    }

}
