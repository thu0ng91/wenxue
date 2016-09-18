<?php

class ShopController extends Q {

    public function actionIndex() {
        $navbars=GoodsClassify::getNavbar();
        $this->pageTitle='积分商城 - '.zmf::config('sitename');
        $data=array(
            'navbars'=>$navbars
        );
        $this->render('index',$data);
    }
    
    public function actionAll(){
        $sql="SELECT id,title,`desc`,scorePrice,goldPrice,classify,comments,score,faceUrl FROM {{goods}}";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        foreach ($posts as $k=>$val){
            $posts[$k]['faceUrl']=  zmf::getThumbnailUrl($val['faceUrl'], '280', 'goods');
        }
        $data=array(
            'pages'=>$pages,
            'posts'=>$posts,
        );
        $this->render('all',$data);
    }
    
    public function actionDetail(){
        $id=  zmf::val('id',2);
        if(!$id){
            throw new CHttpException(404, '你所查看的商品不存在');
        }
        $return=  Goods::detail($id,'640');
        if(!$return['status']){
            throw new CHttpException(403, $return['msg']);
        }
        $data=array(
            'info'=>$return['msg']
        );
        $this->render('detail',$data);
    }

}
