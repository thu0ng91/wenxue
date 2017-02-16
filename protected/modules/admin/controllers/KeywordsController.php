<?php

/**
 * @filename KeywordsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-12-12 20:01:56 */
class KeywordsController extends Admin {

    public function init() {
        parent::init();
        //$this->checkPower('keywords');
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
            //$this->checkPower('updateKeywords');
            $model = $this->loadModel($id);
        } else {
            //$this->checkPower('addKeywords');
            $model = new Keywords;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Keywords'])) {
            if($_POST['Keywords']['url']!='' && strpos($_POST['Keywords']['url'], 'http://')===false && strpos($_POST['Keywords']['url'], 'https://')===false){
                $_POST['Keywords']['url']='http://'.$_POST['Keywords']['url'];
            }
            $model->attributes = $_POST['Keywords'];
            if ($model->save()) {
                Keywords::cacheWords();
                if (!$id) {
                    Yii::app()->user->setFlash('addKeywordsSuccess', "保存成功！您可以继续添加。");
                    $this->redirect(array('create'));
                } else {
                    $this->redirect(array('index'));
                }
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
        $this->checkPower('delKeywords');
        $this->loadModel($id)->deleteByPk($id);
        Keywords::cacheWords();
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
        $select = "id,title,len,url,hash";
        $model = new Keywords;
        $criteria = new CDbCriteria();
        $id = zmf::val("id", 1);
        if ($id) {
            $criteria->addSearchCondition("id", $id);
        }
        $title = zmf::val("title", 1);
        if ($title) {
            $criteria->addSearchCondition("title", $title);
        }
        $len = zmf::val("len", 1);
        if ($len) {
            $criteria->addSearchCondition("len", $len);
        }
        $url = zmf::val("url", 1);
        if ($url) {
            $criteria->addSearchCondition("url", $url);
        }
        $hash = zmf::val("hash", 1);
        if ($hash) {
            $criteria->addSearchCondition("hash", $hash);
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
        $model = new Keywords('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Keywords']))
            $model->attributes = $_GET['Keywords'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Keywords the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Keywords::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Keywords $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'keywords-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
