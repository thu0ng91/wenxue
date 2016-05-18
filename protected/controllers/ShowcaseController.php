<?php

/**
 * @filename ShowcaseController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-18  16:15:26 
 */
class ShowcaseController extends Q {
    
    public function actionIndex(){
        $columnid=  zmf::val('cid',2);
        if(!$columnid){
            throw new CHttpException(404, '请选择版块');
        }
        $posts=  Showcases::getPagePosts('returnColumnColumns',$columnid);
        $data = array(
            'posts' => $posts
        );
        $this->render('index',$data);
    }    
}