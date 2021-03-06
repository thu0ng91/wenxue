<?php

class SiteController extends Q {

    public function actions() {
        $cookieInfo = zmf::getCookie('checkWithCaptcha');
        if ($cookieInfo == '1') {
            return array(
                'captcha' => array(
                    'class' => 'CCaptchaAction',
                    'backColor' => 0xFFFFFF,
                    'minLength' => '2', // 最少生成几个字符
                    'maxLength' => '3', // 最多生成几个字符
                    'height' => '30',
                    'width' => '60'
                ),
                'page' => array(
                    'class' => 'CViewAction',
                ),
            );
        }
    }

    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                $outPutData = array(
                    'status' => 0,
                    'msg' => $error['message'],
                    'code' => $error['code']
                );
                $json = CJSON::encode($outPutData);
                header("Content-type:application/json;charset=UTF-8");
                echo $json;
                Yii::app()->end();
            } else {
                $this->layout = 'common';
                $this->pageTitle = '提示';
                $this->render('error', $error);
            }
        }
    }

    public function actionLogin() {
        if (!Yii::app()->user->isGuest) {
            $this->message(0, '你已登录，请勿重复操作');
        }
        if ($this->isMobile) {
            $this->layout = 'common';
        }
        $canLogin = true;
        $ip = Yii::app()->request->getUserHostAddress();
        $cacheKey = 'loginErrors-' . $ip;
        $errorTimes = zmf::getFCache($cacheKey);
        if ($errorTimes >= 5) {
            $canLogin = false;
        }
        if ($canLogin) {
            $model = new FrontLogin;
            if (isset($_POST['FrontLogin'])) {
                $model->attributes = $_POST['FrontLogin'];
                $validator = new CEmailValidator;
                if (is_numeric($_POST['FrontLogin']['phone']) && !zmf::checkPhoneNumber($_POST['FrontLogin']['phone'])) {
                    $model->addError('phone', '请输入正确的手机号');
                } elseif (!is_numeric($_POST['FrontLogin']['phone']) && !$validator->validateValue($_POST['FrontLogin']['phone'])) {
                    $model->addError('phone', '请输入正确的邮箱地址');
                } elseif ($model->validate() && $model->login()) {
                    $arr = array(
                        'latestLoginTime' => zmf::now(),
                    );
                    $uid = Yii::app()->user->id;
//                    User::model()->updateByPk($uid, $arr);                    
                    zmf::delCookie('checkWithCaptcha');
                    zmf::delFCache($cacheKey);
                    if ($this->referer) {
                        $this->redirect($this->referer);
                    } else {
                        $this->redirect(zmf::config('baseurl'));
                    }
                } else {
                    //zmf::updateFCacheCounter($cacheKey, 1, 3600);
                    zmf::setCookie('checkWithCaptcha', 1, 86400);
                }
            }
        }
        $this->pageTitle = '欢迎回来 - ' . zmf::config('sitename');
        $this->mobileTitle = '登录';
        $this->render('login', array(
            'model' => $model,
            'canLogin' => $canLogin,
        ));
    }

    public function actionLogout() {
        if (Yii::app()->user->isGuest) {
            $this->message(0, '你尚未登录');
        }
        Yii::app()->user->logout();
        $this->redirect(zmf::config('baseurl'));
    }

    public function actionReg() {
        if (!Yii::app()->user->isGuest) {
            $this->redirect($this->referer);
        }
        if ($this->isMobile) {
            $this->layout = 'common';
        }
        $ip = ip2long(Yii::app()->request->userHostAddress);
        if (zmf::actionLimit('reg', $ip, 5, 86400, true, true)) {
            throw new CHttpException(403, '你的操作太频繁，请明天再来吧');
        }
        $modelUser = new Users();
        if (isset($_POST['Users'])) {
            $truename = zmf::filterInput($_POST['Users']['truename'], 1);
            $email = zmf::filterInput($_POST['Users']['email'], 1);
            $password = $_POST['Users']['password'];
            $modelUser->attributes = $_POST['Users'];
            $validator = new CEmailValidator;
            $isEmail = $validator->validateValue($email);
            $isPhone = zmf::checkPhoneNumber($email);
            if (!$truename) {
                $modelUser->addError('truename', '用户昵称不能为空');
            } elseif (!$email) {
                $modelUser->addError('email', '请输入常用邮箱/手机号');
            } elseif (!$isEmail && !$isPhone) {
                $modelUser->addError('email', '请输入正确的邮箱/手机号');
            } elseif (!$password || strlen($password) < 6) {
                $modelUser->addError('password', '密码不能为空且不能小于6位');
            } elseif (Users::findByName($truename)) {
                $modelUser->addError('truename', '该用户昵称已被注册');
            } elseif ($isEmail && Users::findByEmail($email)) {
                $modelUser->addError('email', '该邮箱已被注册');
            } elseif ($isPhone && Users::findByPhone($email)) {
                $modelUser->addError('email', '该手机号已被注册');
            } else {
                $inputData = array(
                    'truename' => $truename,
                    'password' => md5($password),
                );
                if ($isEmail) {
                    $inputData['email'] = $email;
                } else {
                    $inputData['phone'] = $email;
                }
                $modelUser->attributes = $inputData;
                if ($modelUser->save()) {
                    zmf::actionLimit('reg', $ip, 5, 86400, true);
                    $_model = new FrontLogin;
                    $_model->phone = $email;
                    $_model->password = $password;
                    $_model->login();
                    $this->redirect(array('user/index'));
                }
            }
        }
        $this->pageTitle = '欢迎注册 - ' . zmf::config('sitename');
        $this->mobileTitle = '注册';
        $this->render('reg', array(
            'model' => $modelUser
        ));
    }

    public function actionForgot() {
        if ($this->isMobile) {
            $this->layout = 'common';
        }
        $this->referer = Yii::app()->createUrl('site/login');
        $this->pageTitle = '找回密码';
        $this->render('forgot', $data);
    }

    public function actionInfo() {
        $code = zmf::val('code', 1);
        $_title = SiteInfo::exTypes($code);
        if (!$_title) {
            throw new CHttpException(404, '你所查看的页面不存在2');
        }
        $info = SiteInfo::model()->find('code=:code', array(':code' => $code));
        if (!$info) {
            throw new CHttpException(404, '你所查看的页面不存在');
        }
        $allInfos = SiteInfo::model()->findAll(array(
            'select' => 'code,title',
            'condition' => 'code!=:code AND status=' . Posts::STATUS_PASSED,
            'params' => array(
                ':code' => $code
            )
        ));
        //更新访问统计
        Posts::updateCount($info['id'], 'SiteInfo');
        $data = array(
            'info' => $info,
            'code' => $code,
            'allInfos' => $allInfos,
        );
        $this->pageTitle = $info['title'] . ' - ' . zmf::config('sitename');
        $this->selectNav = $code;
        $this->render('about', $data);
    }

    public function actionRecommend() {
        $this->layout = 'common';
        //取热门作者
        $authors = Authors::model()->findAll(array(
            'condition' => 'status=1',
            'select' => 'id,authorName,avatar,content',
            'order' => 'score DESC',
            'limit' => 16
        ));
        foreach ($authors as $k => $val) {
            $authors[$k]['avatar'] = zmf::getThumbnailUrl($val['avatar'], 'a120', 'author');
        }
        //取热门用户
        $users = Users::model()->findAll(array(
            'condition' => 'status=1',
            'select' => 'id,truename,avatar,content',
            'order' => 'hits DESC',
            'limit' => 16
        ));
        foreach ($users as $k => $val) {
            $users[$k]['avatar'] = zmf::getThumbnailUrl($val['avatar'], 'a120', 'author');
        }

        $favoriteAuthor = GroupPowers::checkAction($this->userInfo, 'favoriteAuthor');
        $favoriteUser = GroupPowers::checkAction($this->userInfo, 'favoriteUser');
        $url = zmf::config('baseurl');
        if ($this->uid) {
            $ginfo = Group::getOne($this->userInfo['groupid']);
            if ($ginfo['isAuthor']) {
                if ($this->userInfo['authorId'] > 0) {
                    $url = Yii::app()->createUrl('user/index');
                } else {
                    $url = Yii::app()->createUrl('user/author');
                }
            } else {
                $url = Yii::app()->createUrl('user/index');
            }
        }
        $this->pageTitle = '推荐 - ' . zmf::config('sitename');
        $data = array(
            'authors' => $authors,
            'users' => $users,
            'favoriteAuthor' => $favoriteAuthor,
            'favoriteUser' => $favoriteUser,
            'url' => $url,
        );
        $this->render('recommend', $data);
    }

}
