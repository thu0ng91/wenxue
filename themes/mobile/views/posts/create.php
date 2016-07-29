<?php

/**
 * @filename create.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2015-12-18  16:36:08 
 */
?>
<div class="container">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'posts-form',
        'enableAjaxValidation'=>false,
    )); ?>
    <div class="module add-post-page">
        <div class="module-body padding-body">            
            <?php echo $form->errorSummary($model); ?>
            <div class="form-group">
                <?php echo $form->labelEx($model,'title'); ?>
                <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control','placeholder'=>'请输入帖子标题')); ?>
            </div>
            
            <div class="form-group">
                <label>帖子正文</label>
                <?php echo $form->textArea($model,'content',array('rows'=>6,'class'=>'form-control','placeholder'=>'请输入帖子正文')); ?>
            </div>
            
            <?php if(!empty($tags)){?>
            <div class="form-group">
                <label>选择标签</label>
                <div class="tags-body">
                    <?php foreach ($tags as $tagid=>$tag){?>
                    <span class="tag-item">
                        <?php echo $tag;?>
                        <input type="checkbox" name="tags[]" value="<?php echo $tagid;?>"/>
                    </span>
                    <?php }?>
                </div>
            </div>
            <?php }?>
            
            <div class="form-group">
                <label>文章设置</label>
                <div class="checkbox">
                    <label><?php echo CHtml::activeCheckBox($model, 'open');?> 开放评论和点赞</label>
                </div>
            </div>    
            
            <div class="form-group text-right">
                <?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-success','id'=>'add-post-btn')); ?>
            </div>            
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div><!-- form -->
