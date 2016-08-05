<?php

/**
 * @filename ShowcaseLinkController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-17 08:49:53 */
class ShowcaseLinkController extends Admin {

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
            $sid = zmf::val('sid', 2);
            if (!$sid) {
                throw new CHttpException(404, '请选择板块');
            }
            $showInfo = Showcases::getOne($sid);
            if (!$showInfo) {
                throw new CHttpException(404, '请选择板块');
            }
            $model = new ShowcaseLink;
            $model->sid = $sid;
            $model->classify = $showInfo['classify'];
        }
        if (isset($_POST['ShowcaseLink'])) {
            $now = zmf::now();
            if ($_POST['ShowcaseLink']['startTime']) {
                $_POST['ShowcaseLink']['startTime'] = strtotime($_POST['ShowcaseLink']['startTime'], $now);
            } else {
                $_POST['ShowcaseLink']['startTime'] = $now;
            }
            if ($_POST['ShowcaseLink']['endTime']) {
                $_POST['ShowcaseLink']['endTime'] = strtotime($_POST['ShowcaseLink']['endTime'], $now);
            }
            if ($model->classify == 'book' && !$_POST['ShowcaseLink']['logid']) {
                $model->addError('logid', '请填写小说ID');
            } else {
                $model->attributes = $_POST['ShowcaseLink'];
                if ($model->save()) {
                    if (!$id) {
                        Yii::app()->user->setFlash('addShowcaseLinkSuccess', "保存成功！你可以继续添加。");
                        $this->redirect(array('create', 'sid' => $model->sid));
                    } else {
                        $this->redirect(array('showcaseLink/index', 'sid' => $model->sid));
                    }
                }
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionIndex() {
        $sid = zmf::val('sid', 2);
        if (!$sid) {
            throw new CHttpException(404, '请选择板块');
        }        
        $select = "id,sid,logid,classify,startTime,endTime,status";
        $model = new ShowcaseLink();
        $criteria = new CDbCriteria();
        $criteria->addCondition("sid='{$sid}'");
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
            'from' => 'showcaseLink',
            'sid' => $sid,
            'selectArr' => explode(',', $select),
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
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new ShowcaseLink('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ShowcaseLink']))
            $model->attributes = $_GET['ShowcaseLink'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ShowcaseLink the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = ShowcaseLink::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ShowcaseLink $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'showcase-link-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
