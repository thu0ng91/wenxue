<?php

/**
 * 前台共用类
 */
class Q extends Controller {

    public $layout = 'main';
    public $referer;
    public $uid;
    public $userInfo;
    public $selectNav = '';
    public $currentModule = 'mobile';
    public $platform;
    public $isMobile = false;
    public $keywords;
    public $mobileTitle;
    public $pageDescription;
    public $page = 1;
    public $pageSize = 30;
    public $isAjax = false;
    public $searchType = '';
    public $searchKeyword = '';
    public $rightBtns = array(); //手机版导航条右侧按钮
    public $returnUrl = ''; //左侧返回按钮的返回链接
    public $showLeftBtn = true; //左侧返回按钮
    public $showTopbar = true;
    public $adminLogin = false;
    public $links; //友链

    function init() {
        parent::init();
        Yii::app()->theme = 'web';
        if (zmf::config('mobile')) {
            if (zmf::checkmobile($this->platform)) {
                Yii::app()->theme = 'mobile';
                $this->isMobile = true;
            }
        }
        if (Yii::app()->request->isAjaxRequest && Yii::app()->request->isPostRequest) {
            $this->isAjax = true;
        }
        $page = zmf::val('page', 2);
        $this->page = $page >= 1 ? $page : 1;
        self::_referer();
        $uid = zmf::uid();
        if ($uid) {
            $this->uid = $uid;
            $this->userInfo = Users::getOne($uid);
            if ($this->userInfo['status'] != Posts::STATUS_PASSED) {
                Yii::app()->user->logout();
                unset($this->uid);
                unset($this->userInfo);
            } elseif (!$this->userInfo['groupid']) {
                $currentUrl = Yii::app()->request->url;
                if (strpos($currentUrl, 'user/joinGroup') === false && !$this->isAjax) {
                    $this->redirect(array('user/joinGroup'));
                }
            }
            $this->userInfo['favoriteForums'] = PostForums::getUserFavorites($this->uid);
        }
    }

    function _referer() {
        $currentUrl = Yii::app()->request->url;
        $arr = array(
            'login',
            'site/',
            '/error/',
            '/attachments/',
            '/weibo/',
            '/qq/',
            '/weixin/',
            '/user/index',
            '/search/',
        );
        $set = true;
        if ($set) {
            foreach ($arr as $val) {
                if (!$set) {
                    break;
                }
                if (strpos($currentUrl, $val) !== false) {
                    $set = false;
                    break;
                }
            }
        }
        if ($set && Yii::app()->request->isAjaxRequest) {
            $set = false;
        }
        $referer = zmf::getCookie('refererUrl');
        if ($set) {
            zmf::setCookie('refererUrl', $currentUrl, 86400);
        }
        if ($referer != '') {
            $this->referer = $referer;
        }
    }

    public function onlyOnPc() {
        if ($this->isMobile) {
            $this->layout = 'common';
            $this->render('//common/onlyOnPc');
            Yii::app()->end();
        }
    }

    public function checkUserStatus($return = FALSE) {
        $msg = $url = '';
        if (!$this->uid) {
            $msg = '请先登录';
            $url = Yii::app()->createUrl('site/login');
        } else {
            if (!$this->userInfo['groupid']) {
                $msg = '请先选择你的角色';
                $url = Yii::app()->createUrl('user/joinGroup');
            } elseif (!$this->userInfo['phoneChecked']) {
                $_groupInfo = Group::getOne($this->userInfo['groupid']);
                if (!$_groupInfo) {
                    $msg = '请先选择你的角色';
                    $url = Yii::app()->createUrl('user/joinGroup');
                } elseif ($_groupInfo['isAuthor']) {
                    $msg = '请先验证你的手机号';
                    $url = Yii::app()->createUrl('user/setting', array('action' => 'checkPhone'));
                }
            }
        }
        if ($return) {
            if ($msg != '') {
                return array(
                    'msg' => $msg,
                    'url' => $url,
                );
            } else {
                return true;
            }
        } else {
            if ($this->isAjax && $msg != '') {
                $this->jsonOutPut(0, $msg);
            } elseif ($msg != '') {
                $this->message(0, $msg, $url);
            }
        }
    }

}
