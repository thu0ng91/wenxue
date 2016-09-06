<?php

/**
 * This is the model class for table "{{task}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:13
 * The followings are the available columns in table '{{task}}':
 * @property string $id
 * @property string $title
 * @property string $faceImg
 * @property string $desc
 * @property string $action
 * @property integer $type
 * @property integer $continuous
 * @property integer $num
 * @property string $score
 * @property string $startTime
 * @property string $endTime
 * @property string $times
 * @property string $cTime
 */
class Task extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{task}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('title, action', 'required'),
            array('title', 'length', 'max' => 50),
            array('faceImg, desc', 'length', 'max' => 255),
            array('action', 'length', 'max' => 16),
            array('times, cTime', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, faceImg, desc, action, times, cTime', 'safe', 'on' => 'search'),
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
            'faceImg' => '封面图',
            'desc' => '描述',
            'action' => '操作标识',
            'times' => '参与人数',
            'cTime' => '创建时间',
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
        $criteria->compare('faceImg', $this->faceImg, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('action', $this->action, true);
        $criteria->compare('times', $this->times, true);
        $criteria->compare('cTime', $this->cTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Task the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getOne($id) {
        return Task::model()->findByPk($id);
    }

    public static function listAll() {
        $items = Task::model()->findAll();
        return CHtml::listData($items, 'id', 'title');
    }

    public static function addTaskLog($userInfo, $action) {
        if (!$userInfo['id'] || !$userInfo['groupid'] || !$action) {
            return array(
                'status' => 0,
                'msg' => '缺少参数'
            );
        }
        $sql = "SELECT t.id,gt.id AS groupTaskId,tl.id AS taskLogId,t.title AS taskTitle,t.faceImg AS taskFaceImg,t.desc AS taskDesc,tl.status AS taskStatus,tl.score,tl.times,gt.type,gt.continuous,gt.num,gt.startTime,gt.endTime FROM {{task}} t INNER JOIN {{task_logs}} tl ON t.id=tl.tid INNER JOIN {{group_tasks}} gt ON t.id=gt.tid WHERE tl.uid=:uid AND gt.gid=:gid AND gt.action=:action";
        $res = Yii::app()->db->createCommand($sql);
        $res->bindValues(array(
            ':uid' => $userInfo['id'],
            ':gid' => $userInfo['groupid'],
            ':action' => $action
        ));
        $info = $res->queryRow();
        $now = zmf::now();
        if (!$info) {
            return array(
                'status' => 0,
                'msg' => '未领取相关任务'
            );
        } elseif ($info['startTime'] > 0 && $info['startTime'] > $now) {
            return array(
                'status' => 0,
                'msg' => '任务尚未开始'
            );
        } elseif ($info['endTime'] > 0 && $info['endTime'] < $now) {
            return array(
                'status' => 0,
                'msg' => '任务已经结束'
            );
        } elseif ($info['taskStatus'] == TaskLogs::STATUS_REACHED) {
            //任务已达成
            return array(
                'status' => 1,
                'msg' => '任务已达成'
            );
        } elseif ($info['num'] <= $info['times']) {
            //任务参与数已超过任务规定参与数，则说明本任务已完成
            TaskLogs::finishTask($userInfo, $info['taskLogId']);
            return array(
                'status' => 1,
                'msg' => '任务已达成'
            );
        }
        //处理任务
        if ($info['type'] == GroupTasks::TYPE_ONETIME) {//如果是一次性任务
            //由于判断是否参与任务是在记录用户操作之后，所以可以在此判断用户此类操作的次数
            //根据次数判断是否已达成任务
            $num = UserAction::statAction($userInfo['id'], $action);
            if ($info['num'] <= $num) {//已达成任务
                TaskLogs::finishTask($userInfo, $info['taskLogId']);
                return array(
                    'status' => 1,
                    'msg' => '任务已达成'
                );
            } else {//否则没有达成任务
                //记录一条参与任务的记录
                $taskRecordAttr = array(
                    'uid' => $userInfo['id'],
                    'tid' => $info['id'],
                );
                TaskRecords::simpleAdd($taskRecordAttr);
                //更新我的任务的参与次数
                TaskLogs::model()->updateByPk($info['taskLogId'], array('times' => $num));
                return array(
                    'status' => 1,
                    'msg' => $info
                );
            }
        } elseif ($info['continuous']) {//如果是连续性任务
            
            
        } else {//既不是一次性任务也不是连续性任务
        }

        return array(
            'status' => 1,
            'msg' => $info
        );
    }

}
