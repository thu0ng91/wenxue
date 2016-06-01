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
            <?php if($info['bookStatus']!=Books::STATUS_PUBLISHED){?>
            <p><?php echo CHtml::link('立即发布','javascript:;',array('class'=>'btn btn-danger btn-xs btn-block','action'=>'publishBook','data-id'=>$info['id']));?></p>
            <?php }else{?>
            <p><?php echo CHtml::link('预览',array('book/view','id'=>$info['id']),array('class'=>'btn btn-success btn-xs btn-block','target'=>'_blank'));?></p>
            <p><?php echo CHtml::link('分享','javascript:;',array('class'=>'btn btn-default btn-xs btn-block'));?></p>
            <?php }?>
        </div>
        <div class="media-body">
            <h4><?php echo $info['title'];?></h4>
            <p class="help-block"><?php echo $info['desc'];?></p>
            <p class="help-block"><?php echo $info['content'];?></p>
        </div>
    </div>
    <div class="module-header">目录</div>
    <div class="module-body author-book-chapters">
        <table class="table table-hover table-striped">
            <?php foreach ($chapters as $chapter){?>
            <tr>
                <td>
                    <?php echo CHtml::link($chapter['title'],array('book/chapter','cid'=>$chapter['id']));?>
                    <?php echo $chapter['status']==Books::STATUS_PUBLISHED ? '':'<span class="color-warning">'.($chapter['status']==Books::STATUS_STAYCHECK ? '待审核' : '草稿').'</span>';?>
                </td>
                <td style="width: 120px">
                    <?php echo CHtml::link('编辑',array('author/addChapter','cid'=>$chapter['id']));?>
                    <?php echo $chapter['status']==Books::STATUS_PUBLISHED ? '' : CHtml::link('发表','javascript:;',array('action'=>'publishChapter','data-id'=>$chapter['id']));?>
                    <?php echo CHtml::link('删除',array('author/addChapter','cid'=>$chapter['id']));?>
                </td>
            </tr>        
            <?php }?>            
        </table>
        <?php echo CHtml::link('<i class="fa fa-plus"></i> 新章节',array('author/addChapter','bid'=>$info['id']),array('class'=>'btn btn-primary btn-block'));?>
    </div>
</div>