<?php

/**
 * This is the model class for table "{{goods_classify}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-10 16:11:15
 * The followings are the available columns in table '{{goods_classify}}':
 * @property string $id
 * @property string $belongid
 * @property string $title
 * @property string $order
 * @property string $goods
 * @property integer $level
 */
class GoodsClassify extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{goods_classify}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required'),
            array('level', 'numerical', 'integerOnly' => true),
            array('belongid, order, goods', 'length', 'max' => 10),
            array('title', 'length', 'max' => 32),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, belongid, title, order, goods, level', 'safe', 'on' => 'search'),
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
            'id' => '分类ID',
            'belongid' => '所属分类',
            'title' => '分类标题',
            'order' => '排序',
            'goods' => '商品数',
            'level' => '层级',
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
        $criteria->compare('belongid', $this->belongid, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('order', $this->order, true);
        $criteria->compare('goods', $this->goods, true);
        $criteria->compare('level', $this->level);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GoodsClassify the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getOne($id) {
        return self::model()->findByPk($id);
    }

    public static function getOneBelongs($id) {
        $info = self::getOne($id);
        $arr = array();
        $arr[$info['level']] = array(
            'id' => $info['id'],
            'title' => $info['title'],
        );
        for ($i = $info['level'] - 1; $i > 0; --$i) {
            if ($info['belongid'] > 0) {
                $_info = self::getOne($info['belongid']);
                if ($_info) {
                    $arr[$i] = array(
                        'id' => $_info['id'],
                        'title' => $_info['title'],
                    );
                    $info = $_info;
                }
            }
        }
        asort($arr);
        return $arr;
    }

    public static function getNavbar($foreach = false) {
        $items = GoodsClassify::model()->findAll(array(
            'order' => '`order` DESC'
        ));
        $navbar = array();
        foreach ($items as $val) {
            if ($val['belongid'] > 0) {
                if ($val['level'] == 2) {
                    $_seconds = $navbar[$val['belongid']]['items'][$val['id']]['items'];
                    $navbar[$val['belongid']]['items'][$val['id']] = array(
                        'id' => $val['id'],
                        'title' => $val['title'],
                        'level' => 2,
                        'items' => $_seconds,
                    );
                } elseif ($val['level'] == 3) {//第三层级，需要取出第二层级的所属层级
                    $_beInfo = self::getOne($val['belongid']);
                    $_seconds = $navbar[$_beInfo['belongid']]['items'][$val['belongid']]['items'];
                    $navbar[$_beInfo['belongid']]['items'][$val['belongid']]['items'][$val['id']] = array(
                        'id' => $val['id'],
                        'title' => $val['title']
                    );
                }
            } else {
                $navbar[$val['id']] = array(
                    'id' => $val['id'],
                    'title' => $val['title'],
                    'level' => 1,
                    'items' => $navbar[$val['id']]['items'],
                );
            }
        }
        if ($foreach) {
            foreach ($navbar as $k1 => $v1) {
                if (empty($v1['items'])) {
                    unset($navbar[$k1]);
                    continue;
                }
                foreach ($v1['items'] as $k2 => $v2) {
                    if (empty($v2['items'])) {
                        unset($navbar[$k1]['items'][$k2]);
                        continue;
                    }
                }
            }
            foreach ($navbar as $k1 => $v1) {
                if (empty($v1['items'])) {
                    unset($navbar[$k1]);
                    continue;
                }
            }
        }
        return $navbar;
    }

    public static function getChildren($id) {
        $navbars = self::getNavbar(TRUE);
        $children=array();
        foreach ($navbars as $k1 => $v1) {
            if($id==$k1){
                $children=$v1['items'];
                break;
            }
        }
        return $children;
    }

}
