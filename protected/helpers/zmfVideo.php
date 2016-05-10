<?php

class zmfVideo {

    public static function parseUrl($url) {
        $lowerurl = strtolower($url);
        $flv = '';
        $imgurl = '';
        if ($lowerurl != str_replace(array('player.youku.com/player.php/sid/', 'tudou.com/v/', 'player.ku6.com/refer/'), '', $lowerurl)) {
            $flv = $url;
        } elseif (strpos($lowerurl, 'v.youku.com/v_show/') !== FALSE) {
            $ctx = stream_context_create(array('http' => array('timeout' => 10)));
            if (preg_match("/http:\/\/v.youku.com\/v_show\/id_([^\/]+)(.html|)/i", $url, $matches)) {
                $flv = 'http://player.youku.com/player.php/sid/' . $matches[1] . '/v.swf';
                $api = 'http://v.youku.com/player/getPlayList/VideoIDS/' . $matches[1];
                $str = stripslashes(file_get_contents($api, false, $ctx));
                if (!empty($str) && preg_match("/\"logo\":\"(.+?)\"/i", $str, $image)) {
                    $url = substr($image[1], 0, strrpos($image[1], '/') + 1);
                    $filename = substr($image[1], strrpos($image[1], '/') + 2);
                    $imgurl = $url . '0' . $filename;
                }
            }
        } elseif (strpos($lowerurl, 'tudou.com/programs/view/') !== FALSE) {
            if (preg_match("/http:\/\/(www.)?tudou.com\/programs\/view\/([^\/]+)/i", $url, $matches)) {
                $flv = 'http://www.tudou.com/v/' . $matches[2];
                $str = file_get_contents($url, false, $ctx);
                if (!empty($str) && preg_match("/<span class=\"s_pic\">(.+?)<\/span>/i", $str, $image)) {
                    $imgurl = trim($image[1]);
                }
            }
        } elseif (strpos($lowerurl, 'v.ku6.com/show/') !== FALSE) {
            if (preg_match("/http:\/\/v.ku6.com\/show\/([^\/]+).html/i", $url, $matches)) {
                $flv = 'http://player.ku6.com/refer/' . $matches[1] . '/v.swf';
                $api = 'http://vo.ku6.com/fetchVideo4Player/1/' . $matches[1] . '.html';
                $str = file_get_contents($api, false, $ctx);
                if (!empty($str) && preg_match("/\"picpath\":\"(.+?)\"/i", $str, $image)) {
                    $imgurl = str_replace(array('\u003a', '\u002e'), array(':', '.'), $image[1]);
                }
            }
        } elseif (strpos($lowerurl, 'v.ku6.com/special/show_') !== FALSE) {
            if (preg_match("/http:\/\/v.ku6.com\/special\/show_\d+\/([^\/]+).html/i", $url, $matches)) {
                $flv = 'http://player.ku6.com/refer/' . $matches[1] . '/v.swf';
                $api = 'http://vo.ku6.com/fetchVideo4Player/1/' . $matches[1] . '.html';
                $str = file_get_contents($api, false, $ctx);
                if (!empty($str) && preg_match("/\"picpath\":\"(.+?)\"/i", $str, $image)) {
                    $imgurl = str_replace(array('\u003a', '\u002e'), array(':', '.'), $image[1]);
                }
            }
        } elseif (strpos($lowerurl, 'www.youtube.com/watch?') !== FALSE) {
            if (preg_match("/http:\/\/www.youtube.com\/watch\?v=([^\/&]+)&?/i", $url, $matches)) {
                $flv = 'http://www.youtube.com/v/' . $matches[1] . '&hl=zh_CN&fs=1';
                $str = file_get_contents($url, false, $ctx);
                if (!empty($str) && preg_match("/'VIDEO_HQ_THUMB':\s'(.+?)'/i", $str, $image)) {
                    $url = substr($image[1], 0, strrpos($image[1], '/') + 1);
                    $filename = substr($image[1], strrpos($image[1], '/') + 3);
                    $imgurl = $url . $filename;
                }
            }
        } elseif (strpos($lowerurl, 'tv.mofile.com/') !== FALSE) {
            if (preg_match("/http:\/\/tv.mofile.com\/([^\/]+)/i", $url, $matches)) {
                $flv = 'http://tv.mofile.com/cn/xplayer.swf?v=' . $matches[1];
                $str = file_get_contents($url, false, $ctx);
                if (!empty($str) && preg_match("/thumbpath=\"(.+?)\";/i", $str, $image)) {
                    $imgurl = trim($image[1]);
                }
            }
        } elseif (strpos($lowerurl, 'v.mofile.com/show/') !== FALSE) {
            if (preg_match("/http:\/\/v.mofile.com\/show\/([^\/]+).shtml/i", $url, $matches)) {
                $flv = 'http://tv.mofile.com/cn/xplayer.swf?v=' . $matches[1];
                $str = file_get_contents($url, false, $ctx);
                if (!empty($str) && preg_match("/thumbpath=\"(.+?)\";/i", $str, $image)) {
                    $imgurl = trim($image[1]);
                }
            }
        } elseif (strpos($lowerurl, 'video.sina.com.cn/v/b/') !== FALSE) {
            if (preg_match("/http:\/\/video.sina.com.cn\/v\/b\/(\d+)-(\d+).html/i", $url, $matches)) {
                $flv = 'http://vhead.blog.sina.com.cn/player/outer_player.swf?vid=' . $matches[1];
                $api = 'http://interface.video.sina.com.cn/interface/common/getVideoImage.php?vid=' . $matches[1];
                $str = file_get_contents($api, false, $ctx);
                if (!empty($str)) {
                    $imgurl = str_replace('imgurl=', '', trim($str));
                }
            }
        } elseif (strpos($lowerurl, 'you.video.sina.com.cn/b/') !== FALSE) {
            if (preg_match("/http:\/\/you.video.sina.com.cn\/b\/(\d+)-(\d+).html/i", $url, $matches)) {
                $flv = 'http://vhead.blog.sina.com.cn/player/outer_player.swf?vid=' . $matches[1];
                $api = 'http://interface.video.sina.com.cn/interface/common/getVideoImage.php?vid=' . $matches[1];
                $str = file_get_contents($api, false, $ctx);
                if (!empty($str)) {
                    $imgurl = str_replace('imgurl=', '', trim($str));
                }
            }
        } elseif (strpos($lowerurl, 'http://my.tv.sohu.com/u/') !== FALSE) {
            if (preg_match("/http:\/\/my.tv.sohu.com\/u\/[^\/]+\/(\d+)/i", $url, $matches)) {
                $flv = 'http://v.blog.sohu.com/fo/v4/' . $matches[1];
                $api = 'http://v.blog.sohu.com/videinfo.jhtml?m=view&id=' . $matches[1] . '&outType=3';
                $str = file_get_contents($api, false, $ctx);
                if (!empty($str) && preg_match("/\"cutCoverURL\":\"(.+?)\"/i", $str, $image)) {
                    $imgurl = str_replace(array('\u003a', '\u002e'), array(':', '.'), $image[1]);
                }
            }
        } elseif (strpos($lowerurl, 'http://v.blog.sohu.com/u/') !== FALSE) {
            if (preg_match("/http:\/\/v.blog.sohu.com\/u\/[^\/]+\/(\d+)/i", $url, $matches)) {
                $flv = 'http://v.blog.sohu.com/fo/v4/' . $matches[1];
                $api = 'http://v.blog.sohu.com/videinfo.jhtml?m=view&id=' . $matches[1] . '&outType=3';
                $str = file_get_contents($api, false, $ctx);
                if (!empty($str) && preg_match("/\"cutCoverURL\":\"(.+?)\"/i", $str, $image)) {
                    $imgurl = str_replace(array('\u003a', '\u002e'), array(':', '.'), $image[1]);
                }
            }
        } elseif (strpos($lowerurl, 'http://www.ouou.com/fun_funview') !== FALSE) {
            $str = file_get_contents($url, false, $ctx);
            if (!empty($str) && preg_match("/var\sflv\s=\s'(.+?)';/i", $str, $matches)) {
                $flv = $_G['style']['imgdir'] . '/flvplayer.swf?&autostart=true&file=' . urlencode($matches[1]);
                if (preg_match("/var\simga=\s'(.+?)';/i", $str, $image)) {
                    $imgurl = trim($image[1]);
                }
            }
        } elseif (strpos($lowerurl, 'http://www.56.com') !== FALSE) {
            if (preg_match("/http:\/\/www.56.com\/\S+\/play_album-aid-(\d+)_vid-(.+?).html/i", $url, $matches)) {
                $flv = 'http://player.56.com/v_' . $matches[2] . '.swf';
                $matches[1] = $matches[2];
            } elseif (preg_match("/http:\/\/www.56.com\/\S+\/([^\/]+).html/i", $url, $matches)) {
                $flv = 'http://player.56.com/' . $matches[1] . '.swf';
            }
            if (!$width && !$height && !empty($matches[1])) {
                $api = 'http://vxml.56.com/json/' . str_replace('v_', '', $matches[1]) . '/?src=out';
                $str = file_get_contents($api, false, $ctx);
                if (!empty($str) && preg_match("/\"img\":\"(.+?)\"/i", $str, $image)) {
                    $imgurl = trim($image[1]);
                }
            }
        } elseif (strpos($lowerurl, 'v.qq.com') !== FALSE) {//腾讯视频
            if (preg_match("/http:\/\/v.qq.com\/.+?\/[^\/]+.html\?vid=([^\/]+)/i", $url, $matches)) {
                $vid = $matches[1];
            } elseif (preg_match("/http:\/\/v.qq.com\/.+?\/([^\/]+).html/i", $url, $matches)) {
                $vid = $matches[1];
            }
            if ($vid) {
                $flv = 'http://static.video.qq.com/TPout.swf?vid=' . $vid . '&exid=k0&showend=1';
                $num = 0xFFFFFFFF + 1;
                $m = 10000 * 10000;
                $res = 0;
                $i = 0;
                while ($i < strlen($vid)) {
                    $temp = ord(substr($vid, $i, 1));
                    $res = $res * 32 + $res + $temp;
                    while ($res >= $num) {
                        $res -= $num;
                    }
                    $i++;
                }
                while ($res >= $m) {
                    $res -= $m;
                }
                $imgurl = 'http://vpic.video.qq.com/' . $res . '/' . $vid . '.png';
            }
        }
        if ($flv) {
            return array('flv' => $flv, 'imgurl' => $imgurl);
        } else {
            return FALSE;
        }
    }

