<?php

/**
 * @filename WenkuBookController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:16:26 */
class WenkuBookController extends Admin {

    public function init() {
        parent::init();
        //$this->checkPower('wenkubook');
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
            //$this->checkPower('updateWenkuBook');
            $model = $this->loadModel($id);
        } else {
            //$this->checkPower('addWenkuBook');
            $model = new WenkuBook;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['WenkuBook'])) {
            $filter = Posts::handleContent($_POST['WenkuBook']['content']);
            $_POST['WenkuBook']['content'] = $filter['content'];
            $model->attributes = $_POST['WenkuBook'];
            if ($model->save()) {
                if (!$id) {
                    Yii::app()->user->setFlash('addWenkuBookSuccess', "保存成功！您可以继续添加。");
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
        //$this->checkPower('delWenkuBook');
        $this->loadModel($id)->updateByPk($id, array('status' => Posts::STATUS_DELED));

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
        $select = "id,author,dynasty,uid,title,content,classify,status,cTime,attachid";
        $model = new WenkuBook;
        $criteria = new CDbCriteria();
        $id = zmf::val("id", 1);
        if ($id) {
            $criteria->addSearchCondition("id", $id);
        }
        $author = zmf::val("author", 1);
        if ($author) {
            $criteria->addSearchCondition("author", $author);
        }
        $dynasty = zmf::val("dynasty", 1);
        if ($dynasty) {
            $criteria->addSearchCondition("dynasty", $dynasty);
        }
        $uid = zmf::val("uid", 1);
        if ($uid) {
            $criteria->addSearchCondition("uid", $uid);
        }
        $title = zmf::val("title", 1);
        if ($title) {
            $criteria->addSearchCondition("title", $title);
        }
        $content = zmf::val("content", 1);
        if ($content) {
            $criteria->addSearchCondition("content", $content);
        }
        $classify = zmf::val("classify", 1);
        if ($classify) {
            $criteria->addSearchCondition("classify", $classify);
        }
        $status = zmf::val("status", 1);
        if ($status) {
            $criteria->addSearchCondition("status", $status);
        }
        $cTime = zmf::val("cTime", 1);
        if ($cTime) {
            $criteria->addSearchCondition("cTime", $cTime);
        }
        $attachid = zmf::val("attachid", 1);
        if ($attachid) {
            $criteria->addSearchCondition("attachid", $attachid);
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
        $model = new WenkuBook('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['WenkuBook']))
            $model->attributes = $_GET['WenkuBook'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return WenkuBook the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = WenkuBook::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param WenkuBook $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'wenku-book-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
