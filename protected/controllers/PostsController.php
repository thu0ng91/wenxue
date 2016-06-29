<?php

class PostsController extends Q {

    public $favorited = false;

    public function actionIndex() {
        $type = zmf::val('type', 1);
        if (!in_array($type, array('author', 'reader'))) {
            $type = 'author';
        }
        $classify = 0;
        if ($type == 'author') {
            $classify = Posts::CLASSIFY_AUTHOR;
            $label = '作者专区';
            $sql = "SELECT p.id,p.title,p.uid,u.truename AS username,p.cTime,p.comments,p.favorite,p.classify,p.top,p.styleStatus,p.aid FROM {{posts}} p,{{users}} u WHERE p.classify='{$classify}' AND p.status=" . Posts::STATUS_PASSED . " AND p.uid=u.id AND u.status=" . Posts::STATUS_PASSED . " ORDER BY p.top DESC,p.cTime DESC";
        } elseif ($type == 'reader') {
            $classify = Posts::CLASSIFY_READER;
            $label = '读者专区';
            $sql = "SELECT p.id,p.title,p.uid,u.truename AS username,p.cTime,p.comments,p.favorite,p.classify,p.top,p.styleStatus FROM {{posts}} p,{{users}} u WHERE p.classify='{$classify}' AND p.status=" . Posts::STATUS_PASSED . " AND p.uid=u.id AND u.status=" . Posts::STATUS_PASSED . " ORDER BY p.top DESC,p.cTime DESC";
        }
        Posts::getAll(array('sql' => $sql, 'pageSize' => $this->pageSize), $pages, $posts);
        if ($type == 'author' && !empty($posts)) {
            $aids = join(',', array_unique(array_filter(array_keys(CHtml::listData($posts, 'aid', '')))));
            $authors = array();
            if ($aids != '') {
                $authors = Authors::model()->findAll(array(
                    'condition' => "id IN({$aids}) AND status=" . Posts::STATUS_PASSED,
                    'select' => 'id,authorName'
                ));
            }
            if(!empty($authors)){
                foreach ($posts as $k => $val) {
                    if (!$val['aid']) {
                        continue;
                    }
                    foreach ($authors as $author) {
                        if ($author['id'] == $val['aid']) {
                            $posts[$k]['username'] = $author['authorName'];
                        }
                    }
                }
            }
        }
        //获取展示
        $showcases = Showcases::getPagePosts('authorQzone', NUll, false, 'c360');
        $this->selectNav = $type . 'Forum';
        $this->pageTitle = $label . ' - ' . zmf::config('sitename');
        $data = array(
            'posts' => $posts,
            'pages' => $pages,
            'label' => $label,
            'type' => $type,
            'showcases' => $showcases,
        );
        $this->render('index', $data);
    }

    public function actionView() {
        $id = zmf::val('id', 2);
        if (!$id) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $info = $this->loadModel($id);        
        //作者信息
        $authorInfo = array();
        $type = '';
        if ($info['classify'] == Posts::CLASSIFY_AUTHOR && $info['aid']) {
            $author = Authors::getOne($info['aid'],'d120');
            if (!$author) {
                throw new CHttpException(404, '所属作者不存在');
            }
            $authorInfo = array(
                'title' => $author['authorName'],
                'url' => array('author/view', 'id' => $info['aid']),
                'avatar' => $author['avatar'],
            );
            $this->selectNav = 'authorForum';
            $type = 'author';
        }elseif ($info['classify'] == Posts::CLASSIFY_AUTHOR && !$info['aid']) {
            $user = Users::getOne($info['uid']);
            if (!$user) {
                throw new CHttpException(404, '所属用户不存在');
            }
            $authorInfo = array(
                'title' => $user['truename'],
                'url' => array('user/index', 'id' => $info['uid']),
                'avatar' => zmf::getThumbnailUrl($user['avatar'], 'd120', 'user'),
            );
            $this->selectNav = 'authorForum';
            $type = 'author';
        } else {
            $user = Users::getOne($info['uid']);
            if (!$user) {
                throw new CHttpException(404, '所属用户不存在');
            }
            $authorInfo = array(
                'title' => $user['truename'],
                'url' => array('user/index', 'id' => $info['uid']),
                'avatar' => zmf::getThumbnailUrl($user['avatar'], 'd120', 'user'),
            );
            $this->selectNav = 'readerForum';
            $type = 'reader';
        }
        if (!zmf::actionLimit('visit-Posts', $id, 5, 60)) {
            Posts::updateCount($id, 'Posts', 1, 'hits');
        }
        $size = 'w600';
        if ($this->isMobile) {
            $size = 'w640';
        }
        $info['content'] = zmf::text(array(), $info['content'], true, $size);
        $comments = Comments::getCommentsByPage($id, 'posts', 1, $this->pageSize);
        $tags = Tags::getByIds($info['tagids']);
        $relatePosts = Posts::getRelations($id, 5);
        $data = array(
            'info' => $info,
            'authorInfo' => $authorInfo,
            'comments' => $comments,
            'tags' => $tags,
            'relatePosts' => $relatePosts,
            'type' => $type,
            'loadMore' => count($comments) == $this->pageSize ? 1 : 0,
        );
        $this->favorited = Favorites::checkFavored($id, 'post');
        $this->pageTitle = $info['title'];
        $this->render('view', $data);
    }

