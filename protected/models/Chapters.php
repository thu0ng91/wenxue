<?php

/**
 * This is the model class for table "{{chapters}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-11 10:54:06
 * The followings are the available columns in table '{{chapters}}':
 * @property string $id
 * @property string $uid
 * @property string $aid
 * @property string $bid
 * @property string $title
 * @property string $content
 * @property string $words
 * @property string $comments
 * @property string $hits
 * @property integer $status
 * @property integer $vip
 * @property string $cTime
 * @property string $updateTime
 * @property string $postTime
 */
class Chapters extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{chapters}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('cTime,updateTime,postTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('uid, aid, bid, title, content,chapterNum', 'required'),
            array('status, vip,psPosition,chapterStatus', 'numerical', 'integerOnly' => true),
            array('uid, aid, bid, words, comments, hits, cTime, updateTime, postTime,props', 'length', 'max' => 10),
            array('title', 'length', 'max' => 255),
            array('chapterNum', 'length', 'max' => 6),
            array('postscript', 'length', 'max' => 1024),
            array('content', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, aid, bid, title, content, words, comments, hits, status, vip, cTime, updateTime, postTime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'bookInfo' => array(self::BELONGS_TO, 'Books', 'bid'),
            'authorInfo' => array(self::BELONGS_TO, 'Authors', 'aid'),
        );
    }
    
    public function beforeSave(){
        $this->words=mb_strlen($this->content, 'UTF-8');
        return true;
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
            'title' => '标题',
            'content' => '正文',
            'words' => '字数',
            'comments' => '评论数',
            'hits' => '点击数',
            'status' => '状态',
            'vip' => '是否VIP',
            'cTime' => '创建时间',
            'updateTime' => '更新时间',
            'postTime' => '发布时间',
            'postscript' => '还有话说',
            'psPosition' => '展示位置',
            'chapterNum' => '章节号',
            'chapterStatus' => '章节状态',
            'props' => '赞赏数',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('words', $this->words, true);
        $criteria->compare('comments', $this->comments, true);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('vip', $this->vip);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('updateTime', $this->updateTime, true);
        $criteria->compare('postTime', $this->postTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Chapters the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function exStatus($type) {
        $arr = array(
            Posts::STATUS_NOTPASSED => '草稿箱',
            Posts::STATUS_PASSED => '正常',
            Posts::STATUS_STAYCHECK => '待审核',
            Posts::STATUS_DELED => '已删除',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }
    
    public static function exStatusForAuthor($type) {
        $arr = array(
            Posts::STATUS_NOTPASSED => '存草稿箱',
            Posts::STATUS_PASSED => '立即发布'
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }
    
    public static function exPsPosition($type) {
        $arr = array(
            '0' => '底部',
            '1' => '顶部'
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }
    
    public static function getByBook($id,$adminLogin=false){
        if(!$id){
            return array();
        }
        $items=  Chapters::model()->findAll(array(
            'condition'=>'bid=:bid AND status='.Posts::STATUS_PASSED.(!$adminLogin ? ' AND chapterStatus='.Books::STATUS_PUBLISHED : ''),
            'order'=>'chapterNum ASC',
            'params'=>array(
                ':bid'=>$id
            ),
            'select'=>'id,title,chapterStatus,chapterNum'
        ));
        return $items;
    }
    
    public static function getOne($cid){
        return Chapters::model()->findByPk($cid);
    }
    
    public static function checkTip($cid,$uid){
        if(!$cid || !$uid){
            return false;
        }
        $tipInfo=  Tips::model()->find(array(
            'condition'=>"uid=:uid AND logid=:logid AND classify='chapter'",
            'select'=>'id,status',
            'params'=>array(
                ':uid'=>$uid,
                ':logid'=>$cid,
            )
        ));
        return !empty($tipInfo) ? $tipInfo : false;
    }
    
    public static function text($content){
        $content=  nl2br($content);
        $content=str_replace('<br />', '</p><p>', $content);
        $content='<p>'.$content.'</p>';
        $content = Keywords::linkWords($content);
        return $content;
    }
    public static function mobileText($content){       
        $replace = array(
            "/<\/p>\s*<p>/i",
            "/<p>/i",
            "/<\/p>/i",
            
        );
        $to = array(
            '',
            '',
            '',
        );
        $content = preg_replace($replace, $to, $content);       
        $content = Keywords::linkWords($content);
        return $content;
    }
    
    /**
     * 处理内容
     * @param type $content
     * @return type
     */
    public static function handleContent($content) {
        $content = strip_tags($content, '<p><br/><br>');
        $replace = array(
            "/style=\"[^\"]*?\"/i",
            "/<p><br\/><\/p>/i",
            "/<p><\/p>/i",
        );
        $to = array(
            ''
        );
        $content = preg_replace($replace, $to, $content);        
        return $content;
    }

}
