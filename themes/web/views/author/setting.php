<?php

/**
 * @filename setting.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-5-24  10:39:41 
 */
?>
<div class="module-header">修改资料</div>
<div class="module-body">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'authors-form',
        'enableAjaxValidation'=>false,
    )); ?>
    <?php echo CHtml::hiddenField('type',$type);?>
    <?php if(Yii::app()->user->hasFlash('updateAuthorInfoSuccess')){echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('updateAuthorInfoSuccess').'</div>';}?>
    <?php if($type=='info'){?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'authorName'); ?>
        <?php echo $form->textField($model,'authorName',array('class'=>'form-control','disabled'=>'disabled')); ?>
        <?php echo $form->error($model,'authorName'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'content'); ?>
        <?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
        <?php echo $form->error($model,'content'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
    <?php }elseif ($type=='skin') {?>
    <?php echo $form->hiddenField($model,'avatar'); ?>
    <?php echo $form->hiddenField($model,'skinUrl'); ?>
    <div class="form-group change-avatar-holder">
        <div class="media">
            <div class="media-left">
                <img src="<?php echo $model->avatar;?>" alt="修改头像" id="author-avatar">
            </div>
            <div class="media-body">
                <p><a href="javascript:;" class="btn btn-default openGallery" role="button" data-holder="author-avatar" data-field="<?php echo CHtml::activeId($model, 'avatar');?>">选择头像</a></p>
                <p class="help-block">修改作者的头像</p>
                <p class="help-block">图片必须是站内图片，<?php echo CHtml::link('点此管理素材',array('user/gallery'));?></p>
                <?php echo $form->error($model,'avatar'); ?>
            </div>
        </div>
        <div class="media">
            <div class="media-left">
                <img src="<?php echo $model->skinUrl;?>" alt="修改背景图" id="author-skin">
            </div>
            <div class="media-body">
                <p><a href="javascript:;" class="btn btn-default openGallery" role="button" data-holder="author-skin" data-field="<?php echo CHtml::activeId($model, 'skinUrl');?>">更换背景</a></p>
                <p class="help-block">修改作者主页的背景大图</p>
                <p class="help-block">更换图片后请点击更新按钮保存设置</p>
                <?php echo $form->error($model,'skinUrl'); ?>
                <p><?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?></p>
            </div>
        </div>
    </div>
    <?php }elseif($type=='passwd'){?> 
    <div class="form-group">
        <label>原始密码</label>
        <?php echo $form->passwordField($model,'password',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model,'newPassword'); ?>
        <?php echo $form->passwordField($model,'newPassword',array('class'=>'form-control')); ?>
        <p class="help-block">长度不小于6位，且不能和账号密码相同</p>
        <?php echo $form->error($model,'newPassword'); ?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-primary')); ?>
    </div>
    <?php }?>
    <?php $this->endWidget(); ?>
</div>