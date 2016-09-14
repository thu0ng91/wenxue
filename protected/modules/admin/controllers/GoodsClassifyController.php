<?php

/**
 * @filename GoodsClassifyController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-10 16:12:35 */
class GoodsClassifyController extends Admin {

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
        $belongid = zmf::val('belongid', 2);
        if ($id) {
            $model = $this->loadModel($id);
        } else {
            $model = new GoodsClassify;
            $belongInfo = GoodsClassify::getOne($belongid);
            if ($belongInfo['level'] <= 2) {
                $model->belongid = $belongid;
                $model->level = $belongInfo['level'] + 1;
            }
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['GoodsClassify'])) {
            $model->attributes = $_POST['GoodsClassify'];
            if ($model->save()) {
                if (!$id) {
                    Yii::app()->user->setFlash('addGoodsClassifySuccess', "保存成功！您可以继续添加。");
                    if ($model->belongid) {
                        $this->redirect(array('create', 'belongid' => $model->belongid));
                    } else {
                        $this->redirect(array('create'));
                    }
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
        $navbars = GoodsClassify::getNavbar();
//        foreach ($navbars as $t1 => $v1) {
//            $_total1 = 0;
//            foreach ($v1['items'] as $t2 => $v2) {
//                $_total2 = count($v2['items']);
//                $v1['items'][$t2]['total'] = ($_total2 > 0 ? $_total2 : 1);                
//                $_total1+=($_total2 > 0 ? $_total2 : 1);
//            }
//            $navbars[$t1] = $v1;
//            $navbars[$t1]['total']+=$_total1;
//        }
        $this->render('index', array(
            'navbars' => $navbars,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GoodsClassify('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GoodsClassify']))
            $model->attributes = $_GET['GoodsClassify'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GoodsClassify the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GoodsClassify::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GoodsClassify $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'goods-classify-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
