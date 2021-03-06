<?php

/**
 * @filename DigestController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-25 10:46:40 */
class DigestController extends Admin {

    public function init() {
        parent::init();
        //$this->checkPower('digest');
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
            //$this->checkPower('updateDigest');
            $model = $this->loadModel($id);
        } else {
            //$this->checkPower('addDigest');
            $model = new Digest;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Digest'])) {
            $model->attributes = $_POST['Digest'];
            if ($model->save()) {
                if ($_POST['Digest']['url'] && (strpos($_POST['Digest']['url'], 'http://') === false && strpos($_POST['Digest']['url'], 'https://') === false)) {
                    $_POST['Digest']['url'] = 'http://' . $_POST['Digest']['url'];
                }
                if (!$id) {
                    Yii::app()->user->setFlash('addDigestSuccess', "保存成功！您可以继续添加。");
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
        //$this->checkPower('delDigest');
        $this->loadModel($id)->updateByPk($id, array('status' => Posts::STATUS_DELED));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        header('location: ' . $_SERVER['HTTP_REFERER']);        
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $select = "id,uid,title,url,faceImg,content,cTime,status";
        $model = new Digest;
        $criteria = new CDbCriteria();
        $id = zmf::val("id", 1);
        if ($id) {
            $criteria->addSearchCondition("id", $id);
        }
        $uid = zmf::val("uid", 1);
        if ($uid) {
            $criteria->addSearchCondition("uid", $uid);
        }
        $title = zmf::val("title", 1);
        if ($title) {
            $criteria->addSearchCondition("title", $title);
        }
        $url = zmf::val("url", 1);
        if ($url) {
            $criteria->addSearchCondition("url", $url);
        }
        $faceImg = zmf::val("faceImg", 1);
        if ($faceImg) {
            $criteria->addSearchCondition("faceImg", $faceImg);
        }
        $content = zmf::val("content", 1);
        if ($content) {
            $criteria->addSearchCondition("content", $content);
        }
        $cTime = zmf::val("cTime", 1);
        if ($cTime) {
            $criteria->addSearchCondition("cTime", $cTime);
        }
        $criteria->addCondition('status=1');
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
        $model = new Digest('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Digest']))
            $model->attributes = $_GET['Digest'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Digest the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Digest::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Digest $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'digest-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
