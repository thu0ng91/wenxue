<?php

class Comments extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{comments}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid, logid,content,classify, status, cTime', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('uid,aid,logid,tocommentid, cTime', 'length', 'max' => 10),
            array('content,username,email,ipInfo', 'length', 'max' => 255),
            array('platform, classify,ip', 'length', 'max' => 16),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'authorInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
            'postInfo' => array(self::BELONGS_TO, 'Posts', 'logid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '作者',
            'logid' => '文章ID',
            'tocommentid' => '回复楼层',
            'content' => '类型',
            'platform' => '平台',
            'classify' => '分类',
            'status' => '状态',
            'cTime' => '回复时间',
            'username' => '用户名',
            'email' => '邮箱地址',
            'ip' => 'IP',
            'ipInfo' => 'IP信息',
            'aid' => '作者ID',
        );
    }

    public function beforeSave() {
        $ip = Yii::app()->request->userHostAddress;
//        $key = 'ipInfo-' . $ip;
//        $ipData = zmf::getCookie($key);
//        if (!$ipData) {
//            $url = 'http://apis.baidu.com/apistore/iplookupservice/iplookup?ip=' . $ip;
//            // 执行HTTP请求
//            $header = array(
//                'apikey:e5882e7ac4b03c5d6f332b6de4469e81',
//            );
//            $ch = curl_init();
//            // 添加apikey到header
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_URL, $url);
//            $res = curl_exec($ch);
//            $res = CJSON::decode($res, true);
//            $retData=array();
//            if ($res['errNum'] == 0) {
//                $retData = $res['retData'];
//            }
//            $ipData = json_encode($retData);
//            zmf::setCookie($key, $ipData,2592000);
//        }
        $this->ip = ip2long($ip);
        //$this->ipInfo = $ipData;
        return true;
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getSimpleInfo($keyid) {
        $info = Comments::model()->findByPk($keyid);
        return $info;
    }

    public static function getCommentsByPage($id, $classify, $page = 1, $pageSize = 30, $field = "c.id,c.uid,u.truename,c.aid,c.logid,c.tocommentid,c.content,c.cTime,c.status") {
        if (!$id || !$classify) {
            return array();
        }
        $page = $page <= 1 ? 1 : $page;
        $pageSize = !$pageSize ? 30 : $pageSize;
        $limitStart = ($page - 1) * $pageSize;
        $sql = "SELECT {$field} FROM {{comments}} c,{{users}} u WHERE c.logid='{$id}' AND c.classify='{$classify}' AND c.status=" . Posts::STATUS_PASSED . " AND c.uid=u.id AND u.status=" . Posts::STATUS_PASSED . " ORDER BY c.cTime ASC LIMIT {$limitStart},{$pageSize}";
        $items = Yii::app()->db->createCommand($sql)->queryAll();
        if (!empty($items)) {
            $uids = array_filter(array_keys(CHtml::listData($items, 'aid', '')));
            $uidsStr = join(',', $uids);
            $usernames = array();
            if ($uidsStr != '') {
                $usernames = Yii::app()->db->createCommand("SELECT id,authorName,avatar FROM {{authors}} WHERE id IN($uidsStr)")->queryAll();
            }
            foreach ($items as $k => $val) {
                $find = false;
                if (!empty($usernames)) {
                    foreach ($usernames as $val2) {
                        if ($val['aid'] > 0 && $val['aid'] == $val2['id']) {
                            $items[$k]['userInfo']['type'] = 'author';
                            $items[$k]['userInfo']['id'] = $val2['id'];
                            $items[$k]['userInfo']['username'] = $val2['authorName'];
                            $items[$k]['userInfo']['linkArr'] = array('author/view', 'id' => $val2['id']);
                            $items[$k]['userInfo']['avatar'] =  zmf::getThumbnailUrl($val2['avatar'],'d120','author');
                            $find = true;
                            break;
                        }
                    }
                }
                if (!$find) {
                    $items[$k]['userInfo']['type'] = 'user';
                    $items[$k]['userInfo']['id'] = $val['uid'];
                    $items[$k]['userInfo']['username'] = $val['truename'];
                    $items[$k]['userInfo']['linkArr'] = array('user/index', 'id' => $val['uid']);
                    $items[$k]['userInfo']['avatar'] =  zmf::getThumbnailUrl($val['avatar'],'d120','user');
                }
                unset($items[$k]['truename']);
            }
            $tocommentIdstr = join(',', array_filter(array_unique(array_values(CHtml::listData($items, 'id', 'tocommentid')))));
            if ($tocommentIdstr != '') {
                $_sql = "SELECT t.id,t.uid,t.aid,u.truename FROM {{comments}} t,{{users}} u WHERE t.id IN({$tocommentIdstr}) AND t.classify='{$classify}' AND t.status=" . Posts::STATUS_PASSED . " AND t.uid=u.id AND u.status=" . Posts::STATUS_PASSED;
                $_userInfoArr = Yii::app()->db->createCommand($_sql)->queryAll();
                $toaids = array_filter(array_keys(CHtml::listData($_userInfoArr, 'aid', '')));
                $toaidsStr = join(',', $toaids);
                $toAuthors = array();
                if ($toaidsStr != '') {
                    $toAuthors = Yii::app()->db->createCommand("SELECT id,authorName FROM {{authors}} WHERE id IN($toaidsStr)")->queryAll();
                }  
                foreach ($items as $k => $tip) {
                    $items[$k]['replyInfo'] = array();
                    if (!$tip['tocommentid']) {
                        continue;
                    }
                    foreach ($_userInfoArr as $_val) {
                        if ($tip['tocommentid'] == $_val['id']) {
                            if($_val['aid']>0){
                                foreach ($toAuthors as $_val2){
                                    if($_val2['id']==$_val['aid']){
                                        $items[$k]['replyInfo']['username'] = $_val2['authorName'];
                                        $items[$k]['replyInfo']['linkArr'] = array('author/view', 'id' => $_val2['id']);
                                        break;
                                    }
                                }
                            }else{
                                $items[$k]['replyInfo']['username'] = $_val['truename'];
                                $items[$k]['replyInfo']['linkArr'] = array('user/index', 'id' => $_val['uid']);
                            }
                            continue;
                        }
                    }
                }
            }
        }
        return $items;
    }

}
