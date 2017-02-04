<?php

class BookController extends Q {

    public $favorited = false;
    public $tipInfo = array();

    public function actionIndex() {
        $colid = zmf::val('colid', 2);
        $order = zmf::val('order', 1);
        $colInfo=array();
        if ($colid) {
            $colInfo = Column::getSimpleInfo($colid);
            if (!$colInfo) {
                throw new CHttpException(404, '请选择正确的分类');
            }
        }
        $_orderTitle=  Books::orderConditions($order);
        if((!$_orderTitle || !$order) && $order!='admin'){
            $orderBy=$order='score';
        }else{
            $orderBy=$order;
        }
        $arr=array(
            Books::STATUS_PUBLISHED,
            Books::STATUS_FINISHED
        );
        $sql = "SELECT id,colid,title,faceImg,`desc`,words,cTime,score,scorer,bookStatus FROM {{books}} WHERE ".($colid>0 ? "colid='{$colid}' AND " : "")." status=" . Posts::STATUS_PASSED . " AND bookStatus IN(" . join(',',$arr) . ") ORDER BY {$orderBy} DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        foreach ($posts as $k => $val) {
            $posts[$k]['faceImg'] = zmf::getThumbnailUrl($val['faceImg'], 'w120', 'book');
        }
        $cols=  Column::allCols();
        //获取所有活动
        $activity=array();
        if(!$this->isMobile){
            $activity=  Activity::getAllByType('books','id,title,faceimg,`desc`',false);
        }
        $this->selectNav = 'book';
        $this->showLeftBtn=false;
        $this->pageTitle = ($colInfo['title'] ? $colInfo['title'] : '作品集') . ' - ' . zmf::config('sitename');
        $this->mobileTitle='作品集';
        $data = array(
            'posts' => $posts,
            'colInfo' => $colInfo,
            'pages' => $pages,
            'cols' => $cols,
            'order' => $order,
            'activity' => $activity,
        );
        $this->render('index', $data);
    }

    public function actionView() {
        $id = zmf::val('id', 1);
        if (!$id) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $info = Books::getOne($id,  $this->isMobile ? 'w240' : 'w120');
        if (!$info || $info['status'] != Posts::STATUS_PASSED) {
            throw new CHttpException(404, '你所查看的小说不存在。');
        } else {
            $arr=array(
                Books::STATUS_PUBLISHED,
                Books::STATUS_FINISHED
            );
            if (!in_array($info['bookStatus'],$arr) && $this->uid != $info['uid']) {
                throw new CHttpException(404, '你所查看的小说不存在。');
            }
        }
        $authorInfo = Authors::getOne($info['aid']);
        if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        //获取点评列表
        $sql = "SELECT t.id,t.uid,u.truename,u.avatar,c.title AS chapterTitle,t.logid,t.content,t.score,t.favors,t.cTime,0 AS favorited,1 AS status,t.comments FROM ({{tips}} AS t RIGHT JOIN {{chapters}} AS c ON t.logid=c.id) LEFT JOIN {{users}} AS u ON t.uid=u.id WHERE t.bid=:bid AND t.classify='chapter' AND t.status=" . Posts::STATUS_PASSED . " AND t.logid=c.id ORDER BY t.cTime ASC";
        $tips = Posts::getByPage(array(
                    'sql' => $sql,
                    'page' => $this->page,
                    'pageSize' => $this->pageSize,
                    'bindValues' => array(
                        ':bid' => $id
                    )
        ));
        foreach ($tips as $k => $tip) {
            $tips[$k]['avatar'] = zmf::getThumbnailUrl($tip['avatar'], 'd120', 'user');
        }
        //取得已收藏过的点评
        if (!empty($tips) && $this->uid) {
            $favoriteTipsArr = array();
            $tids = join(',', array_keys(CHtml::listData($tips, 'id', '')));
            if ($tids != '') {
                $favoriteTipsArr = Favorites::model()->findAll(array(
                    'condition' => "uid=:uid AND logid IN({$tids}) AND classify='tip'",
                    'select' => 'logid',
                    'params' => array(
                        ':uid' => $this->uid
                    )
                ));
            }
            if (!empty($favoriteTipsArr)) {
                foreach ($tips as $k => $val) {
                    foreach ($favoriteTipsArr as $val2) {
                        if ($val2['logid'] == $val['id']) {
                            $tips[$k]['favorited'] = 1;
                            break;
                        }
                    }
                }
            }
        }
        $chapters = Chapters::getByBook($id);
        //作者的其他推荐书
        $otherTops = Authors::otherTops($info['aid'], $id, 10);
        //该分类的其他作品
        $otherBooks=  Books::getColBooks($info['colid']);        
        //获取分类
        $colInfo = Column::getSimpleInfo($info['colid']);
        //更新小说数据,10分钟更新一次
        $upBookInfo = zmf::getFCache('stat-Books-' . $id);
        if (!$upBookInfo) {
            Books::updateBookStatInfo($id,false);
            zmf::setFCache('stat-Books-' . $id, 1, 600);
        }
        //获取赞赏
        $props=Props::getClassifyProps('book', $id);   
        //是否已收藏
        $this->favorited = Favorites::checkFavored($id, 'book');
        //参与活动
        $myActivity=  Activity::getTypeActivity('books', $id);
        //标题
        $this->pageTitle = '【' . $authorInfo['authorName'] . '作品】' . $info['title'] . ' - ' . zmf::config('sitename');
        $this->mobileTitle='作品详情';
        $this->keywords = $info['title'] . '、' . $info['title'] . '小说阅读、' . $info['title'] . '最新章节';
        $this->pageDescription = "{$info['title']},{$info['title']}小说阅读。{$colInfo['title']}{$info['title']}由作家{$authorInfo['authorName']}创作," . zmf::config('sitename') . "提供{$info['title']}最新章节及章节列表,{$info['title']}最新更新尽在" . zmf::config('sitename') . "。";
        $this->selectNav = 'book';
        if($this->isMobile){
            $this->layout='book';
        }
        //二维码
        $url = zmf::config('domain') . Yii::app()->createUrl('book/view', array('id' => $id));
        $qrcode = zmf::qrcode($url, 'book', $id);
        $data = array(
            'info' => $info,
            'authorInfo' => $authorInfo,
            'colInfo' => $colInfo,
            'chapters' => $chapters,
            'otherTops' => $otherTops,
            'tips' => $tips,
            'url' => $url,
            'qrcode' => $qrcode,
            'props' => $props,
            'otherBooks' => $otherBooks,
            'myActivity' => $myActivity,
        );
        $this->render('view', $data);
    }

    public function actionChapter() {
        $cid = zmf::val('cid', 2);
        $chapterInfo = Chapters::model()->findByPk($cid);
        if (!$chapterInfo || $chapterInfo['status'] != Posts::STATUS_PASSED) {
            throw new CHttpException(404, '你所查看的章节不存在。');
        } else {
            if ($chapterInfo['chapterStatus'] != Posts::STATUS_PASSED && $this->uid != $chapterInfo['uid']) {
                throw new CHttpException(404, '你所查看的章节不存在。');
            }
        }
        $bookInfo = Books::getOne($chapterInfo['bid']);
        if (!$bookInfo || $bookInfo['status'] != Posts::STATUS_PASSED) {
            throw new CHttpException(404, '你所查看的小说不存在。');
        } else {
            $arr=array(
                Books::STATUS_PUBLISHED,
                Books::STATUS_FINISHED
            );
            if (!in_array($bookInfo['bookStatus'],$arr) && $this->uid != $bookInfo['uid']) {
                throw new CHttpException(404, '你所查看的小说不存在。');
            }
        }
        $authorInfo = Authors::getOne($chapterInfo['aid']);
        if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        //获取点评数
        $sql = "SELECT t.id,t.uid,u.truename,u.avatar,t.content,t.cTime,t.logid,t.tocommentid,t.favors,t.score,t.status,t.comments FROM {{tips}} t,{{users}} u WHERE t.logid=:logid AND t.classify='chapter' AND t.status=" . Posts::STATUS_PASSED . " AND t.uid=u.id AND u.status=" . Posts::STATUS_PASSED;
        $tips = Posts::getByPage(array(
                    'sql' => $sql,
                    'pageSize' => 30,
                    'page' => 1,
                    'bindValues' => array(
                        ':logid' => $cid
                    )
        ));
        foreach ($tips as $k => $tip) {
            $tips[$k]['avatar'] = zmf::getThumbnailUrl($tip['avatar'], 'd120', 'user');
        }
        //更新统计
        if (!zmf::actionLimit('visit-Chapters', $cid, 5, 60)) {
            Posts::updateCount($cid, 'Chapters', 1, 'hits');
        }
        //更新小说数据,10分钟更新一次
        $upBookInfo = zmf::getFCache('stat-Books-' . $bookInfo['id']);
        if (!$upBookInfo) {
            Books::updateBookStatInfo($bookInfo['id']);
            zmf::setFCache('stat-Books-' . $bookInfo['id'], 1, 600);
        }
        //获取分类
        $colInfo = Column::getSimpleInfo($bookInfo['colid']);
        //获取章节
        $chapters = Chapters::getByBook($bookInfo['id'], false);
        //下一章 上一章
        $next = $prev = array();
        foreach ($chapters as $k => $val) {
            $find = false;
            if ($val['id'] == $cid) {
                $_n = $chapters[$k + 1];
                $_p = $chapters[$k - 1];
                if (!empty($_n)) {
                    $next = array(
                        'title' => $_n['title'],
                        'url' => array('book/chapter', 'cid' => $_n['id'])
                    );
                }
                if (!empty($_p)) {
                    $prev = array(
                        'title' => $_p['title'],
                        'url' => array('book/chapter', 'cid' => $_p['id'])
                    );
                }
                $find = true;
            }
            if ($find) {
                break;
            }
        }
        //收到的道具
        $props=Props::getClassifyProps('chapter', $cid);
        //判断我是否已点评过
        $this->tipInfo = Chapters::checkTip($cid, $this->uid);
        //标题
        $this->pageTitle = '【' . $bookInfo['title'] . '】' . $chapterInfo['title'] . ' - ' . $authorInfo['authorName'] . '作品 - ' . zmf::config('sitename');

        $this->keywords = $bookInfo['title'] . '、' . $bookInfo['title'] . '小说阅读、' . $bookInfo['title'] . '最新章节';
        $this->pageDescription = "{$bookInfo['title']}：{$chapterInfo['title']}。{$colInfo['title']}{$bookInfo['title']}由作家{$authorInfo['authorName']}创作," . zmf::config('sitename') . "提供{$bookInfo['title']}最新章节及章节列表,{$bookInfo['title']}最新更新尽在" . zmf::config('sitename') . "。";
        $this->selectNav = 'book';
        if($this->isMobile){
            $this->layout='common';
        }
        $data = array(
            'bookInfo' => $bookInfo,
            'chapterInfo' => $chapterInfo,
            'chapters' => $chapters,
            'authorInfo' => $authorInfo,
            'colInfo' => $colInfo,
            'tips' => $tips,
            'next' => $next,
            'prev' => $prev,
            'props' => $props,
        );
        $this->render('chapter', $data);
    }

    public function actionEditTip() {
        if (!$this->uid) {
            $this->redirect(array('site/login'));
        }
        $this->checkUserStatus();
        $tid = zmf::val('tid', 2);
        $model = Tips::model()->findByPk($tid);
        if (!$model || $model->classify != 'chapter') {
            throw new CHttpException(404, 'The requested page does not exist.');
        } elseif ($model->uid != $this->uid) {
            throw new CHttpException(403, '你无权此操作.');
        }
        $postInfo = Chapters::getOne($model->logid);
        if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
            throw new CHttpException(404, '你所评论的内容不存在.');
        }
        //小说信息
        $bookInfo = Books::getOne($postInfo['bid']);
        if (!$bookInfo || $bookInfo['status'] != Posts::STATUS_PASSED) {
            throw new CHttpException(404, '你所评论的内容不存在.');
        } elseif ($bookInfo['bookStatus'] != Books::STATUS_PUBLISHED) {
            throw new CHttpException(403, '你所评论的小说暂未发表.');
        }
        if (isset($_POST['Tips'])) {
            $score = zmf::filterInput($_POST['Tips']['score'], 2);
            $content = zmf::filterInput($_POST['Tips']['content'], 1);
            $status = zmf::filterInput($_POST['Tips']['status'], 2);
            $attr = array(
                'score' => ($score < 0 || $score > 5) ? '' : $score,
                'content' => $content,
                'status' => ($status < 0 || $status > 3) ? Posts::STATUS_DELED : $status,
            );
            $model->attributes = $attr;
            if ($model->save()) {
                Books::updateScore($model->bid);
                //记录用户操作
                $jsonData = CJSON::encode(array(
                            'cid' => $postInfo['id'],
                            'cTitle' => $postInfo['title'],
                            'bid' => $bookInfo['id'],
                            'bTitle' => $bookInfo['title'],
                            'bDesc' => $bookInfo['desc'],
                            'bFaceImg' => $bookInfo['faceImg'],
                ));
                UserAction::recordAction($tid, 'chapterTip', $jsonData);
                $this->redirect(array('book/chapter', 'cid' => $model->logid));
            }
        }
        $this->pageTitle = '编辑点评 - ' . zmf::config('sitename');
        $this->mobileTitle='编辑点评';
        $data = array(
            'model' => $model,
        );
        $this->render('editTip', $data);
    }

}
