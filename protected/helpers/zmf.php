<?php

class zmf {

    /**
     * 打印数组
     * @param type $data
     */
    public static function test($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public static function uid() {
        if (Yii::app()->user->isGuest) {
            return 0;
        } else {
            return Yii::app()->user->id;
        }
    }

    public static function powered() {
        return CHtml::link('新灵中国 提供技术支持', 'http://newsoul.cn', array('target' => '_blank'));
    }

    /**
     * file_put_contents到某个文件下
     * @param type $msg
     * @param type $arr
     */
    public static function fp($msg, $arr = 0) {
        if ($arr) {
            file_put_contents(Yii::app()->basePath . '/runtime/log.txt', var_export($msg, true) . PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents(Yii::app()->basePath . '/runtime/log.txt', $msg . PHP_EOL, FILE_APPEND);
        }
    }

    public static function config($type) {
        if ($type == 'authorCode') {
            return 'zhangmaofei@2015';
        }
        if (empty(Yii::app()->params['c'])) {
            $_c = Config::model()->findAll();
            $configs = CHtml::listData($_c, 'name', 'value');
            self::writeSet($configs);
            return stripcslashes($configs[$type]);
        } else {
            return stripcslashes(Yii::app()->params['c'][$type]);
        }
    }

    /**
     * 将配置写入缓存
     * @param type $array
     * @param type $classify
     * @return boolean
     */
    public static function writeSet($array) {
        $dir = Yii::app()->basePath . "/runtime/config/";
        zmf::createUploadDir($dir);
        $dir = $dir . 'zmfconfig.php';
        $values = array_values($array);
        $keys = array_keys($array);
        $len = count($keys);
        $config = "<?php\n";
        $config .= "return array(\n";
        for ($i = 0; $i < $len; $i++) {
            $config .= "'" . $keys[$i] . "'=> '" . addslashes($values[$i]) . "',\n";
        }
        $config .= ");\n";
        $config .= "?>";
        $fp = fopen($dir, 'w');
        $fw = fwrite($fp, $config);
        if (!$fw) {
            fclose($fp);
            return false;
        } else {
            fclose($fp);
            return true;
        }
    }

    public static function readTxt($file, $mode = 'r') {
        if (is_readable($file)) {
            $handle = fopen($file, $mode);
            flock($handle, LOCK_EX);
            $content = fread($handle, filesize($file));
            flock($handle, LOCK_UN);
            fclose($handle);
            return $content;
        } else {
            return false;
        }
    }

    public static function stripStr($string) {
        $string = strip_tags($string);
        $replace = array(
            '/\[attach\](\d+)\[\/attach\]/i',
            '/\[atone\](\d+)\[\/atone\]/i',
            "/\[url=.+?\](.+?)\[\/url\]/i",
            "/\[texturl=.+?\].+?\[\/texturl\]/i",
            "/\[poi=.+?\](.+?)\[\/poi\]/i",
        );
        $to = array(
            '',
            '',
            '$1',
            '',
            '$1',
        );
        $string = preg_replace($replace, $to, $string);
        return $string;
    }

    public static function subStr($string, $sublen = 20, $start = 0, $separater = '...') {
        $string = self::stripStr($string);
        $code = 'UTF-8';
        if ($code == 'UTF-8') {
            $string = strip_tags($string);
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);
            if (count($t_string[0]) - $start > $sublen) {
                $str = join('', array_slice($t_string[0], $start, $sublen));
                return $str . $separater;
            } else {
                return join('', array_slice($t_string[0], $start, $sublen));
            }
        } else {
            $start = $start * 2;
            $sublen = $sublen * 2;
            $strlen = strlen($string);
            $tmpstr = '';
            for ($i = 0; $i < $strlen; $i++) {
                if ($i >= $start && $i < ($start + $sublen)) {
                    if (ord(substr($string, $i, 1)) > 129) {
                        $tmpstr .= substr($string, $i, 2);
                    } else {
                        $tmpstr .= substr($string, $i, 1);
                    }
                }
                if (ord(substr($string, $i, 1)) > 129)
                    $i++;
            }
            if (strlen($tmpstr) < $strlen)
                $tmpstr .= $separater;
            return $tmpstr;
        }
    }

    /**
     * 循环创建目录
     * @param type $dir
     */
    public static function createUploadDir($dir) {
        if (!is_dir($dir)) {
            $temp = explode('/', $dir);
            $cur_dir = '';
            for ($i = 0; $i < count($temp); $i++) {
                $cur_dir .= $temp[$i] . '/';
                if (!is_dir($cur_dir)) {
                    mkdir($cur_dir, 0777);
                }
            }
        }
    }

    public static function uploadDirs($ctime = '', $base = 'site', $type = 'posts') {
        if (!$ctime) {
            $ctime = zmf::now();
        }
        $baseUrl = self::attachBase($base, $type);
        $_extra = self::getUpExtraUrl($ctime);
        $dir = $baseUrl . $type . '/' . $_extra . '/';
        return $dir;
    }

    public static function attachBase($base, $type = '') {
        if ($base === 'site') {
            //根据网站          
            if (self::config('imgVisitUrl') != '') {
                $baseUrl = self::config('imgVisitUrl') . '/';
            } else {
                $baseUrl = self::config('baseurl') . 'attachments/';
            }
            //如果是本地开发且是获取头像时，则不走云存储
            if (self::config('appStatus') == '1' && in_array($type, array('avatar'))) {
                $baseUrl = self::config('baseurl') . 'attachments/';
            }
        } elseif ($base === 'app') {
            //根据应用来
            if (self::config('imgUploadUrl') != '') {
                $baseUrl = self::config('imgUploadUrl') . '/';
            } else {
                $baseUrl = Yii::app()->basePath . "/../attachments/";
            }
        } elseif ($base == 'upload') {
            //解决imagick open图片问题
            if (self::config('imgUploadUrl') != '') {
                $baseUrl = self::config('imgUploadUrl') . '/';
            } else {
                $baseUrl = self::config('baseurl') . 'attachments/';
            }
        } else {
            $baseUrl = '';
        }
        return $baseUrl;
    }

    /**
     * 按时间生产文件路径
     * @param type $date
     * @return string
     */
    public static function getUpExtraUrl($date = '') {
        if (!$date) {
            $date = zmf::now();
        }
        $_extra = zmf::time($date, 'Y') . '/' . zmf::time($date, 'm') . '/' . zmf::time($date, 'd');
        return $_extra;
    }

    public static function lazyImg() {
        return zmf::config('baseurl') . 'common/images/grey.gif';
    }

    public static function getThumbnailUrl($url, $size = '', $type = '') {
        if(!$url){
            return '';
        }
        //c132|c120|c180|c105|c360
        $appStatus = self::config('appStatus');
        $visitUrl = self::config('imgVisitUrl');
        $csize = ($size != '' && $visitUrl != '') ? (is_numeric($size) ? '/c' . $size : '/' . $size) : '';
        $reurl = $url . $csize;
        return $reurl;
    }

    /**
     * memcache
     * @param type $key
     * @param type $value
     * @param type $expire
     */
    public static function setCache($key, $value, $expire = '60') {
        Yii::app()->cache->set($key, $value, $expire);
    }

    public static function getCache($key) {
        return Yii::app()->cache->get($key);
    }

    public static function delCache($key) {
        Yii::app()->cache->delete($key);
    }

    /**
     * 文件缓存
     * @param type $key
     * @param type $value
     * @param type $expire
     */
    public static function setFCache($key, $value, $expire = '60') {
        Yii::app()->filecache->set($key, $value, $expire);
    }

    public static function getFCache($key) {
        return Yii::app()->filecache->get($key);
    }

    public static function updateFCacheCounter($key, $value, $expire = 3600) {
        $_value = self::getFCache($key);
        if ($_value) {
            $value = (int) $value + (int) $_value;
        }
        self::setFCache($key, $value, $expire);
    }

    public static function delFCache($key) {
        Yii::app()->filecache->delete($key);
    }
    
    public static function checkFCache($key){
        $info=  self::getFCache($key);
        return $info;
    }

    public static function setCookie($key, $value, $expire = 3600) {
        $key = md5($key);
        header("P3P: CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR");
        $ck = new CHttpCookie($key, $value);
        if (!$value) {
            $ck->expire = zmf::now() - 3600;
        } else {
            $ck->expire = zmf::now() + $expire;
        }
        if (zmf::config('cookieDomain') != '') {
            $ck->domain = zmf::config('cookieDomain');
        }
        Yii::app()->request->cookies[$key] = $ck;
    }

    public static function getCookie($key) {
        $key = md5($key);
        $value = isset(Yii::app()->request->cookies[$key]) ? Yii::app()->request->cookies[$key]->value : '';
        return $value;
    }

    public static function delCookie($key) {
        $key = md5($key);
        $cookie = Yii::app()->request->getCookies();
        unset($cookie[$key]);
    }

    public static function updateCookieCounter($key, $value, $expire = 3600) {
        $_value = self::getCookie($key);
        if ($_value) {
            $value = (int) $value + (int) $_value;
        }
        self::setCookie($key, $value, $expire);
    }

    /**
     * 过滤输入
     * @param type $str
     * @param type $type
     * @param type $textonly 0富文本，1纯文本，2数字
     * @return type
     */
    public static function filterInput($str, $textonly = 0) {
        if ($textonly == 1) {
            $str = strip_tags(trim($str));
        } elseif ($textonly == 2) {
            $str = self::myint($str);
        }
        if ($textonly != 2) {
            $str = self::removeEmoji($str);
        }
        return $str;
    }

    /**
     * 仅返回整数
     * intval、(int)在32位系统上有问题
     * @param type $s
     * @return type
     */
    public static function myint($s) {
        return($a = preg_replace('/[^\-\d]*(\-?\d*).*/', '$1', $s)) ? $a : '0';
    }

    /**
     * 删除苹果自带emoji表情
     * @param type $text
     * @return type
     */
    public static function removeEmoji($text) {
        $clean_text = "";
        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, '', $text);
        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, '', $clean_text);
        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, '', $clean_text);
        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, '', $clean_text);
        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);
        return $clean_text;
    }

    /**
     * 遍历目录下所有文件
     * @param type $dir
     * @return type
     */
    public static function readDir($dir, $name = true) {
        $name_arr = array();
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        if ($name) {
                            $_tmp = explode('.', $file);
                            $name_arr[] = $_tmp[0];
                        } else {
                            $name_arr[] = $file;
                        }
                    }
                }
                closedir($dh);
            }
        }
        return $name_arr;
    }

    //判断是平板电脑还是手机
    public static function checkmobile(&$platform) {
        if (!self::config("mobile")) {
            return false;
            exit();
        }
        $mobile = array();
        static $mobilebrowser_list = array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
            'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
            'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
            'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
            'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
            'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
            'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
        $pad_list = array('pad', 'gt-p1000');

        $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (self::dstrpos($useragent, $pad_list)) {
            return false;
        }
        if (($v = self::dstrpos($useragent, $mobilebrowser_list, true))) {
            $platform = $v;
            return true;
        }
        $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
        if (self::dstrpos($useragent, $brower))
            return false;
    }

    public static function dstrpos($string, &$arr, $returnvalue = false) {
        if (empty($string))
            return false;
        foreach ((array) $arr as $v) {
            if (strpos($string, $v) !== false) {
                $return = $returnvalue ? $v : true;
                return $return;
            }
        }
        return false;
    }

    public static function checkFromWeixin() {
        $ua = Yii::app()->request->userAgent;
        if (strpos($ua, 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 获取当前时间戳
     * @param type $timestamp
     * @return type
     */
    public static function now($timestamp = '') {
        $timeset = date_default_timezone_get();
        if (!in_array($timeset, array('Etc/GMT-8', 'PRC', 'Asia/Shanghai', 'Asia/Shanghai', 'Asia/Chongqing'))) {
            date_default_timezone_set('Etc/GMT-8');
        }
        if ($timestamp == '') {
            return time();
        } else {
            return strtotime($timestamp, time());
        }
    }

    /**
     * 普通格式化时间戳
     * @param type $time
     * @param type $format
     * @return type
     */
    public static function time($time = '', $format = 'Y-m-d H:i:s') {
        if (!$time) {
            $time = zmf::now();
        }
        $timeset = date_default_timezone_get();
        if (!in_array($timeset, array('Etc/GMT-8', 'PRC', 'Asia/Shanghai', 'Asia/Shanghai', 'Asia/Chongqing'))) {
            date_default_timezone_set('Etc/GMT-8');
        }
        return date($format, $time);
    }

    /**
     * 格式化时间戳
     * @param type $date
     * @return string
     */
    public static function formatTime($date) {
        $thisyear = intval(zmf::time(NULL, 'Y'));
        $dateyear = intval(zmf::time($date, 'Y'));
        if (($thisyear - $dateyear) > 0) {
            return zmf::time($date, 'Y-m-d H:i');
        }
        $thismo = intval(zmf::time(NULL, 'm'));
        $datemo = intval(zmf::time($date, 'm'));
        if ($thisyear == $dateyear && $thismo != $datemo) {
            return zmf::time($date, 'm-d H:i');
        }
        $timer = $date;
        $diff = zmf::now() - $timer;
        $thisto = intval(zmf::time(NULL, 'd'));
        $dateto = intval(zmf::time($date, 'd'));
        $day = $thisto - $dateto;
        $free = $diff % 86400;
        if ($day > 0) {
            if ($day > 7) {
                return zmf::time($date, 'n-j H:i');
            } elseif ($day == 1) {
                return "昨天 " . zmf::time($date, 'H:i');
            } elseif ($day == 2) {
                return "前天 " . zmf::time($date, 'H:i');
            } else {
                return $day . "天前";
            }
        } else {
            if ($free > 0) {
                $hour = floor($free / 3600);
                $free = $free % 3600;
                if ($hour > 0) {
                    return $hour . "小时前";
                } else {
                    if ($free > 0) {
                        $min = floor($free / 60);
                        $free = $free % 60;
                        if ($min > 0) {
                            return $min . "分钟前";
                        } else {
                            if ($free > 0) {
                                return $free . "秒前";
                            } else {
                                return '刚刚';
                            }
                        }
                    } else {
                        return '刚刚';
                    }
                }
            } else {
                return '刚刚';
            }
        }
    }

    /**
     * 格式化显示文件大小
     * @param int $size
     * @return type
     */
    public static function formatBytes($size) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++)
            $size /= 1024;
        return round($size, 2) . $units[$i];
    }

    /**
     * 获取中文的拼音
     * @param type $string
     * @return type
     */
    public static function pinyin($string) {
        $dir = Yii::app()->basePath . '/data/pinyin_table.php';
        if (file_exists($dir)) {
            $pinyin = include $dir;
        } else {
            return $string;
            exit;
        }
        $arr = explode('\\', strtoupper(str_replace('"', '', json_encode(urldecode($string)))));
        $arr = array_values(array_filter($arr));
        for ($i = 0; $i < count($arr); $i++) {
            $_pin.=$pinyin['\\' . $arr[$i]] . '';
        }
        return strtolower($_pin);
    }

    /**
     * 给文本中的链接加链接
     * @param type $content
     * @return type
     */
    public static function addcontentlink($content) {
        //在处理之前，先要把a或img标签内的排除，先替换
        preg_match_all('/<a.*?href=".*?".*?>.*?<\/a>/i', $content, $linkList);
        $linkList = $linkList[0];
        $str = preg_replace('/<a.*?href=".*?".*?>.*?<\/a>/i', '<{link}>', $content);
        //提取替换出所有的IMG标签（统一标记<{img}>）
        preg_match_all('/<img[^>]+>/im', $content, $imgList);
        $imgList = $imgList[0];
        $str = preg_replace('/<img[^>]+>/im', '<{img}>', $str);
        $str = preg_replace('/(((http|https):\/\/)[a-z0-9;&#@=_~%\?\/\.\,\+\-\!\:]+)/ie', "self::strip_link('$1')", $str);
        if (strpos($str, "http") === FALSE) {
            $str = preg_replace('/(www.[a-z0-9;&#@=_~%\?\/\.\,\+\-\!\:]+)/ie', "self::strip_link('$1')", $str);
        } else {
            $str = preg_replace('/([[:space:]()[{}])(www.[a-z0-9;&#@=_~%\?\/\.\,\+\-\!\:]+)/i', '\1<a href="http://\2" target=_blank rel=nofollow>\2</a>', $str);
        }
        //还原A统一标记为原来的A标签
        $arrLen = count($linkList);
        for ($i = 0; $i < $arrLen; $i++) {
            $str = preg_replace('/<{link}>/', $linkList[$i], $str, 1);
        }
        //还原IMG统一标记为原来的IMG标签
        $arrLen2 = count($imgList);
        for ($i = 0; $i < $arrLen2; $i++) {
            $str = preg_replace('/<{img}>/', $imgList[$i], $str, 1);
        }
        return $str;
    }

    public static function strip_link($link) {
        $link = trim(htmlspecialchars_decode($link));
        return '<a href="' . $link . '" target=_blank rel=nofollow >' . $link . '</a>';
    }

    /**
     * 加密
     * @param type $plain_text
     * @return type
     */
    public static function jiaMi($plain_text) {
        $key = zmf::config('authorCode');
        $plain_text = trim($plain_text);
        Yii::import('application.vendors.*');
        require_once 'rc4crypt.php';
        $rc4 = new Crypt_RC4();
        $rc4->setKey($key);
        $text = $plain_text;
        $x = $rc4->encrypt($text);
        return $x;
    }

    /**
     * 解密
     * @param type $string
     * @return type
     */
    public static function jieMi($string) {
        $key = zmf::config('authorCode');
        $plain_text = trim($string);
        Yii::import('application.vendors.*');
        require_once 'rc4crypt.php';
        $rc4 = new Crypt_RC4();
        $rc4->setKey($key);
        $text = $plain_text;
        $x = $rc4->decrypt($text);
        return $x;
    }

    /**
     * 
     * @param type $multi_array 二维数组
     * @param type $sort_key 根据数组中某项排序
     * @param type $sort 排序方式
     * @return boolean
     */
    public static function multi_array_sort($multi_array, $sort_key, $sort = SORT_ASC) {
        if (is_array($multi_array)) {
            foreach ($multi_array as $row_array) {
                if (is_array($row_array)) {
                    $key_array[] = $row_array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_array, $sort, $multi_array);
        return $multi_array;
    }

    /**
     * 过滤数组中重复的成员
     * @param type $array
     * @return type
     */
    public static function super_unique($array) {
        $result = array_map("unserialize", array_unique(array_map("serialize", $array)));
        foreach ($result as $key => $value) {
            if (is_array($value)) {
                $result[$key] = self::super_unique($value);
            }
        }
        return $result;
    }

    public static function uniqueArr($array2D, $stkeep = false, $ndformat = true) {
        // 判断是否保留一级数组键 (一级数组键可以为非数字)
        if ($stkeep)
            $stArr = array_keys($array2D);

        // 判断是否保留二级数组键 (所有二级数组键必须相同)
        if ($ndformat)
            $ndArr = array_keys(end($array2D));

        //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
        foreach ($array2D as $v) {
            $v = join(",", $v);
            $temp[] = $v;
        }

        //去掉重复的字符串,也就是重复的一维数组
        $temp = array_unique($temp);

        //再将拆开的数组重新组装
        foreach ($temp as $k => $v) {
            if ($stkeep)
                $k = $stArr[$k];
            if ($ndformat) {
                $tempArr = explode(",", $v);
                foreach ($tempArr as $ndkey => $ndval)
                    $output[$k][$ndArr[$ndkey]] = $ndval;
            } else
                $output[$k] = explode(",", $v);
        }

        return $output;
    }

    public static function arrayDiff($array1, $array2) {
        $ret = array();
        foreach ($array1 as $k => $v) {
            if (!isset($array2[$k]))
                $ret[$k] = $v;
            else if (is_array($v) && is_array($array2[$k]))
                $ret[$k] = self::arrayDiff($v, $array2[$k]);
            else if ((string) $v != (string) $array2[$k])
                $ret[$k] = $v;
        }
        return $ret;
    }

    /**
     * strpos变种，用来检查字符串中是否包含数组中的字符串
     * @param type $haystack 要检查的字符串
     * @param type $needles 数组（不限二维）或要查找的词
     * @return type
     */
    public static function strpos_array($haystack, $needles) {
        if (is_array($needles)) {
            $find = false;
            foreach ($needles as $str) {
                if (is_array($str)) {
                    $pos = self::strpos_array($haystack, $str);
                } else {
                    $pos = strpos($haystack, $str);
                }
                if ($pos !== FALSE) {
                    $find = true;
                    break;
                }
            }
            return $find;
        } else {
            if (strpos($haystack, $needles) !== false) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * curl请求
     * @param type $remote_server
     * @param type $post_string
     * @return type
     */
    public static function request_by_curl($remote_server, $post_string) {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public static function randMykeys($length, $type = 1) {
        if ($type == 2) {
            $pattern = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';    //字符池,可任意修改
            $len = 51;
        } elseif ($type == 3) {
            $pattern = '1234567890';
            $len = 9;
        } else {
            $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';    //字符池,可任意修改
            $len = 61;
        }
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, $len)};    //生成php随机数
        }
        return $key;
    }

    public static function simpleRand($length) {
        $pattern = '1234567890';    //字符池,可任意修改
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 9)};    //生成php随机数
        }
        return $key;
    }

    /**
     * 生成不重复串
     * @param int $begin
     * @param int $end
     * @param int $limit 返回个数
     * @return type
     */
    public static function noRand($begin = 0, $end = 20, $limit = 5) {
        $rand_array = range($begin, $end);
        shuffle($rand_array); //调用现成的数组随机排列函数
        return array_slice($rand_array, 0, $limit); //截取前$limit个
    }

    /**
     * 获取传参
     * @param type $key，参数的键名
     * @param type $ttype,传参类型,n:数字,t:文本
     * @param type $textonly,是否纯文本
     * @return boolean
     */
    public static function val($key, $textonly = 1) {
        $return = zmf::filterInput(Yii::app()->request->getParam($key), $textonly);
        return $return;
    }

    public static function text($params, $content, $lazyload = false, $size = 600) {
        if (!$content) {
            return $content;
        }
        if (is_array($params)) {
            $width = $params['imgwidth'];
            $action = $params['action'];
            $encode = $params['encode'];
        }
        if (!$width) {
            $width = $size;
        }
        if ($action != 'edit') {
            $content = zmf::addcontentlink($content);
        } else {
            $lazyload = false;
        }
        if (strpos($content, '[attach]') !== false) {
            preg_match_all("/\[attach\](\d+)\[\/attach\]/i", $content, $match);
            if (!empty($match[1])) {
                foreach ($match[1] as $key => $val) {
                    $thekey = $match[0][$key];
                    $src = Attachments::getOne($val);
                    if ($src) {
                        $_originImgUrl = Attachments::getUrl($src, '');
                        $_imgurl = zmf::getThumbnailUrl($_originImgUrl, $size, $src['classify']);
                        $_zoomImgUrl = zmf::getThumbnailUrl($_originImgUrl, '960', $src['classify']);
                        if ($lazyload) {
                            $_width = $_height = 0;
                            if ($src['width'] <= $width) {
                                $_width = $src['width'];
                                $_height = $src['height'];
                            } else {
                                $_width = $width;
                            }
                            //$_extra = " width='" . $_width . "px'";                                
                            $imgurl = "<a href='javascript:;' action='zoom' action-type='img' action-data='{$_zoomImgUrl}'><img src='" . self::lazyImg() . "' class='lazy img-responsive' data-original='{$_imgurl}' " . ($action == 'edit' ? 'data="' . $val . '"' : '') . $_extra . "/></a>";
                        } else {
                            $imgurl = "<img src='{$_imgurl}' class='img-responsive' " . ($action == 'edit' ? 'data="' . $val . '"' : '') . "/>";
                        }
                        $content = str_ireplace("{$thekey}", $imgurl, $content);
                    } else {
                        $content = str_ireplace("{$thekey}", '', $content);
                    }
                }
            }
        }
        if (strpos($content, '[video]') !== false) {
            preg_match_all("/\[video\](\d+)\[\/video\]/i", $content, $match);
            if (!empty($match[1])) {
                $isMobile = zmf::checkmobile();
                foreach ($match[1] as $key => $val) {
                    $thekey = $match[0][$key];
                    $src = Videos::getOne($val);
                    if ($src) {
                        $videoHolderId = zmf::randMykeys(8, 2);
                        if ($action == 'edit') {
                            $_videoHtml = '<p><img src="' . $src['faceimg'] . '" style="width:100%" ' . ('video="' . $val . '"') . '/></p>';
                        } else {
                            $_videoHtml = '<div class="media-item" id="' . $videoHolderId . '"><div class="media-cover" onclick="playVideo(\'' . $src['company'] . '\',\'' . $src['videoid'] . '\',\'' . $videoHolderId . '\',this)"><i class="fa fa-play"></i><img src="' . $src['faceimg'] . '" style="width:100%"/></div><div class="media-content"><p class="media-title">' . $src['title'] . '</p><p>' . $src['content'] . '</p></div></div>';
                        }
                        $content = str_ireplace("<p>{$thekey}</p>", $_videoHtml, $content);
                    } else {
                        $content = str_ireplace("{$thekey}", '', $content);
                    }
                }
            }
        }
        if (strpos($content, '[map]') !== false) {
            preg_match_all("/\[map\](.*?)\[\/map\]/i", $content, $match);
            if (!empty($match[1])) {
                foreach ($match[1] as $key => $val) {
                    $thekey = $match[0][$key];
                    $latLongArr = explode('#', $val);
                    if (count($latLongArr) == 3) {
                        $_html = '<a href="javascript:;"><img data-original="http://ditu.google.cn/maps/api/staticmap?center=' . $latLongArr[0] . ',' . $latLongArr[1] . '&amp;zoom=' . $latLongArr[2] . '&amp;size=640x480&amp;markers=color:red%7Clabel:A%7C' . $latLongArr[0] . ',' . $latLongArr[1] . '&amp;sensor=false&amp;key=' . self::config('googleApiKey') . '" src="' . self::lazyImg() . '" class="img-responsive lazy" ' . ($action == 'edit' ? 'mapinfo="' . $latLongArr[0] . '#' . $latLongArr[1] . '#' . $latLongArr[2] . '"' : '') . '/></a>';
                        $content = str_ireplace("{$thekey}", $_html, $content);
                    } else {
                        $content = str_ireplace("{$thekey}", '', $content);
                    }
                }
            }
        }
        return $content;
    }
    
    public static function trimText($str){
        $replace=array(
            '　',
            "\r\n",
            "\r", 
            "\n",
            PHP_EOL
        );        
        $str=  str_replace($replace, ' ', $str);
        return $str;
    }

    public static function qrcode($content, $origin, $keyid) {
        if (!$content || !$origin || !$keyid) {
            return false;
        }
        $filename = $keyid . '.png';
        $siteUrl = self::config('baseurl') . 'attachments/qrcode/' . $origin . '/';
        $appUrl = Yii::app()->basePath . '/../attachments/qrcode/' . $origin . '/';
        self::createUploadDir($appUrl);
        if (file_exists($appUrl . $filename)) {
            return $siteUrl . $filename;
        } else {
            Yii::import('ext.qrcode.QRCode');
            $code = new QRCode($content);
            $code->create($appUrl . $filename);
            return $siteUrl . $filename;
        }
    }

    /**
     * 限制用户对某一操作的频率，如点赞，收藏，关注
     * 默认4次
     */
    public static function actionLimit($type, $keyid, $num = 4, $time = 60, $fileCache = false, $isCheck = false) {
        $cacheKey = 'actionLimit-' . $type . '-' . $keyid;
        $info = self::myint(zmf::getCookie($cacheKey));
        if ($fileCache) {
            $cacheKey.=ip2long(Yii::app()->request->userHostAddress);
            $fileNum = self::myint(zmf::getFCache($cacheKey));
        }
        if ($info >= $num || ($fileCache && $fileNum >= $num)) {
            return true;
        } else {
            if (!$isCheck) {
                zmf::updateCookieCounter($cacheKey, 1, $time);
                if ($fileCache) {
                    zmf::setFCache($cacheKey, $fileNum + 1, $time);
                }
            }
            return false;
        }
    }

    /**
     * 生成uuid
     * @return string
     */
    public static function uuid() {
        if (function_exists('com_create_guid')) {
            $uuid = com_create_guid();
            //去掉{}
            return str_replace(array('{', '}'), '', $uuid);
        } else {
            mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            //chr(123)// "{"
            //chr(125); // "}"
            $uuid = substr($charid, 0, 8) . $hyphen
                    . substr($charid, 8, 4) . $hyphen
                    . substr($charid, 12, 4) . $hyphen
                    . substr($charid, 16, 4) . $hyphen
                    . substr($charid, 20, 12);
            return $uuid;
        }
    }

    /**
     * 将字符串中间部分隐藏
     * @param type $string
     * @return string
     */
    public static function hideWord($string) {
        $enc = 'UTF-8';
        $s_len = mb_strlen($string, $enc);
        $str = '';
        switch ($s_len) {
            // 两个字的敏感词，只替换第二字为'*'
            case 2 : {
                    $str = mb_substr($string, 0, 1, $enc) . '*';
                    break;
                }
            // 敏感词，一个字，虽然很少。不过可能会有。
            case 1 : {
                    $str = '*';
                    break;
                }
            // 其他情况，取首位各一个字，中间根据多少个字替换多少个'*';
            default : {
                    $str = mb_substr($string, 0, 3, $enc) . str_repeat('*', ($s_len - 4)) . mb_substr($string, -1, 1, $enc);
                }
        }
        return $str;
    }

    /**
     * 处理语言输出
     * @param type $type
     * @param type $value
     * @return type
     */
    public static function t($type, $value = '') {
        $str = Yii::t('default', $type);
        if (isset($value) && !is_array($value)) {
            return sprintf($str, $value);
        } elseif (is_array($value)) {
            $_tmp = join(',', $value);

            //foreach($value as $s){
            $str = sprintf($str, $_tmp);
            //}
            return $str;
        } else {
            return $str;
        }
    }

    /**
     * Yes OR NO
     * @param string $type
     * @return string
     */
    public static function yesOrNo($type = '') {
        $arr = array(
            '0' => 'No',
            '1' => 'Yes',
        );
        if ($type == 'admin') {
            return $arr;
        }
        return $arr[$type];
    }

    public static function formatField($field) {
        return str_replace('`', '', $field);
    }

    /**
     * 判断是否是手机号
     * @param int $mobile
     * @return boolean
     */
    public static function checkPhoneNumber($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }

}
