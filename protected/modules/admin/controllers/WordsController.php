<?php

/**
 * @filename WordsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-06-01 05:28:02 */
class WordsController extends Admin {

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
            $model = $this->loadModel($id);
        } else {
            $model = new Words;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Words'])) {
            $model->attributes = $_POST['Words'];
            if ($model->save()) {
                Words::createWordsCache();
                if (!$id) {
                    Yii::app()->user->setFlash('addWordsSuccess', "保存成功！您可以继续添加。");
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
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $select = "id,uid,word,type,status";
        $model = new Words();
        $criteria = new CDbCriteria();
        $criteria->select = $select;
        $criteria->order = 'id DESC';
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = $model->findAll($criteria);
        $this->render('/posts/content', array(
            'model' => $model,
            'pages' => $pager,
            'posts' => $posts,
            'from' => 'words',
            'selectArr' => explode(',', $select),
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Words('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Words']))
            $model->attributes = $_GET['Words'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Words the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Words::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Words $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'words-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
