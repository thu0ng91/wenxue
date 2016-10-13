<?php
/**
 * @filename _postPost.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-9-5  14:09:17 
 */
?>
<div class="media" id="reply-<?php echo $data['id'];?>">
    <div class="media-left text-center">
        <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $data['username'], array('data-original' => $data['avatar'], 'class' => 'lazy img-circle a48 media-object')), $data['authorUrl']); ?>
        <p><?php echo CHtml::link($data['levelTitle'],array('site/level','id'=>$data['level']),array('class'=>'btn btn-block btn-xs btn-default'));?></p>
    </div>
    <div class="media-body">
        <p class="help-block title"><?php echo CHtml::link($data['username'],array());?></p>
        <div class="post-content-item"><?php echo $data['content'];?></div>
<!--        <div class="more-awesome">
            <span>打赏榜</span>
        </div>-->
        <?php $this->renderPartial('/common/props',array('props'=>$data['props'],'keyid'=>$data['id']));?>
        <p>
            <span class="color-grey"><?php echo zmf::formatTime($data['cTime']);?></span>
            <span class="right-actions">
                <span><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'post','action-id'=>$data['id'],'action-title'=>$data['title']));?></span>
                <span><?php echo CHtml::link('赞赏'.$data['comments'],'javascript:;',array('action'=>'getProps','data-id'=>$data['id'],'data-type'=>'postPosts','data-target'=>'props-holder-'.$data['id'],'data-loaded'=>0));?></span>
                <span><?php echo GroupPowers::link('favorPostReply',$this->userInfo,'<i class="fa '.($data['favorited'] ? 'fa-thumbs-up' : 'fa-thumbs-o-up').'"></i> '.$data['favors'],'javascript:;',array('action'=>'favorite','action-data'=>$data['id'],'action-type'=>'postPosts'),true);?></span>
                <span><?php echo CHtml::link('<i class="fa fa-comment-o"></i> '.$data['comments'],'javascript:;',array('action'=>'getContents','data-id'=>$data['id'],'data-type'=>'postPosts','data-target'=>'comments-postPosts-'.$data['id'],'data-loaded'=>0));?></span>
            </span>
        </p>
        <div class="comments-list" id="comments-postPosts-<?php echo $data['id'];?>-box">
            <i class="icon-spike" style="display: inline;right:10px"></i>
            <div id="comments-postPosts-<?php echo $data['id'];?>"></div>
            <div id="comments-postPosts-<?php echo $data['id'];?>-form"></div>
        </div>
        <div class="props-holder" id="props-holder-<?php echo $data['id'];?>-box">
            <i class="icon-spike" style="display: inline;right:85px"></i>
            <div id="props-holder-<?php echo $data['id'];?>"></div>
        </div>
    </div>
</div>