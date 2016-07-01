<?php

/**
 * @filename ShowcasesController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-17 08:49:07 */
class ShowcasesController extends Admin {

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
            $model = new Showcases;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Showcases'])) {
            if (!$id) {
                $cols = array_filter(array_unique($_POST['Showcases']['columnid']));
            } else {
                $cols = $_POST['Showcases']['columnid'];
            }
            if ($id) {//更新时
                $_POST['Showcases']['columnid'] = $cols;
                $model->attributes = $_POST['Showcases'];
                if ($model->save()) {
                    $this->redirect(array('index'));
                }
            } elseif (!$id && empty($cols)) {//新增且没选择所属版块时
                $_POST['Showcases']['columnid'] = 0;
                $model->attributes = $_POST['Showcases'];
                if ($model->save()) {
                    Yii::app()->user->setFlash('addShowcasesSuccess', "保存成功！你可以继续添加。");
                    $this->redirect(array('create'));
                }
            } else {//新增且有版块
                $hasError = false;
                foreach ($cols as $colid) {
                    $_POST['Showcases']['columnid'] = $colid;
                    $model->attributes = $_POST['Showcases'];
                    if (!$model->save()) {
                        $hasError = true;
                        break;
                    }else{
                        unset($model->id);
                        $model->isNewRecord=true;
                    }
                }
                if (!$hasError) {
                    Yii::app()->user->setFlash('addShowcasesSuccess', "保存成功！你可以继续添加。");
                    $this->redirect(array('create'));
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
        $select = "id,title,position,display,status";
        $model = new Showcases();
        $criteria = new CDbCriteria();
        $criteria->select = $select;
        $criteria->order = 'cTime DESC';
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = $model->findAll($criteria);
        $this->render('content', array(
            'model' => $model,
            'pages' => $pager,
            'posts' => $posts,
            'from' => 'showcases',
            'selectArr' => explode(',', $select),
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Showcases('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Showcases']))
            $model->attributes = $_GET['Showcases'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Showcases the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Showcases::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Showcases $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'showcases-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
