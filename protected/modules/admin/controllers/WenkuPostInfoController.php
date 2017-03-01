<?php

/**
 * @filename WenkuPostInfoController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:16:54 */
class WenkuPostInfoController extends Admin {

    public function init() {
        parent::init();
        //$this->checkPower('wenkupostinfo');
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
            //$this->checkPower('updateWenkuPostInfo');
            $model = $this->loadModel($id);
        } else {
            //$this->checkPower('addWenkuPostInfo');
            $pid = zmf::val('pid', 2);
            if (!$pid) {
                throw new CHttpException(404, '缺少PID');
            }
            $model = new WenkuPostInfo;
            $model->pid = $pid;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['WenkuPostInfo'])) {
            $filter = Posts::handleContent($_POST['WenkuPostInfo']['content']);
            $_POST['WenkuPostInfo']['content'] = $filter['content'];
            $model->attributes = $_POST['WenkuPostInfo'];
            if ($model->save()) {
                $this->redirect(array('wenkuPosts/view','id'=>$model->pid));                
            }
        }
        $replace = array(
            "/\[link=([^\]]+?)\](.+?)\[\/link\]/i",
        );
        $to = array(
            "$2",
        );
        $model->content = preg_replace($replace, $to, $model->content);
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
        //$this->checkPower('delWenkuPostInfo');
        $this->loadModel($id)->updateByPk($id, array('status' => Posts::STATUS_DELED));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if ($this->isAjax) {
            $this->jsonOutPut(1, '已删除');
        } else {
            header('location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $select = "id,pid,classify,content,comments,hits,cTime,status,likes,dislikes";
        $model = new WenkuPostInfo;
        $criteria = new CDbCriteria();
        $id = zmf::val("id", 1);
        if ($id) {
            $criteria->addSearchCondition("id", $id);
        }
        $uid = zmf::val("uid", 1);
        if ($uid) {
            $criteria->addSearchCondition("uid", $uid);
        }
        $pid = zmf::val("pid", 1);
        if ($pid) {
            $criteria->addSearchCondition("pid", $pid);
        }
        $classify = zmf::val("classify", 1);
        if ($classify) {
            $criteria->addSearchCondition("classify", $classify);
        }
        $content = zmf::val("content", 1);
        if ($content) {
            $criteria->addSearchCondition("content", $content);
        }
        $comments = zmf::val("comments", 1);
        if ($comments) {
            $criteria->addSearchCondition("comments", $comments);
        }
        $hits = zmf::val("hits", 1);
        if ($hits) {
            $criteria->addSearchCondition("hits", $hits);
        }
        $cTime = zmf::val("cTime", 1);
        if ($cTime) {
            $criteria->addSearchCondition("cTime", $cTime);
        }
        $status = zmf::val("status", 1);
        if ($status) {
            $criteria->addSearchCondition("status", $status);
        }
        $likes = zmf::val("likes", 1);
        if ($likes) {
            $criteria->addSearchCondition("likes", $likes);
        }
        $dislikes = zmf::val("dislikes", 1);
        if ($dislikes) {
            $criteria->addSearchCondition("dislikes", $dislikes);
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
        $model = new WenkuPostInfo('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['WenkuPostInfo']))
            $model->attributes = $_GET['WenkuPostInfo'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return WenkuPostInfo the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = WenkuPostInfo::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param WenkuPostInfo $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'wenku-post-info-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
