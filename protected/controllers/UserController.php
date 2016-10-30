<?php

class UserController extends Q {

    public $toUserInfo;
    public $favorited = false;
    public $myself = false;

    public function init() {
        parent::init();
        $this->layout = 'user';
        $id = zmf::val('id', 2);
        if (!$id && !$this->uid) {
            throw new CHttpException(404, '你所查看的用户不存在，请核实');
        } elseif (!$id || $id == $this->uid) {
            $this->toUserInfo = $this->userInfo;
        } else {
            $this->toUserInfo = Users::getOne($id);
        }
        if ($this->toUserInfo['id'] == $this->uid) {
            $this->adminLogin = Authors::checkLogin($this->userInfo, $this->userInfo['authorId']);
            $this->myself = true;
        } else {
            Posts::updateCount($id, 'Users', 1, 'hits');
        }
        if ($this->uid) {
            $this->favorited = Favorites::checkFavored($this->toUserInfo['id'], 'user');
        }
    }

    private function checkLogin() {
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
    }

    private function showError() {
        
    }

    public function actionIndex() {
        if ($this->myself) {
            $sql = "SELECT ua.id,ua.uid,ua.classify,ua.`data`,ua.cTime FROM {{user_action}} ua,{{favorites}} f WHERE f.uid='{$this->uid}' AND f.classify='user' AND ua.display=1 AND (f.logid=ua.uid OR ua.uid='{$this->uid}') ORDER BY ua.cTime DESC";
        } else {
            $sql = "SELECT uid,classify,`data`,cTime FROM {{user_action}} WHERE uid='{$this->toUserInfo['id']}' AND display=1 ORDER BY cTime DESC";
        }
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        if (!empty($posts)) {
            $usersArr = array();
            if ($this->myself) {
                $uids = join(',', array_unique(array_keys(CHtml::listData($posts, 'uid', 'id'))));
                if ($uids != '') {
                    $sql2 = "SELECT id,truename,avatar FROM {{users}} WHERE id IN($uids)";
                    $usersArr = Yii::app()->db->createCommand($sql2)->queryAll();
                }
            }
            foreach ($posts as $k => $val) {
                $posts[$k]['data'] = CJSON::decode($val['data']);
                $posts[$k]['action'] = UserAction::exClassify($val['classify']);
                if ($this->myself && !empty($usersArr)) {
                    foreach ($usersArr as $val2) {
                        if ($val2['id'] == $val['uid']) {
                            $posts[$k]['truename'] = $val2['truename'];
                            $posts[$k]['avatar'] = zmf::getThumbnailUrl($val2['avatar'], 'a120', 'user');
                        }
                    }
                } else {
                    $posts[$k]['truename'] = $this->toUserInfo['truename'];
                    $posts[$k]['avatar'] = zmf::getThumbnailUrl($this->toUserInfo['avatar'], 'a120', 'user');
                }
            }
        }
        $this->selectNav = 'index';
        $this->pageTitle = $this->toUserInfo['truename'] . ' - ' . zmf::config('sitename');
        $this->render('index', array(
            'posts' => $posts,
            'pages' => $pages,
        ));
    }

