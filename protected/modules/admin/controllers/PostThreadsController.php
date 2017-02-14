<?php

/**
 * @filename PostThreadsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-04 22:17:36 */
class PostThreadsController extends Admin {

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
            $model = new PostThreads;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['PostThreads'])) {
            $model->attributes = $_POST['PostThreads'];
            if ($model->save()) {
                if (!$id) {
                    Yii::app()->user->setFlash('addPostThreadsSuccess', "保存成功！您可以继续添加。");
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
        $select = "id,fid,type,uid,title,hits,posts,comments,favorites,cTime";
        $model = new PostThreads;
        $criteria = new CDbCriteria();
        $criteria->select = $select;
        $criteria->order = 'cTime DESC';
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
        $model = new PostThreads('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PostThreads']))
            $model->attributes = $_GET['PostThreads'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PostThreads the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = PostThreads::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PostThreads $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'post-threads-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionIntoPosts() {
        $posts = Posts::model()->findAll();
        foreach ($posts as $item) {
            $_title = mysql_escape_string($item['title']);
            $_faceImg = Attachments::faceImg(array('faceimg' => $item['faceimg']), '');
            $sqlStr = "INSERT INTO pre_post_threads SET id='{$item['id']}',fid=1,uid='{$item['uid']}',aid='{$item['aid']}',title='{$_title}',faceImg='{$_faceImg}',hits='{$item['hits']}',comments='{$item['comments']}',favorites='{$item['favorite']}',styleStatus='{$item['styleStatus']}',`top`='{$item['top']}',`open`='{$item['open']}',cTime='{$item['cTime']}',status='{$item['status']}';";
            if (Yii::app()->db->createCommand($sqlStr)->execute()) {
                $_first = array(
                    'uid' => $item['uid'],
                    'aid' => $item['aid'],
                    'tid' => $item['id'],
                    'content' => $item['content'],
                    'cTime' => $item['cTime'],
                    'updateTime' => $item['cTime'],
                    'open' => $item['open'],
                    'status' => $item['status'],
                    'isFirst' => 1,
                );
                $_model = new PostPosts;
                $_model->attributes = $_first;
                if (!$_model->save()) {
                    PostThreads::model()->deleteByPk($item['id']);
                    continue;
                }
                $comments = Comments::model()->findAll("logid=:logid AND classify='posts'", array(':logid' => $item['id']));
                foreach ($comments as $comment) {
                    $_attr = array(
                        'uid' => $comment['uid'],
                        'aid' => $comment['aid'],
                        'tid' => $item['id'],
                        'content' => $comment['content'],
                        'cTime' => $comment['cTime'],
                        'updateTime' => $comment['cTime'],
                        'open' => $item['open'],
                        'status' => $comment['status'],
                        'isFirst' => 0,
                    );
                    $_model = new PostPosts;
                    $_model->attributes = $_attr;
                    $_model->save();
                }
                //更新帖子的楼层数
                PostThreads::model()->updateByPk($item['id'], array('posts' => count($comments)));
            }
        }
        exit('well done');
    }

}
