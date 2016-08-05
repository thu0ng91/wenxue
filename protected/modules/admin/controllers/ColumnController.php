<?php

/**
 * @filename ColumnController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 16:32:06 */
class ColumnController extends Admin {

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
            $model = new Column;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Column'])) {
            $model->attributes = $_POST['Column'];
            if ($model->save()) {
                if (!$id) {
                    Yii::app()->user->setFlash('addColumnSuccess', "保存成功！你可以继续添加。");
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

    public function actionIndex() {
        $select = "id,title,status";
        $model = new Column();
        $criteria = new CDbCriteria();
        $criteria->select = $select;
        $criteria->order = '`queue` ASC';
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = $model->findAll($criteria);
        $this->render('/posts/content', array(
            'model' => $model,
            'pages' => $pager,
            'posts' => $posts,
            'from' => 'column',
            'selectArr' => explode(',', $select),
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Column('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Column']))
            $model->attributes = $_GET['Column'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Column the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Column::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Column $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'column-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
