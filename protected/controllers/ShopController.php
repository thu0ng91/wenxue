<?php

class ShopController extends Q {

    public function actionIndex() {
        $this->pageTitle='积分商城 - '.zmf::config('sitename');
        $this->render('index');
    }

}
