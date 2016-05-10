<?php

/**
 * This is the model class for table "{{zazhi}}".
 *
 * The followings are the available columns in table '{{zazhi}}':
 * @property integer $id
 * @property integer $uid
 * @property string $title
 * @property string $content
 * @property integer $faceimg
 * @property integer $comments
 * @property string $favorites
 * @property integer $top
 * @property integer $hits
 * @property integer $status
 * @property integer $cTime
 * @property integer $updateTime
 */
class Zazhi extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{zazhi}}';
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
            array('uid, title,faceimg', 'required'),
            array('uid, faceimg, comments, top, hits, status, cTime, updateTime', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('favorites', 'length', 'max' => 10),
            array('content', 'safe'),
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
            'uid' => '作者ID',
            'title' => '标题',
            'content' => '简介',
            'faceimg' => '封面图',
            'comments' => '评论数',
            'favorites' => '收藏数',
            'top' => '是否置顶',
            'hits' => '阅读数',
            'status' => '状态',
            'cTime' => '创建时间',
            'updateTime' => '最近更新时间',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Zazhi the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function listTitles() {
        $items = Zazhi::model()->findAll(array(
            'select' => 'id,title'
        ));
        return CHtml::listData($items, 'id', 'title');
    }
    
    public static function getOthers($id,$limit=8,$imgSize=240){
        $items = Zazhi::model()->findAll(array(
            'condition'=>'id!=:id AND status='.Posts::STATUS_PASSED,
            'params'=>array(':id'=>$id),
            'select' => 'id,title,faceimg,content',
            'limit'=>$limit
        ));
        foreach ($items as $k=>$val){
            $items[$k]['faceimg']=Attachments::faceImg($val, $imgSize, 'zazhi');
        }
        return $items;
    }

    public static function getLatestOne() {
        $sql = "SELECT id,title,content,faceimg FROM {{zazhi}} WHERE `status`=" . Posts::STATUS_PASSED . " ORDER BY cTime DESC";
        $item = Yii::app()->db->createCommand($sql)->queryRow();
        if(!$item){
            return false;
        }
        $item['faceimg'] = Attachments::faceImg($item, '960', 'zazhi');
        return $item;
    }
    
    public static function getOne($id){
        $sql = "SELECT id,title,content,faceimg,status,favorites,comments FROM {{zazhi}} WHERE id='{$id}'";
        $item = Yii::app()->db->createCommand($sql)->queryRow();
        $item['faceimg'] = Attachments::faceImg($item, '', 'zazhi');
        return $item;
    }

    public static function getChapters($zid) {
        $items = Posts::model()->findAll(array(
            'condition' => "zazhi='{$zid}' AND status=" . Posts::STATUS_PASSED,
            'order' => '`order` ASC,cTime ASC',
            'select' => 'id,title'
        ));
        $chapters = array();
        foreach ($items as $k => $val) {
            $chapters[]=array(
                'id'=>$val['id'],
                'title'=>$val['title'],
                'url'=>Yii::app()->createUrl('/zazhi/chapter', array('zid' => $zid, 'id' => $val['id'])),
            );
        }
        return $chapters;
    }
    
    /**
     * 更新统计一篇杂志的评论总数和点赞数
     * @param type $id
     * @return boolean
     */
    public static function updateStat($id){
        if(!$id){
            return false;
        }
        $sqlCCount="SELECT COUNT(c.id) AS total FROM {{comments}} c,{{posts}} p WHERE p.zazhi='{$id}' AND p.status=".Posts::STATUS_PASSED." AND (c.classify='posts' AND c.status=".Posts::STATUS_PASSED." AND c.logid=p.id)";
        $cCount=  Yii::app()->db->createCommand($sqlCCount)->queryRow();
        
        $sqlCCount="SELECT COUNT(f.id) AS total FROM {{favorites}} f,{{posts}} p WHERE p.zazhi='{$id}' AND p.status=".Posts::STATUS_PASSED." AND f.classify='post' AND f.logid=p.id";
        $fCount=  Yii::app()->db->createCommand($sqlCCount)->queryRow();
        
        $sqlCCount="SELECT SUM(hits) AS total FROM {{posts}} WHERE zazhi='{$id}' AND status=".Posts::STATUS_PASSED;
        $hCount=  Yii::app()->db->createCommand($sqlCCount)->queryRow();
        
        Zazhi::model()->updateByPk($id, array(
            'favorites'=>$fCount['total'],
            'comments'=>$cCount['total'],
            'hits'=>$hCount['total'],
        ));
        return true;
    }

}