    public function actionFollow() {
        $type = zmf::val('type', 1);
        if ($type == 'fans') {
            $sql = "SELECT u.id,u.truename,u.avatar FROM {{users}} u,{{favorites}} f WHERE f.logid='{$this->toUserInfo['id']}' AND f.classify='user' AND f.uid=u.id ORDER BY f.cTime DESC";
            $label = '关注者';
            $render = 'fans';
            $this->pageTitle = $this->toUserInfo['truename'] . '的关注者 - ' . zmf::config('sitename');
        } elseif ($type == 'authors') {
            $sql = "SELECT a.id,a.authorName,a.avatar FROM {{authors}} a,{{favorites}} f WHERE f.uid='{$this->toUserInfo['id']}' AND f.classify='author' AND f.logid=a.id ORDER BY f.cTime DESC";
            $label = '关注作者';
            $render = 'authors';
            $this->pageTitle = $this->toUserInfo['truename'] . '关注的作者 - ' . zmf::config('sitename');
        } else {
            $sql = "SELECT u.id,u.truename,u.avatar FROM {{users}} u,{{favorites}} f WHERE f.uid='{$this->toUserInfo['id']}' AND f.classify='user' AND f.logid=u.id ORDER BY f.cTime DESC";
            $label = '关注了';
            $render = 'fans';
            $this->pageTitle = $this->toUserInfo['truename'] . '的关注 - ' . zmf::config('sitename');
        }
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        foreach ($posts as $k => $val) {
            $posts[$k]['avatar'] = zmf::getThumbnailUrl($val['avatar'], 'a120', 'avatar');
        }
        $this->selectNav = 'favorite';
        $data = array(
            'label' => $label,
            'posts' => $posts,
            'pages' => $pages,
        );
        $this->render($render, $data);
    }

    public function actionAuthor() {
        $this->checkLogin();
        $this->checkUserStatus();
        $authorInfo = Authors::findByUid($this->uid);
        if ($authorInfo) {
            throw new CHttpException(403, '你已成为作者，请勿重复操作');
        }
        //获取用户组的权限
        $powerInfo = GroupPowers::checkPower($this->userInfo, 'createAuthor');
        if (!$powerInfo['status']) {
            $this->message($powerInfo['status'], $powerInfo['msg']);
        }
        $this->selectNav = 'setting';
        $model = new Authors;
        $model->uid = $this->uid;
        if (isset($_POST['Authors'])) {
            $password = $_POST['Authors']['password'];
            $authorName = $_POST['Authors']['authorName'];
            if (!$authorName) {
                $field = 'authorName';
                $msg = '作者名不能为空';
            } elseif (!$password || strlen($password) < 6) {
                $field = 'password';
                $msg = '密码不短于6位';
            } elseif (Authors::findByName($authorName)) {
                $field = 'authorName';
                $msg = '该作者名已被占用';
            }
            if (!$field && !$msg) {
                $_POST['Authors']['hashCode'] = zmf::randMykeys(6);
                $_POST['Authors']['password'] = md5($password . $_POST['Authors']['hashCode']);
                $model->attributes = $_POST['Authors'];
                if ($model->save()) {
                    Users::model()->updateByPk($this->uid, array('authorId' => $model->id));
                    $code = 'authorAuth-' . $this->uid;
                    Yii::app()->session[$code] = 1;
                    $this->redirect(array('author/view', 'id' => $model->id));
                }
            } else {
                $model->attributes = $_POST['Authors'];
                $model->addError($field, $msg);
            }
        }
        $this->pageTitle = '成为作者 - ' . zmf::config('sitename');
        $this->render('createAuthor', array(
            'model' => $model,
        ));
    }

    public function actionAuthorAuth() {
        $this->checkLogin();
        $this->checkUserStatus();
        $authorInfo = Authors::findByUid($this->uid);
        if (!$authorInfo) {
            throw new CHttpException(403, '你尚未成为作者');
        }
        if ($this->isMobile) {
            $this->layout = 'common';
            $this->referer = Yii::app()->createUrl('user/index');
        }
        $model = new Authors;
        if (isset($_POST['Authors'])) {
            $password = $_POST['Authors']['password'];
            if (!$password) {
                $model->addError('password', '请填写密码');
            } elseif (md5($password . $authorInfo['hashCode']) != $authorInfo['password']) {
                $model->addError('password', '密码错误，请重试');
            } else {
                $code = 'authorAuth-' . $this->uid;
                Yii::app()->session[$code] = true;
                $this->redirect(array('author/view', 'id' => $authorInfo['id']));
            }
        }
        $this->pageTitle = '登录作者中心 - ' . zmf::config('sitename');
        $this->render('authorAuth', array(
            'model' => $model,
        ));
    }

