<?php

/**
 * @filename SearchController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-30  11:51:18 
 */
class SearchController extends Q {

    public function actionDo() {
        $type=  zmf::val('type',1);
        $_keyword=  zmf::val('keyword',1);
        $keyword=  zmf::subStr($_keyword,8,0,'');
        if($keyword){
            $keyword='%' . strtr($keyword, array('%' => '\%', '_' => '\_', '\\' => '\\\\')) . '%';
            if($type=='author'){
                $sql="SELECT id,authorName,avatar,content FROM {{authors}} WHERE authorName LIKE :keyword AND status=".Posts::STATUS_PASSED;
                $posts=  Posts::getByPage(array(
                    'sql'=>$sql,
                    'page'=>  $this->page,
                    'pageSize'=>  $this->pageSize,
                    'bindValues'=>array(
                        ':keyword'=>$keyword
                    )
                ));
                foreach ($posts as $k=>$val){
                    $posts[$k]['avatar']=  zmf::getThumbnailUrl($val['avatar'], 'w120', 'author');
                }
            }elseif ($type=='book') {
                $sql="SELECT id,title,faceImg,`desc`,words,cTime,score,scorer,bookStatus FROM {{books}} WHERE title LIKE :keyword AND status=".Posts::STATUS_PASSED." AND bookStatus=".Books::STATUS_PUBLISHED;
                $posts=  Posts::getByPage(array(
                    'sql'=>$sql,
                    'page'=>  $this->page,
                    'pageSize'=>  $this->pageSize,
                    'bindValues'=>array(
                        ':keyword'=>$keyword
                    )
                ));
                foreach ($posts as $k=>$val){
                    $posts[$k]['faceImg']=  zmf::getThumbnailUrl($val['faceImg'], 'w120', 'book');
                }
            }elseif ($type=='chapter') {
                $sql="SELECT b.id AS bid,b.title AS bTitle,b.faceImg,b.desc,c.id AS cid,c.title AS chapterTitle FROM {{books}} b,{{chapters}} c WHERE c.title LIKE :keyword AND c.status=".Books::STATUS_PUBLISHED." AND c.bid=b.id AND b.bookStatus=".Books::STATUS_PUBLISHED;
                $posts=  Posts::getByPage(array(
                    'sql'=>$sql,
                    'page'=>  $this->page,
                    'pageSize'=>  $this->pageSize,
                    'bindValues'=>array(
                        ':keyword'=>$keyword
                    )
                ));
                foreach ($posts as $k=>$val){
                    $posts[$k]['faceImg']=  zmf::getThumbnailUrl($val['faceImg'], 'w120', 'book');
                }
            }elseif ($type=='user') {
                $sql="SELECT id,truename,avatar,content FROM {{users}} WHERE truename LIKE :keyword AND status=".Posts::STATUS_PASSED;
                $posts=  Posts::getByPage(array(
                    'sql'=>$sql,
                    'page'=>  $this->page,
                    'pageSize'=>  $this->pageSize,
                    'bindValues'=>array(
                        ':keyword'=>$keyword
                    )
                ));
                foreach ($posts as $k=>$val){
                    $posts[$k]['avatar']=  zmf::getThumbnailUrl($val['avatar'], 'a120', 'user');
                }
            }
        }
        $this->pageTitle='搜索 - '.zmf::config('sitename');
        $data=array(
            'type'=>$type,
            'keyword'=>$_keyword,
            'posts'=>$posts,
        );
        $this->render('/site/search',$data);
    }

}
