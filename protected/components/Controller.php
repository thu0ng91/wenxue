<?php

class Controller extends CController {

    public function message($status = 0, $message = '', $url = '', $time = 3, $jump = true, $render = true) {
        $this->layout='common';
        if (empty($url)) {
            $url = Yii::app()->user->returnUrl;
        }
        if ($status) {
            $success = $message;
        } else {
            $error = $message;
        }
        $data = array(
            'error' => $error,
            'success' => $success,
            'jumpUrl' => $url,
            'waitSecond' => $time,
            'jumpStatus' => $jump
        );
        if ($render) {
            $this->render('//msg/error', $data);
        } else {
            $this->renderPartial('//msg/error', $data);
        }
        Yii::app()->end();
    }

    public function jsonOutPut($status = 0, $msg = '', $return = false, $end = true) {
        $outPutData = array(
            'status' => $status,
            'msg' => $msg
        );
        $json = CJSON::encode($outPutData);
        if ($return) {
            return $json;
        } else {
            echo $json;
        }
        if ($end) {
            Yii::app()->end();
        }
    }

    /**
     * 判断用户是否有权限
     * @param type $type 判断权限类型
     * @param type $fuid 用户ID，默认为当前登录用户
     * @param type $return 是否返回
     * @param type $json 是否以JSON格式输出
     * @return boolean
     */
    public function checkPower($type, $fuid = '', $return = false, $json = false) {
        $uid = $fuid ? $fuid : Yii::app()->user->id;
        if (!$uid) {
            if ($return) {
                return false;
            } elseif (!$json AND ! Yii::app()->request->isAjaxRequest) {
                $this->redirect(array('/site/login'));
            } else {
                $this->jsonOutPut(0, '请先登录');
            }
        }
        if ($type == 'login') {
            $pinfo = Admins::model()->find('uid=:uid', array(':uid' => $uid));
            if (!$pinfo) {
                if ($return) {
                    return false;
                } elseif (!$json AND ! Yii::app()->request->isAjaxRequest) {
                    throw new CHttpException(403, '您无权该操作');
                } else {
                    $this->jsonOutPut(0, '不是管理员');
                }
            }
            return true;
        }
        $power = Admins::model()->find('powers=:p AND uid=:uid', array(':p' => $type, ':uid' => $uid));
        if (!$power) {
            if ($return) {
                return false;
            } elseif (!$json AND ! Yii::app()->request->isAjaxRequest) {
                throw new CHttpException(403, '您无权该操作');
            } else {
                $this->jsonOutPut(0, '您无权该操作');
            }
        }
        return true;
    }

}