    public function actionForgotAuthorPass() {
        $this->checkLogin();
        $this->pageTitle = '找回密码';
        $this->render('forgot', $data);
    }

    public function actionComment() {
        //获取点评列表
        $sql = "SELECT t.id,t.uid,t.bid,b.title AS bookTitle,t.logid,c.title AS chapterTitle,t.content,t.score,t.favors,t.cTime,t.status FROM ({{tips}} AS t RIGHT JOIN {{chapters}} AS c ON t.logid=c.id) LEFT JOIN {{books}} AS b ON t.bid=b.id WHERE t.uid=:uid AND t.classify='chapter' AND t.logid=c.id ORDER BY t.cTime ASC";
        $tips = Posts::getByPage(array(
                    'sql' => $sql,
                    'page' => $this->page,
                    'pageSize' => $this->pageSize,
                    'bindValues' => array(
                        ':uid' => $this->toUserInfo['id']
                    )
        ));
        foreach ($tips as $k => $tip) {
            $tips[$k]['avatar'] = zmf::getThumbnailUrl($this->toUserInfo['avatar'], 'd120', 'user');
        }
        $this->selectNav = 'comment';
        $this->pageTitle = $this->toUserInfo['truename'] . '的点评 - ' . zmf::config('sitename');
        $this->render('comment', array(
            'tips' => $tips,
        ));
    }

