<?php

class FrontLogin extends CFormModel {

    public $phone;
    public $email;
    public $password;
    public $rememberMe;
    public $verifyCode;
    private $_identity;

    public function rules() {
        $rules = array(
            array('phone, password', 'required'),
            array('rememberMe', 'boolean'),
            array('email,phone', 'safe'),
            array('password', 'authenticate'),
        );
        $cookieInfo = zmf::getCookie('checkWithCaptcha');
        if (intval($cookieInfo) > 0) {
            $rules[] = array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements());
        }
        return $rules;
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'phone' => '手机号/邮箱',
            'email' => '邮箱',
            'password' => '密码',
            'rememberMe' => '记住我',
            'verifyCode' => '验证码',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        $this->_identity = new U($this->phone, $this->password);
        if (!$this->_identity->authenticate())
            $this->addError('password', '用户名或密码不正确.');
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new U($this->phone, $this->password);
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === U::ERROR_NONE) {
            $duration = 3600 * 24 * 30; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        } else
            return false;
    }

}
