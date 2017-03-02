<?php
/**
 * @filename PostPostsController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-05 04:08:51 
 */
$uploadurl=Yii::app()->createUrl('attachments/upload',array('type'=>'threads','imgsize'=>'c640'));
 ?>

<div class="container">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-posts-form',
	'enableAjaxValidation'=>false,
)); ?>
    <ol class="breadcrumb">
        <li><?php echo CHtml::link('初心创文首页',zmf::config('baseurl'));?></li>
        <li><?php echo CHtml::link($threadInfo['title'],array('posts/view','id'=>$threadInfo['id']));?></li>        
        <li class="active">回帖</li>
    </ol>
    <div class="module add-post-page">
        <div class="main-part">
            <?php echo $form->errorSummary($model); ?>
            <div class="form-group">
                <?php echo $form->labelEx($model,'content'); ?>     
                <?php $this->renderPartial('//common/editor_um', array('model' => $model,'content' => $model->content,'uploadurl'=>$uploadurl,'mini'=>true,'editorWidth'=>620,'editorHeight'=>360)); ?>                      
                <?php echo $form->error($model,'content'); ?>
            </div>
            <div class="form-group text-right">
                <?php echo CHtml::submitButton($model->isNewRecord ? '回帖' : '更新',array('class'=>'btn btn-success','id'=>'add-post-btn')); ?>
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
                <?php if($forumInfo['anonymous']){?>
                <div class="checkbox">
                    <label><?php echo CHtml::activeCheckBox($model, 'anonymous');?> 匿名回复</label>
                </div>
                <?php }?>
                <?php if($this->userInfo['authorId']>0){?>
                <div class="checkbox">
                    <label>
                        <?php echo $form->checkbox($model,'aid');?> 以作者身份回复
                    </label>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->