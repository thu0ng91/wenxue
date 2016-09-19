<?php

/**
 * @filename GroupTasksController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:21:04 */
class GroupTasksController extends Admin {

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
            $model = new GroupTasks;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['GroupTasks'])) {
            $_gtaskInfo = false;
            if (!$id) {
                $_gtaskInfo = GroupTasks::model()->find('gid=:gid AND tid=:tid', array(':gid' => $_POST['GroupTasks']['gid'], ':tid' => $_POST['GroupTasks']['tid']));
            }
            if ($_gtaskInfo) {
                $model->attributes = $_POST['GroupTasks'];
                $model->addError('tid', '每个用户组只能领取一次同一任务');
            } else {
                if ($_POST['GroupTasks']['tid']) {
                    $taskInfo = Task::getOne($_POST['GroupTasks']['tid']);
                    if ($taskInfo) {
                        $_POST['GroupTasks']['action'] = $taskInfo['action'];
                    } else {
                        unset($_POST['GroupTasks']['tid']);
                    }
                }
                if ($_POST['GroupTasks']['type'] == GroupTasks::TYPE_ONETIME) {
                    $_POST['GroupTasks']['days'] = 1;
                    $_POST['GroupTasks']['continuous'] = 0;
                }
                $model->attributes = $_POST['GroupTasks'];
                if ($model->save()) {
                    if (!$id) {
                        Yii::app()->user->setFlash('addGroupTasksSuccess', "保存成功！您可以继续添加。");
                        $this->redirect(array('create'));
                    } else {
                        $this->redirect(array('index'));
                    }
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
        $select = "id,gid,tid,action,type,days,num,score,startTime,endTime,times";
        $model = new GroupTasks;
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
        $model = new GroupTasks('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GroupTasks']))
            $model->attributes = $_GET['GroupTasks'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GroupTasks the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GroupTasks::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GroupTasks $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'group-tasks-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