    public function actionThreads() {
        $sql = "SELECT p.id,p.title,p.faceImg,p.uid,p.cTime,p.posts,p.hits,p.top,p.digest,p.styleStatus,p.aid FROM {{post_threads}} p WHERE p.uid='{$this->toUserInfo['id']}' AND p.status=" . Posts::STATUS_PASSED . " ORDER BY p.top DESC,p.cTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        $data = array(
            'posts' => $posts,
            'pages' => $pages,
        );
        $this->selectNav = 'threads';
        $this->pageTitle = $this->userInfo['truename'] . '的文章 - ' . zmf::config('sitename');
        $this->render('posts', $data);
    }

    public function actionOrders() {
        $sql = "SELECT id,orderId,gid,title,`desc`,faceUrl,classify,totalPrice,num,payAction,orderStatus,paidTime FROM {{orders}}  WHERE uid='{$this->userInfo['id']}' AND status=" . Posts::STATUS_PASSED . " ORDER BY cTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        foreach ($posts as $k => $val) {
            $posts[$k]['faceUrl'] = zmf::getThumbnailUrl($val['faceUrl'], $this->isMobile ? 'c280' : 'c120', 'goods');
            $posts[$k]['typeLabel'] = $val['payAction'] == 'score' ? '积分' : '金币';
        }
        $data = array(
            'posts' => $posts,
            'pages' => $pages,
        );
        $this->selectNav = 'orders';
        $this->pageTitle = '我的订单 - ' . zmf::config('sitename');
        $this->render('orders', $data);
    }

    public function actionProps() {
        $sql = "SELECT p.id,o.title,o.faceUrl,p.classify,p.action,p.from,p.to,p.num FROM {{orders}} o,{{props}} p WHERE p.uid='{$this->userInfo['id']}' AND o.uid='{$this->userInfo['id']}' AND p.gid=o.gid ORDER BY p.updateTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        foreach ($posts as $k => $val) {
            $posts[$k]['faceUrl'] = zmf::getThumbnailUrl($val['faceUrl'], 'a120', 'goods');
        }
        $data = array(
            'posts' => $posts,
            'pages' => $pages,
        );
        $this->selectNav = 'props';
        $this->pageTitle = '我的背包 - ' . zmf::config('sitename');
        $this->render('props', $data);
    }

    public function actionFavorite() {
        $arr = array(
            Books::STATUS_PUBLISHED,
            Books::STATUS_FINISHED
        );
        $sql = "SELECT b.id,b.aid,b.title,b.faceImg,b.desc,b.words,b.cTime,b.score,b.scorer,b.bookStatus FROM {{favorites}} f,{{books}} b WHERE f.uid='{$this->toUserInfo['id']}' AND f.classify='book' AND f.logid=b.id AND b.status=" . Posts::STATUS_PASSED . " AND b.bookStatus IN(" . join(',', $arr) . ") ORDER BY f.cTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        foreach ($posts as $k => $val) {
            $posts[$k]['faceImg'] = zmf::getThumbnailUrl($val['faceImg'], 'w120', 'avatar');
        }
        $this->selectNav = 'favorite';
        $this->pageTitle = $this->toUserInfo['truename'] . '的收藏 - ' . zmf::config('sitename');
        $this->render('favorite', array(
            'posts' => $posts,
            'pages' => $pages,
        ));
    }

    public function actionGallery() {
        $this->checkLogin();
        $from = zmf::val('from', 1);
        if ($from != 'selectImg') {
            $from = '';
        }
        $sql = "SELECT id,filePath,classify,remote FROM {{attachments}} WHERE uid=:uid AND status=" . Posts::STATUS_PASSED . " ORDER BY cTime DESC";
        $posts = Posts::getByPage(array(
                    'sql' => $sql,
                    'page' => $this->page,
                    'pageSize' => $this->pageSize,
                    'bindValues' => array(
                        ':uid' => $this->uid
                    )
        ));
        foreach ($posts as $k => $val) {
            $_original = Attachments::getUrl($val, '');
            $posts[$k]['imgUrl'] = zmf::getThumbnailUrl($_original, 120, $val['classify']);
            $posts[$k]['original'] = $_original;
        }
        if ($this->isAjax) {
            $html = '';
            foreach ($posts as $post) {
                $html.=$this->renderPartial('/posts/_addImg', array('data' => $post, 'from' => $from), true);
            }
            $data = array(
                'html' => $html,
                'loadMore' => (count($post) == $this->pageSize) ? 1 : 0,
            );
            $this->jsonOutPut(1, $data);
        }
        $this->selectNav = 'gallery';
        $this->pageTitle = '相册 - ' . zmf::config('sitename');
        $this->render('gallery', array(
            'posts' => $posts,
            'from' => $from,
        ));
    }

    public function actionUpload() {
        $this->checkLogin();
        $this->selectNav = 'gallery';
        $this->pageTitle = '上传素材 - ' . zmf::config('sitename');
        $this->render('upload', array(
            'model' => $model,
        ));
    }

    public function actionSetting() {
        $this->checkLogin();
        $action = zmf::val('action', 1);
        if (!in_array($action, array('baseInfo', 'passwd', 'skin', 'checkPhone'))) {
            $action = 'baseInfo';
        }
        if ($action == 'checkPhone' && $this->userInfo['phoneChecked']) {
            $this->message(0, '你的号码已验证，不需要重复验证');
        }
        if ($action == 'passwd' && $this->isMobile) {
            $this->layout = 'common';
            $this->referer = Yii::app()->createUrl('user/index', array('id' => $this->uid));
        }
        $model = Users::model()->findByPk($this->uid);
        if (isset($_POST['Users'])) {
            if ($action == 'baseInfo') {
                $truename = zmf::filterInput($_POST['Users']['truename'], 1);
                $content = zmf::filterInput($_POST['Users']['content'], 1);
                $sex = zmf::filterInput($_POST['Users']['sex'], 2);
                if (!$truename) {
                    $field = 'truename';
                    $msg = '用户名不能为空哦~';
                } else {
                    $_uinfo = Users::findByName($truename);
                    if ($_uinfo['id'] != $this->uid && $_uinfo) {
                        $field = 'truename';
                        $msg = '用户名已被占用';
                    } else {
                        $attr = array(
                            'truename' => $truename,
                            'content' => $content,
                            'sex' => $sex,
                        );
                    }
                }
            } elseif ($action == 'passwd') {
                $old = zmf::filterInput($_POST['Users']['password'], 1);
                $new = zmf::filterInput($_POST['Users']['newPassword'], 1);
                if (!$old) {
                    $field = 'password';
                    $msg = '请输入原始密码';
                } elseif (!$new) {
                    $field = 'newPassword';
                    $msg = '请输入新密码';
                } elseif (strlen($new) < 6) {
                    $field = 'newPassword';
                    $msg = '新密码不能短于6位';
                } elseif (md5($old) != $model->password) {
                    $field = 'password';
                    $msg = '原始密码有误';
                } else {
                    $attr = array(
                        'password' => md5($new),
                    );
                }
            } elseif ($action == 'skin') {
                $avatar = zmf::filterInput($_POST['Users']['avatar'], 1);
                $attr = array(
                    'avatar' => $avatar,
                );
            }
            if (!$field && !$msg) {
                $model->updateByPk($this->uid, $attr);
                $this->redirect(array('user/setting'));
            } else {
                $model->addError($field, $msg);
            }
        }
        unset($model->password);
        $this->selectNav = 'setting';
        $this->pageTitle = '设置 - ' . zmf::config('sitename');
        $this->render('setting', array(
            'model' => $model,
            'action' => $action,
        ));
    }

    public function actionJoinGroup() {
        $this->checkLogin();
        $this->layout = 'common';
        if ($this->userInfo['groupid']) {
            $this->message(0, '你已选择过角色，请勿重复操作');
        }
        //status=1为推荐
        $sql = "SELECT id,title,faceImg,`desc`,members,'' AS levels FROM {{group}} WHERE status=1";
        $groups = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($groups as $k => $val) {
            $groups[$k]['id'] = Posts::encode($val['id'], 'group');
            $groups[$k]['faceImg'] = zmf::getThumbnailUrl($val['faceImg'], $this->isMobile ? 'c640' : 'a360', 'group');
            $groups[$k]['levels'] = GroupLevels::getByGroupid($val['id'],5);
        }
        $this->pageTitle = '角色选择 - ' . zmf::config('sitename');
        $data = array(
            'groups' => $groups
        );
        $this->render('joinGroup', $data);
    }

    public function actionNotice() {
        $this->checkLogin();
        $this->selectNav = 'notice';
        $sql = "SELECT id,type,new,authorid,content,cTime,'' AS truename,'' AS avatar FROM {{notification}} WHERE uid='{$this->uid}' ORDER BY cTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        $uids = join(',', array_unique(array_filter(array_keys(CHtml::listData($posts, 'authorid', '')))));
        $authors = array();
        if ($uids != '') {
            $authors = Users::model()->findAll(array(
                'condition' => "id IN({$uids}) AND status=" . Posts::STATUS_PASSED,
                'select' => 'id,truename,avatar'
            ));
            foreach ($posts as $k => $val) {
                foreach ($authors as $val2) {
                    if ($val['authorid'] == $val2['id']) {
                        $posts[$k]['truename'] = $val2['truename'];
                        $posts[$k]['avatar'] = zmf::getThumbnailUrl($val2['avatar'], 'a120', 'avatar');
                    }
                }
            }
        }
        Notification::model()->updateAll(array('new' => 0), 'uid=:uid', array(':uid' => $this->uid));
        $data = array(
            'posts' => $posts,
            'pages' => $pages,
        );
        $this->pageTitle = '提醒 - ' . zmf::config('sitename');
        $this->render('notice', $data);
    }

    public function actionTasks() {
        $this->checkLogin();
        $tasks = Task::getUserTasks($this->userInfo);
        $data = array(
            'tasks' => $tasks
        );
        $this->selectNav = 'tasks';
        $this->pageTitle = '任务 - ' . zmf::config('sitename');
        $this->render('/user/tasks', $data);
    }

}
