<?php

class IndexController extends Admin {

    public function init() {
        parent::init();
        $this->checkPower('login');
    }

    public function actionIndex() {
        $arr['serverSoft'] = $_SERVER['SERVER_SOFTWARE'];
        $arr['serverOS'] = PHP_OS;
        $arr['PHPVersion'] = PHP_VERSION;
        $arr['fileupload'] = ini_get('file_uploads') ? ini_get('upload_max_filesize') : '禁止上传';
        $dbsize = 0;
        $connection = Yii::app()->db;
        $sql = 'SHOW TABLE STATUS LIKE \'' . $connection->tablePrefix . '%\'';
        $command = $connection->createCommand($sql)->queryAll();
        foreach ($command as $table) {
            $dbsize += $table['Data_length'] + $table['Index_length'];
        }
        $mysqlVersion = $connection->createCommand("SELECT version() AS version")->queryAll();
        $arr['mysqlVersion'] = $mysqlVersion[0]['version'];
        $arr['dbsize'] = $dbsize ? zmf::formatBytes($dbsize) : '未知';
        $arr['serverUri'] = $_SERVER['SERVER_NAME'];
        $arr['maxExcuteTime'] = ini_get('max_execution_time') . ' 秒';
        $arr['maxExcuteMemory'] = ini_get('memory_limit');
        $arr['excuteUseMemory'] = function_exists('memory_get_usage') ? zmf::formatBytes(memory_get_usage()) : '未知';

        //获取待审核
        $arr['posts'] = Posts::model()->count('status=:status', array(':status' => Posts::STATUS_STAYCHECK));
        $arr['comments'] = Comments::model()->count('status=:status', array(':status' => Posts::STATUS_STAYCHECK));
        $arr['books'] = Books::model()->count('status=:status', array(':status' => Posts::STATUS_STAYCHECK));
        $arr['chapters'] = Chapters::model()->count('status=:status AND chapterStatus=:chapterStatus', array(':status' => Posts::STATUS_PASSED,':chapterStatus' => Posts::STATUS_STAYCHECK));
        $arr['tips'] = Tips::model()->count('status=:status', array(':status' => Posts::STATUS_STAYCHECK));
        $this->render('index', array('siteinfo' => $arr));
    }

    public function actionStat() {
        $posts = Posts::model()->count();
        $commentsNum = Comments::model()->count();
        $attachsNum = Attachments::model()->count();
        $feedbackNum = Feedback::model()->count();
        $arr = array(
            'posts' => $posts,
            'commentsNum' => $commentsNum,
            'attachsNum' => $attachsNum,
            'feedbackNum' => $feedbackNum,
        );
        $this->render('stat', $arr);
    }

}
