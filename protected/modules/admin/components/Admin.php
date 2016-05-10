<?php

class Admin extends Controller {

    public $pageTitle;
    public $layout = 'main';
    public $menu = array();
    public $breadcrumbs = array();
    public $userInfo;
    public $uid;

    public function init() {
        parent::init();
        $passwdErrorTimes = zmf::getCookie('checkWithCaptcha');
        $time = zmf::config('adminErrorTimes');
        if ($time > 0) {
            if ($passwdErrorTimes >= $time) {
                header('Content-Type: text/html; charset=utf-8');
                echo '您暂时已被禁止访问';
                Yii::app()->end();
            }
        }
        $uid = zmf::uid();
        if ($uid) {
//            $randKey_cookie = zmf::getCookie('adminRandKey' . $uid);
//            $randKey_cache = zmf::getFCache('adminRandKey' . $uid);
//            if (!$randKey_cookie || ($randKey_cache != $randKey_cookie)) {
//                Yii::app()->user->logout();
//                $this->message(0, '登录已过期，请重新登录', Yii::app()->createUrl('admin/site/login'));
//            }
            $this->userInfo = Users::getOne($uid);
            $this->uid=$uid;
        } else {
            $currentUrl = Yii::app()->request->url;
            if (strpos($currentUrl, '/site/') === false) {
                $this->message(0, '请先登录', Yii::app()->createUrl('/site/login'));
            }
        }
    }
}
