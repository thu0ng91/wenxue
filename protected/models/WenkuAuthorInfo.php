<?php

/**
 * This is the model class for table "{{author_info}}".
 *
 * The followings are the available columns in table '{{author_info}}':
 * @property string $id
 * @property string $uid
 * @property string $author
 * @property string $classify
 * @property string $content
 * @property string $hits
 * @property string $cTime
 * @property integer $status
 */
class WenkuAuthorInfo extends CActiveRecord {

    const CLASSIFY_INFO = 1; //简介
    const CLASSIFY_ZILIAO = 2; //资料
    const CLASSIFY_CHENGJIU = 3; //文学成就
    const CLASSIFY_SHENGPING = 4; //生平
    const CLASSIFY_PINGJIA = 5; //历史评价
    const CLASSIFY_GUSHI = 6; //故事

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return '{{wenku_author_info}}';
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
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('classify', 'default', 'setOnEmpty' => true, 'value' => WenkuAuthorInfo::CLASSIFY_INFO),
            array('author, classify, content', 'required'),
            array('status,comments', 'numerical', 'integerOnly' => true),
            array('uid, author, hits, cTime,likes,dislikes', 'length', 'max' => 11),
            array('classify', 'length', 'max' => 32),
            array('comments', 'length', 'max' => 8),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'authorInfo' => array(self::BELONGS_TO, 'WenkuAuthor', 'author'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => 'Uid',
            'author' => '作者',
            'classify' => '分类',
            'content' => '内容',
            'hits' => '点击',
            'cTime' => '创建时间',
            'status' => '状态',
            'comments' => '评论数',
            'likes' => '赞',
            'dislikes' => '踩'
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
        $criteria->compare('author', $this->author, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('comments', $this->comments);
        $criteria->compare('likes', $this->likes);
        $criteria->compare('dislikes', $this->dislikes);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AuthorInfo the static model class
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
            self::CLASSIFY_INFO => '简介',
            self::CLASSIFY_ZILIAO => '资料',
            self::CLASSIFY_CHENGJIU => '文学成就',
            self::CLASSIFY_SHENGPING => '生平',
            self::CLASSIFY_PINGJIA => '历史评价',
            self::CLASSIFY_GUSHI => '轶事典故',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

}
