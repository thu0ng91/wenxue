<?php

/**
 * @filename ChaptersController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-11 10:54:32 */
class ChaptersController extends Admin {

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
            $bid = zmf::val('bid', 2);
            if (!$bid) {
                throw new CHttpException(404, '请选择小说');
            }
            $model = new Chapters;
            $bookInfo = Books::getOne($bid);
            if ($bookInfo) {
                $model->bid = $bid;
                $model->uid = $bookInfo['uid'];
                $model->aid = $bookInfo['aid'];
            }
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Chapters'])) {
            $model->attributes = $_POST['Chapters'];
            if ($model->save()) {
                if (!$id) {
                    Yii::app()->user->setFlash('addChaptersSuccess', "保存成功！你可以继续添加。");
                    $this->redirect(array('create', 'bid' => $bid));
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
        $this->loadModel($id)->updateByPk($id, array('status'=>  Posts::STATUS_DELED));
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    public function actionIndex() {
        $select = "id,title,status";
        $model = new Chapters();
        $criteria = new CDbCriteria();
        $type = zmf::val('type', 1);
        $bid = zmf::val('bid', 2);
        if ($bid) {
            $criteria->addCondition('bid=' . $bid);
        } else {
            if ($type == 'stayCheck') {
                $criteria->addCondition('status=' . Posts::STATUS_STAYCHECK);
            } else {
                $criteria->addCondition('status!=' . Posts::STATUS_DELED);
            }
        }
        $criteria->select = $select;
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = $model->findAll($criteria);
        $this->render('/posts/content', array(
            'model' => $model,
            'pages' => $pager,
            'posts' => $posts,
            'from' => 'chapters',
            'selectArr' => explode(',', $select),
        ));
    }

    public function actionStayCheck($id) {
        $info = $this->loadModel($id);
        $info['content'] = Words::highLight($info['content']);
        $data = array(
            'info' => $info
        );
        $this->render('stayCheck', $data);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Chapters('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Chapters']))
            $model->attributes = $_GET['Chapters'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Chapters the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Chapters::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Chapters $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'chapters-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
