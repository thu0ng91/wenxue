<?php

/**
 * This is the model class for table "{{goods}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-10 16:11:15
 * The followings are the available columns in table '{{goods}}':
 * @property string $id
 * @property string $title
 * @property string $desc
 * @property string $scorePrice
 * @property string $goldPrice
 * @property string $content
 * @property string $classify
 * @property string $comments
 * @property string $hits
 * @property string $score
 */
class Goods extends CActiveRecord {
    
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{goods}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title,scorePrice,goldPrice,classify', 'required'),
            array('title', 'length', 'max' => 32),
            array('desc,faceUrl,groupids,actionId', 'length', 'max' => 255),
            array('scorePrice, goldPrice', 'length', 'max' => 16),
            array('content, classify, comments, hits,faceimg', 'length', 'max' => 10),
            array('score', 'length', 'max' => 4),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, desc, scorePrice, goldPrice, content, classify, comments, hits, score', 'safe', 'on' => 'search'),
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
            'id' => '商品ID',
            'title' => '商品标题',
            'desc' => '简短描述',
            'scorePrice' => '积分价格',
            'goldPrice' => '金币价格',
            'content' => '商品描述',
            'classify' => '分类',
            'comments' => '评论数',
            'hits' => '点击数',
            'score' => '评分',
            'groupids' => '所属团队',
            'faceimg' => '封面图ID',
            'faceUrl' => '封面图',
            'actionId' => '关联事件ID',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('scorePrice', $this->scorePrice, true);
        $criteria->compare('goldPrice', $this->goldPrice, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('classify', $this->classify, true);
        $criteria->compare('comments', $this->comments, true);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('score', $this->score, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Goods the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function getOne($id){
        return Goods::model()->findByPk($id);
    }
    public static function detail($id,$imgSize='640'){
        $info=  self::getOne($id);
        if(!$info){
            return array(
                'status'=>0,
                'msg'=>'你所查看的商品不存在'
            );
        }
        $classify=  GoodsClassify::getOneBelongs($info['classify']);
        $info['classify']=$classify;
        $info['content']=  GoodsContent::detailOne($info['content'], $imgSize);
        $info['actionId']=  GoodsAction::getOneActions($id);
        return array(
            'status'=>1,
            'msg'=>$info
        );
    }

}
