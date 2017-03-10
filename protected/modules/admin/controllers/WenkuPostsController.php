<?php

/**
 * @filename WenkuPostsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:17:08 */
class WenkuPostsController extends Admin {

    public function init() {
        parent::init();
        //$this->checkPower('wenkuposts');
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id = '') {
        if ($id) {
            //$this->checkPower('updateWenkuPosts');
            $model = $this->loadModel($id);
        } else {
            //$this->checkPower('addWenkuPosts');
            $model = new WenkuPosts;
            $author = zmf::val('author', 2);
            if ($author) {
                $model->author = $author;
                $model->dynasty = $model->authorInfo->dynasty;
                $model->status = Posts::STATUS_PASSED;
            }
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['WenkuPosts'])) {
            $filter = Posts::handleContent($_POST['WenkuPosts']['content']);
            $_POST['WenkuPosts']['content'] = $filter['content'];
            $model->attributes = $_POST['WenkuPosts'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->actionCreate($id);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        //$this->checkPower('delWenkuPosts');
        $this->loadModel($id)->updateByPk($id, array('status' => Posts::STATUS_DELED));
        header('location: ' . $_SERVER['HTTP_REFERER']);
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $select = "id,dynasty,colid,author,title,title_en,`status`";
        $model = new WenkuPosts;
        $criteria = new CDbCriteria();
        $dynasty = zmf::val("dynasty", 1);
        if ($dynasty) {
            $criteria->addCondition("dynasty=" . $dynasty);
        }
        $author = zmf::val("author", 1);
        if ($author) {
            $criteria->addSearchCondition("author", $author);
        }
        $title = zmf::val("title", 1);
        if ($title) {
            $criteria->addSearchCondition("title", $title, true, 'OR');
            $criteria->addSearchCondition("title_en", $title, true, 'OR');
        }
        $order = zmf::val("order", 1);
        if ($order == 'len') {
            $criteria->order = '`len` DESC';
        }
        $from = zmf::val("from", 1);
        if ($from != 'tashbin') {
            $criteria->addCondition('status!=' . Posts::STATUS_DELED);
        }
        $status = zmf::val("status", 1);
        if ($status !== '') {
            $criteria->addCondition("status=" . intval($status));
        }
        $criteria->select = $select;
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = $model->findAll($criteria);
        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
            'model' => $model
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new WenkuPosts('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['WenkuPosts']))
            $model->attributes = $_GET['WenkuPosts'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionUpdateLen() {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $num = 1000;
        $start = ($page - 1) * $num;
        $sql = "SELECT id,title FROM {{wenku_posts}} ORDER BY id ASC LIMIT $start,$num";
        $items = Yii::app()->db->createCommand($sql)->queryAll();
        if (empty($items)) {
            exit('well done');
        }
        foreach ($items as $val) {
            $_len = mb_strlen($val['title'], 'GBK');
            WenkuPosts::model()->updateByPk($val['id'], array(
                'len' => $_len
            ));
        }
        $url = Yii::app()->createUrl('admin/wenkuPosts/updateLen', array('page' => ($page + 1)));
        $this->message(1, "正在处理{$page}页", $url, 1);
    }

    public function actionKeywords() {
        $action = zmf::val('action', 1);
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '1800');
        if ($action == '' || $action == 'init') {
            $url = Yii::app()->createUrl('admin/wenkuPosts/keywords', array('action' => 'create'));
            KeywordIndexer::model()->deleteAll();
            $this->message(1, "即将生成关键词，完成前请勿关闭浏览器", $url, 1);
        } elseif ($action == 'create') {
            
        } else {
            $this->message(0, "未知操作", zmf::config('baseurl'), 1);
        }
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $classify = isset($_GET['classify']) ? $_GET['classify'] : 'author';
        $num = 1000;
        $start = ($page - 1) * $num;
        if ($classify == 'author') {
            $sql = "SELECT id,title,status FROM {{wenku_author}} WHERE status=1 ORDER BY id ASC LIMIT $start,$num";
        } elseif ($classify == 'posts') {
            $sql = "SELECT id,title,status FROM {{wenku_posts}} WHERE status=1 ORDER BY id ASC LIMIT $start,$num";
        } else {
            exit('no such classify');
        }
        $items = Yii::app()->db->createCommand($sql)->queryAll();
        if (empty($items)) {
            if ($classify == 'posts') {
                exit('well done');
            } elseif ($classify == 'author') {
                $url = Yii::app()->createUrl('admin/wenkuPosts/keywords', array('action' => 'create', 'classify' => 'posts'));
                $this->message(1, "即将生成古文", $url, 1);
            }
        }
        foreach ($items as $val) {
            KeywordIndexer::createKeywords($val, $classify);
        }
        $url = Yii::app()->createUrl('admin/wenkuPosts/keywords', array('page' => ($page + 1), 'action' => 'create', 'classify' => $classify));
        $this->message(1, "正在处理{$page}页", $url, 1);
    }

    public function actionCache() {
        KeywordIndexer::createWordsCache();
        exit('well done');
    }

    public function actionClearLink() {
        $action = zmf::val('action', 1);
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '1800');
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $classify = isset($_GET['classify']) ? $_GET['classify'] : 'author';
        $num = 10;
        $start = ($page - 1) * $num;
        if ($classify == 'author') {
            $sql = "SELECT id,content FROM {{wenku_author_info}} WHERE status=1 ORDER BY id ASC LIMIT $start,$num";
        } elseif ($classify == 'posts') {
            $sql = "SELECT id,content FROM {{wenku_post_info}} WHERE status=1 ORDER BY id ASC LIMIT $start,$num";
        } else {
            exit('no such classify');
        }
        $items = Yii::app()->db->createCommand($sql)->queryAll();
        if (empty($items)) {
            if ($classify == 'posts') {
                exit('well done');
            } elseif ($classify == 'author') {
                $url = Yii::app()->createUrl('admin/wenkuPosts/clearLink', array('classify' => 'posts'));
                $this->message(1, "即将处理古文", $url, 1);
            }
        }
        foreach ($items as $val) {
            $replace = array(
                "/\[link=([^\]]+?)\](.+?)\[\/link\]/i",
            );
            $to = array(
                '\\2',
            );
            $content = preg_replace($replace, $to, $val['content']);
            if (strpos($content, '[/link]') !== false) {
                $replace = array(
                    "/\[link=([^\]]+?)\](.+?)\[\/link\]/i",
                );
                $to = array(
                    '\\2',
                );
                $content = preg_replace($replace, $to, $content);
            }
            if ($classify == 'author') {
                WenkuAuthorInfo::model()->updateByPk($val['id'], array('content' => $content));
            } elseif ($classify == 'posts') {
                WenkuPostInfo::model()->updateByPk($val['id'], array('content' => $content));
            }
        }
        $url = Yii::app()->createUrl('admin/wenkuPosts/clearLink', array('page' => ($page + 1), 'classify' => $classify));
        $this->message(1, "正在处理{$page}页", $url, 1);
    }

    public function actionLink() {
        $action = zmf::val('action', 1);
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '1800');
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $classify = isset($_GET['classify']) ? $_GET['classify'] : 'author';
        $num = 10;
        $start = ($page - 1) * $num;
        if ($classify == 'author') {
            $sql = "SELECT id,content FROM {{wenku_author_info}} WHERE status=1 ORDER BY id ASC LIMIT $start,$num";
        } elseif ($classify == 'posts') {
            $sql = "SELECT id,content FROM {{wenku_post_info}} WHERE status=1 ORDER BY id ASC LIMIT $start,$num";
        } else {
            exit('no such classify');
        }
        $items = Yii::app()->db->createCommand($sql)->queryAll();
        if (empty($items)) {
            if ($classify == 'posts') {
                exit('well done');
            } elseif ($classify == 'author') {
                $url = Yii::app()->createUrl('admin/wenkuPosts/link', array('classify' => 'posts'));
                $this->message(1, "即将生成古文", $url, 1);
            }
        }
        foreach ($items as $val) {
            $content = KeywordIndexer::linkContent($val['content']);
            if ($classify == 'author') {
                WenkuAuthorInfo::model()->updateByPk($val['id'], array('content' => $content));
            } elseif ($classify == 'posts') {
                WenkuPostInfo::model()->updateByPk($val['id'], array('content' => $content));
            }
        }
        $url = Yii::app()->createUrl('admin/wenkuPosts/link', array('page' => ($page + 1), 'classify' => $classify));
        $this->message(1, "正在处理{$page}页", $url, 1);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return WenkuPosts the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = WenkuPosts::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param WenkuPosts $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'wenku-posts-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
