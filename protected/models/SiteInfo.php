<?php

/**
 * This is the model class for table "{{site_info}}".
 * 站点信息，如关于我们等
 * The followings are the available columns in table '{{site_info}}':
 * @property string $id
 * @property string $uid
 * @property string $colid
 * @property string $faceimg
 * @property string $code
 * @property string $title
 * @property string $content
 * @property string $hits
 * @property string $cTime
 * @property string $updateTime
 * @property integer $status
 */
class SiteInfo extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{site_info}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('uid, title,code, content', 'required'),
            array('code', 'unique'),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('status', 'numerical', 'integerOnly' => true),
            array('cTime,updateTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('uid, colid, faceimg, hits, cTime, updateTime', 'length', 'max' => 10),
            array('code', 'length', 'max' => 16),
            array('title', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, colid, faceimg, code, title, content, hits, cTime, updateTime, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'authorInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '作者',
            'colid' => '所属分类',
            'faceimg' => '封面图',
            'code' => '文章主旨',
            'title' => '标题',
            'content' => '正文',
            'hits' => '点击',
            'cTime' => '创建时间',
            'updateTime' => '更新时间',
            'status' => '状态',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SiteInfo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function exTypes($type) {
        $arr = array(
            'about' => '关于本站或关于我',
            'author' => '关于本站创作者们',
            'copyright' => '版权说明',
            'contribution' => '投稿',
            'cooperation' => '合作',
            'appeal ' => '申诉',
            'terms ' => '本站协议',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }
    
    public static function searchTypes($type){
        $arr=array(
            'author'=>'作者',
            'book'=>'小说',
            'chapter'=>'章节',
            'user'=>'用户',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

}
