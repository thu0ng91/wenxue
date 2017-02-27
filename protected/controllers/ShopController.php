<?php

class ShopController extends Q {

    public function actionIndex() {
        $navbars = GoodsClassify::getNavbar(TRUE);
        //获取推荐商品
        $sql = "SELECT id,title,`desc`,scorePrice,goldPrice,classify,comments,score,faceUrl FROM {{goods}} WHERE topTime>0 ORDER BY topTime DESC LIMIT 30";
        $posts = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($posts as $k => $val) {
            $posts[$k]['faceUrl'] = zmf::getThumbnailUrl($val['faceUrl'], 'a210', 'goods');
        }
        $this->pageTitle = '积分商城 - ' . zmf::config('sitename');
        $data = array(
            'navbars' => $navbars,
            'posts' => $posts,
        );
        $this->render('index', $data);
    }

    public function actionAll() {
        $id = zmf::val('id', 2);
        if (!$id) {
            $this->redirect(array('shop/index'));
        }
        //分类信息
        $colinfo = GoodsClassify::getOne($id);
        if (!$colinfo) {
            throw new CHttpException(404, '你所查看的分类不存在');
        }
        //分类所属
        $belongs = GoodsClassify::getOneBelongs($id);
        //同级分类
        $navbars = GoodsClassify::getChildren($belongs[1]['id']);
        $sql = '';
        if ($colinfo['level'] == 3) {
            $sql = "SELECT id,title,`desc`,scorePrice,goldPrice,classify,comments,score,faceUrl FROM {{goods}}";
        } else {
            //不是最小分类，则存在多个分类的可能
            $colArr = array();
            foreach ($navbars as $val) {
                foreach ($val['items'] as $v2) {
                    $colArr[] = $v2['id'];
                }
            }
            $colArrId = join(',', array_unique(array_filter($colArr)));
            if ($colArrId != '') {
                $sql = "SELECT id,title,`desc`,scorePrice,goldPrice,classify,comments,score,faceUrl FROM {{goods}} WHERE classify IN({$colArrId})";
            }
        }
        if ($sql != '') {
            Posts::getAll(array('sql' => $sql), $pages, $posts);
            foreach ($posts as $k => $val) {
                $posts[$k]['faceUrl'] = zmf::getThumbnailUrl($val['faceUrl'], 'a210', 'goods');
            }
        }
        $this->pageTitle = $colinfo['title'] . ' - ' . zmf::config('sitename');
        $data = array(
            'pages' => $pages,
            'posts' => $posts,
            'colinfo' => $colinfo,
            'belongs' => $belongs,
            'navbars' => $navbars,
        );
        $this->render('all', $data);
    }

    public function actionDetail() {
        $id = zmf::val('id', 2);
        if (!$id) {
            throw new CHttpException(404, '你所查看的商品不存在');
        }
        $return = Goods::detail($id, '640');
        if (!$return['status']) {
            throw new CHttpException(403, $return['msg']);
        }
        $info = $return['msg'];
        $info['faceUrl'] = zmf::getThumbnailUrl($info['faceUrl'], 'a360', 'goods');
        //相关推荐
        if($info['classify']){
            $_classifyArr=end($info['classify']);
            $_classify=$_classifyArr['id'];
            if($_classify){
                $sql='SELECT id,title,faceUrl,scorePrice,goldPrice FROM {{goods}} WHERE classify='.$_classify.' AND id='.$info['id'];
                $posts=Yii::app()->db->createCommand($sql)->queryAll();
            }
        }
        $this->pageTitle = $info['title'] . ' - ' . zmf::config('sitename');
        $data = array(
            'info' => $info,
            'posts' => $posts,
        );
        $this->render('detail', $data);
    }

}
