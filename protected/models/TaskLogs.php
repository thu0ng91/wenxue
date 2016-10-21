<?php

/**
 * This is the model class for table "{{task_logs}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:13
 * The followings are the available columns in table '{{task_logs}}':
 * @property string $id
 * @property string $uid
 * @property string $tid
 * @property integer $times
 * @property string $cTime
 * @property integer $status
 * @property string $score
 */
class TaskLogs extends CActiveRecord {

    const STATUS_REACHED = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{task_logs}}';
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
            array('uid, tid', 'required'),
            array('times, status', 'numerical', 'integerOnly' => true),
            array('uid, tid, cTime, score,exp,finishTime', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, tid, times, cTime, status, score', 'safe', 'on' => 'search'),
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
            'tid' => '任务ID',
            'times' => '参与次数',
            'cTime' => '参与时间',
            'finishTime' => '完成时间',
            'status' => '状态，是否已达成',
            'score' => '奖励积分',
            'exp' => '奖励经验',
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
        $criteria->compare('tid', $this->tid, true);
        $criteria->compare('times', $this->times);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('finishTime', $this->finishTime, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('score', $this->score, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TaskLogs the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function checkInfo($uid, $tid) {
        return TaskLogs::model()->find('uid=:uid AND tid=:tid', array(':uid' => $uid, ':tid' => $tid));
    }

    public static function finishTask($userInfo, $taskInfo, $logid) {
        $num = TaskLogs::model()->count('uid=:uid AND tid=:tid', array(':uid' => $userInfo['id'], ':tid' => $taskInfo['id']));
        $now = zmf::now();
        if (TaskLogs::model()->updateByPk($logid, array(
                    'status' => TaskLogs::STATUS_REACHED,
                    'finishTime' => $now,
                    'times' => $num
                ))) {
            //如果标记成功，则新增一条积分记录
            $_attr = array(
                'uid' => $userInfo['id'],
                'classify' => 'task',
                'logid' => $taskInfo['id'],
                'score' => $taskInfo['score'],
                'exp' => $taskInfo['exp'],
            );
            $_scoreLogModel = new ScoreLogs;
            $_scoreLogModel->attributes = $_attr;
            $_scoreLogModel->save();
            //发送一条提示
            $_noticedata = array(
                'uid' => $userInfo['id'],
                'authorid' => 0,
                'content' => '恭喜你已完成任务「' . $taskInfo['taskTitle'] . '」',
                'new' => 1,
                'type' => 'finishTask',
                'cTime' => $now,
                'from_id' => $logid,
                'from_num' => 1
            );
            Notification::add($_noticedata);
            return true;
        } else {
            return false;
        }
    }

}
