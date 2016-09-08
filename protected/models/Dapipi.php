<?php

/**
 * This is the model class for table "{{dapipi}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-08-24 10:48:11
 * The followings are the available columns in table '{{dapipi}}':
 * @property string $id
 * @property string $uid
 * @property string $aid
 * @property string $bid
 * @property string $cTime
 */
class Dapipi extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{dapipi}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('uid, aid, bid', 'required'),
            array('uid, aid, bid, cTime', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, aid, bid, cTime', 'safe', 'on' => 'search'),
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
            'aid' => '作者ID',
            'bid' => '作品ID',
            'cTime' => '时间',
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
        $criteria->compare('aid', $this->aid, true);
        $criteria->compare('bid', $this->bid, true);
        $criteria->compare('cTime', $this->cTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Dapipi the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function daTapipi($bid, $userInfo) {
        if (!$bid) {
            return array(
                'status' => 0,
                'msg' => '缺少参数'
            );
        }
        if (empty($userInfo)) {
            return array(
                'status' => 0,
                'msg' => '请先登录'
            );
        }
        //获取用户组的权限
        $powerAction = 'dapipi';
        $powerInfo = GroupPowers::checkPower($userInfo, $powerAction);
        if (!$powerInfo['status']) {
            return array(
                'status' => 0,
                'msg' => '你无权本操作'
            );
        }
        $bookInfo = Books::getOne($bid);
        if (!$bookInfo || $bookInfo['status'] != Posts::STATUS_PASSED) {
            return array(
                'status' => 0,
                'msg' => '该作品不存在'
            );
        }elseif($bookInfo['bookStatus']==Books::STATUS_FINISHED){
            return array(
                'status' => 0,
                'msg' => '该作品已完结，不能再催更'
            );
        }
        $authorInfo = Authors::getOne($bookInfo['aid']);
        if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
            return array(
                'status' => 0,
                'msg' => '该作者不存在'
            );
        }
        $dayStart = strtotime(date('Y-m-d'));
        $ckinfo = Dapipi::model()->find(array(
            'condition' => 'uid=:uid AND aid=:aid AND bid=:bid AND cTime>=:ctime',
            'params' => array(
                ':uid' => $userInfo['id'],
                ':aid' => $bookInfo['aid'],
                ':bid' => $bookInfo['id'],
                ':ctime' => $dayStart,
            )
        ));
        if ($ckinfo) {
            return array(
                'status' => 0,
                'msg' => '今天已经催过了，明天再来吧！'
            );
        }
        $attr = array(
            'uid' => $userInfo['id'],
            'aid' => $bookInfo['aid'],
            'bid' => $bookInfo['id'],
        );
        $model = new Dapipi;
        $model->attributes = $attr;
        if ($model->save()) {
            $_num = $authorInfo['unUrge'] + 1;
            Authors::model()->updateByPk($authorInfo['id'], array('totalUrge' => $authorInfo['totalUrge'] + 1, 'unUrge' => $_num));
            //todo，根据作者热度来计算分母
            if ($_num % 10== 0) {
                $authorUInfo = Users::getOne($bookInfo['uid']);
                if ($authorUInfo) {
                    $authorUInfo['phone']=  strval($authorUInfo['phone']);
                    $status = Msg::sendWithParams($authorUInfo, 'dapipi', array(
                                'name' => $authorInfo['authorName'],
                                'num' => strval($_num)
                    ));
                    if ($status) {
                        return array(
                            'status' => 1,
                            'msg' => '已催更！'
                        );
                    } else {
                        zmf::fp($status,1);
                        zmf::fp('Dapipi---171');
                        return array(
                            'status' => 1,
                            'msg' => '已催更！'
                        );
                    }
                } else {
                    return array(
                        'status' => 0,
                        'msg' => '该作者不存在！'
                    );
                }
            }
            //记录用户操作
            $jsonData = CJSON::encode(array(
                        'bid' => $bookInfo['id'],
                        'bTitle' => $bookInfo['title'],
                        'bDesc' => $bookInfo['desc'],
                        'bFaceImg' => $bookInfo['faceImg'],
            ));
            $attr = array(
                'uid' => $this->uid,
                'logid' => $bookInfo['id'],
                'classify' => $powerAction,
                'data' => $jsonData,
                'action' => $powerAction,
                'score' => $powerInfo['msg']['score'],
                'display' => 0,
            );
            if (UserAction::simpleRecord($attr)) {
                //判断本操作是否同属任务
                Task::addTaskLog($userInfo, $powerAction);
            }
            return array(
                'status' => 1,
                'msg' => '已催更！'
            );
        } else {
            return array(
                'status' => 0,
                'msg' => '催更失败！'
            );
        }
    }

}
