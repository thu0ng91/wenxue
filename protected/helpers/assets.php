<?php

/**
 * @filename assets.php 
 * @Description 统一处理css、js加载
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-6-2  14:15:47 
 */
class assets {

    /**
     * 加载js路径配置文件
     * @param type $type 应用类型
     */
    public function jsConfig($type = 'web', $module = 'web') {
        $arr['common'] = array(
            'baseUrl' => zmf::config('baseurl'),
            'hasLogin' => Yii::app()->user->isGuest ? 'false' : 'true',
            'loginUrl' => Yii::app()->createUrl('/site/login'),
            'module' => $module,
            'csrfToken' => Yii::app()->request->csrfToken,
            'currentSessionId' => Yii::app()->session->sessionID,
            'addCommentUrl' => zmf::config('domain') . Yii::app()->createUrl('/ajax/addComment'),
        );
        $arr['web'] = array(
            'editor' => '',
            'allowImgTypes' => zmf::config('imgAllowTypes'),
            'allowImgPerSize' => zmf::formatBytes(zmf::config('imgMaxSize')),
            'perAddImgNum' => zmf::config('imgUploadNum'),
            'weiboAppkey' => zmf::config('weiboAppkey'),
            'weiboRalateUid' => zmf::config('weiboRalateUid'),
            'contentsUrl' => zmf::config('domain') . Yii::app()->createUrl('/ajax/getContents'), //获取内容
            'delContentUrl' => zmf::config('domain') . Yii::app()->createUrl('/ajax/delContent'), //删除内容
            'favoriteUrl' => zmf::config('domain') . Yii::app()->createUrl('/ajax/favorite'), //收藏内容
            'feedbackUrl' => zmf::config('domain') . Yii::app()->createUrl('/ajax/feedback'), //意见反馈
            'ajaxUrl' => zmf::config('domain') . Yii::app()->createUrl('/ajax/do'),
            'userGalleryUrl' => zmf::config('domain') . Yii::app()->createUrl('/user/gallery'),
        );
        $arr['mobile'] = array(
            'contentsUrl' => zmf::config('domain') . Yii::app()->createUrl('/ajax/getContents'), //获取内容
            'delContentUrl' => zmf::config('domain') . Yii::app()->createUrl('/ajax/delContent'), //删除内容
            'favoriteUrl' => zmf::config('domain') . Yii::app()->createUrl('/ajax/favorite'), //收藏内容
            'feedbackUrl' => zmf::config('domain') . Yii::app()->createUrl('/ajax/feedback'), //意见反馈
            'ajaxUrl' => zmf::config('domain') . Yii::app()->createUrl('/ajax/do'),
        );
        $attrs = array_merge($arr['common'], $arr[$type]);
        $longHtml = '<script>var zmf={';
        foreach ($attrs as $k => $v) {
            $longHtml.=$k . ":'" . $v . "',";
        }
        $longHtml.='};</script>';
        echo $longHtml;
    }

