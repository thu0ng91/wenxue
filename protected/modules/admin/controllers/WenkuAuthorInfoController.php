<?php

/**
 * @filename WenkuAuthorInfoController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:16:15 */
class WenkuAuthorInfoController extends Admin {

    public function init() {
        parent::init();
        //$this->checkPower('wenkuauthorinfo');
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
            //$this->checkPower('updateWenkuAuthorInfo');
            $model = $this->loadModel($id);
        } else {
            //$this->checkPower('addWenkuAuthorInfo');
            $model = new WenkuAuthorInfo;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['WenkuAuthorInfo'])) {
            $model->attributes = $_POST['WenkuAuthorInfo'];
            if ($model->save()) {
                if (!$id) {
                    Yii::app()->user->setFlash('addWenkuAuthorInfoSuccess', "保存成功！您可以继续添加。");
                    $this->redirect(array('create'));
                } else {
                    $this->redirect(array('index'));
                }
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
        //$this->checkPower('delWenkuAuthorInfo');
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
        $select = "id,uid,author,classify,content,comments,hits,cTime,status,likes,dislikes";
        $model = new WenkuAuthorInfo;
        $criteria = new CDbCriteria();
        $id = zmf::val("id", 1);
        if ($id) {
            $criteria->addSearchCondition("id", $id);
        }
        $uid = zmf::val("uid", 1);
        if ($uid) {
            $criteria->addSearchCondition("uid", $uid);
        }
        $author = zmf::val("author", 1);
        if ($author) {
            $criteria->addSearchCondition("author", $author);
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
        $model = new WenkuAuthorInfo('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['WenkuAuthorInfo']))
            $model->attributes = $_GET['WenkuAuthorInfo'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return WenkuAuthorInfo the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = WenkuAuthorInfo::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param WenkuAuthorInfo $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'wenku-author-info-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
