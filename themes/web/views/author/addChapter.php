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
    .edit-navbar {
        padding-right: 15px;
        padding-left: 15px;
        width: 100%;
        height: 60px;
        background: #faefc2;
        border-bottom: 1px solid #fff;
        line-height: 58px;
        color: #333;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 999
    }
    .edit-navbar .navbar-logo{
        font-size: 24px;
        float: left
    }
    .edit-navbar .navbar-logo a{
        color: #333
    }
    .edit-navbar .edit-navbar-center{
        width: 660px;
        margin: 0 auto
    }
    .edit-navbar .main-title{
        font-size: 18px;
        float: left;
        margin-right: 10px
    }
    .edit-navbar .main-status{
        float: left
    }
    .edit-navbar .edit-navbar-right{
        float: right;
    }
    .create-chapter-form{
        width:660px;
        padding: 0;
        margin: 0 auto;
        padding-top: 80px;
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
        width: 618px;
        overflow-x: hidden;
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
<header class="edit-navbar">
    <div class="navbar-logo">
        <?php echo CHtml::link(zmf::config('sitename'),  zmf::config('baseurl'));?>
    </div>
    <div class="edit-navbar-center">
        <div class="main-title">写文章</div>
        <div class="color-grey main-status" id="main-status">草稿自动保存</div>
    </div>
    <div class="edit-navbar-right">
        <div class="btn-group" role="group">
            <button type="button" id="preview-btn" class="btn btn-default" disabled="disabled">预览</button>
            <button type="button" id="submit-btn" class="btn btn-default" disabled="disabled">发布</button>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><?php echo CHtml::link('小说主页',array('book/view','id'=>$model->bid),array('target'=>'_blank'));?></li>
                    <li><?php echo CHtml::link('作者中心',array('author/view','id'=>$model->aid),array('target'=>'_blank'));?></li>
                    <li><?php echo CHtml::link('草稿箱',array('author/drafts'),array('target'=>'_blank'));?></li>
                </ul>
            </div>
        </div>
    </div>
</header>
<div class="create-chapter-form">
    <div class="form">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'add-chapter-form',
                'enableAjaxValidation'=>false,
        )); ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model,'title'); ?>
            <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'class'=>'form-control bitian','placeholder'=>'请输入章节标题')); ?>
            <?php echo $form->error($model,'title'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'content'); ?>
            <?php $this->renderPartial('//common/editor_addChapter', array('model' => $model,'content' => $model->content!='' ? Chapters::text($model->content) : '','hiddenToolbar'=>true,'editorWidth'=>620,'editorHeight'=>360,'from'=>'add-chapter')); ?>
            <?php echo $form->error($model,'content'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'postscript'); ?>
            <?php echo $form->textArea($model,'postscript',array('rows'=>2,'maxlength'=>512,'class'=>'form-control','placeholder'=>'关于本章节还有话说')); ?>
            <?php echo $form->error($model,'postscript'); ?>
        </div>
        <div class="form-group">
            <?php $scoreArr=  Chapters::exPsPosition('admin'); foreach ($scoreArr as $val=>$label){?>
            <label class="radio-inline"><input value="<?php echo $val;?>" type="radio" name="<?php echo CHtml::activeName($model, 'psPosition');?>" <?php if($model->psPosition==$val){?>checked="checked"<?php }?>> <?php echo $label;?></label>
            <?php }?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div><!-- form -->
<div class="footer-bg"></div>