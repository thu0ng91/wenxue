<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class U extends CUserIdentity {

    private $_id;
    public $username;

    public function __construct($username, $password) {
        parent::__construct($username, $password);        
        $this->username=$username;
    }

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {        
        $user = Users::model()->find('truename=:truename', array(':truename'=>  $this->username)); 
        if ($user === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if($user['status']!=Posts::STATUS_PASSED)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if (!$this->validatePassword($user->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id = $user->id;
            $this->username = $user->truename;
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId() {
        return $this->_id;
    }

    public function validatePassword($password) {
        //echo $password.'@####@'.$this->password;

        return md5($this->password) == $password ? true : false;
    }

}
