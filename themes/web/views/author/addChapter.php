<?php
/**
 * @filename ChaptersController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-05-11 10:54:32 */
 ?>
<style>
    .create-chapter-form{
        width:760px;
        padding: 0 20px;
        margin: 40px auto;
    }
    .create-chapter-form label{
        
    }
    .create-chapter-form .form-control{
        background: transparent;
        border:none;
        border-bottom:1px solid #fff;
        box-shadow: none;
        padding-left: 0;
        padding-right: 0;
        word-break: break-all;
        max-width: 600px;
        overflow-x: hidden
    }
    .right-fixed{
        position: fixed;
        bottom: 100px;
        left: 50%;
        margin-left: 360px;
        width: 80px;
    }
    .edui-container .edui-toolbar{
        display: none
    }
    .edui-container{
        border: 1px solid #fff
    }
</style>
<div class="create-chapter-form">
    <div class="form">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'chapters-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model,'title'); ?>
            <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control','placeholder'=>'请输入章节标题')); ?>
            <?php echo $form->error($model,'title'); ?>
        </div>    
        <div class="form-group">
            <?php echo $form->labelEx($model,'content'); ?>
            <?php $this->renderPartial('//common/editor_um', array('model' => $model,'content' => $model->content!='' ? Chapters::text($model->content) : '','hiddenToolbar'=>true,'editorWidth'=>720,'editorHeight'=>400)); ?>
            <?php echo $form->error($model,'content'); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <div class="right-fixed">        
        <div class="form-group">
            <?php echo CHtml::submitButton($model->isNewRecord ? '立即发布' : '更新章节',array('class'=>'btn btn-primary')); ?>
        </div>
        <div class="form-group">
            <?php echo CHtml::submitButton( '存草稿',array('class'=>'btn btn-default')); ?>
        </div>
    </div>
</div><!-- form -->