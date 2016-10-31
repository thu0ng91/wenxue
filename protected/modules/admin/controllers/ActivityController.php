<?php

class ActivityController extends Admin {

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $qrcodeUrl = zmf::qrcode(zmf::config('domain') . Yii::app()->createUrl('activity/view', array('id' => $id)), 'activity', $id);
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'qrcodeUrl' => $qrcodeUrl,
        ));
    }

    public function actionAddUser($id) {
        $model = new ActivityLink;
        if (isset($_POST['ActivityLink'])) {
            $uids = $_POST['ActivityLink']['logid'];
            $uidArr = array_unique(array_filter(explode(',', $uids)));
            if (empty($uidArr)) {
                $model->addError('logid', '请填写用户ID');
            } else {
                foreach ($uidArr as $_uid) {
                    $_attr = array(
                        'activity' => $id,
                        'logid' => $_uid,
                        'classify' => 'users'
                    );
                    $_alinfo = ActivityLink::model()->findByAttributes($_attr);
                    if ($_alinfo) {
                        continue;
                    } else {
                        $_model = new ActivityLink;
                        $_model->attributes = $_attr;
                        $_model->save();
                    }
                }
                $this->redirect(array('activity/users', 'id' => $id));
            }
        }
        $this->render('addUser', array('model' => $model));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id = '') {
        if ($id) {
            $model = $this->loadModel($id);
            $isNew = false;
        } else {
            $model = new Activity;
            $isNew = true;
        }
        if (isset($_POST['Activity'])) {
            $now = zmf::now();
            $_POST['Activity']['startTime'] = $_POST['Activity']['startTime'] ? strtotime($_POST['Activity']['startTime'], $now) : $now;
            $_POST['Activity']['expiredTime'] = $_POST['Activity']['expiredTime'] ? strtotime($_POST['Activity']['expiredTime'], $now) : 0;
            $_POST['Activity']['voteStart'] = $_POST['Activity']['voteStart'] ? strtotime($_POST['Activity']['voteStart'], $now) : $now;
            $_POST['Activity']['voteEnd'] = $_POST['Activity']['startTime'] ? strtotime($_POST['Activity']['voteEnd'], $now) : 0;

            if (($_POST['Activity']['expiredTime'] > 0 && $_POST['Activity']['expiredTime'] < $now) && $isNew) {
                $model->addError('expiredTime', '过期时间不能早于当前时间');
            } elseif (($_POST['Activity']['voteEnd'] > 0 && $_POST['Activity']['voteEnd'] < $now) && $isNew) {
                $model->addError('voteEnd', '投票结束时间不能早于当前时间');
            } elseif ($_POST['Activity']['voteEnd'] > 0 && $_POST['Activity']['startTime'] > 0 && $_POST['Activity']['voteEnd'] < $_POST['Activity']['startTime'] && $isNew) {
                $model->addError('voteEnd', '投票结束时间不能早于活动开始时间');
            } else {
                //投票开始时间默认与活动开始时间一致
                $_POST['Activity']['voteStart'] = $_POST['Activity']['voteStart'] ? $_POST['Activity']['voteStart'] : $_POST['Activity']['startTime'];
                //投票结束时间默认与活动结束时间一致
                $_POST['Activity']['voteEnd'] = $_POST['Activity']['voteEnd'] ? $_POST['Activity']['voteEnd'] : $_POST['Activity']['expiredTime'];
                //处理内容
                $filterContent = Posts::handleContent($_POST['Activity']['content']);
                $content = strip_tags($filterContent['content'], '<b><strong><em><span><a><p><u><i><img><br><br/>');
                $_POST['Activity']['content'] = $content;
                $model->attributes = $_POST['Activity'];
                if ($model->save()) {
                    Attachments::model()->updateAll(array('status' => Posts::STATUS_DELED), 'logid=:logid AND classify=:class', array(':logid' => $model->id, ':class' => 'activity'));
                    if ($_POST['Activity']['faceimg']) {
                        Attachments::model()->updateByPk($_POST['Activity']['faceimg'], array('status' => Posts::STATUS_PASSED, 'logid' => $model->id));
                    }
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
        }
        $faceimg = '';
        if ($model->faceimg > 0) {
            $faceimg = Attachments::faceImg($model, 'w650', 'activity');
        }
        if(!$isNew){
            $model->content=zmf::text(array('action'=>'edit'),$model->content,false,'w650');
        }
        $this->render($id ? 'update' : 'create', array(
            'model' => $model,
            'faceimg' => $faceimg,
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
        $this->loadModel($id)->updateByPk($id, array('status' => Activity::STATUS_DELED));
        $this->redirect(array('activity/index'));
    }

    public function actionDelUser($alid, $aid) {
        ActivityLink::model()->deleteByPk($alid);
        $this->redirect(array('activity/users', 'id' => $aid));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $criteria = new CDbCriteria();
        $criteria->order = 'cTime DESC';
        $criteria->addCondition('`status`!=' . Posts::STATUS_DELED);
        $count = Activity::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = Activity::model()->findAll($criteria);
        if (!empty($posts)) {
            foreach ($posts as $k => $v) {
                
            }
        }
        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
        ));
    }

    public function actionPosts() {
        $acid = zmf::val('id', 2);
        $activityInfo = $this->loadModel($acid);
        $criteria = new CDbCriteria();
        $criteria->addCondition("activity='{$acid}' AND `status`=" . Posts::STATUS_PASSED);
        $order = zmf::val('order', 1);
        if ($order == 'votes') {
            $criteria->order = "votes DESC";
        } else {
            $criteria->order = "voteOrder ASC";
        }
        $count = Posts::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = Posts::model()->findAll($criteria);

        $this->render('/posts/index', array(
            'pages' => $pager,
            'posts' => $posts,
            'from' => 'activity',
            'activityInfo' => $activityInfo,
        ));
    }

    public function actionUsers() {
        $acid = zmf::val('id', 2);
        $order = zmf::val('order', 1);
        if ($order) {
            $orderBy = $order;
            $desc = 'DESC';
        } else {
            $orderBy = 'voteOrder';
            $desc = 'ASC';
        }
        $activityInfo = $this->loadModel($acid);
        $sql = "SELECT u.id,al.id AS alid,al.activity AS aid,u.username,u.jobid,al.voteOrder,al.votes FROM {{user}} u,{{activity_link}} al WHERE al.activity='{$acid}' AND al.classify='users' AND al.logid=u.id ORDER BY al.{$orderBy} {$desc}";
        Posts::getAll(array('sql' => $sql, 'pageSize' => 30), $pages, $posts);

        $this->render('users', array(
            'pages' => $pages,
            'posts' => $posts,
            'acid' => $acid,
            'activityInfo' => $activityInfo,
        ));
    }

    public function actionGetip() {
        $page = zmf::val('page');
        $id = zmf::val('id');
        if (!$page) {
            $page = 1;
        }
        $limit = 30;
        $start = ($page - 1) * $limit;
        $sql = "SELECT ip FROM {{weixin_vote}} WHERE activity='{$id}' AND ip!=0 AND ipInfo='' GROUP BY ip LIMIT 0,$limit";
        $posts = Yii::app()->db->createCommand($sql)->queryAll();
        if (!empty($posts)) {
            $header = array(
                'apikey:e5882e7ac4b03c5d6f332b6de4469e81',
            );
            $ch = curl_init();
            // 添加apikey到header
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            foreach ($posts as $val) {
                $ip = long2ip($val['ip']);
                $url = 'http://apis.baidu.com/apistore/iplookupservice/iplookup?ip=' . $ip;
                // 执行HTTP请求
                curl_setopt($ch, CURLOPT_URL, $url);
                $res = curl_exec($ch);
                $res = json_decode($res, true);
                $retData = array();
                if ($res['errNum'] == 0) {
                    $retData = $res['retData'];
                }
                $_info = json_encode($retData);
                WeixinVote::model()->updateAll(array('ipInfo' => $_info), 'ip=:ip', array(':ip' => $val['ip']));
            }
            curl_close($ch);
            $redirectUrl = Yii::app()->createUrl('admin/activity/getip', array('id' => $id, 'page' => ($page + 1)));
            $this->message(1, '正在跳转...', $redirectUrl, 1);
        } else {
            $this->redirect(array('activity/view', 'id' => $id));
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Activity('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Activity']))
            $model->attributes = $_GET['Activity'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Activity the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Activity::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Activity $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'activity-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
