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
    .chapter-navbar .breadcrumb{
        margin-bottom: 0;
        background: transparent
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
    .chapter-fixed-navbar a{
        width: 100%;
        display: block;
        text-align: center;
        padding: 10px;
        background: #fff;
        margin-bottom: 5px
    }
    .chapter-fixed-navbar .fa{
        display: block;
        font-size: 24px;
    }
</style>
<div class="container">
    <div class="chapter-container">
        <div class="chapter-navbar">
            <ol class="breadcrumb">
                <li><a href="#">初心创文首页</a></li>
                <li><a href="#">一级分类</a></li>
                <li><a href="#">二级分类</a></li>
                <li class="active"><?php echo $bookInfo['title']; ?></li>
            </ol>
        </div>
        <div class="module chapter">
            <h1><?php echo $chapterInfo['title']; ?></h1>
            <p class="chapter-min-tips">
                <span>小说：<?php echo CHtml::link($bookInfo['title'],array('book/view','id'=>$bookInfo['id'])); ?></span>
                <span>作者：<?php echo CHtml::link($chapterInfo['aid'],array('author/view','id'=>$chapterInfo['aid'])); ?></span>
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
        <?php echo CHtml::link('<i class="fa fa-list"></i> 目录','javascript:;');?>
        <?php echo CHtml::link('<i class="fa fa-star-o"></i> 点评','javascript:;',array('action'=>'scroll','action-target'=>'add-tip-holder'));?>
        <?php echo CHtml::link('<i class="fa fa-long-arrow-left"></i> 上一章','javascript:;');?>
        <?php echo CHtml::link('<i class="fa fa-long-arrow-right"></i> 下一章','javascript:;');?>
        <?php echo CHtml::link('<i class="fa fa-reply"></i> 返回',array('book/view','id'=>$chapterInfo['bid']));?>        
    </div>
</div>