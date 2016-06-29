<?php

class AuthorController extends Q {

    public $authorInfo = array();
    public $adminLogin = false;
    public $favorited = false;

    public function init() {
        parent::init();
        $this->layout = 'author';
        $id = zmf::val('id', 2);
        if (!$id && !$this->userInfo['authorId']) {
            throw new CHttpException(404, '你所查看的作者不存在，请核实');
        } elseif (!$id) {
            $id = $this->userInfo['authorId'];
        }
        $this->authorInfo = Authors::getOne($id, 'd120');
        if (!$this->authorInfo) {
            throw new CHttpException(404, '你所查看的作者不存在，请核实');
        } else {
            $this->authorInfo['skinUrl'] = zmf::getThumbnailUrl($this->authorInfo['skinUrl'], 'c960', 'author');
        }
        if ($this->uid) {
            $this->adminLogin = Authors::checkLogin($this->userInfo, $id);
            $this->favorited = Favorites::checkFavored($id, 'author');
        }
        $this->pageTitle = $this->authorInfo['authorName'] . ' - ' . zmf::config('sitename');
        $this->keywords="{$this->authorInfo['authorName']},{$this->authorInfo['authorName']}小说,{$this->authorInfo['authorName']}全部小说,{$this->authorInfo['authorName']}作品,{$this->authorInfo['authorName']}最新作品,{$this->authorInfo['authorName']}新书,{$this->authorInfo['authorName']}资料及介绍";
        $this->pageDescription=  "{$this->authorInfo['authorName']}是".zmf::config('sitename')."网作家。这里你可以阅读{$this->authorInfo['authorName']}全部小说及{$this->authorInfo['authorName']}最新作品集，还可以和{$this->authorInfo['authorName']}互动，更多{$this->authorInfo['authorName']}新书及动态请关注".zmf::config('sitename')."网。";
    }

    private function checkAuthorLogin() {
        if (!$this->adminLogin) {
            if ($this->uid && $this->userInfo['authorId'] > 0) {
                $this->redirect(array('user/authorAuth'));
            } else {
                throw new CHttpException(404, '你无权该操作，请核实');
            }
        }
    }

    public function actionView() {
        $posts = Books::model()->findAll(array(
            'condition' => 'aid=:aid' . (!$this->adminLogin ? " AND bookStatus='" . Books::STATUS_PUBLISHED . "'" : ""),
            'select' => 'id,colid,title,faceImg,`desc`,words,cTime,score,scorer,bookStatus',
            'params' => array(
                ':aid' => $this->authorInfo['id']
            )
        ));
        foreach ($posts as $k => $val) {
            $posts[$k]['faceImg'] = zmf::getThumbnailUrl($val['faceImg'], 'w120', 'book');
        }
        if (!zmf::actionLimit('visit-Author', $this->authorInfo['id'], 5, 60)) {
            Posts::updateCount($this->authorInfo['id'], 'Authors', 1, 'hits');
        }
        //更新作者数据,10分钟更新一次
        $upAuthorInfo= zmf::getFCache('stat-Authors-'.$this->authorInfo['id']);
        if(!$upAuthorInfo){
            Authors::updateStatInfo($this->authorInfo);
            zmf::setFCache('stat-Authors-'.$this->authorInfo['id'], 1, 600);
        }
        $this->selectNav = 'index';
        $this->pageTitle = $this->authorInfo['authorName'] . ' - ' . zmf::config('sitename');        
        $data = array(
            'posts' => $posts
        );
        $this->render('view', $data);
    }

