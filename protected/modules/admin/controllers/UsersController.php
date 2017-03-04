<?php

/**
 * @filename TagsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-4  12:54:36 
 */
class UsersController extends Admin {

    public function init() {
        parent::init();
        $this->checkPower('users');
    }

    public function actionIndex() {
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $count = Users::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = Users::model()->findAll($criteria);

        $this->render('index', array(
            'pages' => $pager,
            'posts' => $posts,
        ));
    }

    public function actionCreate($id = '') {
        if ($id) {
            $this->checkPower('updateUser');
            $model = Users::model()->findByPk($id);
            if (!$model) {
                $this->message(0, '你所编辑的用户不存在');
            }
            $isNew = false;
        } else {
            $this->checkPower('addUser');
            $model = new Users;
            $isNew = true;
        }
        if (isset($_POST['Users'])) {
            if ($isNew) {
                $_POST['Users']['password'] = md5($_POST['Users']['password']);
            } elseif ($_POST['Users']['password'] != $model->password) {
                $_POST['Users']['password'] = md5($_POST['Users']['password']);
            }
            $model->attributes = $_POST['Users'];
            if ($model->save()) {
                $this->redirect(array('users/index'));
            }
        }
        $this->render('create', array(
            'model' => $model
        ));
    }

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionUpdate($id) {
        $this->actionCreate($id);
    }

    public function loadModel($id) {
        $model = Users::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionAdmins() {
        $this->checkPower('admins');
        $criteria = new CDbCriteria();
        $criteria->select = 'id,truename';
        $criteria->addCondition('isAdmin=1');
        $count = Users::model()->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize = 30;
        $pager->applyLimit($criteria);
        $posts = Users::model()->findAll($criteria);

        $this->render('admins', array(
            'pages' => $pager,
            'posts' => $posts,
        ));
    }

    public function actionSetadmin($id = '') {
        $this->checkPower('setAdmin');
        $mine = array();
        $model = new Admins();
        if ($id) {
            $pinfos = $model->findAll('uid=:uid', array(':uid' => $id));
            $model->uid = $id;
            if ($pinfos) {
                $mine = array_keys(CHtml::listData($pinfos, 'powers', ''));
            }
        }
        if (isset($_POST['Admins'])) {
            $url = Yii::app()->createUrl('admin/users/admins');
            $uid = $_POST['Admins']['uid'];
            if (!$uid) {
                $model->addError('uid', 'uid不能为空');
            } else {
                $powers = array_unique(array_filter($_POST['powers']));
                Admins::model()->deleteAll('uid=:uid', array(':uid' => $uid));
                if (empty($powers)) {
                    $this->message(1, '操作成功', $url);
                } else {
                    foreach ($powers as $p) {
                        $_attr = array(
                            'uid' => $uid,
                            'powers' => $p
                        );
                        $m = new Admins;
                        $m->attributes = $_attr;
                        $m->save();
                    }
                    $this->message(1, '操作成功', $url);
                }
            }
        }
        $data = array(
            'model' => $model,
            'mine' => $mine,
        );
        $this->render('setadmin', $data);
    }

    public function actionDeladmin($id) {
        $this->checkPower('delAdmin');
        Admins::model()->deleteAll('uid=:uid', array(':uid' => $id));
        Users::model()->updateByPk($id, array('isAdmin' => 0));
        $this->redirect(array('users/admins'));
    }

    public function actionUpdateStat() {
        $users = Users::model()->findAll();
        foreach ($users as $info) {
            $cacheKey = "updateUserLevel-" . $info['id'];
            Users::updateUserExp($info);
            zmf::setFCache($cacheKey, 1, 3600);

            $cacheKey2 = "updateUserScore-" . $info['id'];
            $totalScore = ScoreLogs::statUserScore($info['id']);
            $info['score'] = $totalScore;
            Users::updateInfo($info['id'], 'score', $totalScore);
            zmf::setFCache($cacheKey2, 1, 3600);

            $cacheKey3 = "updateUserExp-" . $info['id'];
            $totalExp = UserAction::statUserExp($info['id']);
            $info['exp'] = $totalExp;
            Users::updateInfo($info['id'], 'exp', $totalExp);
            zmf::setFCache($cacheKey3, 1, 3600);
        }
        exit('well done');
    }

    public function actionUpdateUserActions() {
        $items = UserAction::model()->findAll(array(
            'select' => 'id,data'
        ));
        $from = array(
            'http://o7kv6kf92.bkt.clouddn.com',
            'http://img1.chuxincw.com',
        );
        $to = 'https://img2.chuxincw.com';
        foreach ($items as $val) {
            $_arr = CJSON::decode($val['data'], true);
            if (array_key_exists('bFaceImg', $_arr)) {
                $_arr['bFaceImg'] = str_replace($from, $to, $_arr['bFaceImg']);
                if (strpos($_arr['bFaceImg'], 'https://img2.chuxincw.com') === false) {
                    $_arr['bFaceImg'] = 'https://img2.chuxincw.com' . $_arr['bFaceImg'];
                }
            } elseif (array_key_exists('avatar', $_arr)) {
                $_arr['avatar'] = str_replace($from, $to, $_arr['avatar']);
                if (strpos($_arr['avatar'], 'https://img2.chuxincw.com') === false) {
                    $_arr['avatar'] = 'https://img2.chuxincw.com' . $_arr['avatar'];
                }
            } elseif (array_key_exists('faceimg', $_arr)) {
                $_arr['faceimg'] = str_replace($from, $to, $_arr['faceimg']);
                if (strpos($_arr['faceimg'], 'https://img2.chuxincw.com') === false) {
                    $_arr['faceimg'] = 'https://img2.chuxincw.com' . $_arr['faceimg'];
                }
            }
            UserAction::model()->updateByPk($val['id'], array(
                'data' => CJSON::encode($_arr)
            ));
        }
        exit('well done');
    }

}
