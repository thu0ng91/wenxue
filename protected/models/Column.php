<?php

class Column extends CActiveRecord {

    public function tableName() {
        return '{{column}}';
    }

    public function rules() {
        return array(
            array('title', 'required'),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('hot, bold, queue,classify,status', 'numerical', 'integerOnly' => true),
            array('belongid', 'length', 'max' => 11),
            array('title', 'length', 'max' => 32),
            array('name', 'length', 'max' => 16),
            array('title, name', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'columnInfo' => array(self::BELONGS_TO, 'Column', 'belongid'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'belongid' => '所属栏目',
            'title' => '栏目标题',
            'name' => '拼音',
            'hot' => '热门',
            'bold' => '加粗',
            'queue' => '排序',
            'classify' => '所属分类',
            'status' => '状态',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('title', $this->title, true);
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public static function classify($type){
        $arr=array(
            '1'=>'小说分类'
        );
        if($type=='admin'){
            return $arr;
        }
        return $arr[$type];
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function allCols($type = 1, $second = 0, $col0 = true, $classify = 1) {
        if ($type == 1) {
            $arr = array('belongid' => 0, 'classify' => $classify,'status'=>  Posts::STATUS_PASSED);
        } else {
            $arr = array('belongid' => $second, 'classify' => $classify,'status'=>  Posts::STATUS_PASSED);
        }
        if ($classify === NULL || !$classify) {
            unset($arr['classify']);
        }
        if ($type == 1) {
            $cols = Column::model()->findAllByAttributes($arr);
        } elseif ($type == 2) {
            $cols = Column::model()->findAllByAttributes($arr);
        } elseif ($type == 3) {
            $cols = Column::model()->findByAttributes(array('id' => $second));
        }
        if ($type != 3) {
            if ($col0) {
                $cols = CHtml::listData($cols, 'id', 'title');
            }
        }
        return $cols;
    }

    public static function getSimpleInfo($keyid) {
        $info = Column::model()->findByPk($keyid);
        return $info;
    }

    /**
     * 首页边侧的栏目导航
     * @return boolean
     */
    public function sideColBar() {
        $first = Column::allCols(1, 0, false, Posts::CLASSIFY_BLOG);
        $cols = array();
        if (!empty($first)) {
            foreach ($first as $k => $v) {
                $cols[] = array(
                    'id' => $v['id'],
                    'title' => $v['title'],
                    'seconds' => Column::allCols(2, $v['id'], false, Posts::CLASSIFY_BLOG)
                );
            }
            return $cols;
        } else {
            return false;
        }
    }

    public function colids($keyid, &$info) {
        if (!$keyid) {
            return false;
        }
        $info = Column::getSimpleInfo($keyid);
        if (!$info) {
            return false;
        }
        $ids = array();
        if ($info['belongid'] == 0) {
            $belongs = Column::model()->findAll('belongid=:keyid', array(':keyid' => $keyid));
            $ids = array_keys(CHtml::listData($belongs, 'id', ''));
        }
        $ids[] = $keyid;
        return $ids;
    }

    public function getOne($keyid, $return = '') {
        if (!$keyid) {
            return false;
        }
        $item = Column::model()->findByPk($keyid);
        if ($return != '') {
            return $item[$return];
        }
        return $item;
    }

}
