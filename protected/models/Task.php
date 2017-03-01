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
        $sql = "SELECT t.id,gt.id AS groupTaskId,tl.id AS taskLogId,t.title AS taskTitle,t.faceImg AS taskFaceImg,t.desc AS taskDesc,tl.status AS taskStatus,tl.score,tl.exp,tl.times,tl.cTime AS userStartTime,gt.action,gt.type,gt.continuous,gt.num,gt.startTime,gt.endTime FROM {{task}} t INNER JOIN {{task_logs}} tl ON t.id=tl.tid INNER JOIN {{group_tasks}} gt ON t.id=gt.tid WHERE tl.uid=:uid AND gt.gid=:gid AND gt.action=:action AND tl.status=0";
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
                'msg' => '未领取相关任务或该任务已完成'
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
        }
        //自从接受任务开始，当天该操作的总次数如果大于任务规定数，则不做后续判断
        //必须是大于而不是大于等于，是因为用户操作记录总是在本判断之前
        $todayNum = UserAction::statTodayAction($userInfo, $info);
        if ($todayNum > $info['num']) {
            return array(
                'status' => 0,
                'msg' => '今日该操作已达上限'
            );
        }
        //处理任务
        $finished = false;
        if ($info['type'] == GroupTasks::TYPE_ONETIME) {//如果是一次性任务
            //由于判断是否参与任务是在记录用户操作之后，所以可以在此判断用户此类操作的次数
            //根据次数判断是否已达成任务（必须在用户接受任务及任务结束时间内）            
            $num = UserAction::statTaskAction($userInfo, $info);
            if ($info['num'] <= $num) {
                $finished = true;
            } else {
                $finished = FALSE;
            }
        } else {//如果是连续性任务
            //如果是连续性任务，则在参与任务后这段时间内，必须每天满足$info['num']条记录才行
            $finished = UserAction::checkTaskAction($userInfo, $info);
        }
        //记录一条参与任务的记录
        $taskRecordAttr = array(
            'uid' => $userInfo['id'],
            'tid' => $info['id'],
        );
        TaskRecords::simpleAdd($taskRecordAttr);
        if ($finished) {//已达成任务
            TaskLogs::finishTask($userInfo, $info, $info['taskLogId']);
            return array(
                'status' => 2,
                'msg' => '任务已达成'
            );
        } else {//否则没有达成任务            
            //更新我的任务的参与次数
            TaskLogs::model()->updateByPk($info['taskLogId'], array('times' => $num));
            return array(
                'status' => 3,
                'msg' => $info
            );
        }
    }

    /**
     * 获取用户的所有任务列表
     * @param array $userInfo
     */
    public static function getUserTasks($userInfo) {
        if (!$userInfo['id'] || !$userInfo['groupid']) {
            return array();
        }
        $now = zmf::now();
        $sql = "SELECT t.id,t.title,t.faceImg,gt.type,gt.continuous,gt.days,gt.num,gt.score,gt.exp,gt.endTime,'' AS extraDesc FROM {{task}} t,{{group_tasks}} gt WHERE ((gt.startTime=0 OR gt.startTime<=:cTime) AND (gt.endTime=0 OR gt.endTime>=:cTime)) AND gt.gid=:gid AND gt.tid=t.id LIMIT 10";
        $res = Yii::app()->db->createCommand($sql);
        $res->bindValues(array(
            ':gid' => $userInfo['groupid'],
            ':cTime' => $now,
        ));
        $tasks = $res->queryAll();
        //取出已经参与的任务        
        $logs = TaskLogs::model()->findAll(array(
            'condition' => 'uid=:uid',
            'params' => array(
                ':uid' => $userInfo['id']
            ),
            'select' => 'id,tid,status'
        ));
        foreach ($tasks as $k => $val) {
            $tasks[$k]['action'] = Posts::encode($val['id'], 'joinTask');
            foreach ($logs as $log) {
                if ($log['tid'] == $val['id']) {
                    //排除已完成的任务
                    if ($log['status'] == TaskLogs::STATUS_REACHED) {
                        unset($tasks[$k]);
                        break;
                    } else {
                        $tasks[$k]['receive'] = true;
                    }
                }
            }
            if ($tasks[$k]) {
                if ($val['type'] == 1) {//一次性任务
                    if ($val['score'] > 0 || $val['exp'] > 0) {
                        $_txt = '奖励';
                        $_txtArr = array();
                        $_txtArr[] = $val['score'] > 0 ? $val['score'] . '积分' : '';
                        $_txtArr[] = $val['exp'] > 0 ? $val['exp'] . '经验' : '';
                        $_txt.=join('、', array_filter($_txtArr));
                    } else {
                        $_txt = '';
                    }
                    $tasks[$k]['extraDesc'] = '一天内进行' . $val['num'] . '次，' . $_txt . ($val['endTime'] > 0 ? '，' . zmf::time($val['endTime'], 'm月d日') . '结束' : '');
                } else {
                    
                }
            }
        }
        return $tasks;
    }

    /**
     * 统计我的可接受任务
     * @param array $userInfo
     * @return int
     */
    public static function statUserTasks($userInfo) {
        if (!$userInfo['id'] || !$userInfo['groupid']) {
            return array();
        }        
        $sql = "SELECT COUNT(t.id) AS total FROM {{task}} t,{{group_tasks}} gt WHERE ((gt.startTime=0 OR gt.startTime<=:cTime) AND (gt.endTime=0 OR gt.endTime>=:cTime)) AND gt.gid=:gid AND gt.tid=t.id";
        $res = Yii::app()->db->createCommand($sql);
        $now = zmf::now();
        $res->bindValues(array(
            ':gid' => $userInfo['groupid'],
            ':cTime' => $now,
        ));
        $info = $res->queryRow();
        //取出已经参与的任务        
        $logs = TaskLogs::model()->findAll(array(
            'condition' => 'uid=:uid',
            'params' => array(
                ':uid' => $userInfo['id']
            ),
            'select' => 'id'
        ));
        $_num = $info['total'] - count($logs);
        return $_num>0 ? $_num : 0;
    }

}
