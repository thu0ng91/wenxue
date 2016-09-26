<?php

class PostsController extends Q {

    public $favorited = false;

    public function actionTypes() {
        //更新版块帖子数
        PostForums::updatePostsStat();
        $items = PostForums::model()->findAll();
        foreach ($items as $k=>$val){
            $items[$k]['faceImg']=  zmf::getThumbnailUrl($val['faceImg'],'a120','faceImg');
        }
        $favorites=  $this->uid ? array_keys(CHtml::listData($this->userInfo['favoriteForums'], 'id', 'title')) : array();
        $this->pageTitle = '关注圈子 - ' . zmf::config('sitename');
        $data = array(
            'forums' => $items,
            'favorites' => $favorites,
        );
        $this->render('forums', $data);
    }

    public function actionIndex() {
        $type = zmf::val('type', 1);
        if ($type) {
            $this->redirect(array('posts/types'));
        }
        $forumId = zmf::val('forum', 2);
        if (!$forumId) {
            $this->redirect(array('posts/types'));
        }
        $forumInfo = PostForums::getOne($forumId);
        if (!$forumInfo) {
            $this->message(0, '你所查看的版块不存在');
        }

        $sql = "SELECT p.id,p.title,p.faceImg,p.uid,u.truename AS username,p.cTime,p.comments,p.favorites,p.top,p.digest,p.styleStatus,p.aid,p.fid,'' AS forumTitle FROM {{post_threads}} p,{{users}} u WHERE p.status=" . Posts::STATUS_PASSED . " AND p.uid=u.id AND u.status=" . Posts::STATUS_PASSED . " ORDER BY p.top DESC,p.cTime DESC";

        Posts::getAll(array('sql' => $sql, 'pageSize' => $this->pageSize), $pages, $posts);
        foreach ($posts as $k => $val) {
            $posts[$k]['faceImg'] = zmf::getThumbnailUrl($val['faceImg'], $this->isMobile ? 'c280' : 'c120', 'posts');
        }
        if (!empty($posts)) {
            $aids = join(',', array_unique(array_filter(array_keys(CHtml::listData($posts, 'aid', '')))));
            $authors = array();
            if ($aids != '') {
                $authors = Authors::model()->findAll(array(
                    'condition' => "id IN({$aids}) AND status=" . Posts::STATUS_PASSED,
                    'select' => 'id,authorName'
                ));
            }
            if (!empty($authors)) {
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
        //所有版块
        $forums = PostForums::model()->findAll();
        $this->selectNav = $type . 'Forum';
        $this->showLeftBtn = false;
        $this->pageTitle = $forumInfo['title'] . ' - ' . zmf::config('sitename');
        $this->mobileTitle = $forumInfo['title'];
        $data = array(
            'posts' => $posts,
            'pages' => $pages,
            'forumInfo' => $forumInfo,
            'showcases' => $showcases,
            'forums' => $forums,
        );
        $this->render('index', $data);
    }

    public function actionView() {
        $id = zmf::val('id', 2);
        if (!$id) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $info = $this->loadModel($id);
        //版块信息
        $forumInfo = PostForums::getOne($info['fid']);
        if (!$forumInfo) {
            $this->message(0, '你所查看的版块不存在');
        }

        //作者信息
        $authorInfo = array();
        //$info['aid']
        if (false) {
            $author = Authors::getOne($info['aid'], 'd120');
            if (!$author) {
                throw new CHttpException(404, '所属作者不存在');
            }
            $authorInfo = array(
                'title' => $author['authorName'],
                'url' => array('author/view', 'id' => $info['aid']),
                'avatar' => $author['avatar'],
            );
            $this->selectNav = 'authorForum';
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
        }
        if (!zmf::actionLimit('visit-Posts', $id, 5, 60)) {
            Posts::updateCount($id, 'Posts', 1, 'hits');
        }

        //获取回帖列表
        $sql = "SELECT p.id,p.uid,u.truename AS username,u.avatar,u.level,u.levelTitle,u.levelIcon,p.aid,p.cTime,p.updateTime,p.open,p.comments,p.favors,p.content,'' AS props FROM {{post_posts}} p,{{users}} u WHERE p.tid='{$id}' AND p.status=" . Posts::STATUS_PASSED . " AND p.uid=u.id AND u.status=" . Posts::STATUS_PASSED . " ORDER BY p.isFirst DESC,p.cTime ASC";
        Posts::getAll(array('sql' => $sql, 'pageSize' => $this->pageSize), $pages, $posts);
        $size = 'w600';
        if ($this->isMobile) {
            $size = 'w650';
            $this->layout = 'post';
        }
        foreach ($posts as $k => $val) {
            $posts[$k]['avatar'] = zmf::getThumbnailUrl($val['avatar'], 'a120', 'avatar');
            $posts[$k]['content'] = zmf::text(array(), $val['content'], true, $size);
            $posts[$k]['props'] = Props::getClassifyProps('postPosts', $val['id']);
        }

        //$comments = Comments::getCommentsByPage($id, $this->uid, 'posts', 1, $this->pageSize, "c.id,c.uid,u.truename,u.avatar,c.aid,c.logid,c.tocommentid,c.content,c.cTime,c.status,c.favors");
        //$tags = Tags::getByIds($info['tagids']);
        //$relatePosts = Posts::getRelations($id, 5);
        //$topsPosts = Posts::getTops($info['id'], $info['classify'], 10);
        $data = array(
            'info' => $info,
            'forumInfo' => $forumInfo,
            'posts' => $posts,
            'pages' => $pages,
            'authorInfo' => $authorInfo,
            'comments' => $comments,
            'tags' => $tags,
            'relatePosts' => $relatePosts,
            'topsPosts' => $topsPosts,
        );
        $this->favorited = Favorites::checkFavored($id, 'thread');
        $this->pageTitle = $info['title'] . ' - ' . zmf::config('sitename');
        $this->mobileTitle = '帖子详情';
        $this->render('view', $data);
    }

    public function actionCreate() {
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
        $this->checkUserStatus();
        $forumId = zmf::val('forum', 2);
        if (!$forumId) {
            $this->redirect(array('posts/types'));
        }
        $forumInfo = PostForums::getOne($forumId);
        if (!$forumInfo) {
            $this->message(0, '你所查看的版块不存在');
        }
        //获取用户组的权限
        $powerInfo = GroupPowers::checkPower($this->userInfo, 'addPost');
        if (!$powerInfo['status']) {
            $this->message($powerInfo['status'], $powerInfo['msg']);
        }
        $model = new PostThreads;
        $model->fid = $forumId;
        $isNew = true;
        if (isset($_POST['PostThreads'])) {
            //处理文本
            $filterTitle = Posts::handleContent($_POST['PostThreads']['title'], FALSE);
            $filterContent = Posts::handleContent($_POST['PostThreads']['content']);
            $content = strip_tags($filterContent['content'], '<p><br><strong><em><u>');
            $attr = array(
                'title' => $filterTitle['content'],
                'fid' => $forumInfo['id'],
            );
            $attkeys = array();
            if (!empty($filterContent['attachids'])) {
                $attkeys = array_filter(array_unique($filterContent['attachids']));
                if (!empty($attkeys)) {
                    $attr['faceImg'] = $attkeys[0]; //默认将文章中的第一张图作为封面图
                }
            } else {
                $attr['faceImg'] = ''; //否则将封面图置为空(有可能编辑后没有图片了)
            }
            $tagids = array_unique(array_filter($_POST['tags']));
            //如果标题包含敏感词则直接标记为未通过
            $attr['status'] = $filterTitle['status'] == Posts::STATUS_PASSED ? $filterContent['status'] : $filterTitle['status'];
            $attr['open'] = ($_POST['PostThreads']['open'] == Posts::STATUS_OPEN) ? 1 : 0;
            $attr['platform'] = $this->isMobile ? Posts::PLATFORM_MOBILE : Posts::PLATFORM_WEB;
            $model->attributes = $attr;
            if ($model->save()) {
                //保存第一楼内容
                $postAttr = array(
                    'tid' => $model->id,
                    'content' => $content,
                    'isFirst' => 1, //首层
                );
                $modelPost = new PostPosts;
                $modelPost->attributes = $postAttr;
                if (!$modelPost->save()) {
                    //如果第一楼没写入成功，则删除原来的帖子
                    $model->updateByPk($model->id, array('status' => Posts::STATUS_DELED));
                    $url = Yii::app()->createUrl('posts/create', array('forum' => $forumId));
                    $this->message(0, '发布帖子失败，请重试');
                }
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
                        $_info = Tags::addRelation($tagid, $model->id, 'thread');
                        if ($_info) {
                            $intoTags[] = $tagid;
                        }
                    }
                }
                if (!$isNew || !empty($intoTags)) {
                    Posts::model()->updateByPk($model->id, array('tagids' => join(',', $intoTags)));
                }
                //记录用户操作及积分
                $jsonData = CJSON::encode(array(
                            'id' => $model->id,
                            'title' => $model->title,
                            'faceimg' => $model->faceImg
                ));
                $attr = array(
                    'uid' => $this->uid,
                    'logid' => $model->id,
                    'classify' => 'post',
                    'data' => $jsonData,
                    'action' => 'addPost',
                    'score' => $powerInfo['msg']['score'],
                    'exp' => $powerInfo['msg']['exp'],
                    'display' => 1,
                );
                if (UserAction::simpleRecord($attr)) {
                    //判断本操作是否同属任务
                    $ckTaskStatus = Task::addTaskLog($this->userInfo, 'addPost');
                }
                $this->redirect(array('posts/view', 'id' => $model->id));
            }
        }
        $tags = Tags::getClassifyTags('thread');
        $this->pageTitle = '【' . $forumInfo['title'] . '】发布文章 - ' . zmf::config('sitename');
        $this->selectNav = 'authorForum';
        $this->mobileTitle = '发布帖子';
        $this->render('create', array(
            'model' => $model,
            'tags' => $tags,
        ));
    }

