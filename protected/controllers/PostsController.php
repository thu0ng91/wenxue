<?php

class PostsController extends Q {

    public $favorited = false;

    public function actionTypes() {
        //更新版块帖子数
        PostForums::updatePostsStat();
        $items = PostForums::model()->findAll(array(
            'order' => 'id ASC'
        ));
        foreach ($items as $k => $val) {
            $items[$k]['faceImg'] = zmf::getThumbnailUrl($val['faceImg'], 'a120', 'faceImg');
        }
        //最新帖子
        $posts=array();
        if(!$this->isMobile){
            $now=  zmf::now()-86400*7;
            $posts = PostThreads::model()->findAll(array(
                'condition' => 'cTime>'.$now,
                'select' => 'id,title',
                'order' => 'id DESC',
                'limit' => 10
            ));
        }
        $this->showLeftBtn = false;
        $this->selectNav = 'forum';
        $favorites = $this->uid ? array_keys(CHtml::listData($this->userInfo['favoriteForums'], 'id', 'title')) : array();
        $this->pageTitle = '关注圈子 - ' . zmf::config('sitename');
        $this->mobileTitle = '关注圈子';
        $this->keywords= $this->pageDescription='';
        $data = array(
            'forums' => $items,
            'favorites' => $favorites,
            'posts' => $posts,
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
        $filter = zmf::val('filter', 1);
        $order = zmf::val('order', 1);
        if (!in_array($filter, array('digest'))) {
            $filter = 'zmf';
        }
        if (!in_array($order, array('hits', 'props'))) {
            $order = 'zmf';
        }
        $forumInfo = PostForums::getOne($forumId);
        if (!$forumInfo) {
            $this->message(0, '你所查看的版块不存在');
        }
        $forumInfo['faceImg'] = zmf::getThumbnailUrl($forumInfo['faceImg'], 'a120', 'faceImg');
        //拼装where条件
        $where = "p.fid={$forumId}";
        if ($filter == 'digest') {
            $where.=" AND p.digest=1";
        }
        $where.=" AND p.status=" . Posts::STATUS_PASSED . " AND p.uid=u.id AND u.status=" . Posts::STATUS_PASSED;
        //按需排序
        $orderBy = 'cTime';
        if ($order == 'hits') {
            $orderBy = 'hits';
        } elseif ($order == 'props') {
            $orderBy = 'props';
        }
        //SQL
        $sql = "SELECT p.id,p.title,p.faceImg,p.uid,u.truename AS username,p.cTime,p.posts,p.hits,p.top,p.digest,p.styleStatus,p.aid,p.fid,'' AS forumTitle FROM {{post_threads}} p,{{users}} u WHERE {$where} ORDER BY p.top DESC,p.{$orderBy} DESC";
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
        //本板块活跃用户
        $topUsers = PostForums::getActivityUsers($forumId, 12);
        foreach ($topUsers as $k => $v) {
            $topUsers[$k]['avatar'] = zmf::getThumbnailUrl($v['avatar'], 'a36', 'avatar');
        }
        //判断是否收藏
        $favorited = false;
        if ($this->uid) {
            $favorited = Favorites::checkFavored($forumId, 'forum');
        }

        $this->selectNav = 'forum';
        $this->showLeftBtn = true;
        $this->returnUrl = Yii::app()->createUrl('posts/types');
        $this->pageTitle = $forumInfo['title'] . ' - ' . zmf::config('sitename');
        $this->keywords=$forumInfo['keywords'];
        $this->pageDescription=$forumInfo['description'];
        $this->mobileTitle = $forumInfo['title'];
        $data = array(
            'posts' => $posts,
            'pages' => $pages,
            'forumInfo' => $forumInfo,
            'showcases' => $showcases,
            'forums' => $forums,
            'filter' => $filter,
            'order' => $order,
            'topUsers' => $topUsers,
            'favorited' => $favorited,
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
        $authorInfo = array();
        if ($info['aid'] > 0) {
            //作者信息
            $_info = Authors::getOne($info['aid'], 'a120');
            if (!$_info) {
                throw new CHttpException(404, '所属用户不存在');
            }
            $authorInfo = array(
                'isAuthor' => true,
                'avatar' => $_info['avatar'],
                'title' => $_info['authorName'],
                'linkArr' => array('author/view', 'id' => $_info['id']),
                'linkUrl' => Yii::app()->createUrl('author/view', array('id' => $_info['id'])),
                'favors' => $_info['favors'],
                'posts' => $_info['posts'],
                'score' => $_info['score'],
            );
        } else {
            //用户信息
            $_info = Users::getOne($info['uid']);
            if (!$_info) {
                throw new CHttpException(404, '所属用户不存在');
            }
            $authorInfo = array(
                'isAuthor' => false,
                'avatar' => zmf::getThumbnailUrl($_info['avatar'], 'a120', 'user'),
                'title' => $_info['truename'],
                'linkArr' => array('user/index', 'id' => $_info['id']),
                'linkUrl' => Yii::app()->createUrl('user/index', array('id' => $_info['id'])),
                'exp' => $_info['exp'],
                'favors' => $_info['favors'],
                'favord' => $_info['favord'],
            );
        }
        if (!zmf::actionLimit('visit-Threads', $id, 5, 60)) {
            Posts::updateCount($id, 'PostThreads', 1, 'hits');
        }
        $size = 'w600';
        if ($this->isMobile) {
            $size = 'w650';
            //$this->layout = 'post';
        }
        //取楼主发的第一层
        $sqlContent = "SELECT p.id,p.uid,p.aid,p.cTime,p.updateTime,p.open,p.comments,p.favors,p.content,'' AS props FROM {{post_posts}} p WHERE p.tid='{$id}' AND p.isFirst=1 AND p.status=" . Posts::STATUS_PASSED;
        $firstContent = Yii::app()->db->createCommand($sqlContent)->queryRow();
        $firstContent['content'] = zmf::text(array(), $firstContent['content'], true, $size);
        $firstContent['props'] = Props::getClassifyProps('postPosts', $firstContent['id']);
        $info['content'] = $firstContent;

        $seeLz = zmf::val('see_lz', 2);
        //获取回帖列表
        $where = '';
        if ($seeLz == 1) {
            $where = ' AND p.uid=' . $info['uid'].' AND p.anonymous=0';
        }
        //回帖排序
        $postOrder=  zmf::val('order',2);
        if($postOrder==2){
            $_postOrder='p.favors DESC';
        }else{
            $_postOrder='p.cTime ASC';
            $postOrder=1;
        }
        $sql = "SELECT p.id,p.tid,p.uid,p.aid,p.anonymous,u.truename AS username,u.avatar,u.level,u.levelTitle,u.levelIcon,p.aid,p.cTime,p.updateTime,p.open,p.comments,p.favors,p.content,'' AS props FROM {{post_posts}} p,{{users}} u WHERE p.tid='{$id}' AND p.isFirst=0{$where} AND p.status=" . Posts::STATUS_PASSED . " AND p.uid=u.id AND u.status=" . Posts::STATUS_PASSED . " ORDER BY ".$_postOrder;        Posts::getAll(array('sql' => $sql, 'pageSize' => $this->pageSize), $pages, $posts);
        if (!empty($posts)) {
            $uids = array_filter(array_keys(CHtml::listData($posts, 'aid', '')));
            $uidsStr = join(',', $uids);
            $usernames = array();
            if ($uidsStr != '') {
                $usernames = Yii::app()->db->createCommand("SELECT id,authorName,avatar FROM {{authors}} WHERE id IN($uidsStr)")->queryAll();
            }
            //取出我已赞过的评论
            $favoredArr = array();
            if ($this->uid) {
                $comIds = array_filter(array_keys(CHtml::listData($posts, 'id', '')));
                $comIdsStr = join(',', $comIds);
                if ($comIdsStr != '') {
                    $comTempArr = Yii::app()->db->createCommand("SELECT id,logid FROM {{favorites}} WHERE classify='postPosts' AND uid='{$this->uid}' AND logid IN($comIdsStr)")->queryAll();
                    $favoredArr = array_values(CHtml::listData($comTempArr, 'id', 'logid'));
                }
            }
            foreach ($posts as $k => $val) {
                $posts[$k]['favorited'] = 0;
                if (in_array($val['id'], $favoredArr)) {
                    $posts[$k]['favorited'] = 1;
                }
                $find = false;
                if (!empty($usernames)) {
                    foreach ($usernames as $val2) {
                        if ($val['aid'] > 0 && $val['aid'] == $val2['id']) {
                            $posts[$k]['userInfo'] = array(
                                'type' => 'author',
                                'id' => $val2['id'],
                                'username' => $val2['authorName'],
                                'level' => $val2['level'],
                                'levelTitle' => $val2['levelTitle'],
                                'levelIcon' => $val2['levelIcon'],
                                'linkArr' => array('author/view', 'id' => $val2['id']),
                                'avatar' => zmf::getThumbnailUrl($val2['avatar'], 'd120', 'author'),
                            );
                            $find = true;
                            break;
                        }
                    }
                }
                if (!$find) {
                    $posts[$k]['userInfo'] = array(
                        'type' => 'user',
                        'id' => $val['uid'],
                        'username' => $val['username'],
                        'level' => $val['level'],
                        'levelTitle' => $val['levelTitle'],
                        'levelIcon' => $val['levelIcon'],
                        'linkArr' => array('user/index', 'id' => $val['uid']),
                        'avatar' => zmf::getThumbnailUrl($val['avatar'], 'd120', 'user'),
                    );
                }
                unset($posts[$k]['username']);
                unset($posts[$k]['level']);
                unset($posts[$k]['levelTitle']);
                unset($posts[$k]['levelIcon']);
            }
        }
        foreach ($posts as $k => $val) {
            //$posts[$k]['avatar'] = zmf::getThumbnailUrl($val['avatar'], 'a120', 'avatar');
            $posts[$k]['content'] = zmf::text(array(), $val['content'], true, $size);
            //$posts[$k]['props'] = Props::getClassifyProps('postPosts', $val['id']);
        }
        //$comments = Comments::getCommentsByPage($id, $this->uid, 'posts', 1, $this->pageSize, "c.id,c.uid,u.truename,u.avatar,c.aid,c.logid,c.tocommentid,c.content,c.cTime,c.status,c.favors");
        //$tags = Tags::getByIds($info['tagids']);
        //取作者的其他帖子        
        if ($info['aid'] > 0) {
            $relatePosts = PostThreads::getUserOtherPosts($info['aid'], $info['id'], $info['fid'], 10, true);
        } else {
            $relatePosts = PostThreads::getUserOtherPosts($info['uid'], $info['id'], $info['fid']);
        }
        //获取板块热门帖子
        $topsPosts = PostThreads::getForumTops($info['fid'], $info['id']);

        //初始化快速评论框
        $model = new PostPosts;
        //判断是否收藏
        $favoritedForum = false;
        if ($this->uid) {
            $favoritedForum = Favorites::checkFavored($info['fid'], 'forum');
        }
        //判断是否可以回复
        $now = zmf::now();
        $replyPostOrNot = true;
        $replyPostOrNotLabel = '';
        if ($info['postExpiredTime'] > 0 && $info['postExpiredTime'] <= $now) {
            $replyPostOrNot = false;
            $replyPostOrNotLabel = '投稿已截止';
        }
        if ($replyPostOrNot) {
            $replyPostOrNot = PostForums::replyPostOrNot($info, $this->userInfo);
            if (!$replyPostOrNot) {
                $replyPostOrNotLabel = '仅' . PostForums::posterType($info['posterType']) . '可回复。';
            }
        }
        $data = array(
            'info' => $info,
            'forumInfo' => $forumInfo,
            'posts' => $posts,
            'pages' => $pages,
            'authorInfo' => $authorInfo,
            'relatePosts' => $relatePosts,
            'topsPosts' => $topsPosts,
            'model' => $model,
            'favoritedForum' => $favoritedForum,
            'replyPostOrNot' => $replyPostOrNot,
            'replyPostOrNotLabel' => $replyPostOrNotLabel,
            'postOrder' => $postOrder,
        );
        $this->selectNav = 'forum';
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
        $id = zmf::val('id', 2);
        $addScoreExp = true;
        $firstContent = array();
        if ($id) {
            $model = $this->loadModel($id);
            if (!$model) {
                throw new CHttpException(404, '你所编辑的内容不存在');
            }
            //获取用户组的权限
            $powerInfo = GroupPowers::checkPower($this->userInfo, 'editPost');
            if (!$powerInfo['status']) {
                $this->message($powerInfo['status'], $powerInfo['msg']);
            }
            $forumInfo = PostForums::getOne($model->fid);
            if (!$forumInfo) {
                $this->message(0, '所属版块不存在');
            }
            $isNew = false;
            $addScoreExp = false;
            //取内容
            $firstContent = PostPosts::model()->find(array(
                'condition' => 'tid=:tid AND isFirst=1',
                'params' => array(
                    ':tid' => $id
                )
            ));
            if (!$firstContent || $firstContent['status'] != Posts::STATUS_PASSED) {
                $this->message(0, '此帖数据有误，请重新发布');
            } elseif ($model['uid'] != $firstContent['uid'] || $firstContent['uid'] != $this->uid) {
                //如果不相等，则表示有可能是版主
                if (!ForumAdmins::checkForumPower($this->uid, $model['fid'], 'editPost')) {
                    $this->message(0, '你无权此操作');
                }
            }
            $model->content = zmf::text(array('action' => 'edit'), $firstContent['content'], false, 'w600');
        } else {
            $forumId = zmf::val('forum', 2);
            if (!$forumId) {
                $this->redirect(array('posts/types'));
            }
            $forumInfo = PostForums::getOne($forumId);
            if (!$forumInfo) {
                $this->message(0, '你所查看的版块不存在');
            }
            //判断角色
            if (!PostForums::addPostOrNot($forumInfo, $this->userInfo)) {
                $this->message(0, '你不能在该版块发帖');
            }
            //获取用户组的权限
            $powerInfo = GroupPowers::checkPower($this->userInfo, 'addPost');
            if (!$powerInfo['status']) {
                //用户本身的权限已用完，再判断对方是否是版主
                if (!ForumAdmins::checkForumPower($this->uid, $forumId, 'addPost', true)) {
                    $this->message($powerInfo['status'], $powerInfo['msg']);
                } else {
                    $addScoreExp = false;
                }
            }
            $model = new PostThreads;
            $model->fid = $forumId;
            $model->open = Posts::STATUS_OPEN;
            $isNew = true;
        }
        $setThreadStatus = ForumAdmins::checkForumPower($this->uid, $forumInfo['id'], 'setThreadStatus');
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
                    $attr['faceImg'] = Attachments::faceImg(array('faceimg' => $attkeys[0]), '', 'faceImg'); //默认将文章中的第一张图作为封面图
                }
            } else {
                $attr['faceImg'] = ''; //否则将封面图置为空(有可能编辑后没有图片了)
            }
            //如果标题包含敏感词则直接标记为未通过
            $attr['status'] = $filterTitle['status'] == Posts::STATUS_PASSED ? $filterContent['status'] : $filterTitle['status'];
            $attr['open'] = ($_POST['PostThreads']['open'] == Posts::STATUS_OPEN) ? 1 : 0;
            $attr['platform'] = $this->isMobile ? Posts::PLATFORM_MOBILE : Posts::PLATFORM_WEB;
            $attr['aid'] = ($this->userInfo['authorId'] > 0 ? ($_POST['PostThreads']['aid'] > 0 ? $this->userInfo['authorId'] : 0) : 0);
            //判断是否版主
            if ($setThreadStatus) {
                $attr['display'] = ($_POST['PostThreads']['display'] == 1) ? 1 : 0;
                $_pTLabel = PostForums::posterType($_POST['PostThreads']['posterType']);
                $attr['posterType'] = $_pTLabel ? $_POST['PostThreads']['posterType'] : '';
                $now = zmf::now();
                if (isset($_POST['PostThreads']['postExpiredTime'])) {
                    $attr['postExpiredTime'] = strtotime($_POST['PostThreads']['postExpiredTime'], $now);
                }
                if (isset($_POST['PostThreads']['voteExpiredTime'])) {
                    $attr['voteExpiredTime'] = strtotime($_POST['PostThreads']['voteExpiredTime'], $now);
                }
            } else {
                $attr['display'] = 0;
                $attr['posterType'] = $attr['postExpiredTime'] = $attr['voteExpiredTime'] = '';
            }
            $model->attributes = $attr;
            if ($model->save()) {
                //保存第一楼内容
                if ($firstContent) {
                    $modelPost = $firstContent;
                } else {
                    $modelPost = new PostPosts;
                }
                $postAttr = array(
                    'tid' => $model->id,
                    'content' => $content,
                    'isFirst' => 1, //首层[aid]
                    'aid' => $model->aid
                );
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
                    'score' => $addScoreExp ? $powerInfo['msg']['score'] : 0,
                    'exp' => $addScoreExp ? $powerInfo['msg']['exp'] : 0,
                    'display' => $powerInfo['msg']['display'],
                );
                if (UserAction::simpleRecord($attr)) {
                    //判断本操作是否同属任务
                    $ckTaskStatus = Task::addTaskLog($this->userInfo, 'addPost');
                }
                $this->redirect(array('posts/view', 'id' => $model->id));
            }
        }
        //$tags = Tags::getClassifyTags('thread');
        $this->pageTitle = '【' . $forumInfo['title'] . '】发布文章 - ' . zmf::config('sitename');
        $this->selectNav = 'forum';
        $this->mobileTitle = '发布帖子';
        $this->render('create', array(
            'model' => $model,
            'forumInfo' => $forumInfo,
            'setThreadStatus' => $setThreadStatus,
                //'tags' => $tags,
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
        $addScoreExp = true;
        $threadInfo = $this->loadModel($tid);
        if ($pid) {
            $model = PostPosts::getOne($pid);
            if (!$model || $model->status!=Posts::STATUS_PASSED) {
                throw new CHttpException(404, '你编辑的页面不存在或已被删除！');
            } elseif ($model->tid != $tid) {
                throw new CHttpException(404, '数据有误，请核实！');
            } elseif ($model->isFirst) {//不能编辑首层
                $this->redirect(array('posts/create', 'id' => $model->tid));
            }            
        } else {
            $model = new PostPosts;
            $model->tid = $tid;
            $model->open = Posts::STATUS_OPEN;
        }
        //获取用户组的权限
        if($pid){
            if($model->uid != $this->uid){
                //用户本身的权限已用完，再判断对方是否是版主
                if (!ForumAdmins::checkForumPower($this->uid, $threadInfo['fid'], 'editPostReply', true)) {
                    throw new CHttpException(403, '你无权此操作！');
                } else {
                    $addScoreExp = false;
                }
            }
            $model->content = zmf::text(array('action'=>'edit'), $model['content'], true, 'w600');
        }else{
            $powerInfo = GroupPowers::checkPower($this->userInfo, 'addPostReply');
            if (!$powerInfo['status']) {
                //用户本身的权限已用完，再判断对方是否是版主
                if (!ForumAdmins::checkForumPower($this->uid, $threadInfo['fid'], 'addPostReply', true)) {
                    $this->message($powerInfo['status'], $powerInfo['msg']);
                } else {
                    $addScoreExp = false;
                }
            }
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
            $attr['open'] = $pid ? (($_POST['PostPosts']['open'] == Posts::STATUS_OPEN) ? 1 : 0) : Posts::STATUS_OPEN;
            $attr['platform'] = $this->isMobile ? Posts::PLATFORM_MOBILE : Posts::PLATFORM_WEB;
            $attr['aid'] = ($this->userInfo['authorId'] > 0 ? ($_POST['PostPosts']['aid'] > 0 ? $this->userInfo['authorId'] : 0) : 0);
            $attr['anonymous'] = $_POST['PostPosts']['anonymous'];
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
                //更新帖子的楼层数
                PostThreads::updateStat($model->tid);
                //记录用户操作及积分
                $jsonData = CJSON::encode(array(
                            'id' => $threadInfo['id'],
                            'title' => $threadInfo['title'],
                            'faceimg' => $threadInfo['faceImg']
                ));
                $attr = array(
                    'uid' => $this->uid,
                    'logid' => $model->id,
                    'classify' => 'post',
                    'data' => $jsonData,
                    'action' => 'addPostReply',
                    'score' => $addScoreExp ? $powerInfo['msg']['score'] : 0,
                    'exp' => $addScoreExp ? $powerInfo['msg']['exp'] : 0,
                    'display' => $powerInfo['msg']['display'],
                );
                if (UserAction::simpleRecord($attr)) {
                    //判断本操作是否同属任务
                    $ckTaskStatus = Task::addTaskLog($this->userInfo, 'addPostReply');
                }
                $this->redirect(array('posts/view', 'id' => $model->tid));
            }
        }
        $this->selectNav = 'forum';
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
