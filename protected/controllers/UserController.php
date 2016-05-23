<?php

class UserController extends Q {

    public $toUserInfo;
    public $authorLogin = false;
    public $favorited = false;

    public function init() {
        parent::init();
        $this->layout = 'user';
        $id = zmf::val('id', 2);
        if (!$id && !$this->uid) {
            throw new CHttpException(404, '您所查看的用户不存在，请核实');
        } elseif (!$id || $id == $this->uid) {
            $this->toUserInfo = $this->userInfo;
        } else {
            $this->toUserInfo = Users::getOne($id);
        }
        if ($this->toUserInfo['id'] == $this->uid) {
            $this->authorLogin = Authors::checkLogin($this->userInfo, $this->userInfo['authorId']);
        } else {
            Posts::updateCount($id, 'Users', 1, 'hits');
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
        $this->selectNav = 'index';
        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionFollow() {
        $type = zmf::val('type', 1);
        if ($type == 'fans') {
            $sql = "SELECT u.id,u.truename,u.avatar FROM {{users}} u,{{favorites}} f WHERE f.logid='{$this->toUserInfo['id']}' AND f.classify='user' AND f.uid=u.id ORDER BY f.cTime DESC";
            $label = '关注者';
            $render = 'fans';
        } elseif ($type == 'authors') {
            $sql = "SELECT a.id,a.authorName,a.avatar FROM {{authors}} a,{{favorites}} f WHERE f.uid='{$this->toUserInfo['id']}' AND f.classify='author' AND f.logid=a.id ORDER BY f.cTime DESC";
            $label = '关注作者';
            $render = 'authors';
        } else {
            $sql = "SELECT u.id,u.truename,u.avatar FROM {{users}} u,{{favorites}} f WHERE f.uid='{$this->toUserInfo['id']}' AND f.classify='user' AND f.logid=u.id ORDER BY f.cTime DESC";
            $label = '关注了';
            $render = 'fans';
        }
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        $this->selectNav = 'favorite';
        $data = array(
            'label' => $label,
            'posts' => $posts,
        );
        $this->render($render, $data);
    }

    public function actionAuthor() {
        $this->checkLogin();
        $authorInfo = Authors::findByUid($this->uid);
        if ($authorInfo) {
            throw new CHttpException(403, '您已成为作者，请勿重复操作');
        }
        $this->selectNav = 'setting';
        $model = new Authors;
        $model->uid = $this->uid;
        if (isset($_POST['Authors'])) {
            if ($_POST['Authors']['password']) {
                $_POST['Authors']['hashCode'] = zmf::randMykeys(6);
                $_POST['Authors']['password'] = md5($_POST['Authors']['password'] . $_POST['Authors']['hashCode']);
            }
            $model->attributes = $_POST['Authors'];
            if ($model->save()) {
                Users::model()->updateByPk($this->uid, array('authorId' => $model->id));
                $this->redirect(array('author/view', 'id' => $model->id));
            }
        }
        $this->render('createAuthor', array(
            'model' => $model,
        ));
    }

    public function actionAuthorAuth() {
        $this->checkLogin();
        $authorInfo = Authors::findByUid($this->uid);
        if (!$authorInfo) {
            throw new CHttpException(403, '您尚未成为作者');
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
        $this->render('authorAuth', array(
            'model' => $model,
        ));
    }

    public function actionComment() {
        //获取点评列表
        $sql = "SELECT t.id,t.uid,t.bid,b.title AS bookTitle,t.logid,c.title AS chapterTitle,t.content,t.score,t.favors,t.cTime FROM ({{tips}} AS t RIGHT JOIN {{chapters}} AS c ON t.logid=c.id) LEFT JOIN {{books}} AS b ON t.bid=b.id WHERE t.uid=:uid AND t.classify='chapter' AND t.logid=c.id ORDER BY t.cTime ASC";
        $tips = Posts::getByPage(array(
                    'sql' => $sql,
                    'page' => $this->page,
                    'pageSize' => $this->pageSize,
                    'bindValues' => array(
                        ':uid' => $this->toUserInfo['id']
                    )
        ));
        $this->selectNav = 'comment';
        $this->render('comment', array(
            'tips' => $tips,
        ));
    }

    public function actionFavorite() {
        $sql = "SELECT b.id,b.aid,b.title,b.faceImg,b.desc,b.words,b.cTime,b.score,b.scorer,b.bookStatus FROM {{favorites}} f,{{books}} b WHERE f.uid='{$this->uid}' AND f.classify='book' AND f.logid=b.id AND b.status=" . Posts::STATUS_PASSED . " ORDER BY f.cTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        $this->selectNav = 'favorite';
        $this->render('favorite', array(
            'posts' => $posts,
            'pages' => $pages,
        ));
    }

    public function actionGallery() {
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
        $this->render('gallery', array(
            'posts' => $posts,
            'from' => $from,
        ));
    }

    public function actionUpload() {
        $this->selectNav = 'gallery';
        $this->render('upload', array(
            'model' => $model,
        ));
    }

    public function actionSetting() {
        $model = Users::model()->findByPk($this->uid);
        if (isset($_POST['Users'])) {
            $action = zmf::val('action', 1);
            if (!in_array($action, array('baseInfo', 'passwd', 'skin'))) {
                throw new CHttpException(403, '不被允许的操作');
            }
            if ($action == 'baseInfo') {
                $truename = zmf::filterInput($_POST['Users']['truename'], 1);
                $content = zmf::filterInput($_POST['Users']['content'], 1);
                $sex = zmf::filterInput($_POST['Users']['sex'], 2);
                if (!$truename) {
                    $field = 'truename';
                    $msg = '用户名不能为空哦~';
                } else {
                    $attr = array(
                        'truename' => $truename,
                        'content' => $content,
                        'sex' => $sex,
                    );
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
                $skinUrl = zmf::filterInput($_POST['Users']['skinUrl'], 1);
                $attr = array(
                    'avatar' => $avatar,
                    'skinUrl' => $skinUrl,
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
        $this->render('setting', array(
            'model' => $model,
        ));
    }

    public function actionNotice() {
        $this->checkLogin();
        $this->selectNav = 'notice';
        $sql = "SELECT * FROM {{notification}} WHERE uid='{$this->uid}' ORDER BY cTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $comLists);
        Notification::model()->updateAll(array('new' => 0), 'uid=:uid', array(':uid' => $this->uid));
        $data = array(
            'posts' => $comLists,
            'pages' => $pages,
        );
        $this->pageTitle = $this->userInfo['truename'] . '的提醒 - ' . zmf::config('sitename');
        $this->render('notice', $data);
    }

    public function actionPosts() {
        $sql = "SELECT id,title,status FROM {{posts}} WHERE uid='{$this->uid}' ORDER BY cTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        $data = array(
            'posts' => $posts,
            'pages' => $pages,
        );
        $this->pageTitle = $this->userInfo['truename'] . '的文章 - ' . zmf::config('sitename');
        $this->render('posts', $data);
    }

}
