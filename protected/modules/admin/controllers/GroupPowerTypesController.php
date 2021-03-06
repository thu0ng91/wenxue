<?php

/**
 * @filename GroupPowerTypesController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:45 */
class GroupPowerTypesController extends Admin {

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
    public function actionCreate() {
        $model = new GroupPowerTypes;
        if (isset($_POST['GroupPowerTypes'])) {
            $items = $_POST['GroupPowerTypes']['key'];
            $_arr = UserAction::userActions('admin');
            foreach ($items as $item) {
                $_attr = array(
                    'key' => $item,
                    'desc' => $_arr[$item],
                );
                $_model = new GroupPowerTypes;
                $_model->attributes = $_attr;
                $_model->save();
            }
            Yii::app()->user->setFlash('addGroupPowerTypesSuccess', "保存成功！您可以继续添加。");
            $this->redirect(array('create'));
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
        if ($id) {
            $model = $this->loadModel($id);
        } else {
            $model = new GroupPowerTypes;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['GroupPowerTypes'])) {
            if (!$_POST['GroupPowerTypes']['desc']) {
                $_arr = UserAction::userActions('admin');
                $_POST['GroupPowerTypes']['desc'] = $_arr[$_POST['GroupPowerTypes']['key']];
            }
            $model->attributes = $_POST['GroupPowerTypes'];
            if ($model->save()) {
                if (!$id) {
                    Yii::app()->user->setFlash('addGroupPowerTypesSuccess', "保存成功！您可以继续添加。");
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
        $select = "id,`key`,`desc`";
        $model = new GroupPowerTypes;
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
        $model = new GroupPowerTypes('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GroupPowerTypes']))
            $model->attributes = $_GET['GroupPowerTypes'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GroupPowerTypes the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GroupPowerTypes::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GroupPowerTypes $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'group-power-types-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
