<?php

/**
 * @filename book.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-13  17:56:14 
 */
?>
<div class="author-content-holder">
    <div class="media">
        <div class="media-left">
            <img class="media-object lazy" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $info['faceImg'];?>" alt="<?php echo $info['title'];?>">  
            <?php if($info['bookStatus']==Books::STATUS_NOTPUBLISHED){?>
            <p><?php echo CHtml::link('立即发布','javascript:;',array('class'=>'btn btn-danger btn-xs btn-block','action'=>'publishBook','data-id'=>$info['id']));?></p>
            <?php }else{?>
            <p><?php echo CHtml::link('预览',array('book/view','id'=>$info['id']),array('class'=>'btn btn-success btn-xs btn-block','target'=>'_blank'));?></p>
            <p><?php echo CHtml::link('分享','javascript:;',array('class'=>'btn btn-default btn-xs btn-block'));?></p>
            <p><?php echo CHtml::link('编辑',array('author/updateBook','bid'=>$info['id']),array('class'=>'btn btn-default btn-xs btn-block'));?></p>
            <p><?php echo $info['bookStatus']==Books::STATUS_FINISHED ? CHtml::link('已完结','javascript:;',array('class'=>'btn btn-default btn-xs btn-block')) : CHtml::link('完结','javascript:;',array('action'=>'finishBook','data-id'=>$info['id'],'class'=>'btn btn-info btn-xs btn-block','title'=>'标记为已完结？'));?></p>
            <?php }?>
        </div>
        <div class="media-body">
            <h4><?php echo $info['title'];?></h4>
            <p><?php echo Books::starCss($info['score']).$info['scorer'].'人评价';?></p>
            <p class="help-block"><?php echo $info['favorites'].'收藏 '.$info['comments'].'评论 '.$info['hits'].'点击';?></p>
            <p><b>简介：</b><span class="color-grey"><?php echo $info['desc'];?></span></p>
            <p><b>介绍：</b><span class="color-grey"><?php echo $info['content'];?></span></p>
            <?php if(!empty($myActivity)){?>
            <p><b>参与活动：</b></p>
            <?php foreach ($myActivity as $ac){?>
            <p><?php echo CHtml::link($ac['title'],array('activity/view','id'=>$ac['id']),array('target'=>'_blank','class'=>'color-warning'));?></p>
            <?php }?>
            <?php }else{?>
            <p><b>参与活动：</b><span class="color-grey">暂未参加任何活动</span></p>
            <?php }?>
            <?php if(!empty($activity)){?>
            <div class="input-group input-group-sm">
                <span class="input-group-addon">可参与活动</span>
                <?php echo CHtml::dropDownList('activityId', '', $activity,array('class'=>'form-control'));?>
                <span class="input-group-btn">
                    <?php echo CHtml::link('确认参与','javascript:;',array('class'=>'btn btn-default','action'=>'joinActivity','action-data'=>$info['id']));?>
                </span>
            </div><!-- /input-group -->
            <?php }?>
        </div>
    </div>
    <div class="module-header">目录</div>
    <div class="module-body author-book-chapters">
        <table class="table table-hover table-striped">
            <?php foreach ($chapters as $chapter){?>
            <tr>
                <td style="width: 40px" title="章节号"><?php echo $chapter['chapterNum'];?></td>
                <td>
                    <?php echo CHtml::link($chapter['title'],array('book/chapter','cid'=>$chapter['id']));?>
                    <?php echo $chapter['chapterStatus']==Books::STATUS_PUBLISHED ? '':'<span class="color-warning">'.($chapter['chapterStatus']==Books::STATUS_STAYCHECK ? '待审核' : '草稿').'</span>';?>
                </td>
                <td style="width: 120px">
                    <?php echo CHtml::link('编辑',array('author/addChapter','cid'=>$chapter['id']));?>
                    <?php echo $chapter['chapterStatus']==Books::STATUS_PUBLISHED ? '' : CHtml::link('发表','javascript:;',array('action'=>'publishChapter','data-id'=>$chapter['id']));?>
                    <?php echo CHtml::link('删除','javascript:;',array('action'=>'delChapter','data-id'=>$chapter['id']));?>
                </td>
            </tr>
            <?php }?>            
        </table>
        <?php echo $info['bookStatus']!=Books::STATUS_FINISHED ? CHtml::link('<i class="fa fa-plus"></i> 新章节',array('author/addChapter','bid'=>$info['id']),array('class'=>'btn btn-primary btn-block')):'';?>
    </div>
</div>