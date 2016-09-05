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
<div class="media">
    <div class="media-left text-center">
        <?php echo CHtml::link(CHtml::image(zmf::lazyImg(), $data['username'], array('data-original' => $data['avatar'], 'class' => 'lazy img-circle a48 media-object')), $data['authorUrl']); ?>
        <p><a class="btn btn-block btn-xs btn-default">等级标识</a></p>
       
    </div>
    <div class="media-body">
        <p class="help-block"><?php echo CHtml::link($data['username'],array());?>发布于<?php echo zmf::formatTime($data['cTime']);?></p>
        <div class="post-content-item"><?php echo $data['content'];?></div>
        <p>
            <span class="right-actions color-grey">
                <span><?php echo CHtml::link('举报','javascript:;',array('action'=>'report','action-type'=>'post','action-id'=>$info['id'],'action-title'=>$info['title']));?></span>
                <span>打赏</span>
                <span><?php echo $data['favors'];?> 赞</span>                
                <span><?php echo $data['comments'];?> 回复</span>
            </span>
        </p>
    </div>
</div>