<?php

/**
 * This is the model class for table "{{books}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 22:25:28
 * The followings are the available columns in table '{{books}}':
 * @property string $id
 * @property string $uid
 * @property string $aid
 * @property string $title
 * @property string $faceImg
 * @property string $desc
 * @property string $content
 * @property string $favorites
 * @property string $hits
 * @property string $chapters
 * @property string $comments
 * @property string $words
 * @property integer $vip
 * @property integer $bookStatus
 * @property integer $status
 */
class Books extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{books}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cTime,updateTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('uid, aid,colid, title', 'required'),
            array('vip, bookStatus, status,top', 'numerical', 'integerOnly' => true),
            array('uid, aid,colid,favorites, hits, chapters, comments, words,topTime,scorer,score1,score2,score3,score4,score5', 'length', 'max' => 10),
            array('title, faceImg, desc', 'length', 'max' => 255),
            array('score', 'length', 'max' => 3),
            array('content', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, aid, title, faceImg, desc, content, favorites, hits, chapters, comments, words, vip, bookStatus, status', 'safe', 'on' => 'search'),
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
            'colid' => '分类',
            'title' => '小说名',
            'faceImg' => '封面图',
            'desc' => '推荐语',
            'content' => '简介',
            'favorites' => '收藏次数',
            'hits' => '点击次数',
            'chapters' => '章节数',
            'comments' => '评论数',
            'words' => '总字数',
            'vip' => '是否VIP可看',
            'bookStatus' => '小说状态',
            'status' => '系统状态',
            'top' => '是否置顶',
            'cTime' => '创建时间',
            'updateTime' => '更新时间',
            'topTime' => '置顶时间',
            'score' => '总评分',
            'scorer' => '评价人数',
            'score1' => '评1分',
            'score2' => '评2分',
            'score3' => '评3分',
            'score4' => '评4分',
            'score5' => '评5分',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('faceImg', $this->faceImg, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('favorites', $this->favorites, true);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('chapters', $this->chapters, true);
        $criteria->compare('comments', $this->comments, true);
        $criteria->compare('words', $this->words, true);
        $criteria->compare('vip', $this->vip);
        $criteria->compare('bookStatus', $this->bookStatus);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Books the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getIndexTops() {
        $cols = Column::allCols();
        $posts = array();
        foreach ($cols as $colid => $colTitle) {
            $_sql = "SELECT b.id AS bookId,a.id AS authorId,a.authorName,b.title,b.faceImg FROM {{authors}} a,{{books}} b WHERE b.colid=:colid AND b.aid=a.id ORDER BY b.topTime DESC LIMIT 48";
            $_res = YII::app()->db->createCommand($_sql);
            $_res->bindValue(':colid', $colid);
            $_posts = $_res->queryAll();
            $posts[$colid]['colInfo'] = array(
                'colid' => $colid,
                'colTitle' => $colTitle
            );
            $posts[$colid]['posts'] = $_posts;
        }
        return $posts;
    }

    public static function getOne($id) {
        if (!$id) {
            return false;
        }
        $info = Books::model()->findByPk($id);
        return $info;
    }

    /**
     * 更新小说的评分
     * @param type $bid
     */
    public static function updateScore($bid) {
        $sql = "SELECT t.id,t.score FROM {{tips}} t,{{chapters}} c WHERE c.bid='{$bid}' AND c.status=" . Posts::STATUS_PASSED . " AND c.id=t.logid AND t.classify='chapter' AND t.status=" . Posts::STATUS_PASSED;
        $items = Yii::app()->db->createCommand($sql)->queryAll();
        $total = count($items);
        $arr = CHtml::listData($items, 'id', 'score');
        $countArr = array_count_values($arr);
        $sum = array_sum($arr);
        $attr = array(
            'score' => $sum > 0 ? number_format($sum / $total * 2, 1, '.', '') : '0',
            'scorer' => $total,
            'score1' => $countArr['1'] ? $countArr['1'] : 0,
            'score2' => $countArr['2'] ? $countArr['2'] : 0,
            'score3' => $countArr['3'] ? $countArr['3'] : 0,
            'score4' => $countArr['4'] ? $countArr['4'] : 0,
            'score5' => $countArr['5'] ? $countArr['5'] : 0,
        );
        return Books::model()->updateByPk($bid, $attr);
    }

    /**
     * 根据评分分别显示出具体占比
     * @param type $bookInfo
     * @return string
     */
    public static function showScoreDetial($bookInfo) {
        $arr = array('5', '4', '3', '2', '1');
        $html = '';
        if (!$bookInfo['scorer']) {
            return $html;
        }
        foreach ($arr as $val) {
            $_per = number_format($bookInfo['score' . $val] / $bookInfo['scorer'] * 100, 0, '.', '');
            $html.='<div class="book-star-detail-item"><span class="star-title">' . $val . '星</span><span class="star-width" style="width:' . $_per . 'px"></span><span class="star-percent">' . $_per . '%</span></div>';
        }
        return $html;
    }

    /**
     * 处理生成评价
     */
    public static function starCss($starNum) {
        if ($starNum > 10) {
            $starNum = 10;
        }
        if ($starNum < 0) {
            $starNum = 0;
        }
        $star = $starNum / 2;
        $_floor = floor($star);
        if (!$_floor) {
            return '';
        }
        $_half = false;
        if ($star == $_floor) {
            $_len = $_int = $star;
        } elseif ($star > $_floor && $star <= ($_floor + 0.7)) {
            $_int = $_floor;
            $_len = $_floor + 0.5;
            $_half = true;
        } else {
            $_len = $_int = ceil($star);
        }
        $str = '';
        for ($i = 1; $i <= $_int; $i++) {
            $str.='<i class="fa fa-star"></i>';
        }
        if ($_half) {
            $str.='<i class="fa fa-star-half-o"></i>';
            $_int+=1;
        }
        if ($_int < 5) {
            for ($j = 0; $j < 5-$_int; $j++) {
                $str.='<i class="fa fa-star-o"></i>';
            }
        }
        return $str;
    }

}