    public function actionReply() {
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
        $this->checkUserStatus();
        $tid = zmf::val('tid', 2);
        $pid = zmf::val('pid', 2);
        if (!$tid) {
            throw new CHttpException(404, '该页面不存在或已被删除！');
        }
        //获取用户组的权限
        $powerInfo = GroupPowers::checkPower($this->userInfo, 'addPostReply');
        if (!$powerInfo['status']) {
            $this->message($powerInfo['status'], $powerInfo['msg']);
        }
        $threadInfo = $this->loadModel($tid);
        if ($pid) {
            $model = PostPosts::model()->findByPk($pid);
            if (!$model || $model->uid != $this->uid) {
                throw new CHttpException(404, '你编辑的页面不存在或已被删除！');
            }
        } else {
            $model = new PostPosts;
            $model->tid = $tid;
        }
        if (isset($_POST['PostPosts'])) {
            //处理文本
            $filterContent = Posts::handleContent($_POST['PostPosts']['content']);
            $content = strip_tags($filterContent['content'], '<p><br><strong><em><u>');
            $attr = array(
                'content' => $content,
                'isFirst' => 0,
            );
            //如果标题包含敏感词则直接标记为未通过
            $attr['status'] = $filterContent['status'];
            $attr['open'] = ($_POST['PostPosts']['open'] == Posts::STATUS_OPEN) ? 1 : 0;
            $attr['platform'] = $this->isMobile ? Posts::PLATFORM_MOBILE : Posts::PLATFORM_WEB;
            $attkeys = array();
            if (!empty($filterContent['attachids'])) {
                $attkeys = array_filter(array_unique($filterContent['attachids']));
            }
            $model->attributes = $attr;
            if ($model->save()) {
                //将上传的图片置为通过
                Attachments::model()->updateAll(array('status' => Posts::STATUS_DELED), 'logid=:logid AND classify=:classify', array(':logid' => $model->id, ':classify' => 'threads'));
                if (!empty($attkeys)) {
                    $attstr = join(',', $attkeys);
                    if ($attstr != '') {
                        Attachments::model()->updateAll(array('status' => Posts::STATUS_PASSED, 'logid' => $model->id), 'id IN(' . $attstr . ')');
                    }
                }
                $this->redirect(array('posts/view', 'id' => $model->tid));
            }
        }
        $this->pageTitle = '回帖 - ' . zmf::config('sitename');
        $this->render('reply', array(
            'model' => $model,
            'threadInfo' => $threadInfo,
        ));
    }

    public function loadModel($id) {
        $model = PostThreads::model()->findByPk($id);
        if ($model === null || $model->status != Posts::STATUS_PASSED)
            throw new CHttpException(404, '该页面不存在或已被删除！');
        return $model;
    }

}
