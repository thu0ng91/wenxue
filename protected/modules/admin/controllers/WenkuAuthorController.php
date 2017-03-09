<?php

/**
 * @filename WenkuAuthorController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-21 11:15:59 */
class WenkuAuthorController extends Admin {

    public function init() {
        parent::init();
        //$this->checkPower('wenkuauthor');
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
            //$this->checkPower('updateWenkuAuthor');
            $model = $this->loadModel($id);
        } else {
            //$this->checkPower('addWenkuAuthor');
            $model = new WenkuAuthor;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['WenkuAuthor'])) {
            $model->attributes = $_POST['WenkuAuthor'];
            if ($model->save()) {
                if (!$id) {
                    Yii::app()->user->setFlash('addWenkuAuthorSuccess', "保存成功！您可以继续添加。");
                    $this->redirect(array('create'));
                } else {
                    $this->redirect(array('view', 'id' => $model->id));
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
        //$this->checkPower('delWenkuAuthor');
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
        $select = "id,uid,title,dynasty,title_en,status";
        $model = new WenkuAuthor;
        $criteria = new CDbCriteria();
        $title = zmf::val("title", 1);
        if ($title) {
            $criteria->addSearchCondition("title", $title, true, 'OR');
            $criteria->addSearchCondition("title_en", $title, true, 'OR');
        }
        $dynasty = zmf::val("dynasty", 1);
        if ($dynasty) {
            $criteria->addCondition("dynasty=" . $dynasty);
        }
        $status = zmf::val("status", 1);
        if ($status !== '') {
            $criteria->addCondition("status=" . intval($status));
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
        $model = new WenkuAuthor('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['WenkuAuthor']))
            $model->attributes = $_GET['WenkuAuthor'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * 所属作者
     */
    public function actionSearch() {
        if (Yii::app()->request->isAjaxRequest && isset($_GET['q'])) {
            $name = $_GET['q'];
            $criteria = new CDbCriteria;
            $criteria->condition = "(title LIKE :keyword OR pinyin LIKE :keyword) AND status=" . Posts::STATUS_PASSED;
            $criteria->params = array(':keyword' => '%' . strtr($name, array('%' => '\%', '_' => '\_', '\\' => '\\\\')) . '%');
            $criteria->limit = 10;
            $criteria->select = 'id,title';
            $userArray = WenkuAuthor::model()->findAll($criteria);
            $returnVal = '';
            foreach ($userArray as $userAccount) {
                $returnVal .= $userAccount->getAttribute('title') . '|' . $userAccount->getAttribute('id') . "\n";
            }
            echo $returnVal;
        }
    }

    public function actionShowOne($id) {
        $id = zmf::val('id', 2);
        $info = $this->loadModel($id);
        WenkuPosts::model()->updateAll(array('status' => Posts::STATUS_PASSED), 'author=:author', array(':author' => $id));
        header('location: ' . $_SERVER['HTTP_REFERER']);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return WenkuAuthor the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = WenkuAuthor::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param WenkuAuthor $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'wenku-author-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
