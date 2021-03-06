<?php

/**
 * This is the model class for table "{{group_tasks}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:13
 * The followings are the available columns in table '{{group_tasks}}':
 * @property string $id
 * @property string $gid
 * @property string $tid
 * @property string $action
 * @property integer $type
 * @property integer $num
 * @property string $score
 * @property string $startTime
 * @property string $endTime
 * @property string $times
 */
class GroupTasks extends CActiveRecord {
    
    const TYPE_ONETIME=1;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{group_tasks}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('gid, tid,days,num,action,startTime, endTime', 'required'),
            array('type,continuous, num', 'numerical', 'integerOnly' => true),
            array('gid, tid, score,exp, startTime, endTime, times,days', 'length', 'max' => 10),
            array('action', 'length', 'max' => 16),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, gid, tid, action, type, num, score, startTime, endTime, times', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'groupInfo' => array(self::BELONGS_TO, 'Group', 'gid'),
            'taskInfo' => array(self::BELONGS_TO, 'Task', 'tid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'gid' => '团队ID',
            'tid' => '任务ID',
            'action' => '操作标识',
            'type' => '一次性任务',
            'continuous' => '不可中断',
            'days' => '总天数',
            'num' => '每天次数',
            'score' => '奖励积分',
            'exp' => '奖励经验',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'times' => '参与人数',
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
        $criteria->compare('gid', $this->gid, true);
        $criteria->compare('tid', $this->tid, true);
        $criteria->compare('action', $this->action, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('num', $this->num);
        $criteria->compare('score', $this->score, true);
        $criteria->compare('startTime', $this->startTime, true);
        $criteria->compare('endTime', $this->endTime, true);
        $criteria->compare('times', $this->times, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GroupTasks the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getOneTask($userInfo, $tid) {
        if (!$userInfo || !$tid || !$userInfo['id'] || !$userInfo['groupid']) {
            return array(
                'status' => 0,
                'msg' => '缺少参数'
            );
        }
        //首先判断用户所在用户组是否可以参与该任务
        $reinfo = GroupTasks::model()->find('gid=:gid AND tid=:tid', array(':gid' => $userInfo['groupid'], ':tid' => $tid));
        if (!$reinfo) {
            return array(
                'status' => 0,
                'msg' => '任务不存在'
            );
        }
        $now = zmf::now();
        if ($reinfo['endTime'] > 0 && $now > $reinfo['endTime']) {
            return array(
                'status' => 0,
                'msg' => '任务已过期'
            );
        }
        $taskInfo = Task::getOne($tid);
        $arr = array(
            'groupTaskId' => $reinfo['id'],
            'tid' => $reinfo['tid'],
            'title' => $taskInfo['title'],
            'faceImg' => $taskInfo['faceImg'],
            'desc' => $taskInfo['desc'],
            'gid' => $reinfo['gid'],
            'action' => $reinfo['action'],
            'type' => $reinfo['type'],
            'continuous' => $reinfo['continuous'],
            'num' => $reinfo['num'],
            'score' => $reinfo['score'],
            'exp' => $reinfo['exp'],
            'startTime' => $reinfo['startTime'],
            'endTime' => $reinfo['endTime'],
            'times' => $reinfo['times'],
        );
        return array(
            'status' => 1,
            'msg' => $arr
        );
    }

}
