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
        if (!in_array($action, array('addTip', 'saveUploadImg', 'publishBook', 'publishChapter', 'saveDraft', 'report', 'sendSms', 'checkSms', 'setStatus', 'delContent', 'getNotice', 'getContents', 'delBook', 'delChapter', 'finishBook', 'dapipi', 'joinGroup', 'float', 'ajax', 'gotoBuy', 'confirmBuy'))) {
            $this->jsonOutPut(0, Yii::t('default', 'forbiddenaction'));
        }
        $this->$action();
    }

    private function float() {
        $data = zmf::val('data', 1);
        if (!$data) {
            $this->jsonOutPut(0, '缺少参数~');
        }
        $arr = Posts::decode($data);
        switch ($arr['type']) {
            case 'tasks':
                $this->userTasks();
                break;
            default:
                $this->jsonOutPut(0, '不被允许的操作~');
                break;
        }
    }

    private function ajax() {
        $data = zmf::val('data', 1);
        if (!$data) {
            $this->jsonOutPut(0, '缺少参数~');
        }
        $arr = Posts::decode($data);
        switch ($arr['type']) {
            case 'joinTask':
                $this->checkLogin();
                $this->checkUserStatus();
                $this->joinTask($arr);
                break;
            default:
                $this->jsonOutPut(0, '不被允许的操作~');
                break;
        }
    }

    private function addTip() {
        $this->checkLogin();
        $this->checkUserStatus();
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
        if (!$score || $score > 5 || $score < 1) {
            $this->jsonOutPut(0, '请评一个分吧~');
        }
        if (!$content) {
            $this->jsonOutPut(0, '评论不能为空哦~');
        }
        //获取用户组的权限
        $powerAction = 'addChapterTip';
        $powerInfo = GroupPowers::checkPower($this->userInfo, $powerAction);
        if (!$powerInfo['status']) {
            $this->jsonOutPut(0, $powerInfo['msg']);
        }
        $uid = $this->uid;
        //todo，按分类获取信息
        $ckInfo = Chapters::checkTip($keyid, $uid);
        if ($ckInfo !== false) {
            $this->jsonOutPut(0, '每章节只能评价一次');
        }
        $postInfo = Chapters::getOne($keyid);
        if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '你所评论的内容不存在');
        }
        //小说信息
        $arr = array(
            Books::STATUS_PUBLISHED,
            Books::STATUS_FINISHED
        );
        $bookInfo = Books::getOne($postInfo['bid']);
        if (!$bookInfo || $bookInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '你所评论的小说不存在');
        } elseif (!in_array($bookInfo['bookStatus'], $arr)) {
            $this->jsonOutPut(0, '你所评论的小说暂未发表');
        }
        //处理文本，不是富文本
        $filter = Posts::handleContent($content, FALSE);
        $content = $filter['content'];
        $status = $filter['status'];
        $model = new Tips();
        $toNotice = true;
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
                    $_content = '点评了你的小说章节【' . $postInfo['title'] . '】,' . $_url;
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
                //记录用户操作
                $jsonData = CJSON::encode(array(
                            'cid' => $keyid,
                            'cTitle' => $postInfo['title'],
                            'bid' => $bookInfo['id'],
                            'bTitle' => $bookInfo['title'],
                            'bDesc' => $bookInfo['desc'],
                            'bFaceImg' => $bookInfo['faceImg'],
                ));
                $attr = array(
                    'uid' => $this->uid,
                    'logid' => $model->id,
                    'classify' => 'chapterTip',
                    'data' => $jsonData,
                    'action' => $powerAction,
                    'score' => $powerInfo['msg']['score'],
                    'display' => 1,
                );
                if (UserAction::simpleRecord($attr)) {
                    //判断本操作是否同属任务
                    Task::addTaskLog($this->userInfo, $powerAction);
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

    public function saveUploadImg() {
        $this->checkLogin();
        $type = zmf::val('type', 1);
        if (!isset($type) OR ! in_array($type, array('posts'))) {
            $this->jsonOutPut(0, '请设置上传所属类型' . $type);
        }
        $filePath = zmf::val('filePath', 1);
        $fileSize = zmf::val('fileSize', 2);
        $pathArr = pathinfo($filePath);
        if (!$filePath || !$fileSize || !$pathArr['basename']) {
            $this->jsonOutPut(0, '保存图片失败');
        }
        $fullDir = zmf::attachBase('site', $type) . $filePath;
        $imgInfo = file_get_contents($fullDir . '?imageInfo');
        $imgInfoArr = CJSON::decode($imgInfo, 'true');
        if (!$imgInfoArr) {
            $this->jsonOutPut(0, '获取图片信息失败');
        }
        $width = $imgInfoArr['width'];
        $height = $imgInfoArr['height'];
        if (in_array($imgInfoArr['orientation'], array('Right-top', 'Left-bottom'))) {
            $width = $imgInfoArr['height'];
            $height = $imgInfoArr['width'];
        }
        $data = array();
        $data['uid'] = zmf::uid();
        $data['logid'] = 0;
        $data['filePath'] = $pathArr['basename']; //文件名
        $data['fileDesc'] = '';
        $data['classify'] = $type;
        $data['covered'] = '0';
        $data['cTime'] = zmf::now();
        $data['status'] = Posts::STATUS_PASSED;
        $data['width'] = $width;
        $data['height'] = $height;
        $data['size'] = $fileSize;
        $data['remote'] = $fullDir;
        $model = new Attachments;
        $model->attributes = $data;
        if ($model->save()) {
            $attachid = $model->id;
            $returnimg = zmf::getThumbnailUrl($fullDir, '120', $type);
            $_attr = array(
                'id' => $attachid,
                'imgUrl' => $returnimg,
                'desc' => ''
            );
            $html = '';
            if ($type == 'posts') {
                $html = $this->renderPartial('/posts/_addImg', array('data' => $_attr), true);
            }
            $outPutData = array(
                'status' => 1,
                'attachid' => $attachid,
                'imgsrc' => $returnimg,
                'html' => $html,
            );
            $json = CJSON::encode($outPutData);
            echo $json;
        } else {
            $this->jsonOutPut(0, '写入数据库错误');
        }
    }

    private function publishBook() {
        $this->checkLogin();
        $this->checkUserStatus();
        $id = zmf::val('id', 2);
        if (!$id) {
            $this->jsonOutPut(0, '缺少参数哦~');
        }
        //获取用户组的权限
        $powerAction = 'addBook';
        $powerInfo = GroupPowers::checkPower($this->userInfo, $powerAction);
        if (!$powerInfo['status']) {
            $this->jsonOutPut(0, $powerInfo['msg']);
        }
        $bookInfo = Books::getOne($id);
        if (!$bookInfo || $bookInfo['status'] != Posts::STATUS_PASSED) {
            if ($bookInfo && $bookInfo['status'] == Books::STATUS_STAYCHECK) {
                $this->jsonOutPut(0, '该小说已被锁定');
            }
            $this->jsonOutPut(0, '小说不存在或已删除');
        } elseif ($bookInfo['uid'] != $this->uid || $bookInfo['aid'] != $this->userInfo['authorId']) {
            $this->jsonOutPut(0, '你无权本操作');
        } elseif (!$bookInfo['iAgree']) {
            $this->jsonOutPut(0, '请先同意本站协议');
        } elseif ($bookInfo['bookStatus'] == Books::STATUS_PUBLISHED) {
            $this->jsonOutPut(1, '已发表');
        }
        if (!Authors::checkLogin($this->userInfo, $bookInfo['aid'])) {
            $this->jsonOutPut(0, '你无权本操作');
        }
        $authorInfo = Authors::getOne($this->userInfo['authorId']);
        if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '你无权本操作');
        }
        //统计已发表的章节
        $chapters = Chapters::model()->count('uid=:uid AND aid=:aid AND bid=:bid AND status=' . Posts::STATUS_PASSED . ' AND chapterStatus=' . Books::STATUS_PUBLISHED, array(':uid' => $this->uid, ':aid' => $this->userInfo['authorId'], ':bid' => $id));
        if ($chapters < 1) {
            $this->jsonOutPut(0, '小说暂无已发表章节，请先发表章节');
        }
        if (Books::model()->updateByPk($id, array('bookStatus' => Books::STATUS_PUBLISHED))) {
            //更新小说信息
            Books::updateBookStatInfo($id);
            //更新作者信息
            Authors::updateStatInfo($authorInfo);
            //记录用户操作
            $jsonData = CJSON::encode(array(
                        'bid' => $bookInfo['id'],
                        'bTitle' => $bookInfo['title'],
                        'bDesc' => $bookInfo['desc'],
                        'bFaceImg' => $bookInfo['faceImg'],
            ));
            $attr = array(
                'uid' => $this->uid,
                'logid' => $bookInfo['id'],
                'classify' => $powerAction,
                'data' => $jsonData,
                'action' => $powerAction,
                'score' => $powerInfo['msg']['score'],
                'display' => 0,
            );
            if (UserAction::simpleRecord($attr)) {
                //判断本操作是否同属任务
                Task::addTaskLog($this->userInfo, $powerAction);
            }
            $this->jsonOutPut(1, '已发表');
        } else {
            $this->jsonOutPut(1, '已发表');
        }
    }

    private function finishBook() {
        $this->checkLogin();
        $this->checkUserStatus();
        $id = zmf::val('bid', 2);
        if (!$id) {
            $this->jsonOutPut(0, '缺少参数哦~');
        }
        //获取用户组的权限
        $powerAction = 'finishBook';
        $powerInfo = GroupPowers::checkPower($this->userInfo, $powerAction);
        if (!$powerInfo['status']) {
            $this->jsonOutPut(0, $powerInfo['msg']);
        }
        $bookInfo = Books::getOne($id);
        if (!$bookInfo || $bookInfo['status'] != Posts::STATUS_PASSED) {
            if ($bookInfo && $bookInfo['status'] == Books::STATUS_STAYCHECK) {
                $this->jsonOutPut(0, '该小说已被锁定');
            }
            $this->jsonOutPut(0, '小说不存在或已删除');
        } elseif ($bookInfo['uid'] != $this->uid || $bookInfo['aid'] != $this->userInfo['authorId']) {
            $this->jsonOutPut(0, '你无权本操作');
        } elseif (!$bookInfo['iAgree']) {
            $this->jsonOutPut(0, '请先同意本站协议');
        } elseif ($bookInfo['bookStatus'] != Books::STATUS_PUBLISHED) {
            $this->jsonOutPut(0, '小说尚未发表');
        } elseif ($bookInfo['bookStatus'] == Books::STATUS_FINISHED) {
            $this->jsonOutPut(1, '已完结');
        }
        if (!Authors::checkLogin($this->userInfo, $bookInfo['aid'])) {
            $this->jsonOutPut(0, '你无权本操作');
        }
        $authorInfo = Authors::getOne($this->userInfo['authorId']);
        if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '你无权本操作');
        }
        //统计已发表的章节
        $chapters = Chapters::model()->count('uid=:uid AND aid=:aid AND bid=:bid AND status=' . Posts::STATUS_PASSED . ' AND chapterStatus=' . Books::STATUS_PUBLISHED, array(':uid' => $this->uid, ':aid' => $this->userInfo['authorId'], ':bid' => $id));
        if ($chapters < 1) {
            $this->jsonOutPut(0, '小说暂无已发表章节，请先发表章节');
        }
        //统计未发表的章节
        $unChapters = Chapters::model()->count('uid=:uid AND aid=:aid AND bid=:bid AND status=' . Posts::STATUS_PASSED . ' AND chapterStatus!=' . Books::STATUS_PUBLISHED, array(':uid' => $this->uid, ':aid' => $this->userInfo['authorId'], ':bid' => $id));
        if ($unChapters > 0) {
            $this->jsonOutPut(0, '小说尚有未发表章节，请妥善处理未发表章节后重试');
        }
        if (Books::model()->updateByPk($id, array('bookStatus' => Books::STATUS_FINISHED))) {
            //更新小说信息
            Books::updateBookStatInfo($id);
            //更新作者信息
            Authors::updateStatInfo($authorInfo);
            //记录用户操作
            $jsonData = CJSON::encode(array(
                        'bid' => $bookInfo['id'],
                        'bTitle' => $bookInfo['title'],
                        'bDesc' => $bookInfo['desc'],
                        'bFaceImg' => $bookInfo['faceImg'],
            ));
            $attr = array(
                'uid' => $this->uid,
                'logid' => $bookInfo['id'],
                'classify' => $powerAction,
                'data' => $jsonData,
                'action' => $powerAction,
                'score' => $powerInfo['msg']['score'],
                'display' => 0,
            );
            if (UserAction::simpleRecord($attr)) {
                //判断本操作是否同属任务
                Task::addTaskLog($this->userInfo, $powerAction);
            }
            $this->jsonOutPut(1, '已标记为完结');
        } else {
            $this->jsonOutPut(1, '标记失败');
        }
    }

    private function delBook() {
        $this->checkLogin();
        $this->checkUserStatus();
        $id = zmf::val('bid', 2);
        $passwd = zmf::val('passwd', 1);
        if (!$id) {
            $this->jsonOutPut(0, '缺少参数哦~');
        }
        if (!$passwd) {
            $this->jsonOutPut(0, '请输入密码');
        }
        //获取用户组的权限
        $powerAction = 'delBook';
        $powerInfo = GroupPowers::checkPower($this->userInfo, $powerAction);
        if (!$powerInfo['status']) {
            $this->jsonOutPut(0, $powerInfo['msg']);
        }
        $bookInfo = Books::getOne($id);
        if (!$bookInfo || $bookInfo['status'] != Posts::STATUS_PASSED) {
            if ($bookInfo && $bookInfo['status'] == Books::STATUS_STAYCHECK) {
                $this->jsonOutPut(0, '该小说已被锁定');
            }
            $this->jsonOutPut(0, '小说不存在或已删除');
        } elseif ($bookInfo['uid'] != $this->uid || $bookInfo['aid'] != $this->userInfo['authorId']) {
            $this->jsonOutPut(0, '你无权本操作');
        } elseif ($bookInfo['status'] == Posts::STATUS_DELED) {
            $this->jsonOutPut(1, '已删除');
        }
        if (!Authors::checkLogin($this->userInfo, $bookInfo['aid'])) {
            $this->jsonOutPut(0, '你无权本操作');
        }
        $authorInfo = Authors::getOne($this->userInfo['authorId']);
        if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '你无权本操作');
        } elseif (md5($passwd . $authorInfo['hashCode']) != $authorInfo['password']) {
            $this->jsonOutPut(0, '密码错误，请重试');
        }
        if (Books::model()->updateByPk($id, array('status' => Posts::STATUS_DELED))) {
            //更新小说信息
            Books::updateBookStatInfo($id);
            //更新作者信息
            Authors::updateStatInfo($authorInfo);
            //记录用户操作
            $jsonData = CJSON::encode(array(
                        'bid' => $bookInfo['id'],
                        'bTitle' => $bookInfo['title'],
                        'bDesc' => $bookInfo['desc'],
                        'bFaceImg' => $bookInfo['faceImg'],
            ));
            $attr = array(
                'uid' => $this->uid,
                'logid' => $bookInfo['id'],
                'classify' => $powerAction,
                'data' => $jsonData,
                'action' => $powerAction,
                'score' => $powerInfo['msg']['score'],
                'display' => 0,
            );
            if (UserAction::simpleRecord($attr)) {
                //判断本操作是否同属任务
                Task::addTaskLog($this->userInfo, $powerAction);
            }
            $this->jsonOutPut(1, '已删除');
        } else {
            $this->jsonOutPut(1, '删除失败');
        }
    }

    private function publishChapter() {
        $this->checkLogin();
        $this->checkUserStatus();
        $id = zmf::val('id', 2);
        if (!$id) {
            $this->jsonOutPut(0, '缺少参数哦~');
        }
        //获取用户组的权限
        $powerAction = 'publishChapter';
        $powerInfo = GroupPowers::checkPower($this->userInfo, $powerAction);
        if (!$powerInfo['status']) {
            $this->jsonOutPut(0, $powerInfo['msg']);
        }
        $chapterInfo = Chapters::getOne($id);
        $bookInfo = Books::getOne($chapterInfo['bid']);
        if (!$bookInfo || $bookInfo['status'] != Posts::STATUS_PASSED) {
            if ($bookInfo && $bookInfo['status'] == Books::STATUS_STAYCHECK) {
                $this->jsonOutPut(0, '小说已被锁定，不能再发表章节！');
            }
            $this->jsonOutPut(0, '小说不存在或已删除，不能再发表章节！');
        } elseif ($bookInfo['uid'] != $this->uid || $bookInfo['aid'] != $this->userInfo['authorId']) {
            $this->jsonOutPut(0, '你无权本操作！');
        } elseif (!$bookInfo['iAgree']) {
            $this->jsonOutPut(0, '请先同意本站协议');
        }
        $authorInfo = Authors::getOne($this->userInfo['authorId']);
        if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '你无权本操作');
        }
        if (!$chapterInfo || $chapterInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '操作对象不存在，请核实');
        } elseif ($chapterInfo['uid'] != $this->uid || $chapterInfo['aid'] != $this->userInfo['authorId']) {
            $this->jsonOutPut(0, '你无权本操作');
        } elseif ($chapterInfo['chapterStatus'] == Books::STATUS_PUBLISHED) {
            $this->jsonOutPut(1, '已发表');
        } elseif ($chapterInfo['chapterStatus'] == Books::STATUS_STAYCHECK) {
            $this->jsonOutPut(0, '该章节待审核，请审核通过后再发表！');
        }
        if (!Authors::checkLogin($this->userInfo, $chapterInfo['aid'])) {
            $this->jsonOutPut(0, '你无权本操作');
        }
        if (Chapters::model()->updateByPk($id, array('chapterStatus' => Books::STATUS_PUBLISHED))) {
            Books::updateBookStatInfo($chapterInfo['bid']);
            Authors::updateStatInfo($authorInfo);
            //记录用户操作
            $jsonData = CJSON::encode(array(
                        'cid' => $chapterInfo['id'],
                        'cTitle' => $chapterInfo['title'],
            ));
            $attr = array(
                'uid' => $this->uid,
                'logid' => $chapterInfo['id'],
                'classify' => $powerAction,
                'data' => $jsonData,
                'action' => $powerAction,
                'score' => $powerInfo['msg']['score'],
                'display' => 0,
            );
            if (UserAction::simpleRecord($attr)) {
                //判断本操作是否同属任务
                Task::addTaskLog($this->userInfo, $powerAction);
            }
            $this->jsonOutPut(1, '已发表');
        } else {
            $this->jsonOutPut(1, '已发表');
        }
    }

    private function delChapter() {
        $this->checkLogin();
        $this->checkUserStatus();
        $id = zmf::val('cid', 2);
        $passwd = zmf::val('passwd', 1);
        if (!$id) {
            $this->jsonOutPut(0, '缺少参数哦~');
        }
        if (!$passwd) {
            $this->jsonOutPut(0, '请输入密码');
        }
        //获取用户组的权限
        $powerAction = 'delChapter';
        $powerInfo = GroupPowers::checkPower($this->userInfo, $powerAction);
        if (!$powerInfo['status']) {
            $this->jsonOutPut(0, $powerInfo['msg']);
        }
        $chapterInfo = Chapters::getOne($id);
        if (!$chapterInfo) {
            $this->jsonOutPut(0, '操作对象不存在，请核实');
        } elseif ($chapterInfo['uid'] != $this->uid || $chapterInfo['aid'] != $this->userInfo['authorId']) {
            $this->jsonOutPut(0, '你无权本操作');
        } elseif ($chapterInfo['status'] == Posts::STATUS_DELED) {
            $this->jsonOutPut(1, '已删除');
        }
        $authorInfo = Authors::getOne($this->userInfo['authorId']);
        if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '你无权本操作');
        }
        if (!Authors::checkLogin($this->userInfo, $chapterInfo['aid'])) {
            $this->jsonOutPut(0, '你无权本操作');
        } elseif (md5($passwd . $authorInfo['hashCode']) != $authorInfo['password']) {
            $this->jsonOutPut(0, '密码错误，请重试');
        }
        if (Chapters::model()->updateByPk($id, array('status' => Posts::STATUS_DELED))) {
            Books::updateBookStatInfo($chapterInfo['bid']);
            Authors::updateStatInfo($authorInfo);
            //记录用户操作
            $jsonData = CJSON::encode(array(
                        'cid' => $id,
                        'cTitle' => $chapterInfo['title'],
                        'bid' => $chapterInfo['bid'],
            ));
            $attr = array(
                'uid' => $this->uid,
                'logid' => $id,
                'classify' => $powerAction,
                'data' => $jsonData,
                'action' => $powerAction,
                'score' => $powerInfo['msg']['score'],
                'display' => 0,
            );
            if (UserAction::simpleRecord($attr)) {
                //判断本操作是否同属任务
                Task::addTaskLog($this->userInfo, $powerAction);
            }
            $this->jsonOutPut(1, '已删除');
        } else {
            $this->jsonOutPut(0, '删除失败');
        }
    }

    private function saveDraft() {
        $this->checkLogin();
        $title = zmf::val('title', 1);
        $content = zmf::val('content', 1);
        $hash = zmf::val('hash', 1);
        $bookId = zmf::val('bookId', 2);
        if (!$bookId) {
            $this->jsonOutPut(0, '缺少参数，请选择小说~');
        }
        $attr = array(
            'uid' => $this->uid,
            'aid' => $this->userInfo['authorId'],
            'bid' => $bookId,
            'uuid' => $hash,
        );
        $draftInfo = Drafts::model()->findByAttributes($attr);
        $attr['title'] = $title;
        $attr['content'] = $content;
        $attr['cTime'] = zmf::now();
        if ($draftInfo) {
            if ($draftInfo['title'] == $title && $draftInfo['content'] == $content) {
                $this->jsonOutPut(1, '已自动保存');
            }
            if (Drafts::model()->updateByPk($draftInfo['id'], $attr)) {
                $this->jsonOutPut(1, '已自动保存');
            } else {
                $this->jsonOutPut(0, '保存草稿失败');
            }
        }
        $model = new Drafts();
        $model->attributes = $attr;
        if ($model->save()) {
            $this->jsonOutPut(1, '已自动保存');
        } else {
            $this->jsonOutPut(0, '保存草稿失败');
        }
    }

    private function report() {
        $data = array();
        $logid = zmf::val('logid', 2);
        $type = zmf::val('type', 1);
        $desc = zmf::val('reason', 1);
        $contact = zmf::val('contact', 1);
        $url = zmf::val('url', 1);
        $allowType = array('book', 'chapter', 'tip', 'comment', 'post', 'user', 'author');
        if (!in_array($type, $allowType)) {
            $this->jsonOutPut(0, '暂不允许的分类');
        }
        if (!$logid) {
            $this->jsonOutPut(0, '缺少参数');
        }
        //一个小时内最多只能对同一对象举报4次
        if (zmf::actionLimit('report', $type . '-' . $logid, 3, 3600)) {
            $this->jsonOutPut(0, '我们已收到你的举报，请勿频繁操作');
        }
        //获取用户组的权限
        $powerAction = 'addReport';
        $powerInfo = GroupPowers::checkPower($this->userInfo, $powerAction);
        if (!$powerInfo['status']) {
            $this->jsonOutPut(0, $powerInfo['msg']);
        }
        $data['logid'] = $logid;
        $data['classify'] = $type;
        $info = false;
        if ($this->uid) {
            $data['uid'] = $this->uid;
            $info = Reports::model()->findByAttributes($data);
        }
        if ($info) {
            $data['desc'] = $info['desc'] . $desc;
            $data['contact'] = $info['contact'] . $contact;
            $data['status'] = Posts::STATUS_STAYCHECK;
            $data['times'] = $info['times'] + 1;
            $data['cTime'] = zmf::now();
            if (Reports::model()->updateByPk($info['id'], $data)) {
                //记录用户操作
                $jsonData = CJSON::encode(array(
                            'logid' => $logid,
                            'classify' => $type,
                            'desc' => $data['desc'],
                            'contact' => $data['contact'],
                ));
                $attr = array(
                    'uid' => $this->uid,
                    'logid' => $info['id'],
                    'classify' => $powerAction,
                    'data' => $jsonData,
                    'action' => $powerAction,
                    'score' => $powerInfo['msg']['score'],
                    'display' => 0,
                );
                if (UserAction::simpleRecord($attr)) {
                    //判断本操作是否同属任务
                    Task::addTaskLog($this->userInfo, $powerAction);
                }
                $this->jsonOutPut(1, '感谢你的举报');
            }
            $this->jsonOutPut(1, '感谢你的举报');
        } else {
            $data['url'] = $url;
            $data['desc'] = $desc;
            $data['contact'] = $contact;
            $data['status'] = Posts::STATUS_STAYCHECK;
            $data['times'] = 1;
            $fm = new Reports();
            $fm->attributes = $data;
            if ($fm->save()) {
                //记录用户操作
                $jsonData = CJSON::encode(array(
                            'logid' => $logid,
                            'classify' => $type,
                            'desc' => $data['desc'],
                            'contact' => $data['contact'],
                ));
                $attr = array(
                    'uid' => $this->uid,
                    'logid' => $info['id'],
                    'classify' => $powerAction,
                    'data' => $jsonData,
                    'action' => $powerAction,
                    'score' => $powerInfo['msg']['score'],
                    'display' => 0,
                );
                if (UserAction::simpleRecord($attr)) {
                    //判断本操作是否同属任务
                    Task::addTaskLog($this->userInfo, $powerAction);
                }
                $this->jsonOutPut(1, '感谢你的举报');
            } else {
                $this->jsonOutPut(0, '举报失败，请稍后重试');
            }
        }
    }

    /**
     * 发送短信
     */
    private function sendSms() {
        $phone = zmf::val('phone', 2);
        $type = zmf::val('type', 1);
        if (!$phone) {
            $this->jsonOutPut(0, '请输入手机号');
        }
        if (!in_array($type, array('reg', 'forget', 'exphone', 'checkPhone', 'authorPass'))) {
            $this->jsonOutPut(0, '不被允许的类型:' . $type);
        } elseif ($type == 'checkPhone') {
            $this->checkLogin();
            if ($this->userInfo['phone'] > 0) {
                $phone = $this->userInfo['phone'];
            } else {
                $_ckinfo = Users::findByPhone($phone);
                if ($_ckinfo) {
                    $this->jsonOutPut(0, '该手机号已被使用');
                }
                $this->userInfo['phone'] = $phone;
            }
            if (!$phone) {
                $this->jsonOutPut(0, '参数错误，缺少手机号');
            }
        } elseif ($type == 'authorPass') {
            $this->checkLogin();
            $phone = $this->userInfo['phone'];
            if (!$phone) {
                $this->jsonOutPut(0, '参数错误，缺少手机号');
            }
            if (!$this->userInfo['authorId']) {
                $this->jsonOutPut(0, '你无权本操作');
            } elseif (!$this->userInfo['phoneChecked']) {
                $this->jsonOutPut(0, '请先验证手机号');
            }
            $authorInfo = Authors::getOne($this->userInfo['authorId']);
            if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
                $this->jsonOutPut(0, '你创建的作者不存在或已删除');
            }
        } else {
            if (!zmf::checkPhoneNumber($phone)) {
                $this->jsonOutPut(0, '请输入正确的手机号');
            }
        }
        $now = zmf::now();
        $sendInfo = Msg::model()->find('phone=:phone AND type=:type AND expiredTime>=:now AND status=0', array(':phone' => $phone, ':type' => $type, ':now' => $now));
        if ($sendInfo) {
            $params = array(
                'phone' => $phone,
                'code' => $sendInfo['code'],
                'template' => Msg::returnTemplate($type),
            );
            $status = Msg::sendOne($params);
            if ($status) {
                $this->jsonOutPut(1, '发送成功');
            } else {
                $this->jsonOutPut(0, '操作太频繁，请稍安勿躁');
            }
        }
        if ($type == 'exphone') {
            $this->checkLogin();
            if ($this->userInfo['phone'] == $phone) {
                $this->jsonOutPut(0, '该号码已被使用');
            } else {
                $info = Users::model()->find('phone=:p', array(':p' => $phone));
                if ($info) {
                    $this->jsonOutPut(0, '该号码已被使用');
                } elseif ($info && $info['status'] != Posts::STATUS_PASSED) {
                    $this->jsonOutPut(0, '该用户禁止访问');
                }
            }
            $this->userInfo['phone'] = $phone;
        } elseif ($type == 'forget') {
            //如果已经登录时则认为是修改密码，只能输入自己的手机号
            if ($this->uid && $phone != $this->userInfo['phone']) {
                $this->jsonOutPut(0, '号码有误，请重新输入');
            }
            $info = Users::model()->find('phone=:p', array(':p' => $phone));
            if (!$info) {
                $this->jsonOutPut(0, '该号码尚未注册');
            } elseif ($info['status'] != Posts::STATUS_PASSED) {
                $this->jsonOutPut(0, '该用户禁止访问');
            }
            $this->userInfo['phone'] = $phone;
        } elseif ($type == 'checkPhone' || $type == 'authorPass') {
            
        } else {
            if ($type == 'reg') {
                //验证手机号是否已被注册
                $info = Users::model()->find('phone=:p', array(':p' => $phone));
                if ($info) {
                    $this->jsonOutPut(0, '该手机号已被注册');
                }
            }
            $this->userInfo['phone'] = $phone;
        }
        $count = Msg::statByPhone($phone);
        if ($count >= 20) {
            $this->jsonOutPut(0, '您今天的短信次数已用完');
        }
        //将该手机号及该操作下的所有短信置为已过期
        //Msg::model()->updateAll(array('status' => -1), 'phone=:p AND type=:type AND status=0', array(':p' => $phone, ':type' => $type));
        //发送一条短信验证码
        $res = Msg::initSend($this->userInfo, $type);
        zmf::fp($res, 1);
        if ($res) {
            if ($type == 'exphone') {
                //记录操作
                //UserLog::add($this->uid, '发送短信请求更换手机');
            } elseif ($type == 'forget') {
                //记录操作
                //UserLog::add($this->uid, '发送短信请求找回密码');
            }
            $this->jsonOutPut(1, '发送成功');
        } else {
            $this->jsonOutPut(0, '发送失败');
        }
    }

    /**
     * 短信验证
     */
    private function checkSms() {
        $phone = zmf::val('phone', 2);
        $code = zmf::val('code', 2);
        $type = zmf::val('type', 1);
        if (!$code) {
            $this->jsonOutPut(0, '请输入验证码');
        }
        if (!in_array($type, array('reg', 'forget', 'exphone', 'checkPhone', 'authorPass'))) {
            $this->jsonOutPut(0, '不被允许的类型');
        }
        if (!$phone) {
            $this->jsonOutPut(0, '请输入手机号');
        } elseif ($type == 'checkPhone') {
            $this->checkLogin();
            if ($this->userInfo['phone'] > 0) {
                $phone = $this->userInfo['phone'];
            } else {
                $_ckinfo = Users::findByPhone($phone);
                if ($_ckinfo) {
                    $this->jsonOutPut(0, '该手机号已被使用');
                }
                $this->userInfo['phone'] = $phone;
            }
            if (!$phone) {
                $this->jsonOutPut(0, '参数错误，缺少手机号');
            }
        } elseif ($type == 'authorPass') {
            $this->checkLogin();
            $phone = $this->userInfo['phone'];
            if (!$phone) {
                $this->jsonOutPut(0, '参数错误，缺少手机号');
            }
            if (!$this->userInfo['authorId']) {
                $this->jsonOutPut(0, '你无权本操作');
            } elseif (!$this->userInfo['phoneChecked']) {
                $this->jsonOutPut(0, '请先验证手机号');
            }
            $authorInfo = Authors::getOne($this->userInfo['authorId']);
            if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
                $this->jsonOutPut(0, '你创建的作者不存在或已删除');
            }
        } else {
            if (!zmf::checkPhoneNumber($phone)) {
                $this->jsonOutPut(0, '请输入正确的手机号');
            }
        }
        $now = zmf::now();
        //查询是否有已发送记录
        if ($type == 'exphone') {
            $this->checkLogin();
            $info = Msg::model()->find('uid=:uid AND phone=:p AND type=:t AND code=:code AND expiredTime>=:now', array(':uid' => $this->uid, ':p' => $phone, ':t' => $type, ':code' => $code, ':now' => $now));
        } elseif ($type == 'reg') {
            $uinfo = Users::model()->find('phone=:p', array(':p' => $phone));
            if ($uinfo) {
                $this->jsonOutPut(0, '该手机号已被注册');
            }
            $info = Msg::model()->find('phone=:p AND type=:t AND code=:code', array(':p' => $phone, ':t' => $type, ':code' => $code));
        } elseif ($type == 'checkPhone') {
            $info = Msg::model()->find('phone=:p AND type=:t AND code=:code', array(':p' => $phone, ':t' => $type, ':code' => $code));
        } elseif ($type == 'forget') {
            $password = zmf::val('password', 1);
            if (!$password || strlen($password) < 6) {
                $this->jsonOutPut(0, '请输入长度不小于6位的有效密码');
            }
            //如果已经登录时则认为是修改密码，只能输入自己的手机号
            if ($this->uid && $phone != $this->userInfo['phone']) {
                $this->jsonOutPut(0, '号码有误，请重新输入');
            }
            $uinfo = Users::model()->find('phone=:p', array(':p' => $phone));
            if (!$uinfo) {
                $this->jsonOutPut(0, '该号码尚未注册');
            }
            $info = Msg::model()->find('phone=:p AND type=:t AND code=:code', array(':p' => $phone, ':t' => $type, ':code' => $code));
        } elseif ($type == 'authorPass') {
            $password = zmf::val('password', 1);
            if (!$password || strlen($password) < 6) {
                $this->jsonOutPut(0, '请输入长度不小于6位的有效密码');
            }
            //如果已经登录时则认为是修改密码，只能输入自己的手机号
            if ($phone != $this->userInfo['phone']) {
                $this->jsonOutPut(0, '号码有误，请重新输入');
            }
            $info = Msg::model()->find('phone=:p AND type=:t AND code=:code', array(':p' => $phone, ':t' => $type, ':code' => $code));
        }
        if (!$info) {
            $this->jsonOutPut(0, '验证码错误，请重试');
        } elseif ($info['expiredTime'] < $now) {
            $this->jsonOutPut(0, '验证码已过期，请重新发送');
        }
        if ($type == 'exphone') {
            //相等以后则修改用户手机号
            Users::model()->updateByPk($this->uid, array('phone' => $phone));
            //更新缓存
            zmf::delFCache("userInfo-{$this->uid}");
            $this->jsonOutPut(1, Yii::app()->createUrl('users/setting'));
        }
        Msg::model()->updateByPk($info['id'], array('status' => 1));
        if ($type == 'checkPhone') {
            Users::model()->updateByPk($this->uid, array('phoneChecked' => 1, 'phone' => $phone));
            $returnCode = Yii::app()->createUrl('user/index');
        } elseif ($type == 'forget') {
            if (Users::updateInfo($uinfo['id'], 'password', md5($password))) {
                $this->jsonOutPut(1, '密码修改成功，正在跳转至登录页面');
            } else {
                $this->jsonOutPut(1, '密码修改成功，正在跳转至登录页面');
            }
        } elseif ($type == 'authorPass') {
            if (Authors::model()->updateByPk($this->userInfo['authorId'], array('password' => md5($password . $authorInfo['hashCode'])))) {
                $this->jsonOutPut(1, Yii::app()->createUrl('user/authorAuth'));
            } else {
                $this->jsonOutPut(0, '密码修改失败，未知错误');
            }
        } else {
            //验证通过，将验证码标记为已使用
            $returnCode = zmf::jiaMi($phone . '#' . $type . '#' . zmf::now());
        }
        $this->jsonOutPut(1, $returnCode);
    }

    private function getNotice() {
        $this->checkLogin();
        $noticeNum = Notification::getNum();
        $this->jsonOutPut(1, $noticeNum);
    }

    private function dapipi() {
        $this->checkLogin();
        $bid = zmf::val('k', 2);
        if (zmf::actionLimit('daTapipi', 'book-' . $bid, 3, 3600)) {
            $this->jsonOutPut(0, '我们已收到你的催更请求，请勿频繁操作');
        }
        $ckInfo = Dapipi::daTapipi($bid, $this->userInfo);
        if (!$ckInfo['status']) {
            $this->jsonOutPut($ckInfo['status'], $ckInfo['msg']);
        }
        $this->jsonOutPut($ckInfo['status'], $ckInfo['msg']);
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
        if ($this->uid) {
            //获取用户组的权限
            $powerAction = 'feedback';
            $powerInfo = GroupPowers::checkPower($this->userInfo, $powerAction);
            if (!$powerInfo['status']) {
                $this->jsonOutPut(0, $powerInfo['msg']);
            }
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
                if ($this->uid) {
                    //记录用户操作
                    $jsonData = CJSON::encode(array(
                                'contact' => $attr['contact'],
                                'content' => $content,
                    ));
                    $attr = array(
                        'uid' => $this->uid,
                        'logid' => $model->id,
                        'classify' => $powerAction,
                        'data' => $jsonData,
                        'action' => $powerAction,
                        'score' => $powerInfo['msg']['score'],
                        'display' => 0,
                    );
                    if (UserAction::simpleRecord($attr)) {
                        //判断本操作是否同属任务
                        Task::addTaskLog($this->userInfo, $powerAction);
                    }
                }
                $this->jsonOutPut(1, '感谢你的反馈');
            } else {
                $this->jsonOutPut(1, '感谢你的反馈');
            }
        } else {
            $this->jsonOutPut(0, '反馈失败，请重试');
        }
    }

    public function actionAddComment() {
        $this->checkLogin();
        $this->checkUserStatus();
        $keyid = zmf::val('k', 2);
        $to = zmf::val('to', 2);
        $type = zmf::val('t', 1);
        $isAuthor = zmf::val('isAuthor', 2);
        $content = zmf::val('c', 1);
        if (!isset($type) OR ! in_array($type, array('posts', 'tipComments', 'postPosts'))) {
            $this->jsonOutPut(0, Yii::t('default', 'forbiddenaction'));
        }
        if ($type == 'posts') {
            $powerAction = 'commentPost';
        } elseif ($type == 'tipComments') {
            $powerAction = 'commentChapterTip';
        } elseif ($type == 'postPosts') {
            $powerAction = 'commentPost';
        }
        if (!isset($keyid) OR ! is_numeric($keyid)) {
            $this->jsonOutPut(0, Yii::t('default', 'pagenotexists'));
        }
        if (!$content) {
            $this->jsonOutPut(0, '评论不能为空哦~');
        }
        $powerInfo = GroupPowers::checkPower($this->userInfo, $powerAction);
        if (!$powerInfo['status']) {
            $this->jsonOutPut(0, $powerInfo['msg']);
        }
        $uid = $this->uid;
        if ($type == 'posts') {
            $postInfo = Posts::model()->findByPk($keyid);
            if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
                $this->jsonOutPut(0, '你所评论的内容不存在');
            } elseif ($postInfo['open'] != Posts::STATUS_OPEN) {
                $this->jsonOutPut(0, '评论功能已关闭');
            }
        } elseif ($type == 'tipComments') {
            $postInfo = Tips::model()->findByPk($keyid);
            if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
                $this->jsonOutPut(0, '你所评论的内容不存在');
            }
            $type = 'tip';
        } elseif ($type == 'postPosts') {
            $postInfo = PostPosts::model()->findByPk($keyid);
            if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
                $this->jsonOutPut(0, '你所评论的楼层不存在');
            } elseif ($postInfo['open'] != Posts::STATUS_OPEN) {
                $this->jsonOutPut(0, '楼层已关闭评论');
            }
        }
        //处理使用作者身份回复
        $authorId = 0;
        $authorInfo = array();
        if ($isAuthor) {
            if (!$this->userInfo['authorId'] || !Authors::checkLogin($this->userInfo, $this->userInfo['authorId'])) {
                $this->jsonOutPut(0, '登录作者中心后才能以作者身份评论或回复');
            }
            $authorInfo = Authors::getOne($this->userInfo['authorId']);
            if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
                $this->jsonOutPut(0, '作者信息不存在');
            }
            $authorId = $this->userInfo['authorId'];
        }
        //处理文本，不是富文本
        $filter = Posts::handleContent($content, FALSE);
        $content = $filter['content'];
        $status = $filter['status'];
        $model = new Comments();
        $toNotice = true;
        $replyInfo = array();
        $touid = $postInfo['uid'];

        if ($to) {
            $comInfo = Comments::model()->findByPk($to);
            if (!$comInfo || $comInfo['status'] != Posts::STATUS_PASSED || !$comInfo['uid']) {
                $to = '';
            } elseif ($comInfo['uid'] == $uid) {
                $toNotice = false;
                $to = '';
            } else {
                $toUserInfo = Users::getOne($comInfo['uid']);
                if ($toUserInfo['status'] == Posts::STATUS_PASSED) {
                    $touid = $comInfo['uid'];
                    $toNotice = true;
                    $replyInfo = array(
                        'username' => $toUserInfo['truename'],
                        'linkArr' => array('user/index', 'id' => $toUserInfo['id']),
                    );
                } else {
                    $this->jsonOutPut(0, '对方账户信息不存在');
                }
                if ($comInfo['aid']) {
                    $authorInfo = Authors::getOne($comInfo['aid']);
                    if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
                        $this->jsonOutPut(0, '对方账户信息不存在');
                    }
                    $replyInfo = array(
                        'username' => $authorInfo['authorName'],
                        'linkArr' => array('author/view', 'id' => $authorInfo['id']),
                    );
                }
            }
        }
        $intoData = array(
            'logid' => $keyid,
            'uid' => $uid,
            'content' => $content,
            'cTime' => zmf::now(),
            'classify' => $type,
            'platform' => $this->isMobile ? Posts::PLATFORM_MOBILE : Posts::PLATFORM_WEB,
            'tocommentid' => $to,
            'status' => $status,
            'aid' => $authorId,
            'favors' => 0,
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
                    $_content = '评论了你的帖子【' . zmf::subStr($postInfo['title']) . '】,' . $_url;
                } elseif ($type == 'tip') {
                    $_url = CHtml::link('查看详情', array('book/chapter', 'cid' => $postInfo['logid']));
                    if ($status == Posts::STATUS_PASSED) {
                        Posts::updateCount($keyid, 'Tips', 1, 'comments');
                    }
                    $_content = '评论了你的点评【' . zmf::subStr($postInfo['content']) . '】,' . $_url;
                } elseif ($type == 'postPosts') {
                    $_url = CHtml::link('查看详情', array('posts/view', 'id' => $postInfo['tid'], '#' => 'reply-' . $keyid));
                    if ($status == Posts::STATUS_PASSED) {
                        Posts::updateCount($keyid, 'PostPosts', 1, 'comments');
                    }
                    $_content = '评论了你的楼层【' . zmf::subStr($postInfo['content']) . '】,' . $_url;
                }
                if ($to && $_url) {
                    $_content = '回复了你的评论【' . zmf::subStr($comInfo['content']) . '】,' . $_url;
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
                $intoData['userInfo']['username'] = !empty($authorInfo) ? $authorInfo['authorName'] : $this->userInfo['truename'];
                $intoData['userInfo']['linkArr'] = !empty($authorInfo) ? array('author/view', 'id' => $this->userInfo['authorId']) : array('user/index', 'id' => $this->uid);
                $intoData['replyInfo'] = $replyInfo;
                $html = $this->renderPartial('/posts/_comment', array('data' => $intoData, 'postInfo' => $postInfo), true);
                //记录用户操作
                $jsonData = CJSON::encode(array(
                            'keyid' => $keyid,
                            'classify' => $type
                ));
                $attr = array(
                    'uid' => $this->uid,
                    'logid' => $model->id,
                    'classify' => $powerAction,
                    'data' => $jsonData,
                    'action' => $powerAction,
                    'score' => $powerInfo['msg']['score'],
                    'display' => 0,
                );
                if (UserAction::simpleRecord($attr)) {
                    //判断本操作是否同属任务
                    Task::addTaskLog($this->userInfo, $powerAction);
                }
                $this->jsonOutPut(1, $html);
            } else {
                $this->jsonOutPut(0, '新增评论失败');
            }
        } else {
            $this->jsonOutPut(0, '新增评论失败');
        }
    }

    private function getContents() {
        $id = zmf::val('data', 2);
        $page = zmf::val('page', 2);
        $type = zmf::val('type', 1);
        if (!$id || !$type) {
            $this->jsonOutPut(0, '数据不全，请核实');
        }
        if (!in_array($type, array('tipComments', 'postComments', 'postPosts', 'props'))) {
            $this->jsonOutPut(0, '暂不允许的分类');
        }
        if ($page < 1 || !is_numeric($page)) {
            $page = 1;
        }
        $longHtml = $from = '';
        $showFormHtml = $showAvatar = false;
        $bookInfo = $postInfo = array();
        switch ($type) {
            case 'tipComments':
                $postInfo = Tips::model()->findByPk($id);
                if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
                    $this->jsonOutPut(0, '你所评论的内容不存在');
                }
                $bookInfo = Books::getOne($postInfo['bid']);
                if (!$bookInfo || $bookInfo['status'] != Posts::STATUS_PASSED) {
                    $this->jsonOutPut(0, '你所评论的小说不存在');
                };
                $posts = Comments::getCommentsByPage($id, $this->uid, 'tip', $page, $this->pageSize);
                $view = '/posts/_comment';
                $from = 'tip';
                $showFormHtml = true;
                break;
            case 'postPosts':
                $postInfo = PostPosts::model()->findByPk($id);
                if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
                    $this->jsonOutPut(0, '你所评论的内容不存在');
                }
                $posts = Comments::getCommentsByPage($id, $this->uid, 'postPosts', $page, $this->pageSize);
                $view = '/posts/_comment';
                $from = 'postPosts';
                $showFormHtml = $postInfo['open'] == PostPosts::OPEN_COMMENT;
                break;
            case 'postComments':
                $postInfo = Posts::model()->findByPk($id);
                if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
                    $this->jsonOutPut(0, '你所评论的内容不存在');
                }
                $posts = Comments::getCommentsByPage($id, $this->uid, 'posts', $page, $this->pageSize, "c.id,c.uid,u.truename,u.avatar,c.aid,c.logid,c.tocommentid,c.content,c.cTime,c.status");
                $view = '/posts/_comment';
                $from = 'post';
                $showAvatar = true;
                break;
            case 'props':
                $this->checkLogin();
                $sql = "SELECT p.id,o.title,o.faceUrl,p.classify,p.action,p.from,p.to,p.num FROM {{orders}} o,{{props}} p WHERE p.uid='{$this->userInfo['id']}' AND o.uid='{$this->userInfo['id']}' AND p.gid=o.gid ORDER BY p.updateTime DESC";
                $posts = Posts::getByPage(array(
                            'sql' => $sql,
                            'page' => $this->page,
                            'pageSize' => $this->pageSize,
                ));
                foreach ($posts as $k => $val) {
                    $posts[$k]['faceUrl'] = zmf::getThumbnailUrl($val['faceUrl'], 'a120', 'goods');
                }
                $view = '/user/_prop';
                $from = 'post';
                $showAvatar = true;
                break;
            default:
                $posts = array();
                break;
        }
        if (!empty($posts)) {
            foreach ($posts as $k => $row) {
                $longHtml.=$this->renderPartial($view, array('data' => $row, 'k' => $k, 'postInfo' => $postInfo, 'bookInfo' => $bookInfo, 'from' => $from, 'showAvatar' => $showAvatar), true);
            }
        }
        $data = array(
            'html' => $longHtml,
            'loadMore' => (count($posts) == $this->pageSize) ? 1 : 0,
            'formHtml' => $showFormHtml ? $this->renderPartial('/posts/_addComment', array('type' => $type, 'keyid' => $id, 'authorPanel' => ($this->userInfo['authorId'] > 0 && $bookInfo['aid'] == $this->userInfo['authorId']), 'authorLogin' => Authors::checkLogin($this->userInfo, $this->userInfo['authorId'])), true) : ''
        );
        $this->jsonOutPut(1, $data);
    }

    private function delContent() {
        $this->checkLogin();
        $data = zmf::val('data', 2);
        $type = zmf::val('type', 1);
        if (!$data || !$type) {
            $this->jsonOutPut(0, '数据不全，请核实');
        }
        if (!in_array($type, array('comment', 'post', 'notice', 'tag', 'img', 'tip'))) {
            $this->jsonOutPut(0, '暂不允许的分类');
        }
        $status = Posts::STATUS_DELED;
        switch ($type) {
            case 'comment':
                $info = Comments::model()->findByPk($data);
                if (!$info) {
                    $this->jsonOutPut(0, '你所查看的内容不存在');
                } elseif ($info['uid'] != $this->uid) {
                    if ($this->checkPower('delComment', $this->uid, true)) {
                        //我是管理员，我就可以删除
                        $status = Posts::STATUS_STAYCHECK;
                    } else {
                        $this->jsonOutPut(0, '你无权操作');
                    }
                }
                if (Comments::model()->updateByPk($data, array('status' => $status))) {
                    if ($info['classify'] == 'posts') {
                        Posts::updateCount($info['logid'], 'Posts', -1, 'comments');
                    } elseif ($info['classify'] == 'tip') {
                        Posts::updateCount($info['logid'], 'Tips', -1, 'comments');
                    }
                    $this->jsonOutPut(1, '已删除');
                }
                $this->jsonOutPut(1, '已删除');
                break;
            case 'post':
                $info = Posts::model()->findByPk($data);
                if (!$info) {
                    $this->jsonOutPut(0, '你所查看的内容不存在');
                } elseif ($info['uid'] != $this->uid) {
                    if ($this->checkPower('delPost', $this->uid, true)) {
                        //我是管理员，我就可以删除
                        $status = Posts::STATUS_STAYCHECK;
                    } else {
                        $this->jsonOutPut(0, '你无权操作');
                    }
                }
                if (Posts::model()->updateByPk($data, array('status' => $status))) {
                    $this->jsonOutPut(1, '已删除');
                }
                $this->jsonOutPut(1, '已删除');
                break;
            case 'notice':
                if (!$data || !is_numeric($data)) {
                    $this->jsonOutPut(0, '你所操作的内容不存在');
                }
                if (Notification::model()->deleteByPk($data)) {
                    $this->jsonOutPut(1, '已删除');
                }
                $this->jsonOutPut(1, '已删除');
                break;
            case 'img':
                $info = Attachments::model()->findByPk($data);
                if (!$info) {
                    $this->jsonOutPut(0, '你所查看的内容不存在');
                } elseif ($info['uid'] != $this->uid) {
                    $this->jsonOutPut(0, '你无权操作');
                }
                if (Attachments::model()->updateByPk($data, array('status' => Posts::STATUS_DELED))) {
                    $this->jsonOutPut(1, '已删除');
                }
                $this->jsonOutPut(1, '已删除');
                break;
            case 'tip':
                $info = Tips::model()->findByPk($data);
                if (!$info) {
                    $this->jsonOutPut(0, '你所查看的内容不存在');
                } elseif ($info['uid'] != $this->uid) {
                    $this->jsonOutPut(0, '你无权操作');
                }
                if (Tips::model()->updateByPk($data, array('status' => $status))) {
                    Books::updateScore($info['bid']);
                    $this->jsonOutPut(1, '已删除');
                }
                $this->jsonOutPut(1, '已删除');
                break;
            case 'tag':
                if (!$data || !is_numeric($data)) {
                    $this->jsonOutPut(0, '你所操作的内容不存在');
                }
                if (!$this->checkPower('delTag', $this->uid, true)) {
                    $this->jsonOutPut(0, '你无权操作');
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

    public function actionFavorite() {
        $data = zmf::val('data', 1);
        $type = zmf::val('type', 1);
        $ckinfo = Posts::favorite($data, $type, 'web', $this->userInfo);
        $this->jsonOutPut($ckinfo['state'], $ckinfo['msg']);
    }

    private function setStatus() {
        $type = zmf::val('type', 1);
        $id = zmf::val('id', 2);
        $action = zmf::val('actype', 1);
        if (!$type || !$id) {
            $this->jsonOutPut(0, '缺少参数');
        }
        if (!in_array($type, array('post'))) {
            $this->jsonOutPut(0, '不被允许的分类');
        }
        if (!in_array($action, array('top', 'red', 'bold', 'boldAndRed', 'lock'))) {
            $this->jsonOutPut(0, '不被允许的操作');
        }
        if (!$this->userInfo['isAdmin']) {
            $this->jsonOutPut(0, '无权本操作');
        }
        $postInfo = Posts::getOne($id);
        if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '操作的内容不存在');
        }
        if ($action == 'top') {
            $status = $postInfo['top'] > 0 ? 0 : 1;
            $filed = 'top';
        } elseif (in_array($action, array('red', 'bold', 'boldAndRed'))) {
            if ($action == 'red') {
                $_status = Posts::STATUS_RED;
            } elseif ($action == 'bold') {
                $_status = Posts::STATUS_BOLD;
            } else {
                $_status = Posts::STATUS_BOLDRED;
            }
            $status = $postInfo['styleStatus'] == $_status ? 0 : $_status;
            $filed = 'styleStatus';
        } elseif ($action == 'lock') {
            $filed = 'open';
            $status = $postInfo['open'] > 0 ? 0 : 1;
        }
        if (Posts::model()->updateByPk($id, array($filed => $status))) {
            $this->jsonOutPut(1, '已设置');
        } else {
            $this->jsonOutPut(0, '操作失败，请重试');
        }
    }

    private function joinGroup() {
        $this->checkLogin();
        if ($this->userInfo['groupid'] > 0) {
            $this->jsonOutPut(0, '你已选择过角色，请勿重复操作');
        }
        $gidStr = zmf::val('gid', 1);
        if (!$gidStr) {
            $this->jsonOutPut(0, '请选择你喜欢的角色');
        }
        $arr = Posts::decode($gidStr);
        if (!$arr['id'] || $arr['type'] != 'group') {
            $this->jsonOutPut(0, '请选择你喜欢的角色');
        }
        $gid = $arr['id'];
        $ginfo = Group::getOne($gid);
        if (!$ginfo || $ginfo['status'] != 1) {
            $this->jsonOutPut(0, '暂不能选择该角色');
        }
        if (Users::updateInfo($this->uid, 'groupid', $gid)) {
            $url = Yii::app()->createUrl('user/index');
            $this->jsonOutPut(1, $url);
        } else {
            $this->jsonOutPut(0, '未知错误，请稍后重试');
        }
    }

    private function userTasks() {
        $this->checkLogin();
        $this->checkUserStatus();
        $now = zmf::now();
        $sql = "SELECT t.id,t.title,t.faceImg FROM {{task}} t,{{group_tasks}} gt WHERE ((gt.startTime=0 OR gt.startTime<=:cTime) AND (gt.endTime=0 OR gt.endTime>=:cTime)) AND gt.gid=:gid AND gt.tid=t.id LIMIT 10";
        $res = Yii::app()->db->createCommand($sql);
        $res->bindValues(array(
            ':gid' => $this->userInfo['groupid'],
            ':cTime' => $now,
        ));
        $tasks = $res->queryAll();
        //取出已经参与的任务
        //todo，排除已完成的任务
        $logs = TaskLogs::model()->findAll(array(
            'condition' => 'uid=:uid',
            'params' => array(
                ':uid' => $this->uid
            ),
            'select' => 'id,tid'
        ));
        $logsArr = CHtml::listData($logs, 'id', 'tid');
        foreach ($tasks as $k => $val) {
            $tasks[$k]['action'] = Posts::encode($val['id'], 'joinTask');
            $receive = false; //是否已领取
            if (in_array($val['id'], $logsArr)) {
                $receive = true;
            }
            $tasks[$k]['receive'] = $receive;
        }
        $html = $this->renderPartial('/user/tasks', array('tasks' => $tasks), true);
        $this->jsonOutPut(1, $html);
    }

    private function joinTask($arr) {
        if (!$arr['id'] || !is_numeric($arr['id']) || $arr['type'] != 'joinTask') {
            $this->jsonOutPut(0, '参数有误，请核实');
        }
        //获取用户组的权限
        $powerAction = 'joinTask';
        $powerInfo = GroupPowers::checkPower($this->userInfo, $powerAction);
        if (!$powerInfo['status']) {
            $this->jsonOutPut(0, $powerInfo['msg']);
        }
        $id = $arr['id'];
        $logInfo = TaskLogs::checkInfo($this->uid, $id);
        if ($logInfo) {
            $this->jsonOutPut(0, '你已领取过，请勿重复操作');
        }
        $checkInfo = GroupTasks::getOneTask($this->userInfo, $id);
        if (!$checkInfo['status']) {
            $this->jsonOutPut(0, $checkInfo['msg']);
        }
        $taskInfo = $checkInfo['msg'];
        $attr = array(
            'tid' => $id,
            'times' => 0,
            'status' => 0,
            'score' => $taskInfo['score'],
        );
        $model = new TaskLogs;
        $model->attributes = $attr;
        if ($model->save()) {
            //已领取后要增加任务的参与人数
            Posts::updateCount($taskInfo['groupTaskId'], 'GroupTasks', 1, 'times');
            //更新任务的参与人数
            Posts::updateCount($id, 'Task', 1, 'times');
            //记录用户操作
            $jsonData = CJSON::encode(array(
                        'tid' => $taskInfo['id'],
                        'tTitle' => $taskInfo['title'],
                        'tDesc' => $taskInfo['desc'],
                        'tFaceImg' => $taskInfo['faceImg'],
            ));
            $attr = array(
                'uid' => $this->uid,
                'logid' => $taskInfo['id'],
                'classify' => $powerAction,
                'data' => $jsonData,
                'action' => $powerAction,
                'score' => $powerInfo['msg']['score'],
                'display' => 0,
            );
            if (UserAction::simpleRecord($attr)) {
                //判断本操作是否同属任务
                Task::addTaskLog($this->userInfo, $powerAction);
            }
            $this->jsonOutPut(1, '已领取');
        } else {
            $this->jsonOutPut(0, '领取失败，请稍后重试');
        }
    }

    private function gotoBuy() {
        $this->checkLogin();
        $this->checkUserStatus();
        $data = zmf::val('data', 1);
        if (!$data) {
            $this->jsonOutPut(0, '缺少参数');
        }
        $arr = Posts::decode($data);
        if ($arr['type'] != 'goToBuy') {
            $this->jsonOutPut(0, '参数有误');
        }
        $num = zmf::val('num', 2);
        if (!$num || $num < 1) {
            $this->jsonOutPut(0, '请选择购买数量');
        }
        $idStr = $arr['id'];
        $idStrArr = explode('@', $idStr);
        if (count($idStrArr) != 2 || !is_numeric($idStrArr[0]) || !in_array($idStrArr[1], array('score', 'gold'))) {
            $this->jsonOutPut(0, '参数有误');
        }
        //获取用户组的权限
        $powerInfo = GroupPowers::checkPower($this->userInfo, 'buyGoods');
        if (!$powerInfo['status']) {
            $this->jsonOutPut($powerInfo['status'], $powerInfo['msg']);
        }
        $id = $idStrArr[0];
        $actionType = $idStrArr[1];
        $return = Goods::detail($id);
        if (!$return['status']) {
            $this->jsonOutPut(0, $return['msg']);
        }
        $info = $return['msg'];
        if ($actionType == 'score' && $info['scorePrice'] == '0') {
            $this->jsonOutPut(0, '该商品不支持积分兑换');
        } elseif ($actionType == 'gold' && $info['goldPrice'] == '0') {
            $this->jsonOutPut(0, '该商品不支持金币兑换');
        } elseif ($actionType == 'score') {
            $perPrice = $info['scorePrice'];
            $label = '积分';
        } elseif ($actionType == 'gold') {
            $perPrice = $info['goldPrice'];
            $label = '金币';
        }
        $totalPrice = $perPrice * $num;
        $now = zmf::now();
        $passdata = zmf::jiaMi($id . '#' . $actionType . '#' . $perPrice . '#' . $num . '#' . $now);
        //判断财富值
        $enough = false;
        if (Users::checkWealth($this->uid, $actionType, $totalPrice)) {
            $enough = true;
        }
        $passData = array(
            'info' => $info,
            'label' => $label,
            'perPrice' => $perPrice,
            'num' => $num,
            'totalPrice' => $totalPrice,
            'passdata' => $passdata,
            'enough' => $enough,
        );
        $html = $this->renderPartial('/shop/_confirmDia', $passData, TRUE);
        $this->jsonOutPut($enough ? 1 : 2, $html);
    }

    private function confirmBuy() {
        $this->checkLogin();
        $this->checkUserStatus();
        $data = zmf::val('data', 1);
        $password = zmf::val('password', 1);
        if (!$data) {
            $this->jsonOutPut(0, '缺少参数');
        }
        if (!$password) {
            $this->jsonOutPut(0, '请输入账户密码');
        }
        $passData = zmf::jieMi($data);
        if (!$passData) {
            $this->jsonOutPut(0, '缺少参数');
        }
        $passDataArr = array_filter(explode('#', $passData));
        $now = zmf::now();
        if (count($passDataArr) != 5 || !is_numeric($passDataArr[0]) || !is_numeric($passDataArr[3]) || !is_numeric($passDataArr[4]) || !in_array($passDataArr[1], array('score', 'gold'))) {
            $this->jsonOutPut(0, '参数错误');
        } elseif ($now - $passDataArr[4] > 3600) {
            $this->jsonOutPut(0, '停留在本页面的时间过长，请刷新');
        } elseif ($passDataArr[3] < 1) {
            $this->jsonOutPut(0, '请设置购买数量');
        }
        //获取用户组的权限
        $powerInfo = GroupPowers::checkPower($this->userInfo, 'buyGoods');
        if (!$powerInfo['status']) {
            $this->jsonOutPut($powerInfo['status'], $powerInfo['msg']);
        }
        //判断商品
        $id = $passDataArr[0];
        $actionType = $passDataArr[1];
        $num = $passDataArr[3];
        $return = Goods::detail($id);
        if (!$return['status']) {
            $this->jsonOutPut(0, $return['msg']);
        }
        $info = $return['msg'];
        if ($actionType == 'score' && $info['scorePrice'] == '0') {
            $this->jsonOutPut(0, '该商品不支持积分兑换');
        } elseif ($actionType == 'gold' && $info['goldPrice'] == '0') {
            $this->jsonOutPut(0, '该商品不支持金币兑换');
        } elseif ($actionType == 'score') {
            $perPrice = $info['scorePrice'];
            $label = '积分';
        } elseif ($actionType == 'gold') {
            $perPrice = $info['goldPrice'];
            $label = '金币';
        }
        if ($passDataArr[2] != $perPrice) {
            $this->jsonOutPut(0, '数据有误，请刷新');
        }
        $totalPrice = $perPrice * $num;
        //判断密码
        if (md5($password) != $this->userInfo['password']) {
            //一天内最多只能输错密码5次
            if (zmf::actionLimit('wrongPasswd', $this->userInfo['id'], 5, 86400)) {
                Yii::app()->user->logout();
                $this->jsonOutPut(0, '密码错误次数太多，请重新登录');
            }
            $this->jsonOutPut(0, '密码有误');
        }
        //判断财富值
        if (Users::checkWealth($this->uid, $actionType, $totalPrice)) {
            //创建订单
            $orderAttr = array(
                'orderId' => Orders::genOrderid(),
                'gid' => $info['id'],
                'title' => $info['title'],
                'desc' => $info['desc'],
                'faceUrl' => $info['faceUrl'],
                'classify' => CJSON::encode($info['classify']),
                'content' => $info['content'],
                'scorePrice' => $info['scorePrice'],
                'goldPrice' => $info['goldPrice'],
                'num' => $num,
                'payAction' => $actionType,
                'totalPrice' => $totalPrice,
                'orderStatus' => Orders::PAID_NOTPAID, //未支付
            );
            $orderModel = new Orders();
            $orderModel->attributes = $orderAttr;
            if ($orderModel->save()) {
                //订单创建成功了，扣除对应的积分或金币
                if (Users::costWealth($this->uid, $actionType, $totalPrice, $info)) {
                    if ($orderModel->updateByPk($orderModel->id, array(
                                'paidTime' => $now,
                                'paidType' => 'yue',
                                'orderStatus' => Orders::PAID_PAID,
                            ))) {
                        //保存商品绑定的道具到用户账上
                        Props::saveUserProps($this->userInfo, $info, $orderAttr);

                        //记录用户操作及积分
                        $jsonData = CJSON::encode(array(
                                    'id' => $info['id'],
                                    'title' => $info['title'],
                                    'faceUrl' => $info['faceUrl']
                        ));
                        $attr = array(
                            'uid' => $this->uid,
                            'logid' => $info['id'],
                            'classify' => 'goods',
                            'data' => $jsonData,
                            'action' => 'buyGoods',
                            'score' => $powerInfo['msg']['score'],
                            'display' => 1,
                        );
                        if (UserAction::simpleRecord($attr)) {
                            //判断本操作是否同属任务
                            $ckTaskStatus = Task::addTaskLog($this->userInfo, 'buyGoods');
                        }
                        $this->jsonOutPut(1, '恭喜，已兑换成功');
                    } else {
                        $this->jsonOutPut(0, '未知错误，请联系客服');
                    }
                } else {
                    //没有成功，则删除本订单
                    $orderModel->updateByPk($orderModel->id, array('status' => Posts::STATUS_DELED));
                    $this->jsonOutPut(0, '兑换失败，余额不足');
                }
            } else {
                $this->jsonOutPut(0, '下单失败');
            }
        } else {
            $this->jsonOutPut(0, '兑换失败，余额不足');
        }
    }

}
