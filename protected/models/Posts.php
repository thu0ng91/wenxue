<?php

/**
 * This is the model class for table "{{posts}}".
 *
 * The followings are the available columns in table '{{posts}}':
 * @property integer $id
 * @property integer $uid
 * @property string $title
 * @property string $content
 * @property integer $faceimg
 * @property integer $classify
 * @property string $lat
 * @property string $long
 * @property integer $mapZoom
 * @property integer $comments
 * @property string $favors
 * @property string $favorite
 * @property integer $top
 * @property integer $hits
 * @property string $tagids
 * @property integer $status
 * @property integer $cTime
 * @property integer $updateTime
 */
class Posts extends CActiveRecord {

    const STATUS_NOTPASSED = 0;
    const STATUS_PASSED = 1;
    const STATUS_STAYCHECK = 2;
    const STATUS_DELED = 3;
    const CLASSIFY_POST = 1;
    const CLASSIFY_AUTHOR = 2;
    const CLASSIFY_READER = 3;
    //关于置顶    
    const STATUS_BOLD = 1; //仅加粗
    const STATUS_RED = 2; //仅标红
    const STATUS_BOLDRED = 3; //加粗标红
    //关于可否评论
    const STATUS_UNOPEN = 0;
    const STATUS_OPEN = 1;
    //关于来源
    const PLATFORM_UNKOWN = 0;
    const PLATFORM_WEB = 1;
    const PLATFORM_MOBILE = 2;
    const PLATFORM_ANDROID = 3;
    const PLATFORM_IOS = 4;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{posts}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid', 'default', 'setOnEmpty' => true, 'value' => zmf::uid()),
            array('cTime,updateTime', 'default', 'setOnEmpty' => true, 'value' => zmf::now()),
            array('status', 'default', 'setOnEmpty' => true, 'value' => Posts::STATUS_PASSED),
            array('uid, title, content', 'required'),
            array('uid, faceimg, classify, mapZoom, comments, top, hits, status, cTime, updateTime,styleStatus,open,platform', 'numerical', 'integerOnly' => true),
            array('title, tagids', 'length', 'max' => 255),
            array('lat, long', 'length', 'max' => 50),
            array('favors', 'length', 'max' => 11),
            array('favorite,zazhi,order,aid,forumid', 'length', 'max' => 10),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'userInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
            'authorInfo' => array(self::BELONGS_TO, 'Authors', 'aid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '用户ID',
            'aid' => '作者ID',
            'title' => '标题',
            'content' => '正文',
            'faceimg' => '封面图',
            'classify' => '分类',
            'comments' => '评论数',
            'favors' => '点赞数',
            'favorite' => '收藏数',
            'top' => '是否置顶',
            'hits' => '阅读数',
            'tagids' => '标签组',
            'status' => '状态',
            'cTime' => '创建世界',
            'updateTime' => '最近更新时间',
            'styleStatus' => '显示状态',
            'open' => '是否允许评论',
            'platform' => '来源',
            'forumid' => '所属版块',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Posts the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getOne($id) {
        return Posts::model()->findByPk($id);
    }

    public static function exStatus($type) {
        $arr = array(
            self::STATUS_NOTPASSED => '存草稿',
            self::STATUS_PASSED => '正式发布',
            self::STATUS_STAYCHECK => '投稿',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

    public static function exClassify($type) {
        $arr = array(
            self::CLASSIFY_AUTHOR => '作者专区',
            self::CLASSIFY_READER => '读者专区',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

    public static function exType($type) {
        $arr = array(
            'author' => self::CLASSIFY_AUTHOR,
            'reader' => self::CLASSIFY_READER,
        );
        if (is_numeric($type)) {
            $arr = array_flip($arr);
        }
        return $arr[$type];
    }

    public static function exTopClass($status) {
        if ($status < 1) {
            return '';
        }
        if ($status == Posts::STATUS_BOLD) {
            return 'bold';
        }
        if ($status == Posts::STATUS_RED) {
            return 'red';
        }
        if ($status == Posts::STATUS_BOLDRED) {
            return 'bold red';
        }
        return '';
    }

    public static function exPlatform($type) {
        $arr = array(
            self::PLATFORM_UNKOWN => '未知',
            self::PLATFORM_WEB => '网页',
            self::PLATFORM_MOBILE => '移动端',
            self::PLATFORM_ANDROID => '安卓客户端',
            self::PLATFORM_IOS => 'iOS客户端',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

    public static function encode($id, $type = 'post') {
        return zmf::jiaMi($id . '#' . $type);
    }

    public static function decode($code) {
        $_de = zmf::jieMi($code);
        $_arr = explode('#', $_de);
        return array(
            'id' => $_arr[0],
            'type' => $_arr[1],
        );
    }

    /**
     * 更新查看次数
     * @param type $keyid 对象ID
     * @param type $type 对象类型
     * @param type $num 更新数量
     * @param type $field 更新字段
     * @return boolean
     */
    public static function updateCount($keyid, $type, $num = 1, $field = 'hits') {
        if (!$keyid || !$type || !in_array($type, array('Books', 'Authors', 'Chapters', 'Tips', 'Users', 'Posts', 'Comments', 'GroupTasks', 'Task'))) {
            return false;
        }
        $model = new $type;
        return $model->updateCounters(array($field => $num), ':id=id', array(':id' => $keyid));
    }

    /**
     * 处理内容
     * @param type $content
     * @return type
     */
    public static function handleContent($content, $fullText = TRUE, $allowTags = '<b><strong><em><span><a><p><u><i><img><br><br/><div><blockquote><h1><h2><h3><h4><h5><h6><ol><ul><li><hr>') {
        if ($fullText) {
            $pattern = '/<img[\s\S]+?(data|mapinfo|video)=("|\')([^\2]+?)\2[^>]+?>/i';
            preg_match_all($pattern, $content, $matches);
            $arr_attachids = $arr_videoids = array();
            if (!empty($matches[0])) {
                $arr = array();
                foreach ($matches[0] as $key => $val) {
                    $_type = $matches[1][$key];
                    if ($_type == 'data') {//处理图片
                        $thekey = $matches[3][$key];
                        $imgsrc = $matches[0][$key];
                        $content = str_ireplace("{$imgsrc}", '[attach]' . $thekey . '[/attach]', $content);
                        $arr_attachids[] = $thekey;
                    } elseif ($_type == 'video') {//处理视频
                        $thekey = $matches[3][$key];
                        $imgsrc = $matches[0][$key];
                        $content = str_ireplace("{$imgsrc}", '[video]' . $thekey . '[/video]', $content);
                        $arr_videoids[] = $thekey;
                    } elseif ($_type == 'mapinfo') {//处理地图信息
                        $thekey = $matches[3][$key];
                        $imgsrc = $matches[0][$key];
                        $content = str_ireplace("{$imgsrc}", '[map]' . $thekey . '[/map]', $content);
                    }
                }
            }
            $content = strip_tags($content, $allowTags);
            $replace = array(
                "/style=\"[^\"]*?\"/i",
                "/<p><span>\&nbsp\;<\/span><\/p>/i",
                "/<p>\&nbsp\;<\/p>/i",
                "/<p><\/p>/i",
            );
            $to = array(
                ''
            );
            $content = preg_replace($replace, $to, $content);
            $content = zmf::removeEmoji($content);
        } else {
            $content = strip_tags($content);
            $content = zmf::removeEmoji($content);
        }
        $status = Posts::STATUS_PASSED;
        if (Words::checkWords($content)) {
            $status = Posts::STATUS_STAYCHECK;
        }
        $data = array(
            'content' => $content,
            'attachids' => $arr_attachids,
            'videoIds' => $arr_videoids,
            'status' => $status,
        );
        return $data;
    }

    public static function getAll($params, &$pages, &$comLists) {
        $sql = $params['sql'];
        if (!$sql) {
            return false;
        }
        $pageSize = $params['pageSize'];
        $_size = isset($pageSize) ? $pageSize : 30;
        $com = Yii::app()->db->createCommand($sql)->query();
        //添加限制，最多取1000条记录
        //todo，按不同情况分不同最大条数
        $total = $com->rowCount > 1000 ? 1000 : $com->rowCount;
        $pages = new CPagination($total);
        $criteria = new CDbCriteria();
        $pages->pageSize = $_size;
        $pages->applylimit($criteria);
        $com = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $com->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $com->bindValue(':limit', $pages->pageSize);
        $comLists = $com->queryAll();
    }

    public static function getByPage($params) {
        $sql = $params['sql'];
        if (!$sql) {
            return false;
        }
        $pageSize = (is_numeric($params['pageSize']) && $params['pageSize'] > 0) ? $params['pageSize'] : 30;
        $page = (is_numeric($params['page']) && $params['page'] > 1) ? $params['page'] : 1;
        $bindValues = !empty($params['bindValues']) ? $params['bindValues'] : array();
        $bindValues[':offset'] = ($page - 1) * $pageSize;
        $bindValues[':limit'] = $pageSize;
        $com = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
        $com->bindValues($bindValues);
        $posts = $com->queryAll();
        return $posts;
    }

    public static function getTopsByTag($tagid, $limit = 5) {
        $sql = "SELECT p.id,p.title FROM {{posts}} p,{{tag_relation}} tr WHERE tr.tagid='{$tagid}' AND tr.classify='posts' AND tr.logid=p.id AND p.status=" . self::STATUS_PASSED . " ORDER BY hits DESC LIMIT {$limit}";
        $items = Yii::app()->db->createCommand($sql)->queryAll();
        return $items;
    }

    public static function getTops($notId, $classify = Posts::CLASSIFY_POST, $limit = 5) {
        $sql = "SELECT id,title FROM {{posts}} WHERE classify='{$classify}' AND id!='{$notId}' AND status=" . Posts::STATUS_PASSED . " ORDER BY hits DESC LIMIT {$limit}";
        $items = Yii::app()->db->createCommand($sql)->queryAll();
        return $items;
    }

    public static function favorite($code, $type, $from = 'web', $userInfo = array()) {
        if (!$code || !$type) {
            return array('status' => 0, 'msg' => '数据不全，请核实');
        }
        if (!in_array($type, array('book', 'author', 'tip', 'user', 'post', 'comment'))) {
            return array('status' => 0, 'msg' => '暂不允许的分类');
        }
        if (is_numeric($code)) {
            $id = $code;
        } else {
            $codeArr = Posts::decode($code);
            if ($codeArr['type'] != $type || !is_numeric($codeArr['id']) || $codeArr['id'] < 1) {
                return array('status' => 0, 'msg' => '你所操作的内容不存在');
            }
            $id = $codeArr['id'];
        }
        if (!$userInfo['id']) {
            $uid = zmf::uid();
            $userInfo=  Users::getOne($uid);
        } else {
            $uid = $userInfo['id'];
        }
        if (!$uid) {
            return array('status' => 0, 'msg' => '请先登录');
        }
        if (zmf::actionLimit('favorite-' . $type, $id)) {
            return array('status' => 0, 'msg' => '操作太频繁，请稍后再试');
        }
        if ($type == 'book') {
            $postInfo = Books::getOne($id);
            $content = "收藏了你的小说【{$postInfo['title']}】";
            $noticeUid = $postInfo['uid'];
            //记录用户操作
            $jsonData = CJSON::encode(array(
                        'bid' => $id,
                        'bTitle' => $postInfo['title'],
                        'bDesc' => $postInfo['desc'],
                        'bFaceImg' => $postInfo['faceImg']
            ));
            $powerAction = 'favoriteBook';
        } elseif ($type == 'author') {
            $postInfo = Authors::getOne($id);
            $content = "关注了你的作者主页【{$postInfo['authorName']}】";
            $noticeUid = $postInfo['uid'];
            //记录用户操作
            $jsonData = CJSON::encode(array(
                        'aid' => $id,
                        'authorName' => $postInfo['authorName'],
                        'avatar' => $postInfo['avatar'],
                        'content' => $postInfo['content']
            ));
            $powerAction = 'favoriteAuthor';
        } elseif ($type == 'tip') {
            $postInfo = Tips::getOne($id);
            if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
                return array('status' => 0, 'msg' => '你所操作的内容不存在');
            }
            $chapterInfo = Chapters::getOne($postInfo['logid']);
            if (!$chapterInfo || $chapterInfo['status'] != Posts::STATUS_PASSED) {
                return array('status' => 0, 'msg' => '你所操作的内容不存在');
            }
            $content = "赞了你对【{$chapterInfo['title']}】的点评";
            $noticeUid = $postInfo['uid'];
            $powerAction = 'favorChapterTip';
        } elseif ($type == 'user') {
            $postInfo = Users::getOne($id);
            $content = "关注了你";
            $noticeUid = $id;
            $powerAction = 'favoriteUser';
        } elseif ($type == 'post') {
            $postInfo = Posts::getOne($id);
            if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
                return array('status' => 0, 'msg' => '你所操作的内容不存在');
            }
            $content = "赞了你的文章【{$postInfo['title']}】，" . CHtml::link('查看详情', array('posts/view', 'id' => $id));
            $noticeUid = $postInfo['uid'];
            $powerAction = 'favoritePost';
        } elseif ($type == 'comment') {
            $postInfo = Comments::getSimpleInfo($id);
            if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
                return array('status' => 0, 'msg' => '你所操作的内容不存在');
            }
            $content = "赞了你的评论。";
            $noticeUid = $postInfo['uid'];
            $powerAction = 'favorComment';
        }
        if (!$postInfo || $postInfo['status'] != Posts::STATUS_PASSED) {
            return array('status' => 0, 'msg' => '你所操作的内容不存在');
        }
        $powerInfo = GroupPowers::checkPower($userInfo, $powerAction);
        if (!$powerInfo['status']) {
            return array('status' => 0, 'msg' => $powerInfo['msg']);
        }
        $attr = array(
            'uid' => $uid,
            'logid' => $id,
            'classify' => $type
        );
        $info = Favorites::model()->findByAttributes($attr);
        if ($info) {
            if (Favorites::model()->deleteByPk($info['id'])) {
                if ($type == 'book') {
                    Posts::updateCount($id, 'Books', -1, 'favorites');
                } elseif ($type == 'author') {
                    Posts::updateCount($id, 'Authors', -1, 'favors');
                } elseif ($type == 'tip') {
                    Posts::updateCount($id, 'Tips', -1, 'favors');
                } elseif ($type == 'user') {
                    //更新被收藏的用户的粉丝数
                    Posts::updateCount($id, 'Users', -1, 'favors');
                    //增加我关注了的人数
                    Posts::updateCount($this->uid, 'Users', -1, 'favord');
                } elseif ($type == 'post') {
                    Posts::updateCount($id, 'Posts', -1, 'favorite');
                } elseif ($type == 'comment') {
                    Posts::updateCount($id, 'Comments', -1, 'favors');
                }
                //todo，取消的赞应扣除相应积分
                return array('status' => 1, 'msg' => '取消点赞', 'state' => 3);
            } else {
                return array('status' => 0, 'msg' => '取消点赞失败', 'state' => 4);
            }
        } else {
            $attr['cTime'] = zmf::now();
            $model = new Favorites();
            $model->attributes = $attr;
            if ($model->save()) {
                if ($type == 'book') {
                    Posts::updateCount($id, 'Books', 1, 'favorites');
                } elseif ($type == 'author') {
                    Posts::updateCount($id, 'Authors', 1, 'favors');
                } elseif ($type == 'tip') {
                    Posts::updateCount($id, 'Tips', 1, 'favors');
                } elseif ($type == 'user') {
                    //更新被收藏的用户的粉丝数
                    Posts::updateCount($id, 'Users', 1, 'favors');
                    //增加我关注了的人数
                    Posts::updateCount($uid, 'Users', 1, 'favord');
                } elseif ($type == 'post') {
                    Posts::updateCount($id, 'Posts', 1, 'favorite');
                } elseif ($type == 'comment') {
                    Posts::updateCount($id, 'Comments', 1, 'favors');
                }
                //点赞后给对方发提醒
                $_noticedata = array(
                    'uid' => $noticeUid,
                    'authorid' => $uid,
                    'content' => $content,
                    'new' => 1,
                    'type' => 'favorite',
                    'cTime' => zmf::now(),
                    'from_id' => $model->id,
                    'from_num' => 1
                );
                Notification::add($_noticedata);
                //记录用户操作
                //UserAction::recordAction($id, 'favorite' . ucfirst($type), $jsonData);
                $attr = array(
                    'uid' => $uid,
                    'logid' => $id,
                    'classify' => 'favorite' . ucfirst($type),
                    'data' => $jsonData,
                    'action' => $powerAction,
                    'score' => $powerInfo['msg']['score'],
                    'display' => 1,
                );
                if (UserAction::simpleRecord($attr)) {
                    //判断本操作是否同属任务
                    Task::addTaskLog($userInfo, $powerAction);
                }
                return array('status' => 1, 'msg' => '点赞成功', 'state' => 1);
            } else {
                return array('status' => 0, 'msg' => '点赞失败', 'state' => 2);
            }
        }
    }

    public static function getRelations($logid, $limit = 5) {
        if (!$logid) {
            return array();
        }
        $sql = "SELECT p.id,p.title FROM {{posts}} p INNER JOIN (SELECT logid,count(logid) AS times FROM {{tag_relation}} WHERE tagid IN(SELECT tagid FROM {{tag_relation}} WHERE logid='$logid') GROUP BY logid ORDER BY times DESC) tmp ON p.id=tmp.logid WHERE p.id!='$logid' AND p.status=" . Posts::STATUS_PASSED . " LIMIT $limit";
        $items = Yii::app()->db->createCommand($sql)->queryAll();
        return $items;
    }

    public static function updateCommentsNum($id) {
        if (!$id) {
            return false;
        }
        $num = Comments::model()->count("logid=:logid AND classify='posts' AND `status`=" . Posts::STATUS_PASSED, array(
            ':logid' => $id
        ));
        return Posts::model()->updateByPk($id, array('comments' => $num));
    }

}
