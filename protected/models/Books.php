<?php

/**
 * This is the model class for table "{{books}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 22:25:28
 * The followings are the available columns in table '{{books}}':
 * @property string $id
 * @property string $uid
 * @property string $aid
 * @property string $title
 * @property string $faceImg
 * @property string $desc
 * @property string $content
 * @property string $favorites
 * @property string $hits
 * @property string $chapters
 * @property string $comments
 * @property string $words
 * @property integer $vip
 * @property integer $bookStatus
 * @property integer $status
 */
class Books extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{books}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cTime,updateTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('uid, aid,colid, title', 'required'),
            array('vip, bookStatus, status,top', 'numerical', 'integerOnly' => true),
            array('uid, aid,colid,favorites, hits, chapters, comments, words,topTime', 'length', 'max' => 10),
            array('title, faceImg, desc', 'length', 'max' => 255),
            array('content', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, aid, title, faceImg, desc, content, favorites, hits, chapters, comments, words, vip, bookStatus, status', 'safe', 'on' => 'search'),
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
            'aid' => '作者ID',
            'colid' => '分类',
            'title' => '小说名',
            'faceImg' => '封面图',
            'desc' => '推荐语',
            'content' => '简介',
            'favorites' => '收藏次数',
            'hits' => '点击次数',
            'chapters' => '章节数',
            'comments' => '评论数',
            'words' => '总字数',
            'vip' => '是否VIP可看',
            'bookStatus' => '小说状态',
            'status' => '系统状态',
            'top' => '是否置顶',
            'cTime' => '创建时间',
            'updateTime' => '更新时间',
            'topTime' => '置顶时间',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('faceImg', $this->faceImg, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('favorites', $this->favorites, true);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('chapters', $this->chapters, true);
        $criteria->compare('comments', $this->comments, true);
        $criteria->compare('words', $this->words, true);
        $criteria->compare('vip', $this->vip);
        $criteria->compare('bookStatus', $this->bookStatus);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Books the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function getIndexTops(){
        $cols=  Column::allCols();
        $posts=array();
        foreach ($cols as $colid=>$colTitle){
            $_sql="SELECT b.id AS bookId,a.id AS authorId,a.authorName,b.title,b.faceImg FROM {{authors}} a,{{books}} b WHERE b.colid=:colid AND b.aid=a.id ORDER BY b.topTime DESC LIMIT 48";
            $_res=YII::app()->db->createCommand($_sql);
            $_res->bindValue(':colid',$colid);
            $_posts=$_res->queryAll();
            $posts[$colid]['colInfo']=array(
                'colid'=>$colid,
                'colTitle'=>$colTitle
            );
            $posts[$colid]['posts']=$_posts;
        }
        return $posts;
    }
    
    public static function getOne($id){
        if(!$id){
            return false;
        }
        $info=  Books::model()->findByPk($id);
        return $info;
    }

}
