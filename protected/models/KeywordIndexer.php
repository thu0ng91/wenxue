<?php

/**
 * This is the model class for table "{{keyword_indexer}}".
 *
 * The followings are the available columns in table '{{keyword_indexer}}':
 * @property string $id
 * @property string $title
 * @property integer $logid
 * @property string $classify
 * @property integer $len
 * @property integer $times
 * @property string $hash
 * @property integer $status
 */
class KeywordIndexer extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{keyword_indexer}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, logid, classify', 'required'),
            array('logid, len, times, status', 'numerical', 'integerOnly' => true),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('title', 'length', 'max' => 255),
            array('classify', 'length', 'max' => 16),
            array('hash', 'length', 'max' => 32),
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
            'logid' => '对象ID',
            'classify' => '分类',
            'len' => '长度',
            'times' => '出现次数',
            'hash' => 'Hash',
            'status' => 'Status',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return KeywordIndexer the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function createKeywords($item, $classify = 'posts') {
        if (!$item || empty($item)) {
            return false;
        }
        KeywordIndexer::model()->deleteAll('logid=:logid AND classify="' . $classify . '"', array(':logid' => $item['id']));
        $_data = array(
            'logid' => $item['id'],
            'classify' => $classify,
            'status' => $item['status']
        );
        $_data['len'] = mb_strlen($item['title'], 'GBK');
        $_data['title'] = $item['title'];
        $hash = md5($_data['title']);
        $_data['hash'] = $hash;
        $_exinfo = KeywordIndexer::model()->find('classify="' . $classify . '" AND hash=:hash', array(':hash' => $hash));
        if (!$_exinfo) {
            $_data['times'] = 0;
        } elseif ($item['status'] != Posts::STATUS_PASSED) {
            $_data['times'] = 1;
        } else {
            $_data['times'] = 1;
            KeywordIndexer::model()->updateCounters(array('times' => 1), 'id=:id', array(':id' => $_exinfo['id']));
        }
        $model = new KeywordIndexer();
        $model->attributes = $_data;
        return $model->save();
    }

    public static function linkContent($content) {               
        $total = self::getWords();
        if (empty($total)) {
            return $content;
        }
        preg_replace("/\[link=([^\]]+?)\](.+?)\[\/link\]/i", "$2", $content);
        preg_match_all('/<a.*?href=".*?".*?>.*?<\/a>/i', $content, $linkList);
        $content = preg_replace('/<a.*?href=".*?".*?>.*?<\/a>/i', '<{link}>', $content);
        preg_match_all('/<img[^>]+>/im', $content, $imgList);
        $content = preg_replace('/<img[^>]+>/im', '<{img}>', $content);
        $linkList = $linkList[0];
        $imgList = $imgList[0];
        $guideList = array();
        $matched = array();
        foreach ($total as $key => $val) {
            if (strpos($content, $key) !== false) {
                $_arrkey = md5($key);
                if (strpos($content, $_arrkey) !== false) {
                    continue;
                } else {
                    $key = str_replace(array('/', '.', '(', ')'), array('\/', '\.', '\(', '\)'), $key);
                    $find = "/($key)/i";
                    preg_match($find, $content, $_matchtmp);
                    if (!empty($_matchtmp[0])) {
                        $content = preg_replace($find, $_arrkey, $content, 1);
                        $url = "[link={$val}]" . $_matchtmp[1] . "[/link]";
                        $guideList[$_arrkey] = $url;
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

    public static function createWordsCache() {
        $items = self::model()->findAll(array(
            'condition' => '`times`=0 AND `status`=1',
            'order' => 'len DESC',
            'select' => 'title,logid,classify'
        ));
        $dir = Yii::app()->basePath . "/runtime/runHtml/contentWords.php";
        $config = "<?php\n";
        $config .= "return array(\n";
        foreach ($items as $item) {
            $_type = '';
            switch ($item['classify']) {
                case 'author':
                    $_type = 'author';
                case 'posts':
                    $_type = 'post';
                    break;
                default:
                    break;
            }
            if (!$_type) {
                continue;
            }
            $_url = zmf::config('domain') . Yii::app()->createUrl('/wenku/' . $_type, array('id' => $item['logid']));
            $config .= "'" . $item['title'] . "'=> '" . $_url . "',\n";
        }
        $config .= ");\n";

        file_put_contents($dir, $config);
        return true;
    }
    
    public static function getWords() {
        $dir = Yii::app()->basePath . "/runtime/runHtml/contentWords.php";
        if (!file_exists($dir)) {
            return array();
        }
        $arr = @include $dir;
        return $arr;
    }

}
