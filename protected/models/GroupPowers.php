<?php

/**
 * This is the model class for table "{{group_powers}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:13
 * The followings are the available columns in table '{{group_powers}}':
 * @property string $id
 * @property string $gid
 * @property string $tid
 * @property integer $value
 * @property integer $score
 */
class GroupPowers extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{group_powers}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('gid, tid', 'required'),
            array('value, score,totalLimit,display', 'numerical', 'integerOnly' => true),
            array('gid, tid,exp', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, gid, tid, value, score', 'safe', 'on' => 'search'),
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
            'typeInfo' => array(self::BELONGS_TO, 'GroupPowerTypes', 'tid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'gid' => '用户组',
            'tid' => '权限',
            'value' => '权限值',
            'score' => '积分值',
            'exp' => '经验值',
            'totalLimit' => '总数限制',
            'display' => '是否在动态里显示',
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
        $criteria->compare('value', $this->value);
        $criteria->compare('score', $this->score);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GroupPowers the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function checkPower($userInfo, $action) {
        if (!$userInfo || !$action || !$userInfo['groupid'] || !$userInfo['id']) {
            return array(
                'status' => 0,
                'msg' => '缺少参数',
            );
        }
        $sql = "SELECT gt.key,gt.desc,gp.value,gp.score,gp.exp,gp.totalLimit,gp.display FROM {{group_power_types}} gt,{{group_powers}} gp WHERE gt.key=:key AND gp.gid=:gid AND gp.tid=gt.id";
        $res = Yii::app()->db->createCommand($sql);
        $res->bindValues(array(
            ':key' => $action,
            ':gid' => $userInfo['groupid']
        ));
        $info = $res->queryRow();
        //没有记录或者记录值为0，则表示不允许
        if (!$info || (!$info['value'] && $info['totalLimit'])) {
            return array(
                'status' => 0,
                'msg' => '你所在用户组不能完成此操作',
            );
        }
        if($info['totalLimit']){
            //在用户操作记录表中查询今天该操作的记录数
            $num = UserAction::statAction($userInfo['id'], $action);
            if ($num >= $info['value']) {
                return array(
                    'status' => 0,
                    'msg' => '你今日' . $info['desc'] . '已达上限',
                );
            }
            return array(
                'status' => 1,
                'msg' => $info,
            );
        }else{
            return array(
                'status' => 1,
                'msg' => $info,
            );
        }
    }

    private function _getGroupActions($groupid) {
        if (!$groupid) {
            return array(
                'status' => 0,
                'msg' => '缺少参数',
            );
        }
        $sql = "SELECT gt.key,gt.desc FROM {{group_power_types}} gt,{{group_powers}} gp WHERE gp.gid=:gid AND gp.value>0 AND gp.tid=gt.id";
        $res = Yii::app()->db->createCommand($sql);
        $res->bindValues(array(
            ':gid' => $groupid
        ));
        $items = $res->queryAll();
        return $items;
    }

    public static function cacheActions($groupid) {
        self::getGroupActions($groupid);
        return true;
    }

    public static function delActionsCache($groupid) {
        $key = "group-powers-" . $groupid;
        zmf::delFCache($key);
        return true;
    }

    public static function getGroupActions($groupid) {
        $key = "group-powers-" . $groupid;
        $items = zmf::getFCache($key);
        if (!$items) {
            $actions = self::_getGroupActions($groupid);
            $items = CHtml::listData($actions, 'key', 'desc');
            $_items = !empty($items) ? $items : array();
            $time = 2592000; //一个月
            zmf::setFCache($key, $_items, $time);
        }
        return $items;
    }

    public static function checkAction($userInfo, $action) {
        if (!$userInfo || !$userInfo['id'] || !$action || !$userInfo['groupid']) {
            return '';
        }
        $actions = self::getGroupActions($userInfo['groupid']);
        if (empty($actions) || !is_array($actions)) {
            return false;
        }
        return array_key_exists($action, $actions);
    }

    public static function link($action, $userInfo, $text, $url = '#', $htmlOptions = array(), $showText = false) {
        if (!$userInfo || !$userInfo['id'] || !$action || !$userInfo['groupid']) {
            return $showText ? $text : '';
        }
        return self::checkAction($userInfo, $action) ? CHtml::link($text, $url, $htmlOptions) : ($showText ? $text : '');
    }

}
