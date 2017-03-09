<?php

/**
 * This is the model class for table "{{forum_admins}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-10-21 09:25:27
 * The followings are the available columns in table '{{forum_admins}}':
 * @property string $id
 * @property string $fid
 * @property string $uid
 * @property integer $num
 * @property string $powers
 */
class ForumAdmins extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{forum_admins}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fid,uid', 'required'),
            array('num', 'numerical', 'integerOnly' => true),
            array('fid, uid', 'length', 'max' => 10),
            array('powers', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, fid, uid, num, powers', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'forumInfo' => array(self::BELONGS_TO, 'PostForums', 'fid'),
            'userInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'fid' => '版块',
            'uid' => '用户',
            'num' => '发帖数量',
            'powers' => '版主权限',
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
        $criteria->compare('fid', $this->fid, true);
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('num', $this->num);
        $criteria->compare('powers', $this->powers, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ForumAdmins the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 根据权限显示具体名称
     * @param string $powers
     * @return string
     */
    public static function displayLabels($powers) {
        $arr = explode(',', $powers);
        $temp = array();
        $actions = UserAction::userActions('post');
        foreach ($arr as $type) {
            $temp[] = $actions[$type];
        }
        return join(',', $temp);
    }

    /**
     * 获取用户在某个版块的信息
     * @param int $uid
     * @param int $fid
     * @return object
     */
    public static function getOne($uid, $fid) {
        if (!$uid || !$fid) {
            return false;
        }
        return self::model()->find('uid=:uid AND fid=:fid', array(':uid' => $uid, ':fid' => $fid));
    }

    /**
     * 判断用户在某版块是否有某个操作的权限
     * @param int $uid 用户ID
     * @param int $fid 所属版块ID
     * @param string $action 操作
     * @param boolean $totalLimit 是否需要总数限制
     * @return boolean
     */
    public static function checkForumPower($uid, $fid, $action, $totalLimit = false) {
        $info = self::getOne($uid, $fid);  
        if (!$info || !$info['powers']) {
            return false;
        }
        $powers = explode(',', $info['powers']);
        if (!in_array($action, $powers)) {
            return false;
        }
        if ($totalLimit) {
            //0表示不限制
            if($info['num']==0){
                return true;
            }
            $num = UserAction::statAction($uid, $action);
            if ($num >= $info['num']) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
    
}
