<?php

/**
 * @filename ZazhiController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-14  17:29:15 
 */
class ZazhiController extends Q {

    public $favorited = false;
    public $zazhiInfo;
    public $chapters;
    public $next;
    public $prev;
    public $currentPage;
    public $currentPostId;
    
    public function actionIndex(){
        $this->selectNav='zazhi';
        $sql="SELECT id,title,content,faceimg,favorites,comments FROM {{zazhi}} WHERE status=".Posts::STATUS_PASSED;
        Posts::getAll(array('sql'=>$sql), $pages, $posts);
        foreach ($posts as $k=>$val){
            $posts[$k]['faceimg']=  Attachments::faceImg($val, '', 'zazhi');
        }
        $this->pageTitle='杂志列表';
        $data=array(
            'posts'=>$posts,
            'pages'=>$pages
        );
        $this->render('index',$data);
    }

    private function checkInfo(){
        $zid=  zmf::val('zid',2);
        if(!$zid){
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $this->zazhiInfo=  Zazhi::getOne($zid);
        if($this->zazhiInfo['status']!=Posts::STATUS_PASSED && !$this->uid){
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $this->chapters=  Zazhi::getChapters($this->zazhiInfo['id']);      
    }
    
    public function actionView(){
        $this->checkInfo();
        $this->currentModule='magazine';
        $this->layout='zazhi';
        $this->next=$this->chapters[0];        
        $this->currentPage='faceimg';
        $this->pageTitle=$this->zazhiInfo['title'].' - '.zmf::config('sitename');
        $this->pageDescription=  zmf::subStr($this->zazhiInfo['content'],140);
        //展示更多杂志
        //$others=  Zazhi::getOthers($this->zazhiInfo['id']);
        //统计数据
        $key="updateZazhiStat-".$this->zazhiInfo['id'];
        $_set=  zmf::getFCache($key);
        if($_set!='zmf'){
            Zazhi::updateStat($this->zazhiInfo['id']);
            zmf::setFCache($key, 'zmf',600);
        }
        $size = '960';
        if ($this->isMobile) {
            $size = '640';
        }
        $this->zazhiInfo['faceimg']=  zmf::getThumbnailUrl($this->zazhiInfo['faceimg'], $size, 'zazhi');
        $data = array(
            'info' => $this->zazhiInfo,
            'others' => $others,
        );
        $this->render('view', $data);
    }

    public function actionChapter() {
        $this->checkInfo();
        $this->currentModule='magazine';
        $id = zmf::val('id', 2);
        if (!$id) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } 
        $info = $this->loadModel($id);
        if(!$info['zazhi']){
            $this->redirect(array('posts/view','id'=>$id));
        }
        $this->currentPostId=$id;
        foreach ($this->chapters as $k=>$val){
            $find=false;
            if($val['id']==$id){
                $this->next=  $this->chapters[$k+1];   
                $this->prev=  $this->chapters[$k-1];
                $find=true;
            }
            if(!$this->prev && $k==0){
                $this->prev=array(
                    'title'=>'封面',
                    'url'=>Yii::app()->createUrl('zazhi/view',array('zid'=>$info['zazhi'])),
                );
            }
            if($find){
                break;
            }
        }
        $this->layout='zazhi';
        $pageSize = 30;
        $comments = Comments::getCommentsByPage($id, 'posts', 1, $pageSize);
        $tags = Tags::getByIds($info['tagids']);
        $relatePosts = Posts::getRelations($id, 5);
        if (!zmf::actionLimit('visit-Posts', $id, 5, 60)) {
            Posts::updateCount($id, 'Posts', 1, 'hits');
        }
        $size = '960';
        if ($this->isMobile) {
            $size = '640';
        }
        $info['content'] = zmf::text(array(), $info['content'], true, $size);
        //展示更多杂志
        $others=array();
        if(empty($this->next)){
            //$others=  Zazhi::getOthers($this->zazhiInfo['id']);
        }
        $data = array(
            'info' => $info,
            'chapters' => $this->chapters,
            'comments' => $comments,
            'tags' => $tags,
            'others' => $others,
            'loadMore' => count($comments) == $pageSize ? 1 : 0,
        );
        $this->favorited = Favorites::checkFavored($id, 'post');
        $this->pageTitle = $info['title'];
        $this->render('chapter', $data);
    }

    public function loadModel($id) {
        $model = Posts::model()->findByPk($id);
        if ($model === null || $model->status != Posts::STATUS_PASSED)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
