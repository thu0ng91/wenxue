<?php

/**
 * This is the model class for table "{{showcases}}".
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-17 08:45:33
 * The followings are the available columns in table '{{showcases}}':
 * @property string $id
 * @property string $uid
 * @property string $title
 * @property string $position
 * @property string $display
 * @property integer $num
 * @property integer $status
 * @property string $cTime
 */
class Showcases extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{showcases}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('cTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),            
            array('uid, title, position, display', 'required'),
            array('num, status', 'numerical', 'integerOnly' => true),
            array('uid, cTime,columnid', 'length', 'max' => 10),
            array('title, position, display,classify', 'length', 'max' => 16),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, uid, title, position, display, num, status, cTime', 'safe', 'on' => 'search'),
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
            'uid' => '创建人ID',
            'classify' => '分类',
            'columnid' => '所属板块',
            'title' => '标题',
            'position' => '位置',
            'display' => '展示方式',
            'num' => '条数',
            'status' => '状态',
            'cTime' => '创建时间',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('position', $this->position, true);
        $criteria->compare('display', $this->display, true);
        $criteria->compare('num', $this->num);
        $criteria->compare('status', $this->status);
        $criteria->compare('cTime', $this->cTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Showcases the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function getOne($id){
        return Showcases::model()->findByPk($id);
    }
    
    public static function exPosition($type){
        $arr=array(
            //首页版块
            'indexLeft1'=>'首页左边1',
            'indexRight1'=>'首页右边1',
            'indexAd1'=>'首页小图1',            
            'indexLeft2'=>'首页左边2',
            //'indexRight2'=>'首页右边2',
            'indexAd2'=>'首页小图2',            
            //'indexLeft3'=>'首页左边3',
            //'indexRight3'=>'首页右边3',
            //'indexAd3'=>'首页小图3',
            //跟版块绑定的栏目            
            'column11'=>'版块第一排1',
            'column12'=>'版块第一排2',
            'column13'=>'版块第一排3',
            'column21'=>'版块第二排1',
            'column22'=>'版块第二排2',
            'column23'=>'版块第二排3',            
            'column31'=>'版块第三排1',
            'column32'=>'版块第三排2',
            'column33'=>'版块第三排3',
            //其他
            'reg'=>'登录注册轮播图',
            'author'=>'作者、读者专区右侧广告',
            //专区            
            'authorTop'=>'作者专区右侧推荐',
            'readerTop'=>'读者专区右侧推荐',
            //站点右侧二维码
            'weiboSideQr'=>'站点右侧微博二维码',
            'weixinSideQr'=>'站点右侧微信二维码',
        );
        if($type=='admin'){
            return $arr;
        }elseif($type=='returnIndexColumns' || $type=='returnColumnColumns'){
            if($type=='returnIndexColumns'){
                $find='index';
            }elseif($type=='returnColumnColumns'){
                $find='column';
            }
            $tmp=array();
            foreach ($arr as $key=>$val){
                if(strpos($key, $find)!==false){
                    $tmp[]=$key;
                }
            }
            return $tmp;
        }elseif($type=='authorQzone'){
            return array('author','authorTop','weiboSideQr','weixinSideQr');
        }elseif($type=='readerQzone'){
            return array('author','readerTop','weiboSideQr','weixinSideQr');
        }
        return $arr[$type];
    }
    
    public static function exDisplay($type){
        $arr=array(
            'thumb'=>'缩略图',
            'link'=>'纯文字链接',
            'thumbFirst'=>'首条缩略图',
            'thumbThird'=>'前三条缩略图',
        );
        if($type=='admin'){
            return $arr;
        }
        return $arr[$type];
    }
    
    public static function exClassify($type){
        $arr=array(
            'book'=>'小说',
            'ad'=>'广告',
        );
        if($type=='admin'){
            return $arr;
        }
        return $arr[$type];
    }
    
    /**
     * 根据页面、栏目或者位置显示作品
     * @param string $type 版块或位置
     * @param int $columnid 所属栏目
     * @param bool $showType 是否只按位置显示
     * @return array
     */
    public static function getPagePosts($type,$columnid=NULL,$showType=false,$adImgSize='c120',$postImgSize='w120'){
        if(!$showType){
            $arr=  Showcases::exPosition($type);
        }else{
            $arr[]=$type;
        }
        $arrWithQuote=array();
        foreach ($arr as $val){
            $arrWithQuote[]="'{$val}'";
        }
        $positionStr=join(',',$arrWithQuote);
        $sql="SELECT id,title,position,display,num,classify FROM {{showcases}} WHERE ".($columnid ? "columnid='{$columnid}' AND " : '')." position IN({$positionStr})";
        $showcases=  Yii::app()->db->createCommand($sql)->queryAll();
        $posts=array();
        foreach ($showcases as $case){
            $_tmp=$case;
            if($case['classify']=='book'){
                $_sql="SELECT b.id,b.aid,b.colid,'' AS colTitle,b.title,b.faceImg,b.desc,'' AS authorName FROM {{books}} b,{{showcase_link}} sl WHERE sl.sid='{$case['id']}' AND sl.classify='book' AND sl.logid=b.id ORDER BY sl.startTime ASC LIMIT {$case['num']}";
                $_posts=  Yii::app()->db->createCommand($_sql)->queryAll();
                if(!empty($_posts)){
                    $colidStr=  join(',', array_unique(array_keys(CHtml::listData($_posts, 'colid', ''))));
                    $aidStr=  join(',', array_unique(array_keys(CHtml::listData($_posts, 'aid', ''))));
                    if($colidStr!=''){
                        $_sqlCol="SELECT id,title FROM {{column}} WHERE id IN({$colidStr})";
                        $_colsArr=Yii::app()->db->createCommand($_sqlCol)->queryAll();
                        foreach ($_posts as $k=>$val){
                            $_posts[$k]['faceImg']=  zmf::getThumbnailUrl($val['faceImg'], $postImgSize, 'showcase');
                            foreach ($_colsArr as $_colval){
                                if($_colval['id']==$val['colid']){
                                    $_posts[$k]['colTitle']=$_colval['title'];
                                    continue;
                                }
                            }
                        }
                    }
                    if($aidStr!=''){
                        $_sqlCol="SELECT id,authorName FROM {{authors}} WHERE id IN({$aidStr})";
                        $_authorArr=Yii::app()->db->createCommand($_sqlCol)->queryAll();
                        foreach ($_posts as $k=>$val){                            
                            foreach ($_authorArr as $_aval){
                                if($_aval['id']==$val['aid']){
                                    $_posts[$k]['authorName']=$_aval['authorName'];
                                    continue;
                                }
                            }
                        }
                    }
                }
                $_tmp['posts']=$_posts;
            }elseif($case['classify']=='ad'){
                $_sql="SELECT title,faceimg,content,url FROM {{showcase_link}} WHERE sid='{$case['id']}' AND classify='ad' ORDER BY startTime ASC LIMIT {$case['num']}";
                $_posts=  Yii::app()->db->createCommand($_sql)->queryAll();
                foreach ($_posts as $k=>$val){
                    $_posts[$k]['faceimg']=  zmf::getThumbnailUrl($val['faceimg'], $adImgSize, 'showcase');
                }
                $_tmp['post']=$_posts;
            }
            $posts[$case['position']]=$_tmp;
        }
        return $posts;
    }

}
