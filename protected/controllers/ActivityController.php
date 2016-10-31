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
        $faceimg = Attachments::faceImg($activityInfo, '', 'activity');
        $activityInfo['faceimg'] = zmf::getThumbnailUrl($faceimg, '650', 'activity');
        //判断
        $ckInfo = Activity::checkStatus($id, 'vote', $activityInfo);
        $btnStatus = $ckInfo['status'];
        
        $sql = "SELECT b.id,b.colid,b.title,b.faceImg,b.`desc`,b.words,b.cTime,b.score,b.scorer,b.bookStatus FROM {{books}} b,{{activity_link}} al WHERE al.activity='{$id}' AND al.classify='books' AND al.voteOrder>0 AND al.logid=b.id ORDER BY al.voteOrder ASC";
        Posts::getAll(array('sql'=>$sql), $pages, $posts);
        $this->pageTitle=$activityInfo['title'].' - '.zmf::config('sitename');
        $data = array(
            'activityInfo' => $activityInfo,
            'posts' => $posts,
            'type' => $activityInfo['type'],           
            'btnStatus' => $btnStatus,
        );
        $this->render('view', $data);
    }

}
