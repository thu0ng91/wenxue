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
<div class="chapter-container">
    <div class="chapter-page">
        <div class="chapter-header">
            <h1><?php echo $chapterInfo['title']; ?></h1>
            <h2 class="color-grey">【<?php echo CHtml::link($bookInfo['title'],array('book/view','id'=>$bookInfo['id'])); ?>】<?php echo CHtml::link($authorInfo['authorName'],array('author/view','id'=>$chapterInfo['aid'])); ?>著</h2>
            <p class="chapter-min-tips color-grey">
                <span><?php echo $chapterInfo['words']; ?>字</span>
                <span><?php echo $chapterInfo['hits']; ?>阅读</span>                
                <span><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'chapter','action-id'=>$chapterInfo['id'],'action-title'=>$chapterInfo['title']));?></span>
            </p>
        </div>        
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
    <div class="chapter-tips-module">
        <div class="module">
            <div class="module-header">点评</div>
            <div class="padding-body module-body" id="comments-chapter-<?php echo $chapterInfo['id'];?>">                
                <div id="more-content" class="book-tips">
                    <?php if(!empty($tips)){?>
                    <?php foreach ($tips as $tip){?> 
                    <?php $this->renderPartial('/book/_tip',array('data'=>$tip));?>
                    <?php }?>
                    <?php }else{?>
                    <p class="help-block">还没人写过点评，快来抢沙发吧</p>
                    <?php }?>
                </div>
            </div>            
        </div>
        <div class="module">
            <div class="module-header" id="add-tip-holder">写点评</div>
            <div class="module-body padding-body">
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
        </div>
    </div>
</div>
<div class="chapter-fixed-navbar">
    <div class="fixed-btns ui-row-flex">
        <div class="ui-col"><?php echo CHtml::link('<i class="fa fa-reply"></i> 返回',array('book/view','id'=>$chapterInfo['bid']));?></div>
        <div class="ui-col"><?php echo CHtml::link('<i class="fa fa-long-arrow-left"></i> 上一章',!empty($prev) ? $prev['url'] : 'javascript:;',array('action'=>'preChapter','id'=>'preChapter'));?></div>
        <div class="ui-col"><?php echo CHtml::link('<i class="fa fa-list"></i> 目录','javascript:;',array('action'=>'showChapters'));?></div>
        <div class="ui-col"><?php echo CHtml::link((($this->uid && $this->tipInfo!==false) ? '<i class="fa fa-star"></i> 已点评' : '<i class="fa fa-star-o"></i> 点评'),'javascript:;',array('action'=>'scroll','action-target'=>'add-tip-holder'));?></div>
        <div class="ui-col"><?php echo CHtml::link('<i class="fa fa-long-arrow-right"></i> 下一章',!empty($next) ? $next['url'] : 'javascript:;',array('action'=>'nextChapter','id'=>'nextChapter'));?></div>
    </div>
</div>    
<div class="fixed-chapters" id="fixed-chapters">    
    <div class="fixed-chapters-body">
        <ul class="ui-list ui-list-text">
            <?php $len=count($chapters);foreach ($chapters as $k=>$chapter){?>
            <li class="<?php if(($k+1)!=$len){?>ui-border-b<?php }?>"><p><?php echo CHtml::link($chapter['title'],array('book/chapter','cid'=>$chapter['id']),array('class'=>$chapterInfo['id']==$chapter['id'] ? 'active' : ''));?></p></li>
            <?php }?>
        </ul>
    </div>
    <i class="fa fa-caret-down"></i>
</div>
