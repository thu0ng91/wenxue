<?php

/**
 * This is the model class for table "{{keywords}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-12-12 20:01:49
 * The followings are the available columns in table '{{keywords}}':
 * @property string $id
 * @property string $title
 * @property integer $len
 * @property string $url
 * @property string $hash
 */
class Keywords extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{keywords}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title,url', 'required'),
            array('title', 'unique'),
            array('len', 'numerical', 'integerOnly' => true),
            array('title, url', 'length', 'max' => 255),
            array('hash', 'length', 'max' => 32),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, len, url, hash', 'safe', 'on' => 'search'),
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
            'title' => '标题',
            'len' => 'Len',
            'url' => '链接',
            'hash' => 'Hash',
        );
    }

    public function beforeSave() {
        if ($this->title != '') {
            $this->len = mb_strlen($this->title, 'GBK');
            $this->hash = md5($this->title);
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('len', $this->len);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('hash', $this->hash, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Keywords the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getOne($id) {
        return self::model()->findByPk($id);
    }

    public static function cacheWords() {
        $items = self::model()->findAll(array(
            'order' => 'len DESC',
            'select' => 'title,url',
        ));
        if (empty($items)) {
            return false;
        }
        $dir = Yii::app()->basePath . '/runtime/runHtml/';
        zmf::createUploadDir($dir);
        $fileName = 'keywords.php';

        $config = "<?php\n";
        $config .= "return array(\n";
        foreach ($items as $val) {
            $config .= "'" . $val['title'] . "'=> '" . addslashes($val['url']) . "',\n";
        }
        $config .= ");\n";
        $fp = fopen($dir . $fileName, 'w');
        $fw = fwrite($fp, $config);
        if (!$fw) {
            fclose($fp);
            return false;
        } else {
            fclose($fp);
            return true;
        }
    }

    public static function getWords() {
        $dir = Yii::app()->basePath . '/runtime/runHtml/keywords.php';
        $items = include $dir;
        return $items;
    }

    /**
     * 内容加链接
     * @param string $content
     * @param string $from 来源，keywords：实时链接；wenku：不变的链接
     * @return string
     */
    public static function linkWords($content,$from='keywords') {
        $keywords = self::getWords();  
        if(empty($keywords) || !$keywords){
            return $content;
        }
        //处理link
        $replace = array(
            "/\[link=([^\]]+?)\](.+?)\[\/link\]/i",
        );
        $to = array(
            '<a href="\\1" target="_blank" class="_auto">\\2</a>',
        );
        $content = preg_replace($replace, $to, $content);
        //取所有链接
        preg_match_all('/<a.*?href=".*?".*?>.*?<\/a>/i', $content, $linkList);
        $content = preg_replace('/<a.*?href=".*?".*?>.*?<\/a>/i', '<{link}>', $content);
        preg_match_all('/<img[^>]+>/im', $content, $imgList);
        $content = preg_replace('/<img[^>]+>/im', '<{img}>', $content);
        $linkList = $linkList[0];
        $imgList = $imgList[0];
        $guideList = array();        
        foreach ($keywords as $title => $url) {
            if (strpos($content, $title) !== false) {
                $_arrkey = md5($title);
                if (strpos($content, $_arrkey) !== false) {
                    continue;
                } else {
                    $key = str_replace(array('/', '.', '(', ')'), array('\/', '\.', '\(', '\)'), $title);
                    $find = "/($key)/i";
                    preg_match($find, $content, $_matchtmp);
                    if (!empty($_matchtmp[0])) {
                        $content = preg_replace($find, $_arrkey, $content, 1);
                        $guideList[$_arrkey] = '<a href="'.$url.'" target="_blank" title="'.$title.'">'.$title.'</a>';
                    }
                }
            }
        }
        $arrLen = count($linkList);
        for ($i = 0; $i < $arrLen; $i++) {
            $content = preg_replace('/<{link}>/', $linkList[$i], $content, 1);
        }
        $arrLen2 = count($imgList);
        for ($i = 0; $i < $arrLen2; $i++) {
            $content = preg_replace('/<{img}>/', $imgList[$i], $content, 1);
        }
        foreach ($guideList as $key => $glink) {
            $content = preg_replace('/' . $key . '/i', $glink, $content, 1);
        }
        $content = str_replace(array('\(', '\)', '\/', '\.'), array('(', ')', '/', '.'), $content);
        return $content;
    }

}
