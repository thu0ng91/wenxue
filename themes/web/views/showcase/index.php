<?php

/**
 * @filename index.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-18  16:16:38 
 */
?>
<style>
    .column-page .module{
        padding: 0;
        float: left;
        margin-bottom: 40px;
    }
    .column-min{
        width: 230px;     
        height:360px;
    }
    .column-medium{
        width: 480px;
        margin:0 10px;
        height:360px;
        border: none
    }
    *{margin:0; padding:0; list-style:none; }

    .slideBox{ width:480px; height:360px; overflow:hidden; position:relative; border:1px solid #ddd;  }
    .slideBox .hd{ height:15px; overflow:hidden; position:absolute; right:5px; bottom:5px; z-index:1; }
    .slideBox .hd ul{ overflow:hidden; zoom:1; float:left;  }
    .slideBox .hd ul li{ float:left; margin-right:2px;  width:15px; height:15px; line-height:14px; text-align:center; background:#fff; cursor:pointer; }
    .slideBox .hd ul li.on{ background:#f00; color:#fff; }
    .slideBox .bd{ position:relative; height:100%; z-index:0;   }
    .slideBox .bd li{ zoom:1; vertical-align:middle; }
    .slideBox .bd img{ width:480px; height:360px; display:block;  } 
</style>
<div class="container column-page">
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column11']));?>
    <div class="module column-medium">
        <div id="slideBox" class="slideBox">
            <div class="hd"><ul><li>1</li><li>2</li><li>3</li></ul></div>
            <div class="bd">
                <ul>
                    <li><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/demo/images/pic1.jpg" /></a></li>
                    <li><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/demo/images/pic2.jpg" /></a></li>
                    <li><a href="http://www.SuperSlide2.com" target="_blank"><img src="http://www.superslide2.com/demo/images/pic2.jpg" /></a></li>
                </ul>
            </div>
        </div>
        <script id="jsID" type="text/javascript">
            $(document).ready(function(){
                jQuery(".slideBox").slide( { mainCell:".bd ul", effect:'left',autoPlay:true,trigger:'mouseover',easing:'swing',delayTime:500,mouseOverStop:true,pnLoop:true});
            });       
       </script>
    </div>
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column13']));?>
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column21']));?>
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column22'],'class'=>'column-medium'));?>       
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column23']));?>
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column31']));?>
    <?php $this->renderPartial('/showcase/columnModule',array('moduleInfo'=>$posts['column32'],'class'=>'column-medium'));?>
    <div class="module column-min">
        <div class="module-header">最新评论</div>
        <div class="module-body">
            <p class="no-wrap">的房间哦士大夫就四的覅计算</p>
            <p class="no-wrap">的房间哦士大夫就四的覅计算</p>
            <p class="no-wrap">的房间哦士大夫就四的覅计算</p>
            <p class="no-wrap">的房间哦士大夫就四的覅计算</p>
            <p class="no-wrap">的房间哦士大夫就四的覅计算</p>
            <p class="no-wrap">的房间哦士大夫就四的覅计算</p>
            <p class="no-wrap">的房间哦士大夫就四的覅计算</p>
            <p class="no-wrap">的房间哦士大夫就四的覅计算</p>
            <p class="no-wrap">的房间哦士大夫就四的覅计算</p>
        </div>
    </div>
</div>