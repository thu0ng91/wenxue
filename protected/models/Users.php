<?php

/**
 * This is the model class for table "{{users}}".
 *
 * The followings are the available columns in table '{{users}}':
 * @property integer $id
 * @property string $truename
 * @property string $password
 * @property string $contact
 * @property string $avatar
 * @property string $content
 * @property integer $hits
 * @property integer $sex
 * @property integer $isAdmin
 * @property integer $status
 */
class Users extends CActiveRecord {

    public $newPassword;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{users}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('truename,email, password', 'required'),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('ip', 'default', 'setOnEmpty' => true, 'value' => ip2long(Yii::app()->request->userHostAddress)),
            array('hits, sex, isAdmin, status,phoneChecked', 'numerical', 'integerOnly' => true),
            array('truename,ip,score,gold', 'length', 'max' => 16),
            array('cTime,authorId,favors,favord,favorAuthors,exp,level', 'length', 'max' => 10),
            array('phone', 'length', 'max' => 11),
            array('password', 'length', 'max' => 32),
            array('contact,avatar,email', 'length', 'max' => 255),
            array('email', 'email'),
            array('content', 'safe'),
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
            'truename' => '用户昵称',
            'phone' => '手机号码',
            'email' => '邮箱地址',
            'password' => '账号密码',
            'newPassword' => '新密码',
            'contact' => '联系方式',
            'avatar' => '用户头像',
            'content' => '个人简介',
            'hits' => '点击次数',
            'sex' => '性别',
            'isAdmin' => '是否管理员',
            'status' => '状态',
            'ip' => '注册IP',
            'cTime' => '注册时间',
            'authorId' => '作者ID',
            'favors' => '粉丝数',
            'favord' => '关注了',
            'favorAuthors' => '收藏作者数',
            'phoneChecked' => '手机号是否已验证',
            'score' => '总积分',
            'gold' => '总金币',
            'exp' => '总经验',
            'level' => '用户等级',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Users the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getOne($id) {
        return Users::model()->findByPk($id);
    }

    public static function getUsername($id) {
        $info = Users::model()->findByPk($id);
        return $info ? $info['truename'] : '';
    }

    public static function userSex($return) {
        $arr = array(
            '0' => '未公开',
            '1' => '男',
            '2' => '女',
        );
        if ($return == 'admin') {
            return $arr;
        }
        return $arr[$return];
    }

    public static function isAdmin($return) {
        $arr = array(
            '0' => '不是',
            '1' => '是',
        );
        if ($return == 'admin') {
            return $arr;
        }
        return $arr[$return];
    }

    public static function userStatus($return) {
        $arr = array(
            Posts::STATUS_NOTPASSED => '未激活',
            Posts::STATUS_PASSED => '正常',
            Posts::STATUS_STAYCHECK => '锁定',
            Posts::STATUS_DELED => '删除',
        );
        if ($return == 'admin') {
            return $arr;
        }
        return $arr[$return];
    }

    public static function findByName($truename) {
        $info = Users::model()->find('truename=:truename AND status=' . Posts::STATUS_PASSED, array(
            ':truename' => $truename
        ));
        return $info;
    }

    public static function findByPhone($phone) {
        $info = Users::model()->find('phone=:phone AND status=' . Posts::STATUS_PASSED, array(
            ':phone' => $phone
        ));
        return $info;
    }

    public static function findByEmail($email) {
        $info = Users::model()->find('email=:email AND status=' . Posts::STATUS_PASSED, array(
            ':email' => $email
        ));
        return $info;
    }

    public static function updateInfo($uid, $field, $value = '') {
        if (!$uid || !$field) {
            return false;
        }
        if (!in_array($field, array('password', 'groupid'))) {
            return false;
        }
        $attr[$field] = $value;
        return Users::model()->updateByPk($uid, $attr);
    }

    public static function checkWealth($uid, $actionType, $totalCost) {
        if (!$uid || !$actionType || !$totalCost) {
            return false;
        }
        if ($actionType == 'score') {
            $totalWealth = ScoreLogs::statUserScore($uid);
        } elseif ($actionType == 'gold') {
            $totalWealth = 0;
        }
        if ($totalWealth >= $totalCost) {
            return TRUE;
        }
        return false;
    }

    public static function costWealth($uid, $actionType, $totalCost,$goodsInfo) {
        if (!self::checkWealth($uid, $actionType, $totalCost)) {
            return false;
        }
        if ($actionType == 'score') {
            $_attr = array(
                'uid' => $uid,
                'classify' => 'goods',
                'logid' => $goodsInfo['id'],
                'score' => (-1)*$totalCost
            );
            $_scoreLogModel = new ScoreLogs;
            $_scoreLogModel->attributes = $_attr;
            return $_scoreLogModel->save();
        } elseif ($actionType == 'gold') {
            $totalWealth = 0;
        }
        return false;
    }

}
