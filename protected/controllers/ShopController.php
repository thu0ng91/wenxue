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

}
