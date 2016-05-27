<?php
/**
 * @filename chapter.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-11  17:22:55 
 */
?>
<style>
    .chapter-container{
        width: 800px;
        margin: 0 auto;
    }
    .chapter{
        padding: 10px 15px;        
    }
    .chapter h1{
        padding: 0;
        margin: 0;
        font-size: 28px;
        font-weight: 700
    }
    .chapter .chapter-content{
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #e6e6e6;
        font-size: 14px;
        line-height: 1.75
    }
    .chapter .chapter-content p{
        margin-bottom: 10px;
        text-indent: 2em;
    }
    .chapter-tips-module{
        padding-left: 0;
        padding-right: 0;
    }
    .chapter-tips-module .module-body{
        padding-bottom: 0
    }
    .chapter-tips-module .media{
        border-bottom: 1px dashed #F2f2f2
    }
    .chapter-tips-module .media:last-child{
        border-bottom: none
    }
    .chapter-min-tips{
        color:#ccc;
        margin-top: 10px;
    }
    .chapter-min-tips a{
        color:#ccc;
    }
    .chapter-min-tips span{
        margin-right: 10px;
    }
    .chapter-fixed-navbar{
        width: 70px;
        height: 200px;
        
        position: absolute;
        top: 34px;
        left: 0
    }
    .chapter-fixed-navbar .fixed-btns a{
        width: 100%;
        display: block;
        text-align: center;
        padding: 10px;
        background: #f8f8f8;
        margin-bottom: 5px;
        text-decoration: none;
        height: 60px;
    }
    .chapter-fixed-navbar .fixed-btns a.active{
        background: #fff;
        border-left: 1px solid #93ba5f;
        border-top: 1px solid #93ba5f;
        border-bottom: 1px solid #93ba5f;
    }
    .chapter-fixed-navbar .fixed-btns .fa{
        display: block;
        font-size: 24px;
    }
    .fixed-chapters{
        width: 810px;
        height: auto;
        position: absolute;
        left: 70px;
        top: 0;
        background: transparent;
        display: none
    }
    .holder-hack{
        width: 11px;
        height: 60px;
        position: absolute;
        left: 0;
        top: 0;
        background: #fff;
        border-top: 1px solid #93ba5f;
        border-bottom: 1px solid #93ba5f;
    }
    .fixed-chapters-body{
        float: right;
        width: 800px;
        background: #fff;
        min-height: 300px;
        display: block;
        border: 1px solid #93ba5f;
        box-shadow: 0 2px 8px -3px #93ba5f;
    }
    .fixed-chapters-body .module{
        border: none;
        box-shadow: none
    }
    .fixed-chapters-body .module .module-body{
        font-size: 14px;
        padding-top: 0;
        max-height: 600px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
    .fixed-chapters-body .module .module-body .col-xs-6{
        border-bottom: 1px dashed #F2f2f2;
        padding-top: 10px;
        padding-bottom: 5px;
    }
    .fixed-chapters-body .module .module-body a.active{
        color: #BF1031
    }
</style>
<div class="container">
    <div class="chapter-container">
        <div class="chapter-navbar">
            <ol class="breadcrumb">
                <li><?php echo CHtml::link(zmf::config('sitename').'首页',  zmf::config('baseurl'));?></li>
                <li><?php echo CHtml::link($colInfo['title'],array('book/index','colid'=>$colInfo['id']),array('target'=>'_blank'));?></li>
                <li class="active"><?php echo $bookInfo['title']; ?></li>
            </ol>
        </div>
        <div class="module chapter">
            <h1><?php echo $chapterInfo['title']; ?></h1>
            <p class="chapter-min-tips">
                <span>小说：<?php echo CHtml::link($bookInfo['title'],array('book/view','id'=>$bookInfo['id'])); ?></span>
                <span>作者：<?php echo CHtml::link($authorInfo['authorName'],array('author/view','id'=>$chapterInfo['aid'])); ?></span>
                <span>字数：<?php echo $chapterInfo['words']; ?></span>
                <span>更新时间：<?php echo zmf::time($chapterInfo['updateTime']); ?></span>         
            </p>
            <div class="chapter-content">
                <?php echo Chapters::text($chapterInfo['content']); ?>
            </div>
        </div>
        <div class="module chapter-tips-module">
            <div class="module-header">点评</div>
            <div class="module-body" id="comments-chapter-<?php echo $chapterInfo['id'];?>">                
                <div id="more-content">
                    <?php if(!empty($tips)){?>
                    <?php foreach ($tips as $tip){?> 
                    <?php $this->renderPartial('/book/_tip',array('data'=>$tip));?>
                    <?php }?>
                    <?php }else{?>
                    <p class="help-block">还没人写过点评，快来抢沙发吧</p>
                    <?php }?>
                </div>
            </div>
            <div class="module-header" id="add-tip-holder">写点评</div>
            <div class="module-body">
                <?php if($this->uid){
                    if($this->tipInfo!==false){
                        if($this->tipInfo['status']==Posts::STATUS_PASSED){
                            echo '<p class="help-block">每章节只能评价一次，但你可以对 '.CHtml::link('现有的评价','javascript:;',array('action-target'=>'tip-'.$this->tipInfo['id'],'action'=>'scroll')).' 进行修改。</p>';
                        }else{
                            echo '<p class="help-block">你已经删除了对本章节的点评，如需显示，请'.CHtml::link('重新编辑','javascript:;').'。</p>';
                        }
                    }else{
                        $this->renderPartial('/common/addTips',array('type'=>'chapter','keyid'=>$chapterInfo['id']));
                    }
                 }else{?>
                <p class="help-block">登录后享有更多功能，<?php echo CHtml::link('立即登录',array('site/login'));?>或<?php echo CHtml::link('注册',array('site/reg'));?>。</p>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="chapter-fixed-navbar">
        <div class="fixed-btns">
        <?php echo CHtml::link('<i class="fa fa-list"></i> 目录','javascript:;',array('action'=>'showChapters'));?>
        <?php echo CHtml::link((($this->uid && $this->tipInfo!==false) ? '<i class="fa fa-star"></i> 已点评' : '<i class="fa fa-star-o"></i> 点评'),'javascript:;',array('action'=>'scroll','action-target'=>'add-tip-holder'));?>
        <?php echo CHtml::link('<i class="fa fa-long-arrow-left"></i> 上一章',!empty($prev) ? $prev['url'] : 'javascript:;',array('action'=>'preChapter','title'=>(!empty($prev) ? $prev['title'].'（键盘←）' : '已是第一篇'),'id'=>'preChapter'));?>
        <?php echo CHtml::link('<i class="fa fa-long-arrow-right"></i> 下一章',!empty($next) ? $next['url'] : 'javascript:;',array('action'=>'nextChapter','title'=>(!empty($next) ? $next['title'].'（键盘→）' : '已是最后一篇'),'id'=>'nextChapter'));?>
        <?php echo CHtml::link('<i class="fa fa-reply"></i> 返回',array('book/view','id'=>$chapterInfo['bid']));?>
        </div>
        <div class="fixed-chapters" id="fixed-chapters">
            <div class="holder-hack"></div>
            <div class="fixed-chapters-body">
                <div class="module">
                    <div class="module-header">章节目录</div>
                    <div class="module-body">
                        <div class="row">
                            <?php foreach ($chapters as $chapter){?>
                            <div class="col-xs-6 col-sm-6"><p><?php echo CHtml::link($chapter['title'],array('book/chapter','cid'=>$chapter['id']),array('class'=>$chapterInfo['id']==$chapter['id'] ? 'active' : ''));?></p></div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>