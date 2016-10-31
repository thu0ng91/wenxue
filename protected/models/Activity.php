<?php

/**
 * This is the model class for table "{{activity}}".
 *
 * The followings are the available columns in table '{{activity}}':
 * @property string $id
 * @property string $title
 * @property string $desc
 * @property string $faceimg
 * @property string $content
 * @property string $startTime
 * @property string $expiredTime
 * @property string $voteStart
 * @property string $voteEnd
 * @property string $province
 * @property integer $status
 * @property string $cTime
 */
class Activity extends CActiveRecord {

    const STATUS_NOTPASSED = 0; //未通过审核
    const STATUS_PASSED = 1; //正常状态
    const STATUS_EXPIRED = 2; //已过期
    const STATUS_DELED = 3; //已删除

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return '{{activity}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title,voteNum,voteType,type', 'required'),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('status,voteType,voteNum', 'numerical', 'integerOnly' => true),
            array('title, desc', 'length', 'max' => 255),
            array('voteNum', 'length', 'max' => 3),
            array('type', 'length', 'max' => 16),
            array('startTime, expiredTime, voteStart, voteEnd, province, cTime,faceimg', 'length', 'max' => 10),
            array('content', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, desc, faceimg, content, startTime, expiredTime, voteStart, voteEnd, province, status, cTime', 'safe', 'on' => 'search'),
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
            'title' => '活动标题',
            'desc' => '活动简介',
            'faceimg' => '活动封面图',
            'content' => '活动描述',
            'startTime' => '报名开始时间',
            'expiredTime' => '报名结束时间',
            'voteStart' => '投票开始时间',
            'voteEnd' => '投票结束时间',
            'province' => '所在省份可参与',
            'status' => 'Status',
            'cTime' => 'C Time',
            'voteNum' => '每人可投次数',
            'voteType' => '参与方式',
            'type' => '活动类型',
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
        $criteria->compare('faceimg', $this->faceimg, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('startTime', $this->startTime, true);
        $criteria->compare('expiredTime', $this->expiredTime, true);
        $criteria->compare('voteStart', $this->voteStart, true);
        $criteria->compare('voteEnd', $this->voteEnd, true);
        $criteria->compare('province', $this->province, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('cTime', $this->cTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Activity the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getOne($id) {
        return Activity::model()->findByPk($id);
    }

    public static function getAllByUser($userInfo) {
        if (!$userInfo) {
            return false;
        }
        $now = zmf::now();
        $items = Activity::model()->findAll(array(
            'condition' => "`type`='posts' AND (province=0 OR province='{$userInfo['province']}') AND (startTime<='{$now}' OR startTime=0) AND (expiredTime>='{$now}' OR expiredTime=0) AND `status`=" . Activity::STATUS_PASSED,
            'select' => 'id,title'
        ));
        return CHtml::listData($items, 'id', 'title');
    }

    public static function checkStatus($id, $from = 'vote', $activityInfo = array()) {
        if (empty($activityInfo)) {
            $activityInfo = self::getOne($id);
        }
        if (!$activityInfo || $activityInfo['status'] == Activity::STATUS_DELED) {
            return array(
                'status' => 0,
                'msg' => '不存在此活动，请核实'
            );
        } elseif ($activityInfo['status'] == Activity::STATUS_EXPIRED) {
            return array(
                'status' => -2,
                'msg' => '活动已结束，下次早点来哦~'
            );
        }
        $now = zmf::now();
        if ($activityInfo['startTime'] > $now) {
            return array(
                'status' => -1,
                'msg' => '活动未开始'
            );
        }
        if ($from == 'vote') {
            if ($activityInfo['voteStart'] > $now) {
                return array(
                    'status' => -4,
                    'msg' => '投票未开始'
                );
            } elseif ($activityInfo['voteEnd'] > 0 && $activityInfo['voteEnd'] < $now) {
                Activity::model()->updateByPk($id, array('status' => Activity::STATUS_EXPIRED));
                return array(
                    'status' => -2,
                    'msg' => '活动已结束，下次早点来哦~'
                );
            }
        } elseif ($from == 'add') {
            if ($activityInfo['expiredTime'] > 0 && $activityInfo['expiredTime'] < $now) {
                return array(
                    'status' => -3,
                    'msg' => '投稿已结束，下次早点来哦~'
                );
            }
        }
        return array(
            'status' => 1,
            'msg' => $activityInfo
        );
    }

    public static function voteTypes($type) {
        $arr = array(
            '1' => '整个活动',
            '2' => '每天',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

    public static function types($type) {
        $arr = array(
            'books' => '作品',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

}
