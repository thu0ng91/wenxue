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
<?php $uploadurl=Yii::app()->createUrl('attachments/upload',array('type'=>'posts','imgsize'=>600));?>
<div class="container">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'posts-form',
        'enableAjaxValidation'=>false,
    )); ?>
    <ol class="breadcrumb">
        <li><a href="#">初心创文首页</a></li>
        <?php if($model['classify']==Posts::CLASSIFY_AUTHOR){?>
        <li><?php echo CHtml::link('作者专区',array('posts/index','type'=>'author'));?></li>
        <?php }elseif($model['classify']==Posts::CLASSIFY_READER){?>
        <li><?php echo CHtml::link('读者专区',array('posts/index','type'=>'reader'));?></li>
        <?php }?>
        <li class="active">新增文章</li>
    </ol>
    <div class="module add-post-page">
        <div class="main-part">            
            <?php echo $form->errorSummary($model); ?>
            <div class="form-group">
                <?php echo $form->labelEx($model,'title'); ?>
                <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control','placeholder'=>'文章标题')); ?>
            </div>
            <div class="form-group">
                <?php $this->renderPartial('//common/editor_um', array('model' => $model,'content' => $model->content,'uploadurl'=>$uploadurl,'mini'=>true,'editorWidth'=>620,'editorHeight'=>360)); ?>      
            </div>
            <div class="form-group text-right">
                <?php echo CHtml::submitButton($model->isNewRecord ? '提交' : '更新',array('class'=>'btn btn-success','id'=>'add-post-btn')); ?>
            </div>            
        </div>
        <div class="aside-part">
            <?php if(!empty($tags)){?>
            <div class="module-header">选择标签</div>
            <div class="module-body tags-body">
                <?php foreach ($tags as $tagid=>$tag){?>
                <span class="tag-item">
                    <?php echo $tag;?>
                    <input type="checkbox" name="tags[]" value="<?php echo $tagid;?>"/>
                </span>
                <?php }?>
            </div>
            <?php }?>
            <div class="module-header">文章设置</div>
            <div class="module-body power-body">
                <div class="checkbox">
                    <label><?php echo CHtml::activeCheckBox($model, 'open');?> 开放评论和点赞</label>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div><!-- form -->
