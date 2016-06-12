<?php

/**
 * @filename BooksController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-10 22:25:56 */
class BooksController extends Admin {

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
            $model = new Books;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Books'])) {
            $model->attributes = $_POST['Books'];
            if ($model->save()) {
                if (!$id) {
                    Yii::app()->user->setFlash('addBooksSuccess', "保存成功！你可以继续添加。");
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
        $this->loadModel($id)->updateByPk($id, array('status'=>  Posts::STATUS_DELED));
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    public function actionIndex() {
        $select = "id,aid,title,bookStatus,status";
        $model = new Books();
        $criteria = new CDbCriteria();
        $type = zmf::val('type', 1);    
        if($type=='stayCheck'){
            $criteria->addCondition('status='.Posts::STATUS_STAYCHECK);
        }else{
            $criteria->addCondition('status!='.Posts::STATUS_DELED);
        }
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
            'from' => 'books',
            'selectArr' => explode(',', $select),
        ));
    }
    
    public function actionChapters() {
        $id=  zmf::val('id',1);
        $select = "id,aid,title,status";
        $model = new Chapters();
        $criteria = new CDbCriteria();
        if($id){
            $criteria->addCondition("bid='$id'");
        }
        $type = zmf::val('type', 1);
        if($type=='stayCheck'){
            $criteria->addCondition('status='.Posts::STATUS_STAYCHECK);
        }else{
            $criteria->addCondition('status!='.Posts::STATUS_DELED);
        }
        $criteria->select = $select;
        $criteria->order = 'cTime DESC';
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = $model->findAll($criteria);
        $this->render('/posts/content', array(
            'bid' => $id,
            'model' => $model,
            'pages' => $pager,
            'posts' => $posts,
            'from' => 'chapters',
            'selectArr' => explode(',', $select),
        ));
    }
    
    public function actionStayCheck($id){
        $info=  $this->loadModel($id);
        $info['title']=  Words::highLight($info['title']);
        $info['desc']=  Words::highLight($info['desc']);
        $info['content']=  Words::highLight($info['content']);
        $data=array(
            'info'=>$info
        );
        $this->render('stayCheck',$data);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Books('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Books']))
            $model->attributes = $_GET['Books'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Books the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Books::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Books $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'books-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
