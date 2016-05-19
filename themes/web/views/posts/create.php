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
<style>
    .add-post-page{
        background: #fff;
        float: left;
        width: 960px
    }
    .add-post-page .main-part{
        padding: 10px
    }
</style>
<?php $uploadurl=Yii::app()->createUrl('attachments/upload',array('type'=>'posts','imgsize'=>600));?>
<div class="container">
    <div class="module add-post-page">
        <div class="main-part">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'posts-form',
                'enableAjaxValidation'=>false,
            )); ?>
            <?php echo $form->errorSummary($model); ?>
            <div class="form-group">
                <?php echo $form->labelEx($model,'title'); ?>
                <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control','placeholder'=>'文章标题')); ?>
            </div>
            <div class="form-group">
                <?php $this->renderPartial('//common/editor_um', array('model' => $model,'content' => $model->content,'uploadurl'=>$uploadurl,'mini'=>true,'editorWidth'=>620,'editorHeight'=>360)); ?>      
            </div>
            <div class="form-group">
                <?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-success','id'=>'editorSubmit')); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
        <div class="aside-part">
            <div class="module-header">选择标签</div>
            <div class="module-body">
                
            </div>
        </div>
    </div>
</div><!-- form -->
