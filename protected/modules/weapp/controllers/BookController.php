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
        zmf::fp($posts,1);
        //$cols = Column::allCols();
        
        $data = array(
            'posts' => $posts,
        );
        $this->output($data, 1);
    }

}
