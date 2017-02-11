<?php

/**
 * @filename MsgController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-7-10  14:30:59 
 */
class MsgController extends Admin {
    
    public function init() {
        parent::init();
        //$this->checkPower('msg');
    }

    public function actionIndex() {
        $start = zmf::val('start', 1);
        $end = zmf::val('end', 1);
        $select = zmf::val('select', 1);
        $now = zmf::now();
        
        
        $criteria = new CDbCriteria();
        $criteria->order = 'cTime DESC';
        $phone = zmf::val('phone', 1);
        if ($phone) {
            $criteria->addCondition("phone='" . $phone . "'");
        }
        if ($start) {
            $start = strtotime($start, $now);
            $criteria->addCondition("cTime>='{$start}'");
        }
        if ($end) {
            $end = strtotime($end, $now);
            $criteria->addCondition("cTime<='{$end}'");
        }
        if($select=='notUse'){
            if($_GET['page']<=1){
                $sql4="UPDATE {{msg}} m,{{user}} u SET m.uid=u.id WHERE m.phone=u.phone";
                Yii::app()->db->createCommand($sql4)->execute();
            }
            $criteria->addCondition("uid=0");
        }
        $count = Msg::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = Msg::model()->findAll($criteria);
        if(!empty($posts)){
            foreach ($posts as $k=>$v){
                if(!$v['uid']){
                    $_uinfo=  Users::findByPhone($v['phone']);
                    if($_uinfo){
                        $posts[$k]['uid']=$_uinfo['id'];
                    }
                }
            }
        }
        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
        ));
    }

}
