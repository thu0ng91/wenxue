<?php

/**
 * @filename SiteFilesController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2017 阿年飞少 
 * @datetime 2017-02-18 11:04:15 */
class SiteFilesController extends Admin {

    public function init() {
        parent::init();
        //$this->checkPower('sitefiles');
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
            //$this->checkPower('updateSiteFiles');
            $model = $this->loadModel($id);
        } else {
            //$this->checkPower('addSiteFiles');
            $model = new SiteFiles;
            $model->version = 1;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['SiteFiles'])) {
            if ($id) {
                $_POST['SiteFiles']['version']+=1;
            }
            $model->attributes = $_POST['SiteFiles'];
            if ($model->save()) {
                if (!$id) {
                    Yii::app()->user->setFlash('addSiteFilesSuccess', "保存成功！您可以继续添加。");
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
        //$this->checkPower('delSiteFiles');
        $this->loadModel($id)->deleteByPk($id);

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
        $select = "id,title,url,version,cTime,updateTime";
        $model = new SiteFiles;
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
        $version = zmf::val("version", 1);
        if ($version) {
            $criteria->addSearchCondition("version", $version);
        }
        $cTime = zmf::val("cTime", 1);
        if ($cTime) {
            $criteria->addSearchCondition("cTime", $cTime);
        }
        $updateTime = zmf::val("updateTime", 1);
        if ($updateTime) {
            $criteria->addSearchCondition("updateTime", $updateTime);
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
        $model = new SiteFiles('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SiteFiles']))
            $model->attributes = $_GET['SiteFiles'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return SiteFiles the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = SiteFiles::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param SiteFiles $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'site-files-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
