<?php

class Attachments extends CActiveRecord {

    public function tableName() {
        return '{{attachments}}';
    }

    public function rules() {
        return array(
            array('covered, status', 'numerical', 'integerOnly' => true),
            array('uid, logid, hits, cTime,comments', 'length', 'max' => 11),
            array('filePath, fileDesc, classify, width, height, size,remote', 'length', 'max' => 255),
            array('id, uid, logid, filePath, fileDesc, classify, width, height, size, covered, hits, cTime, status,remote', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'authorInfo' => array(self::BELONGS_TO, 'Users', 'uid'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => '作者',
            'logid' => '所属',
            'filePath' => '文件名',
            'fileDesc' => '描述',
            'classify' => '分类',
            'width' => '宽',
            'height' => '高',
            'size' => '大小',
            'covered' => '置顶',
            'hits' => '点击',
            'cTime' => '创建时间',
            'status' => '状态',
            'favor' => '赞',
            'remote' => '远程路径',
            'comments' => '评论数',
        );
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 根据单条图片信息返回存放地址
     * @param type $data
     * @return string
     */
    public static function getUrl($data, $size = '170') {
        if($data['remote']!=''){
            $_imgurl=$data['remote'];
        }else{
            $_imgurl = zmf::uploadDirs($data['cTime'], 'site', $data['classify']) . $data['filePath'];
        }
        $reurl=  zmf::getThumbnailUrl($_imgurl, $size, $data['classify']);
        return $reurl;
    }

    /**
     * 返回坐标的封面图
     * @param type $poiInfo
     * @param type $size
     * @return string
     */
    public static function faceImg($poiInfo, $size = '170',$type='posts') {
        $url = '';
        if ($poiInfo['faceimg']) {
            $info = Attachments::getOne($poiInfo['faceimg']);
            if ($info) {
                if($info['remote']!=''){
                    $url=$info['remote'];
                }else{
                    $url = zmf::uploadDirs($info['cTime'], 'site', $info['classify']) . $info['filePath'];
                }
            }
        }
        if(!$url){
            return '';
        }
        $reurl=  zmf::getThumbnailUrl($url, $size, $type);
        return $reurl;
    }
    
    /**
     * 根据图片ID返回图片信息
     * @param type $id
     * @return boolean
     */
    public static function getOne($id){
        if(!$id || !is_numeric($id)){
            return false;
        }
        //todo，图片分表，将图片表分为attachments0~9
        return Attachments::model()->findByPk($id);
    }
    
    /**
     * 判断图片是否是站内图片
     * @param type $url
     * @return boolean
     */
    public static function checkUrlDomain($url){
        $arr[]=  zmf::config('domain');
        $arr[]=  zmf::config('imgVisitUrl');
        $arr=  array_filter($arr);
        $find=false;
        foreach ($arr as $_url){
            if(strpos($url, $_url)!==false){
                $find=true;
                break;
            }
        }
        return $find;
    }

}
