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
            <img class="media-object" src="<?php echo $info['faceImg'];?>" alt="<?php echo $info['title'];?>">            
        </div>
        <div class="media-body">
            <h4><?php echo $info['title'];?></h4>
            <p class="help-block"><?php echo $info['desc'];?></p>
            <p class="help-block"><?php echo $info['content'];?></p>
        </div>
    </div>
    <style>
        .author-book-chapters .table>tr:first-child td{
            border-top: none !important
        }
    </style>
    <div class="module-header">目录</div>
    <div class="module-body author-book-chapters">
        <table class="table table-hover table-striped">
            <?php foreach ($chapters as $chapter){?>
            <tr>
                <td>
                    <?php echo CHtml::link($chapter['title'],array('book/chapter','cid'=>$chapter['id']));?>
                    <?php echo $chapter['status']==Posts::STATUS_PASSED ? '':'<span class="color-warning">草稿</span>';?>
                </td>
                <td style="width: 120px">
                    <?php echo CHtml::link('编辑',array('author/addChapter','cid'=>$chapter['id']));?>
                    <?php echo $chapter['status']==Posts::STATUS_PASSED ? '' : CHtml::link('发布',array('author/addChapter','cid'=>$chapter['id']));?>
                    <?php echo CHtml::link('删除',array('author/addChapter','cid'=>$chapter['id']));?>
                </td>
            </tr>        
            <?php }?>            
        </table>
        <?php echo CHtml::link('<i class="fa fa-plus"></i> 新章节',array('author/addChapter','bid'=>$info['id']),array('class'=>'btn btn-primary btn-block'));?>
    </div>
</div>