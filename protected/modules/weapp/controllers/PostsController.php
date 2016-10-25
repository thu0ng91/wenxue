<?php

/**
 * @filename PostsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-10-25  17:30:49 
 */
class PostsController extends AppApi {

    public function actionAll() {
        //$forumId = $this->getValue('forum',1,2);
        $forumId=1;
        $filter = $this->getValue('filter',0,1);
        $order = $this->getValue('order',0,1);
        if (!in_array($filter, array('digest'))) {
            $filter = 'zmf';
        }
        if (!in_array($order, array('hits', 'props'))) {
            $order = 'zmf';
        }
        $forumInfo = PostForums::getOne($forumId);
        if (!$forumInfo) {
            $this->output('你所查看的版块不存在', 0);
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
        $posts = Posts::getByPage(array(
                    'sql' => $sql,
                    'pageSize' => $this->pageSize,
                    'page' => $this->page,
        ));
        foreach ($posts as $k => $val) {
            $posts[$k]['faceImg'] = zmf::getThumbnailUrl($val['faceImg'], 'c280', 'posts');
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
//        if ($this->uid) {
//            $favorited = Favorites::checkFavored($forumId, 'forum');
//        }
        
        $data = array(
            'posts' => $posts,
            'forumInfo' => $forumInfo,
            'showcases' => $showcases,
            'forums' => $forums,
            'filter' => $filter,
            'order' => $order,
            'topUsers' => $topUsers,
            'favorited' => $favorited,
        );
        $this->output($data, 1);
    }

}