    public function loadCssJs($type = 'web', $action = '') {
        $status=  zmf::config('appStatus');
        if (YII_DEBUG) {
            $staticUrl = Yii::app()->baseUrl . ($status!=3 ? '/jsCssSrc/' : '/common/');
        } else {
            $_staticUrl = zmf::config('cssJsStaticUrl');
            $staticUrl = $_staticUrl ? $_staticUrl : Yii::app()->baseUrl. ($status!=3 ? '/jsCssSrc/' : '/common/');
        }
        $cs = Yii::app()->clientScript;
        $c = Yii::app()->getController()->id;
        $a = Yii::app()->getController()->getAction()->id;
        if ($status!=3) {
            $cssDir = Yii::app()->basePath . '/../jsCssSrc/css';
            $jsDir = Yii::app()->basePath . '/../jsCssSrc/js';
            $coreCssDir = Yii::app()->basePath . '/../jsCssSrc/coreCss';
            $coreJsDir = Yii::app()->basePath . '/../jsCssSrc/coreJs';
        }else{
            $cssDir = Yii::app()->basePath . '/../common/css';
            $jsDir = Yii::app()->basePath . '/../common/js';
            $coreCssDir = Yii::app()->basePath . '/../common/coreCss';
            $coreJsDir = Yii::app()->basePath . '/../common/coreJs';
        }
        $cssArr = $jsArr = $coreCssArr = $coreJsArr =array();
        if ($type == 'web') {
            $coreCssArr=array(
                'bootstrap'=>array('pos'=>'head'),
                'font-awesome'=>array('pos'=>'head'),
            );
            $coreJsArr=array(
                'jquery'=>array('pos'=>'head'),
                'bootstrap'=>array('pos'=>'end'),
                'superSlide'=>array('pos'=>'end'),
            );
            $cssArr = array(
                'web-zmf',
            );
            $jsArr = array(
                'pjax',
                'zmf',
            );            
        } elseif ($type == 'mobile') {
            $coreCssArr=array(
                'frozen'=>array('pos'=>'head'),
                'font-awesome'=>array('pos'=>'head'),
            );
            $coreJsArr=array(
                'jquery'=>array('pos'=>'head'),
            );
            $cssArr = array(
                'mobile',
            );
            $jsArr = array(
                'mobile',
            ); 
            if(($c=='author' && $a=='createBook') || ($c=='author' && $a=='setting') || ($c=='user' && $a=='setting')){
                if(!in_array($_GET['action'], array('checkPhone'))){
                    $coreJsArr['ui.widget']=array('pos'=>'end');
                    $coreJsArr['iframe-transport']=array('pos'=>'end');
                    $coreJsArr['fileupload']=array('pos'=>'end');
                }
            }
        } elseif ($type == 'admin') {
            $cssArr = array(
                'frozen',
            );
            $jsArr = array(
                'zepto',
                'frozen',
            );
            $cssArr[] = 'mobile';
            $jsArr[] = 'mobile';
        }
        $coreCssDirArr = zmf::readDir($coreCssDir, false);
        $coreJsDirArr = zmf::readDir($coreJsDir, false);
        $cssDirArr = zmf::readDir($cssDir, false);
        $jsDirArr = zmf::readDir($jsDir, false);
        foreach ($coreCssDirArr as $coreFileName) {
            foreach ($coreCssArr as $coreCssfile=>$fileParams) {
                if (strpos($coreFileName,$coreCssfile) !== false) {
                    $cs->registerCssFile($staticUrl . 'coreCss/' . $coreFileName);
                }
            }
        }
        foreach ($cssArr as $cssFileName) {
            foreach ($cssDirArr as $cssfile) {
                if (strpos($cssfile, $cssFileName) !== false) {
                    $cs->registerCssFile($staticUrl . 'css/' . $cssfile);
                }
            }
        }
        foreach ($coreJsDirArr as $jsFileName) {
            foreach ($coreJsArr as $jsfile=>$jsParams) {                
                if($jsfile=='jquery'){
                    $cs->registerCoreScript('jquery');
                    continue;
                }
                if (strpos($jsFileName,$jsfile) !== false) {
                    if ($jsParams['pos']=='head') {
                        $pos = CClientScript::POS_HEAD;
                    } else {
                        $pos = CClientScript::POS_END;
                    }
                    $cs->registerScriptFile($staticUrl . 'coreJs/' . $jsFileName, $pos);
                }
            }
        }
        foreach ($jsArr as $jsFileName) {
            foreach ($jsDirArr as $jsfile) {
                if (strpos($jsfile, $jsFileName) !== false) {
                    if (strpos($jsfile, 'head') !== false) {
                        $pos = CClientScript::POS_HEAD;
                    } else {
                        $pos = CClientScript::POS_END;
                    }
                    $cs->registerScriptFile($staticUrl . 'js/' . $jsfile, $pos);
                }
            }
        }
    }

}
