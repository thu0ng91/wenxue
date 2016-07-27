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
<div class="module">
    <ul class="ui-list">
        <li class="ui-border-t">
            <div class="ui-list-img">
                <img class="w78 lazy" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo $info['faceImg'];?>" alt="<?php echo $info['title'];?>">
            </div>
            <div class="ui-list-info">
                <h4><?php echo $info['title'];?></h4>
                <p>推荐语：<span class="color-grey"><?php echo $info['desc'];?></span></p>
                <p>简介：<span class="color-grey"><?php echo $info['content'];?></span></p>
                <?php if($info['bookStatus']!=Books::STATUS_PUBLISHED){?>
                <p><?php echo CHtml::link('立即发布','javascript:;',array('class'=>'btn btn-danger btn-xs btn-block','action'=>'publishBook','data-id'=>$info['id']));?></p>
                <?php }else{?>
                <p>
                    <?php echo CHtml::link('预览',array('book/view','id'=>$info['id']),array('class'=>'btn btn-success btn-xs btn-block','target'=>'_blank'));?>
                    <?php echo CHtml::link('编辑',array('author/updateBook','bid'=>$info['id']),array('class'=>'btn btn-default btn-xs btn-block'));?>
                    <span class="pull-right color-grey"><?php echo CHtml::link('<i class="fa fa-exclamation-triangle"></i> 删除','javascript:;',array('action'=>'delBook','data-id'=>$info['id'],'data-redirect'=>  Yii::app()->createUrl('author/view',array('id'=>$info['aid']))));?></span>
                </p>
                <?php }?>
            </div>
        </li>
    </ul>
    <div class="module-header">目录</div>
    <div class="module-body author-book-chapters">
        <ul class="ui-list ui-list-pure ui-border-t">
            <?php foreach ($chapters as $chapter){?>
            <li class="ui-border-tb">
                <p><span class="color-grey"><?php echo $chapter['chapterNum'];?></span>
                    <?php echo CHtml::link($chapter['title'],array('book/chapter','cid'=>$chapter['id']));?>
                    <?php echo $chapter['chapterStatus']==Books::STATUS_PUBLISHED ? '':'<span class="color-warning">'.($chapter['chapterStatus']==Books::STATUS_STAYCHECK ? '待审核' : '草稿').'</span>';?>
                    <span class="pull-right">                    
                    <?php echo CHtml::link('编辑',array('author/addChapter','cid'=>$chapter['id']));?>
                    <?php echo $chapter['chapterStatus']==Books::STATUS_PUBLISHED ? '' : CHtml::link('发表','javascript:;',array('action'=>'publishChapter','data-id'=>$chapter['id']));?>
                    <?php echo CHtml::link('删除','javascript:;',array('action'=>'delChapter','data-id'=>$chapter['id']));?>
                    </span>
                </p>
            </li>   
            <?php }?>     
            <li>
                <h4><?php echo CHtml::link('<i class="fa fa-plus"></i> 新章节',array('author/addChapter','bid'=>$info['id']),array('class'=>'btn btn-primary btn-block'));?></h4>
            </li>
        </ul>        
    </div>
</div>