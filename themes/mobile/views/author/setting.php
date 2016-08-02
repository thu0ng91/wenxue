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
<div class="module">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'authors-form',
        'enableAjaxValidation'=>false,
    )); ?>
    <?php echo CHtml::hiddenField('type',$type);?>
    <?php if(Yii::app()->user->hasFlash('updateAuthorInfoSuccess')){echo '<div class="alert alert-danger">'.Yii::app()->user->getFlash('updateAuthorInfoSuccess').'</div>';}?>
    <?php if($type=='info'){?>
    <div class="module-header">修改资料</div>
    <div class="module-body padding-body">
        <div class="form-group">
            <?php echo $form->labelEx($model,'authorName'); ?>
            <?php echo $form->textField($model,'authorName',array('class'=>'form-control','disabled'=>'disabled')); ?>
            <?php echo $form->error($model,'authorName'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'content'); ?>
            <?php echo $form->textArea($model,'content',array('rows'=>3, 'cols'=>50,'class'=>'form-control')); ?>
            <?php echo $form->error($model,'content'); ?>
        </div>
        <div class="form-group">
            <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-success')); ?>
        </div>
    </div>
    <?php }elseif ($type=='skin') {?>    
    <div class="module-header">修改背景</div>
    <div class="module-body">
        <div class="form-group">
            <ul class="ui-list">
                <li>
                    <div class="ui-avatar">
                        <img src="<?php echo zmf::getThumbnailUrl($model->skinUrl, 'c120', 'avatar');?>" alt="修改头像" id="author-skin" class="a50">
                    </div>
                    <div class="ui-list-info">
                        <?php $this->renderPartial('/common/_singleUpload',array('model'=>$model,'fieldName'=>'skinUrl','type'=>'author','fileholder'=>'filedata','targetHolder'=>'author-skin','imgsize'=>'c120','progress'=>true));?>
                        <p class="help-block">修改作者主页的背景大图，建议尺寸960*300px，其他尺寸将被裁剪，更换图片后请点击更新按钮保存设置</p>
                    </div>
                </li>
            </ul>
            <div id="progress" class="progress">
                <div class="progress-bar progress-bar-success" style="width: 0%;"></div>
            </div>
        </div>        
        <?php echo $form->hiddenField($model,'skinUrl'); ?>
        <div class="form-group padding-body">
            <p><?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-success')); ?></p>
            <?php echo $form->error($model,'skinUrl'); ?>
        </div>
    </div>
    <?php }elseif($type=='avatar'){?> 
    <div class="module-header">修改头像</div>
    <div class="module-body">
        <?php echo $form->hiddenField($model,'avatar'); ?>
        <div class="form-group">
            <ul class="ui-list">
                <li>
                    <div class="ui-avatar">
                        <img src="<?php echo zmf::getThumbnailUrl($model->avatar, 'a120', 'avatar');?>" alt="修改头像" id="author-avatar" class="a50">
                    </div>
                    <div class="ui-list-info">
                        <?php $this->renderPartial('/common/_singleUpload',array('model'=>$model,'fieldName'=>'avatar','type'=>'author','fileholder'=>'filedata','targetHolder'=>'author-avatar','imgsize'=>'c120','progress'=>true));?>
                        <p class="help-block">修改作者的头像，更换图片后请点击更新按钮保存设置</p>
                    </div>
                </li>
            </ul>
            <div id="progress" class="progress">
                <div class="progress-bar progress-bar-success" style="width: 0%;"></div>
            </div>
        </div>
        <div class="form-group padding-body">
            <p><?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-success')); ?></p>
            <?php echo $form->error($model,'avatar'); ?>
        </div>
    </div>
    <?php }elseif($type=='passwd'){?> 
<div class="login-reg-module">    
    <?php echo CHtml::link('<i class="fa fa-remove"></i>',$this->referer ? $this->referer : 'javascript:history.back()',array('class'=>'fixed-return-url'));?>  
    <div class="login-reg-form">
        <h1><?php echo CHtml::link(zmf::config('sitename'),  zmf::config('baseurl'));?></h1>
        <div class="form-group">
            <span class="fixed-label"><i class="fa fa-lock"></i></span>
            <?php echo $form->passwordField($model,'password',array('class'=>'form-control','placeholder'=>'原始密码')); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
        <div class="form-group">
            <span class="fixed-label"><i class="fa fa-lock"></i></span>
            <?php echo $form->passwordField($model,'newPassword',array('class'=>'form-control','placeholder'=>'新密码（长度不小于6位）')); ?>
            <p class="help-block"></p>
            <?php echo $form->error($model,'newPassword'); ?>
        </div>
        <div class="form-group">
            <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '更新',array('class'=>'btn btn-success')); ?>
        </div>
    </div>
</div>    
    <?php }?>
    <?php $this->endWidget(); ?>
</div>