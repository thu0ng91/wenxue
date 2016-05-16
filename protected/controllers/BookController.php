<?php

class BookController extends Q {

    public $favorited = false;
    public $tipInfo = array();

    public function actionIndex() {
        $this->render('index', $data);
    }

    public function actionView() {
        $id = zmf::val('id', 1);
        if (!$id) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $info = Books::getOne($id);
        if (!$info || $info['status'] != Posts::STATUS_PASSED) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $chapters = Chapters::getByBook($id);
        $this->favorited = Favorites::checkFavored($id, 'book');
        $data = array(
            'info' => $info,
            'chapters' => $chapters,
        );
        $this->render('view', $data);
    }

    public function actionChapter() {
        $cid = zmf::val('cid', 2);
        $chapterInfo = Chapters::model()->findByPk($cid);
        if (!$chapterInfo) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $bookInfo = Books::getOne($chapterInfo['bid']);
        if (!$bookInfo || $bookInfo['status']!=Posts::STATUS_PASSED) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $authorInfo=  Authors::getOne($chapterInfo['aid']);
        if(!$authorInfo || $authorInfo['status']!=Posts::STATUS_PASSED){
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        //获取点评数
        $sql = "SELECT t.id,t.uid,u.truename,t.content,t.cTime,t.logid,t.tocommentid,t.favors,t.score FROM {{tips}} t,{{users}} u WHERE t.logid=:logid AND t.classify='chapter' AND t.status=" . Posts::STATUS_PASSED . " AND t.uid=u.id AND u.status=" . Posts::STATUS_PASSED;
        $returnInfo = Posts::getByPage(array('sql' => $sql, 'pageSize' => 30, 'page' => 1, 'bindValues' => array(':logid' => $cid)));
        $tips = $returnInfo['posts'];

//        if(!empty($tips)){
//            $tocommentIdstr=  join(',', array_filter(array_unique(array_keys(CHtml::listData($tips, 'tocommentid', '')))));
//            if($tocommentIdstr!=''){
//                $_sql="SELECT t.id,t.uid,u.truename FROM {{tips}} t,{{users}} u WHERE t.id IN({$tocommentIdstr}) AND t.classify='chapter' AND t.status=".Posts::STATUS_PASSED." AND t.uid=u.id AND u.status=".Posts::STATUS_PASSED;
//                $_userInfoArr=Yii::app()->db->createCommand($_sql)->queryAll();
//                foreach ($tips as $k=>$tip){
//                    $tips[$k]['replyInfo']=array();
//                    if(!$tip['tocommentid']){
//                        continue;
//                    }
//                    foreach ($_userInfoArr as $_val){
//                        if($tip['tocommentid']==$_val['id']){
//                            $tips[$k]['replyInfo']=$_val;
//                            continue;
//                        }
//                    }
//                }
//            }
//        }
        //更新统计
        Posts::updateCount($cid, 'Chapters', 1, 'hits');
        //判断我是否已点评过
        $this->tipInfo = Chapters::checkTip($cid, $this->uid);
        //标题
        $this->pageTitle='【'.$bookInfo['title'].'】'.$chapterInfo['title'].' - '.$authorInfo['authorName'].'作品';
        $data = array(
            'bookInfo' => $bookInfo,
            'chapterInfo' => $chapterInfo,
            'chapters' => $chapters,
            'tips' => $tips,
        );
        $this->render('chapter', $data);
    }

    public function actionEditTip() {
        if(!$this->uid){
            $this->redirect(array('site/login'));
        }
        $tid = zmf::val('tid', 2);
        $model = Tips::model()->findByPk($tid);
        if(!$model || $model->classify!='chapter'){
            throw new CHttpException(404, 'The requested page does not exist.');
        }elseif($model->uid!=$this->uid){
            throw new CHttpException(403, '你无权此操作.');
        }
        if (isset($_POST['Tips'])) {
            $score=  zmf::filterInput($_POST['Tips']['score'],2);
            $content=  zmf::filterInput($_POST['Tips']['content'],1);
            $status=  zmf::filterInput($_POST['Tips']['status'],2);
            $attr=array(
                'score'=>($score<0 || $score>5) ? '' : $score,
                'content'=>$content,
                'status'=>($status<0 || $status>3) ? Posts::STATUS_DELED : $status,
            );
            $model->attributes = $attr;
            if ($model->save()) {
                $this->redirect(array('book/chapter','cid'=>$model->logid));                
            }
        }
        $data = array(
            'model' => $model,
        );
        $this->render('editTip', $data);
    }

}
