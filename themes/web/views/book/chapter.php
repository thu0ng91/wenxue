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
                <span>点击：<?php echo $chapterInfo['hits']; ?></span>
                <span>更新时间：<?php echo zmf::time($chapterInfo['updateTime']); ?></span>
                <span><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'chapter','action-id'=>$chapterInfo['id'],'action-title'=>$chapterInfo['title']));?></span>
            </p>
            <div class="chapter-content">
                <?php if($chapterInfo['postscript']!='' && $chapterInfo['psPosition']==1){?>
                <p><?php echo nl2br($chapterInfo['postscript']);?></p>
                <hr/>
                <?php }?>
                <?php echo Chapters::text($chapterInfo['content']); ?>
                <?php if($chapterInfo['postscript']!='' && $chapterInfo['psPosition']==0){?>
                <hr/>
                <p><?php echo nl2br($chapterInfo['postscript']);?></p>                
                <?php }?>
            </div>            
        </div>
        <div class="module" id="props-holder">
            <div class="module-header">赞赏榜</div>
            <div class="module-body">
                <?php $this->renderPartial('/common/props',array('props'=>$props,'keyid'=>$chapterInfo['id']));?>
                <p class="text-center"><?php echo CHtml::link('赞赏','javascript:;',array('action'=>'getProps','data-id'=>$chapterInfo['id'],'data-type'=>'chapter','data-target'=>'props-holder-'.$chapterInfo['id'],'data-loaded'=>0,'class'=>'btn btn-danger'));?></p>
                <div class="props-holder" id="props-holder-<?php echo $chapterInfo['id'];?>-box">
                    <i class="icon-spike" style="display: inline;right:85px"></i>
                    <div id="props-holder-<?php echo $chapterInfo['id']; ?>"></div>
                </div>
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
            <?php if(GroupPowers::checkAction($this->userInfo, 'addChapterTip')){?>
            <div class="module-header" id="add-tip-holder">写点评</div>
            <div class="module-body">
                <?php if($this->uid){
                    if($this->tipInfo!==false){
                        if($this->tipInfo['status']==Posts::STATUS_PASSED){
                            echo '<p class="help-block">每章节只能评价一次，但你可以对 '.CHtml::link('现有的评价','javascript:;',array('action-target'=>'tip-'.$this->tipInfo['id'],'action'=>'scroll')).' 进行修改。</p>';
                        }else{
                            echo '<p class="help-block">你已经删除了对本章节的点评，如需显示，请'.CHtml::link('重新编辑',array('book/editTip','tid'=>$this->tipInfo['id'])).'。</p>';
                        }
                    }else{
                        $this->renderPartial('/common/addTips',array('type'=>'chapter','keyid'=>$chapterInfo['id']));
                    }
                 }else{?>
                <p class="help-block">登录后享有更多功能，<?php echo CHtml::link('立即登录',array('site/login'));?>或<?php echo CHtml::link('注册',array('site/reg'));?>。</p>
                <?php }?>
            </div>
            <?php }?>
        </div>
    </div>    
</div>
<div class="chapter-fixed-navbar" id="chapter-fixed-navbar">
    <div class="fixed-btns">
        <?php echo CHtml::link('<i class="fa fa-list"></i> 目录','javascript:;',array('action'=>'showChapters'));?>
        <?php echo CHtml::link((($this->uid && $this->tipInfo!==false) ? '<i class="fa fa-star"></i> 已点评' : '<i class="fa fa-star-o"></i> 点评'),'javascript:;',array('action'=>'scroll','action-target'=>'add-tip-holder'));?>
        <?php echo CHtml::link('<i class="fa fa-long-arrow-left"></i> 上一章',!empty($prev) ? $prev['url'] : 'javascript:;',array('action'=>'preChapter','title'=>(!empty($prev) ? $prev['title'].'（键盘←）' : '已是第一篇'),'id'=>'preChapter'));?>
        <?php echo CHtml::link('<i class="fa fa-long-arrow-right"></i> 下一章',!empty($next) ? $next['url'] : 'javascript:;',array('action'=>'nextChapter','title'=>(!empty($next) ? $next['title'].'（键盘→）' : '已是最后一篇'),'id'=>'nextChapter'));?>
        <?php echo CHtml::link('<i class="fa fa-reply"></i> 返回',array('book/view','id'=>$chapterInfo['bid']));?>
        <?php echo CHtml::link('<i class="fa fa-magic"></i> 赞赏','javascript:;',array('action'=>'scroll','action-target'=>'props-holder'));?>
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