    public function actionCreate($id = '') {
        $this->onlyOnPc();
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
        $this->checkUserStatus();
        if ($id) {
            $id = zmf::myint($id);
            $model = $this->loadModel($id);
            if ($model['uid'] != $this->uid) {
                throw new CHttpException(403, '你无权本操作');
            }
            $isNew = false;
            $type = Posts::exType($model->classify);
            if ($model->classify == Posts::CLASSIFY_AUTHOR && $this->userInfo['authorId'] && !$model->aid) {
                $model->aid = $this->userInfo['authorId'];
            }
            $model->content=zmf::text(array('action'=>'edit','encode'=>'yes'),$model->content,false,640);
        } else {
            $type = zmf::val('type', 1);
            if (!in_array($type, array('author', 'reader'))) {
                throw new CHttpException(404, '你所选择的版块不存在');
            }
            $model = new Posts;
            if ($type == 'author' && !$this->userInfo['authorId'] && !$this->userInfo['isAdmin']) {
                throw new CHttpException(404, '你不能在该版块发帖');
            } elseif ($type == 'author' && $this->userInfo['authorId']) {
                $model->aid = $this->userInfo['authorId'];
            }
            $model->classify = Posts::exType($type);
            $model->open = Posts::STATUS_OPEN; //是否允许评论
            $isNew = true;
        }
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'posts-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['Posts'])) {
            //处理文本
            $filterTitle = Posts::handleContent($_POST['Posts']['title'], FALSE);
            $filterContent = Posts::handleContent($_POST['Posts']['content']);
            $attr = array(
                'title' => $filterTitle['content'],
                'content' => strip_tags($filterContent['content'],'<p><strong><em><u>'),
            );
            $attkeys=array();
            if (!empty($filterContent['attachids'])) {
                $attkeys = array_filter(array_unique($filterContent['attachids']));
                if (!empty($attkeys)) {
                    $attr['faceimg'] = $attkeys[0]; //默认将文章中的第一张图作为封面图
                }
            } else {
                $attr['faceimg'] = ''; //否则将封面图置为空(有可能编辑后没有图片了)
            }
            $tagids = array_unique(array_filter($_POST['tags']));
            //如果标题包含敏感词则直接标记为未通过
            $attr['status'] = $filterTitle['status'] == Posts::STATUS_PASSED ? $filterContent['status'] : $filterTitle['status'];
            $attr['open'] = ($_POST['Posts']['open'] == Posts::STATUS_OPEN) ? 1 : 0;
            $model->attributes = $attr;
            if ($model->save()) {
                //将上传的图片置为通过
                Attachments::model()->updateAll(array('status' => Posts::STATUS_DELED), 'logid=:logid AND classify=:classify', array(':logid' => $model->id, ':classify' => 'posts'));
                if (!empty($attkeys)) {
                    $attstr = join(',', $attkeys);
                    if ($attstr != '') {
                        Attachments::model()->updateAll(array('status' => Posts::STATUS_PASSED, 'logid' => $model->id), 'id IN(' . $attstr . ')');
                    }
                }
                //处理标签
                $intoTags = array();
                if (!empty($tagids)) {
                    foreach ($tagids as $tagid) {
                        $_info = Tags::addRelation($tagid, $model->id, $type);
                        if ($_info) {
                            $intoTags[] = $tagid;
                        }
                    }
                }
                if (!$isNew || !empty($intoTags)) {
                    Posts::model()->updateByPk($model->id, array('tagids' => join(',', $intoTags)));
                }
                //记录用户操作
                $jsonData = CJSON::encode(array(
                            'id' => $model->id,
                            'title' => $model->title,
                            'faceimg' => $model->faceimg
                ));
                UserAction::recordAction($model->id, 'post', $jsonData);
                $this->redirect(array('posts/view', 'id' => $model->id));
            }
        }
        $tags = Tags::getClassifyTags($type);
        $this->selectNav = 'contribution';
        if ($model->classify == Posts::CLASSIFY_AUTHOR) {
            $this->pageTitle = '【作者专区】发布文章 - ' . zmf::config('sitename');
            $this->selectNav = 'authorForum';
        } else {
            $this->pageTitle = '【读者专区】发布文章 - ' . zmf::config('sitename');
            $this->selectNav = 'readerForum';
        }
        $this->render('create', array(
            'model' => $model,
            'tags' => $tags,
        ));
    }

    public function loadModel($id) {
        $model = Posts::model()->findByPk($id);
        if ($model === null || $model->status != Posts::STATUS_PASSED)
            throw new CHttpException(404, '该页面不存在或已被删除！');
        return $model;
    }

}
