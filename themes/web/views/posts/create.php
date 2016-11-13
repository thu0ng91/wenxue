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
<?php $uploadurl=Yii::app()->createUrl('attachments/upload',array('type'=>'posts','imgsize'=>'c640'));?>
<div class="container">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'posts-form',
        'enableAjaxValidation'=>false,
    )); ?>
    <ol class="breadcrumb">
        <li><a href="#">初心创文首页</a></li>
        <li><?php echo CHtml::link($forumInfo['title'],array('posts/index','forum'=>$forumInfo['id']));?></li>        
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
            <div class="module-header">帖子设置</div>
            <div class="module-body power-body">                
                <div class="checkbox">
                    <label><?php echo CHtml::activeCheckBox($model, 'open');?> 开放评论和点赞</label>
                </div>
                <?php if($this->userInfo['authorId']>0){?>
                <div class="checkbox">
                    <label>
                        <?php echo $form->checkbox($model,'aid');?> 以作者身份回复
                    </label>
                </div>
                <?php }?>
                <?php if($setThreadStatus){?>
                <div class="checkbox">
                    <label><?php echo CHtml::activeCheckBox($model, 'display');?> 最新回帖显示在前面</label>
                </div>
                <?php }?>
            </div>
            <?php if($setThreadStatus){?>
            <div class="module-header">回帖设置</div>
            <div class="module-body power-body form-horizontal">
                <div class="form-group" style="margin-top: 15px">
                    <label class="col-sm-5 control-label">哪些可回帖</label>
                    <div class="col-sm-7">
                        <?php echo $form->dropDownlist($model,'posterType',  PostForums::posterType('admin'),array('class'=>'form-control','empty'=>'--不限--')); ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <?php echo $form->labelEx($model,'postExpiredTime',array('class'=>'col-sm-5 control-label')); ?>
                    <div class="col-sm-7">
                        <?php 
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model'=>$model,
                        'attribute'=>'postExpiredTime',
                        'language'=>'zh-cn',
                        'value'=>date('Y/m/d',$model->postExpiredTime),			    
                                    'options'=>array(
                                        'showAnim'=>'fadeIn',
                                    ),	
                                    'htmlOptions'=>array(
                                        'readonly'=>'readonly',
                                        'class'=>'form-control',
                                        'value'=>date('Y/m/d',($model->postExpiredTime) ? $model->postExpiredTime :'')
                                ),		    
                                ));
                        ?>
                    </div>                    
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'voteExpiredTime',array('class'=>'col-sm-5 control-label')); ?>
                    <div class="col-sm-7">
                        <?php 
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model'=>$model,
                        'attribute'=>'voteExpiredTime',
                        'language'=>'zh-cn',
                        'value'=>date('Y/m/d',$model->voteExpiredTime),			    
                                    'options'=>array(
                                        'showAnim'=>'fadeIn',
                                    ),	
                                    'htmlOptions'=>array(
                                        'readonly'=>'readonly',
                                        'class'=>'form-control',
                                        'value'=>date('Y/m/d',($model->voteExpiredTime) ? $model->voteExpiredTime :'')
                                ),		    
                                ));
                        ?>
                    </div>                    
                </div>
            </div>
            <?php }?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div><!-- form -->
