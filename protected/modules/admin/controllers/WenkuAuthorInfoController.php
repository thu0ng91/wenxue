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
            $filter = Posts::handleContent($_POST['WenkuAuthorInfo']['content']);
            $_POST['WenkuAuthorInfo']['content'] = $filter['content'];
            $model->attributes = $_POST['WenkuAuthorInfo'];
            if ($model->save()) {
                $this->redirect(array('wenkuAuthor/view','id'=>$model->author));
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
        $select = "id,uid,author,classify";
        $model = new WenkuAuthorInfo;
        $criteria = new CDbCriteria();        
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
