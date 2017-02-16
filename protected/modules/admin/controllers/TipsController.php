<?php

/**
 * @filename TipsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-14 20:42:39 */
class TipsController extends Admin {

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
            $model = new Tips;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Tips'])) {
            $model->attributes = $_POST['Tips'];
            if ($model->save()) {
                if (!$id) {
                    Yii::app()->user->setFlash('addTipsSuccess', "保存成功！你可以继续添加。");
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
        $select = "id,bid,uid,favors,cTime";
        $model = new Tips;
        $criteria = new CDbCriteria();
        $type = zmf::val('type', 1);
        if($type=='stayCheck'){
            $criteria->addCondition('status='.Posts::STATUS_STAYCHECK);
        }else{
            $criteria->addCondition('status!='.Posts::STATUS_DELED);
        }
        $uid=  zmf::val('uid',2);
        if($uid){
            $criteria->addCondition('uid='.$uid);
        }
        $bid=  zmf::val('bid',2);
        if($bid){
            $criteria->addCondition('bid='.$bid);
        }
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $criteria->select = $select;
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = $model->findAll($criteria);
        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Tips('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Tips']))
            $model->attributes = $_GET['Tips'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Tips the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Tips::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Tips $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'tips-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
