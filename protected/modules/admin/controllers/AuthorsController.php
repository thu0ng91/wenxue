<?php

/**
 * @filename AuthorsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 17:20:08 */
class AuthorsController extends Admin {

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
            $model = new Authors;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Authors'])) {
            if($_POST['Authors']['password']){
                $_POST['Authors']['hashCode']=  zmf::randMykeys(6);
                $_POST['Authors']['password']=  md5($_POST['Authors']['password'].$_POST['Authors']['hashCode']);
            }
            $model->attributes = $_POST['Authors'];
            if ($model->save()) {
                if (!$id) {
                    Yii::app()->user->setFlash('addAuthorsSuccess', "保存成功！您可以继续添加。");
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function actionIndex() {
        $select = "id,authorName";
        $model = new Authors();
        $criteria = new CDbCriteria();
        $criteria->select = $select;
        $criteria->order = 'cTime DESC';
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = $model->findAll($criteria);
        $this->render('/posts/content', array(
            'model' => $model,
            'pages' => $pager,
            'posts' => $posts,
            'from' => 'packageDate',
            'selectArr' => explode(',', $select),
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Authors('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Authors']))
            $model->attributes = $_GET['Authors'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Authors the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Authors::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Authors $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'authors-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
