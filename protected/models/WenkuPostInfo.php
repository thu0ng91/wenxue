<?php

/**
 * This is the model class for table "{{post_info}}".
 *
 * The followings are the available columns in table '{{post_info}}':
 * @property string $id
 * @property string $uid
 * @property string $pid
 * @property integer $classify
 * @property string $content
 * @property integer $comments
 * @property string $hits
 * @property string $cTime
 * @property integer $status
 * @property integer $likes
 * @property integer $dislikes
 */
class WenkuPostInfo extends CActiveRecord {

    const CLASSIFY_FFANYI = 1; //古诗的翻译

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return '{{wenku_post_info}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('pid, classify, content', 'required'),
            array('classify, comments, status, likes, dislikes', 'numerical', 'integerOnly' => true),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('uid, pid, hits, cTime', 'length', 'max' => 11),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'postInfo' => array(self::BELONGS_TO, 'WenkuPosts', 'pid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '作者',
            'pid' => '古诗ID',
            'classify' => '分类',
            'content' => '内容',
            'comments' => '评论数',
            'hits' => '点击',
            'cTime' => '创建时间',
            'status' => '状态',
            'likes' => '点赞数',
            'dislikes' => '点踩数',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PostInfo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 返回状态对应的类型名称
     * @param type $type
     * @return string
     */
    public static function exClassify($type = '') {
        $arr = array(
            self::CLASSIFY_FFANYI => '翻译',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

}
