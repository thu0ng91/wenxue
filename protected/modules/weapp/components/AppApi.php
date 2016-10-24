<?php

class AppApi extends Controller {

    public $appPlatform; //app平台    
    public $appCode;
    //必填参数
    public $version; //获取软件版本号
    public $time; //请求时客户端的时间戳
    public $usercode; //给用户的安全串
    public $userInfo;
    public $iosKey = 'AW0oQ9nMyqmF9erP';
    public $androidKey = '83nlir0NbKwfDaii';
    public $weappKey = '123456';//微信小程序
    public $pageSize;
    public $page;
    public $emptyarr;
    public $startTime; //运行时间记录
    public $currentTime; //开始时间

    function init() {
        $this->startTime = microtime(true);
        $this->currentTime = zmf::now();
        self::checkApp();
        parent::init();
        $_pageSize = self::getValue('pageSize', 0, 2);
        $this->pageSize = $_pageSize ? $_pageSize : 30;
        $page = self::getValue('page', 0, 2);
        $this->page = $page ? $page : 1;
        if ($this->page < 1) {
            $this->page = 1;
        }
        $this->emptyarr = new stdClass();
    }

    public function checkApp() {
        $code = self::getValue('a', 1, 1); //appcode
        $time = self::getValue('t', 1, 2); //time
        $version = self::getValue('v', 1, 1); //version        
        $platform = strtolower(self::getValue('p', 1, 1));
        if (!$code || !$time || !$platform || !$version) {
            self::output(self::getErrorInfo('notInService'), 403);
        }
        if ($platform == 'ios') {
            $_code = $this->iosKey;
            $this->appPlatform = 'ios';
        } elseif($platform=='android') {
            $_code = $this->androidKey;
            $this->appPlatform = 'android';
        }elseif($platform=='weapp') {
            $_code = $this->weappKey;
            $this->appPlatform = 'weapp';
        }
        $this->appCode=$_code;
        $this->version = $version;
        if (md5($time .$version. $_code) != $code) {
            self::output(self::getErrorInfo('dataIncorrect'), 403);
        }
    }

    public function checkUser() {
        if (!$this->usercode) {
            self::output('缺少参数：usercode', 0);
        }
        $code = zmf::jieMi($this->usercode);
        $arr = explode('#', $code);
        //如果不能解密字符串，或者不是类似于'123#ios#1412555521'则报错
        if (!$code || !$arr || count($arr) != 3 || $arr[1] != $this->appPlatform) {
            self::output('验证用户信息失败，请重新登录', 400);
        }
        $this->userInfo = User::model()->findByPk($arr[0]);
        if (!$this->userInfo) {
            self::output('验证用户信息错误：不存在的用户', 400);
        }
        //如果已经过期
        if ((zmf::now() - $arr[2]) > 86400 * 30) {
            self::output('由于长时间未登录，请重新登录', 400);
        }
    }

    /**
     * 统一已JSON输出
     * @param type $data 需要输出的内容
     * @param type $status 状态
     * @param type $code 状态码
     * @param type $encode 是否加密
     */
    public function output($data, $code = 1) {
        $outPutData = array(
            'msg' => $data,
            'code' => $code,
            'hash' => md5($this->currentTime .$this->version. $this->appCode),
            'time' => $this->currentTime
        );
        $json = CJSON::encode($outPutData);
        header("Content-type:application/json;charset=UTF-8");
        echo $json;
        if (zmf::config('appRuntimeLog')) {
            self::log();
            self::log(var_export($outPutData, true));
        }
        Yii::app()->end();
    }

    public function log($content = null) {
        if ($content !== null) {
            $content = '##' . zmf::time() . '===' . ('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']) . PHP_EOL;
            $content.=(microtime(true) - $this->startTime) . PHP_EOL;
            $content.=var_export($_POST, true) . PHP_EOL;
            $content.=str_repeat('-', 40);
        }
        file_put_contents(Yii::app()->basePath . '/runtime/appTimes.txt', $content . PHP_EOL, FILE_APPEND);
    }

    //==============____小函数___==============
    /**
     * 根据名称返回$_GET或$_POST的数据
     * @param type $key
     * @param type $notEmpty
     * @param type $ttype
     * @param type $textonly 0富文本，1纯文本，2数字，默认纯文本
     * @return type
     */
    public function getValue($key, $notEmpty = false, $textonly = 1) {
        $return = zmf::filterInput($_REQUEST[$key], $textonly);
        $arr = array(
            'uid' => '作者ID',
            'content' => '内容',
            'type' => '类型',
            'token' => '请求错误，请退出应用并重新启动',
        );
        if ($notEmpty) {
            if (empty($return)) {
                $_info = $arr[$key];
                if (!$_info) {
                    $_info = $key;
                }
                self::output('[' . $_info . ']不能为空', 0);
            }
        }
        return $return;
    }

    public function getByPage($params) {
        $sql = $params['sql'];
        if (!$sql) {
            return false;
        }
        $pageSize = (is_numeric($params['pageSize']) && $params['pageSize'] > 0) ? $params['pageSize'] : $this->pageSize;
        $page = (is_numeric($params['page']) && $params['page'] > 1) ? $params['page'] : $this->page;
        $bindValues = !empty($params['bindValues']) ? $params['bindValues'] : array();
        $bindValues[':offset'] = intval(($page - 1) * $pageSize);
        $bindValues[':limit'] = intval($pageSize);
        $com = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $com->bindValues($bindValues);
        $posts = $com->queryAll();
        return array(
            'posts' => $posts
        );
    }

    /**
     * 根据指定code返回错误信息
     * @param type $code
     * @return string
     */
    public function getErrorInfo($code) {
        $infoArr = array(
            'dataIncorrect' => '数据不正确',
            'notInApp' => '不允许的来源APP',
            'notInService' => '暂不能提供服务',
            'haveNoUid' => '缺失用户ID',
            'haveNoKeyid' => '缺失KEYID',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
        );
        return $infoArr[$code];
    }

}
