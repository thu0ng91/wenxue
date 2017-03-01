<?php

/**
 * @filename LinksController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-12-06 15:32:40 */
class LinksController extends Admin {

    public function init() {
        parent::init();
        //$this->checkPower('links');
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
            //$this->checkPower('updateLinks');
            $model = $this->loadModel($id);
        } else {
            //$this->checkPower('addLinks');
            $model = new Links;
            $model->platform = 1;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Links'])) {
            $model->attributes = $_POST['Links'];
            if ($model->save()) {
                $typeid = $model->typeid > 0 ? $model->typeid : 0;
                $key = "allLinks-" . ($model->platform == 2 ? 'mobile' : 'web') . "-{$model->position}-{$typeid}";
                zmf::delFCache($key);
                if (!$id) {
                    Yii::app()->user->setFlash('addLinksSuccess', "保存成功！您可以继续添加。");
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
        //$this->checkPower('delLinks');
        $model = $this->loadModel($id);
        //$model->updateByPk($id, array('status' => Posts::STATUS_DELED));
        $model->deleteByPk($id);
        zmf::delFCache("allLinks-" . ($model->platform == 2 ? 'mobile' : 'web') . "-{$model->position}-{$model->typeid}");
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
        $select = "id,title,url,logo,cTime,expritedTime,position,typeid,platform";
        $model = new Links;
        $criteria = new CDbCriteria();
        $id = zmf::val("id", 1);
        if ($id) {
            $criteria->addSearchCondition("id", $id);
        }
        $title = zmf::val("title", 1);
        if ($title) {
            $criteria->addSearchCondition("title", $title);
        }
        $url = zmf::val("url", 1);
        if ($url) {
            $criteria->addSearchCondition("url", $url);
        }
        $logo = zmf::val("logo", 1);
        if ($logo) {
            $criteria->addSearchCondition("logo", $logo);
        }
        $cTime = zmf::val("cTime", 1);
        if ($cTime) {
            $criteria->addSearchCondition("cTime", $cTime);
        }
        $expritedTime = zmf::val("expritedTime", 1);
        if ($expritedTime) {
            $criteria->addSearchCondition("expritedTime", $expritedTime);
        }
        $position = zmf::val("position", 1);
        if ($position) {
            $criteria->addSearchCondition("position", $position);
        }
        $typeid = zmf::val("typeid", 1);
        if ($typeid) {
            $criteria->addSearchCondition("typeid", $typeid);
        }
        $platform = zmf::val("platform", 1);
        if ($platform) {
            $criteria->addSearchCondition("platform", $platform);
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
        $model = new Links('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Links']))
            $model->attributes = $_GET['Links'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Links the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Links::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Links $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'links-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
