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
        if (!in_array($action, array('addTip', 'saveUploadImg', 'publishBook', 'publishChapter', 'saveDraft', 'report', 'sendSms', 'checkSms', 'setStatus', 'delContent', 'getNotice', 'getContents'))) {
            $this->jsonOutPut(0, Yii::t('default', 'forbiddenaction'));
        }
        $this->$action();
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
        $bookInfo = Books::getOne($postInfo['bid']);
        if (!$bookInfo || $bookInfo['status'] != Posts::STATUS_PASSED) {
            $this->jsonOutPut(0, '你所评论的小说不存在');
        } elseif ($bookInfo['bookStatus'] != Books::STATUS_PUBLISHED) {
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
                    $_content = '你的小说章节【' . $postInfo['title'] . '】有了新的点评,' . $_url;
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
                UserAction::recordAction($model->id, 'chapterTip', $jsonData);
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
        $authorInfo=  Authors::getOne($this->userInfo['authorId']);
        if(!$authorInfo || $authorInfo['status']!=Posts::STATUS_PASSED){
            $this->jsonOutPut(0, '你无权本操作');
        }
        //统计已发表的章节
        $chapters = Chapters::model()->count('uid=:uid AND aid=:aid AND bid=:bid AND status=' . Books::STATUS_PUBLISHED, array(':uid' => $this->uid, ':aid' => $this->userInfo['authorId'], ':bid' => $id));
        if ($chapters < 1) {
            $this->jsonOutPut(0, '小说暂无已发表章节，请先发表章节');
        }
        if (Books::model()->updateByPk($id, array('bookStatus' => Books::STATUS_PUBLISHED))) {
            //更新小说信息
            Books::updateBookStatInfo($id);
            //更新作者信息
            Authors::updateStatInfo($authorInfo);
            $this->jsonOutPut(1, '已发表');
        } else {
            $this->jsonOutPut(1, '已发表');
        }
    }

    private function publishChapter() {
        $this->checkLogin();
        $this->checkUserStatus();
        $id = zmf::val('id', 2);
        if (!$id) {
            $this->jsonOutPut(0, '缺少参数哦~');
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
        $authorInfo=  Authors::getOne($this->userInfo['authorId']);
        if(!$authorInfo || $authorInfo['status']!=Posts::STATUS_PASSED){
            $this->jsonOutPut(0, '你无权本操作');
        }
        if (!$chapterInfo) {
            $this->jsonOutPut(0, '操作对象不存在，请核实');
        } elseif ($chapterInfo['uid'] != $this->uid || $chapterInfo['aid'] != $this->userInfo['authorId']) {
            $this->jsonOutPut(0, '你无权本操作');
        } elseif ($chapterInfo['status'] == Books::STATUS_PUBLISHED) {
            $this->jsonOutPut(1, '已发表');
        } elseif ($chapterInfo['status'] == Books::STATUS_STAYCHECK) {
            $this->jsonOutPut(0, '该章节待审核，请审核通过后再发表！');
        }
        if (!Authors::checkLogin($this->userInfo, $chapterInfo['aid'])) {
            $this->jsonOutPut(0, '你无权本操作');
        }
        if (Chapters::model()->updateByPk($id, array('status' => Books::STATUS_PUBLISHED))) {
            Books::updateBookStatInfo($chapterInfo['bid']);
            Authors::updateStatInfo($authorInfo);
            $this->jsonOutPut(1, '已发表');
        } else {
            $this->jsonOutPut(1, '已发表');
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
            $phone = $this->userInfo['phone'];
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
            $phone = $this->userInfo['phone'];
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
            Users::model()->updateByPk($this->uid, array('phoneChecked' => 1));
            $returnCode = Yii::app()->createUrl('user/index');
        } elseif ($type == 'forget') {
            if (Users::updateInfo($uinfo['id'], 'password', md5($password))) {
                $this->jsonOutPut(1, '密码修改成功，正在跳转至登录页面');
            } else {
                $this->jsonOutPut(0, '密码修改失败，未知错误');
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
        $content = zmf::val('c', 1);
        if (!isset($type) OR ! in_array($type, array('posts','tipComments'))) {
            $this->jsonOutPut(0, Yii::t('default', 'forbiddenaction'));
        }
        if (!isset($keyid) OR ! is_numeric($keyid)) {
            $this->jsonOutPut(0, Yii::t('default', 'pagenotexists'));
        }
        if (!$content) {
            $this->jsonOutPut(0, '评论不能为空哦~');
        }
        $uid = $this->uid;
        if($type=='posts'){
            $postInfo = Posts::model()->findByPk($keyid);
            if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
                $this->jsonOutPut(0, '你所评论的内容不存在');
            } elseif ($postInfo['open'] != Posts::STATUS_OPEN) {
                $this->jsonOutPut(0, '评论功能已关闭');
            }
        }elseif($type=='tipComments'){
            $postInfo = Tips::model()->findByPk($keyid);
            if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
                $this->jsonOutPut(0, '你所评论的内容不存在');
            }
            $type='tip';
        }
        //处理文本，不是富文本
        $filter = Posts::handleContent($content, FALSE);
        $content = $filter['content'];
        $status = $filter['status'];
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
                    $_content = '你的文章【' . $postInfo['title'] . '】有了新的评论,' . $_url;
                }elseif($type=='tip'){
                    $_url = CHtml::link('查看详情', array('book/chapter', 'cid' => $postInfo['logid']));
                    if ($status == Posts::STATUS_PASSED) {
                        Posts::updateCount($keyid,'Tips',1,'comments');
                    }
                    $_content = '你的点评有了新的评论,' . $_url;
                }
                if ($to && $_url) {
                    $_content = '你的评论有了新的回复,' . $_url;
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
                $html = $this->renderPartial('/posts/_comment', array('data' => $intoData, 'postInfo' => $postInfo), true);
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
        if (!in_array($type, array('tipComments'))) {
            $this->jsonOutPut(0, '暂不允许的分类');
        }
        if ($page < 1 || !is_numeric($page)) {
            $page = 1;
        }
        $longHtml = '';
        switch ($type) {
            case 'tipComments':
                $postInfo = Tips::model()->findByPk($id);
                if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
                    $this->jsonOutPut(0, '你所评论的内容不存在');
                }
                $limit = 30;
                $posts = Comments::getCommentsByPage($id, 'tip', $page, $limit);
                $view = '/posts/_comment';
                break;
            default:
                $posts = array();
                break;
        }
        if (!empty($posts)) {
            foreach ($posts as $k => $row) {
                $longHtml.=$this->renderPartial($view, array('data' => $row, 'k' => $k, 'postInfo' => $postInfo,'from'=>'tip'), true);
            }
        }
        $data = array(
            'html' => $longHtml,
            'loadMore' => (count($posts) == $limit) ? 1 : 0,
            'formHtml' => $this->renderPartial('/posts/_addComment', array('type' => $type, 'keyid' => $id), true)
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
        $status=  Posts::STATUS_DELED;
        switch ($type) {
            case 'comment':
                $info = Comments::model()->findByPk($data);
                if (!$info) {
                    $this->jsonOutPut(0, '你所查看的内容不存在');
                } elseif ($info['uid'] != $this->uid) {
                    if ($this->checkPower('delComment', $this->uid, true)) {
                        //我是管理员，我就可以删除
                        $status=  Posts::STATUS_STAYCHECK;
                    } else {
                        $this->jsonOutPut(0, '你无权操作');
                    }
                }
                if (Comments::model()->updateByPk($data, array('status' => $status))) {
                    if($info['classify']=='posts'){
                        Posts::updateCount($info['logid'], 'Posts', -1, 'comments');
                    }elseif ($info['classify']=='tip') {
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
                        $status=  Posts::STATUS_STAYCHECK;
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
        $ckinfo = Posts::favorite($data, $type, 'web');
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
        if (!in_array($action, array('top', 'red', 'bold', 'boldAndRed','lock'))) {
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
        }elseif($action == 'lock') {
            $filed = 'open';
            $status=0;
        }
        if (Posts::model()->updateByPk($id, array($filed => $status))) {
            $this->jsonOutPut(1, '已设置');
        } else {
            $this->jsonOutPut(0, '操作失败，请重试');
        }
    }

}