    public static function parse_vedio($url) {
        $data = array();
        $temp_data = array();
        $faceimg = '';
        $result = array();
        if (strpos($url, 'v.youku.com/v_show/') !== FALSE) {
            $temp_data = file_get_contents($url);
            if ($temp_data && preg_match("/videoId2= '(.*?)';/U", $temp_data, $data)) {
                if (!empty($data)) {
                    $result ['swfurl'] = ' http://player.youku.com/player.php/sid/' . $data [1] . '/v.swf';
                    $result ['company'] = 'youku';
                    $result ['videoid'] = $data [1];
                    preg_match('/pic=(.*)" target="_blank"/U', $temp_data, $faceimg);
                    if (!empty($faceimg)) {
                        $result ['faceimg'] = $faceimg [1];
                    }
                    preg_match('/<meta name="irTitle" content="(.*)"/U', $temp_data, $title);
                    if (!empty($title)) {
                        $result ['title'] = $title [1];
                    }
                }
            }
        } elseif (preg_match('/http:\/\/www.tudou.com\/(.).*\/(.*)\.html/U', $url, $data)) {
            $new_data = explode('/', $data [2]);
            $temp = array();
            $html = file_get_contents($url);
            preg_match('/iid:\s?(\d+)[\s\S]/', $html, $iid);
            preg_match("/,pic:\s?'(.*)'/", $html, $pic);
            preg_match("/<meta name=\"irTitle\" content=\"(.*?)\"/", $html, $title);
            if (!empty($data) && !empty($iid) && !empty($pic)) {
                $result ['swfurl'] = 'http://www.tudou.com/' . $data [1] . '/' . $new_data [0] . '/&iid=' . $iid [1] . '/v.swf';
                $result ['company'] = 'tudou';
                $result ['faceimg'] = $pic [1];
                $result ['title'] = $title [1];
                $result ['videoid'] = "code={$new_data[1]}&lcode={$new_data[0]}";
            }
        } elseif (preg_match("/http:\/\/(www.)?tudou.com\/programs\/view\/([^\/]+)/i", $url, $matches)) {
            $html = file_get_contents($url);
            preg_match('/iid:\s?(\d+)[\s\S]/', $html, $iid);
            preg_match("/,pic:\s?'(.*)'/", $html, $pic);
            preg_match("/<meta name=\"irTitle\" content=\"(.*?)\"/", $html, $title);
            //<iframe src="http://www.tudou.com/programs/view/html5embed.action?type=0&code=kIUhFKarqOQ&lcode=&resourceId=0_06_05_99" allowtransparency="true" allowfullscreen="true" allowfullscreenInteractive="true" scrolling="no" border="0" frameborder="0" style="width:480px;height:400px;"></iframe>
            if (!empty($matches) && !empty($iid) && !empty($pic)) {
                $result ['swfurl'] = 'http://www.tudou.com/v/' . $matches[2];
                $result ['company'] = 'tudou';
                $result ['faceimg'] = $pic [1];
                $result ['title'] = $title [1];
                $result ['videoid'] = "code={$matches[2]}";
            }                
        } elseif (preg_match('/http:\/\/(www|yule).iqiyi.com\/(.*).html/', $url, $data)) {
            if (!empty($data)) {
                $html = file_get_contents($url);
                preg_match('/"pid":"(.*)","ptype":"(.*)".*"videoId":"(.*)","albumId":"(.*)".*"tvId":"(.*)".*"qitanId":(.*),[\s\S]*<span id="imgPathData" style="display:none">(.*)<\/span>/U', $html, $temp_data);
                if (!empty($temp_data)) {
                    $result ['company'] = '爱奇艺';
                    $result ['faceimg'] = str_replace('_baidu', '', trim($temp_data [7]));
                    $result ['swfurl'] = 'http://player.video.qiyi.com/' . $temp_data [3] . '/0/0/' . ($data [1] == 'www' ? $data [2] : $data [1] . '/' . $data [2]) . '.swf-pid=' . $temp_data [1] . '-ptype=' . $temp_data [2] . '-albumId=' . $temp_data [4] . '-tvId=' . $temp_data [5] . '-autoplay=1-qitanId=' . $temp_data [6] . '-isDrm=0-isPurchase=0';
                }
            }
        } elseif (strpos($url, 'v.qq.com') !== FALSE) {//腾讯视频
            if (preg_match("/http:\/\/v.qq.com\/.+?\/[^\/]+.html\?vid=([^\/]+)/i", $url, $matches)) {
                $vid = $matches[1];
            } elseif (preg_match("/http:\/\/v.qq.com\/.+?\/([^\/]+).html/i", $url, $matches)) {
                $vid = $matches[1];
            }
            if ($vid) {
                $html = file_get_contents($url);
                //
                preg_match("/<meta itemprop=\"name\" content=\"(.*?)\"/", $html, $title);

                $flv = 'http://static.video.qq.com/TPout.swf?vid=' . $vid . '&exid=k0&showend=1';
                $num = 0xFFFFFFFF + 1;
                $m = 10000 * 10000;
                $res = 0;
                $i = 0;
                while ($i < strlen($vid)) {
                    $temp = ord(substr($vid, $i, 1));
                    $res = $res * 32 + $res + $temp;
                    while ($res >= $num) {
                        $res -= $num;
                    }
                    $i++;
                }
                while ($res >= $m) {
                    $res -= $m;
                }
                $imgurl = 'http://vpic.video.qq.com/' . $res . '/' . $vid . '.png';
                $result=array(
                    'company'=>'qq',
                    'faceimg'=>$imgurl,
                    'swfurl'=>$flv,
                    'title'=>$title[1],
                    'videoid'=>$vid,
                );                
            }
        }
        return $result;
    }

}
