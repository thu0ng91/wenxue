<?php

/**
 * This is the model class for table "{{words}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-06-01 05:27:01
 * The followings are the available columns in table '{{words}}':
 * @property integer $id
 * @property string $word
 * @property integer $type
 * @property integer $len
 * @property string $uid
 */
class Words extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{words}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('word,type,uid', 'required'),
            array('type, len', 'numerical', 'integerOnly' => true),
            array('word', 'length', 'max' => 255),
            array('word', 'unique'),
            array('uid', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, word, type, len, uid', 'safe', 'on' => 'search'),
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
            'word' => '词',
            'type' => '分类',
            'len' => '长度',
            'uid' => '操作者',
        );
    }

    public function beforeSave() {
        if ($this->word != '') {
            $this->len = mb_strlen($this->word, 'GBK');
        }
        return true;
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

        $criteria->compare('id', $this->id);
        $criteria->compare('word', $this->word, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('len', $this->len);
        $criteria->compare('uid', $this->uid, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Words the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function exTypes($type) {
        $arr = array(
            '1' => '政治',
            '2' => '广告',
            '3' => '色情',
            '9' => '入侵',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

    public static function createWordsCache() {
        $items = Words::model()->findAll(array(
            'order' => 'len DESC',
            'select' => 'word'
        ));
        $arr = array_keys(CHtml::listData($items, 'word', ''));
        $dir = Yii::app()->basePath . "/runtime/config/badwords.php";
        $config = "<?php\nreturn " . var_export($arr, true) . ";";
        file_put_contents($dir, $config);
        return true;
    }

    public static function getWords() {
        $dir = Yii::app()->basePath . "/runtime/config/badwords.php";
        if (!file_exists($dir)) {
            return array();
        }
        $arr = @include $dir;
        return $arr;
    }

    public static function checkWords($content) {
        $words = Words::getWords();
        if (empty($words)) {
            return false;
        }
        $str=  strip_tags($content);
        foreach ($words as $word) {
            if (mb_strpos($str, $word) !== false) {
                return true;                
            }
        }
        return false;
    }
    
    public static function highLight($str){
        $words = Words::getWords();
        if (empty($words)) {
            return $str;
        }
        foreach ($words as $word) {
            $str = preg_replace("/($word)/i", "<span style='color:red'>{$word}</span>", $str);
        }
        return $str;
    }

}