    public function actionFans() {
        $sql = "SELECT u.id,u.truename,u.avatar FROM {{users}} u,{{favorites}} f WHERE f.logid='{$this->authorInfo['id']}' AND f.classify='author' AND f.uid=u.id ORDER BY f.cTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        foreach ($posts as $k => $val) {
            $posts[$k]['avatar'] = zmf::getThumbnailUrl($val['avatar'], 'a120', 'avatar');
        }
        $this->selectNav = 'fans';
        $this->pageTitle = $this->authorInfo['authorName'] . '的粉丝 - ' . zmf::config('sitename');
        $data = array(
            'posts' => $posts
        );
        $this->render('fans', $data);
    }

    public function actionDrafts() {
        $this->checkAuthorLogin();
        $sql = "SELECT d.id,d.bid,d.title,d.cTime,b.title AS bookTitle,d.uuid,d.content FROM {{drafts}} d,{{books}} b WHERE d.uid='{$this->uid}' AND d.aid='{$this->userInfo['authorId']}' AND d.bid=b.id ORDER BY d.cTime DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        $this->selectNav = 'drafts';
        $data = array(
            'posts' => $posts,
            'pages' => $pages,
        );
        $this->render('drafts', $data);
    }

    public function actionCreateBook($bid = '') {
        $this->checkAuthorLogin();
        $this->checkUserStatus();
        if ($bid) {
            $model = Books::getOne($bid, '');
            if (!$model || $model['status'] != Posts::STATUS_PASSED) {
                throw new CHttpException(404, '你所编辑的小说不存在，请核实');
            } elseif ($model['uid'] != $this->uid || $model['aid'] != $this->userInfo['authorId']) {
                throw new CHttpException(403, '你无权改操作');
            }
        } else {
            $model = new Books;
            $model->uid = $this->uid;
            $model->aid = $this->userInfo['authorId'];
        }
        if (isset($_POST['Books'])) {
            $filterTitle = Posts::handleContent($_POST['Books']['title'], FALSE);
            $filterDesc = Posts::handleContent($_POST['Books']['desc'], FALSE);
            $filterContent = Posts::handleContent($_POST['Books']['content'], FALSE);
            $faceImg = zmf::filterInput($_POST['Books']['faceImg'], 1);
            $colid = zmf::filterInput($_POST['Books']['colid'], 2);
            $iAgree = zmf::filterInput($_POST['Books']['iAgree'], 1);
            $shoufa = zmf::filterInput($_POST['Books']['shoufa'], 2);
            $attr = array(
                'title' => $filterTitle['content'],
                'desc' => $filterDesc['content'],
                'content' => $filterContent['content'],
                'faceImg' => $faceImg != '' ? $faceImg : '',
                'colid' => $colid > 0 ? $colid : '',
                'shoufa' => ($shoufa > 1 && $shoufa < 0) ? 0 : 1,
                'iAgree' => $iAgree,
            );
            $model->attributes = $attr;
            if ($iAgree != '我同意') {
                $model->addError('iAgree', '请同意本站协议');
            } elseif ($filterTitle['status'] != Posts::STATUS_PASSED || $filterDesc['status'] != Posts::STATUS_PASSED || $filterContent['status'] != Posts::STATUS_PASSED) {
                $model->addError('content', '文本包含敏感词，请修改');
            } else {
                if ($model->save()) {
                    $this->redirect(array('author/book', 'bid' => $model->id));
                }
            }
        }
        $this->selectNav = 'createBook';
        $this->pageTitle =  '发布作品 - ' . zmf::config('sitename');
        $this->render('createBook', array(
            'model' => $model,
        ));
    }

    public function actionUpdateBook($bid) {
        $bid = zmf::val('bid', 2);
        $this->actionCreateBook($bid);
    }

    public function actionBook($bid) {
        $this->checkAuthorLogin();
        $bid = zmf::val('bid', 2);
        $info = Books::getOne($bid);
        if (!$info) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $chapters = Chapters::getByBook($bid, $this->adminLogin);
        $this->pageTitle = $info['title'] . '的章节 - ' . zmf::config('sitename');
        $data = array(
            'info' => $info,
            'chapters' => $chapters,
        );
        $this->render('book', $data);
    }

    public function actionAddChapter($cid = '') {
        $this->checkAuthorLogin();
        $this->checkUserStatus();
        $this->layout = 'common';
        $draft = zmf::val('draft', 1);
        if ($cid) {
            $model = Chapters::getOne($cid);
            if (!$model) {
                throw new CHttpException(404, '你所编辑的内容不存在');
            } elseif ($model['uid'] != $this->uid) {
                throw new CHttpException(403, '你无权本操作');
            }
            $bid = $model->bid;
        } else {
            $bid = zmf::val('bid', 2);
            if (!$bid) {
                throw new CHttpException(404, '请选择小说');
            }
            $model = new Chapters;
        }
        $bookInfo = Books::getOne($bid);
        if (!$bookInfo) {
            throw new CHttpException(404, '你所操作的小说不存在');
        } elseif ($bookInfo['uid'] != $this->uid || $bookInfo['aid'] != $this->userInfo['authorId']) {
            throw new CHttpException(403, '你无权本操作');
        } elseif (!$bookInfo['iAgree']) {
            $this->message(0, '请先同意本站协议', Yii::app()->createUrl('author/updateBook', array('bid' => $bid)));
        }
        $model->bid = $bid;
        $model->uid = $bookInfo['uid'];
        $model->aid = $bookInfo['aid'];
        if (isset($_POST['Chapters'])) {
            $filterTitle = Posts::handleContent($_POST['Chapters']['title'], FALSE);
            $filterContent = Posts::handleContent($_POST['Chapters']['content'],true,'<p>');
            $filterPostscript = Posts::handleContent($_POST['Chapters']['postscript'], FALSE);
            $psPosition = zmf::filterInput($_POST['Chapters']['psPosition'], 2);
            $chapterNum = zmf::filterInput($_POST['Chapters']['chapterNum'], 2);
            $status = $cid > 0 ? $model->status : Posts::STATUS_NOTPASSED;
            if ($filterTitle['status'] != Posts::STATUS_PASSED || $filterPostscript['status'] != Posts::STATUS_PASSED || $filterContent['status'] != Posts::STATUS_PASSED) {
                $status = Posts::STATUS_STAYCHECK;
            }
            $attr = array(
                'title' => $filterTitle['content'],
                'content' => $filterContent['content'],
                'postscript' => $filterPostscript['content'],
                'psPosition' => ($psPosition < 0 || $psPosition > 1) ? 0 : $psPosition,
                'status' => $status,
                'chapterNum' => $chapterNum,
            );
            $model->attributes = $attr;
            if ($model->save()) {
                Books::updateBookStatInfo($model->bid);
                $this->redirect(array('author/book', 'bid' => $model->bid));
            }
        }
        if ($draft) {
            $attr = array(
                'uid' => $this->uid,
                'aid' => $this->userInfo['authorId'],
                'bid' => $model->bid,
                'uuid' => $draft,
            );
            $draftInfo = Drafts::model()->findByAttributes($attr);
            if ($draftInfo) {
                $model->title = $draftInfo['title'];
                $model->content = $draftInfo['content'];
                $hashUuid = $draft;
            } else {
                $hashUuid = zmf::randMykeys(8);
            }
        } else {
            $hashUuid = zmf::randMykeys(8);
        }
        $this->pageTitle = '写文章 - ' . zmf::config('sitename');
        $this->render('addChapter', array(
            'model' => $model,
            'hashUuid' => $hashUuid,
        ));
    }

    public function actionSetting() {
        $this->checkAuthorLogin();
        $this->checkUserStatus();
        $type = zmf::val('type', 1);
        if (!in_array($type, array('info', 'skin', 'passwd','avatar'))) {
            throw new CHttpException(403, '不允许的分类');
        }
        $model = Authors::model()->findByPk($this->userInfo['authorId']);
        if (isset($_POST['Authors'])) {
            if ($type == 'info') {
                $content = zmf::filterInput($_POST['Authors']['content'], 1);
                $attr = array(
                    'content' => $content
                );
            } elseif ($type == 'passwd') {
                $old = zmf::filterInput($_POST['Authors']['password'], 1);
                $new = zmf::filterInput($_POST['Authors']['newPassword'], 1);
                if (!$old) {
                    $field = 'password';
                    $msg = '请输入原始密码';
                } elseif (!$new) {
                    $field = 'newPassword';
                    $msg = '请输入新密码';
                } elseif (strlen($new) < 6) {
                    $field = 'newPassword';
                    $msg = '新密码不能短于6位';
                } elseif (md5($old . $model->hashCode) != $model->password) {
                    $field = 'password';
                    $msg = '原始密码有误';
                } else {
                    $attr = array(
                        'password' => md5($new . $model->hashCode),
                    );
                }
            } elseif ($type == 'skin') {
                $skinUrl = zmf::filterInput($_POST['Authors']['skinUrl'], 1);
                if (!Attachments::checkUrlDomain($skinUrl)) {
                    $field = 'skinUrl';
                    $msg = '请使用站内图片';
                } else {
                    $attr = array(
                        'skinUrl' => $skinUrl,
                    );
                }
            } elseif ($type == 'avatar') {
                $avatar = zmf::filterInput($_POST['Authors']['avatar'], 1);
                if (!Attachments::checkUrlDomain($avatar)) {
                    $field = 'avatar';
                    $msg = '请使用站内图片';
                } else {
                    $attr = array(
                        'avatar' => $avatar,
                    );
                }
            }
            if (!$field && !$msg) {
                $model->updateByPk($this->userInfo['authorId'], $attr);
                Yii::app()->user->setFlash('updateAuthorInfoSuccess', "修改已更新");
                $this->redirect(array('author/setting', 'type' => $type));
            } else {
                $model->addError($field, $msg);
            }
        }
        unset($model->password);
        unset($model->hashCode);
        $this->pageTitle = '编辑资料';
        $this->selectNav = 'update' . $type;
        $this->render('setting', array(
            'model' => $model,
            'type' => $type,
        ));
    }

    public function actionLogout() {
        if (!$this->userInfo['authorId']) {
            throw new CHttpException(403, '操作有误');
        } elseif (!$this->adminLogin) {
            throw new CHttpException(403, '你已退出，请勿重复操作');
        }
        $code = 'authorAuth-' . $this->uid;
        unset(Yii::app()->session[$code]);
        $this->redirect(array('user/index', 'id' => $this->uid));
    }

}
