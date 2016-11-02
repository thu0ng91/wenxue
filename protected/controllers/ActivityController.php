<?php

/**
 * @filename ActivityController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-12-10  10:08:05 
 */
class ActivityController extends Q {

    public function actionView() {
        $id = zmf::val('id', 2);
        $activityInfo = Activity::getOne($id);
        if (!$activityInfo || $activityInfo['status'] == Activity::STATUS_DELED) {
            throw new CHttpException(404, '您所查看的活动不存在');
        }
        $faceimg = Attachments::faceImg($activityInfo, 'c640360', 'activity');
        $activityInfo['faceimg'] = $faceimg;
        $activityInfo['content'] = zmf::text(array(), $activityInfo['content'], true, 'w650');
        //判断
        $ckInfo = Activity::checkStatus($id, 'vote', $activityInfo);
        $sql = "SELECT b.id,al.id AS linkId,b.colid,b.title,b.faceImg,b.`desc`,b.words,b.cTime,b.score,b.scorer,b.bookStatus,al.votes,'' AS encodeData FROM {{books}} b,{{activity_link}} al WHERE al.activity='{$id}' AND al.classify='books' AND al.voteOrder>0 AND al.logid=b.id ORDER BY al.voteOrder ASC";
        Posts::getAll(array('sql' => $sql), $pages, $posts);
        $now = zmf::now();
        foreach ($posts as $k => $val) {
            $_data=$activityInfo['id'] . '@' . $val['linkId'] . '@' . $val['id'] . '@' . $now;            
            $posts[$k]['encodeData'] = Posts::encode($_data,'voteActivity');
        }
        $this->pageTitle = $activityInfo['title'] . ' - ' . zmf::config('sitename');
        $data = array(
            'activityInfo' => $activityInfo,
            'posts' => $posts,
            'type' => $activityInfo['type'],
            'btnInfo' => $ckInfo,
        );
        $this->render('view', $data);
    }

}
