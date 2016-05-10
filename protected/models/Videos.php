<?php

/**
 * This is the model class for table "{{videos}}".
 *
 * The followings are the available columns in table '{{videos}}':
 * @property string $id
 * @property string $uid
 * @property integer $logid
 * @property string $classify
 * @property string $title
 * @property string $content
 * @property string $url
 * @property string $swfurl
 * @property string $h5url
 * @property string $faceimg
 * @property integer $status
 * @property string $cTime
 */
class Videos extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{videos}}';
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
            array('uid, classify, url', 'required'),
            array('logid, status', 'numerical', 'integerOnly' => true),
            array('uid', 'length', 'max' => 11),
            array('classify', 'length', 'max' => 16),
            array('title, content, url, swfurl, h5url, faceimg,company,videoid', 'length', 'max' => 255),
            array('cTime', 'length', 'max' => 10),
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
            'uid' => '作者',
            'logid' => '内容ID',
            'classify' => '分类',
            'title' => '标题',
            'content' => '描述',
            'url' => '链接地址',
            'swfurl' => 'FLASH视频地址',
            'h5url' => 'H5视频地址',
            'faceimg' => '封面图',
            'status' => '状态',
            'cTime' => '创建时间',
            'company' => '来源',
            'videoid' => '视频ID',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Videos the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function getOne($id){
        return Videos::model()->findByPk($id);
    }

}
