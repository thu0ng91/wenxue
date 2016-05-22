<?php

class AjaxController extends Q {

    public function init() {
        parent::init();
        if (!Yii::app()->request->isAjaxRequest) {
            $this->jsonOutPut(0, Yii::t('default', 'forbiddenaction'));
        }
    }

    private function checkLogin() {
        if (Yii::app()->user->isGuest) {
            $this->jsonOutPut(0, Yii::t('default', 'loginfirst'));
        }
    }

    public function actionDo() {
        $action = zmf::val('action', 1);
        if (!in_array($action, array('addTip'))) {
            $this->jsonOutPut(0, Yii::t('default', 'forbiddenaction'));
        }
        $this->$action();
    }

    private function addTip() {
        $this->checkLogin();
        $keyid = zmf::val('k', 2);
        $type = zmf::val('t', 1);
        $content = zmf::val('c', 1);
        $score = zmf::val('score', 2);
        if (!isset($type) || !in_array($type, array('chapter'))) {
            $this->jsonOutPut(0, Yii::t('default', 'forbiddenaction'));
        }
        if (!isset($keyid) || !is_numeric($keyid)) {
            $this->jsonOutPut(0, Yii::t('default', 'pagenotexists'));
        }
        if(!$score || $score>5 || $score<1){
            $this->jsonOutPut(0, '请评一个分吧~');
        }
        if (!$content) {
            $this->jsonOutPut(0, '评论不能为空哦~');
        }
        $status = Posts::STATUS_PASSED;
        $uid = $this->uid;
        if ($type == 'chapter') {
            $ckInfo=  Chapters::checkTip($keyid, $uid);
            if($ckInfo!==false){
                $this->jsonOutPut(0, '每章节只能评价一次');
            }
            $postInfo = Chapters::getOne($keyid);
        }
        if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '您所评论的内容不存在');
        }
        //处理文本
        $filter = Posts::handleContent($content);
        $content = $filter['content'];
        $model = new Tips();
        $toNotice = true;
        $toUserInfo = array();
        $touid = $postInfo['uid'];
        $intoData = array(
            'bid' => $postInfo['bid'],
            'logid' => $keyid,
            'uid' => $uid,
            'content' => $content,
            'classify' => $type,
            'score' => $score,
            'platform' => '', //$this->platform
            'status' => $status
        );
        unset(Yii::app()->session['checkHasBadword']);
        $model->attributes = $intoData;
        if ($model->validate()) {
            if ($model->save()) {
                if ($type == 'chapter') {
                    Posts::updateCount($keyid, 'Chapters', 1, 'comments');
                    $_url = CHtml::link('查看详情', array('book/chapter', 'cid' => $keyid, '#' => 'pid-' . $model->id));
                    $_content = '您的小说章节【' . $postInfo['title'] . '】有了新的点评,' . $_url;
                    $intoData['truename'] = $this->userInfo['truename'];
                    $intoData['cTime'] = zmf::now();
                    $intoData['favors'] = 0;
                    //更新统计
                    Books::updateScore($postInfo['bid']);
                }
                if ($toNotice) {
                    $_noticedata = array(
                        'uid' => $touid,
                        'authorid' => $uid,
                        'content' => $_content,
                        'new' => 1,
                        'type' => 'comment',
                        'cTime' => zmf::now(),
                        'from_id' => $model->id,
                        'from_num' => 1
                    );
                    Notification::add($_noticedata);
                }
                $html = $this->renderPartial('/book/_tip', array('data' => $intoData, 'postInfo' => $postInfo), true);
                $this->jsonOutPut(1, $html);
            } else {
                $this->jsonOutPut(0, '新增评论失败');
            }
        } else {
            $this->jsonOutPut(0, '新增评论失败');
        }
    }

    public function actionFeedback() {
        $content = zmf::val('content', 1);
        if (!$content) {
            $this->jsonOutPut(0, '内容不能为空哦~');
        }
        //一个小时内最多只能反馈5条
        if (zmf::actionLimit('feedback', '', 5, 3600)) {
            $this->jsonOutPut(0, '操作太频繁，请稍后再试');
        }
        $attr['uid'] = $this->uid;
        $attr['type'] = 'web';
        $attr['contact'] = zmf::val('email', 1);
        $attr['appinfo'] = zmf::val('url', 1);
        $attr['sysinfo'] = Yii::app()->request->userAgent;
        $attr['content'] = $content;
        $model = new Feedback();
        $model->attributes = $attr;
        if ($model->validate()) {
            if ($model->save()) {
                $this->jsonOutPut(1, '感谢您的反馈');
            } else {
                $this->jsonOutPut(1, '感谢您的反馈');
            }
        } else {
            $this->jsonOutPut(0, '反馈失败，请重试');
        }
    }

    public function actionAddComment() {
        $this->checkLogin();
        $keyid = zmf::val('k', 2);
        $to = zmf::val('to', 2);
        $type = zmf::val('t', 1);
        $content = zmf::val('c', 1);
        if (!isset($type) OR ! in_array($type, array('posts'))) {
            $this->jsonOutPut(0, Yii::t('default', 'forbiddenaction'));
        }
        if (!isset($keyid) OR ! is_numeric($keyid)) {
            $this->jsonOutPut(0, Yii::t('default', 'pagenotexists'));
        }
        if (!$content) {
            $this->jsonOutPut(0, '评论不能为空哦~');
        }
        $status = Posts::STATUS_PASSED;
        $uid = $this->uid;
        $postInfo = Posts::model()->findByPk($keyid);
        if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '您所评论的内容不存在');
        }
        //处理文本
        $filter = Posts::handleContent($content);
        $content = $filter['content'];
        $model = new Comments();
        $toNotice = true;
        $toUserInfo = array();
        $touid = $postInfo['uid'];
        if ($to) {
            $comInfo = Comments::model()->findByPk($to);
            if (!$comInfo || $comInfo['status'] != Posts::STATUS_PASSED || !$comInfo['uid']) {
                $to = '';
            } elseif ($comInfo['uid'] == $uid) {
                $toNotice = false;
            } else {
                $toUserInfo = Users::getOne($comInfo['uid']);
                if ($toUserInfo['status'] == Posts::STATUS_PASSED) {
                    $touid = $comInfo['uid'];
                    $toNotice = true;
                }
            }
        }
        $intoData = array(
            'logid' => $keyid,
            'uid' => $uid,
            'content' => $content,
            'cTime' => zmf::now(),
            'classify' => $type,
            'platform' => '', //$this->platform
            'tocommentid' => $to,
            'status' => $status,
            'username' => $username,
            'email' => $email,
        );
        unset(Yii::app()->session['checkHasBadword']);
        $model->attributes = $intoData;
        if ($model->validate()) {
            if ($model->save()) {
                if ($type == 'posts') {
                    $_url = CHtml::link('查看详情', array('posts/view', 'id' => $keyid, '#' => 'pid-' . $model->id));
                    if ($status == Posts::STATUS_PASSED) {
                        Posts::updateCommentsNum($keyid);
                    }
                    $_content = '您的文章【' . $postInfo['title'] . '】有了新的评论,' . $_url;
                }
                if ($to && $_url) {
                    $_content = '您的评论有了新的回复,' . $_url;
                }
                if ($toNotice) {
                    $_noticedata = array(
                        'uid' => $touid,
                        'authorid' => $uid,
                        'content' => $_content,
                        'new' => 1,
                        'type' => 'comment',
                        'cTime' => zmf::now(),
                        'from_id' => $model->id,
                        'from_num' => 1
                    );
                    Notification::add($_noticedata);
                }
                if ($uid) {
                    $intoData['loginUsername'] = $this->userInfo['truename'];
                    $intoData['toUserInfo'] = $toUserInfo;
                }
                if ($postInfo['zazhi'] > 0) {
                    $html = $this->renderPartial('/zazhi/_comment', array('data' => $intoData, 'postInfo' => $postInfo), true);
                } else {
                    $html = $this->renderPartial('/posts/_comment', array('data' => $intoData, 'postInfo' => $postInfo), true);
                }
                $this->jsonOutPut(1, $html);
            } else {
                $this->jsonOutPut(0, '新增评论失败');
            }
        } else {
            $this->jsonOutPut(0, '新增评论失败');
        }
    }

    public function actionGetContents() {
        $data = zmf::filterInput($_POST['data']);
        $page = zmf::filterInput($_POST['page']);
        $type = zmf::filterInput($_POST['type'], 't', 1);
        if (!$data || !$type) {
            $this->jsonOutPut(0, '数据不全，请核实');
        }
        if (!in_array($type, array('comments'))) {
            $this->jsonOutPut(0, '暂不允许的分类');
        }
        if ($page < 1 || !is_numeric($page)) {
            $page = 1;
        }
        $limit = 30;
        $longHtml = '';
        $postInfo = Posts::model()->findByPk($data);
        if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '您所评论的内容不存在');
        }
        switch ($type) {
            case 'comments':
                $limit = 30;
                $posts = Comments::getCommentsByPage($data, 'posts', $page, $limit);
                if ($postInfo['zazhi'] > 0) {
                    $view = '/zazhi/_comment';
                } else {
                    $view = '/posts/_comment';
                }
                break;
            default:
                $posts = array();
                break;
        }
        if (!empty($posts)) {
            foreach ($posts as $k => $row) {
                $longHtml.=$this->renderPartial($view, array('data' => $row, 'k' => $k, 'postInfo' => $postInfo), true);
            }
        }
        $data = array(
            'html' => $longHtml,
            'loadMore' => (count($posts) == $limit) ? 1 : 0,
            'formHtml' => ''
        );
        $this->jsonOutPut(1, $data);
    }

    public function actionDelContent() {
        $this->checkLogin();
        $data = zmf::val('data', 1);
        $type = zmf::val('type', 1);
        if (!$data || !$type) {
            $this->jsonOutPut(0, '数据不全，请核实');
        }
        if (!in_array($type, array('comment', 'post', 'notice', 'tag'))) {
            $this->jsonOutPut(0, '暂不允许的分类');
        }
        switch ($type) {
            case 'comment':
                $info = Comments::model()->findByPk($data);
                if (!$info) {
                    $this->jsonOutPut(0, '您所查看的内容不存在');
                } elseif ($info['uid'] != $this->uid) {
                    if ($this->checkPower('delComment', $this->uid, true)) {
                        //我是管理员，我就可以删除
                    } else {
                        $this->jsonOutPut(0, '您无权操作');
                    }
                }
                if (Comments::model()->updateByPk($data, array('status' => Posts::STATUS_DELED))) {
                    $this->jsonOutPut(1, '已删除');
                }
                $this->jsonOutPut(1, '已删除');
                break;
            case 'post':
                $info = Posts::model()->findByPk($data);
                if (!$info) {
                    $this->jsonOutPut(0, '您所查看的内容不存在');
                } elseif ($info['uid'] != $this->uid) {
                    if ($this->checkPower('delPost', $this->uid, true)) {
                        //我是管理员，我就可以删除
                    } else {
                        $this->jsonOutPut(0, '您无权操作');
                    }
                }
                if (Posts::model()->updateByPk($data, array('status' => Posts::STATUS_DELED))) {
                    $this->jsonOutPut(1, '已删除');
                }
                $this->jsonOutPut(1, '已删除');
                break;
            case 'notice':
                if (!$data || !is_numeric($data)) {
                    $this->jsonOutPut(0, '您所操作的内容不存在');
                }
                if (Notification::model()->deleteByPk($data)) {
                    $this->jsonOutPut(1, '已删除');
                }
                $this->jsonOutPut(1, '已删除');
                break;
            case 'tag':
                if (!$data || !is_numeric($data)) {
                    $this->jsonOutPut(0, '您所操作的内容不存在');
                }
                if (!$this->checkPower('delTag', $this->uid, true)) {
                    $this->jsonOutPut(0, '您无权操作');
                }
                if (Tags::model()->updateByPk($data, array('status' => Posts::STATUS_DELED))) {
                    $this->jsonOutPut(1, '已删除');
                }
                $this->jsonOutPut(1, '已删除');
                break;
            default:
                $this->jsonOutPut(0, '操作有误');
                break;
        }
    }

    public function actionSetStatus() {
        $this->checkLogin();
        $keyid = zmf::val('a', 2);
        $classify = zmf::val('b', 1);
        $_status = zmf::val('c', 1);
        if (!$keyid) {
            $this->jsonOutPut(0, '请选择对象');
        }
        if (!in_array($classify, array('posts', 'comments'))) {
            $this->jsonOutPut(0, '不允许的类型');
        }
        if (!in_array($_status, array('del', 'passed'))) {
            $this->jsonOutPut(0, '不允许的类型');
        }
        if ($_status == 'top') {
            if ($classify == 'posts') {
                $attr = array(
                    'top' => 1,
                    'updateTime' => zmf::now()
                );
            } else {
                $attr = array(
                    'top' => 1
                );
            }
        } else if ($_status == 'canceltop') {
            $attr = array(
                'top' => 0,
            );
        } else if ($_status == 'del') {
            $attr = array(
                'status' => Posts::STATUS_DELED,
            );
        } else if ($_status == 'passed') {
            $attr = array(
                'status' => Posts::STATUS_PASSED,
            );
        }
        $ucClassify = ucfirst($classify);
        if (!class_exists($ucClassify)) {
            $this->jsonOutPut(0, '不存在的类型');
        }
        $model = new $ucClassify;
        if ($model->updateByPk($keyid, $attr)) {
            if ($classify == 'comments') {
                Posts::updateCommentsNum($keyid);
            }
            $this->jsonOutPut(1, '操作成功');
        } else {
            $this->jsonOutPut(0, '操作失败');
        }
    }

    public function actionFavorite() {
        $data = zmf::val('data', 1);
        $type = zmf::val('type', 1);
        $ckinfo = Posts::favorite($data, $type, 'web');
        $this->jsonOutPut($ckinfo['state'], $ckinfo['msg']);
    }

    public function actionOrderChapters() {
        $zid = zmf::val('zid', 2);
        $ids = zmf::val('ids', 1);
        if (!$ids || !$zid) {
            $this->jsonOutPut(0, '数据不全');
        }
        $arr = array_filter(explode('#', $ids));
        if (empty($arr)) {
            $this->jsonOutPut(0, '数据不全');
        }
        $s = $e = 0;
        foreach ($arr as $k => $v) {
            $_order = ($k + 1);
            $data = array(
                'order' => $_order
            );
            $_info = Posts::model()->updateByPk($v, $data);
            if ($_info) {
                $s+=1;
            } else {
                $e+=1;
            }
        }
        if ($s > 0) {
            $this->jsonOutPut(1, '已更新');
        }
        $this->jsonOutPut(0, '可能未做修改');
    }

    public function actionParseVideo() {
        $this->checkLogin();
        $url = zmf::val('url', 1);
        $desc = zmf::val('desc', 1);
        if (!$url) {
            $this->jsonOutPut(0, '请输入地址');
        }
        if (strpos($url, 'http://') === false && strpos($url, 'https://') === false) {
            $url = 'http://' . $url;
        }
        $validator = new CUrlValidator;
        if (!$validator->validateValue($url)) {
            $this->jsonOutPut(0, '请输入输入一个有效的链接地址');
        } elseif (strpos($url, 'qq.com') !== false) {
            $this->jsonOutPut(0, '暂不支持腾讯视频');
        } elseif (strpos($url, 'youku.com') === false && strpos($url, 'tudou.com') === false && strpos($url, 'qq.com') === false) {
            $this->jsonOutPut(0, '目前仅支持优酷、土豆视频');
        }

        $return = zmfVideo::parse_vedio($url);
        if ($return['faceimg'] != '') {
            $data = array(
                'logid' => 0,
                'classify' => 'posts',
                'title' => $return['title'],
                'url' => $url,
                'swfurl' => $return['swfurl'],
                'h5url' => $return['h5url'],
                'content' => $desc,
                'faceimg' => $return['faceimg'],
                'status' => Posts::STATUS_NOTPASSED,
                'company' => $return['company'],
                'videoid' => $return['videoid'],
            );
            $model = new Videos;
            $model->attributes = $data;
            if ($model->save()) {
                $html = $this->renderPartial('//common/_addVideo', array('data' => $model), true);
                $this->jsonOutPut(1, $html);
            }
        }
        $this->jsonOutPut(0, '解析视频地址错误');
    }

}
