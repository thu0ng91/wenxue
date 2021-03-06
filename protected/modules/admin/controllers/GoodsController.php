<?php

/**
 * @filename GoodsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-10 16:12:23 */
class GoodsController extends Admin {

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
            $model = new Goods;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Goods'])) {
            $groupids = array_unique(array_filter($_POST['Goods']['groupids']));
            $_POST['Goods']['groupids'] = join(',', $groupids);
            //处理内容
            $handleInfo = Posts::handleContent($_POST['Goods']['content'], true);
            $content = $handleInfo['content'];
            $attachids = $handleInfo['attachids'];
            $now=  zmf::now();
            $_POST['Goods']['content'] = 0;
            $_POST['Goods']['faceUrl'] = $_POST['Goods']['faceUrl'];
            $_POST['Goods']['content'] = $_POST['Goods']['scorePrice'] <= 1 ? 1 : $_POST['Goods']['scorePrice'];
            $_POST['Goods']['topTime'] = $_POST['Goods']['topTime'] ? $now : 0;
            
            //处理商品关联操作
            $actionClassify = zmf::val('actionClassify', 1);
            $fromGroupid = zmf::val('fromGroupid', 2);
            $toGroupid = zmf::val('toGroupid', 2);
            $model->attributes = $_POST['Goods'];
            if ($model->save()) {
                //删除商品与用户组的关系
                if ($id) {
                    GoodsGroup::model()->deleteAll('goodsId=:gid', array(':gid' => $id));
                }
                if (!empty($groupids)) {
                    //当团队ID不为空时，表示只允许这几个团队可见                    
                    foreach ($groupids as $gid) {
                        $_ggAttr = array(
                            'goodsId' => $model->id,
                            'groupId' => $gid
                        );
                        $_ggModel = new GoodsGroup();
                        $_ggModel->attributes = $_ggAttr;
                        $_ggModel->save();
                    }
                } else {
                    //当团队为空时，表示所有团队可见
                    $_ggAttr = array(
                        'goodsId' => $model->id,
                        'groupId' => 0
                    );
                    $_ggModel = new GoodsGroup();
                    $_ggModel->attributes = $_ggAttr;
                    $_ggModel->save();
                }
                //绑定商品操作
                if (!$id) {
                    GoodsAction::model()->deleteAll('gid=:gid', array(':gid' => $model->id));
                }
                $actionClassifyArr = array_unique(array_filter(explode('#', $actionClassify)));
                if (!empty($actionClassifyArr)) {
                    $_goodsActionAttr = array(
                        'gid' => $model->id,
                        'classify' => $actionClassifyArr[0],
                        'action' => $actionClassifyArr[1],
                        'from' => ($actionClassifyArr[2] == 1 ? $fromGroupid : ''),
                        'to' => ($actionClassifyArr[2] == 1 ? $toGroupid : ''),
                    );
                    $_goodsActionModel = new GoodsAction;
                    $_goodsActionModel->attributes = $_goodsActionAttr;
                    if ($_goodsActionModel->save()) {
                        $model->updateByPk($model->id, array('actionId' => $_goodsActionModel->id));
                    }
                }
                //删除商品的详情
                //todo,判断详情是否有更新
                //todo,备份每个版本
                if ($id) {
                    GoodsContent::model()->deleteAll('gid=:gid', array(':gid' => $model->id));
                }
                if ($content != '') {
                    $_gcAttr = array(
                        'gid' => $model->id,
                        'content' => $content
                    );
                    $_gcModel = new GoodsContent;
                    $_gcModel->attributes = $_gcAttr;
                    if ($_gcModel->save()) {
                        $model->updateByPk($model->id, array('content' => $_gcModel->id));
                    }
                }
                //将上传的图片置为通过
                Attachments::model()->updateAll(array('status' => Posts::STATUS_DELED), 'logid=:logid AND classify=:classify', array(':logid' => $model->id, ':classify' => 'goods'));
                if (!empty($attachids)) {
                    $attstr = join(',', $attachids);
                    if ($attstr != '') {
                        Attachments::model()->updateAll(array('status' => Posts::STATUS_PASSED, 'logid' => $model->id), 'id IN(' . $attstr . ')');
                    }
                }
                if (!$id) {
                    Yii::app()->user->setFlash('addGoodsSuccess', "保存成功！您可以继续添加。");
                    $this->redirect(array('create'));
                } else {
                    $this->redirect(array('index'));
                }
            }
        }
        //正文
        if($model->content){
            $model->content= zmf::text(array('action'=>'edit'), $model->contentInfo->content);
        }
        
        //获取商品关联操作的分类
        $actions = GoodsAction::exClassify('all');
        $actionClassifyHtml = '<select class="form-control" onchange="selectActionClassify()" name="actionClassify" id="actionClassify">';
        foreach ($actions as $k1 => $val1) {
            if (!empty($val1['seconds'])) {
                $actionClassifyHtml.='<option value="">--请选择--</option>';
                foreach ($val1['seconds'] as $k2 => $val2) {
                    $actionClassifyHtml.='<option value="' . ($k1 . '#' . $k2 . ($val2['fromto'] ? '#1' : '')) . '">' . $val1['desc'] . '->' . $val2['desc'] . '</option>';
                }
            } else {
                $actionClassifyHtml.='<option value="' . ($k1) . '">' . $val1['desc'] . '</option>';
            }
        }
        $actionClassifyHtml.='</select>';
        //获取商品分类
        $navbars = GoodsClassify::getNavbar();
        $classifyHtml = $this->renderPartial('/goodsClassify/index', array('navbars' => $navbars, 'from' => 'goods'), true);
        $this->render('create', array(
            'model' => $model,
            'classifyHtml' => $classifyHtml,
            'actionClassifyHtml' => $actionClassifyHtml,
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
        $select = "id,title,scorePrice,goldPrice,classify,comments,hits,score";
        $model = new Goods;
        $criteria = new CDbCriteria();
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

    public function actionChkAction() {
        $classify = zmf::val('classify', 1);
        $level = zmf::val('level', 1);
        $action = zmf::val('action', 1);
        $info = GoodsAction::exClassify($classify);
        $html = '';
        if (!empty($info['seconds'])) {
            if ($level == 'first') {
                $html = '<label>' . $info['desc'] . '</label>';
                $html.=CHtml::dropDownList('actionAction', '', CHtml::listData($info['seconds'], 'key', 'desc'), array('class' => 'form-control', 'empty' => '--请选择--', 'onclick' => 'selectActionClassify("second")'));
            } else {
                $secondInfo = $info[$action];
                $html = '<label>' . $info['desc'] . '</label>';
                $html.=CHtml::dropDownList('actionAction', '', CHtml::listData($info['seconds'], 'key', 'desc'), array('class' => 'form-control', 'empty' => '--请选择--', 'onclick' => 'selectActionClassify("second")'));
            }
        } else {
            
        }
        $this->jsonOutPut(1, $html);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Goods('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Goods']))
            $model->attributes = $_GET['Goods'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Goods the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Goods::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Goods $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'goods-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
