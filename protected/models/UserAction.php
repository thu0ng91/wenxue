<?php

/**
 * This is the model class for table "{{user_action}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-29 10:06:37
 * The followings are the available columns in table '{{user_action}}':
 * @property string $id
 * @property string $uid
 * @property string $logid
 * @property string $classify
 * @property string $data
 * @property string $cTime
 * @property string $ip
 */
class UserAction extends CActiveRecord {

    const STATUS_DISPLAY = 1; //显示为动态

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return '{{user_action}}';
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
            array('ip', 'default', 'setOnEmpty' => true, 'value' => ip2long(Yii::app()->request->userHostAddress)),
            array('uid, logid, classify, data', 'required'),
            array('display', 'numerical', 'integerOnly' => true),
            array('uid, logid,score,exp', 'length', 'max' => 10),
            array('classify', 'length', 'max' => 32),
            array('action', 'length', 'max' => 16),
            array('cTime, ip', 'length', 'max' => 11),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, logid, classify, data, cTime, ip', 'safe', 'on' => 'search'),
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
            'logid' => '对象ID',
            'classify' => '对象分类',
            'data' => '对象内容',
            'cTime' => '创建时间',
            'ip' => '所在IP',
            'action' => '操作',
            'score' => '积分',
            'display' => '是否显示为动态',
            'exp' => '经验值',
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
        $criteria->compare('logid', $this->logid, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('cTime', $this->cTime, true);
        $criteria->compare('ip', $this->ip, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserAction the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function exClassify($type) {
        $arr = array(
            'favoriteAuthor' => '关注了作者',
            'favoriteBook' => '收藏了小说',
            'chapterTip' => '点评了',
            'post' => '发布帖子',
        );
        return $arr[$type];
    }

    public static function userActions($type='admin') {
        $arr = array(
            'post' => array(
                'desc' => '论坛相关',
                'items' => array(
                    'addPost' => '发表帖子',
                    'editPost' => '编辑帖子',
                    'addPostReply' => '回复帖子',
                    'editPostReply' => '编辑帖子楼层',
                    'favorPost' => '赞帖子',
                    'favoritePost' => '收藏帖子',
                    'favorPostReply' => '赞楼层',
                    'rewardPostReply' => '打赏帖子',
                    'delPost' => '删除帖子',
                    'delPostReply' => '删除楼层',
                    'favoriteForum' => '关注版块',
                    'changeForum' => '修改所属版块',
                    'setThreadStatus' => '设置帖子',
                ),
            ),
            'book' => array(
                'desc' => '小说相关',
                'items' => array(
                    'favoriteBook' => '收藏作品',
                    'addChapterTip' => '点评作品',
                    'delChapterTip' => '删除点评',
                    'favorChapterTip' => '赞点评',
                    'dapipi' => '催更作品',
                    'addBook' => '发表作品',
                    'finishBook' => '完结作品',
                    'delBook' => '删除作品',
                    'addChapter' => '新增章节',
                    'publishChapter' => '发表章节',
                    'delChapter' => '删除章节',
                ),
            ),
            'goods' => array(
                'desc' => '商品相关',
                'items' => array(
                    'buyGoods' => '购买商品',
                    'favoriteGoods' => '收藏商品',
                ),
            ),
            'author' => array(
                'desc' => '作者相关',
                'items' => array(
                    'createAuthor' => '成为作者',
                    'favoriteAuthor' => '关注作者',
                ),
            ),
            'comment' => array(
                'desc' => '评论相关',
                'items' => array(
                    'commentPost' => '评论帖子',
                    'commentChapterTip' => '评论点评',
                    'favorComment' => '点赞评论',
                ),
            ),
            'report' => array(
                'desc' => '评论相关',
                'items' => array(
                    'addReport' => '举报',
                ),
            ),
            'feedback' => array(
                'desc' => '意见反馈',
                'items' => array(
                    'feedback' => '意见反馈',
                ),
            ),
            'user' => array(
                'desc' => '用户相关',
                'items' => array(
                    'favoriteUser' => '关注用户',
                ),
            ),
            'task' => array(
                'desc' => '任务相关',
                'items' => array(
                    'joinTask' => '接受任务',
                    'delTask' => '删除任务',
                    'finishTask' => '完成任务',
                ),
            ),
        );
        $tmpArr = array();
        if($type=='admin'){            
            foreach ($arr as $detail) {
                $tmpArr = array_merge($tmpArr, $detail['items']);
            }
        }else{
            $tmpArr=$arr[$type]['items'];
        }
        return $tmpArr;
    }

    public static function recordAction($logid, $type, $jsonData, $uid = '') {
        if (!$logid || !$type || !$jsonData) {
            return false;
        }
        $data = array(
            'uid' => ($uid != '') ? $uid : zmf::uid(),
            'logid' => $logid,
            'classify' => $type,
        );
        $info = UserAction::model()->findByAttributes($data);
        if ($info) {
            $attr = array(
                'data' => $jsonData,
                'cTime' => zmf::now()
            );
            return UserAction::model()->updateByPk($info['id'], $attr);
        }
        $data['data'] = $jsonData;
        $model = new UserAction();
        $model->attributes = $data;
        return $model->save();
    }

    public static function simpleRecord($attr) {
        if (empty($attr)) {
            return false;
        }
        $model = new UserAction;
        $model->attributes = $attr;
        if ($model->save()) {
            if ($attr['score'] != 0 && $attr['score'] != '') {
                $_attr = array(
                    'uid' => $attr['uid'],
                    'classify' => $attr['action'],
                    'logid' => $attr['logid'],
                    'score' => $attr['score']
                );
                $_scoreLogModel = new ScoreLogs;
                $_scoreLogModel->attributes = $_attr;
                $_scoreLogModel->save();
            }
            return true;
        } else {
            return false;
        }
    }

    public static function delAction($logid, $type) {
        if (Yii::app()->user->isGuest) {
            return false;
        }
        if (!is_numeric($logid)) {
            return false;
        }
        $attr = array(
            ':uid' => Yii::app()->user->id,
            ':logid' => $logid,
            ':classify' => $type,
        );
        if (UserAction::model()->deleteAll(':uid=uid AND :logid=logid AND :classify=classify', $attr)) {
            return true;
        } else {
            return false;
        }
    }

    
    /**
     * 统计某个用户某天某操作的次数
     * @param int $uid
     * @param string $action
     * @return int
     */
    public static function statAction($uid, $action) {
        $now = zmf::now();
        $_time = strtotime(zmf::time($now, 'Y-m-d'), $now);
        $params = array(
            ':uid' => $uid,
            ':action' => $action,
            ':startTime' => $_time
        );
        $num = UserAction::model()->count('uid=:uid AND action=:action AND cTime>=:startTime', $params);
        return $num;
    }

    public static function statTaskAction($userInfo, $taskInfo) {
        //取出用户领取任务到任务结束时间内该操作的所有时间
        $params = array(
            ':uid' => $userInfo['id'],
            ':action' => $taskInfo['action'],
            ':startTime' => $taskInfo['userStartTime'],
        );
        if ($taskInfo['endTime'] > 0) {
            $params['endTime'] = $taskInfo['endTime'];
        }
        $num = UserAction::model()->count(array(
            'condition' => 'uid=:uid AND action=:action AND cTime>=:startTime' . ($taskInfo['endTime'] > 0 ? ' AND cTime<=:endTime' : ''),
            'params' => $params
        ));
        return $num;
    }

    public static function statTodayAction($userInfo, $taskInfo) {
        //取出用户领取任务到任务结束时间内该操作的所有时间
        $now = zmf::now();
        //如果用户接受任务的时间在任务开始之后        
        $_time = strtotime(zmf::time($now, 'Y-m-d'), $now);        
        $params = array(
            ':uid' => $userInfo['id'],
            ':action' => $taskInfo['action'],
            ':startTime' => $taskInfo['userStartTime'],
            ':endTime' => ($_time + 86400),
        );
        if($taskInfo['endTime']>0){
            $params[':endTime'] = $taskInfo['endTime'];
        }else{
            //unset($params[':endTime']);
        }
        $num = UserAction::model()->count(array(
            'condition' => 'uid=:uid AND action=:action AND cTime>=:startTime AND cTime<=:endTime',
            'params' => $params
        ));
        return $num;
    }

    public static function checkTaskAction($userInfo, $taskInfo) {
        //取出用户领取任务到任务结束时间内该操作的所有时间
        $params = array(
            ':uid' => $userInfo['id'],
            ':action' => $taskInfo['action'],
            ':startTime' => $taskInfo['userStartTime'],
        );
        if ($taskInfo['endTime'] > 0) {
            $params[':endTime'] = $taskInfo['endTime'];
        }
        $timeItems = UserAction::model()->findAll(array(
            'condition' => 'uid=:uid AND action=:action AND cTime>=:startTime' . ($taskInfo['endTime'] > 0 ? ' AND cTime<=:endTime' : ''),
            'params' => $params,
            'select' => 'cTime'
        ));
        $len = count($timeItems);
        if ($len < 1) {
            return false;
        }
        $now = zmf::now();
        if ($taskInfo['days'] > 1) {//大于1，说明必须连续几天每天都有一定数量的操作
            $tmpArr = array();
            foreach ($timeItems as $item) {
                $_time = strtotime(zmf::time($item['cTime'], 'Y-m-d'), $now);
                $tmpArr[$_time]+=1;
            }
            $_prevTime = 0;
            $_continuous = true;
            foreach ($tmpArr as $_day => $_num) {
                if (!$_continuous) {
                    break;
                }
                if ($taskInfo['continuous']) {//必须连续不间断
                    if (!$_prevTime) {
                        $_prevTime = $_day;
                    }
                    //如果那天的操作数小于规定数量 或者 那天与前一天的时间差大于1天，则说明没有连续
                    if ($_num < $taskInfo['num'] || ($_day - $_prevTime > 86400)) {
                        $_continuous = false;
                    }
                    $_prevTime = $_day;
                } else {//只需要总数达到了规定数量即可
                    if ($_num >= $taskInfo['num']) {
                        $_prevTime+=1;
                    }
                }
            }
            //如果不是连续性任务，且符合数量的天数小于规定天数，则说明没达标
            if (!$taskInfo['continuous'] && ($_prevTime < $taskInfo['days'])) {
                $_continuous = false;
            }
            return $_continuous;
        } else {
            return true;
        }
    }
    
    /**
     * 统计用户的总经验
     * @param int $uid
     * @return int
     */
    public static function statUserExp($uid){
        //日常操作的经验奖励
        $sql="SELECT SUM(exp) AS total FROM {{user_action}} WHERE uid=:uid";
        $res=  Yii::app()->db->createCommand($sql);
        $res->bindValue(':uid', $uid);
        $info=$res->queryRow();
        //完成任务的奖励
        $sql2="SELECT SUM(exp) AS total FROM {{score_logs}} WHERE uid=:uid";
        $res2=  Yii::app()->db->createCommand($sql2);
        $res2->bindValue(':uid', $uid);
        $info2=$res2->queryRow();
        
        return $info['total']+$info2['total'];
    }

}
