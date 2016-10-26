<?php

/**
 * @filename BookController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-10-24  15:48:52 
 */
class BookController extends AppApi {

    public function actionAll() {
        $colid = $this->getValue('colid', 0, 2);
        $order = $this->getValue('order', 0, 2);
        $colInfo = array();
        if ($colid) {
            $colInfo = Column::getSimpleInfo($colid);
            if (!$colInfo) {
                throw new CHttpException(404, '请选择正确的分类');
            }
        }
        $_orderTitle = Books::orderConditions($order);
        if ((!$_orderTitle || !$order) && $order != 'admin') {
            $orderBy = $order = 'score';
        } else {
            $orderBy = $order;
        }
        $arr = array(
            Books::STATUS_PUBLISHED,
            Books::STATUS_FINISHED
        );
        $sql = "SELECT id,colid,title,faceImg,`desc`,words,cTime,score,scorer,bookStatus FROM {{books}} WHERE " . ($colid > 0 ? "colid='{$colid}' AND " : "") . " status=" . Posts::STATUS_PASSED . " AND bookStatus IN(" . join(',', $arr) . ") ORDER BY {$orderBy} DESC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        foreach ($posts as $k => $val) {
            $posts[$k]['faceImg'] = zmf::getThumbnailUrl($val['faceImg'], 'w120', 'book');
        }
        $data = array(
            'posts' => $posts,
        );
        $this->output($data, 1);
    }

    public function actionDetail() {
        $id = $this->getValue('id', 1, 2);
        if (!$id) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $info = Books::getOne($id, 'w240');
        if (!$info || $info['status'] != Posts::STATUS_PASSED) {
            throw new CHttpException(404, '你所查看的小说不存在。');
        } else {
            $arr = array(
                Books::STATUS_PUBLISHED,
                Books::STATUS_FINISHED
            );
            if (!in_array($info['bookStatus'], $arr)) {
                throw new CHttpException(404, '你所查看的小说不存在。');
            }
        }
        $info['bookStatus']=  Books::exStatus($info['bookStatus']);
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
            $tips[$k]['cTime'] = zmf::formatTime($tip['cTime']);
        }
        //取得已收藏过的点评
//        if (!empty($tips) && $this->uid) {
//            $favoriteTipsArr = array();
//            $tids = join(',', array_keys(CHtml::listData($tips, 'id', '')));
//            if ($tids != '') {
//                $favoriteTipsArr = Favorites::model()->findAll(array(
//                    'condition' => "uid=:uid AND logid IN({$tids}) AND classify='tip'",
//                    'select' => 'logid',
//                    'params' => array(
//                        ':uid' => $this->uid
//                    )
//                ));
//            }
//            if (!empty($favoriteTipsArr)) {
//                foreach ($tips as $k => $val) {
//                    foreach ($favoriteTipsArr as $val2) {
//                        if ($val2['logid'] == $val['id']) {
//                            $tips[$k]['favorited'] = 1;
//                            break;
//                        }
//                    }
//                }
//            }
//        }

        $chapters = Chapters::getByBook($id);
        //作者的其他推荐书
        $otherTops = Authors::otherTops($info['aid'], $id, 10);
        //该分类的其他作品
        $otherBooks = Books::getColBooks($info['colid']);
        //获取分类
        $colInfo = Column::getSimpleInfo($info['colid']);
        //更新小说数据,10分钟更新一次
        $upBookInfo = zmf::getFCache('stat-Books-' . $id);
        if (!$upBookInfo) {
            Books::updateBookStatInfo($id);
            zmf::setFCache('stat-Books-' . $id, 1, 600);
        }
        //获取赞赏
        $props = Props::getClassifyProps('book', $id);
        //是否已收藏
        //$this->favorited = Favorites::checkFavored($id, 'book');        
        //二维码
        //$url = zmf::config('domain') . Yii::app()->createUrl('book/view', array('id' => $id));
        //$qrcode = zmf::qrcode($url, 'book', $id);

        $data = array(
            'info' => $info,
            'authorInfo' => $authorInfo,
            'colInfo' => $colInfo,
            'chapters' => $chapters,
            'otherTops' => $otherTops,
            'tips' => $tips,
            'props' => $props,
            'otherBooks' => $otherBooks,
        );
        $this->output($data, 1);
    }

    public function actionChapter() {
        $cid = $this->getValue('id', 1, 2);
        $chapterInfo = Chapters::model()->findByPk($cid);
        if (!$chapterInfo || $chapterInfo['status'] != Posts::STATUS_PASSED) {
            throw new CHttpException(404, '你所查看的章节不存在。');
        } else {
            if ($chapterInfo['chapterStatus'] != Posts::STATUS_PASSED) {
                throw new CHttpException(404, '你所查看的章节不存在。');
            }
        }
        $bookInfo = Books::getOne($chapterInfo['bid']);
        if (!$bookInfo || $bookInfo['status'] != Posts::STATUS_PASSED) {
            throw new CHttpException(404, '你所查看的小说不存在。');
        } else {
            $arr = array(
                Books::STATUS_PUBLISHED,
                Books::STATUS_FINISHED
            );
            if (!in_array($bookInfo['bookStatus'], $arr)) {
                throw new CHttpException(404, '你所查看的小说不存在。');
            }
        }
        $authorInfo = Authors::getOne($chapterInfo['aid']);
        if (!$authorInfo || $authorInfo['status'] != Posts::STATUS_PASSED) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        //处理内容
        $_content = strip_tags($chapterInfo['content'], '<p>');
        $_content = str_replace(array('<p>', '</p>','&nbsp;'), array('　　', PHP_EOL.''.PHP_EOL,''), $_content);
        $chapterInfo['content'] = $_content;
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
            $tips[$k]['cTime'] = zmf::formatTime($tip['cTime']);
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
        $props = Props::getClassifyProps('chapter', $cid);
        //判断我是否已点评过
        //$this->tipInfo = Chapters::checkTip($cid, $this->uid);        
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
        $this->output($data, 1);
    }

}
