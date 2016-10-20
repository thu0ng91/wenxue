<?php

/**
 * This is the model class for table "{{group_gifts}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-10-20 08:31:54
 * The followings are the available columns in table '{{group_gifts}}':
 * @property string $id
 * @property string $groupid
 * @property string $goodsid
 * @property integer $num
 */
class GroupGifts extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{group_gifts}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('groupid, goodsid, num', 'required'),
            array('num', 'numerical', 'integerOnly' => true),
            array('groupid, goodsid', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, groupid, goodsid, num', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'groupInfo' => array(self::BELONGS_TO, 'Group', 'groupid'),
            'goodsInfo' => array(self::BELONGS_TO, 'Goods', 'goodsid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'groupid' => '用户组ID',
            'goodsid' => '商品ID',
            'num' => '赠送数量',
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
        $criteria->compare('groupid', $this->groupid, true);
        $criteria->compare('goodsid', $this->goodsid, true);
        $criteria->compare('num', $this->num);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GroupGifts the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 取出用户赠送的商品并给用户
     * @param int $gid
     * @param array $userInfo
     * @return boolean
     */
    public static function groupGiftsForUser($gid, $userInfo) {
        if (!$gid) {
            return false;
        }
        $gifts = self::model()->findAll('groupid=:gid', array(':gid' => $gid));
        foreach ($gifts as $gift) {
            $_return = Goods::detail($gift['goodsid']);
            if (!$_return['status']) {
                continue;
            }
            $_goodsInfo = $_return['msg'];
            if (!$_goodsInfo) {
                continue;
            }
            //保存商品绑定的道具到用户账上
            Props::saveUserProps($userInfo, $_goodsInfo, $gift);
        }
        return TRUE;
    }

}
